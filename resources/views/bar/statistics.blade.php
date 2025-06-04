<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/jpeg" href="{{ asset('storage/images/lualogo.jpeg') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
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
            text-decoration: none;
        }

        .btn-filter:hover {
            background-color: var(--primary-dark);
            color: white;
        }

        .order-history-table {
            background-color: var(--neutral-bg);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
            margin-bottom: 2rem;
        }

        .table-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .table-header-left {
            display: flex;
            align-items: center;
            gap: 10px;
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

        .table-info {
            font-size: 0.9rem;
            color: #64748b;
            font-weight: 500;
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

        .status-canceled {
            background-color: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        /* Estilos para la paginación */
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #f1f5f9;
        }

        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 0.5rem;
        }

        .pagination .page-item {
            display: flex;
        }

        .pagination .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.7rem 1rem;
            background-color: #ffffff;
            color: #475569;
            text-decoration: none;
            border-radius: var(--radius-sm);
            font-weight: 500;
            min-width: 45px;
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }

        .pagination .page-link:hover {
            background-color: var(--primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .pagination .page-item.active .page-link {
            background-color: var(--primary);
            color: white;
            font-weight: 700;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        }

        .pagination .page-item.disabled .page-link {
            background-color: #f8fafc;
            color: #cbd5e1;
            cursor: not-allowed;
        }

        .pagination .page-item.disabled .page-link:hover {
            transform: none;
            box-shadow: none;
            background-color: #f8fafc;
            color: #cbd5e1;
        }

        .top-products-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background-color: rgba(245, 158, 11, 0.1);
            color: #d97706;
            padding: 0.4rem 0.8rem;
            border-radius: var(--radius-md);
            font-size: 0.85rem;
            font-weight: 600;
        }

        .rank-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            background-color: var(--primary);
            color: white;
            border-radius: 50%;
            font-size: 0.8rem;
            font-weight: 700;
            margin-right: 0.5rem;
        }

        .rank-badge.gold {
            background-color: #f59e0b;
        }

        .rank-badge.silver {
            background-color: #6b7280;
        }

        .rank-badge.bronze {
            background-color: #cd7c2f;
        }

        @media (max-width: 768px) {
            .filter-form {
                flex-direction: column;
                align-items: stretch;
            }

            .table-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            table {
                font-size: 0.9rem;
            }

            table th,
            table td {
                padding: 0.7rem 0.5rem;
            }

            .pagination {
                flex-wrap: wrap;
                justify-content: center;
            }

            .pagination .page-link {
                padding: 0.5rem 0.8rem;
                min-width: 40px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .page-header h1 {
                font-size: 1.5rem;
            }

            table th,
            table td {
                padding: 0.5rem 0.3rem;
                font-size: 0.8rem;
            }

            .status-badge {
                padding: 0.2rem 0.6rem;
                font-size: 0.75rem;
            }
        }
    </style>
</head>

<body>
    @include('bar.side-bar')

    <main>
        <div class="page-header">
            <div class="logo">
                <i class="fas fa-chart-bar"></i>
            </div>
            <h1>{{ auth()->user()->name }} - Statistics</h1>
        </div>

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

        <!-- TOP PRODUCTS PRIMERO -->
        @if(isset($topProducts) && $topProducts->count() > 0)
            <div class="order-history-table">
                <div class="table-header">
                    <div class="table-header-left">
                        <i class="fas fa-trophy"></i>
                        <h2>Top Products</h2>
                    </div>
                    <div class="top-products-badge">
                        <i class="fas fa-star"></i>
                        Best Sellers
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Product</th>
                            <th>Quantity Sold</th>
                            <th>Total Sales</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topProducts as $index => $product)
                            <tr>
                                <td>
                                    <span
                                        class="rank-badge {{ $index === 0 ? 'gold' : ($index === 1 ? 'silver' : ($index === 2 ? 'bronze' : '')) }}">
                                        {{ $index + 1 }}
                                    </span>
                                </td>
                                <td>{{ $product->product->name ?? 'Product' }}</td>
                                <td><strong>{{ $product->total_quantity }}</strong> units</td>
                                <td><strong>{{ number_format($product->total_sales, 2) }}€</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- ORDER HISTORY CON PAGINACIÓN -->
        <div class="order-history-table">
            <div class="table-header">
                <div class="table-header-left">
                    <i class="fas fa-scroll"></i>
                    <h2>Order History</h2>
                </div>
                @if(isset($orders) && $orders->count() > 0)
                    <div class="table-info">
                        Showing {{ $orders->firstItem() }}-{{ $orders->lastItem() }} of {{ $orders->total() }} orders
                    </div>
                @endif
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
                            <td>{{ $order->table_number ?? 'N/A' }}</td>
                            <td>
                                @foreach ($order->items as $item)
                                    {{ $item->quantity }}x {{ $item->product->name ?? 'Product' }}
                                    @if(!$loop->last), @endif
                                @endforeach
                            </td>
                            <td><strong>{{ number_format($order->total, 2) }}€</strong></td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="status-badge status-{{ $order->status }}">
                                    <i
                                        class="fas fa-{{ $order->status == 'completed' ? 'check-circle' : ($order->status == 'canceled' ? 'times-circle' : 'hourglass-half') }}"></i>
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

            <!-- Paginación -->
            @if($orders->hasPages())
                <div class="pagination-wrapper">
                    <nav aria-label="Pagination Navigation">
                        <ul class="pagination">
                            {{-- Previous Page Link --}}
                            @if ($orders->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="fas fa-chevron-left"></i>
                                    </span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $orders->appends(request()->query())->previousPageUrl() }}"
                                        rel="prev">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($orders->appends(request()->query())->getUrlRange(1, $orders->lastPage()) as $page => $url)
                                @if ($page == $orders->currentPage())
                                    <li class="page-item active">
                                        <span class="page-link">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($orders->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $orders->appends(request()->query())->nextPageUrl() }}"
                                        rel="next">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            @endif
        </div>
    </main>
</body>

</html>