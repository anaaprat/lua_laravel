@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Administrativo')

@section('content')
    <div class="row">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <h3>{{ $stats['total_users'] }}</h3>
                <p><i class="fas fa-users"></i> Usuarios Totales</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <h3>{{ $stats['total_bars'] }}</h3>
                <p><i class="fas fa-store"></i> Bares Registrados</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <h3>{{ $stats['total_products'] }}</h3>
                <p><i class="fas fa-cocktail"></i> Productos</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <h3>{{ $stats['total_orders'] }}</h3>
                <p><i class="fas fa-shopping-cart"></i> Pedidos Totales</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shopping-cart text-primary"></i>
                        Pedidos Recientes
                    </h5>
                    <a href="{{ route('admin.orders') }}" class="btn btn-outline-primary btn-sm">Ver Todos</a>
                </div>
                <div class="card-body">
                    @if($recent_orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Bar</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recent_orders as $order)
                                        <tr>
                                            <td>{{ $order->user->name }}</td>
                                            <td>{{ $order->bar->name }}</td>
                                            <td>€{{ number_format($order->total, 2) }}</td>
                                            <td>
                                                @if($order->status === 'pending')
                                                    <span class="badge bg-warning">Pendiente</span>
                                                @elseif($order->status === 'completed')
                                                    <span class="badge bg-success">Completado</span>
                                                @else
                                                    <span class="badge bg-danger">Cancelado</span>
                                                @endif
                                            </td>
                                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center">No hay pedidos recientes</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exchange-alt text-primary"></i>
                        Movimientos Recientes
                    </h5>
                    <a href="{{ route('admin.movements') }}" class="btn btn-outline-primary btn-sm">Ver Todos</a>
                </div>
                <div class="card-body">
                    @if($recent_movements->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Bar</th>
                                        <th>Cantidad</th>
                                        <th>Tipo</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recent_movements as $movement)
                                        <tr>
                                            <td>{{ $movement->user->name }}</td>
                                            <td>{{ $movement->bar->name }}</td>
                                            <td class="text-{{ $movement->amount >= 0 ? 'success' : 'danger' }}">
                                                €{{ number_format($movement->amount, 2) }}
                                            </td>
                                            <td>
                                                @if($movement->amount > 0)
                                                    <span class="badge bg-success">Recarga</span>
                                                @else
                                                    <span class="badge bg-danger">Pago</span>
                                                @endif
                                            </td>
                                            <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center">No hay movimientos recientes</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection