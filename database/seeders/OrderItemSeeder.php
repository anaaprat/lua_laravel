<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;

class OrderItemSeeder extends Seeder
{
    public function run()
    {
        // Asegúrate de tener pedidos creados
        $orders = Order::all();
        $products = Product::all();

        foreach ($orders as $order) {
            // Añade 2 productos aleatorios por pedido
            $items = $products->random(2);
            foreach ($items as $product) {
                $quantity = rand(1, 3);
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'subtotal' => $quantity * $product->price,
                ]);
            }
        }
    }
}