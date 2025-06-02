<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('storage/images/favicon.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - LUA Admin</title>

    <link rel="icon" type="image/png" href="{{ asset('storage/images/favicon.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    <style>
        :root {
            --primary-color: #84a98c;
            --primary-dark: #52796f;
            --sidebar-bg: #2f3e46;
            --sidebar-width: 280px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-brand {
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
            text-decoration: none;
        }

        .sidebar-brand:hover {
            color: var(--primary-color);
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-nav .nav-item {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .sidebar-nav .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar-nav .nav-link.active {
            color: white;
            background-color: var(--primary-color);
        }

        .sidebar-nav .nav-link i {
            width: 20px;
            margin-right: 1rem;
            text-align: center;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            padding: 2rem;
        }

        .page-header {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .page-title {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--sidebar-bg);
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid var(--primary-color);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.5rem 0.8rem;
        }

        .stat-card {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            margin-bottom: 2rem;
        }

        .stat-card h3 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .stat-card p {
            margin: 0;
            opacity: 0.9;
        }

        .alert {
            border: none;
            border-radius: 10px;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                <i class="fas fa-crown"></i> LUA Admin
            </a>
        </div>

        <nav>
            <ul class="sidebar-nav">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.users') }}"
                        class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        Usuarios
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.bars') }}"
                        class="nav-link {{ request()->routeIs('admin.bars*') ? 'active' : '' }}">
                        <i class="fas fa-store"></i>
                        Bares
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.products') }}"
                        class="nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                        <i class="fas fa-cocktail"></i>
                        Productos
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.rankings') }}"
                        class="nav-link {{ request()->routeIs('admin.rankings*') ? 'active' : '' }}">
                        <i class="fas fa-trophy"></i>
                        Rankings
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.movements') }}"
                        class="nav-link {{ request()->routeIs('admin.movements*') ? 'active' : '' }}">
                        <i class="fas fa-exchange-alt"></i>
                        Movimientos
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.orders') }}"
                        class="nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                        <i class="fas fa-shopping-cart"></i>
                        Pedidos
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#logoutModal">
                        <i class="fas fa-sign-out-alt"></i>
                        Cerrar Sesión
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <div class="main-content">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                <div class="d-flex align-items-center">
                    <span class="text-muted me-3">Bienvenido, {{ auth()->user()->name }}</span>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="logoutModalLabel">
                        <i class="fas fa-sign-out-alt text-warning"></i>
                        Confirmar Cierre de Sesión
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <i class="fas fa-question-circle text-warning" style="font-size: 3rem;"></i>
                    </div>
                    <h6 class="mb-3">¿Estás seguro de que quieres cerrar sesión?</h6>
                    <p class="text-muted mb-0">Se cerrará tu sesión actual y tendrás que volver a iniciar sesión.</p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-sign-out-alt"></i> Sí, Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.data-table').DataTable({
                responsive: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
                },
                pageLength: 25,
                order: [[0, 'desc']]
            });
        });

        function confirmDelete(message = '¿Estás seguro de que quieres eliminar este elemento?') {
            return confirm(message);
        }
    </script>

    @stack('scripts')
</body>

</html>