@extends('admin.layout')

@section('title', 'Gestión de Rankings')
@section('page-title', 'Gestión de Rankings')

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0" style="background: linear-gradient(135deg, #ffd700, #ffb347);">
            <div class="card-body text-white text-center">
                <h3 class="mb-0">{{ $stats['total_rankings'] }}</h3>
                <p class="mb-0"><i class="fas fa-trophy"></i> Rankings Totales</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0" style="background: linear-gradient(135deg, #6f42c1, #5a3a9a);">
            <div class="card-body text-white text-center">
                <h3 class="mb-0">{{ $stats['total_participants'] }}</h3>
                <p class="mb-0"><i class="fas fa-users"></i> Participantes</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0" style="background: linear-gradient(135deg, #28a745, #20c997);">
            <div class="card-body text-white text-center">
                <h3 class="mb-0">{{ $stats['highest_score'] ?? 0 }}</h3>
                <p class="mb-0"><i class="fas fa-star"></i> Mayor Puntuación</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0" style="background: linear-gradient(135deg, #dc3545, #c82333);">
            <div class="card-body text-white text-center">
                <h3 class="mb-0">{{ $stats['most_active_ranking'] ? $stats['most_active_ranking']->users_count : 0 }}</h3>
                <p class="mb-0"><i class="fas fa-fire"></i> Ranking Más Activo</p>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-cogs text-primary"></i>
            Acciones Globales
        </h5>
    </div>
    <div class="card-body">
        <div class="d-flex gap-3">
            <a href="{{ route('admin.rankings.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Ranking
            </a>
            <form action="{{ route('admin.rankings.reset-all') }}" 
                  method="POST" 
                  class="d-inline"
                  onsubmit="return confirm('¿Estás seguro de resetear TODOS los rankings? Se guardarán los récords mensuales.')">
                @csrf
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-redo"></i> Resetear Todos los Rankings
                </button>
            </form>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-trophy text-primary"></i>
            Lista de Rankings
        </h5>
    </div>
    <div class="card-body">
        @if($rankings->count() > 0)
            <div class="row">
                @foreach($rankings as $ranking)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #ffd700, #ffb347);">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-white fw-bold">
                                        <i class="fas fa-trophy"></i> {{ $ranking->name }}
                                    </h6>
                                    <span class="badge bg-white text-dark">{{ $ranking->users_count }} usuarios</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <small class="text-muted">
                                        <strong>Código:</strong> 
                                        <span class="badge bg-primary">{{ $ranking->code }}</span>
                                    </small><br>
                                    <small class="text-muted">
                                        <strong>Creador:</strong> {{ $ranking->creator->name }}
                                    </small><br>
                                    <small class="text-muted">
                                        <strong>Creado:</strong> {{ $ranking->created_at->format('d/m/Y') }}
                                    </small>
                                </div>

                                @if($ranking->users->count() > 0)
                                    <div class="mb-3">
                                        <h6 class="text-muted mb-2">🏆 Top 3:</h6>
                                        @foreach($ranking->users->take(3) as $index => $user)
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <div class="d-flex align-items-center">
                                                    @if($index === 0)
                                                        <span class="badge bg-warning me-2">🥇</span>
                                                    @elseif($index === 1)
                                                        <span class="badge bg-secondary me-2">🥈</span>
                                                    @elseif($index === 2)
                                                        <span class="badge bg-warning me-2" style="background-color: #cd7f32!important;">🥉</span>
                                                    @endif
                                                    <span class="small">{{ $user->name }}</span>
                                                </div>
                                                <span class="badge bg-info">{{ $user->pivot->points }} pts</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="card-footer bg-transparent">
                                <div class="btn-group w-100" role="group">
                                    <a href="{{ route('admin.rankings.show', $ranking->id) }}" 
                                       class="btn btn-sm btn-outline-primary"
                                       title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.rankings.edit', $ranking->id) }}" 
                                       class="btn btn-sm btn-outline-warning"
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.rankings.reset', $ranking->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('¿Resetear puntos de este ranking?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-info"
                                                title="Resetear puntos">
                                            <i class="fas fa-redo"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.rankings.delete', $ranking->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirmDelete('¿Eliminar este ranking permanentemente?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger"
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No hay rankings creados</h5>
                <p class="text-muted">Crea el primer ranking para que los usuarios puedan competir</p>
                <a href="{{ route('admin.rankings.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Crear Primer Ranking
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .bg-gradient {
        background: linear-gradient(135deg, #ffd700, #ffb347) !important;
    }
    
    .card-ranking {
        transition: transform 0.2s ease;
    }
    
    .card-ranking:hover {
        transform: translateY(-5px);
    }
</style>
@endpush