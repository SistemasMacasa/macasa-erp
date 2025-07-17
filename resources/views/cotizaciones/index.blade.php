@extends('layouts.app') {{-- o el layout base que uses --}}

@section('title', 'SIS 3.0 | Monitor de Cotizaciones')

@section('content')
    <div class="container-fluid">
        {{-- 🧭 Migas de pan --}}
        @section('breadcrumb')
            <li class="breadcrumb-item"><a href="{{ route('inicio') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Cotizaciones</li>
        @endsection
        <h1 class="mb-4">Cotizaciones</h1>

        {{-- 🎛 Botonera --}}
        <div class="row-fluid gap-2 mb-3">
            <a href="{{ url()->previous() }}" class="col-md-2 btn btn-secondary btn-principal">
                <i class="fa fa-arrow-left me-1"></i> Regresar
            </a>
        </div>

        {{-- 🎛 Buscador de cotizaciones --}}
        <form method="GET" action="{{ route('cotizaciones.index') }}">
            <div class="card mb-3">
                <div class="card-header text-center">
                    <h5 class="mb-0 text-subtitulo">Filtros</h5>
                </div>

                <div class="card-body">
                    <div class="row g-2 mb-4">
                        {{-- Año --}}
                        <div class="col-md-2">
                            <label for="year" class="form-label">Año</label>
                            <select name="year" id="year" class="form-select">
                                @php
                                    $currentYear = now()->year;
                                    $selectedYear = request('year', $currentYear);
                                @endphp
                                @for ($y = $currentYear; $y >= $currentYear - 5; $y--)
                                    <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        {{-- Mes --}}
                        <div class="col-md-2">
                            <label for="month" class="form-label">Mes</label>
                            <select name="month" id="month" class="form-select">
                                @php
                                    $selectedMonth = request('month', now()->month);
                                @endphp
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Botón --}}
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-filter me-1"></i> Filtrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="accordion" id="equiposAccordion">
            <div class="tabla-header-top">
                <div class="header-title">
                    <i class="fa fa-users me-1"></i> Equipos de Trabajo
                </div>
                <div class="header-period">
                    VIENDO
                    {{ strtoupper(\Carbon\Carbon::createFromDate($year, $month, 1)->locale('es')->isoFormat('MMMM [del] YYYY')) }}
                </div>
            </div>

            @foreach ($equipos as $equipo)
                <div class="accordion-item mb-2">
                    <h2 class="accordion-header" id="headingEquipo{{ $equipo->id_equipo }}">
                        <button class="accordion-button collapsed py-2 px-3" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseEquipo{{ $equipo->id_equipo }}" aria-expanded="false"
                            aria-controls="collapseEquipo{{ $equipo->id_equipo }}" style="font-size: 0.85rem;">

                            <div class="d-flex align-items-center w-100">
                                {{-- Nombre del equipo a la izquierda --}}
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa fa-users text-primary"></i>
                                    <span class="fw-semibold">{{ $equipo->nombre }}</span>
                                </div>

                                {{-- Contenedor centrado de segmento y sucursal --}}
                                <div class="ms-auto d-flex gap-4 text-muted small" style="max-width: 600px;">
                                    <span class="d-flex align-items-center">
                                        <i class="fa fa-sitemap me-1 text-secondary"></i>
                                        Segmento: <strong class="ms-1">{{ $equipo->sucursal->segmento->nombre ?? '—' }}</strong>
                                    </span>
                                    <span class="d-flex align-items-center">
                                        <i class="fa fa-building me-1 text-secondary"></i>
                                        Sucursal: <strong class="ms-1">{{ $equipo->sucursal->nombre ?? '—' }}</strong>
                                    </span>
                                </div>
                            </div>

                        </button>
                    </h2>






                    <div id="collapseEquipo{{ $equipo->id_equipo }}"
                        class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                        aria-labelledby="headingEquipo{{ $equipo->id_equipo }}" data-bs-parent="#equiposAccordion">

                        <div class="accordion-body">

                            {{-- Tabla de usuarios del equipo --}}
                            <div style="padding: 10px;">
                                <div class="tabla-header">
                                    <div class="header-tabla"></div>
                                    <div class="cotiaziones-header">Cuota Cotización</div>
                                    <div class="cotiaziones-header">Alcance Cotización</div>
                                    <div class="cotiaziones-header">% Cotización</div>
                                    <div class="margen-header">Cuota Margen</div>
                                    <div class="margen-header">Alcance Margen</div>
                                    <div class="margen-header">% Margen</div>
                                </div>

                                @if ($equipo->usuarios->isEmpty())
                                    <div class="fila-miembro text-muted text-center">
                                        Este equipo no tiene miembros.
                                    </div>
                                @else
                                    @foreach ($equipo->usuarios as $usuario)
                                        <div class="fila-miembro" onclick="mostrarDetalleUsuario({{ $usuario->id_usuario }})">
                                            <div>
                                                👤 <strong>{{ $usuario->nombre }} {{ $usuario->apellido_p }}</strong>
                                                <small class="text-muted">({{ $usuario->pivot->rol }})</small>
                                            </div>
                                            <div class="text-center">{{ number_format($usuario->metas['cuota_cotizacion'] ?? 0, 2) }}
                                            </div>
                                            <div class="text-center">{{ number_format($usuario->metas['alcance_cotizacion'] ?? 0, 2) }}
                                            </div>
                                            <div class="text-center">
                                                {{ number_format($usuario->metas['porcentaje_cotizacion'] ?? 0, 2) }}%
                                            </div>
                                            <div class="text-center">${{ number_format($usuario->metas['cuota_margen'] ?? 0, 2) }}</div>
                                            <div class="text-center">${{ number_format($usuario->metas['alcance_margen'] ?? 0, 2) }}
                                            </div>
                                            <div class="text-center">{{ number_format($usuario->metas['porcentaje_margen'] ?? 0, 2) }}%
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                <div class="fila-alcance">
                                    <div class="text-center">📊 Alcance del Equipo</div>
                                    <div class="text-center">{{ number_format($equipo->cuota_cotizacion, 2) }}</div>
                                    <div class="text-center">{{ number_format($equipo->alcance_cotizacion, 2) }}</div>
                                    <div class="text-center">{{ number_format($equipo->porcentaje_cotizacion, 2) }}%</div>
                                    <div class="text-center">${{ number_format($equipo->cuota_margen, 2) }}</div>
                                    <div class="text-center">${{ number_format($equipo->alcance_margen, 2) }}</div>
                                    <div class="text-center">{{ number_format($equipo->porcentaje_margen, 2) }}%</div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Panel para mostrar detalles al fondo --}}
        <div id="panel-detalle-usuario"
            style="display:none; padding:20px; margin-top:20px; border:1px solid #ccc; border-radius:6px; background:#f8f9fa;">
        </div>

        {{-- Contenedor oculto con todos los detalles --}}
        <div id="contenedores-detalle" style="display: none;">
            @foreach ($equipos as $equipo)
                @foreach ($equipo->usuarios as $usuario)
                    <div id="contenido-detalle-{{ $usuario->id_usuario }}">
                        @if(isset($detallesPorUsuario[$usuario->id_usuario]))
                            @foreach ($detallesPorUsuario[$usuario->id_usuario] as $fecha => $cotizaciones)
                                <div class="mb-3 border rounded">
                                    {{-- Encabezado del día --}}
                                    <div onclick="toggleDiaDetalle(this)" style="cursor:pointer; padding:10px; background:#e9ecef;">
                                        <strong>
                                            📅 {{ \Carbon\Carbon::parse($fecha)->translatedFormat('l, d \\d\\e F \\d\\e Y') }}
                                        </strong>
                                        - {{ $cotizaciones->count() }} cotizaciones capturadas.
                                        <span style="float:right;">⯈</span>
                                    </div>

                                    {{-- Contenido colapsable --}}
                                    <div class="contenido-dia-detalle"
                                        style="max-height:0; overflow:hidden; transition:max-height 0.4s ease;">
                                        <div class="table-responsive p-2">
                                            <table class="table table-sm table-bordered mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th>EMPRESA</th>
                                                        <th>DETALLE</th>
                                                        <th>TOTAL IMPORTE</th>
                                                        <th>SCORE TOTAL</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $totalImporte = 0;
                                                        $totalScore = 0;
                                                    @endphp
                                                    @foreach ($cotizaciones as $cot)
                                                        @php
                                                            $totalImporte += $cot->monto_total;
                                                            $totalScore += $cot->score_final ?? 0;
                                                        @endphp
                                                        <tr>
                                                            <td><button class="btn btn-success btn-sm">Emitir pedido</button></td>
                                                            <td><button class="btn btn-secondary btn-sm">Editar</button></td>
                                                            <td><button class="btn btn-primary btn-sm">Descargar</button></td>
                                                            <td>{{ $cot->cliente->nombre }}</td>
                                                            <td>{{ $cot->partidas->first()->descripcion ?? 'Sin detalle' }}</td>
                                                            <td>${{ number_format($cot->monto_total, 2) }}</td>
                                                            <td>${{ number_format($cot->score_final ?? 0, 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                    <tr class="table-light fw-bold">
                                                        <td colspan="5">TOTALES POR DÍA [{{ count($cotizaciones) }}]</td>
                                                        <td>${{ number_format($totalImporte, 2) }}</td>
                                                        <td>${{ number_format($totalScore, 2) }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-muted small">
                                No hay cotizaciones para este usuario en el mes.
                            </div>
                        @endif
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>

    <script>
        function toggleCollapse(id_equipo) {
            const element = document.getElementById('collapse-' + id_equipo);
            if (!element) {
                console.error('Elemento no encontrado: collapse-' + id_equipo);
                return;
            }
            element.classList.toggle('open');
        }
    </script>

    <script>
        let ultimoAbierto = null;

        function mostrarDetalleUsuario(idUsuario) {
            const panel = document.getElementById('panel-detalle-usuario');
            const contenido = document.getElementById('contenido-detalle-' + idUsuario);

            if (!contenido) return;

            const mismoUsuario = ultimoAbierto === idUsuario;

            if (mismoUsuario && panel.classList.contains('visible')) {
                // Animar salida
                panel.classList.remove('visible');

                // Esperar transición y luego limpiar
                setTimeout(() => {
                    // Cerrar todos los días abiertos
                    const diasAbiertos = panel.querySelectorAll('.contenido-dia-detalle');
                    diasAbiertos.forEach(dia => dia.style.maxHeight = '0');

                    // Cambiar iconos ⯆ por ⯈
                    const iconos = panel.querySelectorAll('div[onclick^="toggleDiaDetalle"] span');
                    iconos.forEach(icon => icon.textContent = '⯈');

                    panel.style.display = 'none';
                    panel.innerHTML = '';
                    ultimoAbierto = null;
                }, 300);

                return;
            }

            // Si otro usuario estaba abierto, cerrar primero
            if (panel.classList.contains('visible')) {
                panel.classList.remove('visible');
                setTimeout(() => {
                    abrirPanel(panel, contenido, idUsuario);
                }, 300);
            } else {
                abrirPanel(panel, contenido, idUsuario);
            }
        }

        function abrirPanel(panel, contenido, idUsuario) {
            panel.innerHTML = contenido.innerHTML;
            panel.style.display = 'block';
            void panel.offsetWidth; // reflow
            panel.classList.add('visible');
            ultimoAbierto = idUsuario;
            panel.scrollIntoView({ behavior: 'smooth' });
        }

    </script>

    <script>
        function toggleDiaDetalle(header) {
            const contenido = header.nextElementSibling;
            const icon = header.querySelector('span');

            if (contenido.style.maxHeight && contenido.style.maxHeight !== "0px") {
                contenido.style.maxHeight = "0";
                icon.textContent = "⯈";
            } else {
                contenido.style.maxHeight = contenido.scrollHeight + "px";
                icon.textContent = "⯆";
            }
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filas = document.querySelectorAll('.fila-miembro');
            let ultimoSeleccionado = null;

            filas.forEach(fila => {
                fila.addEventListener('click', function () {
                    const idUsuario = this.getAttribute('onclick').match(/\d+/)[0];

                    // Si estás clicando el mismo que ya estaba seleccionado
                    if (ultimoSeleccionado === idUsuario) {
                        // Quitar selección
                        this.classList.remove('seleccionado');
                        ultimoSeleccionado = null;
                    } else {
                        // Quitar selección de todas las filas
                        filas.forEach(f => f.classList.remove('seleccionado'));
                        // Agregar selección a esta
                        this.classList.add('seleccionado');
                        ultimoSeleccionado = idUsuario;
                    }
                });
            });
        });

    </script>

@endsection