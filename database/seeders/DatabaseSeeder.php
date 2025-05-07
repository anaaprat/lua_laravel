<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\BarProduct;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Movement;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Asumimos que ya existen bares con IDs del 2 al 4
        $bars = User::whereIn('id', [2, 3, 4, 5])->get();

        // Crear productos manualmente
        $productData = [
            ['name' => 'Gin Tonic', 'is_drink' => 1, 'type' => 'other'],
            ['name' => 'Mojito', 'is_drink' => 1, 'type' => 'other'],
            ['name' => 'Hamburger', 'is_drink' => 0, 'type' => 'other'],
            ['name' => 'Nachos', 'is_drink' => 0, 'type' => 'other'],
            ['name' => 'Coca-Cola', 'is_drink' => 1, 'type' => 'other'],
        ];

        foreach ($productData as $data) {
            Product::create(array_merge($data, [
                'description' => $data['name'] . ' description',
                'image_url' => null,
            ]));
        }

        $products = Product::all();

        // // Asociar productos a bares (BarProduct)
        // foreach ($bars as $bar) {
        //     foreach ($products->random(5) as $product) {
        //         BarProduct::create([
        //             'user_id' => $bar->id,
        //             'product_id' => $product->id,
        //             'price' => rand(2, 20),
        //             'stock' => rand(0, 50),
        //             'available' => rand(0, 1),
        //         ]);
        //     }
        // }

        // Crear usuarios normales (clientes)
        for ($i = 0; $i < 10; $i++) {
            User::create([
                'name' => 'Client ' . ($i + 1),
                'email' => 'client' . ($i + 1) . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'credit' => rand(10, 100),
                'token' => Str::random(10),
                'is_active' => true,
                'deleted' => false,
            ]);
        }

        $users = User::where('role', 'user')->get();

        // Crear órdenes por usuario en bares
        foreach ($users as $user) {
            $bar = $bars->random();
            $order = Order::create([
                'user_id' => $user->id,
                'bar_id' => $bar->id,
                'total' => 0,
                'status' => 'pending',
            ]);

            $total = 0;
            foreach ($products->random(2) as $product) {
                $price = BarProduct::where('user_id', $bar->id)->where('product_id', $product->id)->value('price') ?? 5;
                $quantity = rand(1, 3);
                $subtotal = $price * $quantity;
                $total += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ]);
            }

            $order->update(['total' => $total]);
        }
    }
}