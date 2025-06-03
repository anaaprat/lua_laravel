<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BarProduct;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($barId, $productId)
    {
        $barProduct = BarProduct::with('product')
                    ->where('user_id', $barId)
                    ->where('product_id', $productId)
                    ->where('available', true)
                    ->first();

        if (!$barProduct) {
            return response()->json([
                'message' => 'Producto no encontrado en este bar',
            ], 404);
        }

        $product = [
            'id' => $barProduct->product_id,
            'name' => $barProduct->product->name,
            'description' => $barProduct->product->description,
            'is_drink' => $barProduct->product->is_drink,
            'type' => $barProduct->product->type,
            'price' => $barProduct->price,
            'image_url' => $barProduct->product->image_url,
            'stock' => $barProduct->stock,
            'available' => $barProduct->available,
            'bar_product_id' => $barProduct->id
        ];

        return response()->json([
            'product' => $product,
        ]);
    }

    public function search(Request $request, $barId)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json([
                'message' => 'Se requiere un término de búsqueda',
            ], 422);
        }

        $products = BarProduct::with('product')
                    ->where('user_id', $barId)
                    ->where('available', true)
                    ->whereHas('product', function ($q) use ($query) {
                        $q->where('name', 'like', "%{$query}%")
                          ->orWhere('description', 'like', "%{$query}%");
                    })
                    ->get()
                    ->map(function ($barProduct) {
                        return [
                            'id' => $barProduct->product_id,
                            'name' => $barProduct->product->name,
                            'description' => $barProduct->product->description,
                            'is_drink' => $barProduct->product->is_drink,
                            'type' => $barProduct->product->type,
                            'price' => $barProduct->price,
                            'image_url' => $barProduct->product->image_url,
                            'stock' => $barProduct->stock,
                            'available' => $barProduct->available,
                            'bar_product_id' => $barProduct->id
                        ];
                    });

        return response()->json([
            'products' => $products,
        ]);
    }
}