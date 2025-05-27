@extends('admin.layout')

@section('title', 'Gestión de Productos')
@section('page-title', 'Gestión de Productos')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="fas fa-cocktail text-primary"></i>
            Lista de Productos
        </h5>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Producto
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Es Bebida</th>
                        <th>Descripción</th>
                        <th>Bares que lo usan</th>
                        <th>Fecha Creación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($product->image_url)
                                        <img src="{{ $product->image_url }}" 
                                             alt="{{ $product->name }}" 
                                             class="me-2 rounded"
                                             style="width: 32px; height: 32px; object-fit: cover;">
                                    @else
                                        <div class="avatar-sm me-2">
                                            <div class="avatar-title rounded bg-{{ $product->is_drink ? 'info' : 'secondary' }} text-white">
                                                <i class="fas fa-{{ $product->is_drink ? 'cocktail' : 'utensils' }}"></i>
                                            </div>
                                        </div>
                                    @endif
                                    <strong>{{ $product->name }}</strong>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $product->type }}</span>
                            </td>
                            <td>
                                @if($product->is_drink)
                                    <span class="badge bg-info">
                                        <i class="fas fa-cocktail"></i> Bebida
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-utensils"></i> Comida
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($product->description)
                                    <span title="{{ $product->description }}">
                                        {{ Str::limit($product->description, 50) }}
                                    </span>
                                @else
                                    <span class="text-muted">Sin descripción</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $product->barProducts->count() }} bares
                                </span>
                                @if($product->barProducts->count() > 0)
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-info ms-1" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#barsModal{{ $product->id }}"
                                            title="Ver bares">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    <div class="modal fade" id="barsModal{{ $product->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Bares que usan "{{ $product->name }}"</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    @foreach($product->barProducts as $barProduct)
                                                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                            <div>
                                                                <strong>{{ $barProduct->bar->name }}</strong><br>
                                                                <small class="text-muted">{{ $barProduct->bar->email }}</small>
                                                            </div>
                                                            <div class="text-end">
                                                                <div>€{{ number_format($barProduct->price, 2) }}</div>
                                                                <small class="text-muted">Stock: {{ $barProduct->stock }}</small>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $product->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.products.delete', $product->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirmDelete('¿Estás seguro de eliminar este producto? Se eliminará de todos los bares.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger"
                                                title="Eliminar"
                                                {{ $product->barProducts->count() > 0 ? 'disabled' : '' }}>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
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