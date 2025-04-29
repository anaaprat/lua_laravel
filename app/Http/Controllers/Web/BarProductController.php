<?php

namespace App\Http\Controllers\web;

use App\Models\BarProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class BarProductController extends Controller
{
    public function index()
    {
        $barProducts = BarProduct::with('product')
            ->where('user_id', Auth::id())
            ->get();

        return view('bar.bar_products.index', compact('barProducts'));
    }

    public function create()
    {
        $products = Product::all(); // Catálogo base
        return view('bar.bar_products.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'available' => 'required|boolean',
        ]);

        BarProduct::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'price' => $request->price,
            'stock' => $request->stock,
            'available' => $request->available,
        ]);

        return redirect()->route('bar-products.index')->with('success', 'Product added!');
    }

    public function edit(BarProduct $barProduct)
    {
        $this->authorize('update', $barProduct);
        return view('bar.bar_products.edit', compact('barProduct'));
    }

    public function update(Request $request, BarProduct $barProduct)
    {
        $this->authorize('update', $barProduct);
        $this->authorize('update', $barProduct);

        $request->validate([
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'available' => 'required|boolean',
        ]);

        $barProduct->update($request->only(['price', 'stock', 'available']));

        return redirect()->route('bar-products.index')->with('success', 'Product updated!');
    }

    public function destroy(BarProduct $barProduct)
    {
        $this->authorize('delete', $barProduct);
        $barProduct->delete();
        return redirect()->route('bar-products.index');
    }
}
