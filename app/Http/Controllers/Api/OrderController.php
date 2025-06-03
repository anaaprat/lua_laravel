<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\BarProduct;
use App\Models\Movement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();
        \Log::info('=== NUEVA PETICIÓN DE PEDIDO ===');
        \Log::info('Usuario autenticado:', [
            'user_id' => $user ? $user->id : 'NULL',
            'user_name' => $user ? $user->name : 'NULL',
            'user_role' => $user ? $user->role : 'NULL',
            'user_credit' => $user ? $user->credit : 'NULL'
        ]);
        \Log::info('Datos recibidos:', $request->all());

        if (!$user) {
            \Log::error('No hay usuario autenticado en la petición de orders');
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'bar_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'table_number' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            \Log::error('Errores de validación:', $validator->errors()->toArray());
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $barId = $request->bar_id;
        $items = $request->items;
        $tableNumber = $request->table_number;

        $bar = User::where('id', $barId)
            ->where('role', 'bar')
            ->where('is_active', true)
            ->where('deleted', false)
            ->first();

        if (!$bar) {
            \Log::error('Bar no encontrado o no activo:', ['bar_id' => $barId]);
            return response()->json([
                'message' => 'Bar no válido',
            ], 404);
        }

        \Log::info('Bar encontrado:', [
            'bar_id' => $bar->id,
            'bar_name' => $bar->name,
            'table_number' => $bar->table_number
        ]);

        if ($bar->table_number && $tableNumber > $bar->table_number) {
            \Log::error('Número de mesa no válido:', [
                'requested_table' => $tableNumber,
                'max_tables' => $bar->table_number
            ]);
            return response()->json([
                'message' => 'Número de mesa no válido',
            ], 422);
        }

        $total = 0;
        $orderItems = [];

        try {
            DB::beginTransaction();

            foreach ($items as $item) {
                \Log::info('Procesando producto:', $item);

                $barProduct = BarProduct::where('user_id', $barId)
                    ->where('product_id', $item['product_id'])
                    ->where('available', true)
                    ->with('product')
                    ->first();

                if (!$barProduct) {
                    throw new \Exception("Producto no disponible en este bar: {$item['product_id']}");
                }

                \Log::info('Producto encontrado:', [
                    'product_name' => $barProduct->product->name,
                    'price' => $barProduct->price,
                    'stock' => $barProduct->stock,
                    'requested_quantity' => $item['quantity']
                ]);

                if ($barProduct->stock < $item['quantity']) {
                    throw new \Exception("Stock insuficiente para el producto: {$barProduct->product->name}");
                }

                $subtotal = $barProduct->price * $item['quantity'];
                $total += $subtotal;

                $orderItems[] = [
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal
                ];

+                $barProduct->stock -= $item['quantity'];
                $barProduct->save();

                \Log::info('Stock actualizado:', [
                    'product_name' => $barProduct->product->name,
                    'new_stock' => $barProduct->stock
                ]);
            }

            \Log::info('Total calculado:', ['total' => $total, 'user_credit' => $user->credit]);

            if ($user->credit < $total) {
                throw new \Exception("Saldo insuficiente. Necesitas {$total} €, pero solo tienes {$user->credit} €");
            }

            $orderData = [
                'user_id' => $user->id,
                'bar_id' => $barId,
                'total' => $total,
                'status' => 'pending',
            ];

            try {
                $orderData['table_number'] = $tableNumber;
                $order = Order::create($orderData);
            } catch (\Exception $e) {
                if (str_contains($e->getMessage(), 'table_number')) {
                    \Log::warning('Columna table_number no existe, creando pedido sin ella');
                    unset($orderData['table_number']);
                    $order = Order::create($orderData);
                } else {
                    throw $e;
                }
            }

            \Log::info('Pedido creado:', ['order_id' => $order->id]);

            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            Movement::create([
                'user_id' => $user->id,
                'bar_id' => $barId,
                'amount' => -$total 
            ]);

            $user->credit -= $total;
            $user->save();

            \Log::info('Saldo actualizado:', ['new_credit' => $user->credit]);

            if (class_exists('App\Models\Ranking')) {
                foreach ($orderItems as $item) {
                    $product = \App\Models\Product::find($item['product_id']);
                    if ($product && $product->is_drink) {
                        $rankings = DB::table('ranking_users')
                            ->where('user_id', $user->id)
                            ->get();

                        foreach ($rankings as $ranking) {
                            DB::table('ranking_users')
                                ->where('ranking_id', $ranking->ranking_id)
                                ->where('user_id', $user->id)
                                ->increment('points', $item['quantity']);
                        }
                    }
                }
            }

            DB::commit();

            $order = Order::with(['bar:id,name', 'items.product'])
                ->find($order->id);

            \Log::info('Pedido completado exitosamente:', ['order_id' => $order->id]);

            return response()->json([
                'message' => 'Pedido creado correctamente',
                'order' => $order,
                'new_balance' => $user->credit
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Error al crear el pedido:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function history(Request $request)
    {
        $user = $request->user();

        $orders = Order::with(['bar:id,name', 'items.product'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'orders' => $orders,
        ]);
    }

    public function show(Request $request, $orderId)
    {
        $user = $request->user();

        $order = Order::with(['bar:id,name', 'items.product'])
            ->where('id', $orderId)
            ->where('user_id', $user->id)
            ->first();

        if (!$order) {
            return response()->json([
                'message' => 'Pedido no encontrado',
            ], 404);
        }

        return response()->json([
            'order' => $order,
        ]);
    }
}