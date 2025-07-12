<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cotización #{{ $cotizacion->num_consecutivo }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { font-size: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f3f3f3; text-align: left; }
        .totales td { font-weight: bold; }
    </style>
</head>
<body>

    <h1>Cotización #{{ $cotizacion->num_consecutivo }}</h1>

    <p><strong>Fecha:</strong> {{ $cotizacion->fecha_alta->format('d/m/Y') }}</p>
    <p><strong>Válida hasta:</strong> {{ $cotizacion->vencimiento->format('d/m/Y') }}</p>
    <p><strong>Cliente:</strong> {{ $cotizacion->cliente->nombre ?? 'N/D' }}</p>
    <p><strong>Razón social:</strong> {{ $cotizacion->razonSocial->nombre ?? 'N/D' }}</p>
    <p><strong>Dirección de entrega:</strong> {{ $cotizacion->direccionEntrega->direccion ?? 'N/D' }}</p>
    <p><strong>Contacto entrega:</strong> {{ $cotizacion->contactoEntrega->nombre ?? 'N/D' }}</p>
    <p><strong>Vendedor:</strong> {{ $cotizacion->vendedor->nombre ?? 'N/D' }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Descripción</th>
                <th>Proveedor</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Importe</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cotizacion->partidas as $i => $partida)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $partida->descripcion }}</td>
                <td>{{ $partida->proveedor ?? 'N/D' }}</td>
                <td>{{ $partida->cantidad }}</td>
                <td>${{ number_format($partida->precio_unitario, 2) }}</td>
                <td>${{ number_format($partida->importe, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="totales">
                <td colspan="5" style="text-align: right;">Subtotal</td>
                <td>${{ number_format($cotizacion->subtotal, 2) }}</td>
            </tr>
            <tr class="totales">
                <td colspan="5" style="text-align: right;">IVA 16%</td>
                <td>${{ number_format($cotizacion->iva, 2) }}</td>
            </tr>
            <tr class="totales">
                <td colspan="5" style="text-align: right;">Total</td>
                <td>${{ number_format($cotizacion->total, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    @if ($cotizacion->notas_entrega)
        <p><strong>Notas para entrega:</strong><br>{{ $cotizacion->notas_entrega }}</p>
    @endif

</body>
</html>
