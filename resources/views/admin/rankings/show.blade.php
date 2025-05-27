@extends('admin.layout')

@section('title', 'Detalles del Ranking')
@section('page-title', 'Ranking: ' . $ranking->name)

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h5 class="card-title mb-0">
                    <i class="fas fa-trophy"></i>
                    Información del Ranking
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Nombre:</strong><br>
                    <span class="fs-5">{{ $ranking->name }}</span>
                </div>
                
                <div class="mb-3">
                    <strong>Código de Acceso:</strong><br>
                    <span class="badge bg-primary fs-6">{{ $ranking->code }}</span>
                    <button class="btn btn-sm btn-outline-secondary ms-2" 
                            onclick="copyToClipboard('{{ $ranking->code }}')"
                            title="Copiar código">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
                
                <div class="mb-3">
                    <strong>Creador:</strong><br>
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-2">
                            <div class="avatar-title rounded-circle bg-success text-white">
                                {{ strtoupper(substr($ranking->creator->name, 0, 1)) }}
                            </div>
                        </div>
                        <div>
                            <div>{{ $ranking->creator->name }}</div>
                            <small class="text-muted">{{ $ranking->creator->email }}</small>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>Fecha de Creación:</strong><br>
                    {{ $ranking->created_at->format('d/m/Y H:i') }}
                </div>
                
                <div class="mb-3">
                    <strong>Participantes:</strong><br>
                    <span class="badge bg-info">{{ $ranking->users->count() }} usuarios</span>
                </div>
                
                <div class="mb-3">
                    <strong>Puntuación Total:</strong><br>
                    <span class="fs-5 fw-bold text-primary">{{ $ranking->users->sum('pivot.points') }} puntos</span>
                </div>
                
                <hr>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.rankings.edit', $ranking->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar Ranking
                    </a>
                    
                    <form action="{{ route('admin.rankings.reset', $ranking->id) }}" 
                          method="POST" 
                          onsubmit="return confirm('¿Resetear puntos de este ranking?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-redo"></i> Resetear Puntos
                        </button>
                    </form>
                    
                    <a href="{{ route('admin.rankings') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver a Rankings
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-medal text-warning"></i>
                    Clasificación Actual
                </h5>
            </div>
            <div class="card-body">
                @if($ranking->users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Posición</th>
                                    <th>Usuario</th>
                                    <th>Puntos Actuales</th>
                                    <th>Récord Mensual</th>
                                    <th>Crédito Actual</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ranking->users as $index => $user)
                                    <tr class="{{ $index < 3 ? 'table-warning' : '' }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($index === 0)
                                                    <span class="badge bg-warning me-2">🥇 1°</span>
                                                @elseif($index === 1)
                                                    <span class="badge bg-secondary me-2">🥈 2°</span>
                                                @elseif($index === 2)
                                                    <span class="badge me-2" style="background-color: #cd7f32; color: white;">🥉 3°</span>
                                                @else
                                                    <span class="badge bg-light text-dark me-2">{{ $index + 1 }}°</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <div class="avatar-title rounded-circle bg-primary text-white">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $user->name }}</div>
                                                    <small class="text-muted">{{ $user->email }}</small>
                                                    @if($user->id === $ranking->creator_id)
                                                        <span class="badge bg-success ms-1">Creador</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fs-5 fw-bold text-primary">
                                                {{ $user->pivot->points }} pts
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $user->pivot->month_record }} pts
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">
                                                €{{ number_format($user->credit, 2) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Promedio de Puntos</h6>
                                    <div class="fs-4 fw-bold text-info">
                                        {{ number_format($ranking->users->avg('pivot.points'), 1) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Mayor Diferencia</h6>
                                    <div class="fs-4 fw-bold text-warning">
                                        {{ $ranking->users->max('pivot.points') - $ranking->users->min('pivot.points') }} pts
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No hay participantes</h5>
                        <p class="text-muted">Añade usuarios a este ranking para que puedan empezar a competir</p>
                        <a href="{{ route('admin.rankings.edit', $ranking->id) }}" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> Añadir Participantes
                        </a>
                    </div>
                @endif
            </div>
        </div>
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
    
    .table-warning {
        background-color: rgba(255, 193, 7, 0.1) !important;
    }
</style>
@endpush

@push('scripts')
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Crear una notificación temporal
            const toast = document.createElement('div');
            toast.className = 'alert alert-success position-fixed';
            toast.style.top = '20px';
            toast.style.right = '20px';
            toast.style.zIndex = '9999';
            toast.innerHTML = '<i class="fas fa-check"></i> Código copiado al portapapeles';
            
            document.body.appendChild(toast);
            
            setTimeout(function() {
                toast.remove();
            }, 2000);
        }).catch(function(err) {
            console.error('Error al copiar: ', err);
        });
    }
</script>
@endpush