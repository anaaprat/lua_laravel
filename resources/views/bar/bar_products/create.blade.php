<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        body {
            background: linear-gradient(to bottom, #cad2c5, #84a98c, #52796f);
            color: #2f3e46;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 3rem 2rem;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .form-container {
            background-color: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 1.5rem;
            color: #1e1e1e;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 0.6rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
        }

        button {
            padding: 0.8rem 1.4rem;
            background-color: #2f3e46;
            color: white;
            font-size: 1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-back {
            display: inline-block;
            margin-bottom: 1rem;
            color: #2f3e46;
            text-decoration: none;
            font-weight: 600;
        }

        .hidden {
            display: none;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <a href="{{ route('bar.dashboard') }}" class="btn-back">⬅ Back to Dashboard</a>
        <h1>Add Product to My Bar</h1>

        <form action="{{ route('bar-products.store') }}" method="POST">
            @csrf

            <label for="product_option">Select existing or create new:</label>
            <select name="product_option" id="product_option" required>
                <option value="">-- Choose --</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
                <option value="new">➕ Create new product</option>
            </select>

            <div id="new-product-fields" class="hidden">
                <label>Product Name:</label>
                <input type="text" name="product_name">

                <label>Description:</label>
                <textarea name="description" rows="2"></textarea>

                <label>Type:</label>
                <select name="type">
                    <option value="food">Food</option>
                    <option value="drink">Drink</option>
                    <option value="other">Other</option>
                </select>

                <label>Is it a drink?</label>
                <select name="is_drink">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>

            <label>Price (€):</label>
            <input type="number" name="price" step="0.01" required>

            <label>Stock:</label>
            <input type="number" name="stock" required>

            <label>Available:</label>
            <select name="available">
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>

            <button type="submit">Add Product</button>
        </form>
    </div>

    <script>
        const selector = document.getElementById('product_option');
        const newFields = document.getElementById('new-product-fields');

        selector.addEventListener('change', () => {
            if (selector.value === 'new') {
                newFields.classList.remove('hidden');
            } else {
                newFields.classList.add('hidden');
            }
        });
    </script>
</body>

</html>