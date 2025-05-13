<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recharges</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        /* Estilos específicos para la página de recargas */
        .page-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 2rem;
        }

        .page-header .logo {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-sm);
            background-color: var(--dark-sidebar);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .page-header .logo i {
            color: white;
            font-size: 1.5rem;
        }

        .page-header h1 {
            font-size: 1.8rem;
            font-weight: 600;
            color: white;
        }

        .filter-form {
            display: flex;
            gap: 1rem;
            align-items: flex-end;
            justify-content: flex-end;
            margin-bottom: 1.5rem;
        }

        .filter-form label {
            font-size: 0.9rem;
            color: white;
            margin-bottom: 0.3rem;
            font-weight: 500;
        }

        .filter-form input[type="date"] {
            padding: 0.6rem 1rem;
            border: none;
            border-radius: var(--radius-md);
            background-color: rgba(255, 255, 255, 0.9);
        }

        .btn-filter {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: var(--radius-md);
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-filter:hover {
            background-color: var(--primary-dark);
        }

        .recharges-table {
            background-color: var(--neutral-bg);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
        }

        .table-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1.5rem;
        }

        .table-header i {
            color: var(--primary);
            font-size: 1.3rem;
        }

        .table-header h2 {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--dark);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 600;
            text-align: left;
            padding: 1rem;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table td {
            padding: 1rem;
            border-top: 1px solid #f1f5f9;
        }

        table tr:hover {
            background-color: #f8fafc;
        }

        .amount {
            font-weight: 600;
        }

        .amount.positive {
            color: var(--success);
        }

        .amount.negative {
            color: var(--danger);
        }

        .amount.neutral {
            color: var(--primary-dark);
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
            <a href="{{ route('bar-products.index') }}">
                <i class="fas fa-cocktail"></i>
                <span>Products</span>
            </a>
            <a href="{{ route('bar.statistics') }}">
                <i class="fas fa-chart-bar"></i>
                <span>Statistics</span>
            </a>
            <a href="{{ route('bar.recharges') }}" class="active">
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
    <main>
        <!-- Cabecera de página -->
        <div class="page-header">
            <div class="logo">
                <i class="fas fa-wallet"></i>
            </div>
            <h1>{{ auth()->user()->name }} - Recharges</h1>
        </div>

        <!-- Tarjetas de estadísticas -->
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-value">{{ number_format($totalAmount, 2) }}€</div>
                <div class="stat-label">Total Balance</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ number_format($deposits, 2) }}€</div>
                <div class="stat-label">Total Deposits</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ number_format($withdrawals, 2) }}€</div>
                <div class="stat-label">Total Withdrawals</div>
            </div>
        </div>

        <!-- Filtro de fechas -->
        <form method="GET" action="{{ route('bar.recharges') }}" class="filter-form">
            <div>
                <label for="from">From:</label>
                <input type="date" id="from" name="from" value="{{ request('from') }}">
            </div>
            <div>
                <label for="to">To:</label>
                <input type="date" id="to" name="to" value="{{ request('to') }}">
            </div>
            <button type="submit" class="btn-filter">
                <i class="fas fa-filter"></i> Filter
            </button>
            @if(request('from') || request('to'))
                <a href="{{ route('bar.recharges') }}" class="btn-filter" style="background-color: #475569;">
                    <i class="fas fa-sync-alt"></i> Reset
                </a>
            @endif
        </form>

        <!-- Tabla de historial de movimientos -->
        <div class="recharges-table">
            <div class="table-header">
                <i class="fas fa-exchange-alt"></i>
                <h2>Movement History</h2>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($movements as $movement)
                        <tr>
                            <td>#{{ $movement->id }}</td>
                            <td>{{ $movement->user->name ?? 'Unknown User' }}</td>
                            <td>
                                <span
                                    class="amount {{ $movement->amount > 0 ? 'positive' : ($movement->amount < 0 ? 'negative' : 'neutral') }}">
                                    {{ $movement->amount > 0 ? '+' : '' }}{{ number_format($movement->amount, 2) }}€
                                </span>
                            </td>
                            <td>{{ $movement->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 2rem;">
                                <i class="fas fa-info-circle"
                                    style="font-size: 2rem; margin-bottom: 1rem; opacity: 0.6;"></i>
                                <p>No recharge movements found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</body>

</html>