@extends('layouts.app') {{-- o el layout base que uses --}}

@section('title', 'SIS 3.0 | Monitor de Cotizaciones')

@section('content')
    <div class="container-fluid">
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




        <div class="tabla-colapsable">
            <div class="tabla-header">
                <div class="header-tabla">Equipos de Trabajo</div>
                <div class=""></div>
                <div class="header-tabla">
                    VIENDO
                    {{ strtoupper(\Carbon\Carbon::createFromDate($year, $month, 1)->locale('es')->isoFormat('MMMM [del] YYYY')) }}
                </div>

                <div class=""></div>
                <div class=""></div>
                <div class=""></div>
                <div class=""></div>
            </div>

            @foreach ($equipos as $equipo)
                <div class="fila-lider" onclick="toggleCollapse({{ $equipo->id }})">
                    <div><strong>{{ $equipo->nombre}}</strong>
                    </div>
                    {{-- <div>{{ number_format($equipo->cuota_cotizacion, 2) }}</div>
                    <div>{{ number_format($equipo->alcance_cotizacion, 2) }}</div>
                    <div>{{ number_format($equipo->porcentaje_cotizacion, 2) }}%</div>
                    <div>{{ number_format($equipo->cuota_margen, 2) }}</div>
                    <div>{{ number_format($equipo->alcance_margen, 2) }}</div>
                    <div>{{ number_format($equipo->porcentaje_margen, 2) }}%</div> --}}
                </div>

                <div id="collapse-{{ $equipo->id }}" class="collapse-content">
                    <div style="padding: 25px;">
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
                            <div class="fila-miembro text-muted text-center">Este equipo no tiene miembros.</div>
                        @else
                            @foreach ($equipo->usuarios as $usuario)
                                <div class="fila-miembro">
                                    <div>
                                        👤 <strong>{{ $usuario->nombre }} {{ $usuario->apellido_p }}</strong>
                                        <small class="text-muted">({{ $usuario->pivot->rol }})</small>
                                    </div>
                                    <div class="text-center">{{ number_format($usuario->metas['cuota_cotizacion'] ?? 0, 2) }}</div>
                                    <div class="text-center">{{ number_format($usuario->metas['alcance_cotizacion'] ?? 0, 2) }}</div>
                                    <div class="text-center">{{ number_format($usuario->metas['porcentaje_cotizacion'] ?? 0, 2) }}%
                                    </div>
                                    <div class="text-center">${{ number_format($usuario->metas['cuota_margen'] ?? 0, 2) }}</div>
                                    <div class="text-center">${{ number_format($usuario->metas['alcance_margen'] ?? 0, 2) }}</div>
                                    <div class="text-center">{{ number_format($usuario->metas['porcentaje_margen'] ?? 0, 2) }}%</div>
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
            @endforeach
        </div>

        {{-- Aquí se listarán las cotizaciones --}}
        {{-- <div class="card shadow">
            <div class="card-body">
                <p class="text-muted">Aquí aparecerán las cotizaciones registradas. Usa el botón "Nueva cotización" para
                    comenzar una.</p>
                <a href="#" class="btn btn-primary">+ Nueva cotización</a>
            </div>
        </div> --}}
    </div>

    <script>
        function toggleCollapse(id) {
            const element = document.getElementById('collapse-' + id);
            element.classList.toggle('open');
        }
    </script>

@endsection