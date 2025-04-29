<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BarProduct;
use App\Models\User;
use App\Models\Product;

class BarProductSeeder extends Seeder
{
    public function run(): void
    {
        $bars = User::where('role', 'bar')->get();
        $products = Product::all();

        foreach ($bars as $bar) {
            foreach ($products->random(3) as $product) {
                BarProduct::updateOrCreate(
                    [
                        'user_id' => $bar->id,
                        'product_id' => $product->id
                    ],
                    [
                        'price' => rand(3, 15),
                        'stock' => rand(5, 50),
                        'available' => rand(0, 1),
                    ]
                );
            }
        }
    }
}