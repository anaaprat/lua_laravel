<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bar Dashboard - Lua</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Raleway', sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background: linear-gradient(to bottom, #cad2c5, #84a98c, #52796f);
            color: #fff;
        }

        aside {
            position: fixed;
            top: 0;
            right: 0;
            width: 200px;
            height: 100vh;
            background-color: #2f3e46;
            padding: 2rem 1rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            z-index: 1000;
        }

        .nav-links {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            padding: 1.5rem;
            border-radius: 10px;
            transition: background-color 0.3s, color 0.3s;
            background-color: transparent;
            display: block;
            width: 100%;
            text-align: center;
        }

        .nav-links a:hover {
            background-color: white;
            color: #2f3e46;
        }

        .bottom-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .qr-img {
            width: 120px;
            height: 120px;
            border: 2px solid white;
            border-radius: 10px;
            object-fit: cover;
            margin-top: 2rem;
        }

        .qr-note {
            font-size: 0.8rem;
            text-align: center;
            color: #ccc;
            max-width: 180px;
        }

        .logout-link {
            color: white;
            text-decoration: none;
            font-weight: 600;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            transition: background-color 0.3s, color 0.3s;
            width: 100%;
            text-align: center;
        }

        .logout-link:hover {
            background-color: white;
            color: #2f3e46;
        }

        .qr-img {
            width: 100px;
            height: 100px;
            border-radius: 5px;
            margin-bottom: 0.5rem;
        }

        .qr-note {
            font-size: 0.85rem;
            text-align: center;
            color: #ccc;
        }

        main {
            flex: 1;
            padding: 2rem;
            margin-right: 200px;
        }

        .bar-name {
            display: flex;
            align-items: center;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 2rem;
            color: white;
            text-transform: uppercase;
            gap: 10px;
        }

        .dashboard {
            display: grid;
            grid-template-columns: 2fr 1fr;
            /* más espacio a pendientes */
            gap: 2rem;
        }

        .card {
            background: rgba(255, 255, 255, 0.1);
            padding: 1.5rem;
            border-radius: 12px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .card h2 {
            margin-bottom: 1rem;
            font-size: 1.4rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            padding-bottom: 0.5rem;
        }

        .order {
            background: rgba(255, 255, 255, 0.1);
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .order span {
            font-size: 0.95rem;
        }

        .btn {
            background-color: rgb(165, 215, 176);
            color: #2f3e46;
            padding: 0.4rem 0.9rem;
            border-radius: 6px;
            font-weight: bold;
            text-decoration: none;
            font-size: 0.85rem;
            transition: background-color 0.3s, color 0.3s;
        }

        .btn:hover {
            background-color: #52796f;
            color: white;
        }

        .mini-logo {
            width: 50px;
            object-fit: cover;
            border-radius: 10px;
            margin: 0 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>

<body>
    <main>
        <div class="bar-name">
            <img src="{{ asset('storage/images/lualogo.jpeg') }}" alt="Lua Logo" class="mini-logo">
            {{ auth()->user()->name }}
        </div>
        <div class="dashboard">
            <div class="card">
                <h2>🕒 Pending Orders</h2>
                @foreach ($pendingOrders as $order)
                    <div class="order">
                        <div>
                            <div>
                                Table {{ $order->user->table_number }}<br>
                                <ul style="margin-top: 0.5rem; font-size: 0.85rem;">
                                    @foreach ($order->items as $item)
                                        <li>{{ $item->quantity }}x {{ $item->product->name ?? 'Product' }}</li>
                                    @endforeach
                                </ul>
                            </div><br>

                        </div>
                        <form action="{{ route('orders.complete', $order) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn">✅ Complete</button>
                        </form>

                        <!-- <form action="{{ route('orders.cancel', $order) }}" method="POST"
                                                                                                        style="display:inline; margin-left: 0.5rem;">
                                                                                                        @csrf
                                                                                                        <button type="submit" class="btn" style="background: #ff6b6b; color: white;">❌ Cancel</button>
                                                                                                    </form> -->
                    </div>
                @endforeach
            </div>

            <div class="card">
                <h2>✅ Completed Orders</h2>
                @foreach ($completedOrders as $order)
                    <div class="order">
                        <div>
                            <div>
                                Table {{ $order->user->table_number }}<br>

                            </div><br>
                            <ul style="margin-top: 0.4rem; font-size: 0.85rem;">
                                @foreach ($order->items as $item)
                                    <li>{{ $item->quantity }}x {{ $item->product->name ?? 'Product' }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <form action="{{ route('orders.pending', $order) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn">🔄 Back to Pending</button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
    <aside>
        <div class="nav-links">
            <a href="#">Products</a>
            <a href="#">Orders</a>
            <a href="#">Recharges</a>
        </div>

        <div class="bottom-section">
            @if(auth()->user()->qr_path)
                <a href="{{ asset('storage/' . auth()->user()->qr_path) }}" download="qr_bar_{{ auth()->user()->id }}.svg">
                    <img src="{{ asset('storage/' . auth()->user()->qr_path) }}" alt="QR Code" class="qr-img">
                </a>
                <div class="qr-note">Click the QR to download and place it on your tables</div>
            @endif

            <a href="{{ route('logout') }}" class="logout-link"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </aside>
</body>

</html>