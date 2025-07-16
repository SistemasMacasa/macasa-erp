<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Cotización #{{ $cotizacion->num_consecutivo }}</title>

    <style>
        @page {
            margin: 28px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10.5px;
            color: #222;
        }

        h1 {
            font-size: 19px;
            color: #0050a5;
            text-align: center;
            margin: 18px 0 14px;
        }

        /* Tablas genéricas */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 0.6px solid #b5b5b5;
            padding: 6px;
            vertical-align: top;
        }

        th {
            background: #f4f6f8;
            font-weight: bold;
        }

        /* Tablas sin borde externo */
        .no-border td {
            border: none;
            padding: 2px 0;
        }

        /* Tabla meta (datos rápidos) */
        .meta td {
            border: 0.6px solid #d1d1d1;
        }

        .meta td.label {
            background: #f4f6f8;
            font-weight: bold;
            width: 28%;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .section {
            margin-top: 22px;
        }
    </style>
</head>

<body>

    {{-- LOGO --}}
    <div style="text-align:center;">
        <img src="{{ public_path('assets/images/logo.png') }}" style="width:175px;">
    </div>

    {{-- Línea divisoria --}}
    <hr style="border:0; border-top:0.6px solid #b5b5b5; margin:8px 0 20px;">

    {{-- Emisor / Dirección de entrega --}}
    <table class="no-border">
        <tr>
            <td style="width:47%;">
                <strong>Emite</strong><br>
                MC Comercializadora de Cómputo y Redes<br>
                Av. Primero de Mayo #15 Int. 1025<br>
                Naucalpan de Juárez, 53500, México<br>
                01&nbsp;(55)&nbsp;5003-2830<br>
                Email: mcarreon@macasahs.com.mx
            </td>

            <td style="width:53%;">
                <strong>Dirección de Entrega</strong><br>
                {{ $cotizacion->contactoEntrega->nombre ?? 'Nombre de Contacto' }}<br>
                {{ $cotizacion->contactoEntrega->direccion_entrega->calle ?? '' }}
                {{ $cotizacion->contactoEntrega->direccion_entrega->num_ext ?? '' }}<br>
                {{ $cotizacion->contactoEntrega->direccion_entrega->colonia->d_asenta ?? '' }}<br>
                {{ $cotizacion->contactoEntrega->direccion_entrega->colonia->D_mnpio ?? '' }},
                {{ $cotizacion->contactoEntrega->direccion_entrega->cp ?? '' }}<br>
                {{ $cotizacion->contactoEntrega->direccion_entrega->colonia->d_estado ?? '' }}<br>
                Teléfono: {{ $cotizacion->contactoEntrega->telefono1 ?? '-' }}<br>
                Email: {{ $cotizacion->contactoEntrega->email ?? '-' }}
            </td>
        </tr>
    </table>

    {{-- Título --}}
    <h1>Cotización # {{ $cotizacion->num_consecutivo }}</h1>

    {{-- Datos clave --}}
    <table class="meta">
        <tr>
            <td class="label">Vendedor</td>
            <td>{{ $cotizacion->vendedor->nombreCompleto ?? 'N/D' }}</td>
            <td class="label">Comprador</td>
            <td>{{ $cotizacion->cliente->contacto_predet->nombreCompleto ?? 'N/D' }}</td>
        </tr>
        <tr>
            <td class="label">Fecha</td>
            <td>{{ $cotizacion->fecha_alta->format('d/m/Y') }}</td>
            <td class="label">Vigencia</td>
            <td>{{ $cotizacion->vencimiento->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td class="label">Condición de Pago</td>
            <td>[Condición&nbsp;por&nbsp;definir]</td>
            <td class="label">Tiempo de Entrega</td>
            <td>[Entrega&nbsp;por&nbsp;definir]</td>
        </tr>
        <tr>
            <td class="label">Moneda</td>
            <td>MXN</td>
            <td class="label"></td>
            <td></td>
        </tr>
    </table>

    {{-- Productos --}}
    <div class="section">
        <table>
            <thead>
                <tr>
                    <th style="width:17%;">SKU</th>
                    <th>Descripción</th>
                    <th style="width:16%;">Precio Unitario</th>
                    <th style="width:10%;">Cantidad</th>
                    <th style="width:16%;">Importe</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cotizacion->partidas as $p)
                    <tr>
                        <td>{{ $p->sku ?? '-' }}</td>
                        <td>{{ $p->descripcion }}</td>
                        <td class="text-right">${{ number_format($p->precio_unitario, 2) }}</td>
                        <td class="text-center">{{ $p->cantidad }}</td>
                        <td class="text-right">${{ number_format($p->importe, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Totales + Datos bancarios --}}
    <table class="no-border" style="margin-top:20px;">
        <tr>
            <td style="width:60%; vertical-align:top;">
                <p style="margin-bottom:4px;"><strong>Favor de realizar su pago a nombre de:</strong><br>
                    MC Comercializadora de Cómputo y Redes, S.A de C.V.<br>
                    BANCO SCOTIABANK<br>
                    Cuenta 00101474386<br>
                    CLABE 044180001014743869
                </p>

                <p><strong>Aspectos importantes de nuestro proceso:</strong></p>
                <ol style="font-size:9px; margin-left:14px; padding-right:8px;">
                    <li>Precios y condiciones sujetos a cambio sin previo aviso.</li>
                    <li>Una vez confirmado el pedido, no hay devoluciones.</li>
                    <li>Pedidos &lt; $2,500 + IVA: cargo de envío $150 + IVA.</li>
                    <li>Confirme procesos de garantía con su ejecutivo antes de autorizar el pedido.</li>
                </ol>
            </td>

            <td style="width:40%; vertical-align:top;">
                <table>
                    <tr>
                        <td class="text-right"><strong>Subtotal:</strong></td>
                        <td class="text-right">${{ number_format($cotizacion->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="text-right"><strong>IVA (16%):</strong></td>
                        <td class="text-right">${{ number_format($cotizacion->iva, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="text-right"><strong>Total:</strong></td>
                        <td class="text-right">${{ number_format($cotizacion->total, 2) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>

</html>