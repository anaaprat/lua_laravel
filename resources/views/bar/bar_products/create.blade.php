<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/jpeg" href="{{ asset('storage/images/lualogo.jpeg') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .form-page {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            flex: 1;
            margin-left: var(--sidebar-width);
        }

        .form-container {
            background-color: var(--neutral-bg);
            border-radius: var(--radius-lg);
            padding: 2rem;
            width: 100%;
            max-width: 700px;
            box-shadow: var(--shadow-lg);
        }

        .form-header {
            margin-bottom: 2rem;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--dark);
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 1.5rem;
            padding: 0.5rem 0;
            transition: var(--transition);
        }

        .btn-back:hover {
            color: var(--primary);
            transform: translateX(-5px);
        }

        .form-header h1 {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-header h1 i {
            color: var(--primary);
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-dark);
            margin: 1.5rem 0 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark);
        }

        .form-control {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: var(--radius-md);
            font-family: 'Poppins', sans-serif;
            transition: var(--transition);
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(132, 169, 140, 0.2);
            outline: none;
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%232f3e46' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1rem;
            padding-right: 2.5rem;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .divider {
            height: 1px;
            background-color: #e2e8f0;
            margin: 1.5rem 0;
        }

        .btn-submit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 0.9rem 1.5rem;
            border-radius: var(--radius-md);
            font-weight: 500;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
            width: 100%;
            margin-top: 1.5rem;
        }

        .btn-submit:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .hidden {
            display: none;
        }

        .alert {
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            border-radius: var(--radius-md);
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #dc2626;
            border: 1px solid #fca5a5;
        }

        .alert-success {
            background-color: #dcfce7;
            color: #16a34a;
            border: 1px solid #86efac;
        }

        /* Estilos para el upload de imagen */
        .image-upload-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            border: 2px dashed #e2e8f0;
            border-radius: var(--radius-md);
            padding: 2rem;
            text-align: center;
            transition: var(--transition);
            cursor: pointer;
        }

        .image-upload-container:hover {
            border-color: var(--primary);
            background-color: rgba(132, 169, 140, 0.05);
        }

        .image-upload-container.has-image {
            border-style: solid;
            border-color: var(--primary);
        }

        .image-preview {
            max-width: 150px;
            max-height: 150px;
            border-radius: var(--radius-md);
            object-fit: cover;
            margin-bottom: 1rem;
        }

        .upload-icon {
            font-size: 2rem;
            color: #94a3b8;
            margin-bottom: 1rem;
        }

        .upload-text {
            color: #64748b;
            font-size: 0.9rem;
        }

        .upload-hint {
            color: #94a3b8;
            font-size: 0.8rem;
            margin-top: 0.5rem;
        }

        .file-input {
            display: none;
        }

        .remove-image {
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            cursor: pointer;
            margin-top: 0.5rem;
        }

        .form-text {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }

        @media (max-width: 992px) {
            .form-page {
                margin-left: var(--sidebar-collapsed);
            }
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
        }

        @media (max-width: 576px) {
            .form-container {
                padding: 1.5rem;
            }

            .form-header h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <aside>
        <div class="sidebar-header">
            <img src="{{ asset('storage/images/lualogo.jpeg') }}" alt="Lua Logo" class="sidebar-logo">
            <div class="bar-sidebar-name">{{ auth()->user()->name }}</div>
            <div class="bar-role">Bar Manager</div>
        </div>

        <div class="nav-links">
            <a href="{{ route('bar.dashboard') }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('bar-products.index') }}" class="active">
                <i class="fas fa-cocktail"></i>
                <span>Products</span>
            </a>
            <a href="{{ route('bar.statistics') }}">
                <i class="fas fa-chart-bar"></i>
                <span>Statistics</span>
            </a>
            <a href="{{ route('bar.recharges') }}">
                <i class="fas fa-wallet"></i>
                <span>Recharges</span>
            </a>
        </div>

        <div class="bottom-section">
            @if(auth()->user()->qr_path)
                <div class="qr-container">
                    <img src="{{ asset('storage/' . auth()->user()->qr_path) }}" alt="QR Code" class="qr-img">
                    <div class="qr-note">Click to download your QR code for your tables</div>
                </div>
            @endif

            <a href="{{ route('logout') }}" class="logout-link"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </aside>

    <div class="form-page">
        <div class="form-container">
            <div class="form-header">
                <a href="{{ route('bar-products.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Back to Products
                </a>
                <h1><i class="fas fa-cocktail"></i> Add Product to My Bar</h1>
            </div>

            {{-- Mostrar errores --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin: 0; list-style: none; padding: 0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Mostrar mensaje de éxito --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('bar-products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="product_option">Select existing or create new:</label>
                    <select name="product_option" id="product_option" class="form-control" required>
                        <option value="">-- Choose --</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_option') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                        <option value="new" {{ old('product_option') == 'new' ? 'selected' : '' }}>➕ Create new product
                        </option>
                    </select>
                </div>

                <div id="new-product-fields" class="{{ old('product_option') == 'new' ? '' : 'hidden' }}">
                    <div class="section-title">
                        <i class="fas fa-plus-circle"></i> New Product Details
                    </div>

                    <div class="form-group">
                        <label>Product Name:</label>
                        <input type="text" name="product_name" class="form-control" value="{{ old('product_name') }}">
                    </div>

                    <div class="form-group">
                        <label>Description:</label>
                        <textarea name="description" rows="2" class="form-control">{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Category:</label>
                        <select name="type" id="product-type" class="form-control">
                            <option value="food" {{ old('type') == 'food' ? 'selected' : '' }}>Food</option>
                            <option value="drink" {{ old('type') == 'drink' ? 'selected' : '' }}>Drink</option>
                            <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div class="form-group" id="alcoholic-field"
                        style="{{ old('type') == 'drink' ? 'display: block;' : 'display: none;' }}">
                        <label>Is it alcoholic? (for rankings)</label>
                        <select name="is_drink" class="form-control">
                            <option value="0" {{ old('is_drink') == '0' ? 'selected' : '' }}>No - Non-alcoholic</option>
                            <option value="1" {{ old('is_drink') == '1' ? 'selected' : '' }}>Yes - Alcoholic</option>
                        </select>
                        <div class="form-text">Alcoholic drinks appear in the drinks ranking</div>
                    </div>

                    <div class="divider"></div>
                </div>

                <!-- Sección de imagen -->
                <div class="section-title">
                    <i class="fas fa-image"></i> Product Image
                </div>

                <div class="form-group">
                    <label>Product Image:</label>
                    <div class="image-upload-container" onclick="document.getElementById('image-input').click()">
                        <input type="file" id="image-input" name="image" class="file-input" accept="image/*">

                        <div id="upload-placeholder">
                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                            <div class="upload-text">Click to upload product image</div>
                            <div class="upload-hint">JPG, PNG, GIF up to 2MB</div>
                        </div>

                        <div id="image-preview-container" style="display: none;">
                            <img id="image-preview" src="" alt="Preview" class="image-preview">
                            <button type="button" class="remove-image" onclick="removeImage(event)">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="divider"></div>

                <div class="section-title">
                    <i class="fas fa-tag"></i> Product Details for Your Bar
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Price (€):</label>
                        <input type="number" name="price" step="0.01" class="form-control" value="{{ old('price') }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Stock:</label>
                        <input type="number" name="stock" class="form-control" value="{{ old('stock') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Available for ordering:</label>
                    <select name="available" class="form-control" required>
                        <option value="1" {{ old('available') == '1' ? 'selected' : '' }}>Yes - Available</option>
                        <option value="0" {{ old('available') == '0' ? 'selected' : '' }}>No - Not available</option>
                    </select>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-plus"></i> Add Product
                </button>
            </form>
        </div>
    </div>

    <script>
        const selector = document.getElementById('product_option');
        const newFields = document.getElementById('new-product-fields');

        selector.addEventListener('change', () => {
            if (selector.value === 'new') {
                newFields.classList.remove('hidden');
            } else {
                newFields.classList.add('hidden');
            }
        });

        // Mantener estado si hay errores
        document.addEventListener('DOMContentLoaded', function () {
            if (selector.value === 'new') {
                newFields.classList.remove('hidden');
            }
        });

        // Manejar preview de imagen
        document.getElementById('image-input').addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('image-preview').src = e.target.result;
                    document.getElementById('upload-placeholder').style.display = 'none';
                    document.getElementById('image-preview-container').style.display = 'block';
                    document.querySelector('.image-upload-container').classList.add('has-image');
                };
                reader.readAsDataURL(file);
            }
        });

        // Función para remover imagen
        function removeImage(event) {
            event.stopPropagation();

            document.getElementById('image-input').value = '';
            document.getElementById('image-preview').src = '';
            document.getElementById('upload-placeholder').style.display = 'block';
            document.getElementById('image-preview-container').style.display = 'none';
            document.querySelector('.image-upload-container').classList.remove('has-image');
        }

        // Manejar la lógica de bebidas alcohólicas
        document.getElementById('product-type').addEventListener('change', function () {
            const alcoholicField = document.getElementById('alcoholic-field');
            const isDrinkSelect = document.querySelector('select[name="is_drink"]');

            if (this.value === 'drink') {
                alcoholicField.style.display = 'block';
                isDrinkSelect.value = '1'; // Por defecto, las bebidas son alcohólicas
            } else {
                alcoholicField.style.display = 'none';
                isDrinkSelect.value = '0'; // No es bebida alcohólica
            }
        });

        // Mantener estado al cargar la página
        document.addEventListener('DOMContentLoaded', function () {
            const typeSelect = document.getElementById('product-type');
            const alcoholicField = document.getElementById('alcoholic-field');

            if (typeSelect.value === 'drink') {
                alcoholicField.style.display = 'block';
            }
        });
    </script>
</body>

</html>