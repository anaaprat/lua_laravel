<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/jpeg" href="{{ asset('storage/images/lualogo.jpeg') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña - LUA</title>
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

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 1rem 3rem 1rem 3rem;
            border: 2px solid #E5E7EB;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.2s ease;
            background: #F9FAFB;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #84A98C;
            background: white;
            box-shadow: 0 0 0 3px rgba(132, 169, 140, 0.1);
        }

        input[type="email"]:read-only {
            background: #F3F4F6;
            color: #6B7280;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #84A98C;
            font-size: 1.1rem;
        }

        .toggle-password {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6B7280;
            cursor: pointer;
            font-size: 1.1rem;
            transition: color 0.2s ease;
        }

        .toggle-password:hover {
            color: #84A98C;
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

        .password-requirements {
            background: #F0F9FF;
            border: 1px solid #E0F2FE;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .password-requirements h4 {
            color: #075985;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .password-requirements ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .password-requirements li {
            color: #075985;
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .requirement {
            transition: color 0.2s ease;
        }

        .requirement.valid {
            color: #059669;
        }

        .requirement.invalid {
            color: #DC2626;
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

            <h1 class="welcome-text">Nueva contraseña</h1>
            <p class="subtitle">Introduce tu nueva contraseña para completar el restablecimiento</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-error">
                <strong>⚠ Error</strong><br>
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        <div class="password-requirements">
            <h4>🔒 Requisitos de la contraseña:</h4>
            <ul>
                <li class="requirement" id="length-req">• Mínimo 6 caracteres</li>
                <li class="requirement" id="match-req">• Las contraseñas deben coincidir</li>
            </ul>
        </div>

        <form method="POST" action="{{ route('password.update') }}" id="resetForm">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label for="email">Dirección de correo electrónico</label>
                <div class="input-container">
                    <span class="input-icon">📧</span>
                    <input type="email" id="email" name="email" value="{{ $email ?? old('email') }}" required
                        autocomplete="email" readonly>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Nueva contraseña</label>
                <div class="input-container">
                    <span class="input-icon">🔒</span>
                    <input type="password" id="password" name="password" required autocomplete="new-password"
                        placeholder="Introduce tu nueva contraseña">
                    <span class="toggle-password" onclick="togglePassword('password')">👁️</span>
                </div>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmar nueva contraseña</label>
                <div class="input-container">
                    <span class="input-icon">🔒</span>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        autocomplete="new-password" placeholder="Confirma tu nueva contraseña">
                    <span class="toggle-password" onclick="togglePassword('password_confirmation')">👁️</span>
                </div>
            </div>

            <button type="submit" class="btn" id="submitBtn">
                Cambiar contraseña
            </button>
        </form>

        <div class="back-to-login">
            <a href="{{ route('login') }}">
                ← Volver al inicio de sesión
            </a>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const toggleIcon = field.parentElement.querySelector('.toggle-password');

            if (field.type === 'password') {
                field.type = 'text';
                toggleIcon.textContent = '🙈';
            } else {
                field.type = 'password';
                toggleIcon.textContent = '👁️';
            }
        }

        // Validación en tiempo real
        function validateRequirements() {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;

            // Validar longitud
            const lengthReq = document.getElementById('length-req');
            if (password.length >= 6) {
                lengthReq.classList.remove('invalid');
                lengthReq.classList.add('valid');
                lengthReq.innerHTML = '✓ Mínimo 6 caracteres';
            } else {
                lengthReq.classList.remove('valid');
                lengthReq.classList.add('invalid');
                lengthReq.innerHTML = '• Mínimo 6 caracteres';
            }

            // Validar coincidencia
            const matchReq = document.getElementById('match-req');
            if (password === confirmation && confirmation !== '') {
                matchReq.classList.remove('invalid');
                matchReq.classList.add('valid');
                matchReq.innerHTML = '✓ Las contraseñas coinciden';
            } else {
                matchReq.classList.remove('valid');
                matchReq.classList.add('invalid');
                matchReq.innerHTML = '• Las contraseñas deben coincidir';
            }
        }

        document.getElementById('password').addEventListener('input', validateRequirements);
        document.getElementById('password_confirmation').addEventListener('input', validateRequirements);

        document.getElementById('resetForm').addEventListener('submit', function () {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner"></span>Cambiando contraseña...';
        });
    </script>
</body>

</html>