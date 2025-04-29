<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
</head>

<body
    style="background: linear-gradient(to bottom, #cad2c5, #84a98c, #52796f); color: #2f3e46; font-family: 'Segoe UI', sans-serif; padding: 2rem;">
    <h1>Edit Bar Product</h1>
    <form action="{{ route('bar-products.update', $barProduct) }}" method="POST" class="form-style">
        @csrf
        @method('PUT')

        <p><strong>Product:</strong> {{ $barProduct->product->name }}</p>

        <label>Price (€):</label>
        <input type="number" name="price" step="0.01" value="{{ $barProduct->price }}" required>

        <label>Stock:</label>
        <input type="number" name="stock" value="{{ $barProduct->stock }}" required>

        <label>Available:</label>
        <select name="available">
            <option value="1" @selected($barProduct->available)>Yes</option>
            <option value="0" @selected(!$barProduct->available)>No</option>
        </select>

        <button type="submit">Update</button>
    </form>
</body>

</html>