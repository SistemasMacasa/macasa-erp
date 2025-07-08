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
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

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
                        @foreach ($usuarios as $usuario)
                            <tr>
                                <form action="{{ route('ventas.metas.guardar') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id_usuario" value="{{ $usuario->id_usuario }}">
                                    <input type="hidden" name="month" value="{{ $month }}">
                                    <input type="hidden" name="year" value="{{ $year }}">

                                    <td>{{ $usuario->id_usuario }}</td>
                                    <td>{{ $usuario->nombre }} {{ $usuario->apellido_p }} {{ $usuario->apellido_m }}</td>

                                    <td>
                                        <input type="text" name="cuota_facturacion"
                                            class="form-control form-control-sm formato-cantidad"
                                            value="${{ number_format($usuario->metaVenta?->cuota_facturacion ?? 0, 2) }}">
                                    </td>
                                    <td>
                                        <input type="text" name="cuota_marginal_facturacion"
                                            class="form-control form-control-sm formato-cantidad"
                                            value="{{ number_format($usuario->metaVenta?->cuota_marginal_facturacion ?? 0, 2) }}">
                                    </td>
                                    <td class="text-center align-middle">
                                        <input type="number" name="dias_meta"
                                            class="form-control form-control-sm text-center short-number mx-auto"
                                            style="width: 80px;" min="0" value="{{ $usuario->metaVenta?->dias_meta ?? '' }}">
                                    </td>
                                    <td class="text-center align-middle">
                                        <input type="number" name="cotizaciones_diarias"
                                            class="form-control form-control-sm text-center short-number mx-auto" min="0"
                                            value="{{ $usuario->metaVenta?->cotizaciones_diarias ?? '' }}">
                                    </td>
                                    <!-- Campos ocultos: mes y año -->
                                    <input type="hidden" name="mes" value="{{ $month }}">
                                    <input type="hidden" name="anio" value="{{ $year }}">

                                    <td>
                                        <button type="submit" class="btn btn-sm btn-success">Guardar</button>
                                    </td>
                                </form>
                            </tr>
                        @endforeach
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
            // Al enfocar
            input.addEventListener('focus', () => {
                if (input.value === '$0.00' || input.value === '0.00') input.value = '';
                input.value = input.value.replace('$', '').replace(/,/g, '');
            });

            // Al escribir
            input.addEventListener('input', () => {
                let value = input.value.replace(/[^\d.]/g, '').replace(/^0+(?!\.)/, '');
                if (value.includes('.')) {
                    let parts = value.split('.');
                    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                    value = parts[0] + '.' + parts[1].substring(0, 2);
                } else {
                    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                }
                if (value) {
                    input.value = '$' + value;
                } else {
                    input.value = '';
                }
            });

            // Al salir
            input.addEventListener('blur', () => {
                let num = parseFloat(input.value.replace(/[$,]/g, ''));
                if (!isNaN(num)) {
                    input.value = '$' + num.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                } else {
                    input.value = '$0.00';
                }
            });

            // Formato inicial
            if (input.value && !input.value.includes('$')) {
                let num = parseFloat(input.value.replace(/,/g, ''));
                if (!isNaN(num)) {
                    input.value = '$' + num.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }
            }
        });

        // 🧹 Limpieza antes de enviar el formulario
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', () => {
                form.querySelectorAll('.formato-cantidad').forEach(input => {
                    input.value = input.value.replace(/[$,]/g, '');
                });
            });
        });
    </script>
@endpush