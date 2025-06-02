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
                    <form action="{{ route('admin.products.update', $product->id) }}" method="POST"
                        enctype="multipart/form-data">
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
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Tipo de Producto *</label>
                                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type"
                                        required onchange="updateAlcoholField()">
                                        <option value="">Seleccionar...</option>
                                        <option value="drink" {{ old('type', $product->type) == 'drink' ? 'selected' : '' }}>
                                            🍺 Bebida
                                        </option>
                                        <option value="food" {{ old('type', $product->type) == 'food' ? 'selected' : '' }}>
                                            🍽️ Comida
                                        </option>
                                        <option value="other" {{ old('type', $product->type) == 'other' ? 'selected' : '' }}>
                                            🔧 Otros
                                        </option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3" id="alcohol-field" style="display: none;">
                                    <label for="is_drink" class="form-label">¿Es alcohólica? *</label>
                                    <select class="form-select @error('is_drink') is-invalid @enderror" id="is_drink"
                                        name="is_drink" onchange="updateRankingInfo()">
                                        <option value="">Seleccionar...</option>
                                        <option value="1" {{ old('is_drink', $product->is_drink) == '1' ? 'selected' : '' }}>
                                            🍷 Sí, es alcohólica
                                        </option>
                                        <option value="0" {{ old('is_drink', $product->is_drink) == '0' ? 'selected' : '' }}>
                                            💧 No, sin alcohol
                                        </option>
                                    </select>
                                    @error('is_drink')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Solo las bebidas alcohólicas cuentan para el ranking
                                    </div>
                                </div>

                                <input type="hidden" id="is_drink_hidden" name="is_drink" value="0" style="display: none;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="alert alert-info" id="ranking-info" style="display: none;">
                                <i class="fas fa-info-circle"></i>
                                <span id="ranking-text">Selecciona el tipo de producto para ver información del
                                    ranking</span>
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

                        <div class="mb-3">
                            <label class="form-label">Imagen del Producto</label>

                            @if($product->image_url)
                                <div class="mb-3">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h6 class="card-title">📷 Imagen actual:</h6>
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                                class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                            <div class="mt-2">
                                                <small class="text-muted">{{ $product->image_url }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h6 class="card-title">📁 Cambiar por archivo</h6>
                                            <input type="file"
                                                class="form-control @error('image_file') is-invalid @enderror"
                                                id="image_file" name="image_file" accept="image/*"
                                                onchange="previewImage(this)">
                                            @error('image_file')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">
                                                Formatos: JPG, PNG, WEBP (máx. 2MB)
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title">🌐 Cambiar por URL</h6>
                                            <input type="url" class="form-control @error('image_url') is-invalid @enderror"
                                                id="image_url" name="image_url"
                                                value="{{ old('image_url', $product->image_url) }}"
                                                placeholder="https://ejemplo.com/imagen.jpg">
                                            @error('image_url')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">
                                                O introduce una nueva URL de imagen
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="image-preview" class="mt-3" style="display: none;">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">Vista previa de nueva imagen:</h6>
                                        <img id="preview-img" src="" alt="Vista previa"
                                            style="max-width: 200px; max-height: 200px; border-radius: 8px;">
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="removePreview()">
                                                <i class="fas fa-trash"></i> Quitar nueva imagen
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                                    <strong>Tipo actual:</strong> {{ ucfirst($product->type) }}
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
                                bar(es). Los cambios se reflejarán en todos los bares que lo tengan en su carta.
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

    <script>
        function updateAlcoholField() {
            const typeSelect = document.getElementById('type');
            const alcoholField = document.getElementById('alcohol-field');
            const isDrinkSelect = document.getElementById('is_drink');
            const isDrinkHidden = document.getElementById('is_drink_hidden');

            if (typeSelect.value === 'drink') {
                alcoholField.style.display = 'block';
                isDrinkSelect.setAttribute('required', 'required');
                isDrinkHidden.style.display = 'none';
                isDrinkHidden.disabled = true;
            } else {
                alcoholField.style.display = 'none';
                isDrinkSelect.removeAttribute('required');
                isDrinkSelect.value = '';

                isDrinkHidden.style.display = 'block';
                isDrinkHidden.disabled = false;
                isDrinkHidden.value = '0';
            }

            updateRankingInfo();
        }

        function updateRankingInfo() {
            const typeSelect = document.getElementById('type');
            const isDrinkSelect = document.getElementById('is_drink');
            const rankingInfo = document.getElementById('ranking-info');
            const rankingText = document.getElementById('ranking-text');

            const type = typeSelect.value;
            const isAlcoholic = isDrinkSelect.value;

            rankingInfo.style.display = 'block';

            if (type === 'drink' && isAlcoholic === '1') {
                rankingText.innerHTML = '🍷 <strong>Esta bebida alcohólica SÍ contará para el ranking entre amigos</strong>';
                rankingInfo.className = 'alert alert-success';

            } else if (type === 'drink' && isAlcoholic === '0') {
                rankingText.innerHTML = '💧 Esta bebida sin alcohol NO contará para el ranking';
                rankingInfo.className = 'alert alert-warning';

            } else if (type === 'drink' && isAlcoholic === '') {
                rankingText.innerHTML = '🍺 Especifica si la bebida es alcohólica para determinar si cuenta para el ranking';
                rankingInfo.className = 'alert alert-info';

            } else if (type === 'food') {
                rankingText.innerHTML = '🍽️ La comida NO cuenta para el ranking';
                rankingInfo.className = 'alert alert-secondary';

            } else if (type === 'other') {
                rankingText.innerHTML = '🔧 Este tipo de producto NO cuenta para el ranking';
                rankingInfo.className = 'alert alert-secondary';

            } else {
                rankingText.innerHTML = 'Selecciona el tipo de producto para ver información del ranking';
                rankingInfo.className = 'alert alert-info';
            }
        }

        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    previewImg.src = e.target.result;
                    preview.style.display = 'block';

                    document.getElementById('image_url').value = '';
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function removePreview() {
            document.getElementById('image-preview').style.display = 'none';
            document.getElementById('image_file').value = '';
            document.getElementById('preview-img').src = '';
        }

        document.getElementById('image_url').addEventListener('input', function () {
            if (this.value) {
                document.getElementById('image_file').value = '';
                removePreview();
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            updateAlcoholField();
        });
    </script>
@endsection