@extends('layouts.app')
@section('title', 'SIS 3.0 | Traspaso de cuentas')

@section('content')
    <div class="container-fluid">

        {{-- 🧭 Migas de pan --}}
        @section('breadcrumb')
            <li class="breadcrumb-item"><a href="{{ route('inicio') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Traspaso de cuentas</li>
        @endsection

        <h2 class="mb-3 text-titulo">Traspaso de cuentas</h2>

        {{-- 🎛 Botonera --}}
        <div class="d-flex flex-wrap gap-2 mb-3">
            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-principal">
                <i class="fa fa-arrow-left me-1"></i> Regresar
            </a>

            <a href="{{ route('clientes.index') }}" class="btn btn-primary btn-principal">
                <i class="fa fa-phone me-1"></i> Mis Cuentas
            </a>
        </div>

        <div id="alert-transfer" class="row justify-content-center">
            <div class="col-md-7">
            <div class="alert alert-success d-flex align-items-center py-2 px-3 shadow-sm" role="alert" style="border-radius: 0.75rem;">
                <i class="fa fa-check-circle me-2 fs-5 text-success"></i>
                <div class="small">
                <strong>¡Listo para transferir cuentas!</strong> Selecciona y mueve cuentas entre ejecutivos de forma fácil y rápida.
                </div>
            </div>
            </div>
        </div>
        @push('scripts')
        <script>
            setTimeout(function() {
            var alert = document.getElementById('alert-transfer');
            if(alert) {
                alert.style.transition = "opacity 0.5s";
                alert.style.opacity = 0;
                setTimeout(function() {
                alert.remove();
                }, 500);
            }
            }, 5000);
        </script>
        @endpush
        <div class="row">
            {{-- Formulario de cuentas (origen) --}}
                <form method="GET" action="{{ route('clientes.transfer') }}" class="col-md-5">
                    <input type="hidden" name="lado" value="origen">   
                    <input type="hidden" name="id_vendedor_destino" value="{{ request('id_vendedor_destino') }}">
                 
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Seleccione ejecutivo de origen</h5>
                        </div>
                        <div class="card-body">
                            <div class="row gx-2 gy-2 mb-4">
                                <div class="col-md-8 col-sm-12">
                                    <label for="nombre_ejecutivo" class="form-label">Ejecutivo</label>
                                    <select name="id_vendedor_origen" class="form-select">
                                        <option value="">-- Selecciona --</option>
                                        <option value="base" {{ request('id_vendedor_origen') == 'base' ? 'selected' : '' }}>BASE GENERAL</option>
                                        @foreach($vendedores as $v)
                                            <option value="{{ $v->id_usuario }}"
                                                {{ request('id_vendedor_origen') == $v->id_usuario ? 'selected' : '' }}>
                                                {{ strtoupper($v->username) }} - {{ $v->nombre }} {{ $v->apellido_p }} {{ $v->apellido_m }}
                                            </option>
                                        @endforeach
                                    </select>                                
                                </div>

                                <div class="col-md-4">
                                    <label for="orden" class="form-label">Ordenar por</label>
                                    <select name="orden" class="form-select">
                                        <option value=""            {{ request('orden') == '' ? 'selected' : '' }}>-- Selecciona -- </option>
                                        <option value="id_cliente"  {{ request('orden') == 'id_cliente' ? 'selected' : '' }}>ID Cliente</option>
                                        <option value="nombre"      {{ request('orden') == 'nombre' ? 'selected' : '' }}>Empresa</option>
                                        <option value="contacto"    {{ request('orden') == 'contacto' ? 'selected' : '' }}>Contacto</option>
                                        <option value="correo"      {{ request('orden') == 'correo' ? 'selected' : '' }}>Correo</option>
                                        <option value="sector"      {{ request('orden') == 'sector' ? 'selected' : '' }}>Sector</option>
                                        <option value="segmento"    {{ request('orden') == 'segmento' ? 'selected' : '' }}>Segmento</option>
                                        <option value="id_vendedor" {{ request('orden') == 'id_vendedor' ? 'selected' : '' }}>Asignado a</option>
                                    </select>
                                </div>
                            
                                <div class="col-md-4">
                                    <label for="ver" class="form-label">Ver registros</label>
                                    <select name="per_page" id="" class="form-select">
                                        <option value="">-- Selecciona --</option>
                                        <option value="5000" {{ request('per_page') == '5000' ? 'selected' : '' }}>Todos</option>
                                        <option value="25"  {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                                        <option value="50"  {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                                        <option value="75"  {{ request('per_page') == '75' ? 'selected' : '' }}>75</option>
                                        <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="ciclo_venta" class="form-label">Ciclo de venta</label>
                                    <select name="ciclo_venta" id="" class="form-select">
                                        <option value=""            {{ request('ciclo_venta') == '' ? 'selected' : '' }}>-- Selecciona --</option>
                                        <option value="cotizacion"  {{ request('ciclo_venta') == 'cotizacion' ? 'selected' : '' }}>Cotización</option>
                                        <option value="venta"       {{ request('ciclo_venta') == 'venta' ? 'selected' : '' }}>Venta</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Botones alineados a la derecha --}}
                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <a href="{{ route('clientes.transfer') }}" class="btn btn-secondary">
                                    <i class="fa fa-eraser me-1"></i> Limpiar
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-search me-1"></i> Buscar
                                </button>
                            </div>

                        </div>
                    </div>
                </form>

            {{-- Botones mover --}}
            <div class="col-md-2 text-center d-flex flex-column justify-content-center">
                <button type="button" id="btnAgregar" class="btn btn-success mb-2">&gt;</button>
                <button type="button" id="btnQuitar" class="btn btn-success">&lt;</button>
            </div>

            {{-- Formulario de cuentas (destino) --}}
            <form method="GET" action="{{ route('clientes.transfer') }}" class="col-md-5">
                <input type="hidden" name="lado" value="destino">   
                <input type="hidden" name="id_vendedor_destino" value="{{ request('id_vendedor_destino') }}">
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Seleccione ejecutivo de destino</h5>
                    </div>
                    <div class="card-body">
                        <div class="row gx-2 gy-2 mb-4">
                            <div class="col-md-8 col-sm-12">
                                <label for="nombre_ejecutivo" class="form-label">Ejecutivo</label>
                                <select name="id_vendedor_destino" class="form-select">
                                    <option value="">-- Selecciona --</option>
                                    <option value="base" {{ request('id_vendedor_destino') == 'base' ? 'selected' : '' }}>BASE GENERAL</option>
                                    @foreach($vendedores as $v)
                                        <option value="{{ $v->id_usuario }}"
                                            {{ request('id_vendedor_destino') == $v->id_usuario ? 'selected' : '' }}>
                                            {{ strtoupper($v->username) }} - {{ $v->nombre }} {{ $v->apellido_p }} {{ $v->apellido_m }}
                                        </option>
                                    @endforeach
                                </select>                                
                            </div>

                            <div class="col-md-4">
                                <label for="orden" class="form-label">Ordenar por</label>
                                <select name="orden" class="form-select">
                                    <option value=""            {{ request('orden') == '' ? 'selected' : '' }}>-- Selecciona -- </option>
                                    <option value="id_cliente"  {{ request('orden') == 'id_cliente' ? 'selected' : '' }}>ID Cliente</option>
                                    <option value="nombre"      {{ request('orden') == 'nombre' ? 'selected' : '' }}>Empresa</option>
                                    <option value="contacto"    {{ request('orden') == 'contacto' ? 'selected' : '' }}>Contacto</option>
                                    <option value="correo"      {{ request('orden') == 'correo' ? 'selected' : '' }}>Correo</option>
                                    <option value="sector"      {{ request('orden') == 'sector' ? 'selected' : '' }}>Sector</option>
                                    <option value="segmento"    {{ request('orden') == 'segmento' ? 'selected' : '' }}>Segmento</option>
                                    <option value="id_vendedor" {{ request('orden') == 'id_vendedor' ? 'selected' : '' }}>Asignado a</option>
                                </select>
                            </div>
                        
                            <div class="col-md-4">
                                <label for="ver" class="form-label">Ver registros</label>
                                <select name="per_page" id="" class="form-select">
                                    <option value="">-- Selecciona --</option>
                                    <option value="5000" {{ request('per_page') == '5000' ? 'selected' : '' }}>Todos</option>
                                    <option value="25"  {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                                    <option value="50"  {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                                    <option value="75"  {{ request('per_page') == '75' ? 'selected' : '' }}>75</option>
                                    <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="ciclo_venta" class="form-label">Ciclo de venta</label>
                                <select name="ciclo_venta" id="" class="form-select">
                                    <option value=""            {{ request('ciclo_venta') == '' ? 'selected' : '' }}>-- Selecciona --</option>
                                    <option value="cotizacion"  {{ request('ciclo_venta') == 'cotizacion' ? 'selected' : '' }}>Cotización</option>
                                    <option value="venta"       {{ request('ciclo_venta') == 'venta' ? 'selected' : '' }}>Venta</option>
                                </select>
                            </div>
                        </div>

                        {{-- Botones alineados a la derecha --}}
                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <a href="{{ route('clientes.transfer') }}" class="btn btn-secondary">
                                <i class="fa fa-eraser me-1"></i> Limpiar
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-search me-1"></i> Buscar
                            </button>
                        </div>

                    </div>
                </div>
            </form>
        </div>

        {{-- ───────── Paginación ───────── --}}
        <div class="row align-items-center mb-3">
            {{-- Texto de totales --}}
            <div class="col-sm">
                @if(isset($clientes))
                    <p class="mb-0 text-muted small">
                        Mostrando <strong>{{ $clientes->firstItem() ?? "Todos" }}</strong> a <strong>{{ $clientes->lastItem() }}</strong>
                        | Total: <strong>{{ $clientes->total() ?? "Todos" }}</strong> clientes de {{ request('lado') }}
                    </p>
                @else
                    <p class="mb-0 text-muted small">
                        No hay clientes encontrados
                    </p>
                @endif
            </div>

            @if(isset($clientes))
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
            @endif
        </div>
        {{-- ────────── Fin paginación ────────── --}}

        {{-- 📋 Tabla responsiva --}}
        <div class="table-responsive mb-3 shadow-lg">
            <form id="formTraspaso" method="POST" action="{{ route('clientes.transfer.store') }}">
            @csrf
            <input type="hidden" name="origen" value="{{ request('id_vendedor_origen') }}">
            <input type="hidden" name="destino" value="{{ request('id_vendedor_destino') }}">
                
                <table id="tabla-transfer" class="table align-middle table-hover table-nowrap
                                                table-striped table-bordered">
                    <thead class="text-center align-middle">
                        <tr>
                            <th class="py-1 px-2 div-5ch"                    style="background-color: var( --tabla-header-bg);"><input type="checkbox" id="selectAll"></th>
                            <th class="py-1 px-2 div-10ch filtro-asc-desc"   style="background-color: var( --tabla-header-bg);">ID Cliente</th>
                            <th class="py-1 px-2 div-30ch filtro-asc-desc"   style="background-color: var( --tabla-header-bg);">Empresa</th>
                            <th class="py-1 px-2 div-25ch filtro-asc-desc"   style="background-color: var( --tabla-header-bg);">Contacto</th>
                            <th class="py-1 px-2 div-15ch"                   style="background-color: var( --tabla-header-bg);">Teléfono</th>
                            <th class="py-1 px-2 div-25ch"                   style="background-color: var( --tabla-header-bg);">Correo</th>
                            <th class="py-1 px-2 div-10ch filtro-asc-desc"   style="background-color: var( --tabla-header-bg);">Sector</th>
                            <th class="py-1 px-2 div-20ch filtro-asc-desc"   style="background-color: var( --tabla-header-bg);">Segmento</th>
                            <th class="py-1 px-2 div-10ch filtro-asc-desc"   style="background-color: var( --tabla-header-bg);">Ciclo</th>
                            <th class="py-1 px-2 div-20ch filtro-asc-desc"   style="background-color: var( --tabla-header-bg);">Asignado a</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if($clientes != null)
                            @foreach ($clientes as $c)
                                @php
                                    $pc    = $c->primerContacto;
                                    $tel   = optional($pc)->telefono1;
                                    $email = optional($pc)->email;
                                    $username = $c->vendedor->username ?? '';
                                @endphp

                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" class="cliente-checkbox" name="clientes[]" value="{{ $c->id_cliente }}">
                                    </td>
                                    
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
                                    <td class="py-1 px-2 text-truncate"
                                        title="{{ optional($pc)->nombre_completo }}">
                                        @if(optional($pc)->nombre_completo)
                                            {{ Str::limit($pc->nombre_completo, 25) }}
                                        @else
                                        —
                                        @endif
                                    </td>

                                    {{-- Teléfono (sin truncar) --}}
                                    <td class="py-1 px-2 text-center">
                                        @if($tel)
                                        <a class="phone-field" href="tel:{{ $tel }}">{{ $tel }}</a>
                                        @else
                                        —
                                        @endif
                                    </td>

                                    {{-- Correo --}}
                                    <td class="py-1 px-2 text-truncate" title="{{ $email }}">
                                        @if($email)
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
                                            {{ 'Privado' }} @break
                                        @case('gobierno')
                                            {{ 'Gobierno' }} @break
                                        @case('persona')
                                            {{ 'Persona' }} @break
                                        @default
                                            —
                                        @endswitch
                                            
                                    </td>

                                    {{-- Segmento filtrado --}}
                                    <td class="py-1 px-2 text-center">
                                        @php $seg = mb_strtolower($c->segmento ?? ''); @endphp
                                        @switch($seg)
                                        @case('macasa cuentas especiales')
                                            {{ 'Macasa Cuentas Especiales' }} @break
                                        @case('tekne store ecommerce')
                                            {{ 'Tekne Store E-Commerce' }} @break
                                        @case('la plaza ecommerce')
                                            {{ 'LaPlazaEnLinea E-Commerce' }} @break
                                        @default
                                            —
                                        @endswitch
                                    </td>

                                    {{-- Ciclo Venta --}}
                                    <td class="py-1 px-2 text-center">
                                        <span class="badge"
                                            style="background-color:{{ $c->ciclo_venta==='venta'? 'var(--mc-verde)' : '#FEE028' }};
                                                    color:{{ $c->ciclo_venta==='venta'? '#fff':'#000' }};
                                                    font-size: var(--bs-body-font-size);
                                                    min-width:10ch;">
                                            
                                                @switch($c->ciclo_venta)
                                                    @case('venta')
                                                        {{ 'Venta' }} @break
                                                    @case('cotizacion')
                                                        {{ 'Cotización' }} @break
                                                    @default
                                                        —
                                                @endswitch
                                        </span>
                                    </td>

                                    {{-- Asignado a --}}
                                    <td class="py-1 px-2 text-truncate text-uppercase" title="{{ strtoupper($username) }}">
                                        {{ $username
                                            ? Str::limit(strtoupper($username), 15)
                                            : 'BASE GENERAL'
                                        }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>

                </table>

            </form>
        </div> {{-- /.table-responsive --}}

        {{-- ───────── Paginación ───────── --}}
        <div class="row align-items-center mb-3">
            {{-- Texto de totales --}}
            <div class="col-sm">
                @if(isset($clientes))
                    <p class="mb-0 text-muted small">
                        Mostrando <strong>{{ $clientes->firstItem() ?? "Todos" }}</strong> a <strong>{{ $clientes->lastItem() }}</strong>
                        de <strong>{{ $clientes->total() ?? "Todos" }}</strong> clientes encontrados
                    </p>
                @else
                    <p class="mb-0 text-muted small">
                        No hay clientes encontrados
                    </p>
                @endif
            </div>

            @if(isset($clientes))
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
            @endif
        </div>
        {{-- ────────── Fin paginación ────────── --}}
        
        {{-- Modal de confirmación de traspaso de cuenta --}}
        <div class="modal fade" id="confirmarModal" tabindex="-1" aria-labelledby="confirmarLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmarLabel">Confirmar traspaso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas traspasar las cuentas seleccionadas?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnConfirmarTraspaso">Sí, traspasar</button>
                </div>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            //Script para preparar la petición para traspaso de cuentas
            document.getElementById('selectAll').addEventListener('click', function () {
                document.querySelectorAll('.cliente-checkbox').forEach(cb => cb.checked = this.checked);
            });

            function prepararTraspaso(origen, destino) {
                const seleccionados = document.querySelectorAll('.cliente-checkbox:checked');
                if (seleccionados.length === 0) {
                    alert("Selecciona al menos una cuenta.");
                    return;
                }

                const form = document.getElementById('formTraspaso');
                form.innerHTML = '';

                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);

                form.appendChild(Object.assign(document.createElement('input'), {
                    type: 'hidden',
                    name: 'origen',
                    value: origen
                }));

                form.appendChild(Object.assign(document.createElement('input'), {
                    type: 'hidden',
                    name: 'destino',
                    value: destino
                }));

                seleccionados.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'clientes[]';
                    input.value = cb.value;
                    form.appendChild(input);
                });

                new bootstrap.Modal(document.getElementById('confirmarModal')).show();
            }

            document.getElementById('btnAgregar')?.addEventListener('click', () => {
                prepararTraspaso(
                    '{{ request('id_vendedor_origen') }}',
                    '{{ request('id_vendedor_destino') }}'
                );
            });

            document.getElementById('btnQuitar')?.addEventListener('click', () => {
                prepararTraspaso(
                    '{{ request('id_vendedor_destino') }}',
                    '{{ request('id_vendedor_origen') }}'
                );
            });

            document.getElementById('btnConfirmarTraspaso')?.addEventListener('click', () => {
                document.getElementById('formTraspaso').submit();
            });
        </script>
    @endpush



@endsection