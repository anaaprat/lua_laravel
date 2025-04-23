<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Statistics</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(to bottom, #cad2c5, #84a98c, #52796f);
            font-family: 'Raleway', sans-serif;
            color: white;
        }

        .nav-links {
            display: flex;
            align-items: stretch;
            gap: 0;
        }

        .nav-btn {
            background-color: #2f3e46;
            color: white;
            text-decoration: none;
            font-weight: 600;
            padding: 1rem 2rem;
            flex: 1;
            text-align: center;
            transition: background-color 0.3s, color 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 3px solid transparent;
        }

        .nav-btn:hover {
            background-color: #3c4f55;
            cursor: pointer;
        }

        .nav-btn.active {
            background-color: #3c4f55;
        }

        .bar-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem 2rem;
        }

        .bar-info img {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            border: 2px solid white;
        }

        .bar-info h1 {
            font-size: 1.8rem;
            font-weight: 700;
        }

        main {
            padding: 2rem;
        }

        .section {
            margin-bottom: 3rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th,
        td {
            padding: 0.75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            font-size: 0.9rem;
        }

        th {
            text-align: left;
            font-weight: bold;
        }

        .filter-form input {
            margin-right: 0.5rem;
            padding: 0.4rem;
            border-radius: 5px;
            border: none;
        }

        .filter-form button {
            padding: 0.4rem 1rem;
            background: white;
            color: #2f3e46;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            border: none;
        }

        .filter-form button:hover {
            background: #52796f;
            color: white;
        }

        .back-arrow {
            max-width: 80px;
            font-size: 1.5rem;
            padding: 2rem 0;
            flex: 0 0 auto;
        }

        .back-arrow:hover {
            color: #cad2c5;
        }

        table th {
            background-color: rgba(255, 255, 255, 0.15);
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #fff;
        }

        table td {
            font-size: 0.95rem;
            padding: 0.8rem;
        }

        .status {
            font-weight: bold;
            padding: 0.3rem 0.6rem;
            border-radius: 6px;
            display: inline-block;
            font-size: 0.85rem;
        }

        .status.pending {
            background-color: #e9c46a;
            color: #2f3e46;
        }

        .status.completed {
            background-color: #2a9d8f;
            color: white;
        }

        .status.canceled {
            background-color: #e76f51;
            color: white;
        }

        .bar-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem 2rem 1rem 0.5rem;
        }

        .bar-info h1 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }

        .section.orders h2 {
            font-size: 1.5rem;
            margin-bottom: 1.2rem;
        }

        .filter-form {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .filter-form label {
            display: flex;
            flex-direction: column;
            font-weight: 600;
            font-size: 0.9rem;
            color: white;
        }

        .filter-form input[type="date"] {
            padding: 0.4rem 0.6rem;
            border: none;
            border-radius: 6px;
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            font-family: 'Raleway', sans-serif;
        }

        .filter-form input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }

        .filter-form button {
            padding: 0.5rem 1rem;
            background: white;
            color: #2f3e46;
            border-radius: 6px;
            font-weight: bold;
            border: none;
            margin-top: auto;
            height: fit-content;
        }

        .top-controls {
            display: flex;
            justify-content: flex-end;
            padding: 0 2rem;
            margin-top: 0.3rem;
            margin-bottom: 0.5rem;
        }

        .filter-form {
            display: flex;
            gap: 1rem;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .filter-form label {
            display: flex;
            flex-direction: column;
            font-weight: 600;
            font-size: 0.9rem;
            color: white;
        }

        .filter-form input[type="date"] {
            padding: 0.4rem 0.6rem;
            border: none;
            border-radius: 6px;
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            font-family: 'Raleway', sans-serif;
        }

        .filter-form input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }

        .filter-form button {
            padding: 0.5rem 1rem;
            background: white;
            color: #2f3e46;
            border-radius: 6px;
            font-weight: bold;
            border: none;
        }

        .section.orders h2 {
            font-size: 1.5rem;
            margin-bottom: 0.8rem;
        }
    </style>
</head>

<body>

    <nav>
        <div class="nav-links">
            <a href="{{ route('bar.dashboard') }}" class="nav-btn back-arrow">⬅</a>
            <a href="#products" class="nav-btn" id="productsBtn">Products</a>
            <a href="{{ route('bar.statistics') }}" class="nav-btn active" id="ordersBtn">Orders</a>
        </div>
    </nav>

    <div class="bar-info">
        <img src="{{ asset('storage/images/lualogo.jpeg') }}" alt="Lua Logo">
        <h1>{{ auth()->user()->name }}</h1>
    </div>

    <main>
        <!-- Filtro arriba a la derecha -->
        <div class="top-controls">
            <form method="GET" action="{{ route('bar.statistics') }}" class="filter-form">
                <label>
                    From:
                    <input type="date" name="from" value="{{ request('from') }}">
                </label>
                <label>
                    To:
                    <input type="date" name="to" value="{{ request('to') }}">
                </label>
                <button type="submit">Filter</button>
            </form>
        </div>

        <!-- Tabla de órdenes -->
        <div id="orders" class="section orders">
            <h2>📜 Order History</h2>

            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Table</th>
                        <th>Products</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->user->name }}</td>
                            <td>{{ $order->user->table_number }}</td>
                            <td>
                                <ul>
                                    @foreach ($order->items as $item)
                                        <li>{{ $item->quantity }}x {{ $item->product->name ?? 'Product' }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{ number_format($order->total, 2) }}€</td>
                            <td>{{ $order->created_at->format('Y-m-d') }}</td>
                            <td>
                                <span class="status {{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Ranking de productos -->
        <div id="products" class="section" style="display: none;">
            <h2>🔥 Top Products</h2>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Times Ordered</th>
                        <th>Total €</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($topProducts as $product)
                        <tr>
                            <td>{{ $product->product->name }}</td>
                            <td>{{ $product->total_quantity }}</td>
                            <td>{{ number_format($product->total_sales, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>

    <script>
        const ordersBtn = document.querySelector('a[href="{{ route('bar.statistics') }}"]');
        const productsBtn = document.querySelector('a[href="#products"]');

        const ordersSection = document.getElementById('orders');
        const productsSection = document.getElementById('products');

        // Activar Products desde botón
        productsBtn.addEventListener('click', function (e) {
            e.preventDefault();
            ordersSection.style.display = 'none';
            productsSection.style.display = 'block';
            productsBtn.classList.add('active');
            ordersBtn.classList.remove('active');
        });

        // Activar Orders desde botón
        ordersBtn.addEventListener('click', function (e) {
            e.preventDefault();
            productsSection.style.display = 'none';
            ordersSection.style.display = 'block';
            ordersBtn.classList.add('active');
            productsBtn.classList.remove('active');
        });
    </script>

</body>

</html>