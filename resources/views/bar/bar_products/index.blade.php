<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bar Products</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        /* Estilos específicos para la tabla de productos */
        .products-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            background-color: var(--neutral-bg);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
        }

        .products-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.6rem;
            color: var(--dark);
            font-weight: 600;
        }

        .products-title i {
            color: var(--primary);
        }

        .product-table-container {
            background-color: var(--neutral-bg);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
            overflow: hidden;
        }

        .dataTables_wrapper {
            margin-top: 0.5rem;
        }

        table.dataTable {
            border-collapse: collapse;
            width: 100%;
        }

        table.dataTable thead th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e2e8f0;
        }

        table.dataTable tbody td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e2e8f0;
            color: var(--dark);
        }

        table.dataTable tbody tr:hover {
            background-color: #f8fafc;
        }

        .dataTables_filter input {
            padding: 0.5rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: var(--radius-md);
            margin-left: 0.5rem;
        }

        .dataTables_length select {
            padding: 0.5rem;
            border: 1px solid #e2e8f0;
            border-radius: var(--radius-sm);
            margin: 0 0.5rem;
        }

        .dataTables_info,
        .dataTables_paginate {
            margin-top: 1rem;
            font-size: 0.9rem;
        }

        .dataTables_paginate .paginate_button {
            padding: 0.4rem 0.8rem;
            margin: 0 0.2rem;
            border-radius: var(--radius-sm);
            background-color: #f1f5f9;
            color: var(--dark);
            border: none !important;
        }

        .dataTables_paginate .paginate_button.current,
        .dataTables_paginate .paginate_button:hover {
            background-color: var(--primary) !important;
            color: white !important;
        }

        .price-col {
            font-weight: 600;
            color: var(--primary-dark);
        }

        .stock-col {
            font-weight: 600;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: white;
            border: none;
            cursor: pointer;
            transition: var(--transition);
        }

        .edit-btn {
            background-color: var(--primary);
        }

        .delete-btn {
            background-color: var(--danger);
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .btn-add-product {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: var(--primary);
            color: white;
            padding: 0.7rem 1.2rem;
            border-radius: var(--radius-md);
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition);
        }

        .btn-add-product:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
    </style>
</head>

<body>
    <!-- Barra lateral -->
    @include('bar.side-bar');

    <!-- Contenido principal -->
    <main>
        <!-- Cabecera de productos -->
        <div class="products-header">
            <div class="products-title">
                <i class="fas fa-cocktail"></i>
                <span>My Bar Products</span>
            </div>
            <a href="{{ route('bar-products.create') }}" class="btn-add-product">
                <i class="fas fa-plus"></i> Add Product
            </a>
        </div>

        <!-- Contenedor de tabla de productos -->
        <div class="product-table-container">
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
                            <td class="price-col">€{{ number_format($barProduct->price, 2) }}</td>
                            <td class="stock-col">{{ $barProduct->stock }}</td>
                            <td>{{ $barProduct->product->description }}</td>
                            <td class="actions">
                                <a href="{{ route('bar-products.edit', $barProduct) }}" class="action-btn edit-btn">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('bar-products.destroy', $barProduct) }}" method="POST"
                                    style="display: inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="action-btn delete-btn"
                                        onclick="return confirm('Are you sure you want to delete this product?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#products-table').DataTable({
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search products...",
                },
                "columnDefs": [
                    { "orderable": false, "targets": 4 } // Disable sorting on the Actions column
                ]
            });
        });
    </script>
</body>

</html>