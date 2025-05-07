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
        $products = Product::all();
        return view('bar.bar_products.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_option' => 'required|string',
            'product_name' => 'required_if:product_option,new|string|max:255',
            'type' => 'required_if:product_option,new|string',
            'is_drink' => 'required_if:product_option,new|boolean',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'available' => 'required|boolean',
        ]);

        if ($request->product_option === 'new') {
            $existing = Product::where('name', $request->product_name)->first();
            if ($existing) {
                return redirect()->back()->withErrors(['product_name' => 'A product with this name already exists.']);
            }

            $product = Product::create([
                'name' => $request->product_name,
                'description' => $request->description,
                'type' => $request->type,
                'is_drink' => $request->is_drink,
            ]);
        } else {
            $product = Product::findOrFail($request->product_option);
        }

        BarProduct::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
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