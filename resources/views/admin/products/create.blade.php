@extends('admin.layout')

@section('title', 'Crear Producto')
@section('page-title', 'Crear Nuevo Producto')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-plus-circle text-primary"></i>
                    Datos del Producto
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.products.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nombre del Producto *</label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="type" class="form-label">Tipo/Categoría *</label>
                                <input type="text" 
                                       class="form-control @error('type') is-invalid @enderror" 
                                       id="type" 
                                       name="type" 
                                       value="{{ old('type') }}" 
                                       placeholder="Ej: Cerveza, Cóctel, Tapas"
                                       required>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="is_drink" class="form-label">Tipo de Producto *</label>
                                <select class="form-select @error('is_drink') is-invalid @enderror" 
                                        id="is_drink" 
                                        name="is_drink" 
                                        required>
                                    <option value="">Seleccionar...</option>
                                    <option value="1" {{ old('is_drink') == '1' ? 'selected' : '' }}>
                                        🍺 Bebida
                                    </option>
                                    <option value="0" {{ old('is_drink') == '0' ? 'selected' : '' }}>
                                        🍽️ Comida
                                    </option>
                                </select>
                                @error('is_drink')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Las bebidas se cuentan para el ranking entre amigos
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="image_url" class="form-label">URL de Imagen</label>
                                <input type="url" 
                                       class="form-control @error('image_url') is-invalid @enderror" 
                                       id="image_url" 
                                       name="image_url" 
                                       value="{{ old('image_url') }}"
                                       placeholder="https://ejemplo.com/imagen.jpg">
                                @error('image_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3"
                                  placeholder="Descripción detallada del producto...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Nota:</strong> Una vez creado el producto, los bares podrán añadirlo a su carta 
                        estableciendo su propio precio y stock.
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.products') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Crear Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection