<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/jpeg" href="{{ asset('storage/images/lualogo.jpeg') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients Recharges</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        :root {
            --bg-green-light: rgba(132, 169, 140, 0.3);
            --bg-green-medium: rgba(82, 121, 111, 0.6);
            --bg-green-dark: rgba(47, 62, 70, 0.8);
            --color-text-dark: #2f3e46;
            --color-text-light: #ffffff;
            --color-accent-green: #52796f;
            --color-positive: #00b894;
        }

        body {
            background: linear-gradient(135deg, #84a98c, #52796f);
        }

        .page-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 2rem;
            background-color: var(--bg-green-dark);
            padding: 1rem 1.5rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
        }

        .page-header .logo {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-sm);
            background-color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-accent-green);
        }

        .page-header .logo i {
            font-size: 1.5rem;
        }

        .page-header h1 {
            font-size: 1.8rem;
            font-weight: 600;
            color: white;
        }

        .search-section {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
            backdrop-filter: blur(10px);
            margin-bottom: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .search-form {
            display: flex;
            gap: 1rem;
            align-items: flex-end;
        }

        .search-form label {
            font-size: 1rem;
            color: var(--color-text-dark);
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .search-form input[type="text"] {
            flex: 1;
            padding: 0.8rem 1.2rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: var(--radius-md);
            background-color: rgba(255, 255, 255, 0.95);
            font-size: 1rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .search-form input[type="text"]:focus {
            outline: none;
            border-color: var(--color-accent-green);
            box-shadow: 0 0 0 2px rgba(82, 121, 111, 0.3);
        }

        .btn-search {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background-color: var(--color-accent-green);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: var(--radius-md);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            font-size: 1rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        }

        .btn-search:hover {
            background-color: #3a6259;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .user-result {
            background-color: rgba(255, 255, 255, 0.25);
            border-radius: var(--radius-lg);
            padding: 1.8rem;
            box-shadow: var(--shadow-md);
            backdrop-filter: blur(10px);
            margin-bottom: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .user-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.2rem;
            padding-bottom: 1.2rem;
            border-bottom: 1px solid rgba(47, 62, 70, 0.2);
        }

        .user-details {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .user-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--color-text-dark);
        }

        .user-email {
            font-size: 1rem;
            color: #4a6163;
        }

        .credit-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.7rem 1.2rem;
            border-radius: var(--radius-md);
            background-color: rgba(0, 184, 148, 0.15);
            color: #00734d;
            font-weight: 700;
            font-size: 1.4rem;
            border: 1px solid rgba(0, 184, 148, 0.3);
        }

        .add-credit-form {
            display: flex;
            gap: 1rem;
            align-items: flex-end;
        }

        .add-credit-form label {
            font-size: 1rem;
            color: var(--color-text-dark);
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .add-credit-form input[type="number"] {
            flex: 1;
            padding: 0.8rem 1.2rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: var(--radius-md);
            background-color: rgba(255, 255, 255, 0.95);
            font-size: 1rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .add-credit-form input[type="number"]:focus {
            outline: none;
            border-color: var(--color-accent-green);
            box-shadow: 0 0 0 2px rgba(82, 121, 111, 0.3);
        }

        .btn-add-credit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background-color: var(--color-positive);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: var(--radius-md);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            font-size: 1rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        }

        .btn-add-credit:hover {
            background-color: #00a382;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .recent-section {
            background-color: rgba(255, 255, 255, 0.25);
            border-radius: var(--radius-lg);
            padding: 1.8rem;
            box-shadow: var(--shadow-md);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            color: var(--color-text-dark);
        }

        .section-header-left {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .section-header h2 {
            font-size: 1.4rem;
            font-weight: 700;
        }

        .section-header i {
            font-size: 1.3rem;
            color: var(--color-accent-green);
        }

        .total-recharges {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--color-positive);
        }

        .recent-recharges {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: var(--radius-md);
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .recent-recharges th {
            background: var(--bg-green-dark);
            color: white;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            padding: 1.2rem 1.5rem;
            text-align: left;
        }

        .recent-recharges td {
            padding: 1.2rem 1.5rem;
            font-size: 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            vertical-align: middle;
            color: var(--color-text-dark);
        }

        .recent-recharges tr:last-child td {
            border-bottom: none;
        }

        .recent-recharges tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.6);
        }

        .recent-recharges tr:hover {
            background-color: rgba(132, 169, 140, 0.15);
        }

        .amount {
            font-weight: 700;
        }

        .amount.positive {
            color: var(--color-positive);
        }

        .no-results {
            padding: 3rem 2rem;
            text-align: center;
            color: #4a6163;
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: var(--radius-md);
        }

        .no-results i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.6;
            color: var(--color-accent-green);
        }

        .no-results p {
            font-size: 1.1rem;
            font-weight: 500;
        }

        .alert {
            padding: 1.2rem 1.5rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            font-size: 1rem;
        }

        .alert i {
            font-size: 1.2rem;
        }

        .alert-success {
            background-color: rgba(0, 184, 148, 0.15);
            color: #00734d;
            border: 1px solid rgba(0, 184, 148, 0.3);
        }

        .alert-danger {
            background-color: rgba(235, 77, 75, 0.15);
            color: #c0392b;
            border: 1px solid rgba(235, 77, 75, 0.3);
        }

        /* Estilos para la paginación */
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(47, 62, 70, 0.2);
        }

        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 0.5rem;
        }

        .pagination .page-item {
            display: flex;
        }

        .pagination .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.7rem 1rem;
            background-color: rgba(255, 255, 255, 0.8);
            color: var(--color-text-dark);
            text-decoration: none;
            border-radius: var(--radius-sm);
            font-weight: 500;
            min-width: 45px;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .pagination .page-link:hover {
            background-color: var(--color-accent-green);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .pagination .page-item.active .page-link {
            background-color: var(--color-accent-green);
            color: white;
            font-weight: 700;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        }

        .pagination .page-item.disabled .page-link {
            background-color: rgba(255, 255, 255, 0.4);
            color: rgba(47, 62, 70, 0.5);
            cursor: not-allowed;
        }

        .pagination .page-item.disabled .page-link:hover {
            transform: none;
            box-shadow: none;
        }

        @media (max-width: 992px) {
            main {
                margin-left: var(--sidebar-collapsed);
                width: calc(100% - var(--sidebar-collapsed));
            }
        }

        @media (max-width: 768px) {
            main {
                padding: 1.5rem;
            }

            .search-form,
            .add-credit-form {
                flex-direction: column;
                align-items: stretch;
            }

            .user-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .search-form button,
            .add-credit-form button {
                width: 100%;
            }

            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .recent-recharges {
                font-size: 0.9rem;
            }

            .recent-recharges th,
            .recent-recharges td {
                padding: 0.8rem 1rem;
            }

            .pagination {
                flex-wrap: wrap;
                justify-content: center;
            }

            .pagination .page-link {
                padding: 0.5rem 0.8rem;
                min-width: 40px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            main {
                padding: 1rem;
            }

            .page-header h1 {
                font-size: 1.5rem;
            }

            .recent-recharges th,
            .recent-recharges td {
                padding: 0.6rem 0.8rem;
                font-size: 0.8rem;
            }
        }
    </style>
</head>

<body>
    @include('bar.side-bar')

    <main>
        <div class="page-header">
            <div class="logo">
                <i class="fas fa-wallet"></i>
            </div>
            <h1>{{ auth()->user()->name }} - Clients recharges</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <div class="search-section">
            <form method="GET" action="{{ route('bar.rechargesUser') }}" class="search-form">
                <div style="flex: 1;">
                    <label for="search">Search client:</label>
                    <input type="text" id="search" name="search" placeholder="Client's name or email"
                        value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn-search">
                    <i class="fas fa-search"></i> Search
                </button>
            </form>
        </div>

        @if(isset($user))
            <div class="user-result">
                <div class="user-info">
                    <div class="user-details">
                        <div class="user-name">{{ $user->name }}</div>
                        <div class="user-email">{{ $user->email }}</div>
                    </div>
                    <div class="credit-badge">
                        <i class="fas fa-coins" style="margin-right: 8px;"></i> {{ number_format($user->credit, 2) }}€
                    </div>
                </div>

                <form method="POST" action="{{ route('bar.addCredit') }}" class="add-credit-form">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <div style="flex: 1;">
                        <label for="amount">Amount to add (€):</label>
                        <input type="number" id="amount" name="amount" min="1" step="0.01" required>
                    </div>
                    <button type="submit" class="btn-add-credit">
                        <i class="fas fa-plus"></i> Add Credit
                    </button>
                </form>
            </div>
        @elseif(request('search'))
            <div class="no-results">
                <i class="fas fa-user-slash"></i>
                <p>No users found with those search criteria</p>
            </div>
        @endif

        <div class="recent-section">
            <div class="section-header">
                <div class="section-header-left">
                    <i class="fas fa-history"></i>
                    <h2>All recharges</h2>
                </div>
                @if(isset($allRecharges) && $allRecharges->count() > 0)
                    <div class="total-recharges">
                        Total: {{ $allRecharges->total() }} recharges
                    </div>
                @endif
            </div>

            @if(isset($allRecharges) && $allRecharges->count() > 0)
                <table class="recent-recharges">
                    <thead>
                        <tr>
                            <th>USER</th>
                            <th>AMOUNT</th>
                            <th>DATE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allRecharges as $recharge)
                            <tr>
                                <td>{{ $recharge->user->name }}</td>
                                <td>
                                    <span class="amount positive">
                                        +{{ number_format($recharge->amount, 2) }}€
                                    </span>
                                </td>
                                <td>{{ $recharge->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Paginación -->
                @if($allRecharges->hasPages())
                    <div class="pagination-wrapper">
                        <nav aria-label="Pagination Navigation">
                            <ul class="pagination">
                                {{-- Previous Page Link --}}
                                @if ($allRecharges->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            <i class="fas fa-chevron-left"></i>
                                        </span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $allRecharges->previousPageUrl() }}" rel="prev">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($allRecharges->getUrlRange(1, $allRecharges->lastPage()) as $page => $url)
                                    @if ($page == $allRecharges->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($allRecharges->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $allRecharges->nextPageUrl() }}" rel="next">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            <i class="fas fa-chevron-right"></i>
                                        </span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                @endif
            @else
                <div class="no-results">
                    <i class="fas fa-info-circle"></i>
                    <p>There are no recharges to show</p>
                </div>
            @endif
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search');
            if (searchInput) {
                searchInput.focus();
            }
        });
    </script>
</body>

</html>