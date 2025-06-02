<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BarProduct;
use Illuminate\Http\Request;

class BarController extends Controller
{
    public function getByToken(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $bar = User::where('token', $request->token)
                  ->where('role', 'bar')
                  ->where('is_active', true)
                  ->where('deleted', false)
                  ->first();

        if (!$bar) {
            return response()->json([
                'message' => 'Bar no encontrado',
            ], 404);
        }

        return response()->json([
            'bar' => $bar,
        ]);
    }

    public function getProducts($barId)
    {
        $bar = User::where('id', $barId)
                  ->where('role', 'bar')
                  ->where('is_active', true)
                  ->where('deleted', false)
                  ->first();

        if (!$bar) {
            return response()->json([
                'message' => 'Bar no encontrado',
            ], 404);
        }

        $products = BarProduct::with('product')
                    ->where('user_id', $barId)
                    ->where('available', true)
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

    public function index()
    {
        $bars = User::where('role', 'bar')
                   ->where('is_active', true)
                   ->where('deleted', false)
                   ->get(['id', 'name', 'email', 'token', 'table_number', 'qr_path']);

        return response()->json([
            'bars' => $bars,
        ]);
    }
}