@if($completedOrders->count() > 0)
    @foreach ($completedOrders as $order)
        <div class="order-item completed">
            <div class="order-info">
                <div class="order-meta">
                    <span><strong>TABLE {{ $order->table_number ?? 'N/A' }}</strong></span>
                    <span>Completed {{ $order->updated_at->diffForHumans() }}</span>
                    <span class="order-time">{{ $order->updated_at->format('H:i') }}</span>
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
                <form action="{{ route('orders.pending', $order) }}" method="POST" class="pending-order-form">
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
        <p>No completed orders today yet</p>
        <span>Complete pending orders and they will appear here</span>
    </div>
@endif