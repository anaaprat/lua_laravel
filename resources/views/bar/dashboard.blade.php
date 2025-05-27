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
    @include('bar.side-bar');

    <main>
        <div class="header-title">
            <div class="logo">
                <i class="fas fa-cocktail"></i>
            </div>
            <h1>{{ auth()->user()->name }}</h1>
        </div>

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
                    {{ number_format($pendingOrders->sum('total') + $completedOrders->sum('total'), 2) }} €
                </div>
                <div class="stat-label">Today's Sales</div>
            </div>
        </div>

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