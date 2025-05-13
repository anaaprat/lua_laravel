<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        /* Estilos específicos para el formulario de productos */
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

        .product-info {
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: var(--radius-md);
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--primary);
        }

        .product-info h2 {
            color: var(--dark);
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .product-info p {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 0;
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
    <!-- Barra lateral -->
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

    <!-- Contenido principal -->
    <div class="form-page">
        <div class="form-container">
            <div class="form-header">
                <a href="{{ route('bar-products.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Back to Products
                </a>
                <h1><i class="fas fa-edit"></i> Edit Product</h1>
            </div>

            <div class="product-info">
                <h2>{{ $barProduct->product->name }}</h2>
                <p>{{ $barProduct->product->description }}</p>
            </div>

            <form action="{{ route('bar-products.update', $barProduct) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="section-title">
                    <i class="fas fa-tag"></i> Product Details
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Price (€):</label>
                        <input type="number" name="price" step="0.01" class="form-control" required
                            value="{{ $barProduct->price }}">
                    </div>

                    <div class="form-group">
                        <label>Stock:</label>
                        <input type="number" name="stock" class="form-control" required
                            value="{{ $barProduct->stock }}">
                    </div>
                </div>

                <div class="form-group">
                    <label>Is it alcoholic?</label>
                    <select name="es_copa" class="form-control">
                        <option value="0" {{ $barProduct->product->es_copa == 0 ? 'selected' : '' }}>No</option>
                        <option value="1" {{ $barProduct->product->es_copa == 1 ? 'selected' : '' }}>Yes</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Available:</label>
                    <select name="available" class="form-control">
                        <option value="1" {{ $barProduct->available ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ !$barProduct->available ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Update Product
                </button>
            </form>
        </div>
    </div>
</body>

</html>