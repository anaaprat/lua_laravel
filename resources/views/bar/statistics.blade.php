<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        /* Estilos específicos para la página de estadísticas */
        .page-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 2rem;
        }

        .page-header .logo {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-sm);
            background-color: var(--dark-sidebar);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .page-header .logo i {
            color: white;
            font-size: 1.5rem;
        }

        .page-header h1 {
            font-size: 1.8rem;
            font-weight: 600;
            color: white;
        }

        .filter-form {
            display: flex;
            gap: 1rem;
            align-items: flex-end;
            justify-content: flex-end;
            margin-bottom: 1.5rem;
        }

        .filter-form label {
            font-size: 0.9rem;
            color: white;
            margin-bottom: 0.3rem;
            font-weight: 500;
        }

        .filter-form input[type="date"] {
            padding: 0.6rem 1rem;
            border: none;
            border-radius: var(--radius-md);
            background-color: rgba(255, 255, 255, 0.9);
        }

        .btn-filter {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: var(--radius-md);
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-filter:hover {
            background-color: var(--primary-dark);
        }

        .order-history-table {
            background-color: var(--neutral-bg);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
        }

        .table-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1.5rem;
        }

        .table-header i {
            color: var(--primary);
            font-size: 1.3rem;
        }

        .table-header h2 {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--dark);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 600;
            text-align: left;
            padding: 1rem;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table td {
            padding: 1rem;
            border-top: 1px solid #f1f5f9;
        }

        table tr:hover {
            background-color: #f8fafc;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .status-pending {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .status-completed {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }
    </style>
</head>

<body>
    <!-- Barra lateral -->
    @include('bar.side-bar');

    <!-- Contenido principal -->
    <main>
        <!-- Cabecera de página -->
        <div class="page-header">
            <div class="logo">
                <i class="fas fa-chart-bar"></i>
            </div>
            <h1>{{ auth()->user()->name }} - Statistics</h1>
        </div>

        <!-- Tarjetas de estadísticas -->
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-value">{{ number_format($totalSales, 2) }}€</div>
                <div class="stat-label">Total Sales</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $completedOrders }}</div>
                <div class="stat-label">Completed Orders</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $pendingOrders }}</div>
                <div class="stat-label">Pending Orders</div>
            </div>
        </div>

        <!-- Filtro de fechas -->
        <form method="GET" action="{{ route('bar.statistics') }}" class="filter-form">
            <div>
                <label for="from">From:</label>
                <input type="date" id="from" name="from" value="{{ request('from') }}">
            </div>
            <div>
                <label for="to">To:</label>
                <input type="date" id="to" name="to" value="{{ request('to') }}">
            </div>
            <button type="submit" class="btn-filter">
                <i class="fas fa-filter"></i> Filter
            </button>
            @if(request('from') || request('to'))
                <a href="{{ route('bar.statistics') }}" class="btn-filter" style="background-color: #475569;">
                    <i class="fas fa-sync-alt"></i> Reset
                </a>
            @endif
        </form>

        <!-- Tabla de historial de órdenes -->
        <div class="order-history-table">
            <div class="table-header">
                <i class="fas fa-scroll"></i>
                <h2>Order History</h2>
            </div>

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
                    @forelse ($orders as $order)
                        <tr>
                            <td>{{ $order->user->name }}</td>
                            <td>{{ $order->user->table_number }}</td>
                            <td>
                                @foreach ($order->items as $item)
                                    {{ $item->quantity }}x {{ $item->product->name ?? 'Product' }}
                                    @if(!$loop->last), @endif
                                @endforeach
                            </td>
                            <td><strong>{{ number_format($order->total, 2) }}€</strong></td>
                            <td>{{ $order->created_at->format('Y-m-d') }}</td>
                            <td>
                                <span class="status-badge status-{{ $order->status }}">
                                    <i
                                        class="fas fa-{{ $order->status == 'completed' ? 'check-circle' : 'hourglass-half' }}"></i>
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem;">
                                <i class="fas fa-info-circle"
                                    style="font-size: 2rem; margin-bottom: 1rem; opacity: 0.6;"></i>
                                <p>No orders found for this period</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Top productos (si existen) -->
        @if(isset($topProducts) && $topProducts->count() > 0)
            <div class="order-history-table" style="margin-top: 2rem;">
                <div class="table-header">
                    <i class="fas fa-trophy"></i>
                    <h2>Top Products</h2>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity Sold</th>
                            <th>Total Sales</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topProducts as $product)
                            <tr>
                                <td>{{ $product->product->name ?? 'Product' }}</td>
                                <td>{{ $product->total_quantity }}</td>
                                <td><strong>{{ number_format($product->total_sales, 2) }}€</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </main>
</body>

</html>