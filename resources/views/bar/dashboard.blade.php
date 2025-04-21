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
            background: linear-gradient(to bottom, #cad2c5, #84a98c, #52796f);
            min-height: 100vh;
            padding: 2rem;
            color: #fff;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        header h1 {
            font-size: 1.8rem;
        }

        .logout {
            background: #354f52;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            color: #fff;
            font-weight: bold;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .logout:hover {
            background: #2f3e46;
        }

        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .card {
            background: rgba(255, 255, 255, 0.1);
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 0.5rem;
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
            background: rgba(255, 255, 255, 0.05);
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order span {
            font-size: 0.95rem;
        }

        .section {
            margin-top: 3rem;
            text-align: center;
        }

        .qr img {
            margin-top: 1rem;
            width: 200px;
            border-radius: 8px;
            border: 1px solid #fff;
            padding: 5px;
        }

        .links {
            margin-top: 2rem;
        }

        .links a {
            display: inline-block;
            margin: 0.5rem 1rem;
            text-decoration: none;
            background: #354f52;
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            transition: background 0.3s;
        }

        .links a:hover {
            background: #2f3e46;
        }
    </style>
</head>

<body>
    <header>
        <h1>👋 Welcome, {{ auth()->user()->name }}</h1>
        <a href="{{ route('logout') }}" class="logout"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            🔓 Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </header>

    <div class="card">
        <h2>🕒 Pending Orders</h2>
        @foreach ($orders->where('status', 'pending') as $order)
            <div class="order">
                <span>Order #{{ $order->id }}</span>
                <a href="#">Manage</a>
            </div>
        @endforeach
    </div>

    <div class="card">
        <h2>✅ Completed Orders</h2>
        @foreach ($orders->where('status', 'completed') as $order)
            <div class="order">
                <span>Order #{{ $order->id }}</span>
                <a href="#">View</a>
            </div>
        @endforeach
    </div>
    </div>

    <div class="section links">
        <a href="#">🛒 Products</a>
        <a href="#">📦 Orders</a>
        <a href="#">💳 Recharges</a>
    </div>
</body>

</html>