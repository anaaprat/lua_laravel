<?php

namespace App\Http\Controllers\API;

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
    // Crear un nuevo pedido
    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
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
                  ->where('is_active', true)
                  ->where('deleted', false)
                  ->first();
                  
        if (!$bar) {
            return response()->json([
                'message' => 'Bar no válido',
            ], 404);
        }
        
        // Validar que el número de mesa no excede las disponibles en el bar
        if ($bar->table_number && $tableNumber > $bar->table_number) {
            return response()->json([
                'message' => 'Número de mesa no válido',
            ], 422);
        }

        // Calcular el total del pedido y validar disponibilidad de productos
        $total = 0;
        $orderItems = [];
        
        try {
            DB::beginTransaction();
            
            foreach ($items as $item) {
                $barProduct = BarProduct::where('user_id', $barId)
                              ->where('product_id', $item['product_id'])
                              ->where('available', true)
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
                
                // Reducir stock
                $barProduct->stock -= $item['quantity'];
                $barProduct->save();
            }
            
            // Verificar saldo suficiente
            if ($user->credit < $total) {
                throw new \Exception("Saldo insuficiente. Necesitas {$total} €");
            }
            
            // Crear el pedido
            $order = Order::create([
                'user_id' => $user->id,
                'bar_id' => $barId,
                'total' => $total,
                'status' => 'pending',
                'table_number' => $tableNumber, // Añadir este campo si no está en la migración
            ]);
            
            // Crear los items del pedido
            foreach ($orderItems as $item) {
                $order->items()->create($item);
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
            
            DB::commit();
            
            return response()->json([
                'message' => 'Pedido creado correctamente',
                'order' => $order->load('items.product'),
                'new_balance' => $user->credit
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    // Obtener el historial de pedidos del usuario
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

    // Obtener detalles de un pedido específico
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