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
    <div class="card mb-4 shadow">
        <div class="card-header text-center">
            <h5 class="mb-0">Filtros</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('clientes.recalls') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="busqueda" class="form-label">Búsqueda</label>
                    <input type="text" name="busqueda" id="busqueda" class="form-control" placeholder="Nombre, empresa, teléfono...">
                </div>

                <div class="col-md-2">
                    <label for="estado" class="form-label">Estado de recall</label>
                    <select name="estado" id="estado" class="form-select">
                        <option value="pendientes" selected>Pendientes</option>
                        <option value="contestados">Contestados</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Jefe de equipo</label>
                    <select class="form-select" disabled>
                        <option value="">Todos</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="id_vendedor" class="form-label">Ejecutivo</label>
                    <select name="id_vendedor" id="id_vendedor" class="form-select">
                        <option value="">Todos</option>
                        @foreach($ejecutivos as $id => $nombre)
                            <option value="{{ $id }}" {{ request('id_vendedor') == $id ? 'selected' : '' }}>
                                {{ $nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="orden" class="form-label">Ordenar por</label>
                    <select name="orden" id="orden" class="form-select">
                        <option value="nombre" {{ request('orden') === 'nombre' ? 'selected' : '' }}>Empresa</option>
                        <option value="fecha" {{ request('orden') === 'fecha' ? 'selected' : '' }}>Fecha</option>
                    </select>
                </div>


                <div class="col-md-2">
                    <label for="ver" class="form-label">Ver registros</label>
                    <select name="ver" id="ver" class="form-select">
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="500">500</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fa fa-search me-1"></i> Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ───────── Paginación ───────── --}}
    <div class="row align-items-center mb-3">
        {{-- Texto de totales --}}
        <div class="col">
            <p class="mb-0 text-muted small">
                Mostrando <strong>{{ $clientes->firstItem() ?? "Todos" }}</strong> a <strong>{{ $clientes->lastItem() }}</strong>
                de <strong>{{ $clientes->total() ?? "Todos" }}</strong> clientes encontrados
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
    </div>

    {{-- 📋 Resultados --}}
    <div class="card shadow">
        <div class="card-header text-center">
            <h5 class="mb-0">Filtros</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover table-bordered align-middle">
                <thead>
                    <tr>
                        <th></th>
                        <th>Cliente</th>
                        <th>Empresa</th>
                        <th>Contacto</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Próxima llamada</th>
                        <th>Asignado A</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($registros as $r)
                        <tr>
                            <td>
                                <a href="{{ route('clientes.view', $estado === 'contestados' ? $r->cliente->id_cliente : $r->id_cliente) }}"
                                class="btn btn-outline-primary btn-sm">Ver cuenta</a>
                            </td>

                            <td>
                                {{ $estado === 'contestados' ? $r->cliente->id_cliente : $r->id_cliente }}
                            </td>

                            <td>
                                {{ $estado === 'contestados' ? $r->cliente->nombre : $r->nombre }}
                            </td>

                            <td>
                                @php
                                    $contacto = $estado === 'contestados'
                                        ? $r->cliente->primerContacto
                                        : $r->primerContacto;
                                @endphp
                                {{ $contacto->nombre ?? '—' }} {{ $contacto->apellido_p ?? '' }}
                            </td>

                            <td>
                                {{ $contacto->telefono1 ?? '—' }}
                            </td>

                            <td>
                                {{ $contacto->email ?? '—' }}
                            </td>

                            <td>
                                {{ $estado === 'contestados' ? $r->fecha_reprogramacion : $r->recall }}
                            </td>

                            <td>
                                {{ $estado === 'contestados' ? $r->usuario->usuario ?? '—' : $r->vendedor->usuario ?? '—' }}
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
    </div>

    {{-- ───────── Paginación ───────── --}}
    <div class="row align-items-center mb-3">
        {{-- Texto de totales --}}
        <div class="col">
            <p class="mb-0 text-muted small">
                Mostrando <strong>{{ $clientes->firstItem() ?? "Todos" }}</strong> a <strong>{{ $clientes->lastItem() }}</strong>
                de <strong>{{ $clientes->total() ?? "Todos" }}</strong> clientes encontrados
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
    </div>
</div>
@endsection
