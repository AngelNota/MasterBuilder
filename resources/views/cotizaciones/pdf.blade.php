<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Presupuesto #{{ $cotizacione->id }} - PC Master Builder</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333333;
            line-height: 1.4;
            font-size: 14px;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .logo-title {
            font-size: 28px;
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .logo-subtitle {
            font-size: 11px;
            color: #4b5563;
            margin-top: 5px;
        }
        .invoice-details {
            text-align: right;
            font-size: 12px;
            color: #4b5563;
        }
        .invoice-title {
            font-size: 20px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 5px;
        }
        .info-section {
            margin-bottom: 30px;
            width: 100%;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-col {
            width: 50%;
            vertical-align: top;
        }
        .info-box {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            margin-right: 10px;
        }
        .info-box-right {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            margin-left: 10px;
        }
        .info-title-box {
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 8px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .components-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .components-table th {
            background-color: #1e3a8a;
            color: #ffffff;
            padding: 10px 12px;
            font-size: 12px;
            text-transform: uppercase;
            font-weight: bold;
            text-align: left;
        }
        .components-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px;
        }
        .components-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .text-right {
            text-align: right !important;
        }
        .text-center {
            text-align: center !important;
        }
        .totals-section {
            width: 100%;
            margin-top: 15px;
        }
        .totals-table {
            width: 300px;
            float: right;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 6px 12px;
            font-size: 13px;
        }
        .totals-table tr.grand-total {
            border-top: 2px solid #3b82f6;
            font-weight: bold;
            font-size: 16px;
            color: #1e3a8a;
        }
        .footer {
            clear: both;
            margin-top: 60px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 11px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="container">
        
        <!-- ENCABEZADO -->
        <div class="header">
            <table class="header-table">
                <tr>
                    <td>
                        <div class="logo-title">PC Master Builder</div>
                        <div class="logo-subtitle">SISTEMA DE CONFIGURACIÓN Y COTIZACIÓN DE HARDWARE</div>
                    </td>
                    <td class="invoice-details">
                        <div class="invoice-title">COTIZACIÓN</div>
                        <div><strong>Folio:</strong> #MB-{{ str_pad($cotizacione->id, 5, '0', STR_PAD_LEFT) }}</div>
                        <div><strong>Fecha:</strong> {{ $cotizacione->created_at->format('d/m/Y H:i') }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- INFORMACIÓN CLIENTE / PROVEEDOR -->
        <div class="info-section">
            <table class="info-table">
                <tr>
                    <td class="info-col">
                        <div class="info-box">
                            <div class="info-title-box">Cliente</div>
                            <div><strong>Nombre:</strong> {{ $cotizacione->user->name }} {{ $cotizacione->user->last_name }}</div>
                            <div><strong>Correo:</strong> {{ $cotizacione->user->email }}</div>
                        </div>
                    </td>
                    <td class="info-col">
                        <div class="info-box-right">
                            <div class="info-title-box">Emisor</div>
                            <div><strong>Empresa:</strong> PC Master Builder S.A. de C.V.</div>
                            <div><strong>Dirección:</strong> Av. Universidad 1200, CDMX, México</div>
                            <div><strong>Contacto:</strong> soporte@pcmasterbuilder.com</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- TABLA DE COMPONENTES -->
        <table class="components-table">
            <thead>
                <tr>
                    <th>Componente</th>
                    <th>Categoría</th>
                    <th class="text-center">Cant.</th>
                    <th class="text-right">Precio Unitario</th>
                    <th class="text-right">Importe</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cotizacione->components as $component)
                    <tr>
                        <td>
                            <strong>{{ $component->marca }}</strong> {{ $component->nombre }}
                            <div style="font-size: 10px; color: #6b7280;">Modelo: {{ $component->modelo ?? 'N/A' }}</div>
                        </td>
                        <td style="color: #4b5563;">{{ $component->category->name }}</td>
                        <td class="text-center">{{ $component->pivot->cantidad }}</td>
                        <td class="text-right font-mono">${{ number_format($component->pivot->precio_unitario, 2) }} MXN</td>
                        <td class="text-right font-mono" style="font-weight: bold;">${{ number_format($component->pivot->precio_unitario * $component->pivot->cantidad, 2) }} MXN</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- TOTALES -->
        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td>Subtotal:</td>
                    <td class="text-right font-mono">${{ number_format($cotizacione->subtotal, 2) }} MXN</td>
                </tr>
                <tr>
                    <td>IVA (16%):</td>
                    <td class="text-right font-mono">${{ number_format($cotizacione->iva, 2) }} MXN</td>
                </tr>
                <tr class="grand-total">
                    <td>Total:</td>
                    <td class="text-right font-mono">${{ number_format($cotizacione->total, 2) }} MXN</td>
                </tr>
            </table>
        </div>

        <!-- PIE DE PÁGINA -->
        <div class="footer">
            <p>Este documento es un presupuesto formal generado de forma interactiva en la plataforma PC Master Builder.</p>
            <p>Precios sujetos a cambios sin previo aviso. Vigencia de la cotización: 15 días naturales a partir de la fecha de emisión.</p>
            <p>&copy; {{ date('Y') }} PC Master Builder. Todos los derechos reservados.</p>
        </div>

    </div>
</body>
</html>
