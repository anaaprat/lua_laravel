<?php

namespace App\Http\Controllers\Web;

use App\Models\BarProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class BarProductController extends Controller
{
    public function index()
    {
        Log::info('=== BAR PRODUCTS INDEX ===');
        Log::info('Usuario autenticado:', ['user_id' => Auth::id(), 'user_name' => Auth::user()->name]);

        $barProducts = BarProduct::with('product')
            ->where('user_id', Auth::id())
            ->get();

        Log::info('Productos encontrados:', ['count' => $barProducts->count()]);
        Log::info('Productos:', $barProducts->toArray());

        return view('bar.bar_products.index', compact('barProducts'));
    }

    public function create()
    {
        Log::info('=== BAR PRODUCTS CREATE ===');
        Log::info('Usuario autenticado:', ['user_id' => Auth::id()]);

        $products = Product::all();
        Log::info('Productos disponibles:', ['count' => $products->count()]);

        return view('bar.bar_products.create', compact('products'));
    }

    public function store(Request $request)
    {
        Log::info('=== BAR PRODUCTS STORE ===');
        Log::info('Usuario autenticado:', ['user_id' => Auth::id(), 'user_name' => Auth::user()->name]);
        Log::info('Datos recibidos:', $request->all());

        $rules = [
            'product_option' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'available' => 'required|in:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        if ($request->product_option === 'new') {
            $rules['product_name'] = 'required|string|max:255';
            $rules['type'] = 'required|string|in:food,drink,other';
            $rules['is_drink'] = 'required|in:0,1';
            $rules['description'] = 'nullable|string';
        }

        $validatedData = $request->validate($rules);

        Log::info('Datos validados:', $validatedData);

        try {
            DB::beginTransaction();
            Log::info('Transacción iniciada');

            $product = null;
            $imagePath = null;

            // Procesar imagen si se subió
            if ($request->hasFile('image')) {
                Log::info('Procesando imagen subida');
                $imagePath = $request->file('image')->store('products', 'public');
                Log::info('Imagen guardada en:', ['path' => $imagePath]);
            }

            if ($request->product_option === 'new') {
                Log::info('Creando nuevo producto');

                $existing = Product::where('name', $request->product_name)->first();
                if ($existing) {
                    Log::warning('Producto ya existe:', ['existing_product' => $existing->toArray()]);
                    DB::rollBack();
                    return redirect()->back()
                        ->withErrors(['product_name' => 'A product with this name already exists.'])
                        ->withInput();
                }

                $productData = [
                    'name' => $request->product_name,
                    'description' => $request->description,
                    'type' => $request->type,
                    'is_drink' => $request->is_drink ? 1 : 0,
                ];

                if ($imagePath) {
                    $productData['image_url'] = $imagePath;
                }

                $product = Product::create($productData);

                Log::info('Nuevo producto creado:', $product->toArray());
            } else {
                Log::info('Usando producto existente:', ['product_id' => $request->product_option]);

                $product = Product::findOrFail($request->product_option);
                Log::info('Producto encontrado:', $product->toArray());

                // Si se subió una nueva imagen para un producto existente, actualizamos el producto
                if ($imagePath) {
                    // Eliminar imagen anterior si existe
                    if ($product->image_url && Storage::disk('public')->exists($product->image_url)) {
                        Storage::disk('public')->delete($product->image_url);
                        Log::info('Imagen anterior eliminada:', ['path' => $product->image_url]);
                    }

                    $product->update(['image_url' => $imagePath]);
                    Log::info('Imagen del producto actualizada');
                }

                $existingBarProduct = BarProduct::where('user_id', Auth::id())
                    ->where('product_id', $product->id)
                    ->first();

                if ($existingBarProduct) {
                    Log::warning('El bar ya tiene este producto:', ['existing_bar_product' => $existingBarProduct->toArray()]);
                    DB::rollBack();
                    return redirect()->back()
                        ->withErrors(['product_option' => 'You already have this product in your inventory.'])
                        ->withInput();
                }
            }

            Log::info('Creando BarProduct con:', [
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'price' => $request->price,
                'stock' => $request->stock,
                'available' => $request->available,
            ]);

            $barProduct = BarProduct::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'price' => $request->price,
                'stock' => $request->stock,
                'available' => $request->available ? 1 : 0,
            ]);

            Log::info('BarProduct creado:', $barProduct->toArray());

            $saved = BarProduct::find($barProduct->id);
            Log::info('BarProduct verificado en BD:', $saved ? $saved->toArray() : 'NO ENCONTRADO');

            DB::commit();
            Log::info('Transacción confirmada');

            Log::info('Redirigiendo a bar-products.index');
            return redirect()->route('bar-products.index')
                ->with('success', 'Product added successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            // Limpiar imagen si hubo error
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
                Log::info('Imagen limpiada debido a error');
            }

            Log::error('Error en store:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Error adding product: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function edit(BarProduct $barProduct)
    {
        Log::info('=== BAR PRODUCTS EDIT ===');
        Log::info('Usuario autenticado:', ['user_id' => Auth::id()]);
        Log::info('BarProduct:', $barProduct->toArray());

        if ($barProduct->user_id !== Auth::id()) {
            Log::warning('Acceso no autorizado');
            abort(403, 'Unauthorized action.');
        }

        return view('bar.bar_products.edit', compact('barProduct'));
    }

    public function update(Request $request, BarProduct $barProduct)
    {
        Log::info('=== BAR PRODUCTS UPDATE ===');
        Log::info('Usuario autenticado:', ['user_id' => Auth::id()]);
        Log::info('BarProduct:', $barProduct->toArray());
        Log::info('Datos recibidos:', $request->all());

        if ($barProduct->user_id !== Auth::id()) {
            Log::warning('Acceso no autorizado');
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'available' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $updateData = [
                'price' => $request->price,
                'stock' => $request->stock,
                'available' => $request->available ? 1 : 0,
            ];

            // Procesar nueva imagen si se subió
            if ($request->hasFile('image')) {
                Log::info('Procesando nueva imagen');

                // Eliminar imagen anterior si existe
                if ($barProduct->product->image_url && Storage::disk('public')->exists($barProduct->product->image_url)) {
                    Storage::disk('public')->delete($barProduct->product->image_url);
                    Log::info('Imagen anterior eliminada:', ['path' => $barProduct->product->image_url]);
                }

                // Guardar nueva imagen
                $imagePath = $request->file('image')->store('products', 'public');
                Log::info('Nueva imagen guardada en:', ['path' => $imagePath]);

                // Actualizar el producto con la nueva imagen
                $barProduct->product->update(['image_url' => $imagePath]);
            }

            $barProduct->update($updateData);

            Log::info('BarProduct actualizado:', $barProduct->fresh()->toArray());

            DB::commit();

            return redirect()->route('bar-products.index')
                ->with('success', 'Product updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error en update:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Error updating product: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy(BarProduct $barProduct)
    {
        Log::info('=== BAR PRODUCTS DESTROY ===');
        Log::info('Usuario autenticado:', ['user_id' => Auth::id()]);
        Log::info('BarProduct a eliminar:', $barProduct->toArray());

        if ($barProduct->user_id !== Auth::id()) {
            Log::warning('Acceso no autorizado');
            abort(403, 'Unauthorized action.');
        }

        try {
            $barProduct->delete();
            Log::info('BarProduct eliminado');

            return redirect()->route('bar-products.index')
                ->with('success', 'Product deleted successfully!');

        } catch (\Exception $e) {
            Log::error('Error en destroy:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Error deleting product: ' . $e->getMessage()]);
        }
    }
}