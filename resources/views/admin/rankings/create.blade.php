@extends('admin.layout')

@section('title', 'Crear Ranking')
@section('page-title', 'Crear Nuevo Ranking')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-trophy text-primary"></i>
                    Datos del Ranking
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.rankings.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nombre del Ranking *</label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       placeholder="Ej: Amigos del Bar Lua, Ranking Mensual Mayo"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="creator_id" class="form-label">Creador del Ranking *</label>
                                <select class="form-select @error('creator_id') is-invalid @enderror" 
                                        id="creator_id" 
                                        name="creator_id" 
                                        required>
                                    <option value="">Seleccionar usuario...</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('creator_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('creator_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">El creador será añadido automáticamente al ranking</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Participantes del Ranking</label>
                        <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                            <div class="mb-2">
                                <input type="text" 
                                       class="form-control form-control-sm" 
                                       id="searchUsers" 
                                       placeholder="Buscar usuarios...">
                            </div>
                            
                            @foreach($users as $user)
                                <div class="form-check user-checkbox" data-user-name="{{ strtolower($user->name) }}" data-user-email="{{ strtolower($user->email) }}">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="users[]" 
                                           value="{{ $user->id }}" 
                                           id="user_{{ $user->id }}"
                                           {{ in_array($user->id, old('users', [])) ? 'checked' : '' }}>
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
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="form-text">
                            Selecciona los usuarios que participarán en este ranking. 
                            Puedes añadir más usuarios después desde la edición.
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Información del Ranking:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Se generará automáticamente un código único de 6 caracteres</li>
                            <li>Los usuarios pueden unirse al ranking usando este código desde la app</li>
                            <li>Solo las bebidas (productos marcados como "is_drink") suman puntos</li>
                            <li>Cada bebida consumida suma 1 punto al ranking</li>
                            <li>Los rankings se pueden resetear manualmente cada mes</li>
                        </ul>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.rankings') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Crear Ranking
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
        
        const creatorSelect = document.getElementById('creator_id');
        creatorSelect.addEventListener('change', function() {
            const creatorId = this.value;
            if (creatorId) {
                const creatorCheckbox = document.getElementById('user_' + creatorId);
                if (creatorCheckbox) {
                    creatorCheckbox.checked = true;
                }
            }
        });
    });
</script>
@endpush