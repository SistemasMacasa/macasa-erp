@extends('layouts.app')
@section('title', 'SIS 3.0 | Nueva Cotización')

@section('content')

    <!-- SECCION PRINCIPAL -->
    <div class="container-fluid">

        {{-- 🧭 Migas de pan --}}
        @section('breadcrumb')
            <li class="breadcrumb-item"><a href="{{ route('inicio') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cotizaciones.index') }}">Cotizaciones</a></li>
            <li class="breadcrumb-item active">Levantar Cotización - [{{ $cliente->id_cliente }}] {{ $cliente->nombre }}</li>
        @endsection

        <h2 class="mb-3" style="color: inherit;">Levantar cotización - [{{ $cliente->id_cliente }}] {{ $cliente->nombre }}</h2>

        {{-- 🎛 Botonera --}}
        <div class="d-flex flex-wrap gap-2 mb-3">
            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-principal col-xxl-2 col-xl-2 col-lg-3">
                <i class="fa fa-arrow-left me-1"></i> Regresar
            </a>
            <button class="btn btn-success btn-principal col-xxl-2 col-xl-2 col-lg-3">
                <i class="fa fa-save me-1"></i> Guardar
            </button>
            <a href="{{ route('clientes.index') }}" class="btn btn-primary btn-principal col-xxl-2 col-xl-2 col-lg-3">
                <i class="fa fa-building me-1"></i> Mis cuentas
            </a>
            <a href="#" class="btn btn-primary btn-principal col-xxl-2 col-xl-2 col-lg-3">
                <i class="fa fa-user me-1"></i> Ver cuenta
            </a>
        </div>

        {{-- 🧾 Sección: Dirección de Facturación y Entrega --}}
        <div class="row gy-4">
            {{-- Dirección de facturación --}}
            <div class="col-md-6">
                {{-- 🔲 DIRECCIÓN DE FACTURACIÓN --}}
                <div class="card shadow mb-4 h-100">
                    @php
                        $rsCount = $razones_sociales->count();
                    @endphp

                    <div class="card-header d-flex flex-row flex-nowrap align-items-center py-2 px-3">
                        {{-- Título que empuja todo lo demás a la derecha --}}
                        <strong class="mb-0 me-auto text-subtitulo ">Dirección de Facturación</strong>

                        {{-- Contenedor de botones, evita wrap --}}
                        <div class="d-flex flex-row align-items-center gap-2">
                            {{-- Botón Directorio con badge numérico --}}
                            <button
                            id="btn-directorio"
                            type="button"
                            class="btn btn-primary btn-sm position-relative"
                            data-bs-toggle="modal"
                            data-bs-target="#modalFacturacion"
                            aria-label="Abrir directorio ({{ $rsCount }})"
                            {{ $rsCount ? '' : 'disabled' }}
                            >
                            <i class="fa fa-address-book"></i>
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-info text-white"
                                style="
                                font-size: .6rem;
                                line-height: 1;
                                width: 1.25rem;
                                height: 1.25rem;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                "
                            >
                                {{ $rsCount }}
                            </span>
                            </button>

                            {{-- Botón “+” para agregar nueva razón social --}}
                            <button
                            type="button"
                            class="btn btn-success btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#modalCrearDireccionFactura"
                            aria-label="Agregar nueva razón social"
                            >
                            <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>


                    @php
                        // La colección $razones_sociales ya viene eager-loaded con dirección
                        $rsPredet = $razones_sociales->firstWhere('predeterminado', 1);
                    @endphp

                    <form id="cotizacionForm" class="h-100" method="POST" action="{{ route('cotizaciones.store') }}">
                            @csrf
                            {{-- Campos invisibles que se sobreescriben por JS --}}
                            <input type="hidden" name="id_razon_social"  id="id_razon_social">
                            <input type="hidden" name="rfc"              id="rfc">
                            <input type="hidden" name="calle"            id="calle">
                            <input type="hidden" name="num_ext"          id="num_ext">
                            <input type="hidden" name="num_int"          id="num_int">
                            <input type="hidden" name="cp"               id="cp">
                            <input type="hidden" name="id_colonia"       id="id_colonia">
                            <input type="hidden" name="id_ciudad"        id="id_ciudad">
                            <input type="hidden" name="id_estado"        id="id_estado">
                            <input type="hidden" name="id_pais"          id="id_pais">
                            <input type="hidden" name="uso_cfdi"         id="uso_cfdi">
                            <input type="hidden" name="metodo_pago"      id="metodo_pago">
                            <input type="hidden" name="forma_pago"       id="forma_pago">
                            <input type="hidden" name="regimen_fiscal"   id="regimen_fiscal">

                        <div class="card-body h-100">
                            <div class="row g-3">
                                <!-- Información de Facturación -->
                                <div class="col-12">
                                    <div class="row mb-2">
                                        <div class="col-lg-5 d-sm-none d-lg-block">
                                            <span class="text-muted me-2"><i class="fa fa-calendar-alt me-1"></i> Fecha de facturación:</span>
                                            <span id="fecha-facturacion" class="fw-semibold">
                                                {{ now()->format('d/m/Y') }}
                                            </span>
                                        </div>
                                        <div class="col-lg-5 d-sm-block d-lg-none">
                                            <span class="text-muted me-2"><i class="fa fa-calendar-alt me-1"></i> Fecha:</span>
                                            <span id="fecha-facturacion" class="fw-semibold">
                                                {{ now()->format('d/m/Y') }}
                                            </span>
                                        </div>
                                        <div class="col">
                                            <span class="text-muted me-2"><i class="fa fa-file-invoice-dollar me-1"></i> Monto:</span>
                                            <span id="monto-factura" class="fw-semibold text-success">
                                                $0.00
                                            </span>
                                        </div>
                                        <div class="col">
                                            <span class="text-muted me-2"><i class="fa fa-chart-line me-1"></i> Utilidad:</span>
                                            <span id="utilidad-factura" class="fw-semibold text-primary">
                                                $0.00
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <hr class="my-2">
                                </div>

                                <!-- Razón Social -->
                                <div class="col-12">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fa fa-building fa-fw text-primary me-2"></i>
                                        <span class="fw-semibold fs-5" id="rs-nombre">
                                            {{ $rsPredet->nombre ?? '— Sin seleccionar —' }}
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fa fa-id-card fa-fw text-secondary me-2"></i>
                                        <span id="rs-rfc" class="text-muted">{{ $rsPredet->RFC ?? '' }}</span>
                                    </div>
                                </div>

                                <!-- Dirección -->
                                <div class="col-12">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fa fa-map-marker-alt fa-fw text-danger me-2"></i>
                                        <span id="dir-calle" class="me-2">
                                            @isset($rsPredet)
                                                {{ $rsPredet->direccion_facturacion->calle }}
                                                Ext. {{ $rsPredet->direccion_facturacion->num_ext }}
                                                @if($rsPredet->direccion_facturacion->num_int)
                                                    Int. {{ $rsPredet->direccion_facturacion->num_int }}
                                                @endif
                                            @endisset
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fa fa-location-arrow fa-fw text-info me-2"></i>
                                        <span id="dir-colonia" class="me-2">
                                            {{ $rsPredet->direccion_facturacion->colonia->d_asenta ?? '' }}
                                        </span>
                                    </div>
                                    <div class="d-flex flex-wrap align-items-center gap-2">
                                        <i class="fa fa-map fa-fw text-warning"></i>
                                        <span id="dir-ciudad" class="me-1">
                                            {{ $rsPredet->direccion_facturacion->ciudad->n_mnpio ?? '—' }}
                                        </span>
                                        <span id="dir-estado" class="me-1">
                                            {{ $rsPredet->direccion_facturacion->estado->d_estado ?? '—' }}
                                        </span>
                                        <span>
                                            <strong>C.P.</strong>
                                            <span id="dir-cp">{{ $rsPredet->direccion_facturacion->cp ?? '—' }}</span>
                                        </span>
                                        <span id="dir-pais" class="ms-1">
                                            {{ $rsPredet->direccion_facturacion->pais->nombre ?? '—' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <hr class="my-2">
                                </div>

                                <!-- CFDI y Métodos -->
                                <div class="col-12">
                                    <div class="row g-2">
                                        <div class="col-12 col-md-6">
                                            <span class="text-muted">Uso CFDI:</span>
                                            <span id="uso-cfdi" class="fw-semibold">
                                                {{ $rsPredet->uso_cfdi->clave ?? '— Sin seleccionar —' }}
                                                {{ $rsPredet->uso_cfdi->nombre ?? '' }}
                                            </span>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <span class="text-muted">Método de pago:</span>
                                            <span id="metodo-pago" class="fw-semibold">
                                                {{ $rsPredet->metodo_pago->clave ?? '— Sin seleccionar —' }}
                                                {{ $rsPredet->metodo_pago->nombre ?? '' }}
                                            </span>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <span class="text-muted">Forma de pago:</span>
                                            <span id="forma-pago" class="fw-semibold">
                                                {{ $rsPredet->forma_pago->clave ?? '— Sin seleccionar —' }}
                                                {{ $rsPredet->forma_pago->nombre ?? '' }}
                                            </span>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <span class="text-muted">Régimen Fiscal:</span>
                                            <span id="regimen-fiscal" class="fw-semibold">
                                                {{ $rsPredet->regimen_fiscal->clave ?? '— Sin seleccionar —' }}
                                                {{ $rsPredet->regimen_fiscal->nombre ?? '' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <hr class="mt-3">
                                </div>

                               
                            </div>
                        </div>
                    </form>

                </div>

            </div>

            {{-- Dirección de entrega --}}
            <div class="col-md-6">
                {{-- 🔲 DIRECCIÓN DE ENTREGA --}}
                <div class="card shadow mb-4 h-100">
                    <div class="card-header d-flex flex-row flex-nowrap align-items-center py-2 px-3">
                        <strong class="mb-0 me-auto text-subtitulo">Dirección de Entrega</strong>

                        <div class="d-flex flex-row align-items-center gap-2">
                            {{-- Directorio --}}
                            <button  id="btn-directorio-entrega"
                                    type="button"
                                    class="btn btn-primary btn-sm position-relative"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEntrega"
                                    aria-label="Abrir directorio de entregas ({{ $contactos_entrega->count() }})"
                                    {{ $contactos_entrega->isEmpty() ? 'disabled' : '' }}>
                                        <i class="fa fa-address-book"></i>
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-info">
                                                {{ $contactos_entrega->count() }}
                                        </span>
                            </button>

                            {{-- Alta rápida --}}
                            <button  type="button" class="btn btn-success btn-sm"
                                    data-bs-toggle="modal" data-bs-target="#modalCrearDireccionEntrega"
                                    aria-label="Agregar nueva dirección de entrega"
                                    title="Agregar nueva dirección de entrega">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    
                    <form id="entregaForm" class="h-100">
                            @csrf
                            {{-- Inputs invisibles --}}
                            <input type="hidden" name="id_cliente" value="{{ $cliente->id_cliente }}">
                            <input type="hidden" name="id_direccion_entrega"  id="id_direccion_entrega">
                            <input type="hidden" name="id_contacto_entrega"   id="id_contacto_entrega">
                            <input type="hidden" name="entrega[id_direccion_entrega]" id="input-entrega-id_direccion_entrega">
                            <input type="hidden" name="entrega[id_contacto_entrega]"  id="input-entrega-id_contacto_entrega">
                            <input type="hidden" name="entrega[nombre]"              id="input-entrega-nombre">
                            <input type="hidden" name="entrega[contacto]"            id="input-entrega-contacto">
                            <input type="hidden" name="entrega[telefono]"            id="input-entrega-telefono">
                            <input type="hidden" name="entrega[email]"               id="input-entrega-email">
                            <input type="hidden" name="entrega[calle]"               id="input-entrega-calle">
                            <input type="hidden" name="entrega[num_ext]"             id="input-entrega-num_ext">
                            <input type="hidden" name="entrega[num_int]"             id="input-entrega-num_int">
                            <input type="hidden" name="entrega[id_colonia]"          id="input-entrega-colonia">
                            <input type="hidden" name="entrega[ciudad]"              id="input-entrega-ciudad">
                            <input type="hidden" name="entrega[estado]"              id="input-entrega-estado">
                            <input type="hidden" name="entrega[cp]"                  id="input-entrega-cp">
                            <input type="hidden" name="entrega[pais]"                id="input-entrega-pais">
                            <input type="hidden" name="entrega[notas]"               id="input-entrega-notas">
                        <div class="card-body h-100">
                            <div class="row g-3">

                                {{-- DÍAS DE CRÉDITO --}}
                                <div class="col-12">
                                    <div class="row mb-2">
                                        <div class="col col-xxl-4 col-xl-4 col-lg-3">
                                            <i class="fa fa-calendar-alt fa-fw text-muted me-1"></i>
                                            <span class="text-muted me-2">Días de Crédito</span>
                                            <span id="dias-credito" class="fw-semibold"> $0.00</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <hr class="my-2">
                                </div>

                                {{-- ALIAS / NOMBRE DE LA DIRECCIÓN --}}
                                <div class="col-12">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fa fa-warehouse fa-fw text-primary me-2"></i>
                                        <span id="entrega-nombre" class="fw-semibold fs-5">
                                            {{ $contacto_entrega->direccion_entrega->nombre ?? '—' }}
                                        </span>
                                    </div>
                                    <div class="row">

                                        {{-- CONTACTO DE RECEPCIÓN --}}
                                        <div class="col col-xxl-4 col-md-3">
                                            <div class="d-flex align-items-center mb-1">
                                                <i class="fa fa-user fa-fw text-primary me-2"></i>
                                                <span id="entrega-contacto">
                                                    {{ $contacto_entrega->nombreCompleto ?? '—' }} 
                                                </span>
                                            </div>
                                        </div>
    
                                        {{-- TELÉFONO + EXTENSIÓN --}}
                                        <div class="col col-xxl-4 col-md-5">
                                            <div class="d-flex align-items-center mb-1">
                                                <i class="fa fa-phone fa-fw text-primary me-2"></i>
                                                <span id="entrega-telefono">
                                                    {{ $contacto_entrega->telefono1 ?? '—' }}
                                                </span>
                                                @if(!empty($contacto_entrega->ext1))
                                                    <small class="text-muted ms-2">Ext. <span id="entrega-ext"> {{ $contacto_entrega->ext1 ?? '—' }} </span></small>
                                                @endif
                                            </div>
                                        </div>
    
                                        {{-- E-MAIL --}}
                                        <div class="col col-xxl-4 col-md-4">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fa fa-envelope fa-fw text-primary me-2"></i>
                                                <span id="entrega-email">
                                                    {{ $contacto_entrega->email ?? '—' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                </div>


                                <!-- Dirección de Entrega -->
                                <div class="col-12">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fa fa-map-marker-alt fa-fw text-danger me-2"></i>
                                        <span id="entrega-calle" class="me-2">
                                            {{ $contacto_entrega->direccion_entrega->calle ?? '—' }}
                                            Ext. {{ $contacto_entrega->direccion_entrega->num_ext ?? '—' }}
                                            @if(!empty($contacto_entrega->direccion_entrega->num_int))
                                                Int. {{ $contacto_entrega->direccion_entrega->num_int }}
                                            @endif
                                        </span>
                                    </div>

                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fa fa-location-arrow fa-fw text-info me-2"></i>
                                        <span id="entrega-colonia" class="me-2">
                                        {{ $contacto_entrega->direccion_entrega->colonia->d_asenta ?? '—' }}
                                        </span>
                                    </div>

                                    <div class="d-flex flex-wrap align-items-center gap-2">
                                        <i class="fa fa-map fa-fw text-warning"></i>
                                        <span id="entrega-ciudad" class="me-1">
                                            {{ $contacto_entrega->direccion_entrega->ciudad->n_mnpio ?? '—' }}
                                        </span>
                                        <span id="entrega-estado" class="me-1">
                                            {{ $contacto_entrega->direccion_entrega->estado->d_estado ?? '—' }}
                                        </span>
                                        <span>
                                            <strong>C.P.</strong>
                                            <span id="entrega-cp">{{ $contacto_entrega?->direccion_entrega->cp ?? '—' }}</span>
                                        </span>
                                        <span id="entrega-pais" class="ms-1">
                                            {{ optional($contacto_entrega?->direccion_entrega->pais)->nombre ?? '—' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <hr class="my-2">
                                </div>

                                {{-- NOTAS / REFERENCIAS --}}
                                <div class="col-12">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-sticky-note fa-fw text-muted me-2 mt-1"></i>
                                        <div>
                                            <span class="text-muted">Notas / Referencias</span>
                                            <div id="entrega-notas" class="fw-semibold">
                                                {{ $contacto_entrega->direccion_entrega->notas ?? '—' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <hr class="my-2">
                                </div>
                            </div>
                        </div>

                    </form>
                </div>

            </div>
        </div>

        {{-- 📦 Sección: Partidas --}}
        <div class="card shadow mt-4">
            <div class="card-header fw-bold">Agregar partidas</div>
            <div class="card-body">
            </div>
        </div>

    </div> <!-- End SECCION PRINCIPAL -->

    <!-- Modal 1: Directorio de Razones Sociales + Direccion de Facturación -->
    <div class="modal fade" id="modalFacturacion" tabindex="-1" aria-labelledby="modalFacturacionLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content text-center">

                <div class="modal-header flex-column border-0 bg-white">
                    <h4 class="modal-title w-100 fw-bold text-primary-emphasis">
                        <i class="fa fa-address-book me-2 text-primary"></i> Seleccionar Dirección de Facturación
                    </h4>
                    <hr class="w-100 my-2 opacity-25">
                    <button type="button" class="btn-close position-absolute end-0 top-0 mt-3 me-3" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>

                <div class="modal-body pt-0">
                    <p class="text-muted mb-4">Selecciona una dirección registrada para el cliente actual</p>

                    <div class="table-responsive">
                        <table id="tabla-razones" class="table table-hover table-bordered align-middle small text-start">
                            <thead class="table-light">
                                <tr>
                                    <th>Predeterminado</th>
                                    <th>Razón Social</th>
                                    <th>RFC</th>
                                    <th>Calle</th>
                                    <th>Colonia</th>
                                    <th>CP</th>
                                    <th>Municipio</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($razones_sociales as $rs)
                                    <tr id="rs-row-{{ $rs->id_razon_social }}" class="{{ $rs->predeterminado ? 'table-success' : '' }}">
                                        <td>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-success seleccionar-direccion"
                                                data-id="{{ $rs->id_razon_social }}"
                                                data-route="{{ route('razones_sociales.seleccionar', $rs->id_razon_social) }}">
                                                <i class="fa fa-check"></i>
                                            </button>
                                        </td>

                                        <td>{{ $rs->nombre }}</td>
                                        <td>{{ $rs->RFC }}</td>
                                        <td>{{ $rs->direccion_facturacion->calle }} #{{ $rs->direccion_facturacion->num_ext }}</td>
                                        <td>{{ $rs->direccion_facturacion->colonia?->d_asenta }}</td>
                                        <td>{{ $rs->direccion_facturacion?->cp }}</td>
                                        <td>{{ $rs->direccion_facturacion->ciudad?->n_mnpio }}</td>
                                        <td>{{ $rs->direccion_facturacion->estado?->d_estado }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <span>No se encontraron direcciones de facturación</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div><!-- End Modal: Directorio de Razones Sociales + Direccion de Facturación -->

    <!-- Modal 2: Crear nueva razón social + dirección de facturación -->
    <div class="modal fade" id="modalCrearDireccionFactura" tabindex="-1" aria-labelledby="modalCrearDireccionFacturaLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">

        {{-- Header --}}
        <div class="modal-header flex-column border-0 bg-white text-center pb-0">
            <h4 class="modal-title fw-bold text-primary-emphasis">
            <i class="fa fa-plus me-2 text-primary"></i>
            Nueva Razón Social y Dirección
            </h4>
            <button type="button" class="btn-close position-absolute end-0 top-0 mt-3 me-3" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            <hr class="w-100 my-2 opacity-25">
        </div>

        <div class="modal-body pt-0">
            <form id="formNuevaRazonSocialFactura">
            @csrf
            <input type="hidden" name="id_cliente" value="{{ $cliente->id_cliente }}">

            {{-- Razón Social --}}
            <h6 class="text-primary mb-3"><i class="fa fa-id-card-alt me-1"></i>Razón Social</h6>
            <div class="row g-3">
                <div class="col-md-6">
                <label class="form-label fw-semibold">Razón Social *</label>
                <input type="text" name="nombre" class="form-control" required>
                </div>
                <div class="col-md-6">
                <label class="form-label fw-semibold">RFC *</label>
                <input type="text" name="rfc" class="form-control" required>
                </div>
                <div class="col-md-4">
                <label class="form-label fw-semibold">Uso CFDI *</label>
                <select name="id_uso_cfdi" class="form-select" required>
                    <option value="" disabled selected>Selecciona uno</option>
                    @foreach($uso_cfdis as $uso)
                    <option value="{{ $uso->id_uso_cfdi }}">{{ $uso->clave }} – {{ $uso->nombre }}</option>
                    @endforeach
                </select>
                </div>
                <div class="col-md-4">
                <label class="form-label fw-semibold">Método de pago *</label>
                <select name="id_metodo_pago" class="form-select" required>
                    <option value="" disabled selected>Selecciona uno</option>
                    @foreach($metodos->unique('clave') as $metodo)
                    <option value="{{ $metodo->id_metodo_pago }}">{{ $metodo->clave }} – {{ $metodo->nombre }}</option>
                    @endforeach
                </select>
                </div>
                <div class="col-md-4">
                <label class="form-label fw-semibold">Forma de pago *</label>
                <select name="id_forma_pago" class="form-select" required>
                    <option value="" disabled selected>Selecciona uno</option>
                    @foreach($formas->unique('clave') as $forma)
                    <option value="{{ $forma->id_forma_pago }}">{{ $forma->clave }} – {{ $forma->nombre }}</option>
                    @endforeach
                </select>
                </div>
                <div class="col-md-4">
                <label class="form-label fw-semibold">Régimen Fiscal *</label>
                <select name="id_regimen_fiscal" class="form-select" required>
                    <option value="" disabled selected>Selecciona uno</option>
                    @foreach($regimenes as $regimen)
                    <option value="{{ $regimen->id_regimen_fiscal }}">{{ $regimen->clave }} – {{ $regimen->nombre }}</option>
                    @endforeach
                </select>
                </div>
            </div>

            {{-- Separador --}}
            <hr class="my-4">

            {{-- Dirección --}}
            <h6 class="text-primary mb-3"><i class="fa fa-map-marked-alt me-1"></i>Dirección de Facturación</h6>
            <div class="row g-3">
                <div class="col-md-4">
                <label class="form-label fw-semibold">Calle *</label>
                <input type="text" name="calle" class="form-control" required>
                </div>
                <div class="col-md-2">
                <label class="form-label fw-semibold">Num. Ext. *</label>
                <input type="text" name="num_ext" class="form-control" required>
                </div>
                <div class="col-md-2">
                <label class="form-label fw-semibold">Num. Int.</label>
                <input type="text" name="num_int" class="form-control">
                </div>

                <div class="col-md-4">
                <label class="form-label fw-semibold">Colonia *</label>
                <select name="colonia" class="form-select colonia-select" required>
                    <option value="">— Selecciona CP primero —</option>
                </select>
                </div>

                <div class="col-md-3">
                <label class="form-label fw-semibold">C.P. *</label>
                <input type="text" name="cp" maxlength="5" class="form-control cp-field" required>
                </div>



                <div class="col-md-3">
                <label class="form-label fw-semibold">Municipio *</label>
                <select name="municipio" class="form-select municipio-field" required>
                    <option value="">— Selecciona CP primero —</option>
                </select>
                </div>
                <div class="col-md-3">
                <label class="form-label fw-semibold">Estado *</label>
                <select name="estado" class="form-select estado-field" required>
                    <option value="">— Selecciona CP primero —</option>
                </select>
                </div>
                <div class="col-md-3">
                <label class="form-label fw-semibold">País *</label>
                <select name="id_pais" class="form-select pais-field" required>
                    @foreach($paises as $pais)
                    <option value="{{ $pais->id_pais }}" {{ $pais->nombre === 'México' ? 'selected' : '' }}>
                        {{ $pais->nombre }}
                    </option>
                    @endforeach
                </select>
                </div>
                <div class="col-md-12">
                <label class="form-label fw-semibold">Notas / Referencias</label>
                <textarea name="notas" rows="2" class="form-control"></textarea>
                </div>
            </div>

            {{-- Footer --}}
            <div class="mt-4 text-end">
                <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">
                <i class="fa fa-save me-1"></i> Guardar razón social
                </button>
            </div>
            </form>
        </div>

        </div>
    </div>
    </div> <!-- End Modal: Crear nueva razón social + dirección de facturación -->


    <!-- Modal 3: Directorio de contactos + direcciones de entrega -->
    <div class="modal fade" id="modalEntrega" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content text-center">

            <div class="modal-header flex-column border-0 bg-white">
                <h4 class="modal-title w-100 fw-bold text-primary-emphasis">
                <i class="fa fa-address-book me-2 text-primary"></i>
                Seleccionar Dirección de Entrega
                </h4>
                <button type="button" class="btn-close position-absolute end-0 top-0 mt-3 me-3"
                        data-bs-dismiss="modal" aria-label="Cerrar"></button>
                <hr class="w-100 my-2 opacity-25">
            </div>

            <div class="modal-body pt-0">
                <p class="text-muted mb-4">Elige un contacto/dirección registrada para este cliente</p>

                <div class="table-responsive">
                <table id="tabla-entregas"
                        class="table table-hover table-bordered align-middle small text-start">
                    <thead class="table-light">
                    <tr>
                        <th>Predeterminado</th>
                        <th>Alias</th>
                        <th>Contacto</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Calle</th>
                        <th>Colonia</th>
                        <th>CP</th>
                        <th>Municipio</th>
                        <th>Estado</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($contactos_entrega as $c)
                        @php $dir = $c->direccion_entrega @endphp
                        <tr id="entrega-row-{{ $dir->id_direccion }}"
                            class="{{ $c->predeterminado ? 'table-success' : '' }}">
                        <td>
                            <button type="button"
                                    class="btn btn-sm btn-success seleccionar-entrega"
                                    data-id="{{ $dir->id_direccion }}"
                                    data-route="{{ route('cotizaciones.seleccionar-entrega',$dir->id_direccion) }}">
                            <i class="fa fa-check"></i>
                            </button>
                        </td>
                        <td>{{ $dir->nombre }}</td>
                        <td>{{ $c->nombreCompleto }}</td>
                        <td>{{ $c->telefono1 }}</td>
                        <td>{{ $c->email }}</td>
                        <td>{{ $dir->calle }} #{{ $dir->num_ext }}</td>
                        <td>{{ $dir->colonia->d_asenta }}</td>
                        <td>{{ $dir->cp }}</td>
                        <td>{{ $dir->ciudad->n_mnpio }}</td>
                        <td>{{ $dir->estado->d_estado }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                </div>
            </div>

            </div>
        </div>
    </div>



    <!-- Modal 4: Alta rápida Dirección de Entrega -->
    <div class="modal fade" id="modalCrearDireccionEntrega" tabindex="-1" aria-labelledby="modalCrearDireccionEntregaLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                {{-- Header --}}
                <div class="modal-header flex-column border-0 bg-white text-center pb-0">
                    <h4 class="modal-title fw-bold text-primary-emphasis">
                    <i class="fa fa-plus me-2 text-primary"></i>
                    Nueva Dirección de Entrega
                    </h4>
                    <button type="button" class="btn-close position-absolute end-0 top-0 mt-3 me-3"
                            data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    <hr class="w-100 my-2 opacity-25">
                </div>

                <div class="modal-body pt-0">
                    <form id="formCrearEntrega" action="{{ route('cotizaciones.nueva-entrega') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_cliente" value="{{ $cliente->id_cliente }}">

                    {{-- Contacto --}}
                    <h6 class="text-primary mb-3"><i class="fa fa-user me-1"></i>Contacto</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                        <label class="form-label fw-semibold">Nombre(s) *</label>
                        <input name="contacto[nombre]" required maxlength="30" class="form-control">
                        </div>
                        <div class="col-md-4">
                        <label class="form-label fw-semibold">Apellido paterno *</label>
                        <input name="contacto[apellido_p]" required maxlength="27" class="form-control">
                        </div>
                        <div class="col-md-4">
                        <label class="form-label fw-semibold">Apellido materno</label>
                        <input name="contacto[apellido_m]" maxlength="27" class="form-control">
                        </div>
                        <div class="col-md-4">
                        <label class="form-label fw-semibold">Teléfono</label>
                        <input name="contacto[telefono]" maxlength="20" class="form-control phone-field">
                        </div>
                        <div class="col-md-2">
                        <label class="form-label fw-semibold">Ext.</label>
                        <input name="contacto[ext]" maxlength="7" class="form-control">
                        </div>
                        <div class="col-md-6">
                        <label class="form-label fw-semibold">E-mail</label>
                        <input name="contacto[email]" type="email" maxlength="27" class="form-control">
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- Dirección --}}
                    <h6 class="text-primary mb-3"><i class="fa fa-map-marked-alt me-1"></i>Dirección de Entrega</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nombre Dirección</label>
                            <input name="direccion[nombre]" maxlength="27" class="form-control"
                                placeholder="Ej. Oficina, Casa, Almacén…">
                        </div>
                        <div class="col-md-6"></div>

                        {{-- Calle / número --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Calle *</label>
                            <input name="direccion[calle]" required maxlength="30" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Num. Ext. *</label>
                            <input name="direccion[num_ext]" required maxlength="7" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Num. Int.</label>
                            <input name="direccion[num_int]" maxlength="7" class="form-control">
                        </div>

                        {{-- Colonia / CP --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Colonia *</label>
                            <select name="direccion[id_colonia]" class="form-select colonia-select-entrega" required>
                                <option value="">— Selecciona CP primero —</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">C.P. *</label>
                            <input name="direccion[cp]" maxlength="5" required class="form-control cp-field-entrega">
                        </div>

                        {{-- Municipio / Estado / País --}}
                        <div class="col-md-3">
                            <!-- No lo procesa el backend porque busca por id_municipio -->
                            <label class="form-label fw-semibold">Municipio *</label>
                            <select name="direccion[ciudad]" required class="form-select municipio-field-entrega">
                                <option value="">— Selecciona CP primero —</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <!-- No lo procesa el backend porque busca por id_colonia -->
                            <label class="form-label fw-semibold">Estado *</label>
                            <select name="direccion[estado]" required class="form-select estado-field-entrega">
                                <option value="">— Selecciona CP primero —</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">País *</label>
                            <select name="direccion[pais]" required class="form-select pais-field-entrega">
                                <option value="México" selected>México</option>
                                <option value="Estados Unidos">Estados Unidos</option>
                                <option value="Canadá">Canadá</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Notas / Referencias</label>
                            <textarea name="notas" rows="2" class="form-control"></textarea>
                        </div>
                    </div>


                    <div class="mt-4 text-end">
                        <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save me-1"></i> Guardar y usar
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div> <!-- End Modal: Alta rápida Dirección de Entrega -->

@push('scripts')
<!-- =======================================================================
     HELPERS GLOBALES  (window.SIS) 
     ===================================================================== -->
<script defer>
window.SIS = (() => {
  const delay = (fn, ms = 400) => { let t; return (...a)=>{ clearTimeout(t); t = setTimeout(()=>fn(...a), ms); }; };
  const selTxt = (sel, txt) => {
    if (!sel) return;
    let opt = [...sel.options].find(o => o.text.trim().toLowerCase() === txt.trim().toLowerCase())
            || [...sel.options].find(o => o.value === txt);
    if (!opt) { opt = new Option(txt, txt, true, true); sel.prepend(opt); }
    sel.value = opt.value;
  };
  const qs   = s => document.querySelector(s);
  const setV = (id,v='') => { const e=qs('#'+id); if(e) e.value = v ?? ''; };
  const setT = (id,v='—')=> { const e=qs('#'+id); if(e) e.textContent = v ?? '—'; };
  return { delay, selTxt, qs, setV, setT };
})();
</script>

<!-- =======================================================================
     FUNCIÓN insertarFila    (versión robusta + console.debug)
     =======================================================================-->
    <script defer>
        (() => {

        window.insertarFila = function (rs)
        {
            try {

            /* ── 1. Tabla ─────────────────────────────────────────────── */
            const tbody = document.querySelector('#tabla-razones tbody');
            if (!tbody) { console.warn('❗ #tabla-razones tbody no encontrado'); return; }

            /* ── 2. Limpio selección previa ───────────────────────────── */
            tbody.querySelectorAll('tr').forEach(r => r.classList.remove('table-success'));

            /* ── 3. Protejo contra “huecos” ───────────────────────────── */
            const dir    = rs.direccion_facturacion || {};
            const col    = dir.colonia || {};
            const ciudad = dir.ciudad  || {};
            const edo    = dir.estado  || {};

            /* ── 4. Construyo la fila ─────────────────────────────────── */
            const tr = document.createElement('tr');
            tr.id = `rs-row-${rs.id_razon_social}`;
            tr.classList.add('table-success');

            tr.innerHTML = `
                <td>
                <button type="button"
                        class="btn btn-sm btn-success seleccionar-direccion"
                        data-id="${rs.id_razon_social}"
                        data-route="/razones-sociales/${rs.id_razon_social}/seleccionar">
                    <i class="fa fa-check"></i>
                </button>
                </td>
                <td>${rs.nombre                  ?? ''}</td>
                <td>${rs.RFC                     ?? ''}</td>
                <td>${dir.calle      ?? ''} #${dir.num_ext ?? ''}</td>
                <td>${col.d_asenta   ?? ''}</td>
                <td>${dir.cp         ?? ''}</td>
                <td>${ciudad.n_mnpio ?? ''}</td>
                <td>${edo.d_estado   ?? ''}</td>
            `;

            tbody.appendChild(tr);

            } catch (e) {
            console.error('⚡ insertarFila falló:', e, rs);
            /*  — No vuelvo a lanzar la excepción para que el flujo continúe — */
            }
        };

        })();
    </script>



<!-- =======================================================================
     BLOQUE FACTURACIÓN  (autocompletado CP + alta rápida + directorio)
     ===================================================================== -->
    <script defer>
        (() => {
        const { delay, selTxt, setV, setT } = window.SIS;
        const csrf   = document.querySelector('meta[name="csrf-token"]').content;
        const modalF = document.getElementById('modalCrearDireccionFactura');
        const form   = document.getElementById('formNuevaRazonSocialFactura');
        const dirDlg = document.getElementById('modalFacturacion');
        if (!modalF || !form) return;

        // — Autocompletado CP (México) —
        const cpInput = modalF.querySelector('.cp-field');
        const colSel  = modalF.querySelector('.colonia-select');
        const munSel  = modalF.querySelector('.municipio-field');
        const edoSel  = modalF.querySelector('.estado-field');
        const paisSel = modalF.querySelector('.pais-field');

        const resetDir = () => {
            colSel.innerHTML = '<option value="">— Selecciona CP primero —</option>';
            colSel.disabled = true; munSel.value=''; edoSel.value='';
        };
        async function buscarCP(cp) {
            const r = await fetch(`/api/cp/${cp}`);
            if (!r.ok) throw new Error('CP sin datos');
            return r.json();
        }
        function togglePais() {
            const esMx = paisSel.options[paisSel.selectedIndex].text.toLowerCase() === 'méxico';
            cpInput.disabled = colSel.disabled = !esMx;
            if (!esMx) { colSel.innerHTML = '<option>No aplicable fuera de México</option>'; resetDir(); }
        }
        paisSel.addEventListener('change', togglePais);
        cpInput.addEventListener('input', delay(async () => {
            const cp = cpInput.value.trim();
            if (!/^\d{5}$/.test(cp)) { resetDir(); return; }
            try {
            const d = await buscarCP(cp);
            selTxt(edoSel, d.head.estado);
            selTxt(munSel, d.head.municipio);
            colSel.innerHTML = '';
            d.colonias.forEach(c => colSel.add(new Option(`${c.colonia} (${c.tipo})`, c.colonia)));
            colSel.disabled = false;
            } catch {
            resetDir();
            }
        }));
        togglePais();

        // — Alta rápida (guardar) —
        form.addEventListener('submit', async e=>{
        e.preventDefault()
        const res = await fetch('{{ route("ajax.direccion.factura") }}',{
            method:'POST',
            headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'},
            body:new FormData(form)
        })
        if(!res.ok){ alert('Error al guardar'); return }
        const { razon_social: rs, direccion } = await res.json();
        rs.direccion_facturacion = direccion;           
        bootstrap.Modal.getOrCreateInstance(modalF).hide()

        pintarCard(rs)
        insertarFila(rs)
        actualizarBadge()
        limpiarFormulario()
        })

        // — Selección en directorio —
        dirDlg.addEventListener('click', async e => {
            const btn = e.target.closest('.seleccionar-direccion');
            if (!btn) return;
            const { route:url, id:idRs } = btn.dataset;
            const r = await fetch(url, { method:'POST', headers:{'X-CSRF-TOKEN':csrf} });
            const j = await r.json();
            if (!j.success) { alert('Error'); return; }
            document.querySelectorAll('#tabla-razones tr').forEach(tr=>tr.classList.remove('table-success'));
            document.getElementById('rs-row-'+idRs)?.classList.add('table-success');
            pintarCard(j.razon);
            bootstrap.Modal.getOrCreateInstance(dirDlg).hide();
        });

        // — Helpers locales —
        function pintarCard(rs){
            const d = rs.direccion_facturacion;
            setT('rs-nombre', rs.nombre);
            setT('rs-rfc',    rs.RFC);
            setT('dir-calle', `${d.calle} #${d.num_ext}${d.num_int?' Int.'+d.num_int:''}`);
            setT('dir-colonia', d.colonia?.d_asenta);
            setT('dir-ciudad',  d.ciudad?.n_mnpio);
            setT('dir-estado',  d.estado?.d_estado);
            setT('dir-cp',      d.cp);
            setV('id_razon_social', rs.id_razon_social);
            setV('rfc',             rs.RFC);
            setV('calle',           d.calle);
        }
        function limpiarFormulario(){
            form.reset();
            resetDir();
            togglePais();
        }
        function actualizarBadge(){
            const btn = document.getElementById('btn-directorio');
            if (!btn) return;
            btn.removeAttribute('disabled');
            const badge = btn.querySelector('.badge');
            if (badge) {
            const total = document.querySelectorAll('#tabla-razones tbody tr').length;
            badge.textContent = total;
            }
        }
        })();
    </script>

<!-- =======================================================================
     BLOQUE ENTREGA (autocompletado CP + alta rápida + directorio futuro)
     ===================================================================== -->
    <script defer>
        (() => 
        {
            const { delay, selTxt, setV, setT } = window.SIS;
            const csrf   = document.querySelector('meta[name="csrf-token"]').content;
            const modalE = document.getElementById('modalCrearDireccionEntrega');
            const formE  = document.getElementById('formCrearEntrega');
            if (!modalE || !formE) return;

            /* ── 1) Autocompletado CP (México) ─────────────────────────────── */
            const cpInput = modalE.querySelector('.cp-field-entrega');
            const colSel  = modalE.querySelector('.colonia-select-entrega');
            const munSel  = modalE.querySelector('.municipio-field-entrega');
            const edoSel  = modalE.querySelector('.estado-field-entrega');
            const paisSel = modalE.querySelector('.pais-field-entrega');

            function resetDir() {
                colSel.innerHTML = '<option value="">— Selecciona CP primero —</option>';
                colSel.disabled  = true;
                munSel.value = '';
                edoSel.value = '';
            }

            async function buscarCP(cp) {
                const r = await fetch(`/api/cp/${cp}`);
                if (!r.ok) throw new Error('CP sin datos');
                return r.json();
            }

            function togglePais() {
                const esMx = paisSel.options[paisSel.selectedIndex].text.toLowerCase() === 'méxico';
                cpInput.disabled = colSel.disabled = !esMx;
                if (!esMx) {
                colSel.innerHTML = '<option>No aplicable fuera de México</option>';
                resetDir();
                }
            }

            paisSel.addEventListener('change', togglePais);
            cpInput.addEventListener('input', delay(async () => {
                const cp = cpInput.value.trim();
                if (!/^\d{5}$/.test(cp)) { resetDir(); return; }
                try {
                const d = await buscarCP(cp);
                selTxt(edoSel, d.head.estado);
                selTxt(munSel, d.head.municipio);
                colSel.innerHTML = '';
                d.colonias.forEach(c=>{
                    colSel.add(new Option(`${c.colonia} (${c.tipo})`, c.id_colonia));
                });
                colSel.disabled = false;
                } catch {
                resetDir();
                }
            }));
            togglePais();

                /* ── 2) Helpers de entrega ──────────────────────────────────────── */
                function formatCalle(d) {
                    return `${d.calle||''} #${d.num_ext||''}` + (d.num_int ? ' Int. '+d.num_int : '');
                }

                function pintarEntrega(ent) 
                {
                    const d = ent.direccion;

                    /* ------- datos visibles en la card ------- */
                    setT('entrega-nombre',    d.nombre              || '—');
                    setT('entrega-contacto',  ent.contacto.nombre   || '—');
                    setT('entrega-telefono',  ent.contacto.telefono || '—');
                    setT('entrega-ext',       ent.contacto.ext      || '—');   // ← ya existe el id
                    setT('entrega-email',     ent.contacto.email    || '—');

                    setT('entrega-calle',   formatCalle(d));
                    setT('entrega-colonia', d.colonia || '—');
                    setT('entrega-ciudad',  d.ciudad  || '—');
                    setT('entrega-estado',  d.estado  || '—');
                    setT('entrega-cp',      d.cp      || '—');
                    setT('entrega-pais',    d.pais    || '—');
                    setT('entrega-notas',   ent.notas || '—');

                    /* ------- inputs ocultos para la cotización ------- */
                    setV('input-entrega-id_direccion_entrega', ent.id_direccion_entrega);
                    setV('input-entrega-id_contacto_entrega',  ent.contacto.id_contacto);
                    setV('input-entrega-nombre',               d.nombre   || '');
                    setV('input-entrega-contacto',             ent.contacto.nombre || '');
                    setV('input-entrega-telefono',             ent.contacto.telefono || '');
                    setV('input-entrega-ext',                  ent.contacto.ext || '');
                    setV('input-entrega-email',                ent.contacto.email || '');

                    setV('input-entrega-calle',    d.calle   || '');
                    setV('input-entrega-num_ext',  d.num_ext || '');
                    setV('input-entrega-num_int',  d.num_int || '');
                    setV('input-entrega-colonia',  d.colonia || '');
                    setV('input-entrega-ciudad',   d.ciudad  || '');
                    setV('input-entrega-estado',   d.estado  || '');
                    setV('input-entrega-cp',       d.cp      || '');
                    setV('input-entrega-pais',     d.pais    || '');
                    setV('input-entrega-notas',    ent.notas || '');
                }



            /* ── 3) Alta rápida contacto + dirección ───────────────────────── */
            formE.addEventListener('submit', async e => {
                e.preventDefault();
                try {
                const res = await fetch(formE.action, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                    body: new FormData(formE)
                });
                const j = await res.json();
                if (!j.success) {
                    alert(j.message || 'No se guardó la dirección');
                    return;
                }

                // 1) Pinto card y formulario oculto
                pintarEntrega(j.entrega);

                // 2) (Opcional) Directorio futuro
                if (typeof insertarFilaEntrega === 'function') insertarFilaEntrega(j.entrega);
                if (typeof actualizarBadgeEntrega === 'function') actualizarBadgeEntrega();

                // 3) Limpio form y cierro modal
                formE.reset();
                resetDir();
                togglePais();
                bootstrap.Modal.getOrCreateInstance(modalE).hide();

                } catch (err) {
                console.error(err);
                alert('Error de red al guardar la dirección');
                }
            });

            
  /* -----------------------------------------------------------
     1)  Insertar una fila nueva tras alta rápida
  ----------------------------------------------------------- */
  window.insertarFilaEntrega = function(ent){
    const tbody = document.querySelector('#tabla-entregas tbody');
    if(!tbody) return;

    // desmarca todo y arma fila
    tbody.querySelectorAll('tr').forEach(tr=>tr.classList.remove('table-success'));

    const d   = ent.direccion;
    const tel = ent.contacto.telefono||'';
    const row = document.createElement('tr');
    row.id = `entrega-row-${ent.id_direccion_entrega}`;
    row.classList.add('table-success');
    row.innerHTML = `
      <td>
        <button type="button" class="btn btn-sm btn-success seleccionar-entrega"
                data-id="${ent.id_direccion_entrega}"
                data-route="/cotizaciones/seleccionar-entrega/${ent.id_direccion_entrega}">
          <i class="fa fa-check"></i>
        </button>
      </td>
      <td>${d.nombre||''}</td>
      <td>${ent.contacto.nombre}</td>
      <td>${tel}</td>
      <td>${ent.contacto.email||''}</td>
      <td>${d.calle} #${d.num_ext}</td>
      <td>${d.colonia}</td>
      <td>${d.cp}</td>
      <td>${d.ciudad}</td>
      <td>${d.estado}</td>`;
    tbody.appendChild(row);
  };

  /* -----------------------------------------------------------
     2)  Badge numérico del botón directorio
  ----------------------------------------------------------- */
  window.actualizarBadgeEntrega = function(){
    const btn   = document.getElementById('btn-directorio-entrega');
    if(!btn) return;
    btn.removeAttribute('disabled');
    const total = document.querySelectorAll('#tabla-entregas tbody tr').length;
    btn.querySelector('.badge').textContent = total;
  };

  /* -----------------------------------------------------------
     3)  Selección dentro del modal
  ----------------------------------------------------------- */
  document.getElementById('modalEntrega')
          .addEventListener('click', async e=>{
    const btn = e.target.closest('.seleccionar-entrega');
    if(!btn) return;

    const { route:url, id } = btn.dataset;
    const res = await fetch(url,{
      method:'POST',
      headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'}
    });
    const j = await res.json();
    if(!j.success){ alert('Error al seleccionar'); return; }

    // pinta card y actualiza marcado en tabla
    pintarEntrega(j.entrega);
    document.querySelectorAll('#tabla-entregas tr')
            .forEach(tr=>tr.classList.remove('table-success'));
    document.getElementById('entrega-row-'+id)?.classList.add('table-success');

    bootstrap.Modal.getOrCreateInstance(
      document.getElementById('modalEntrega')).hide();
  });

        })();
    </script>


@endpush


@endsection