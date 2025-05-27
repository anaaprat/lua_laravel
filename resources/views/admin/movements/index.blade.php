@extends('admin.layout')

@section('title', 'Movimientos')
@section('page-title', 'Visualización de Movimientos')

@section('content')
    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0" style="background: linear-gradient(135deg, #28a745, #20c997);">
                <div class="card-body text-white text-center">
                    <h3 class="mb-0">€{{ number_format($stats['total_amount'], 2) }}</h3>
                    <p class="mb-0"><i class="fas fa-chart-line"></i> Total General</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0" style="background: linear-gradient(135deg, #007bff, #0056b3);">
                <div class="card-body text-white text-center">
                    <h3 class="mb-0">€{{ number_format($stats['positive_amount'], 2) }}</h3>
                    <p class="mb-0"><i class="fas fa-arrow-up"></i> Recargas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0" style="background: linear-gradient(135deg, #dc3545, #c82333);">
                <div class="card-body text-white text-center">
                    <h3 class="mb-0">€{{ number_format(abs($stats['negative_amount']), 2) }}</h3>
                    <p class="mb-0"><i class="fas fa-arrow-down"></i> Pagos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0" style="background: linear-gradient(135deg, #6f42c1, #5a3a9a);">
                <div class="card-body text-white text-center">
                    <h3 class="mb-0">{{ number_format($stats['total_movements']) }}</h3>
                    <p class="mb-0"><i class="fas fa-exchange-alt"></i> Total Movimientos</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-filter text-primary"></i>
                Filtros de Búsqueda
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.movements') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="from" class="form-label">Fecha Desde</label>
                    <input type="date" class="form-control" id="from" name="from" value="{{ request('from') }}">
                </div>
                <div class="col-md-3">
                    <label for="to" class="form-label">Fecha Hasta</label>
                    <input type="date" class="form-control" id="to" name="to" value="{{ request('to') }}">
                </div>
                <div class="col-md-3">
                    <label for="type" class="form-label">Tipo de Movimiento</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">Todos</option>
                        <option value="positive" {{ request('type') === 'positive' ? 'selected' : '' }}>
                            Recargas (Positivos)
                        </option>
                        <option value="negative" {{ request('type') === 'negative' ? 'selected' : '' }}>
                            Pagos (Negativos)
                        </option>
                    </select>
                </div>
                <div class="col-md-3">
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

    <!-- Tabla de Movimientos -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-exchange-alt text-primary"></i>
                Lista de Movimientos
            </h5>
            @if(request()->hasAny(['from', 'to', 'type']))
                <a href="{{ route('admin.movements') }}" class="btn btn-outline-secondary btn-sm">
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
                            <th>Cantidad</th>
                            <th>Tipo</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $movement)
                            <tr>
                                <td>#{{ $movement->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <div class="avatar-title rounded-circle bg-primary text-white">
                                                {{ strtoupper(substr($movement->user->name, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $movement->user->name }}</div>
                                            <small class="text-muted">{{ $movement->user->email }}</small>
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
                                            <div class="fw-bold">{{ $movement->bar->name }}</div>
                                            <small class="text-muted">{{ $movement->bar->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold fs-5 text-{{ $movement->amount >= 0 ? 'success' : 'danger' }}">
                                        {{ $movement->amount >= 0 ? '+' : '' }}€{{ number_format($movement->amount, 2) }}
                                    </span>
                                </td>
                                <td>
                                    @if($movement->amount > 0)
                                        <span class="badge bg-success">
                                            <i class="fas fa-plus"></i> Recarga
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-minus"></i> Pago
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $movement->created_at->format('d/m/Y') }}</div>
                                    <small class="text-muted">{{ $movement->created_at->format('H:i:s') }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-2x mb-2"></i>
                                        <p>No se encontraron movimientos</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if($movements->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $movements->appends(request()->query())->links() }}
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