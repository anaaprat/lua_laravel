<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
</head>

<body
    style="background: linear-gradient(to bottom, #cad2c5, #84a98c, #52796f); color: #2f3e46; font-family: 'Segoe UI', sans-serif; padding: 2rem;">
    <h1>Add Product to My Bar</h1>
    <form action="{{ route('bar-products.store') }}" method="POST" class="form-style">
        @csrf
        <label>Product:</label>
        <select name="product_id" required>
            @foreach ($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
            @endforeach
        </select>

        <label>Price (€):</label>
        <input type="number" name="price" step="0.01" required>

        <label>Stock:</label>
        <input type="number" name="stock" required>

        <label>Available:</label>
        <select name="available">
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>

        <button type="submit">Add</button>
    </form>
</body>

</html>