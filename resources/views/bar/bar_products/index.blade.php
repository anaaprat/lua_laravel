<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bar Products</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to bottom, #cad2c5, #84a98c, #52796f);
            color: #2f3e46;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            padding: 3rem 2rem;
        }

        .header-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        h1 {
            font-size: 2.6rem;
            font-weight: bold;
            color: #1e1e1e;
        }

        .btn-add,
        .btn-back {
            display: inline-block;
            padding: 0.6rem 1.4rem;
            background-color: #2f3e46;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-back {
            margin-right: 1rem;
            background-color: #354f52;
        }

        table.dataTable {
            width: 100% !important;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 12px;
            overflow: hidden;
            background-color: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        table.dataTable th,
        table.dataTable td {
            padding: 1rem 1.2rem;
            font-size: 1rem;
        }

        table.dataTable th {
            background-color: #2f3e46;
            color: white;
        }

        table.dataTable tbody tr:nth-child(even) {
            background-color: #f6f8f7;
        }

        table.dataTable tbody tr:hover {
            background-color: #e4ece7;
        }

        .actions a,
        .actions form {
            display: inline;
        }

        .actions button {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.2rem;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 0.4rem 0.6rem;
        }

        .dataTables_wrapper .dataTables_filter label {
            font-weight: 600;
            font-size: 0.95rem;
        }

        .dataTables_wrapper .dataTables_length select {
            border-radius: 6px;
            padding: 0.3rem 0.4rem;
        }

        .dataTables_wrapper {
            background-color: white;
            border-radius: 12px;
            padding: 1rem;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header-bar">
            <h1>🍹 My Bar Products</h1>
            <div>
                <a href="{{ route('bar.dashboard') }}" class="btn-back">⬅ Back</a>
                <a href="{{ route('bar-products.create') }}" class="btn-add">➕ Add Product</a>
            </div>
        </div>

        <table id="products-table" class="display">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price (€)</th>
                    <th>Stock</th>
                    <th>Available</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barProducts as $barProduct)
                    <tr>
                        <td>{{ $barProduct->product->name }}</td>
                        <td>{{ $barProduct->price }}</td>
                        <td>{{ $barProduct->stock }}</td>
                        <td>{{ $barProduct->available ? '✅' : '❌' }}</td>
                        <td class="actions">
                            <a href="{{ route('bar-products.edit', $barProduct) }}">✏</a>
                            <form action="{{ route('bar-products.destroy', $barProduct) }}" method="POST"
                                style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure?')">🗑</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#products-table').DataTable();
        });
    </script>
</body>

</html>