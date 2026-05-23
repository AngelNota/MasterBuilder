<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tu Presupuesto - PC Master Builder</title>
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
        .summary-box {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .summary-title {
            font-weight: bold;
            margin-bottom: 12px;
            font-size: 15px;
            color: #1f2937;
            text-transform: uppercase;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 8px;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .total-row {
            font-weight: bold;
            font-size: 16px;
            color: #1e3a8a;
            border-top: 1px dashed #d1d5db;
            padding-top: 8px;
            margin-top: 8px;
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
            <p>Hola <strong>{{ $quote->user->name }}</strong>,</p>
            
            <p>¡Gracias por usar PC Master Builder para configurar tu computadora! Hemos generado tu presupuesto formal con éxito.</p>
            
            <p>Adjunto a este correo electrónico encontrarás el archivo PDF formal con el desglose completo, precios unitarios e IVA. A continuación te presentamos un resumen rápido de tu ensamble:</p>
            
            <div class="summary-box">
                <div class="summary-title">Resumen de Cotización #{{ $quote->id }}</div>
                
                @foreach($quote->components as $component)
                    <div class="summary-item">
                        <span>{{ $component->marca }} {{ $component->nombre }} (x{{ $component->pivot->cantidad }})</span>
                        <strong style="margin-left: auto;">${{ number_format($component->pivot->precio_unitario * $component->pivot->cantidad, 2) }} MXN</strong>
                    </div>
                @endforeach
                
                <div class="summary-item total-row" style="margin-top: 15px; border-top: 1px solid #e5e7eb; padding-top: 10px;">
                    <span>Total con IVA:</span>
                    <strong style="margin-left: auto;">${{ number_format($quote->total, 2) }} MXN</strong>
                </div>
            </div>
            
            <p>Si tienes alguna pregunta sobre la compatibilidad de los componentes, no dudes en responder a este correo.</p>
            
            <a href="{{ route('cotizaciones.show', $quote->id) }}" class="btn">Ver Cotización en Línea</a>
        </div>
        
        <div class="footer">
            <p>Este es un correo automático enviado por PC Master Builder.</p>
            <p>&copy; {{ date('Y') }} PC Master Builder. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
