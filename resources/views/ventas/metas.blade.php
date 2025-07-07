@extends('layouts.app')
@section('title', 'SIS 3.0 | Equipos de Trabajo')

@section('content')

    <div class="container-fluid">
        {{-- 🧭 Migas de pan --}}
        @section('breadcrumb')
            <li class="breadcrumb-item"><a href="{{ route('inicio') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Metas de Venta</li>
        @endsection

        <h2 class="mb-3 text-titulo">Metas de Venta</h2>

        {{-- 🎛 Botonera --}}
        <div class="row-fluid gap-2 mb-3">
            <a href="{{ url()->previous() }}" class="col-md-2 btn btn-secondary btn-principal">
                <i class="fa fa-arrow-left me-1"></i> Regresar
            </a>

            {{-- Botón Modal Crear Equipo de Trabajo --}}
            <button type="button" id="btnCrearEquipo" class="col-md-2 btn btn-primary btn-principal">
                <i class="fa fa-users-cog me-1"></i> Agregar Usuario
            </button>
        </div>

        {{-- 🎯 Filtros --}}
        <div class="card mb-4">
            <div class="card-header">Filtros</div>
            <div class="card-body">
                <form method="GET" action="{{ route('ventas.metas') }}" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="busqueda" class="form-label">Búsqueda</label>
                        <input type="text" name="busqueda" id="busqueda" value="{{ request('busqueda') }}"
                            class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label for="month" class="form-label">Mes</label>
                        <select name="month" id="month" class="form-select">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->monthName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="year" class="form-label">Año</label>
                        <select name="year" id="year" class="form-select">
                            @for($a = now()->year; $a >= 2020; $a--)
                                <option value="{{ $a }}" {{ $year == $a ? 'selected' : '' }}>{{ $a }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="por_pagina" class="form-label">Ver registros</label>
                        <select name="por_pagina" id="por_pagina" class="form-select">
                            @foreach([10, 25, 50, 100] as $opcion)
                                <option value="{{ $opcion }}" {{ $porPagina == $opcion ? 'selected' : '' }}>{{ $opcion }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="ordenar_por" class="form-label">Ordenar por</label>
                        <select name="ordenar_por" id="ordenar_por" class="form-select">
                            <option value="username" {{ $ordenarPor == 'username' ? 'selected' : '' }}>Nombre</option>
                            <option value="id_usuario" {{ $ordenarPor == 'id_usuario' ? 'selected' : '' }}>ID</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-success w-100">Buscar</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- 📋 Resultados --}}
        <div class="card">
            <div class="card-header">Resultados</div>
            <div class="card-body table-responsive">
                <p class="text-muted">
                    Viendo
                    {{ strtoupper(\Carbon\Carbon::createFromDate($year, $month, 1)->locale('es')->isoFormat('MMMM [del] YYYY')) }}
                </p>

                <table class="table table-striped table-sm align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NOMBRE</th>
                            <th>CUOTA DE FACTURACIÓN</th>
                            <th>CUOTA MARGINAL</th>
                            <th class="text-center">DIAS PARA META DE VENTA</th>
                            <th class="text-center">COTIZACIONES DIARIAS</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($usuarios as $usuario)
                            @php
                                $meta = $usuario->metasVentas->first();
                            @endphp
                            <tr>
                                <td>{{ $usuario->id_usuario }}</td>
                                <td>{{ $usuario->username }}</td>
                                <td>
                                    <input type="text" name="cuota_facturacion[{{ $usuario->id_usuario }}]"
                                        class="form-control form-control-sm formato-cantidad"
                                        value="{{ number_format($meta->cuota_facturacion ?? 0, 2) }}">
                                </td>
                                <td>
                                    <input type="text" name="cuota_marginal[{{ $usuario->id_usuario }}]"
                                        class="form-control form-control-sm formato-cantidad"
                                        value="{{ number_format($meta->cuota_marginal_facturacion ?? 0, 2) }}">
                                </td>
                                <td class="text-center">
                                    <input type="number" name="cuota_llamadas[{{ $usuario->id_usuario }}]"
                                        class="form-control form-control-sm short-number" min="0"
                                        value="{{ $meta->cuota_llamadas ?? '' }}">
                                </td>
                                <td class="text-center">
                                    <input type="number" name="cuota_cotizaciones[{{ $usuario->id_usuario }}]"
                                        class="form-control form-control-sm short-number" min="0"
                                        value="{{ $meta->cuota_cotizaciones ?? '' }}">
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-success">Guardar</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No se encontraron resultados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Paginación --}}
                {{-- <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Página {{ $usuarios->currentPage() }} / {{ $usuarios->lastPage() }}<br>
                        {{ $usuarios->total() }} Registros encontrados
                    </div>
                    <div>
                        {{ $usuarios->appends(request()->query())->links() }}
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.formato-cantidad').forEach(input => {
            input.addEventListener('focus', () => {
                if (input.value === '0.00') input.value = '';
            });

            input.addEventListener('input', () => {
                let value = input.value.replace(/[^\d.]/g, '').replace(/^0+(?!\.)/, '');
                if (value.includes('.')) {
                    let parts = value.split('.');
                    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                    value = parts[0] + '.' + parts[1].substring(0, 2);
                } else {
                    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                }
                input.value = value;
            });

            input.addEventListener('blur', () => {
                let num = parseFloat(input.value.replace(/,/g, ''));
                if (!isNaN(num)) {
                    input.value = num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                } else {
                    input.value = '0.00';
                }
            });
        });
    </script>
@endpush