<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register - LUA</title>
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

        .back-link:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .register-box {
            background: rgba(255, 255, 255, 0.15);
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 420px;
            backdrop-filter: blur(10px);
            color: white;
            animation: fadeIn 1s ease-out;
            font-weight: 400;
        }

        .register-box h2 {
            text-align: center;
            margin-bottom: 1.2rem;
            font-size: 1.6rem;
            font-family: 'Playfair Display', serif;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-bottom: 0.4rem;
            font-weight: 500;
            font-size: 0.95rem;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.5rem;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 400;
        }

        button {
            width: 100%;
            padding: 0.65rem;
            background: #2f3e46;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s ease;
            font-size: 1rem;
        }

        button:hover {
            background: #354f52;
        }

        .error {
            color: #ffb3b3;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            text-align: center;
        }

        .logo {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 35px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
        }

        .login-redirect {
            margin-top: 1rem;
            text-align: center;
            font-size: 0.95rem;
        }

        .login-redirect a {
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
    <div class="register-box">
        <h2>Register your bar</h2>

        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}">
            @csrf
            <div class="form-group">
                <label for="name">Bar name</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirm password</label>
                <input type="password" name="password_confirmation" required>
            </div>
            <div class="form-group">
                <label for="table_number">Number of tables</label>
                <input type="number" name="table_number" min="1" max="100" value="{{ old('table_number', 1) }}"
                    required>
                <small style="color: rgba(255, 255, 255, 0.6); font-size: 0.8rem;">
                    Define how many tables will be available for your customers.
                </small>
            </div>
            <button type="submit">Register</button>
        </form>

        <div class="login-redirect">
            Already have an account? <a href="{{ route('login') }}">Log in</a>
        </div>
    </div>
</body>

</html>