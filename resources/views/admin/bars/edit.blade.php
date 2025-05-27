@extends('admin.layout')

@section('title', 'Editar Bar')
@section('page-title', 'Editar Bar: ' . $bar->name)

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit text-primary"></i>
                        Editar Datos del Bar
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.bars.update', $bar->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nombre del Bar *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                        name="name" value="{{ old('name', $bar->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                        name="email" value="{{ old('email', $bar->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Nueva Contraseña</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Dejar en blanco para mantener la actual</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="table_number" class="form-label">Número de Mesas</label>
                                    <input type="number" class="form-control @error('table_number') is-invalid @enderror"
                                        id="table_number" name="table_number"
                                        value="{{ old('table_number', $bar->table_number) }}" min="1" max="999">
                                    @error('table_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="is_active" class="form-label">Estado del Bar</label>
                                    <select class="form-select @error('is_active') is-invalid @enderror" id="is_active"
                                        name="is_active" required>
                                        <option value="1" {{ old('is_active', $bar->is_active) == 1 ? 'selected' : '' }}>
                                            Activo
                                        </option>
                                        <option value="0" {{ old('is_active', $bar->is_active) == 0 ? 'selected' : '' }}>
                                            Inactivo
                                        </option>
                                    </select>
                                    @error('is_active')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Código QR</label>
                                    @if($bar->qr_path)
                                        <div class="text-center">
                                            <img src="{{ Storage::url($bar->qr_path) }}" alt="QR Code"
                                                class="img-fluid border rounded" style="max-width: 150px;">
                                            <div class="mt-2">
                                                <a href="{{ Storage::url($bar->qr_path) }}"
                                                    download="qr_{{ Str::slug($bar->name) }}.svg"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-download"></i> Descargar QR
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            No hay código QR disponible
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Información Adicional</label>
                                    <div class="bg-light p-3 rounded">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <small class="text-muted">
                                                    <strong>Token:</strong> {{ $bar->token }}<br>
                                                    <strong>Registrado:</strong>
                                                    {{ $bar->created_at->format('d/m/Y H:i') }}<br>
                                                    <strong>Última actualización:</strong>
                                                    {{ $bar->updated_at->format('d/m/Y H:i') }}
                                                </small>
                                            </div>
                                            <div class="col-md-6">
                                                <small class="text-muted">
                                                    @php
                                                        $totalProducts = \App\Models\BarProduct::where('user_id', $bar->id)->count();
                                                        $totalOrders = \App\Models\Order::where('bar_id', $bar->id)->count();
                                                        $totalRevenue = \App\Models\Order::where('bar_id', $bar->id)->where('status', 'completed')->sum('total');
                                                    @endphp
                                                    <strong>Productos en carta:</strong> {{ $totalProducts }}<br>
                                                    <strong>Pedidos totales:</strong> {{ $totalOrders }}<br>
                                                    <strong>Ingresos generados:</strong>
                                                    €{{ number_format($totalRevenue, 2) }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Nota:</strong> El token y código QR se generaron automáticamente al crear el bar y no se
                            pueden modificar.
                            Si necesitas un nuevo QR, contacta con el desarrollador.
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.bars') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                            <div>
                                @if($bar->qr_path)
                                    <a href="{{ Storage::url($bar->qr_path) }}" download="qr_{{ Str::slug($bar->name) }}.svg"
                                        class="btn btn-outline-info me-2">
                                        <i class="fas fa-qrcode"></i> Descargar QR
                                    </a>
                                @endif
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Actualizar Bar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .qr-preview {
            max-width: 150px;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 10px;
            background: white;
        }
    </style>
@endpush