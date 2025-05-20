<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\BarProduct;
use App\Models\Movement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Crear un nuevo pedido
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bar_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'table_number' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        $barId = $request->bar_id;
        $items = $request->items;
        $tableNumber = $request->table_number;

        // Validar que el bar existe y está activo
        $bar = User::where('id', $barId)
            ->where('role', 'bar')
            ->where('is_active', 1)
            ->where('deleted', 0)
            ->first();

        if (!$bar) {
            return response()->json([
                'message' => 'Bar no válido o no activo',
            ], 404);
        }

        // Validar que el número de mesa no excede las disponibles en el bar
        if ($bar->table_number && $tableNumber > $bar->table_number) {
            return response()->json([
                'message' => 'Número de mesa no válido para este bar',
            ], 422);
        }

        // Calcular el total del pedido y validar disponibilidad de productos
        $total = 0;
        $orderItems = [];
        $productDetails = [];

        try {
            DB::beginTransaction();

            foreach ($items as $item) {
                $barProduct = BarProduct::with('product')
                    ->where('user_id', $barId)
                    ->where('product_id', $item['product_id'])
                    ->where('available', 1)
                    ->first();

                if (!$barProduct) {
                    throw new \Exception("Producto no disponible en este bar: {$item['product_id']}");
                }

                // Verificar stock
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

                $productDetails[] = [
                    'barProduct' => $barProduct,
                    'quantity' => $item['quantity']
                ];
            }

            // Verificar saldo suficiente
            if ($user->credit < $total) {
                throw new \Exception("Saldo insuficiente. Necesitas {$total} €, pero solo tienes {$user->credit} €");
            }

            // Crear el pedido
            $order = Order::create([
                'user_id' => $user->id,
                'bar_id' => $barId,
                'total' => $total,
                'status' => 'pending'
            ]);

            // Crear los items del pedido
            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal']
                ]);
            }

            // Registrar el movimiento (pago)
            Movement::create([
                'user_id' => $user->id,
                'bar_id' => $barId,
                'amount' => -$total // Monto negativo porque es un pago
            ]);

            // Actualizar el saldo del usuario
            $user->credit -= $total;
            $user->save();

            // Reducir stock de productos
            foreach ($productDetails as $detail) {
                $detail['barProduct']->stock -= $detail['quantity'];
                $detail['barProduct']->save();
            }

            // Actualizar puntos en rankings para bebidas (si existe la funcionalidad)
            if (class_exists('App\Models\Ranking')) {
                foreach ($productDetails as $detail) {
                    // Si el producto es una bebida, sumar puntos en rankings
                    if ($detail['barProduct']->product->is_drink) {
                        // Buscar todos los rankings a los que pertenece el usuario
                        $rankings = DB::table('ranking_users')
                            ->where('user_id', $user->id)
                            ->get();

                        foreach ($rankings as $ranking) {
                            // Actualizar puntos
                            DB::table('ranking_users')
                                ->where('ranking_id', $ranking->ranking_id)
                                ->where('user_id', $user->id)
                                ->increment('points', $detail['quantity']);
                        }
                    }
                }
            }

            DB::commit();

            // Cargar los detalles completos del pedido
            $order = Order::with(['bar:id,name', 'items.product'])
                ->find($order->id);

            return response()->json([
                'message' => 'Pedido creado correctamente',
                'order' => $order,
                'new_balance' => $user->credit
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Error al crear el pedido: ' . $e->getMessage(),
            ], 422);
        }
    }

    public function markAsCompleted(Order $order)
    {
        if ($order->bar_id !== auth()->id())
            abort(403);
        $order->status = 'completed';
        $order->save();
        return back();
    }

    public function markAsPending(Order $order)
    {
        if ($order->bar_id !== auth()->id())
            abort(403);
        $order->status = 'pending';
        $order->save();
        return back();
    }

    public function cancel(Order $order)
    {
        if ($order->bar_id !== auth()->id())
            abort(403);
        $order->status = 'canceled';
        $order->save();
        return back();
    }
}