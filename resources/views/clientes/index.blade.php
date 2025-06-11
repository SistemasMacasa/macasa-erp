@extends('layouts.app')
@section('title', 'SIS 3.0 | Mis Cuentas')



@section('content')
    <div class="container-fluid">

        {{-- 🧭 Migas de pan --}}
    @section('breadcrumb')

        <li class="breadcrumb-item"><a href="{{ route('inicio') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Mis Cuentas</li>
    @endsection

    <h2 class="mb-3">Mis Cuentas</h2>

    {{-- 🎛 Botonera --}}
    <div class="d-flex flex-wrap gap-2 mb-3">
        <a href="{{ url()->previous() }}" class="btn btn-secondary btn-principal">
            <i class="fa fa-arrow-left me-1"></i> Regresar
        </a>

        <a href="{{ route('inicio') }}" class="btn btn-primary btn-principal">
            <i class="fa fa-phone me-1"></i> Mis Recall's
        </a>

        <a href="{{ route('inicio') }}" class="btn btn-primary btn-principal">
            <i class="fa fa-check me-1"></i> Enviar carta de presentación
        </a>
    </div>

    {{-- 🔎 Buscador --}}
    <form method="GET" action="{{ route('clientes.index') }}">
        <div class="card mb-3">
            <div class="card-header text-center">
                <h5 class="mb-0">Filtros</h5>
            </div>

            <div class="card-body bg-body">
                <div class="row gx-3 gy-2 justify-content-between">

                    {{-- Fecha de creación --}}

                    {{-- Búsqueda global --}}
                    <div class="col">
                        <label for="search" class="form-label"><i class="fa fa-search"></i> Búsqueda</label>
                        <div class="input-group">
                            <input type="text" name="search" id="search" class="form-control"
                                placeholder="Cliente, email, ID…" value="{{ request('search') }}" maxlength="25">
                        </div>
                    </div>

                    {{-- Ejecutivos (multi-select con Select2) --}}
                    <div class="col">
                        <label for="ejecutivos" class="form-label">Ejecutivo(s)</label>
                        <select id="ejecutivos" name="ejecutivos[]" placeholder="Todos" class="form-select select2"
                            {{-- ← SIN "form-select" --}} multiple data-placeholder="Seleccione uno o varios ejecutivos">
                            <option value="base_general"
                                {{ in_array('base_general', request('ejecutivos', [])) ? 'selected' : '' }}>
                                Base General
                            </option>

                            @foreach ($vendedores as $v)
                                <option value="{{ $v->id_usuario }}"
                                    {{ in_array($v->id_usuario, request('ejecutivos', [])) ? 'selected' : '' }}>
                                    {{ $v->username }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Sector --}}
                    <div class="col">
                        <label for="sector" class="form-label">Sector</label>
                        <select name="sector" id="sector" class="form-select">
                            <option value="">Todos</option>
                            @foreach ($sectores as $s)
                                <option value="{{ $s }}" {{ request('sector') == $s ? 'selected' : '' }}>
                                    {{ ucfirst(mb_strtolower($s)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Segmento --}}
                    <div class="col">
                        <label for="segmento" class="form-label">Segmento</label>
                        <select name="segmento" id="segmento" class="form-select">
                            <option value="">Todos</option>
                            @foreach ($segmentos as $s)
                                @php
                                    $label = match ($s) {
                                        'macasa cuentas especiales' => 'Macasa Cuentas Especiales',
                                        'tekne store ecommerce' => 'Tekne Store E-Commerce',
                                        'la plaza ecommerce' => 'LaPlazaEnLinea E-Commerce',
                                        default => ucfirst($s), // por si aparece otro
                                    };
                                @endphp
                                <option value="{{ $s }}" {{ request('segmento') == $s ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    {{-- Ciclo de venta --}}
                    <div class="col">
                        <label for="cycle" class="form-label">Ciclo de venta</label>
                        <select name="cycle" id="cycle" class="form-select">
                            <option value="">Todos</option>
                            @foreach ($ciclos as $ciclo)
                                <option value="{{ $ciclo }}"
                                    {{ request('cycle') == $ciclo ? 'selected' : '' }}>
                                    @switch($ciclo)
                                        @case('venta')
                                            Venta
                                        @break

                                        @case('cotizacion')
                                            Cotización
                                        @break

                                        @default
                                            {{ ucfirst(mb_strtolower($ciclo)) }}
                                    @endswitch
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div> {{-- /.row --}}


                <div class="row gx-3 gy-2 justify-content-between mt-3">

                    {{-- Registros por página --}}
                    <div class="col">
                        <label for="perPage" class="form-label">Ver registros</label>
                        <select name="perPage" id="perPage" class="form-select">
                            <option value="all" {{ request('perPage') == 'all' ? 'selected' : '' }}>Todos</option>

                            @foreach ([10, 25, 50, 100] as $n)
                                <option value="{{ $n }}"
                                    {{ request('perPage', 25) == $n ? 'selected' : '' }}>{{ $n }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                </div>


                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="{{ route('clientes.index') }}"
                        class="btn btn-secondary btn-limpiar d-flex align-items-center justify-content-center ">
                        <i class="fa fa-eraser me-1"></i>
                        Limpiar
                    </a>

                    <button type="submit"
                        class="btn btn-success btn-limpiar d-flex align-items-center justify-content-center">
                        <i class="fa fa-search me-1"></i>
                        Buscar
                    </button>
                </div>


            </div>
        </div>
    </form>

    {{-- ───────── Paginación ───────── --}}
    <div class="row align-items-center mb-3">
        {{-- Texto de totales --}}
        <div class="col-sm">
            <p class="mb-0 text-muted small">
                Mostrando <strong>{{ $clientes->firstItem() ?? 'Todos' }}</strong> a
                <strong>{{ $clientes->lastItem() }}</strong>
                de <strong>{{ $clientes->total() ?? 'Todos' }}</strong> clientes encontrados
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
                        <a class="page-link" href="{{ $clientes->previousPageUrl() }}" rel="prev"
                            aria-label="Anterior">
                            <i class="fa fa-chevron-left"></i>
                        </a>
                    </li>

                    @php
                        $last = $clientes->lastPage();
                        $curr = $clientes->currentPage();
                        $start = max(1, $curr - 1);
                        $end = min($last, $curr + 1);
                        if ($curr === 1) {
                            $end = min($last, 3);
                        }
                        if ($curr === $last) {
                            $start = max(1, $last - 2);
                        }
                    @endphp

                    @if ($start > 1)
                        <li class="page-item disabled"><span class="page-link">…</span></li>
                    @endif

                    @foreach ($clientes->getUrlRange($start, $end) as $page => $url)
                        <li class="page-item {{ $page == $curr ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    @if ($end < $last)
                        <li class="page-item disabled"><span class="page-link">…</span></li>
                    @endif

                    {{-- Siguiente --}}
                    <li class="page-item {{ $clientes->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $clientes->nextPageUrl() }}" rel="next"
                            aria-label="Siguiente">
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
                @foreach (request()->except('page') as $key => $value)
                    @if (is_array($value))
                        @foreach ($value as $v)
                            <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                        @endforeach
                    @else
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach


                <label for="input-page" class="mb-0 me-1 small">Ir a</label>
                <input type="number" id="input-page" name="page" min="1"
                    max="{{ $clientes->lastPage() }}" class="form-control form-control-sm caja-paginado"
                    placeholder="pág." value="{{ $clientes->currentPage() }}" />
                <button type="submit" class="btn btn-sm btn-primary ms-1">Ir</button>
            </form>
        </div>
    </div>
    {{-- ────────── Fin paginación ────────── --}}

    {{-- 📋 Tabla responsiva --}}
    <div class="table-responsive mb-3 shadow-lg">
        <table id="tabla-clientes"
            class="table align-middle table-hover table-nowrap
                                         table-striped table-bordered">
            <thead class="text-center align-middle">
                <tr>
                    <th class="header-tabla py-1 px-2 filtro-asc-desc">ID
                        Cliente</th>
                    <th class="header-tabla py-1 px-2 filtro-asc-desc">Empresa
                    </th>
                    <th class="header-tabla py-1 px-2 filtro-asc-desc" >Contacto
                    </th>
                    <th class="header-tabla py-1 px-2">Teléfono</th>
                    <th class="header-tabla py-1 px-2">Correo</th>
                    <th class="header-tabla py-1 px-2 filtro-asc-desc">Sector
                    </th>
                    <th class="header-tabla py-1 px-2 filtro-asc-desc" >Segmento
                    </th>
                    <th class="header-tabla py-1 px-2 filtro-asc-desc">Ciclo
                    </th>
                    <th class="header-tabla py-1 px-2 filtro-asc-desc">Asignado
                        a</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($clientes as $c)
                    @php
                        $pc = $c->primerContacto;
                        $tel = optional($pc)->telefono1;
                        $email = optional($pc)->email;
                        $username = $c->vendedor->username ?? '';
                    @endphp
                    <tr>
                        {{-- ID Cliente --}}
                        <td class="py-1 px-2 text-truncate text-center" title="{{ $c->id_cliente }}">
                            {{ Str::limit($c->id_cliente, 6) }}
                        </td>

                        {{-- Empresa --}}
                        <td class="py-1 px-2 text-truncate" title="{{ $c->nombre }}">
                            <a href="{{ route('clientes.view', $c->id_cliente) }}"
                                class="text-decoration-underline fw-bold text-dark">
                                {{ Str::limit($c->nombre, 30) }}
                            </a>
                        </td>

                        {{-- Contacto --}}
                        <td class="py-1 px-2 text-truncate" title="{{ optional($pc)->nombre_completo }}">
                            @if (optional($pc)->nombre_completo)
                                {{ Str::limit($pc->nombre_completo, 25) }}
                            @else
                                —
                            @endif
                        </td>

                        {{-- Teléfono (sin truncar) --}}
                        <td class="py-1 px-2 text-center">
                            @if ($tel)
                                <a class="phone-field" href="tel:{{ $tel }}">{{ $tel }}</a>
                            @else
                                —
                            @endif
                        </td>

                        {{-- Correo --}}
                        <td class="py-1 px-2 text-truncate" title="{{ $email }}">
                            @if ($email)
                                <a href="mailto:{{ $email }}">
                                    {{ Str::limit($email, 30) }}
                                </a>
                            @else
                                —
                            @endif
                        </td>

                        {{-- Sector --}}
                        <td class="py-1 px-2 text-center">
                            @switch($c->sector)
                                @case('privada')
                                    {{ 'Privado' }}
                                @break

                                @case('gobierno')
                                    {{ 'Gobierno' }}
                                @break

                                @case('persona')
                                    {{ 'Persona' }}
                                @break

                                @default
                                    —
                            @endswitch

                        </td>

                        {{-- Segmento filtrado --}}
                        <td class="py-1 px-2 text-center">
                            @php $seg = mb_strtolower($c->segmento ?? ''); @endphp
                            @switch($seg)
                                @case('macasa cuentas especiales')
                                    {{ 'Macasa Cuentas Especiales' }}
                                @break

                                @case('tekne store ecommerce')
                                    {{ 'Tekne Store E-Commerce' }}
                                @break

                                @case('la plaza ecommerce')
                                    {{ 'LaPlazaEnLinea E-Commerce' }}
                                @break

                                @default
                                    —
                            @endswitch
                        </td>

                        {{-- Ciclo Venta --}}
                        <td class="py-1 px-2 text-center">
                            <span class="badge"
                                style="background-color:{{ $c->ciclo_venta === 'venta' ? 'var(--mc-verde)' : '#FEE028' }};
                                    color:{{ $c->ciclo_venta === 'venta' ? '#fff' : '#000' }};
                                    font-size: var(--bs-body-font-size);
                                    min-width:10ch;">

                                @switch($c->ciclo_venta)
                                    @case('venta')
                                        {{ 'Venta' }}
                                    @break

                                    @case('cotizacion')
                                        {{ 'Cotización' }}
                                    @break

                                    @default
                                        —
                                @endswitch
                            </span>
                        </td>

                        {{-- Asignado a --}}
                        <td class="py-1 px-2 text-truncate text-uppercase" title="{{ strtoupper($username) }}">
                            {{ $username ? Str::limit(strtoupper($username), 15) : 'BASE GENERAL' }}
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
                        <a class="page-link" href="{{ $clientes->previousPageUrl() }}" rel="prev"
                            aria-label="Anterior">
                            <i class="fa fa-chevron-left"></i>
                        </a>
                    </li>

                    @php
                        $last = $clientes->lastPage();
                        $curr = $clientes->currentPage();
                        $start = max(1, $curr - 1);
                        $end = min($last, $curr + 1);
                        if ($curr === 1) {
                            $end = min($last, 3);
                        }
                        if ($curr === $last) {
                            $start = max(1, $last - 2);
                        }
                    @endphp

                    @if ($start > 1)
                        <li class="page-item disabled"><span class="page-link">…</span></li>
                    @endif

                    @foreach ($clientes->getUrlRange($start, $end) as $page => $url)
                        <li class="page-item {{ $page == $curr ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    @if ($end < $last)
                        <li class="page-item disabled"><span class="page-link">…</span></li>
                    @endif

                    {{-- Siguiente --}}
                    <li class="page-item {{ $clientes->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $clientes->nextPageUrl() }}" rel="next"
                            aria-label="Siguiente">
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
                @foreach (request()->except('page') as $key => $value)
                    @if (is_array($value))
                        @foreach ($value as $v)
                            <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                        @endforeach
                    @else
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach


                <label for="input-page" class="mb-0 me-1 small">Ir a</label>
                <input type="number" id="input-page" name="page" min="1"
                    max="{{ $clientes->lastPage() }}" class="form-control form-control-sm caja-paginado"
                    placeholder="pág." value="{{ $clientes->currentPage() }}" />
                <button type="submit" class="btn btn-sm btn-primary ms-1">Ir</button>
            </form>
        </div>
    </div>
    {{-- ────────── Fin paginación ────────── --}}
</div>



<script>
    //Filtros ASC/DESC en cabecera de la tabla pincipal
    document.addEventListener('DOMContentLoaded', () => {
        const table = document.getElementById('tabla-clientes');
        const tbody = table.tBodies[0];
        // 1) Todos los th
        const allThs = Array.from(table.querySelectorAll('thead th'));
        // 2) Solo los que tienen la clase filtro-asc-desc
        const sortableThs = allThs.filter(th => th.classList.contains('filtro-asc-desc'));
        const dir = {}; // guardará la dirección por índice de columna

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
                        res = parseFloat(A.replace(/[^\d.-]/g, '')) - parseFloat(B
                            .replace(/[^\d.-]/g, ''));
                    } else {
                        res = A.localeCompare(B, undefined, {
                            numeric: true,
                            sensitivity: 'base'
                        });
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
