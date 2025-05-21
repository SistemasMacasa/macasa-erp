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
            <div class="card-header" style="background-color: rgba(81, 86, 190, 0.1); font-weight: 700 !important;">
                Filtros
            </div>

            <div class="card-body">
                <div class="row gx-3 gy-2">

                    {{-- Búsqueda global --}}
                    <div class="col-md-3">
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
                    <div class="col-md-3">
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
                            >{{ ucfirst(mb_strtolower($s)) }}</option>
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
                            >{{ ucfirst(mb_strtolower($s)) }}</option>
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
                            >{{ ucfirst(mb_strtolower($ciclo)) }}</option>
                            @endforeach
                        </select>
                    </div>
            
                    {{-- Registros por página --}}
                    <div class="col-md-3">
                        <label for="perPage" class="form-label">Ver registros</label>
                        <select name="perPage" id="perPage" class="form-select">
                            @foreach([10,25,50,100] as $n)
                            <option
                                value="{{ $n }}"
                                {{ request('perPage',25) == $n ? 'selected':'' }}
                            >{{ $n }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 d-flex gap-2 align-items-end">
                        <a  href="{{ route('clientes.index') }}"
                            class="btn btn-light d-flex align-items-center justify-content-center"
                            style="height:38px">
                            <i class="fa fa-eraser me-1"></i> Limpiar
                        </a>

                        <button type="submit"
                                class="btn btn-success d-flex align-items-center justify-content-center"
                                style="height:38px">
                            <i class="fa fa-search me-1"></i> Buscar
                        </button>
                    </div>
                    
                </div>
            </div>
        </div>
    </form>

    {{-- ───────── Paginación ───────── --}}
    <div class="row align-items-center mb-4">
        {{-- Texto de totales --}}
        <div class="col-sm">
            <p class="mb-0 text-muted small">
                Mostrando <strong>{{ $clientes->firstItem() }}</strong> a <strong>{{ $clientes->lastItem() }}</strong>
                de <strong>{{ $clientes->total() }}</strong> clientes encontrados
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
    {{-- ────────── Fin paginación ────────── --}}

    {{-- 📋 Tabla responsiva --}}
    <div class="table-responsive mb-4 shadow-lg">
        <table id="tabla-clientes" class="table align-middle table-hover table-nowrap
                                         table-striped table-bordered">
            <thead class="table-dark text-center align-middle" style="font-size: var(--bs-body-font-size);">
                <tr>
                    <th class="py-1 px-2 filtro-asc-desc" style="width: 70px;">ID Cliente</th>
                    <th class="py-1 px-2 filtro-asc-desc">Empresa</th>
                    <th class="py-1 px-2 filtro-asc-desc">Contacto</th>
                    <th class="py-1 px-2">Teléfono</th>
                    <th class="py-1 px-2">Correo</th>
                    <th class="py-1 px-2 filtro-asc-desc">Sector</th>
                    <th class="py-1 px-2 filtro-asc-desc">Segmento</th>
                    <th class="py-1 px-2 filtro-asc-desc">Ciclo</th>
                    <th class="py-1 px-2 filtro-asc-desc">Asignado a</th>
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
                        <td class="py-1 px-2 text-truncate" style="max-width:220px;" title="{{ $c->nombre }}">
                            <a href="{{ route('clientes.view', $c->id_cliente) }}"
                                class="text-decoration-underline fw-bold text-dark">
                                {{-- ID con enlace al view --}}
                                {{ Str::limit($c->id_cliente, 7) }}
                            </a>
                        </td>

                        <td class="py-1 px-2 text-truncate" style="max-width:220px;" title="{{ $c->nombre }}">
                            <a href="{{ route('clientes.view', $c->id_cliente) }}" style="color: inherit; text-decoration: underline;  font-weight: bold;">
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
                        <td>{{ $c->sector ? ucfirst(mb_strtolower($c->sector)) : '—' }}</td>
                        <td>{{ $c->segmento ? ucfirst(mb_strtolower($c->segmento)) : '—' }}</td>

                        {{-- Ciclo como badge --}}
                        <td>
                            <span
                                class="badge"
                                style="background-color:{{ $c->ciclo_venta === 'venta' ? '#198754' : '#FFBF00' }};
                                    color:{{ $c->ciclo_venta === 'venta' ? '#fff' : '#212529' }}; font-size: var(--bs-body-font-size); min-width: 79px;">
                                {{ ucfirst(mb_strtolower($c->ciclo_venta)) }}
                            </span>
                        </td>


                        <td>
                            {{ $c->vendedor->nombre ?? '—' }} {{$c->vendedor->apellido_p ?? '' }} {{ $c->vendedor->apellido_m ?? '' }}
                        </td>
                    </tr>

                @endforeach
            </tbody>
        </table>
    </div> {{-- /.table-responsive --}}

    {{-- ───────── Paginación ───────── --}}
    <div class="row align-items-center mb-4">
        {{-- Texto de totales --}}
        <div class="col-sm">
            <p class="mb-0 text-muted small">
                Mostrando <strong>{{ $clientes->firstItem() }}</strong> a <strong>{{ $clientes->lastItem() }}</strong>
                de <strong>{{ $clientes->total() }}</strong> clientes encontrados
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
    {{-- ────────── Fin paginación ────────── --}}
    


    <script>
        //Filtros ASC/DESC en cabecera de la tabla pincipal
        document.addEventListener('DOMContentLoaded', () => {
        const table = document.getElementById('tabla-clientes');
        const tbody = table.tBodies[0];
        // 1) Todos los th
        const allThs = Array.from(table.querySelectorAll('thead th'));
        // 2) Solo los que tienen la clase filtro-asc-desc
        const sortableThs = allThs.filter(th => th.classList.contains('filtro-asc-desc'));
        const dir = {};  // guardará la dirección por índice de columna

        sortableThs.forEach(th => {
            // posición real de la columna
            const colIndex = th.cellIndex;

            th.style.cursor = 'pointer';
            // agrega la flecha
            const arrow = document.createElement('span');
            arrow.className = 'sort-arrow';
            th.append(' ', arrow);

            th.addEventListener('click', () => {
            // alterna dirección
            dir[colIndex] = dir[colIndex] === 'asc' ? 'desc' : 'asc';

            // extrae y ordena filas
            const rows = Array.from(tbody.rows);
            rows.sort((a, b) => {
                const A = a.cells[colIndex].innerText.trim();
                const B = b.cells[colIndex].innerText.trim();
                // parsea número si usas data-type="number", o texto por defecto
                let res = 0;
                if (th.dataset.type === 'number') {
                res = parseFloat(A.replace(/[^\d.-]/g, '')) - parseFloat(B.replace(/[^\d.-]/g, ''));
                } else {
                res = A.localeCompare(B, undefined, { numeric: true, sensitivity: 'base' });
                }
                return dir[colIndex] === 'asc' ? res : -res;
            });

            // reinyecta las filas ordenadas
            rows.forEach(r => tbody.appendChild(r));

            // actualiza clases de flecha
            sortableThs.forEach(t => t.classList.remove('asc', 'desc'));
            th.classList.add(dir[colIndex]);
            });
        });
        });
    </script>

@endsection