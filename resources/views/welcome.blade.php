<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Playfair+Display:wght@500&family=Raleway:wght@600&display=swap"
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
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        nav {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 15px 30px;
            backdrop-filter: blur(10px);
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-weight: 600;
            padding: 10px 20px;
            border: 2px solid white;
            border-radius: 8px;
            transition: background 0.3s, color 0.3s;
        }

        nav a:hover {
            background: white;
            color: #2f3e46;
        }

        .logo {
            width: 140px;
            height: 140px;
            object-fit: cover;
            border-radius: 50%;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            margin-bottom: 30px;
            z-index: 2;
            animation: fadeIn 1.2s ease-out forwards;
        }

        .slogan {
            font-size: 1.6rem;
            font-weight: 600;
            color: #ffffff;
            padding: 15px 60px;
            border-radius: 12px;
            backdrop-filter: blur(5px);
            text-align: center;
            max-width: 1000px;
            z-index: 2;
            animation: slideUp 1.2s ease-out 0.4s forwards;
            opacity: 0;
        }

        .slider {
            margin-top: 40px;
            overflow: hidden;
            width: 100%;
            position: absolute;
            bottom: 30px;
        }

        .slider-track {
            display: flex;
            width: calc(250px * 10);
            animation: scroll 30s linear infinite;
        }

        .slider-track img {
            width: 250px;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
            margin: 0 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: scale(0.9);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes slideUp {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes scroll {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }
    </style>
</head>

<body>
    <nav>
        <a href="{{ route('login') }}">Login</a>
        <a href="{{ route('register') }}">Register</a>
    </nav>
    <img src="{{ asset('storage/images/lualogo.jpeg') }}" alt="Lua Logo" class="logo">
    <div class="slogan">
        Skip the paper. Speed up service. Lua takes care of it.
    </div>
    <div class="slider">
        <div class="slider-track">
            <img src="{{ asset('storage/images/bar1.jpg') }}" alt="Bar 1">
            <img src="{{ asset('storage/images/bar2.jpg') }}" alt="Bar 2">
            <img src="{{ asset('storage/images/bar3.jpg') }}" alt="Bar 3">
            <img src="{{ asset('storage/images/bar4.jpg') }}" alt="Bar 4">
            <img src="{{ asset('storage/images/bar1.jpg') }}" alt="Bar 1 copy">
            <img src="{{ asset('storage/images/bar2.jpg') }}" alt="Bar 2 copy">
            <img src="{{ asset('storage/images/bar3.jpg') }}" alt="Bar 3 copy">
            <img src="{{ asset('storage/images/bar4.jpg') }}" alt="Bar 4 copy">
        </div>
    </div>
</body>

</html>