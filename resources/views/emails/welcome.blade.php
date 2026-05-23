<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>¡Bienvenido a PC Master Builder!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
            color: #374151;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid #e5e7eb;
        }
        .header {
            background-color: #1e3a8a;
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .content {
            padding: 30px;
            line-height: 1.6;
        }
        .welcome-box {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .welcome-title {
            font-weight: bold;
            margin-bottom: 12px;
            font-size: 18px;
            color: #1e3a8a;
        }
        .btn {
            display: block;
            width: 200px;
            margin: 30px auto 0;
            text-align: center;
            background-color: #2563eb;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 15px;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>PC Master Builder</h1>
        </div>
        
        <div class="content">
            <p>Hola <strong>{{ $user->name }}</strong>,</p>
            
            <p>¡Te damos la más cordial bienvenida a <strong>PC Master Builder</strong>! Nos alegra mucho tenerte con nosotros.</p>
            
            <div class="welcome-box">
                <div class="welcome-title">¡Tu cuenta está lista!</div>
                <p style="margin: 0; font-size: 14px; color: #4b5563;">Ahora puedes armar tu presupuesto ideal, comparar componentes y recibir el análisis de compatibilidad técnica con nuestro asistente de IA.</p>
            </div>
            
            <p>Comienza a cotizar y configurar tu próxima computadora personalizada ahora mismo desde nuestro panel de control.</p>
            
            <a href="{{ route('dashboard') }}" class="btn">Ir al Dashboard</a>
        </div>
        
        <div class="footer">
            <p>Este es un correo automático enviado por PC Master Builder.</p>
            <p>&copy; {{ date('Y') }} PC Master Builder. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
