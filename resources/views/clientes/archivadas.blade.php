@extends('layouts.app')
@section('title', 'SIS 3.0 | Mis Cuentas')

@section('content')
    <div class="container-fluid">

        {{-- 🧭 Migas de pan --}}
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('inicio') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Cuentas Archivadas</li>
    @endsection

    <h2 class="mb-3 text-titulo">Cuentas Archivadas</h2>

    {{-- 🎛 Botonera --}}
    <div class=" row-fluid gap-2 mb-3">
        <a href="{{ url()->previous() }}"
            class="col-md-2 btn btn-secondary d-flex align-items-center justify-content-center ">
            <i class="fa fa-arrow-left me-1"></i> Regresar
        </a>

        <a href="{{ route('clientes.recalls') }}"
            class=" col-md-2 btn btn-primary d-flex align-items-center justify-content-center">
            <i class="fa fa-list me-1"></i> Mis Recall's
        </a>
    </div>

    {{-- 🔎 Buscador --}}
    <form method="GET" action="{{ route('clientes.archivadas') }}">
        <div class="card mb-3">
            <div class="card-header text-center">
                <h5 class="mb-0 text-subtitulo">Filtros</h5>
            </div>

            <div class="card-body">
                <div class="row gx-3 gy-2 ">

                    {{-- Búsqueda global --}}
                    <div class="col-md-2">
                        <label for="search" class="form-label"><i class="fa fa-search text-normal"></i>
                            Búsqueda</label>
                        <div class="input-group">
                            <input type="text" name="search" id="search" class="form-control"
                                placeholder="Cliente, email, ID…" value="{{ request('search') }}" maxlength="25">
                        </div>
                    </div>

                    {{-- Ejecutivos (multi-select con Select2) --}}
                    <div class="col-md-2">
                        <label for="ejecutivos" class="form-label text-normal">Ejecutivo(s)</label>
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
                    <div class="col-md-2">
                        <label for="sector" class="form-label text-normal">Sector</label>
                        <select name="sector" id="sector" class="form-select">
                            <option value="">Todos</option>
                            @foreach ($sectores as $s)
                                <option value="{{ $s }}" {{ request('sector') == $s ? 'selected' : '' }}>
                                    {{ ucfirst(mb_strtolower($s)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Segmento --}}
                    <div class="col-md-2">
                        <label for="segmento" class="form-label text-normal">Segmento</label>
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
                    <div class="col-md-2">
                        <label for="cycle" class="form-label text-normal">Ciclo de venta</label>
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


                <div class="row gx-3 gy-2 mt-3">

                    {{-- Registros por página --}}
                    <div class="col-xxl-2 col-xl-4">
                        <label for="perPage" class="form-label text-normal">Ver registros</label>
                        <select name="perPage" id="perPage" class="form-select">
                            <option value="all" {{ request('perPage') == 'all' ? 'selected' : '' }}>Todos</option>

                            @foreach ([10, 25, 50, 100] as $n)
                                <option value="{{ $n }}"
                                    {{ request('perPage', 25) == $n ? 'selected' : '' }}>{{ $n }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- ① Toggle ON/OFF multiselección --}}
                    <div class="col-6 col-md-2 col-xxl-2 d-flex justify-content-center align-items-center">
                        <label class="switch">
                            <input type="checkbox" id="toggleBulk">
                            <span class="slider round"></span>
                        </label>
                        <span class="ms-2">Modo Restaurar</span>
                    </div>

                    {{-- Ejecutivo Destino (oculto hasta toggle) --}}
                    <div class="col-6 col-md-2 col-xxl-2 d-none restore-item" id="restoreSelect">
                        <label for="restoreEjecutivo" class="form-label">Ejecutivo Destino</label>
                        <select id="restoreEjecutivo" name="id_vendedor" form="formRestore"
                                class="form-select" required>
                            <option value="" disabled selected>-- Selecciona --</option>
                            <option value="base">BASE GENERAL</option>
                            @foreach($vendedores as $v)
                            <option value="{{ $v->id_usuario }}">{{ $v->nombreCompleto }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Botón RESTAURAR (oculto hasta toggle) --}}
                    <div class="col-xxl-2 col-xl-2 mt-4 d-none restore-item" id="restoreButton">
                        <button type="submit"
                                id="btnRestore"
                                form="formRestore"
                                class="btn btn-success w-100">
                            <i class="fa fa-undo me-1"></i> RESTAURAR CUENTAS
                        </button>
                    </div>

                    
                </div>


                <div class="row gx-3 gy-2 justify-content-between mt-1">
                    <div class="col" style="min-width: 172px;"></div>
                    <div class="col" style="min-width: 172px;"></div>
                    <div class="col" style="min-width: 172px;"></div>
                    <div class="col" style="min-width: 172px;">
                        
                    </div>
                    <div class="col d-flex gap-2 align-items-end">

                        <a href="{{ route('clientes.archivadas') }}"
                            class="btn btn-secondary d-flex align-items-center justify-content-center"
                            style="width:50%; /* múltiplo de 5ch */">
                            <i class="fa fa-eraser me-1"></i>
                            Limpiar
                        </a>

                        <button type="submit"
                            class="btn btn-success btn-limpiar d-flex align-items-center justify-content-center"
                            style="width: 50%;">
                            <i class="fa fa-search me-1"></i>
                            Buscar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- ───────── Paginación ───────── --}}
    <div class="row align-items-center mb-3">
        {{-- Texto de totales --}}
        <div class="col">
            <p class="mb-0 text-muted small">
                Mostrando <strong>{{ $clientes->firstItem() ?? 'Todos' }}</strong> a
                <strong>{{ $clientes->lastItem() }}</strong>
                | Total: <strong>{{ $clientes->total() ?? 'Todos' }}</strong> cuentas encontradas
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


    <form id="formRestore" method="POST" action="{{ route('clientes.restaurar-multiples') }}">
        @csrf
                        
        {{-- 📋 Tabla responsiva --}}
        <div class="table-responsive mb-3">
            <table id="tabla-clientes"
                class="table align-middle table-hover table-nowrap
                                            table-striped table-bordered">
                <thead class="text-center align-middle">
                    <tr>
                        <th class="header-tabla col-select col-5ch text-center p-1"  style="width: 5ch;">
                            {{-- ① Checkbox para seleccionar todo --}}
                            <input type="checkbox" id="selectAll" class="client-checkbox">
                        </th>
                        <th class="header-tabla py-1 px-2 filtro-asc-desc tabla-col-id">ID
                            Cliente</th>
                        <th class="header-tabla py-1 px-2 filtro-asc-desc tabla-col-empresa">Empresa
                        </th>
                        <th class="header-tabla py-1 px-2 filtro-asc-desc tabla-col-contacto">Contacto
                        </th>
                        <th class="header-tabla py-1 px-2 tabla-col-telefono">Teléfono</th>
                        <th class="header-tabla py-1 px-2 tabla-col-correo">Correo</th>
                        <th class="header-tabla py-1 px-2 filtro-asc-desc tabla-col-sector">Sector
                        </th>
                        <th class="header-tabla py-1 px-2 filtro-asc-desc tabla-col-segmento">Segmento
                        </th>
                        <th class="header-tabla py-1 px-2 filtro-asc-desc tabla-col-ciclo">Ciclo
                        </th>
                        <th class="header-tabla py-1 px-2 filtro-asc-desc tabla-col-asignado">Asignado
                            a</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($clientes as $c)
                        @php
                            $pc = $c->primerContacto;
                            $tel = optional($pc)->telefono1;
                            $email = optional($pc)->email;
                            $username = $c->vendedor->username ?? '';
                        @endphp
                        <tr>
                            <td class="col-select col-5ch text-center p-1">
                                <input type="checkbox"
                                    class="bulk-item client-checkbox"
                                    name="ids[]"
                                    value="{{ $c->id_cliente }}">
                            </td>

                            {{-- ID Cliente --}}
                            <td class="py-1 px-2 text-truncate text-center" title="{{ $c->id_cliente }}">
                                {{ Str::limit($c->id_cliente, 6) }}
                            </td>

                            {{-- Empresa --}}
                            <td class="py-1 px-2 text-truncate tabla-col-empresa" title="{{ $c->nombre }}">
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
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-3">
                                No se encontraron cuentas archivadas con los filtros seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div> {{-- /.table-responsive --}}
    </form>

    {{-- ───────── Paginación ───────── --}}
    <div class="row align-items-center mb-4">
        {{-- Texto de totales --}}
        <div class="col-sm">
            <p class="mb-0 text-muted small">
                Mostrando <strong>{{ $clientes->firstItem() ?? 'Todos'}}</strong> a <strong>{{ $clientes->lastItem() }}</strong>
                | Total: <strong>{{ $clientes->total() }}</strong> cuentas encontradas
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


@push('scripts')

    
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggle      = document.getElementById('toggleBulk');
            const restoreEls  = document.querySelectorAll('.restore-item');
            const selectAll   = document.getElementById('selectAll');
            const items       = () => document.querySelectorAll('.bulk-item');
            const titleEl     = document.querySelector('.text-titulo');
            const originalTitle = titleEl?.textContent || '';

            function toggleRowHighlightGreen(e) {
                e.target.closest('tr').classList.toggle('table-success', e.target.checked);
            }

            function setRestoreMode(on) {
                // 1) Título
                if (titleEl) {
                titleEl.textContent = on
                    ? 'Restaurar Cuentas Archivadas'
                    : originalTitle;
                }

                // 2) togglear la visibilidad de select+botón
                restoreEls.forEach(el => 
                el.classList.toggle('d-none', !on)
                );

                // 3) columna de checkboxes
                document.querySelectorAll('.col-select')
                .forEach(c => c.style.display = on ? 'table-cell' : 'none');

                // 4) limpiar checks y resaltados
                selectAll.checked = false;
                items().forEach(cb => {
                cb.checked = false;
                cb.removeEventListener('change', toggleRowHighlightGreen);
                });
                document.querySelectorAll('tr.table-success')
                .forEach(tr => tr.classList.remove('table-success'));

                // 5) si on=true, añadir listeners para el resaltado
                if (on) {
                items().forEach(cb => {
                    cb.addEventListener('change', toggleRowHighlightGreen);
                    if (cb.checked) cb.closest('tr').classList.add('table-success');
                });
                }
            }

            // Conecta el toggle
            toggle.addEventListener('change', () =>
                setRestoreMode(toggle.checked)
            );

            // Select All
            selectAll.addEventListener('change', () => {
                items().forEach(cb => {
                cb.checked = selectAll.checked;
                cb.dispatchEvent(new Event('change'));
                });
            });
        });
    </script>



@endpush

@endsection
