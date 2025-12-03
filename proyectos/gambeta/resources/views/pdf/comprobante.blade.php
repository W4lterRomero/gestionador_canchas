<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Comprobante de Pago</title>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 25px;
        color: #333;
        font-size: 14px;
    }

    .header {
        text-align: center;
        padding-bottom: 15px;
        border-bottom: 3px solid #056839;
        margin-bottom: 25px;
    }

    .titulo-sistema {
        font-size: 20px;
        font-weight: bold;
        color: #056839;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .subtitulo {
        font-size: 15px;
        margin-top: 5px;
        color: #333;
    }

    .comprobante-box {
        margin-top: 10px;
        padding: 10px 0;
        font-size: 13px;
        border-bottom: 1px dashed #999;
    }

    h2 {
        color: #056839;
        font-size: 18px;
        margin-bottom: 8px;
    }

    .section {
        margin-bottom: 15px;
    }

    .label {
        font-weight: bold;
        margin-bottom: 3px;
    }

    .tabla {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        font-size: 14px;
    }

    .tabla td, .tabla th {
        padding: 8px;
        border: 1px solid #ccc;
    }

    .tabla th {
        background: #056839;
        color: white;
        text-align: left;
    }

    .total-box {
        margin-top: 25px;
        padding: 15px;
        background: #e8f5ee;
        border-left: 4px solid #056839;
        font-size: 16px;
        color: #056839;
    }

    .total-box div {
        margin-bottom: 6px;
    }

    .footer {
        margin-top: 40px;
        text-align: center;
        font-size: 12px;
        color: #666;
    }
</style>
</head>

<body>

<div class="header">
    <div class="titulo-sistema">Sistema Nacional de Áreas Deportivas</div>
    <div class="subtitulo">Comprobante oficial de pago</div>
</div>

<div class="comprobante-box">
    <strong>Fecha de emisión:</strong> {{ now()->format('d/m/Y H:i') }}
</div>

<h2>Datos del Cliente</h2>
<table class="tabla">
    <tr>
        <th>Nombre del Cliente</th>
        <td>{{ $cliente }}</td>
    </tr>
    <tr>
        <th>Teléfono</th>
        <td>{{ $telefono }}</td>
    </tr>
</table>

<h2>Detalles de la Reserva</h2>
<table class="tabla">
    <tr>
        <th>Cancha</th>
        <td>{{ $cancha }}</td>
    </tr>
    <tr>
        <th>Fecha y Hora</th>
        <td>{{ $fecha }} — {{ $hora }}</td>
    </tr>
    <tr>
        <th>Duración</th>
        <td>{{ $duracion }} hora(s)</td>
    </tr>
</table>

<h2>Resumen de Pago</h2>
<div class="total-box">

    <div><strong>Estado del Pago:</strong> {{ ucfirst($estadoPago) }}</div>

    <div><strong>Total de la Reserva:</strong> ${{ number_format($total, 2) }}</div>

    <div>
        <strong>Adelanto Recibido:</strong>
        @if($estadoPago === 'pagado')
            ${{ number_format($total, 2) }}
        @elseif($estadoPago === 'adelanto')
            ${{ number_format($adelanto, 2) }}
        @else
            $0.00
        @endif
    </div>

    <div><strong>Total a Pagar (Factura):</strong> ${{ number_format($totalPagar, 2) }}</div>

</div>

<div class="footer">
    Este comprobante es válido como constancia oficial de pago.<br>
    Gracias por utilizar el Sistema Nacional de Áreas Deportivas.
</div>

</body>
</html>
