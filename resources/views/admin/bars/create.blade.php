@extends('admin.layout')

@section('title', 'Crear Bar')
@section('page-title', 'Crear Nuevo Bar')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-store text-primary"></i>
                        Datos del Bar
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.bars.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nombre del Bar *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                        name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                        name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Email para acceder al panel del bar</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Contraseña *</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Mínimo 6 caracteres</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="table_number" class="form-label">Número de Mesas</label>
                                    <input type="number" class="form-control @error('table_number') is-invalid @enderror"
                                        id="table_number" name="table_number" value="{{ old('table_number') }}" min="1"
                                        max="999">
                                    @error('table_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Número máximo de mesas disponibles</div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Información:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Se generará automáticamente un token único y código QR para el bar</li>
                                <li>El bar podrá gestionar sus productos, pedidos y recargas desde su panel</li>
                                <li>Los clientes podrán escanear el QR para acceder a la carta del bar</li>
                            </ul>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.bars') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Crear Bar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection