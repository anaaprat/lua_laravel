@extends('admin.layout')

@section('title', 'Editar Ranking')
@section('page-title', 'Editar Ranking: ' . $ranking->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit text-primary"></i>
                    Editar Datos del Ranking
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.rankings.update', $ranking->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nombre del Ranking *</label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $ranking->name) }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Información del Ranking</label>
                                <div class="bg-light p-3 rounded">
                                    <small class="text-muted">
                                        <strong>Código:</strong> {{ $ranking->code }}<br>
                                        <strong>Creador:</strong> {{ $ranking->creator->name }}<br>
                                        <strong>Creado:</strong> {{ $ranking->created_at->format('d/m/Y H:i') }}<br>
                                        <strong>Participantes:</strong> {{ $ranking->users->count() }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Gestionar Participantes</label>
                        
                        <div class="mb-3">
                            <h6>Participantes Actuales ({{ $ranking->users->count() }}):</h6>
                            <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                @foreach($ranking->users->sortByDesc('pivot.points') as $participant)
                                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title rounded-circle bg-primary text-white">
                                                    {{ strtoupper(substr($participant->name, 0, 1)) }}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $participant->name }}</div>
                                                <small class="text-muted">{{ $participant->email }}</small>
                                                @if($participant->id === $ranking->creator_id)
                                                    <span class="badge bg-success ms-1">Creador</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="badge bg-primary">{{ $participant->pivot->points }} pts</div>
                                            <div class="badge bg-info">Récord: {{ $participant->pivot->month_record }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <h6>Añadir/Quitar Participantes:</h6>
                            <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                <div class="mb-2">
                                    <input type="text" 
                                           class="form-control form-control-sm" 
                                           id="searchUsers" 
                                           placeholder="Buscar usuarios...">
                                </div>
                                
                                @php
                                    $currentParticipantIds = $ranking->users->pluck('id')->toArray();
                                @endphp
                                
                                @foreach($users as $user)
                                    <div class="form-check user-checkbox" data-user-name="{{ strtolower($user->name) }}" data-user-email="{{ strtolower($user->email) }}">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="users[]" 
                                               value="{{ $user->id }}" 
                                               id="user_{{ $user->id }}"
                                               {{ in_array($user->id, old('users', $currentParticipantIds)) ? 'checked' : '' }}
                                               {{ $user->id === $ranking->creator_id ? 'disabled' : '' }}>
                                        <label class="form-check-label" for="user_{{ $user->id }}">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <div class="avatar-title rounded-circle bg-primary text-white">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $user->name }}</div>
                                                    <small class="text-muted">{{ $user->email }}</small>
                                                    <small class="badge bg-success ms-1">€{{ number_format($user->credit, 2) }}</small>
                                                    @if($user->id === $ranking->creator_id)
                                                        <span class="badge bg-warning ms-1">Creador (no se puede quitar)</span>
                                                    @endif
                                                    @if(in_array($user->id, $currentParticipantIds))
                                                        <span class="badge bg-info ms-1">Participando</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                                
                                <input type="hidden" name="users[]" value="{{ $ranking->creator_id }}">
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle"></i>
                                Al quitar un participante se perderán sus puntos. 
                                Los nuevos participantes empezarán con 0 puntos.
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Atención:</strong> 
                        <ul class="mb-0 mt-2">
                            <li>El creador del ranking no puede ser eliminado</li>
                            <li>Al quitar participantes, se perderán sus puntos actuales</li>
                            <li>Los nuevos participantes empezarán con 0 puntos</li>
                            <li>El código del ranking no se puede cambiar</li>
                        </ul>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <div>
                            <a href="{{ route('admin.rankings') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                            <a href="{{ route('admin.rankings.show', $ranking->id) }}" class="btn btn-outline-info ms-2">
                                <i class="fas fa-eye"></i> Ver Detalles
                            </a>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Actualizar Ranking
                        </button>
                    </div>
                </form>
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
    
    .user-checkbox {
        margin-bottom: 0.5rem;
        padding: 0.5rem;
        border-radius: 0.375rem;
        transition: background-color 0.2s;
    }
    
    .user-checkbox:hover {
        background-color: #f8f9fa;
    }
    
    .user-checkbox input:checked + label {
        font-weight: 600;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchUsers');
        const userCheckboxes = document.querySelectorAll('.user-checkbox');
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            userCheckboxes.forEach(function(checkbox) {
                const userName = checkbox.getAttribute('data-user-name');
                const userEmail = checkbox.getAttribute('data-user-email');
                
                if (userName.includes(searchTerm) || userEmail.includes(searchTerm)) {
                    checkbox.style.display = 'block';
                } else {
                    checkbox.style.display = 'none';
                }
            });
        });
    });
</script>
@endpush