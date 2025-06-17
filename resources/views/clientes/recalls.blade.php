@extends('layouts.app')
@section('title', 'SIS 3.0 | Mis Recall\'s')

@section('content')
<div class="container-fluid">

    {{-- 🧭 Migas de pan --}}
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('inicio') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Mis Recall's</li>
    @endsection

    <h2 class="mb-3" style="color: inherit;">Mis Recall's</h2>

    {{-- 🎛 Botonera --}}
    <div class="d-flex flex-wrap gap-2 mb-3">
        <a href="{{ url()->previous() }}" class="btn btn-secondary btn-principal">
            <i class="fa fa-arrow-left me-1"></i> Regresar
        </a>

        <a href="{{ route('clientes.index') }}" class="btn btn-primary btn-principal">
            <i class="fa fa-users me-1"></i> Mis cuentas
        </a>
    </div>

    {{-- 🔎 Filtros --}}
    <div class="card mb-3 shadow">
        <div class="card-header text-center">
            <h5 class="mb-0 text-subtitulo">Filtros</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('clientes.recalls') }}">
                <div class="row gx-3 gy-2 justify-content-between">
                    <div class="col">
                        <label for="search" class="form-label"><i class="fa fa-search text-normal"></i> Búsqueda</label>
                        <input  type="text" 
                                name="busqueda" 
                                id="busqueda" 
                                class="form-control" 
                                placeholder="Nombre, empresa, teléfono..."
                                value="{{ request('busqueda') }}">
                    </div>

                    <div class="col">
                        <label for="estado" class="form-label text-normal">Estado de recall</label>
                        <select name="estado" id="estado" class="form-select">
                            <option value="" {{ request('estado')== '' ? 'selected' : '' }}>-- Selecciona --</option>
                            <option value="pendientes" {{ request('estado') == 'pendientes' ? 'selected' : '' }}>Pendientes</option>
                            <option value="contestados" {{ request('estado') == 'contestados' ? 'selected' : '' }}>Contestados</option></select>
                    </div>

                    <div class="col">
                        <label class="form-label text-normal">Jefe de equipo</label>
                        <select class="form-select" disabled>
                            <option value="">-- Selecciona --</option>
                        </select>
                    </div>

                    <div class="col">
                        <label for="id_vendedor" class="form-label text-normal">Ejecutivo</label>
                        <select name="id_vendedor" id="id_vendedor" class="form-select">
                            <option value="" {{ request('id_vendedor') == '' ? 'selected' : '' }}>-- Selecciona --</option>
                            <option value="">Todos</option>
                            @foreach($ejecutivos as $id => $nombre)
                                <option value="{{ $id }}" {{ request('id_vendedor') == $id ? 'selected' : '' }}>
                                    {{ $nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col">
                        <label for="orden" class="form-label text-normal">Ordenar por</label>
                        <select name="orden" id="orden" class="form-select">
                            <option value="">-- Selecciona --</option>
                            <option value="nombre" {{ request('orden') === 'nombre' ? 'selected' : '' }}>Empresa</option>
                            <option value="fecha"  {{ request('orden') === 'fecha' ? 'selected' : '' }}>Fecha</option>
                        </select>
                    </div>
                </div>

                <div class="row gx-3 gy-2 justify-content-between mt-3">
                    <div class="col">
                        <label for="ver" class="form-label text-normal">Ver registros</label>
                        <select name="ver" id="ver" class="form-select">
                            <option value=""     {{ request('ver') === '' ? 'selected' : '' }}>-- Selecciona --</option>
                            <option value="5000" {{ request('ver') === '5000' ? 'selected' : '' }}>Todos</option>
                            <option value="25"   {{ request('ver') === '25' ? 'selected' : '' }}>25</option>
                            <option value="50"   {{ request('ver') === '50' ? 'selected' : '' }}>50</option>
                            <option value="100"  {{ request('ver') === '100' ? 'selected' : '' }}>100</option>
                            <option value="500"  {{ request('ver') === '500' ? 'selected' : '' }}>500</option>
                        </select>
                    </div>
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>

                </div>

                <div class="row gx-3 gy-2 justify-content-between mt-1">
                    <div class="col" style="min-width: 172px;"></div>
                    <div class="col" style="min-width: 172px;"></div>
                    <div class="col" style="min-width: 172px;"></div>
                    <div class="col" style="min-width: 172px;"></div>

                    <div class="col d-flex gap-2 align-items-end">
                        <a
                        href="{{ route('clientes.recalls') }}"
                        class="btn btn-secondary d-flex align-items-center justify-content-center flex-fill"
                        style="width: 50%;">
                            <i class="fa fa-eraser me-1"></i>
                            Limpiar
                        </a>

                        <button
                        type="submit"
                        class="btn btn-success d-flex align-items-center justify-content-center flex-fill"
                        style="width: 50%;">
                            <i class="fa fa-search me-1"></i>
                            Buscar
                        </button>
                    </div>
                </div>

            
            </form>
        </div>
    </div>

    {{-- ───────── Paginación ───────── --}}
    <div class="row align-items-center mb-3">

        {{-- Texto de totales --}}
        @if ($clientes != [])
            <div class="col">
                <p class="mb-0 text-muted small">
                    Mostrando <strong>{{ $clientes->firstItem() ?? "Todos" }}</strong> a <strong>{{ $clientes->lastItem() }}</strong>
                    | Total: <strong>{{ $clientes->total() ?? "Todos" }}</strong> recalls encontrados
                </p>
            </div>
            {{-- Controles de paginación + “Ir a página” --}}
            <div class="col-sm-auto d-flex align-items-center">
                <nav aria-label="Paginación de clientes">
                    <ul class="pagination pagination-rounded pagination-sm mb-0">
                        {{-- Primero --}}
                        <li class="page-item {{ $clientes->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $clientes->url(1) }}" aria-label="Primero">
                                <i class="fa fa-angle-double-left"></i>
                            </a>
                        </li>

                        {{-- Anterior --}}
                        <li class="page-item {{ $clientes->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $clientes->previousPageUrl() }}" rel="prev" aria-label="Anterior">
                                <i class="fa fa-chevron-left"></i>
                            </a>
                        </li>

                        @php
                            $last = $clientes->lastPage();
                            $curr = $clientes->currentPage();
                            $start = max(1, $curr - 1);
                            $end   = min($last, $curr + 1);
                            if ($curr === 1)     $end   = min($last, 3);
                            if ($curr === $last) $start = max(1, $last - 2);
                        @endphp

                        @if($start > 1)
                            <li class="page-item disabled"><span class="page-link">…</span></li>
                        @endif

                        @foreach ($clientes->getUrlRange($start, $end) as $page => $url)
                            <li class="page-item {{ $page == $curr ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endforeach

                        @if($end < $last)
                            <li class="page-item disabled"><span class="page-link">…</span></li>
                        @endif

                        {{-- Siguiente --}}
                        <li class="page-item {{ $clientes->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link" href="{{ $clientes->nextPageUrl() }}" rel="next" aria-label="Siguiente">
                                <i class="fa fa-chevron-right"></i>
                            </a>
                        </li>

                        {{-- Último --}}
                        <li class="page-item {{ $clientes->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link" href="{{ $clientes->url($last) }}" aria-label="Último">
                                <i class="fa fa-angle-double-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>

                {{-- Ir a página --}}
                <form method="GET" action="{{ route('clientes.index') }}" class="d-flex ms-3 align-items-center">
                    {{-- Preserva todos los otros filtros en la query --}}
                    @foreach(request()->except('page') as $key => $value)
                        @if(is_array($value))
                            @foreach($value as $v)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach


                    <label for="input-page" class="mb-0 me-1 small">Ir a</label>
                    <input
                    type="number"
                    id="input-page"
                    name="page"
                    min="1"
                    max="{{ $clientes->lastPage() }}"
                    class="form-control form-control-sm"
                    style="width: 70px;"
                    placeholder="pág."
                    value="{{ $clientes->currentPage() }}"
                    />
                    <button type="submit" class="btn btn-sm btn-primary ms-1">Ir</button>
                </form>
            </div>
        @else
            <div class="col">
                <p class="mb-0 text-muted small">
                    No se han encontrado recalls
                </p>
            </div>
        @endif

    </div>

    {{-- 📋 Resultados --}}
    <div class="table-responsive mb-3 shadow-lg">
        <table id="tabla-recalls" class="table table-striped table-hover table-bordered align-middle table-nowrap">
            <thead class="text-center align-middle">
                <tr>
                    <th class="col-10ch py-1 px-2" style="background-color: var( --tabla-header-bg);">Cliente</th>
                    <th class="campo-dato-secundario py-1 px-2" style="background-color: var( --tabla-header-bg);">Empresa</th>
                    <th class="col-25ch py-1 px-2" style="background-color: var( --tabla-header-bg);">Contacto</th>
                    <th class="col-15ch py-1 px-2" style="background-color: var( --tabla-header-bg);">Teléfono</th>
                    <th class="col-20ch py-1 px-2" style="background-color: var( --tabla-header-bg);">Email</th>
                    <th class="col-10ch py-1 px-2 text-truncate" style="background-color: var( --tabla-header-bg);">Recall</th>
                    <th class="col-15ch py-1 px-2" style="background-color: var( --tabla-header-bg);">Asignado A</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($clientes as $r)
                    <tr>
                        {{-- Cliente ID --}}
                        <td class="col-10ch py-1 px-2 text-truncate text-center" title="{{ $estado === 'contestados' ? $r->cliente->id_cliente : $r->id_cliente }}">
                            {{ $estado === 'contestados' ? $r->cliente->id_cliente : $r->id_cliente }}
                        </td>
                        {{-- Empresa --}}
                        <td class="campo-dato-secundario py-1 px-2 text-truncate">
                            <a href="{{ route('clientes.view', $estado === 'contestados' ? $r->cliente->id_cliente : $r->id_cliente) }}">
                                {{ $estado === 'contestados' ? $r->cliente->nombre : $r->nombre }}
                            </a>
                        </td>
                        {{-- Contacto --}}
                        <td class="col-25ch py-1 px-2 text-truncate">
                            @php
                                $contacto = $estado === 'contestados'
                                    ? $r->cliente->primerContacto
                                    : $r->primerContacto;
                            @endphp
                            {{ $contacto->nombre ?? '—' }} {{ $contacto->apellido_p ?? '' }}
                        </td >
                        {{-- Telefono --}}
                        <td class="col-15ch py-1 px-2 text-truncate text-center phone-field">
                            {{ $contacto->telefono1 ?? '—' }}
                        </td>
                        {{-- Email --}}
                        <td class="col-20ch py-1 px-2 text-truncate">
                            {{ $contacto->email ?? '—' }}
                        </td>
                        {{-- Próxima llamada --}}
                        <td class="col-10ch py-1 px-2 text-center">
                            {{ $estado === 'contestados' ? $r->fecha_reprogramacion : $r->recall }}
                        </td>
                        {{-- Asignado a --}}
                        <td class="col-15ch py-1 px-2">
                            {{ $estado === 'contestados' ? strtoupper($r->usuario->username)  ?? '—' : strtoupper($r->vendedor->username) ?? '—' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Sin registros por mostrar</td>
                    </tr>
                @endforelse
            </tbody>
    
        </table>
    </div>

    {{-- ───────── Paginación ───────── --}}
    <div class="row align-items-center mb-3">

        {{-- Texto de totales --}}
        @if ($clientes != [])
            <div class="col">
                <p class="mb-0 text-muted small">
                    Mostrando <strong>{{ $clientes->firstItem() ?? "Todos" }}</strong> a <strong>{{ $clientes->lastItem() }}</strong>
                    | Total: <strong>{{ $clientes->total() ?? "Todos" }}</strong> recalls encontrados
                </p>
            </div>
            {{-- Controles de paginación + “Ir a página” --}}
            <div class="col-sm-auto d-flex align-items-center">
                <nav aria-label="Paginación de clientes">
                    <ul class="pagination pagination-rounded pagination-sm mb-0">
                        {{-- Primero --}}
                        <li class="page-item {{ $clientes->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $clientes->url(1) }}" aria-label="Primero">
                                <i class="fa fa-angle-double-left"></i>
                            </a>
                        </li>

                        {{-- Anterior --}}
                        <li class="page-item {{ $clientes->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $clientes->previousPageUrl() }}" rel="prev" aria-label="Anterior">
                                <i class="fa fa-chevron-left"></i>
                            </a>
                        </li>

                        @php
                            $last = $clientes->lastPage();
                            $curr = $clientes->currentPage();
                            $start = max(1, $curr - 1);
                            $end   = min($last, $curr + 1);
                            if ($curr === 1)     $end   = min($last, 3);
                            if ($curr === $last) $start = max(1, $last - 2);
                        @endphp

                        @if($start > 1)
                            <li class="page-item disabled"><span class="page-link">…</span></li>
                        @endif

                        @foreach ($clientes->getUrlRange($start, $end) as $page => $url)
                            <li class="page-item {{ $page == $curr ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endforeach

                        @if($end < $last)
                            <li class="page-item disabled"><span class="page-link">…</span></li>
                        @endif

                        {{-- Siguiente --}}
                        <li class="page-item {{ $clientes->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link" href="{{ $clientes->nextPageUrl() }}" rel="next" aria-label="Siguiente">
                                <i class="fa fa-chevron-right"></i>
                            </a>
                        </li>

                        {{-- Último --}}
                        <li class="page-item {{ $clientes->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link" href="{{ $clientes->url($last) }}" aria-label="Último">
                                <i class="fa fa-angle-double-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>

                {{-- Ir a página --}}
                <form method="GET" action="{{ route('clientes.index') }}" class="d-flex ms-3 align-items-center">
                    {{-- Preserva todos los otros filtros en la query --}}
                    @foreach(request()->except('page') as $key => $value)
                        @if(is_array($value))
                            @foreach($value as $v)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach


                    <label for="input-page" class="mb-0 me-1 small">Ir a</label>
                    <input
                    type="number"
                    id="input-page"
                    name="page"
                    min="1"
                    max="{{ $clientes->lastPage() }}"
                    class="form-control form-control-sm"
                    style="width: 70px;"
                    placeholder="pág."
                    value="{{ $clientes->currentPage() }}"
                    />
                    <button type="submit" class="btn btn-sm btn-primary ms-1">Ir</button>
                </form>
            </div>
        @else
            <div class="col">
                <p class="mb-0 text-muted small">
                    No se han encontrado recalls
                </p>
            </div>
        @endif

    </div>
</div>
@endsection
