@extends('admin.layout')

@section('title', 'Editar Producto')
@section('page-title', 'Editar Producto: ' . $product->name)

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit text-primary"></i>
                        Editar Datos del Producto
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nombre del Producto *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                        name="name" value="{{ old('name', $product->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Tipo/Categoría *</label>
                                    <input type="text" class="form-control @error('type') is-invalid @enderror" id="type"
                                        name="type" value="{{ old('type', $product->type) }}"
                                        placeholder="Ej: Cerveza, Cóctel, Tapas" required>
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
                                    <select class="form-select @error('is_drink') is-invalid @enderror" id="is_drink"
                                        name="is_drink" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="1" {{ old('is_drink', $product->is_drink) == 1 ? 'selected' : '' }}>
                                            🍺 Bebida
                                        </option>
                                        <option value="0" {{ old('is_drink', $product->is_drink) == 0 ? 'selected' : '' }}>
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
                                    <input type="url" class="form-control @error('image_url') is-invalid @enderror"
                                        id="image_url" name="image_url" value="{{ old('image_url', $product->image_url) }}"
                                        placeholder="https://ejemplo.com/imagen.jpg">
                                    @error('image_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    @if($product->image_url)
                                        <div class="mt-2">
                                            <small class="text-muted d-block">Vista previa actual:</small>
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                                class="img-thumbnail" style="max-width: 100px; max-height: 100px;"
                                                onerror="this.style.display='none'">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                                name="description" rows="3"
                                placeholder="Descripción detallada del producto...">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Información de Uso</label>
                                    <div class="bg-light p-3 rounded">
                                        @php
                                            $barProducts = \App\Models\BarProduct::with('bar')->where('product_id', $product->id)->get();
                                            $totalOrders = \App\Models\OrderItem::where('product_id', $product->id)->sum('quantity');
                                            $totalRevenue = \App\Models\OrderItem::where('product_id', $product->id)->sum('subtotal');
                                        @endphp

                                        <div class="row">
                                            <div class="col-md-6">
                                                <small class="text-muted">
                                                    <strong>Creado:</strong>
                                                    {{ $product->created_at->format('d/m/Y H:i') }}<br>
                                                    <strong>Última actualización:</strong>
                                                    {{ $product->updated_at->format('d/m/Y H:i') }}<br>
                                                    <strong>Usado en:</strong> {{ $barProducts->count() }} bares
                                                </small>
                                            </div>
                                            <div class="col-md-6">
                                                <small class="text-muted">
                                                    <strong>Veces pedido:</strong> {{ $totalOrders }}<br>
                                                    <strong>Ingresos generados:</strong>
                                                    €{{ number_format($totalRevenue, 2) }}<br>
                                                    <strong>Es bebida:</strong> {{ $product->is_drink ? 'Sí' : 'No' }}
                                                </small>
                                            </div>
                                        </div>

                                        @if($barProducts->count() > 0)
                                            <hr class="my-2">
                                            <small class="text-muted">
                                                <strong>Bares que lo usan:</strong><br>
                                                @foreach($barProducts->take(5) as $barProduct)
                                                    • {{ $barProduct->bar->name }} (€{{ number_format($barProduct->price, 2) }})<br>
                                                @endforeach
                                                @if($barProducts->count() > 5)
                                                    <em>y {{ $barProducts->count() - 5 }} más...</em>
                                                @endif
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($barProducts->count() > 0)
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Atención:</strong> Este producto está siendo usado por {{ $barProducts->count() }}
                                bar(es).
                                Los cambios se reflejarán en todos los bares que lo tengan en su carta.
                            </div>
                        @endif

                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.products') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Actualizar Producto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection