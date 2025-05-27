<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Movement;
use App\Models\Order;
use App\Models\BarProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // Dashboard principal
    public function dashboard()
    {
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_bars' => User::where('role', 'bar')->count(),
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
        $users = User::where('role', 'user')->get();
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
        $user->update(['deleted' => true, 'is_active' => false]);

        return redirect()->route('admin.users')->with('success', 'Usuario eliminado correctamente');
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
        $bars = User::where('role', 'bar')->get();
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
        $bar->update(['deleted' => true, 'is_active' => false]);

        return redirect()->route('admin.bars')->with('success', 'Bar eliminado correctamente');
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|max:255',
            'is_drink' => 'required|boolean',
            'image_url' => 'nullable|url',
        ]);

        Product::create($validated);

        return redirect()->route('admin.products')->with('success', 'Producto creado correctamente');
    }

    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|max:255',
            'is_drink' => 'required|boolean',
            'image_url' => 'nullable|url',
        ]);

        $product->update($validated);

        return redirect()->route('admin.products')->with('success', 'Producto actualizado correctamente');
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);

        // Verificar si el producto está siendo usado en algún bar
        $barProductsCount = BarProduct::where('product_id', $id)->count();

        if ($barProductsCount > 0) {
            return redirect()->route('admin.products')
                ->with('error', 'No se puede eliminar el producto porque está siendo usado en bares');
        }

        $product->delete();

        return redirect()->route('admin.products')->with('success', 'Producto eliminado correctamente');
    }

    // ================ VISUALIZACIÓN DE MOVIMIENTOS ================
    public function movements(Request $request)
    {
        $query = Movement::with(['user', 'bar']);

        // Filtros
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

        // Generar código único
        $code = strtoupper(\Illuminate\Support\Str::random(6));

        // Verificar que el código sea único
        while (\App\Models\Ranking::where('code', $code)->exists()) {
            $code = strtoupper(\Illuminate\Support\Str::random(6));
        }

        $ranking = \App\Models\Ranking::create([
            'name' => $validated['name'],
            'code' => $code,
            'creator_id' => $validated['creator_id'],
        ]);

        // Añadir usuarios al ranking
        $users = $validated['users'] ?? [];
        if (!in_array($validated['creator_id'], $users)) {
            $users[] = $validated['creator_id']; // Añadir al creador si no está incluido
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

        // Actualizar usuarios del ranking
        if (isset($validated['users'])) {
            $currentUsers = $ranking->users->pluck('id')->toArray();
            $newUsers = $validated['users'];

            // Usuarios a añadir
            $usersToAdd = array_diff($newUsers, $currentUsers);
            foreach ($usersToAdd as $userId) {
                $ranking->users()->attach($userId, ['points' => 0, 'month_record' => 0]);
            }

            // Usuarios a eliminar
            $usersToRemove = array_diff($currentUsers, $newUsers);
            $ranking->users()->detach($usersToRemove);
        }

        return redirect()->route('admin.rankings')->with('success', 'Ranking actualizado correctamente');
    }

    public function deleteRanking($id)
    {
        $ranking = \App\Models\Ranking::findOrFail($id);

        // Eliminar todas las relaciones
        $ranking->users()->detach();

        // Eliminar el ranking
        $ranking->delete();

        return redirect()->route('admin.rankings')->with('success', 'Ranking eliminado correctamente');
    }

    public function resetRankingPoints($id)
    {
        $ranking = \App\Models\Ranking::findOrFail($id);

        // Guardar récords mensuales antes de resetear
        \Illuminate\Support\Facades\DB::table('ranking_users')
            ->where('ranking_id', $ranking->id)
            ->where('points', '>', \Illuminate\Support\Facades\DB::raw('month_record'))
            ->update(['month_record' => \Illuminate\Support\Facades\DB::raw('points')]);

        // Resetear puntos
        \Illuminate\Support\Facades\DB::table('ranking_users')
            ->where('ranking_id', $ranking->id)
            ->update(['points' => 0]);

        return redirect()->route('admin.rankings')->with('success', 'Puntos del ranking reseteados correctamente');
    }

    public function resetAllRankings()
    {
        // Guardar récords mensuales
        \Illuminate\Support\Facades\DB::table('ranking_users')
            ->where('points', '>', \Illuminate\Support\Facades\DB::raw('month_record'))
            ->update(['month_record' => \Illuminate\Support\Facades\DB::raw('points')]);

        // Reiniciar puntos
        \Illuminate\Support\Facades\DB::table('ranking_users')->update(['points' => 0]);

        return redirect()->route('admin.rankings')->with('success', 'Todos los rankings han sido reseteados correctamente');
    }
}