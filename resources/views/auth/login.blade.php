<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/jpeg" href="{{ asset('storage/images/lualogo.jpeg') }}">
    <title>Login - LUA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500&family=Raleway:wght@400;500&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Raleway', sans-serif;
        }

        body {
            height: 100vh;
            background: linear-gradient(to bottom, #cad2c5, #84a98c, #52796f);
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            position: relative;
        }

        .login-box {
            background: rgba(255, 255, 255, 0.15);
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            backdrop-filter: blur(10px);
            color: white;
            animation: fadeIn 1s ease-out;
            font-weight: 400;
        }

        .back-link {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 2.7rem;
            color: white;
            text-decoration: none;
            font-weight: bold;
            padding: 8px 14px;
            border-radius: 8px;
            transition: background 0.3s;
        }

        .login-box h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            font-family: 'Playfair Display', serif;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.6rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 400;
        }

        button {
            width: 100%;
            padding: 0.75rem;
            background: #2f3e46;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
            position: relative;
        }

        button:hover:not(:disabled) {
            background: #354f52;
        }

        /* 🔒 Estilos para botón deshabilitado */
        button:disabled {
            background: #6b7280;
            cursor: not-allowed;
            opacity: 0.7;
        }

        /* ⏳ Spinner de carga */
        .spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid #ffffff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 8px;
        }

        button:disabled .spinner {
            display: inline-block;
        }

        button:disabled .button-text {
            opacity: 0.8;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .error {
            color: rgb(177, 43, 43);
            font-size: 0.9rem;
            padding: 0.5rem;
            text-align: center;
        }

        .success {
            color: rgb(34, 197, 94);
            font-size: 0.9rem;
            padding: 0.5rem;
            text-align: center;
        }

        .logo {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 40px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
        }

        .forgot-password {
            text-align: center;
            margin-top: 1rem;
            margin-bottom: 0.5rem;
        }

        .forgot-password a {
            color: white;
            text-decoration: none;
            font-size: 0.9rem;
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }

        .forgot-password a:hover {
            opacity: 1;
            text-decoration: underline;
        }

        .register-redirect {
            margin-top: 1rem;
            text-align: center;
            font-size: 0.95rem;
        }

        .register-redirect a {
            color: white;
            text-decoration: underline;
            font-weight: 500;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>

<body>
    <a href="{{ url('/') }}" class="back-link">←</a>
    <img src="{{ asset('storage/images/lualogo.jpeg') }}" alt="Lua Logo" class="logo">
    <div class="login-box">
        <h2>Login to your account</h2>

        @if (session('status'))
            <div class="success">{{ session('status') }}</div>
        @endif

        @if (session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" id="loginForm">
            @csrf
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit" id="loginButton">
                <span class="spinner"></span>
                <span class="button-text">Login</span>
            </button>
        </form>

        <div class="forgot-password">
            <a href="{{ route('password.request') }}">Forgot your password?</a>
        </div>

        <div class="register-redirect">
            Don't have an account? <a href="{{ route('register') }}">Register you bar here</a>
        </div>
    </div>

    <script>
        //Para doble click 
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('loginForm');
            const button = document.getElementById('loginButton');
            const buttonText = button.querySelector('.button-text');

            form.addEventListener('submit', function (e) {
                if (button.disabled) {
                    e.preventDefault();
                    return false;
                }

                button.disabled = true;
                buttonText.textContent = 'Logging in...';

                setTimeout(function () {
                    if (button.disabled) {
                        button.disabled = false;
                        buttonText.textContent = 'Login';
                    }
                }, 10000);
            });

            if (document.querySelector('.error')) {
                button.disabled = false;
                buttonText.textContent = 'Login';
            }
        });
    </script>
</body>

</html>