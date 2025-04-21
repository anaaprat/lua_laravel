<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Bar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 3rem auto;
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 2rem;
        }

        .section {
            margin-bottom: 2rem;
        }

        .info {
            background: #e9ecef;
            padding: 1rem;
            border-radius: 5px;
        }

        .qr-box {
            text-align: center;
        }

        img.qr {
            width: 200px;
            margin-top: 1rem;
            border: 1px solid #ccc;
            padding: 5px;
            border-radius: 5px;
        }

        .links {
            text-align: center;
            margin-top: 2rem;
        }

        .links a {
            margin: 0.5rem 1rem;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            transition: color 0.2s;
        }

        .links a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>👋 Bienvenido, {{ auth()->user()->name }}</h1>

        <div class="section info">
            <p><strong>🔑 Token único:</strong> {{ auth()->user()->token }}</p>
        </div>

        <div class="section qr-box">
            <p><strong>📲 QR para tus mesas:</strong></p>
            @if(auth()->user()->qr_path)
                <img src="{{ asset('storage/' . auth()->user()->qr_path) }}" alt="Código QR del bar" class="qr">
            @else
                <p style="color: red;">⚠️ No se ha generado el QR.</p>
            @endif
        </div>

        <div class="links">
            <a href="#">🛒 Ver productos</a>
            <a href="#">📦 Ver pedidos</a>
            <a href="#">💳 Recargas realizadas</a>
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                🔓 Cerrar sesión
            </a>
        </div>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</body>
</html>
