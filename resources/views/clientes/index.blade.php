@extends('layouts.app')
@section('title', 'SIS 3.0 | Listado de Clientes')




@section('content')
    {{-- 🧭 Migas de pan --}}
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('inicio') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Mis Cuentas</li>
    @endsection

    <h1 class="mb-4">Mis Cuentas</h1>

    {{-- 🎛 Botonera --}}
    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="{{ url()->previous() }}" class="btn btn-light">
            <i class="fa fa-arrow-left me-1"></i> Regresar
        </a>

        <a href="{{ route('inicio') }}" class="btn btn-outline-primary">
            <i class="fa fa-phone me-1"></i> Mis Recall's
        </a>

        <a href="{{ route('inicio') }}" class="btn btn-primary">
            <i class="fa fa-check me-1"></i> Enviar carta de presentación
        </a>
    </div>

    {{-- 🔎 Buscador --}}
    <form method="GET" action="{{ route('clientes.index') }}">
        <div class="card mb-4">
            <div class="card-header">Filtros</div>
            <div class="card-body">
                <div class="row gy-3">

                    {{-- Búsqueda global --}}
                    <div class="col-md-6">
                        <label for="search" class="form-label">Búsqueda</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fa fa-search"></i></span>
                            <input
                            type="text"
                            name="search"
                            id="search"
                            class="form-control"
                            placeholder="Cliente, email, ID…"
                            value="{{ request('search') }}"
                            >
                        </div>
                    </div>

                    {{-- Ejecutivos (multi-select con Select2) --}}
<div class="col-md-6">
    <label for="ejecutivos" class="form-label">Seleccione ejecutivo(s)</label>
    <select
        id="ejecutivos"
        name="ejecutivos[]"
        class="select2"          {{-- ← SIN "form-select" --}}
        multiple
        data-placeholder="Seleccione uno o varios ejecutivos"
    >
        @foreach($vendedores as $v)
            <option
                value="{{ $v->id_usuario }}"
                {{ in_array($v->id_usuario, request('ejecutivos', [])) ? 'selected' : '' }}
            >
                {{ $v->nombre }} {{ $v->apellido_p }} {{ $v->apellido_m }}
            </option>
        @endforeach
    </select>
</div>


                    {{-- Sector --}}
                    <div class="col-md-3">
                        <label for="sector" class="form-label">Sector</label>
                        <select name="sector" id="sector" class="form-select">
                            <option value="">Todos</option>
                            @foreach($sectores as $s)
                            <option
                                value="{{ $s }}"
                                {{ request('sector') == $s ? 'selected' : '' }}
                            >{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Segmento --}}
                    <div class="col-md-3">
                        <label for="segmento" class="form-label">Segmento</label>
                        <select name="segmento" id="segmento" class="form-select">
                            <option value="">Todos</option>
                            @foreach($segmentos as $s)
                            <option
                                value="{{ $s }}"
                                {{ request('segmento') == $s ? 'selected' : '' }}
                            >{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Ciclo de venta --}}
                    <div class="col-md-3">
                        <label for="cycle" class="form-label">Ciclo de venta</label>
                        <select name="cycle" id="cycle" class="form-select">
                            <option value="">Todos</option>
                            @foreach($ciclos as $ciclo)
                            <option
                                value="{{ $ciclo }}"
                                {{ request('cycle') == $ciclo ? 'selected' : '' }}
                            >{{ $ciclo }}</option>
                            @endforeach
                        </select>
                    </div>
              
                    

                    {{-- Registros por página --}}
                    <div class="col-md-3">
                        <label for="perPage" class="form-label">Ver</label>
                        <select name="perPage" id="perPage" class="form-select">
                            @foreach([10,25,50,100] as $n)
                            <option
                                value="{{ $n }}"
                                {{ request('perPage',25) == $n ? 'selected':'' }}
                            >{{ $n }}</option>
                            @endforeach
                        </select>
                    </div>

                

                    {{-- Botón Limpiar --}}
                    <div class="col-md-1 offset-md-10 text-end">
                        <a href="{{ route('clientes.index') }}" class="btn btn-light">
                            <i class="fa fa-eraser"></i> Limpiar
                        </a>
                    </div>
                    {{-- Botón Buscar --}}
                    <div class="col-md-1 text-end">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-search"></i> Buscar
                        </button>
                    </div>

                </div>


                </div>
            </div>
        </div>
    </form>

        {{-- ───────── Paginación ───────── --}}
    @if($clientes->hasPages())
        <div class="row align-items-center mb-4">
            <div class="col-sm">
                <p class="mb-0 text-muted small">
                    Mostrando {{ $clientes->firstItem() }} a {{ $clientes->lastItem() }}
                    de {{ $clientes->total() }} clientes
                </p>
            </div>
            <div class="col-sm-auto">
                <nav aria-label="Paginación de clientes">
                    <ul class="pagination pagination-rounded pagination-sm mb-0">
                    
                    {{-- ← Anterior (icon only) --}}
                    @if($clientes->onFirstPage())
                        <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fa fa-chevron-left" aria-hidden="true"></i>
                            <span class="visually-hidden">Anterior</span>
                        </span>
                        </li>
                    @else
                        <li class="page-item">
                        <a class="page-link" href="{{ $clientes->previousPageUrl() }}" rel="prev"
                            aria-label="Anterior">
                            <i class="fa fa-chevron-left" aria-hidden="true"></i>
                            <span class="visually-hidden">Anterior</span>
                        </a>
                        </li>
                    @endif

                {{-- Páginas numéricas (current ±1) --}}
                    @php
                        $last = $clientes->lastPage();
                        $curr = $clientes->currentPage();

                        // rango “normal”: currentPage ±1
                        $start = max(1, $curr - 1);
                        $end   = min($last, $curr + 1);

                        // si estamos en la página 1, forzamos [1,2,3]
                        if ($curr === 1) {
                            $end = min($last, 3);
                        }
                        // si estamos en la última, forzamos [last-2, last-1, last]
                        if ($curr === $last) {
                            $start = max(1, $last - 2);
                        }
                    @endphp

                    @foreach ($clientes->getUrlRange($start, $end) as $page => $url)
                        <li class="page-item {{ $page == $curr ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    {{-- Siguiente → (icon only) --}}
                    @if($clientes->hasMorePages())
                        <li class="page-item">
                        <a class="page-link" href="{{ $clientes->nextPageUrl() }}" rel="next"
                            aria-label="Siguiente">
                            <i class="fa fa-chevron-right" aria-hidden="true"></i>
                            <span class="visually-hidden">Siguiente</span>
                        </a>
                        </li>
                    @else
                        <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fa fa-chevron-right" aria-hidden="true"></i>
                            <span class="visually-hidden">Siguiente</span>
                        </span>
                        </li>
                    @endif

                    </ul>
                </nav>
            </div>
        </div>
    @endif
    {{-- ────────── Fin paginación ────────── --}}

    {{-- 📋 Tabla responsiva --}}
    <div class="table-responsive mb-4 shadow-lg">
        <table id="tabla-clientes" class="table align-middle table-hover table-nowrap
                                         table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th class="w-10">ID Cliente</th>
                    <th>Empresa</th>
                    <th>Contacto</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Sector</th>
                    <th>Segmento</th>
                    <th>Ciclo</th>
                    <th>Asignado a</th>
                    <th class="text-center">…</th>
                </tr>
            </thead>
            <tbody>
                {{-- Filas de clientes --}}
                {{-- Iterar clientes --}}
                @foreach ($clientes as $c)
                    @php
                        $pc = $c->primerContacto;
                        $tel = optional($pc)->telefono1;
                        $email = optional($pc)->email;
                    @endphp
                    <tr>
                        {{-- ID con enlace al view --}}
                        <td>
                            <a href="{{ route('clientes.view', $c->id_cliente) }}"
                                class="fw-semibold text-decoration-underline">
                                {{ $c->id_cliente }}
                            </a>
                        </td>

                        <td>
                            <a href="{{ route('clientes.view', $c->id_cliente) }}" style="color: inherit; text-decoration: underline;">
                                {{ $c->nombre }}
                            </a>
                        </td>

                        {{-- Contacto principal o guion --}}
                        <td>
                            @if(optional($pc)->nombre_completo)
                                <a href="{{ route('clientes.view', $c->id_cliente) }}" style="color: inherit; text-decoration: underline;">
                                    {{ $pc->nombre_completo }}
                                </a>
                            @else
                                —
                            @endif
                        </td>

                        {{-- Teléfono clic-to-call --}}
                        <td>
                            @if($tel)
                                <a href="tel:{{ $tel }}">
                                    {{ $tel }}
                                </a>
                            @else
                                —
                            @endif
                        </td>

                        {{-- Email mailto --}}
                        <td>
                            @if ($email)
                                <a href="mailto:{{ $email }}">{{ $email }}</a>
                            @else
                                —
                            @endif
                        </td>

                        <td>{{ $c->sector ?? '—' }}</td>
                        <td>{{ $c->segmento ?? '—' }}</td>

                        {{-- Ciclo como badge --}}
                        <td>
                            <span
                                class="badge"
                                style="background-color:{{ $c->ciclo_venta === 'venta' ? '#198754' : '#FFBF00' }};
                                    color:{{ $c->ciclo_venta === 'venta' ? '#fff' : '#212529' }};">
                                {{ $c->ciclo_venta }}
                            </span>
                        </td>


                        <td>
                            {{ $c->vendedor->nombre ?? '—' }}{{ isset($c->vendedor->apellido_p) ? '' . $c->vendedor->apellido_p : '' }} {{ $c->vendedor->apellido_m ?? '' }}
                        </td>

                        {{-- Acciones compactas en dropdown --}}
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-icon btn-light btn-sm" data-bs-toggle="dropdown">
                                    <i class="fa fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('clientes.edit', $c->id_cliente) }}">
                                            <i class="fa fa-edit me-2 text-warning"></i> Editar
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('clientes.destroy', $c->id_cliente) }}" method="POST"
                                            onsubmit="return confirm('¿Eliminar cliente?')">
                                            @csrf @method('DELETE')
                                            <button class="dropdown-item text-danger">
                                                <i class="fa fa-trash me-2"></i> Borrar
                                            </button>
                                        </form>
                          td        </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div> {{-- /.table-responsive --}}

    {{-- ───────── Paginación ───────── --}}
    @if($clientes->hasPages())
    <div class="row align-items-center mb-4">
        <div class="col-sm">
        <p class="mb-0 text-muted small">
            Mostrando {{ $clientes->firstItem() }} a {{ $clientes->lastItem() }}
            de {{ $clientes->total() }} clientes
        </p>
        </div>
        <div class="col-sm-auto">
        <nav aria-label="Paginación de clientes">
            <ul class="pagination pagination-rounded pagination-sm mb-0">
            
            {{-- ← Anterior (icon only) --}}
            @if($clientes->onFirstPage())
                <li class="page-item disabled">
                <span class="page-link">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                    <span class="visually-hidden">Anterior</span>
                </span>
                </li>
            @else
                <li class="page-item">
                <a class="page-link" href="{{ $clientes->previousPageUrl() }}" rel="prev"
                    aria-label="Anterior">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                    <span class="visually-hidden">Anterior</span>
                </a>
                </li>
            @endif

            {{-- Páginas numéricas (current ±1) --}}
            @php
                $last = $clientes->lastPage();
                $curr = $clientes->currentPage();

                // rango “normal”: currentPage ±1
                $start = max(1, $curr - 1);
                $end   = min($last, $curr + 1);

                // si estamos en la página 1, forzamos [1,2,3]
                if ($curr === 1) {
                    $end = min($last, 3);
                }
                // si estamos en la última, forzamos [last-2, last-1, last]
                if ($curr === $last) {
                    $start = max(1, $last - 2);
                }
            @endphp

            @foreach ($clientes->getUrlRange($start, $end) as $page => $url)
                <li class="page-item {{ $page == $curr ? 'active' : '' }}">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach


            {{-- Siguiente → (icon only) --}}
            @if($clientes->hasMorePages())
                <li class="page-item">
                <a class="page-link" href="{{ $clientes->nextPageUrl() }}" rel="next"
                    aria-label="Siguiente">
                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                    <span class="visually-hidden">Siguiente</span>
                </a>
                </li>
            @else
                <li class="page-item disabled">
                <span class="page-link">
                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                    <span class="visually-hidden">Siguiente</span>
                </span>
                </li>
            @endif

            </ul>
        </nav>
        </div>
    </div>
    @endif
    {{-- ────────── Fin paginación ────────── --}}



@endsection