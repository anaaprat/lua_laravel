<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            ['name' => 'Gin Tonic', 'price' => 8.00, 'is_drink' => true],
            ['name' => 'Mojito', 'price' => 7.50, 'is_drink' => true],
            ['name' => 'Hamburger', 'price' => 10.00, 'is_drink' => false],
            ['name' => 'Nachos', 'price' => 6.50, 'is_drink' => false],
            ['name' => 'Coca-Cola', 'price' => 3.00, 'is_drink' => true],
        ];

        foreach ($products as $data) {
            Product::create($data);
        }
    }
}
