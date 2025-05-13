<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ auth()->user()->name }} - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <!-- Barra lateral -->
    <aside>
        <div class="sidebar-header">
            <img src="{{ asset('storage/images/lualogo.jpeg') }}" alt="Lua Logo" class="sidebar-logo">
            <div class="bar-sidebar-name">{{ auth()->user()->name }}</div>
            <div class="bar-role">Bar Manager</div>
        </div>

        <div class="nav-links">
            <a href="{{ route('bar.dashboard') }}" class="active">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('bar-products.index') }}">
                <i class="fas fa-cocktail"></i>
                <span>Products</span>
            </a>
            <a href="{{ route('bar.statistics') }}">
                <i class="fas fa-chart-bar"></i>
                <span>Statistics</span>
            </a>
            <a href="{{ route('bar.recharges') }}">
                <i class="fas fa-wallet"></i>
                <span>Recharges</span>
            </a>
        </div>

        <div class="bottom-section">
            @if(auth()->user()->qr_path)
                <div class="qr-container">
                    <img src="{{ asset('storage/' . auth()->user()->qr_path) }}" alt="QR Code" class="qr-img">
                    <div class="qr-note">Click to download your QR code for your tables</div>
                </div>
            @endif

            <a href="{{ route('logout') }}" class="logout-link"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </aside>

    <!-- Contenido principal -->
    <main>
        <!-- Cabecera con título -->
        <div class="header-title">
            <div class="logo">
                <i class="fas fa-cocktail"></i>
            </div>
            <h1>{{ auth()->user()->name }}</h1>
        </div>

        <!-- Tarjetas de estadísticas -->
        <div class="stats-cards">
            <div class="stat-card">
                <i class="fas fa-hourglass-half stat-icon"></i>
                <div class="stat-value">{{ $pendingOrders->count() }}</div>
                <div class="stat-label">Pending Orders</div>
            </div>

            <div class="stat-card">
                <i class="fas fa-check-circle stat-icon"></i>
                <div class="stat-value">{{ $completedOrders->count() }}</div>
                <div class="stat-label">Completed Today</div>
            </div>

            <div class="stat-card">
                <i class="fas fa-euro-sign stat-icon"></i>
                <div class="stat-value">
                    {{ number_format($pendingOrders->sum('total') + $completedOrders->sum('total'), 2) }} €</div>
                <div class="stat-label">Today's Sales</div>
            </div>
        </div>

        <!-- Sección de órdenes pendientes -->
        <div class="orders-section">
            <div class="section-header">
                <i class="fas fa-hourglass-half"></i>
                <h2>Pending Orders</h2>
                <div class="badge">{{ $pendingOrders->count() }}</div>
            </div>

            @if($pendingOrders->count() > 0)
                @foreach ($pendingOrders as $order)
                    <div class="order-item">
                        <div class="order-info">
                            <div class="order-meta">
                                <span><strong>TABLE {{ $order->user->table_number }}</strong></span>
                                <span>{{ $order->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="order-products">
                                @foreach ($order->items as $item)
                                    <span>{{ $item->quantity }}x {{ $item->product->name ?? 'Product' }}</span>
                                    @if(!$loop->last), @endif
                                @endforeach
                            </div>
                            <div class="order-total">Total: {{ number_format($order->total, 2) }}€</div>
                        </div>
                        <div class="order-actions">
                            <form action="{{ route('orders.complete', $order) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-check"></i> Complete
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <i class="far fa-smile"></i>
                    <p>No pending orders at the moment</p>
                    <span>Enjoy the break! New orders will appear here when customers place them.</span>
                </div>
            @endif
        </div>

        <!-- Sección de órdenes completadas -->
        <div class="orders-section">
            <div class="section-header">
                <i class="fas fa-check-circle"></i>
                <h2>Completed Orders</h2>
                <div class="badge">{{ $completedOrders->count() }}</div>
            </div>

            @if($completedOrders->count() > 0)
                @foreach ($completedOrders as $order)
                    <div class="order-item">
                        <div class="order-info">
                            <div class="order-meta">
                                <span><strong>TABLE {{ $order->user->table_number }}</strong></span>
                                <span>{{ $order->updated_at->diffForHumans() }}</span>
                            </div>
                            <div class="order-products">
                                @foreach ($order->items as $item)
                                    <span>{{ $item->quantity }}x {{ $item->product->name ?? 'Product' }}</span>
                                    @if(!$loop->last), @endif
                                @endforeach
                            </div>
                            <div class="order-total">Total: {{ number_format($order->total, 2) }}€</div>
                        </div>
                        <div class="order-actions">
                            <form action="{{ route('orders.pending', $order) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-secondary">
                                    <i class="fas fa-undo"></i> Back to Pending
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <i class="fas fa-tasks"></i>
                    <p>No completed orders yet</p>
                    <span>Complete pending orders and they will appear here</span>
                </div>
            @endif
        </div>
    </main>
</body>

</html>