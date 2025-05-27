@extends('admin.layout')

@section('title', 'Pedidos')
@section('page-title', 'Visualización de Pedidos')

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0" style="background: linear-gradient(135deg, #6f42c1, #5a3a9a);">
            <div class="card-body text-white text-center">
                <h3 class="mb-0">{{ number_format($stats['total_orders']) }}</h3>
                <p class="mb-0"><i class="fas fa-shopping-cart"></i> Total Pedidos</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
            <div class="card-body text-white text-center">
                <h3 class="mb-0">{{ number_format($stats['pending_orders']) }}</h3>
                <p class="mb-0"><i class="fas fa-clock"></i> Pendientes</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0" style="background: linear-gradient(135deg, #28a745, #20c997);">
            <div class="card-body text-white text-center">
                <h3 class="mb-0">{{ number_format($stats['completed_orders']) }}</h3>
                <p class="mb-0"><i class="fas fa-check-circle"></i> Completados</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0" style="background: linear-gradient(135deg, #007bff, #0056b3);">
            <div class="card-body text-white text-center">
                <h3 class="mb-0">€{{ number_format($stats['total_revenue'], 2) }}</h3>
                <p class="mb-0"><i class="fas fa-euro-sign"></i> Ingresos Totales</p>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-filter text-primary"></i>
            Filtros de Búsqueda
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.orders') }}" class="row g-3">
            <div class="col-md-2">
                <label for="from" class="form-label">Fecha Desde</label>
                <input type="date" 
                       class="form-control" 
                       id="from" 
                       name="from" 
                       value="{{ request('from') }}">
            </div>
            <div class="col-md-2">
                <label for="to" class="form-label">Fecha Hasta</label>
                <input type="date" 
                       class="form-control" 
                       id="to" 
                       name="to" 
                       value="{{ request('to') }}">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Estado</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Todos los estados</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                        Pendiente
                    </option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>
                        Completado
                    </option>
                    <option value="canceled" {{ request('status') === 'canceled' ? 'selected' : '' }}>
                        Cancelado
                    </option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="bar_id" class="form-label">Bar</label>
                <select class="form-select" id="bar_id" name="bar_id">
                    <option value="">Todos los bares</option>
                    @foreach($bars as $bar)
                        <option value="{{ $bar->id }}" {{ request('bar_id') == $bar->id ? 'selected' : '' }}>
                            {{ $bar->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="fas fa-shopping-cart text-primary"></i>
            Lista de Pedidos
        </h5>
        @if(request()->hasAny(['from', 'to', 'status', 'bar_id']))
            <a href="{{ route('admin.orders') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-times"></i> Limpiar Filtros
            </a>
        @endif
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Bar</th>
                        <th>Productos</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>
                                <strong>#{{ $order->id }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-2">
                                        <div class="avatar-title rounded-circle bg-primary text-white">
                                            {{ strtoupper(substr($order->user->name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $order->user->name }}</div>
                                        <small class="text-muted">{{ $order->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-2">
                                        <div class="avatar-title rounded bg-warning text-dark">
                                            <i class="fas fa-store"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $order->bar->name }}</div>
                                        <small class="text-muted">{{ $order->bar->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <button type="button" 
                                        class="btn btn-sm btn-outline-info" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#itemsModal{{ $order->id }}"
                                        title="Ver productos">
                                    <i class="fas fa-list"></i> {{ $order->items->count() }} productos
                                </button>
                                
                                <div class="modal fade" id="itemsModal{{ $order->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Productos del Pedido #{{ $order->id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                @foreach($order->items as $item)
                                                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                        <div>
                                                            <strong>{{ $item->product->name }}</strong><br>
                                                            <small class="text-muted">
                                                                Cantidad: {{ $item->quantity }}
                                                                @if($item->product->is_drink)
                                                                    <span class="badge bg-info ms-1">Bebida</span>
                                                                @endif
                                                            </small>
                                                        </div>
                                                        <div class="text-end">
                                                            <div class="fw-bold">€{{ number_format($item->subtotal, 2) }}</div>
                                                            <small class="text-muted">
                                                                €{{ number_format($item->subtotal / $item->quantity, 2) }} c/u
                                                            </small>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold fs-5 text-primary">
                                    €{{ number_format($order->total, 2) }}
                                </span>
                            </td>
                            <td>
                                @if($order->status === 'pending')
                                    <span class="badge bg-warning">
                                        <i class="fas fa-clock"></i> Pendiente
                                    </span>
                                @elseif($order->status === 'completed')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle"></i> Completado
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle"></i> Cancelado
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div>{{ $order->created_at->format('d/m/Y') }}</div>
                                <small class="text-muted">{{ $order->created_at->format('H:i:s') }}</small>
                            </td>
                            <td>
                                <button type="button" 
                                        class="btn btn-sm btn-outline-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#detailModal{{ $order->id }}"
                                        title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                <div class="modal fade" id="detailModal{{ $order->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detalles del Pedido #{{ $order->id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6>Información del Cliente</h6>
                                                        <p><strong>Nombre:</strong> {{ $order->user->name }}</p>
                                                        <p><strong>Email:</strong> {{ $order->user->email }}</p>
                                                        <p><strong>Crédito actual:</strong> €{{ number_format($order->user->credit, 2) }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6>Información del Bar</h6>
                                                        <p><strong>Nombre:</strong> {{ $order->bar->name }}</p>
                                                        <p><strong>Email:</strong> {{ $order->bar->email }}</p>
                                                    </div>
                                                </div>
                                                <hr>
                                                <h6>Información del Pedido</h6>
                                                <p><strong>Fecha:</strong> {{ $order->created_at->format('d/m/Y H:i:s') }}</p>
                                                <p><strong>Estado:</strong> 
                                                    @if($order->status === 'pending')
                                                        <span class="badge bg-warning">Pendiente</span>
                                                    @elseif($order->status === 'completed')
                                                        <span class="badge bg-success">Completado</span>
                                                    @else
                                                        <span class="badge bg-danger">Cancelado</span>
                                                    @endif
                                                </p>
                                                <p><strong>Total:</strong> €{{ number_format($order->total, 2) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                                    <p>No se encontraron pedidos</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($orders->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .avatar-sm {
        width: 32px;
        height: 32px;
    }
    
    .avatar-title {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 600;
    }
</style>
@endpush