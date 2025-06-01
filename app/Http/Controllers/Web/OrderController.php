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

        if ($bar->table_number && $tableNumber > $bar->table_number) {
            return response()->json([
                'message' => 'Número de mesa no válido para este bar',
            ], 422);
        }

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

            if ($user->credit < $total) {
                throw new \Exception("Saldo insuficiente. Necesitas {$total} €, pero solo tienes {$user->credit} €");
            }

            $order = Order::create([
                'user_id' => $user->id,
                'bar_id' => $barId,
                'total' => $total,
                'status' => 'pending'
            ]);

            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal']
                ]);
            }

            Movement::create([
                'user_id' => $user->id,
                'bar_id' => $barId,
                'amount' => -$total
            ]);

            $user->credit -= $total;
            $user->save();

            foreach ($productDetails as $detail) {
                $detail['barProduct']->stock -= $detail['quantity'];
                $detail['barProduct']->save();
            }

            if (class_exists('App\Models\Ranking')) {
                foreach ($productDetails as $detail) {
                    if ($detail['barProduct']->product->is_drink) {
                        $rankings = DB::table('ranking_users')
                            ->where('user_id', $user->id)
                            ->get();

                        foreach ($rankings as $ranking) {
                            DB::table('ranking_users')
                                ->where('ranking_id', $ranking->ranking_id)
                                ->where('user_id', $user->id)
                                ->increment('points', $detail['quantity']);
                        }
                    }
                }
            }

            DB::commit();

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

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Order completed successfully']);
        }

        return back()->with('success', 'Order completed successfully');
    }

    public function markAsPending(Order $order)
    {
        if ($order->bar_id !== auth()->id())
            abort(403);

        $order->status = 'pending';
        $order->save();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Order moved to pending']);
        }

        return back()->with('success', 'Order moved to pending');
    }

    public function cancel(Order $order)
    {
        if ($order->bar_id !== auth()->id())
            abort(403);

        $order->status = 'canceled';
        $order->save();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Order canceled']);
        }

        return back()->with('success', 'Order canceled');
    }
}