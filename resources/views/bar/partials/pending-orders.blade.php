@if($pendingOrders->count() > 0)
    @foreach ($pendingOrders as $order)
        <div class="order-item">
            <div class="order-info">
                <div class="order-meta">
                    <span><strong>TABLE {{ $order->table_number ?? 'N/A' }}</strong></span>
                    <span>{{ $order->created_at->diffForHumans() }}</span>
                    <span class="order-time">{{ $order->created_at->format('H:i') }}</span>
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
                <form action="{{ route('orders.complete', $order) }}" method="POST" class="complete-order-form">
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
        <p>No pending orders for today</p>
        <span>Enjoy the break! New orders will appear here when customers place them.</span>
    </div>
@endif