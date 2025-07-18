<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Cotización #{{ $cotizacion->num_consecutivo }}</title>

    {{-- ================= ESTILOS GLOBAL ================= --}}
    <style>
        @page {
            margin-top: 70px;
            /* ya está bien */
            margin-bottom: 100px;
            /* antes 88 px → ahora igual a la nueva altura */
            margin-left: 20mm;
            margin-right: 20mm;
        }


        /* 60 mm deja espacio seguro al pie */

        .footer-logos td {
            width: 20%;
        }

        /* distribución exacta de logos */


        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10.2px;
            color: #2e2e2e;
            line-height: 1.4;
        }

        h1 {
            font-size: 19px;
            color: #003366;
            margin: 0 0 4px;
            text-transform: uppercase;
        }

        h2 {
            font-size: 13px;
            color: #003366;
            margin: 0 0 4px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 4px 6px;
            vertical-align: top;
            line-height: 1.35;
            word-wrap: break-word;
        }

        th {
            background: #e6edff;
            font-weight: bold;
            font-size: 10px;
        }

        tbody tr:nth-child(even) {
            background: #f7f9fb;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .fw-bold {
            font-weight: bold;
        }

        /* META tabla */
        .meta-table td.label {
            background: #f4f6f8;
            font-weight: bold;
            width: 22%;
        }

        .meta-table td {
            border: 0.6px solid #d1d1d1;
        }

        /* Totales */
        .totales-card {
            background: #f0f6ff;
            border-radius: 6px;
            padding: 6px 10px;
            width: 100%;
            font-size: 10px;
        }

        .totales-card td.label {
            text-align: right;
            padding-right: 8px;
            font-weight: bold;
            width: 70%;
        }

        .totales-card td.value {
            text-align: right;
            width: 30%;
        }

        .totales-card tr.total td {
            font-size: 11.5px;
        }

        .totales-wrapper {
            page-break-inside: avoid;
        }

        /* Bullets */
        ul.blue {
            list-style-type: disc;
            margin: 2px 0 0 14px;
            padding: 0;
        }

        ul.blue li {
            margin-bottom: 2px;
        }

        /* Header & Footer */
        header {
            position: fixed;
            top: 0px;
            left: 0px;
            right: 0px;
            height: 60px;
            margin-top: -60px;
        }

        footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            height: 50px;
            margin-bottom: -50px;
        }


        .footer-logos td {
            width: 20%;
            text-align: center;
        }


        .page-number:after {
            content: counter(page) " / " counter(pages);
        }

        .no-break {
            page-break-inside: avoid;
        }

        /* ——— Imágenes de cabecera y pie ——— */
        .img-full {
            width: 100%;
            height: auto;
            /* ← deja que mantenga proporción */
            display: block;
        }

        .mt-tight {
            margin-top: -30px;
        }

        /* ajuste fino: -12 px */
    </style>
</head>

<body>
    {{-- ========== HEADER (htmlpageheader) ========== --}}
    <htmlpageheader name="all">
        <div style="position:relative; height:120px;">
            <table style="width:100%; position:relative; z-index:2;">
                <tr>
                    <td style="width:60%;">
                        <img src="{{ public_path('assets/images/logo.png') }}" style="height:40px;">
                    </td>
                    <td style="width:40%; text-align:right;">
                        <div style="display:inline-block;background:#e6edff;border:1px solid #9fbafc;
                      border-radius:4px;padding:6px 14px;font-size:11px;">
                            <strong>Cotización #{{ $cotizacion->num_consecutivo }}</strong>
                        </div>
                    </td>
                </tr>
            </table>

            {{-- barra corporativa al fondo, sin tapar --}}
            <img src="{{ public_path('assets/images/barra.png') }}" class="img-full"
                style="position:absolute; left:0; bottom:0; z-index:1;">

        </div>
    </htmlpageheader>
    <sethtmlpageheader name="all" value="on" />


    {{-- ======= CONTENIDO (compensar header) ======= --}}
    <div>
        {{-- EMITE / PARA --}}
        <table class="mt-tight" style="margin-bottom:6px;">

            <tr>
                <td style="width:50%;">
                    <h2>Emite</h2>
                    <strong>MC Comercializadora de Cómputo y Redes, S.A. de C.V.</strong><br>
                    Av. Primero de Mayo #15 Int. 1025, Naucalpan de Juárez,<br>
                    CP 53500, Estado de México.<br>
                    Tel: (55)-5003‑2830 &nbsp;|&nbsp; mcarreon@macasahs.com.mx
                </td>
                <td style="width:50%;">
                    <h2>Para</h2>
                    <strong>{{ $cotizacion->cliente->nombre ?? '' }}</strong><br>
                    {{ $cotizacion->contactoEntrega->direccion_entrega->calle ?? '' }}
                    #{{ $cotizacion->contactoEntrega->direccion_entrega->num_ext ?? '' }}@if(isset($cotizacion->contactoEntrega->direccion_entrega->num_int)),
                    Int.{{ $cotizacion->contactoEntrega->direccion_entrega->num_int }}@endif
                    {{ $cotizacion->contactoEntrega->direccion_entrega->colonia->d_asenta ?? '' }},
                    {{ $cotizacion->contactoEntrega->direccion_entrega->ciudad->n_mnpio ?? '' }}<br>
                    CP {{ $cotizacion->contactoEntrega->direccion_entrega->cp ?? '' }},
                    {{ $cotizacion->contactoEntrega->direccion_entrega->colonia->d_estado ?? '' }}<br>
                    Tel: {{ $cotizacion->contactoEntrega->telefono1 ?? '-' }} &nbsp;|&nbsp;
                    {{ $cotizacion->contactoEntrega->email ?? '-' }}
                </td>
            </tr>
        </table>

        {{-- META DATOS --}}
        <table class="meta-table" style="margin-bottom:12px;">
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
                <td class="label">Cond. Pago</td>
                <td>[Por definir]</td>
                <td class="label">Tiempo Entrega</td>
                <td>[Por definir]</td>
            </tr>
            <tr>
                <td class="label">Moneda</td>
                <td>MXN</td>
                <td></td>
                <td></td>
            </tr>
        </table>

        {{-- PROUESTA --}}

        @php
            $mostrarSku = $cotizacion->partidas->contains(function ($p) {
                return !empty($p->sku);
            });
        @endphp



        <h2>Propuesta Económica</h2>
        <table>
            <thead>
                <tr>
                    <th style="width:6%;">#</th>
                    @if($mostrarSku)
                    <th style="width:14%;">SKU</th>@endif
                    <th style="width:46%;">Descripción</th>
                    <th style="width:14%;" class="text-right">P. Unit.</th>
                    <th style="width:10%;" class="text-center">Cant.</th>
                    <th style="width:16%;" class="text-right">Importe</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $subtotal = 0;
                @endphp
                @foreach($cotizacion->partidas as $i => $p)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        @if($mostrarSku)
                        <td>{{ $p->sku ?? '-' }}</td>@endif
                        <td>{!! nl2br(e($p->descripcion)) !!}</td>
                        <td class="text-right">${{ number_format($p->precio, 2) }}</td>
                        <td class="text-center">{{ $p->cantidad }}</td>
                        <td class="text-right">${{ number_format($p->precio * $p->cantidad, 2) }}</td>
                    </tr>
                    @php
                        $subtotal += $p->precio * $p->cantidad;
                    @endphp
                @endforeach
            </tbody>
        </table>

        {{-- TOTALES + NOTAS (en fila) --}}
        <table class="no-break" style="width:100%; margin-top:12px;">
            <tr>
                <td style="width:70%; vertical-align:top;">
                    <p class="fw-bold" style="margin:0 0 4px;">Favor de realizar su pago a nombre de:</p>
                    MC Comercializadora de Cómputo y Redes, S.A. de C.V.<br>
                    Banco SCOTIABANK • Cuenta 00101474386 • CLABE 044180001014743869

                    <p class="fw-bold" style="margin:6px 0 2px;">Aspectos importantes de nuestro proceso:</p>
                    <ul class="blue">
                        <li>Precios y condiciones sujetos a cambio sin previo aviso.</li>
                        <li>No hay devoluciones una vez confirmado el pedido.</li>
                        <li>Pedidos &lt; $2,500 MN + IVA: envío $150 MN + IVA.</li>
                        <li>Confirme garantías con su ejecutivo antes de autorizar.</li>
                    </ul>
                </td>
                <td style="width:30%; vertical-align:top;">
                    <div class="totales-card">
                        <table>
                            <tr>
                                <td class="label">Subtotal:</td>
                                <td class="value">${{ number_format($subtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="label">IVA (16%):</td>
                                <td class="value">${{ number_format($subtotal * 0.16, 2) }}</td>
                            </tr>
                            <tr class="total">
                                <td class="label">TOTAL:</td>
                                <td class="value">${{ number_format($subtotal * 1.16, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>

    </div> {{-- /contenido --}}

    {{-- ========== FOOTER (htmlpagefooter) ========== --}}
    <htmlpagefooter name="last">
        <div style="position:relative; height:120px;"> {{-- ↑ nuevo alto total --}}

            <!-- franja azul pegada al borde inferior -->
            <img src="{{ public_path('assets/images/pie_pag.png') }}" class="img-full"
                style="position:absolute; left:0; bottom:0; z-index:1;">

            <!-- logos 32 px por encima de la franja -->
            <table class="footer-logos" style="width:100%; position:absolute;
                            bottom:2px;    
                    left:0; table-layout:fixed; z-index:2;">

                <tr>
                    @foreach (['dell', 'hp', 'lenovo', 'microsoft', 'kingston'] as $logo)
                        <td><img src="{{ public_path('assets/images/partners/' . $logo . '.png') }}"
                                style="max-height:38px;">
                        </td>
                    @endforeach
                </tr>
            </table>

            <!-- numeración -->
            <div style="position:absolute; right:22mm; bottom:8px;
                  font-size:8px; color:#6d6d6d; z-index:3;">
                <span class="page-number"></span>
            </div>
        </div>
    </htmlpagefooter>
    <sethtmlpagefooter name="last" value="on" show-this-page="1" />

</body>

</html>