<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/jpeg" href="{{ asset('storage/images/lualogo.jpeg') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - LUA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #CAD2C5 0%, #84A98C 50%, #52796F 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo {
            width: 80px;
            height: 80px;
            background: #52796F;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 4px 12px rgba(82, 121, 111, 0.3);
        }

        .logo-text {
            color: white;
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 2px;
        }

        .welcome-text {
            color: #52796F;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .subtitle {
            color: #6B7280;
            font-size: 0.95rem;
            line-height: 1.5;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #374151;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .input-container {
            position: relative;
        }

        input[type="email"] {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid #E5E7EB;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.2s ease;
            background: #F9FAFB;
        }

        input[type="email"]:focus {
            outline: none;
            border-color: #84A98C;
            background: white;
            box-shadow: 0 0 0 3px rgba(132, 169, 140, 0.1);
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #84A98C;
            font-size: 1.1rem;
        }

        .btn {
            width: 100%;
            padding: 1rem;
            background: #84A98C;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 1rem;
        }

        .btn:hover {
            background: #52796F;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(82, 121, 111, 0.3);
        }

        .btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .alert-success {
            background-color: #D1FAE5;
            color: #047857;
            border: 1px solid #A7F3D0;
        }

        .alert-error {
            background-color: #FEE2E2;
            color: #DC2626;
            border: 1px solid #FECACA;
        }

        .back-to-login {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #E5E7EB;
        }

        .back-to-login a {
            color: #84A98C;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: color 0.2s ease;
        }

        .back-to-login a:hover {
            color: #52796F;
        }

        .info-card {
            background: #F0F9FF;
            border: 1px solid #E0F2FE;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .info-card p {
            color: #075985;
            font-size: 0.85rem;
            margin: 0;
            line-height: 1.4;
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid #ffffff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            display: inline-block;
            margin-right: 0.5rem;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 2rem 1.5rem;
                margin: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo-section">
            <img src="{{ asset('storage/images/lualogo.jpeg') }}" alt="Lua Logo" class="logo">
            <h1 class="welcome-text">Recuperar contraseña</h1>
            <p class="subtitle">Introduce tu email y te enviaremos un enlace para restablecer tu contraseña</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success">
                <strong>✓ Enviado correctamente</strong><br>
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <strong>⚠ Error</strong><br>
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        <div class="info-card">
            <p><strong>💡 Información:</strong> El enlace de recuperación expirará en 60 minutos por seguridad. Revisa
                también tu carpeta de spam.</p>
        </div>

        <form method="POST" action="{{ route('password.email') }}" id="resetForm">
            @csrf

            <div class="form-group">
                <label for="email">Dirección de correo electrónico</label>
                <div class="input-container">
                    <span class="input-icon">📧</span>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                        placeholder="tu-email@ejemplo.com">
                </div>
            </div>

            <button type="submit" class="btn" id="submitBtn">
                Enviar enlace de recuperación
            </button>
        </form>

        <div class="back-to-login">
            <a href="{{ route('login') }}">
                ← Volver al inicio de sesión
            </a>
        </div>
    </div>

    <script>
        document.getElementById('resetForm').addEventListener('submit', function () {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner"></span>Enviando...';
        });
    </script>
</body>

</html>