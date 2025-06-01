@extends('admin.layout')

@section('title', 'Gestión de Bares')
@section('page-title', 'Gestión de Bares')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-store text-primary"></i>
                Lista de Bares
            </h5>
            <a href="{{ route('admin.bars.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Bar
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table data-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Mesas</th>
                            <th>QR</th>
                            <th>Estado</th>
                            <th>Fecha Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bars as $bar)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <div class="avatar-title rounded bg-warning text-dark">
                                                <i class="fas fa-store"></i>
                                            </div>
                                        </div>
                                        {{ $bar->name }}
                                    </div>
                                </td>
                                <td>{{ $bar->email }}</td>
                                <td>
                                    @if($bar->table_number)
                                        <span class="badge bg-info">{{ $bar->table_number }} mesas</span>
                                    @else
                                        <span class="text-muted">No especificado</span>
                                    @endif
                                </td>
                                <td>
                                    @if($bar->qr_path)
                                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                            data-bs-target="#qrModal{{ $bar->id }}" title="Ver QR">
                                            <i class="fas fa-qrcode"></i>
                                        </button>

                                        <!-- Modal QR -->
                                        <div class="modal fade" id="qrModal{{ $bar->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-sm">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">QR de {{ $bar->name }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <img src="{{ Storage::url($bar->qr_path) }}" alt="QR Code" class="img-fluid"
                                                            style="max-width: 200px;">
                                                        <p class="mt-2 small text-muted">Token: {{ $bar->token }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">Sin QR</span>
                                    @endif
                                </td>
                                <td>
                                    @if($bar->is_active && !$bar->deleted)
                                        <span class="badge bg-success">Activo</span>
                                    @elseif(!$bar->is_active && !$bar->deleted)
                                        <span class="badge bg-warning">Inactivo</span>
                                    @else
                                        <span class="badge bg-danger">Eliminado</span>
                                    @endif
                                </td>
                                <td>{{ $bar->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.bars.edit', $bar->id) }}"
                                            class="btn btn-sm btn-outline-primary" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        @if(!$bar->deleted)
                                            <form action="{{ route('admin.bars.toggle-status', $bar->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-{{ $bar->is_active ? 'warning' : 'success' }}"
                                                    title="{{ $bar->is_active ? 'Desactivar' : 'Activar' }}">
                                                    <i class="fas fa-{{ $bar->is_active ? 'ban' : 'check' }}"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.bars.delete', $bar->id) }}" method="POST" class="d-inline"
                                                onsubmit="return confirmDelete('¿Estás seguro de eliminar este bar?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
            font-size: 14px;
            font-weight: 600;
        }
    </style>
@endpush