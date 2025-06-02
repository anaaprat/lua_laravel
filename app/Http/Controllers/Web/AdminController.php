<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Movement;
use App\Models\Order;
use App\Models\BarProduct;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::where('role', 'user')->where('deleted', 0)->count(),
            'total_bars' => User::where('role', 'bar')->where('deleted', 0)->count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_movements' => Movement::sum('amount'),
        ];

        $recent_orders = Order::with(['user', 'bar', 'items.product'])
            ->latest()
            ->take(5)
            ->get();

        $recent_movements = Movement::with(['user', 'bar'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_orders', 'recent_movements'));
    }

    // ================ GESTIÓN DE USUARIOS ================
    public function users()
    {
        $users = User::where('role', 'user')
            ->where('deleted', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'credit' => 'nullable|numeric|min:0',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'credit' => $validated['credit'] ?? 0,
            'token' => substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10),
            'is_active' => true,
            'deleted' => false,
        ]);

        return redirect()->route('admin.users')->with('success', 'Usuario creado correctamente');
    }

    public function editUser($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::where('role', 'user')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|min:6',
            'credit' => 'nullable|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'credit' => $validated['credit'] ?? $user->credit,
            'is_active' => $validated['is_active'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('admin.users')->with('success', 'Usuario actualizado correctamente');
    }


    public function deleteUser($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);

        try {
            DB::beginTransaction();

            $pendingOrders = Order::where('user_id', $id)->where('status', 'pending')->get();

            foreach ($pendingOrders as $order) {
                foreach ($order->items as $item) {
                    $barProduct = BarProduct::where('user_id', $order->bar_id)
                        ->where('product_id', $item->product_id)
                        ->first();

                    if ($barProduct) {
                        $barProduct->stock += $item->quantity;
                        $barProduct->save();
                    }
                }

                $order->update(['status' => 'canceled']);
            }

            if (class_exists('App\Models\Ranking')) {
                $rankingsCount = DB::table('ranking_users')->where('user_id', $id)->count();
                DB::table('ranking_users')->where('user_id', $id)->delete();
            }
            $user->update([
                'deleted' => true,
                'is_active' => false,
                'email' => $user->email . '_deleted_' . time(),
                'token' => null
            ]);

            DB::commit();

            return redirect()->route('admin.users')->with('success', 'Usuario eliminado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('admin.users')->with(
                'error',
                'Error al eliminar el usuario: ' . $e->getMessage()
            );
        }
    }

    public function toggleUserStatus($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activado' : 'desactivado';
        return redirect()->route('admin.users')->with('success', "Usuario {$status} correctamente");
    }

    // ================ GESTIÓN DE BARES ================
    public function bars()
    {
        $bars = User::where('role', 'bar')
            ->where('deleted', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return view('admin.bars.index', compact('bars'));
    }

    public function createBar()
    {
        return view('admin.bars.create');
    }

    public function storeBar(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'table_number' => 'nullable|integer|min:1',
        ]);

        $token = Str::uuid();

        $bar = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'bar',
            'token' => $token,
            'table_number' => $validated['table_number'],
            'is_active' => true,
            'deleted' => false,
        ]);

        // Generar QR
        $qr = QrCode::format('svg')->size(300)->generate($token);
        $filePath = 'qrs/bar_' . Str::slug($bar->name) . '.svg';
        Storage::disk('public')->put($filePath, $qr);

        $bar->update(['qr_path' => $filePath]);

        return redirect()->route('admin.bars')->with('success', 'Bar creado correctamente');
    }

    public function editBar($id)
    {
        $bar = User::where('role', 'bar')->findOrFail($id);
        return view('admin.bars.edit', compact('bar'));
    }

    public function updateBar(Request $request, $id)
    {
        $bar = User::where('role', 'bar')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($bar->id)],
            'password' => 'nullable|min:6',
            'table_number' => 'nullable|integer|min:1',
            'is_active' => 'required|boolean',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'table_number' => $validated['table_number'],
            'is_active' => $validated['is_active'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $bar->update($updateData);

        return redirect()->route('admin.bars')->with('success', 'Bar actualizado correctamente');
    }

    public function deleteBar($id)
    {
        $bar = User::where('role', 'bar')->findOrFail($id);

        try {
            DB::beginTransaction();

            $pendingOrders = Order::where('bar_id', $id)->where('status', 'pending')->get();

            foreach ($pendingOrders as $order) {
                Log::info('Cancelando pedido pendiente', ['order_id' => $order->id]);

                foreach ($order->items as $item) {
                    $barProduct = BarProduct::where('user_id', $id)
                        ->where('product_id', $item->product_id)
                        ->first();

                    if ($barProduct) {
                        $barProduct->stock += $item->quantity;
                        $barProduct->save();
                        Log::info('Stock restaurado', [
                            'product_id' => $item->product_id,
                            'quantity_restored' => $item->quantity,
                            'new_stock' => $barProduct->stock
                        ]);
                    }
                }

                if ($order->user && !$order->user->deleted) {
                    $order->user->credit += $order->total;
                    $order->user->save();

                    Movement::create([
                        'user_id' => $order->user_id,
                        'bar_id' => $id,
                        'amount' => $order->total,
                        'description' => 'Reembolso por eliminación de bar'
                    ]);

                    Log::info('Usuario reembolsado', [
                        'user_id' => $order->user_id,
                        'refund_amount' => $order->total,
                        'new_balance' => $order->user->credit
                    ]);
                }

                $this->revertRankingPointsForOrder($order);
            }

            $totalOrders = Order::where('bar_id', $id)->count();
            Order::where('bar_id', $id)->delete();
            Log::info('Pedidos eliminados', ['total_orders_deleted' => $totalOrders]);

            $totalBarProducts = BarProduct::where('user_id', $id)->count();
            BarProduct::where('user_id', $id)->delete();
            Log::info('Productos del bar eliminados', ['total_bar_products_deleted' => $totalBarProducts]);

            $totalMovements = Movement::where('bar_id', $id)->count();
            Movement::where('bar_id', $id)->delete();
            Log::info('Movimientos eliminados', ['total_movements_deleted' => $totalMovements]);

            //Borrar el QR 
            if ($bar->qr_path && Storage::disk('public')->exists($bar->qr_path)) {
                Storage::disk('public')->delete($bar->qr_path);
                Log::info('Código QR eliminado', ['qr_path' => $bar->qr_path]);
            }

            $bar->update([
                'deleted' => true,
                'is_active' => false,
                'token' => null, 
                'qr_path' => null
            ]);

            DB::commit();

            Log::info('Bar eliminado exitosamente en cascada', [
                'bar_id' => $id,
                'orders_deleted' => $totalOrders,
                'bar_products_deleted' => $totalBarProducts,
                'movements_deleted' => $totalMovements
            ]);

            return redirect()->route('admin.bars')->with(
                'success',
                "Bar eliminado correctamente. Se eliminaron {$totalOrders} pedidos, {$totalBarProducts} productos y {$totalMovements} movimientos."
            );

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error eliminando bar en cascada', [
                'bar_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('admin.bars')->with(
                'error',
                'Error al eliminar el bar: ' . $e->getMessage()
            );
        }
    }

    private function revertRankingPointsForOrder($order)
    {
        if (!class_exists('App\Models\Ranking')) {
            return;
        }

        try {
            foreach ($order->items as $item) {
                if ($item->product && $item->product->is_drink) {
                    $rankings = DB::table('ranking_users')
                        ->where('user_id', $order->user_id)
                        ->get();

                    foreach ($rankings as $ranking) {
                        DB::table('ranking_users')
                            ->where('ranking_id', $ranking->ranking_id)
                            ->where('user_id', $order->user_id)
                            ->decrement('points', $item->quantity);

                        Log::info('Puntos de ranking revertidos', [
                            'user_id' => $order->user_id,
                            'ranking_id' => $ranking->ranking_id,
                            'points_removed' => $item->quantity
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::warning('Error revirtiendo puntos de ranking', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function toggleBarStatus($id)
    {
        $bar = User::where('role', 'bar')->findOrFail($id);
        $bar->update(['is_active' => !$bar->is_active]);

        $status = $bar->is_active ? 'activado' : 'desactivado';
        return redirect()->route('admin.bars')->with('success', "Bar {$status} correctamente");
    }

    // ================ GESTIÓN DE PRODUCTOS ================
    public function products()
    {
        $products = Product::with('barProducts')->get();
        return view('admin.products.index', compact('products'));
    }

    public function createProduct()
    {
        return view('admin.products.create');
    }

    public function storeProduct(Request $request)
    {
        Log::info('=== CREANDO PRODUCTO ===');
        Log::info('Datos recibidos:', $request->all());
        Log::info('Archivos recibidos:' . $request->hasFile('image_file') ? 'SÍ' : 'NO');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|string|max:255',
            'is_drink' => 'required|boolean',
            'image_url' => 'nullable|url|max:500',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', 
        ]);

        Log::info('Datos validados:', $validated);

        $imageUrl = null;

        try {
            if ($request->hasFile('image_file')) {
                Log::info('Procesando archivo de imagen...');

                $image = $request->file('image_file');
                Log::info('Archivo de imagen:', [
                    'name' => $image->getClientOriginalName(),
                    'size' => $image->getSize(),
                    'mime' => $image->getMimeType(),
                ]);

                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                if (!Storage::disk('public')->exists('products')) {
                    Storage::disk('public')->makeDirectory('products');
                    Log::info('Directorio products creado');
                }

                $imagePath = $image->storeAs('products', $imageName, 'public');
                Log::info('Imagen guardada en:' . $imagePath);

                $imageUrl = asset('storage/' . $imagePath);
                Log::info('URL de imagen generada:' . $imageUrl);

            } elseif (!empty($validated['image_url'])) {
                $imageUrl = $validated['image_url'];
                Log::info('Usando URL proporcionada:', $imageUrl);
            }

            $product = Product::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'type' => $validated['type'],
                'is_drink' => $validated['is_drink'],
                'image_url' => $imageUrl,
            ]);

            Log::info('Producto creado exitosamente:', $product->toArray());

            return redirect()->route('admin.products')->with('success', 'Producto creado correctamente');

        } catch (\Exception $e) {
            Log::error('Error creando producto:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el producto: ' . $e->getMessage());
        }
    }

    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        Log::info('=== ACTUALIZANDO PRODUCTO ===');
        Log::info('Producto ID:', $id);
        Log::info('Datos recibidos:', $request->all());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|string|max:255',
            'is_drink' => 'required|boolean',
            'image_url' => 'nullable|url|max:500',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        try {
            $imageUrl = $product->image_url;

            if ($request->hasFile('image_file')) {
                Log::info('Procesando nueva imagen...');

                if ($product->image_url && str_contains($product->image_url, 'storage/products/')) {
                    $oldImagePath = str_replace(asset('storage/'), '', $product->image_url);
                    if (Storage::disk('public')->exists($oldImagePath)) {
                        Storage::disk('public')->delete($oldImagePath);
                        Log::info('Imagen anterior eliminada:', $oldImagePath);
                    }
                }

                $image = $request->file('image_file');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('products', $imageName, 'public');
                $imageUrl = asset('storage/' . $imagePath);

                Log::info('Nueva imagen guardada:' . $imageUrl);

            } elseif (!empty($validated['image_url']) && $validated['image_url'] !== $product->image_url) {

                if ($product->image_url && str_contains($product->image_url, 'storage/products/')) {
                    $oldImagePath = str_replace(asset('storage/'), '', $product->image_url);
                    if (Storage::disk('public')->exists($oldImagePath)) {
                        Storage::disk('public')->delete($oldImagePath);
                        Log::info('Imagen anterior eliminada por nueva URL');
                    }
                }

                $imageUrl = $validated['image_url'];
                Log::info('Usando nueva URL:', $imageUrl);
            }

            $product->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'type' => $validated['type'],
                'is_drink' => $validated['is_drink'],
                'image_url' => $imageUrl,
            ]);

            Log::info('Producto actualizado exitosamente');

            return redirect()->route('admin.products')->with('success', 'Producto actualizado correctamente');

        } catch (\Exception $e) {
            Log::error('Error actualizando producto:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el producto: ' . $e->getMessage());
        }
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);

        $barProductsCount = BarProduct::where('product_id', $id)->count();

        if ($barProductsCount > 0) {
            return redirect()->route('admin.products')
                ->with('error', 'No se puede eliminar el producto porque está siendo usado en bares');
        }

        try {
            if ($product->image_url && str_contains($product->image_url, 'storage/products/')) {
                $imagePath = str_replace(asset('storage/'), '', $product->image_url);
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }

            $product->delete();

            return redirect()->route('admin.products')->with('success', 'Producto eliminado correctamente');

        } catch (\Exception $e) {
            return redirect()->route('admin.products')
                ->with('error', 'Error al eliminar el producto: ' . $e->getMessage());
        }
    }

    // ================ VISUALIZACIÓN DE MOVIMIENTOS ================
    public function movements(Request $request)
    {
        $query = Movement::with(['user', 'bar']);

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        if ($request->filled('type')) {
            if ($request->type === 'positive') {
                $query->where('amount', '>', 0);
            } elseif ($request->type === 'negative') {
                $query->where('amount', '<', 0);
            }
        }

        $movements = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total_amount' => Movement::sum('amount'),
            'positive_amount' => Movement::where('amount', '>', 0)->sum('amount'),
            'negative_amount' => Movement::where('amount', '<', 0)->sum('amount'),
            'total_movements' => Movement::count(),
        ];

        return view('admin.movements.index', compact('movements', 'stats'));
    }

    // ================ VISUALIZACIÓN DE PEDIDOS ================
    public function orders(Request $request)
    {
        $query = Order::with(['user', 'bar', 'items.product']);

        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('bar_id')) {
            $query->where('bar_id', $request->bar_id);
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        $bars = User::where('role', 'bar')->where('is_active', true)->get();

        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'canceled_orders' => Order::where('status', 'canceled')->count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total'),
        ];

        return view('admin.orders.index', compact('orders', 'bars', 'stats'));
    }

    // ================ GESTIÓN DE RANKINGS ================
    public function rankings()
    {
        $rankings = \App\Models\Ranking::with([
            'creator',
            'users' => function ($query) {
                $query->orderBy('ranking_users.points', 'desc');
            }
        ])->withCount('users')->orderBy('created_at', 'desc')->get();

        $stats = [
            'total_rankings' => \App\Models\Ranking::count(),
            'total_participants' => \Illuminate\Support\Facades\DB::table('ranking_users')->distinct('user_id')->count(),
            'most_active_ranking' => \App\Models\Ranking::withCount('users')->orderBy('users_count', 'desc')->first(),
            'highest_score' => \Illuminate\Support\Facades\DB::table('ranking_users')->max('points'),
        ];

        return view('admin.rankings.index', compact('rankings', 'stats'));
    }

    public function showRanking($id)
    {
        $ranking = \App\Models\Ranking::with([
            'creator',
            'users' => function ($query) {
                $query->orderBy('ranking_users.points', 'desc');
            }
        ])->findOrFail($id);

        return view('admin.rankings.show', compact('ranking'));
    }

    public function createRanking()
    {
        $users = User::where('role', 'user')->where('is_active', true)->get();
        return view('admin.rankings.create', compact('users'));
    }

    public function storeRanking(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'creator_id' => 'required|exists:users,id',
            'users' => 'array',
            'users.*' => 'exists:users,id',
        ]);

        $code = strtoupper(\Illuminate\Support\Str::random(6));

        while (\App\Models\Ranking::where('code', $code)->exists()) {
            $code = strtoupper(\Illuminate\Support\Str::random(6));
        }

        $ranking = \App\Models\Ranking::create([
            'name' => $validated['name'],
            'code' => $code,
            'creator_id' => $validated['creator_id'],
        ]);

        $users = $validated['users'] ?? [];
        if (!in_array($validated['creator_id'], $users)) {
            $users[] = $validated['creator_id']; 
        }

        foreach ($users as $userId) {
            $ranking->users()->attach($userId, ['points' => 0, 'month_record' => 0]);
        }

        return redirect()->route('admin.rankings')->with('success', 'Ranking creado correctamente');
    }

    public function editRanking($id)
    {
        $ranking = \App\Models\Ranking::with('users')->findOrFail($id);
        $users = User::where('role', 'user')->where('is_active', true)->get();
        return view('admin.rankings.edit', compact('ranking', 'users'));
    }

    public function updateRanking(Request $request, $id)
    {
        $ranking = \App\Models\Ranking::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'users' => 'array',
            'users.*' => 'exists:users,id',
        ]);

        $ranking->update(['name' => $validated['name']]);

        if (isset($validated['users'])) {
            $currentUsers = $ranking->users->pluck('id')->toArray();
            $newUsers = $validated['users'];

            $usersToAdd = array_diff($newUsers, $currentUsers);
            foreach ($usersToAdd as $userId) {
                $ranking->users()->attach($userId, ['points' => 0, 'month_record' => 0]);
            }

            $usersToRemove = array_diff($currentUsers, $newUsers);
            $ranking->users()->detach($usersToRemove);
        }

        return redirect()->route('admin.rankings')->with('success', 'Ranking actualizado correctamente');
    }

    public function deleteRanking($id)
    {
        $ranking = \App\Models\Ranking::findOrFail($id);

        $ranking->users()->detach();

        $ranking->delete();

        return redirect()->route('admin.rankings')->with('success', 'Ranking eliminado correctamente');
    }

    public function resetRankingPoints($id)
    {
        $ranking = \App\Models\Ranking::findOrFail($id);

        \Illuminate\Support\Facades\DB::table('ranking_users')
            ->where('ranking_id', $ranking->id)
            ->where('points', '>', \Illuminate\Support\Facades\DB::raw('month_record'))
            ->update(['month_record' => \Illuminate\Support\Facades\DB::raw('points')]);

        \Illuminate\Support\Facades\DB::table('ranking_users')
            ->where('ranking_id', $ranking->id)
            ->update(['points' => 0]);

        return redirect()->route('admin.rankings')->with('success', 'Puntos del ranking reseteados correctamente');
    }

    public function resetAllRankings()
    {
        \Illuminate\Support\Facades\DB::table('ranking_users')
            ->where('points', '>', \Illuminate\Support\Facades\DB::raw('month_record'))
            ->update(['month_record' => \Illuminate\Support\Facades\DB::raw('points')]);

        \Illuminate\Support\Facades\DB::table('ranking_users')->update(['points' => 0]);

        return redirect()->route('admin.rankings')->with('success', 'Todos los rankings han sido reseteados correctamente');
    }
}