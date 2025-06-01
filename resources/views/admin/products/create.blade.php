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
                    <!-- IMPORTANTE: Añadir enctype para subida de archivos -->
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nombre del Producto *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                        name="name" value="{{ old('name') }}" required>
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
                                        <option value="drink" {{ old('type') == 'drink' ? 'selected' : '' }}>
                                            🍺 Bebida
                                        </option>
                                        <option value="food" {{ old('type') == 'food' ? 'selected' : '' }}>
                                            🍽️ Comida
                                        </option>
                                        <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>
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
                                        <option value="1" {{ old('is_drink') == '1' ? 'selected' : '' }}>
                                            🍷 Sí, es alcohólica
                                        </option>
                                        <option value="0" {{ old('is_drink') == '0' ? 'selected' : '' }}>
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

                                <!-- Campo oculto para productos que no son bebidas -->
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
                                placeholder="Descripción detallada del producto...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nueva sección para imagen -->
                        <div class="mb-3">
                            <label class="form-label">Imagen del Producto</label>

                            <!-- Opciones de imagen -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h6 class="card-title">📁 Subir desde mi ordenador</h6>
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
                                            <h6 class="card-title">🌐 URL de imagen</h6>
                                            <input type="url" class="form-control @error('image_url') is-invalid @enderror"
                                                id="image_url" name="image_url" value="{{ old('image_url') }}"
                                                placeholder="https://ejemplo.com/imagen.jpg">
                                            @error('image_url')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">
                                                O introduce una URL de imagen
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Vista previa de la imagen -->
                            <div id="image-preview" class="mt-3" style="display: none;">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">Vista previa:</h6>
                                        <img id="preview-img" src="" alt="Vista previa"
                                            style="max-width: 200px; max-height: 200px; border-radius: 8px;">
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="removePreview()">
                                                <i class="fas fa-trash"></i> Quitar imagen
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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

    <script>
        function updateAlcoholField() {
            const typeSelect = document.getElementById('type');
            const alcoholField = document.getElementById('alcohol-field');
            const isDrinkSelect = document.getElementById('is_drink');
            const isDrinkHidden = document.getElementById('is_drink_hidden');

            if (typeSelect.value === 'drink') {
                // Mostrar campo de alcohol solo para bebidas
                alcoholField.style.display = 'block';
                isDrinkSelect.setAttribute('required', 'required');
                isDrinkHidden.style.display = 'none';
                isDrinkHidden.disabled = true;
            } else {
                // Ocultar campo de alcohol para comida y otros
                alcoholField.style.display = 'none';
                isDrinkSelect.removeAttribute('required');
                isDrinkSelect.value = '';

                // Para comida/otros, siempre is_drink = 0
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

            // Mostrar información del ranking
            rankingInfo.style.display = 'block';

            if (type === 'drink' && isAlcoholic === '1') {
                // Bebida alcohólica - SÍ cuenta para ranking
                rankingText.innerHTML = '🍷 <strong>Esta bebida alcohólica SÍ contará para el ranking entre amigos</strong>';
                rankingInfo.className = 'alert alert-success';

            } else if (type === 'drink' && isAlcoholic === '0') {
                // Bebida sin alcohol - NO cuenta para ranking
                rankingText.innerHTML = '💧 Esta bebida sin alcohol NO contará para el ranking';
                rankingInfo.className = 'alert alert-warning';

            } else if (type === 'drink' && isAlcoholic === '') {
                // Bebida pero sin especificar alcohol
                rankingText.innerHTML = '🍺 Especifica si la bebida es alcohólica para determinar si cuenta para el ranking';
                rankingInfo.className = 'alert alert-info';

            } else if (type === 'food') {
                // Comida - NO cuenta para ranking
                rankingText.innerHTML = '🍽️ La comida NO cuenta para el ranking';
                rankingInfo.className = 'alert alert-secondary';

            } else if (type === 'other') {
                // Otros - NO cuenta para ranking
                rankingText.innerHTML = '🔧 Este tipo de producto NO cuenta para el ranking';
                rankingInfo.className = 'alert alert-secondary';

            } else {
                // Sin seleccionar tipo
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

                    // Limpiar URL si se sube archivo
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

        // Limpiar archivo si se introduce URL
        document.getElementById('image_url').addEventListener('input', function () {
            if (this.value) {
                document.getElementById('image_file').value = '';
                removePreview();
            }
        });

        // Inicializar el estado al cargar la página
        document.addEventListener('DOMContentLoaded', function () {
            updateAlcoholField();
        });
    </script>
@endsection