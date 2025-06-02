<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña - LUA</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Inter', 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #CAD2C5 0%, #84A98C 50%, #52796F 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .logo {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            backdrop-filter: blur(10px);
        }

        .logo-text {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 2px;
            color: white;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .header p {
            margin: 0;
            font-size: 16px;
            opacity: 0.9;
        }

        .content {
            padding: 40px 30px;
        }

        .content h2 {
            color: #52796F;
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: 600;
        }

        .content p {
            color: #374151;
            line-height: 1.6;
            margin-bottom: 20px;
            font-size: 16px;
        }

        .button-container {
            text-align: center;
            margin: 30px 0;
        }

        .button {
            display: inline-block;
            background: #84A98C;
            color: white !important;
            padding: 16px 32px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.2s ease;
        }

        .button:hover {
            background: #52796F;
            transform: translateY(-1px);
        }

        .info-box {
            background: #F0F9FF;
            border: 1px solid #E0F2FE;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }

        .info-box p {
            color: #075985;
            margin: 0;
            font-size: 14px;
        }

        .info-box .icon {
            font-size: 18px;
            margin-right: 8px;
        }

        .security-tip {
            background: #F0FDF4;
            border: 1px solid #BBF7D0;
            border-left: 4px solid #84A98C;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }

        .security-tip p {
            color: #047857;
            margin: 0;
            font-size: 14px;
        }

        .url-box {
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            word-break: break-all;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 12px;
            color: #374151;
        }

        .footer {
            background: #F9FAFB;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #E5E7EB;
        }

        .footer p {
            color: #6B7280;
            font-size: 14px;
            margin: 8px 0;
        }

        .footer .logo-small {
            color: #84A98C;
            font-weight: 700;
            font-size: 18px;
            letter-spacing: 1px;
        }

        @media (max-width: 600px) {
            .container {
                margin: 10px;
                border-radius: 12px;
            }

            .content {
                padding: 30px 20px;
            }

            .header {
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('storage/images/lualogo.jpeg') }}" alt="Lua Logo" class="logo">
            <h1>🔐 Restablecer contraseña</h1>
            <p>Tu solicitud de cambio de contraseña</p>
        </div>

        <div class="content">
            <h2>¡Hola!</h2>

            <p>Hemos recibido una solicitud para restablecer la contraseña de tu cuenta en <strong>LUA</strong>.</p>

            <p>Si no has sido tú quien ha solicitado este cambio, puedes ignorar este email de forma segura.</p>

            <div class="button-container">
                <a href="{{ $actionUrl }}" class="button">
                    🔑 Restablecer mi contraseña
                </a>
            </div>

            <div class="info-box">
                <p><span class="icon">⏰</span><strong>Importante:</strong> Este enlace expirará en
                    {{ config('auth.passwords.' . config('auth.defaults.passwords') . '.expire') }} minutos por razones
                    de
                    seguridad.
                </p>
            </div>

            <p><strong>¿El botón no funciona?</strong> Puedes copiar y pegar el siguiente enlace en tu navegador:</p>

            <div class="url-box">
                {{ $actionUrl }}
            </div>

            <div class="security-tip">
                <p><strong>💡 Consejo de seguridad:</strong> Asegúrate de elegir una contraseña fuerte que combine
                    letras mayúsculas, minúsculas, números y caracteres especiales. ¡Mantendrás tu cuenta más segura!
                </p>
            </div>

            <p>Si tienes algún problema o no has solicitado este cambio, no dudes en contactarnos respondiendo a este
                email.</p>

            <p style="margin-top: 30px;">
                Un saludo,<br>
                <strong>El equipo de LUA 🍹</strong>
            </p>
        </div>

        <div class="footer">
            <p class="logo-small">LUA</p>
            <p>Sistema de gestión de bares - Calle del Lua, San Fernando</p>
            <p>Si no solicitaste este cambio, puedes ignorar este mensaje de forma segura.</p>
            <p style="color: #9CA3AF; font-size: 12px;">
                Este email fue generado automáticamente, por favor no respondas a esta dirección.
            </p>
        </div>
    </div>
</body>

</html>