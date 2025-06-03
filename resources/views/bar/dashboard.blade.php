<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/jpeg" href="{{ asset('storage/images/lualogo.jpeg') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            <div class="last-update">
                <small id="lastUpdate">Last update: <span id="timestamp">{{ now()->format('H:i:s') }}</span></small>
            </div>
        </div>

        <div class="stats-cards">
            <div class="stat-card">
                <i class="fas fa-hourglass-half stat-icon"></i>
                <div class="stat-value" id="pendingCount">{{ $pendingOrders->count() }}</div>
                <div class="stat-label">Pending Orders</div>
            </div>

            <div class="stat-card">
                <i class="fas fa-check-circle stat-icon"></i>
                <div class="stat-value" id="completedCount">{{ $completedOrders->count() }}</div>
                <div class="stat-label">Completed Today</div>
            </div>

            <div class="stat-card">
                <i class="fas fa-euro-sign stat-icon"></i>
                <div class="stat-value" id="totalSales">
                    {{ number_format($pendingOrders->sum('total') + $completedOrders->sum('total'), 2) }} €
                </div>
                <div class="stat-label">Today's Sales</div>
            </div>
        </div>

        <div class="orders-section">
            <div class="section-header">
                <i class="fas fa-hourglass-half"></i>
                <h2>Pending Orders</h2>
                <div class="badge" id="pendingBadge">{{ $pendingOrders->count() }}</div>
            </div>

            <div id="pendingOrdersContainer">
                @if($pendingOrders->count() > 0)
                    @foreach ($pendingOrders as $order)
                        <div class="order-item">
                            <div class="order-info">
                                <div class="order-meta">
                                    <span><strong>TABLE {{ $order->table_number }}</strong></span>
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
                                <form action="{{ route('orders.complete', $order) }}" method="POST" class="complete-form">
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
        </div>

        <div class="orders-section">
            <div class="section-header">
                <i class="fas fa-check-circle"></i>
                <h2>Completed Orders</h2>
                <div class="badge" id="completedBadge">{{ $completedOrders->count() }}</div>
            </div>

            <div id="completedOrdersContainer">
                @if($completedOrders->count() > 0)
                    @foreach ($completedOrders as $order)
                        <div class="order-item">
                            <div class="order-info">
                                <div class="order-meta">
                                    <span><strong>TABLE {{ $order->table_number }}</strong></span>
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
                                <form action="{{ route('orders.pending', $order) }}" method="POST" class="pending-form">
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
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function updateOrders() {
                $.ajax({
                    url: '{{ route("bar.orders.ajax") }}',
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        $('#pendingCount').text(response.pending_count);
                        $('#completedCount').text(response.completed_count);
                        $('#pendingBadge').text(response.pending_count);
                        $('#completedBadge').text(response.completed_count);
                        $('#totalSales').text(response.total_sales + ' €');

                        $('#pendingOrdersContainer').html(response.pending_orders_html);
                        $('#completedOrdersContainer').html(response.completed_orders_html);

                        $('#timestamp').text(response.timestamp);

                        attachFormHandlers();

                        console.log('Orders updated successfully');
                    },
                    error: function (xhr, status, error) {
                        console.error('Error updating orders:', error);
                    }
                });
            }

            function attachFormHandlers() {
                $('.complete-form').off('submit').on('submit', function (e) {
                    e.preventDefault();

                    const form = $(this);
                    const button = form.find('button');
                    const originalText = button.html();

                    button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');

                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize(),
                        success: function (response) {
                            if (response.success) {
                                updateOrders();
                                console.log('Order completed successfully');
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('Error completing order:', error);
                            alert('Error al completar el pedido');
                        },
                        complete: function () {
                            button.prop('disabled', false).html(originalText);
                        }
                    });
                });

                $('.pending-form').off('submit').on('submit', function (e) {
                    e.preventDefault();

                    const form = $(this);
                    const button = form.find('button');
                    const originalText = button.html();

                    button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');

                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize(),
                        success: function (response) {
                            if (response.success) {
                                updateOrders();
                                console.log('Order moved to pending successfully');
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('Error moving order to pending:', error);
                            alert('Error al mover el pedido');
                        },
                        complete: function () {
                            button.prop('disabled', false).html(originalText);
                        }
                    });
                });
            }

            attachFormHandlers();

            setInterval(updateOrders, 5000);

            $(window).on('focus', function () {
                updateOrders();
            });

            console.log('Dashboard auto-refresh initialized');
        });
    </script>
</body>

</html>