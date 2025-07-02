@extends('layouts.app')
@section('title', 'SIS 3.0 | Nueva Cotización')

@section('content')
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
                <div class="card shadow mb-4">
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

                    <form id="cotizacionForm" method="POST" action="{{ route('cotizaciones.store') }}">
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

                        <div class="card-body small">
                            <div class="row g-3">
                                <!-- Información de Facturación -->
                                <div class="col-12">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
                                        <div>
                                            <span class="text-muted me-2"><i class="fa fa-calendar-alt me-1"></i> Fecha de facturación:</span>
                                            <span id="fecha-facturacion" class="fw-semibold">
                                                {{ now()->format('d/m/Y') }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="text-muted me-2"><i class="fa fa-file-invoice-dollar me-1"></i> Monto:</span>
                                            <span id="monto-factura" class="fw-semibold text-success">
                                                $0.00
                                            </span>
                                        </div>
                                        <div>
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

                                <div class="col-12">
                                    <hr class="my-2">
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
                                
                                    <hr class="my-2">

                                <div class="col-12">
                                    <textarea name="notas" class="form-control" rows="2" placeholder="Notas adicionales sobre la facturación" style="resize: vertical; min-height: 38px;"></textarea>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>

            </div>

            {{-- Dirección de entrega --}}
            <div class="col-md-6">
                {{-- 🔲 DIRECCIÓN DE ENTREGA --}}
                <div class="card shadow mb-4">
                    <div class="card-header d-flex flex-row flex-nowrap align-items-center py-2 px-3">
                        <strong class="mb-0 me-auto text-subtitulo">Dirección de Entrega</strong>

                        <div class="d-flex flex-row align-items-center gap-2">
                            {{-- Directorio --}}
                            <button  type="button" class="btn btn-primary btn-sm position-relative"
                                    data-bs-toggle="modal" data-bs-target="#modalEntrega"
                                    aria-label="Abrir directorio">
                                <i class="fa fa-address-book"></i>
                            </button>

                            {{-- Alta rápida --}}
                            <button  type="button" class="btn btn-success btn-sm"
                                    data-bs-toggle="modal" data-bs-target="#modalCrearDireccionEntrega"
                                    aria-label="Agregar nueva dirección de entrega">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    
                    <form id="entregaForm" class="">
                            @csrf
                            {{-- Inputs invisibles --}}
                            <input type="hidden" name="id_cliente" value="{{ $cliente->id_cliente }}">
                            <input type="hidden" name="id_direccion_entrega"  id="id_direccion_entrega">
                            <input type="hidden" name="id_contacto_entrega"   id="id_contacto_entrega">

                        <div class="card-body small">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <span class="text-muted">Contacto:</span>
                                    <span id="entrega-contacto" class="fw-semibold">{{ $contacto_entrega->nombre ?? '—' }}</span>
                                </div>
                                <div class="col-12 col-md-6">
                                    <span class="text-muted">Teléfono:</span>
                                    <span id="entrega-telefono" class="fw-semibold">{{ $contacto_entrega->telefono1 ?? '—' }}</span>
                                </div>
                                <div class="col-12 col-md-6">
                                    <span class="text-muted">E-mail:</span>
                                    <span id="entrega-email" class="fw-semibold">{{ $contacto_entrega->email ?? '—' }}</span>
                                </div>
                                <div class="col-12">
                                    <i class="fa fa-map-marker-alt text-danger me-1"></i>
                                    <span class="text-muted">Calle:</span>
                                    <span id="entrega-direccion">{{ $contacto_entrega->direccion_entrega->calle ?? '—' }}</span>
                                </div>
                                <div class="col-12">
                                    <textarea name="notas_entrega_visible" class="form-control" rows="2"
                                            placeholder="Notas de entrega (opcional)"
                                            oninput="document.getElementById('notas_entrega').value=this.value"></textarea>
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
    </div> <!-- End container-fluid -->


    <!-- Modal: Directorio de Razones Sociales + Direccion de Facturación -->
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
    </div>

    <!-- Modal: Crear nueva razón social + dirección de facturación -->
    <div class="modal fade" id="modalCrearDireccionFactura" tabindex="-1" aria-labelledby="modalCrearDireccionFacturaLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header flex-column border-0 bg-white text-center">
                    <h4 class="modal-title w-100 fw-bold text-primary-emphasis">
                        <i class="fa fa-plus me-2 text-primary"></i> Nueva Razón Social y Dirección
                    </h4>
                    <hr class="w-100 my-2 opacity-25">
                    <button type="button" class="btn-close position-absolute end-0 top-0 mt-3 me-3" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>

                <div class="modal-body pt-0">
                    <form id="formNuevaRazonSocialFactura">
                        @csrf
                        <input type="hidden" name="id_cliente" value="{{ $cliente->id_cliente }}">

                        <div class="row g-3">
                            {{-- Razón Social --}}
                            <div class="col-md-6">
                                <label class="form-label">Razón Social *</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>

                            {{-- RFC --}}
                            <div class="col-md-6">
                                <label class="form-label">RFC *</label>
                                <input type="text" name="rfc" class="form-control" required>
                            </div>

                            {{-- Uso CFDI --}}
                            <div class="col-md-4">
                                <label class="form-label">Uso CFDI *</label>
                                <select name="id_uso_cfdi" class="form-select" required>
                                    <option value="" disabled selected>Selecciona uno</option>
                                    @foreach($uso_cfdis as $uso)
                                        <option value="{{ $uso->id_uso_cfdi }}">{{ $uso->clave }} – {{ $uso->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Método de pago CFDI --}}
                            <div class="col-md-4">
                                <label class="form-label">Método de pago CFDI *</label>
                                <select name="id_metodo_pago" class="form-select" required>
                                    <option value="" disabled selected>Selecciona uno</option>
                                    @foreach($metodos->unique('clave') as $metodo)
                                        <option value="{{ $metodo->id_metodo_pago }}">{{ $metodo->clave }} –
                                            {{ $metodo->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Forma de pago --}}
                            <div class="col-md-4">
                                <label class="form-label">Forma de pago *</label>
                                <select name="id_forma_pago" class="form-select" required>
                                    <option value="" disabled selected>Selecciona uno</option>
                                    @foreach($formas->unique('clave') as $forma)
                                        <option value="{{ $forma->id_forma_pago }}">{{ $forma->clave }} – {{ $forma->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Régimen Fiscal --}}
                            <div class="col-md-6">
                                <label class="form-label">Régimen Fiscal *</label>
                                <select name="id_regimen_fiscal" class="form-select" required>
                                    <option value="" disabled selected>Selecciona uno</option>
                                    @foreach($regimenes as $regimen)
                                        <option value="{{ $regimen->id_regimen_fiscal }}">{{ $regimen->clave }} –
                                            {{ $regimen->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Notas --}}
                            <div class="col-md-12">
                                <label class="form-label">Notas</label>
                                <textarea name="notas" class="form-control" rows="2"></textarea>
                            </div>

                            {{-- — Separador — --}}
                            <div class="col-12">
                                <hr>
                            </div>

                            {{-- Calle --}}
                            <div class="col-md-4">
                                <label class="form-label">Calle</label>
                                <input type="text" name="calle" class="form-control">
                            </div>

                            {{-- Num. Ext. --}}
                            <div class="col-md-2">
                                <label class="form-label">Num. Ext.</label>
                                <input type="text" name="num_ext" class="form-control">
                            </div>

                            {{-- Num. Int. --}}
                            <div class="col-md-2">
                                <label class="form-label">Num. Int.</label>
                                <input type="text" name="num_int" class="form-control">
                            </div>

                            {{-- Colonia --}}
                            <div class="col-md-4">
                                <label class="form-label">Colonia</label>
                                <select name="colonia" class="form-select colonia-select">
                                    <option value="">— Selecciona CP primero —</option>
                                </select>
                            </div>

                            {{-- Código Postal --}}
                            <div class="col-md-3">
                                <label class="form-label">CP</label>
                                <input type="text" name="cp" maxlength="5" class="form-control cp-field">
                            </div>

                            {{-- Municipio --}}
                            <div class="col-md-3">
                                <label class="form-label">Municipio</label>
                                <select name="municipio" class="form-select municipio-field">
                                    <option value="">— Selecciona CP primero —</option>
                                </select>

                            </div>

                            {{-- Estado --}}
                            <div class="col-md-3">
                                <label class="form-label">Estado</label>
                                <select name="estado" class="form-select estado-field">
                                    <option value="">— Selecciona CP primero —</option>
                                </select>
                            </div>

                            <!-- País -->
                            <div class="col-md-3">
                                <label class="form-label">País</label>
                                <select name="id_pais" class="form-select pais-field">
                                    @foreach($paises as $pais)
                                        <option value="{{ $pais->id_pais }}" {{ $pais->nombre === 'México' ? 'selected' : '' }}>
                                            {{ $pais->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="mt-4 text-end">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar razón social</button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>


<!-- Modal: Alta rápida Dirección de Entrega -->
<div class="modal fade" id="modalCrearDireccionEntrega" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <form id="formCrearEntrega"
            action="{{ route('cotizaciones.nueva-entrega') }}"
            method="POST">
        @csrf
        <input type="hidden" name="id_cliente" value="{{ $cliente->id_cliente }}">

        <div class="modal-header">
          <h5 class="modal-title">Nueva dirección de entrega</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"
                  aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">

          {{-- ─── CONTACTO ─────────────────────────────────────────────────── --}}
          <h6 class="text-primary mb-2">Contacto</h6>
          <div class="row g-2 mb-3">
            <div class="col-md-4">
              <label class="form-label fw-semibold">Nombre(s) *</label>
              <input name="contacto[nombre]" required maxlength="100"
                     class="form-control">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Apellido paterno *</label>
              <input name="contacto[apellido_p]" required maxlength="100"
                     class="form-control">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Apellido materno</label>
              <input name="contacto[apellido_m]" maxlength="100"
                     class="form-control">
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold">Teléfono</label>
              <input name="contacto[telefono]" maxlength="20"
                     class="form-control">
            </div>
            <div class="col-md-2">
              <label class="form-label fw-semibold">Ext.</label>
              <input name="contacto[ext]" maxlength="10"
                     class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">E-mail</label>
              <input name="contacto[email]" type="email" maxlength="120"
                     class="form-control">
            </div>
          </div>

          <hr>

          {{-- ─── DIRECCIÓN ────────────────────────────────────────────────── --}}
          <h6 class="text-primary mb-2">Dirección</h6>
          <div class="row g-2">
            <div class="col-md-8">
                <label class="form-label fw-semibold">Nombre Dirección</label>
                <input name="direccion[nombre]" maxlength="27"
                       class="form-control"
                       placeholder="Ej. Oficina, Casa, Almacén...">
            </div>
            <div class="col-md-8">
              <label class="form-label fw-semibold">Calle *</label>
              <input name="direccion[calle]" required maxlength="120"
                     class="form-control">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Número Exterior *</label>
              <input name="direccion[num_ext]" required maxlength="15"
                     class="form-control">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Número Interior *</label>
              <input name="direccion[num_int]" required maxlength="15"
                     class="form-control">
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Colonia *</label>
              <input name="direccion[colonia]" required maxlength="120"
                     class="form-control">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold">C.P. *</label>
              <input name="direccion[cp]" required maxlength="10"
                     class="form-control">
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Ciudad *</label>
              <input name="direccion[ciudad]" required maxlength="120"
                     class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Estado *</label>
              <input name="direccion[estado]" required maxlength="120"
                     class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">País *</label>
              <input name="direccion[pais]" required maxlength="120"
                     value="México" class="form-control">
            </div>

            <div class="col-12">
              <label class="form-label fw-semibold">Notas / Referencias</label>
              <textarea name="notas" rows="2"
                        class="form-control"></textarea>
            </div>
          </div>

        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">
            <i class="fa fa-save me-1"></i> Guardar y usar
          </button>
          <button type="button" class="btn btn-outline-secondary"
                  data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>


    @push('scripts')

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const form = document.getElementById('formNuevaRazonSocialFactura');

                form.addEventListener('submit', async e => {
                    e.preventDefault();
                    const datos = new FormData(form);

                    const resp = await fetch('{{ route('ajax.direccion.factura') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'           // ← pide JSON de Laravel
                        },
                        body: datos
                    });

                    if (!resp.ok) {
                        const contentType = resp.headers.get('Content-Type') || '';
                        if (contentType.includes('application/json')) {
                            const err = await resp.json();
                            console.error('Error JSON:', err);
                            alert('❌ ' + (err.error || JSON.stringify(err.errors) || 'Error al guardar'));
                        } else {
                            const text = await resp.text();
                            console.error('Error HTML:', text);
                            alert('❌ Error inesperado. Revisa consola.');
                        }
                        return;
                    }

                    const { razon_social: razon, direccion: dir } = await resp.json();

                    // Cierra modal
                    bootstrap.Modal.getInstance(
                        document.getElementById('modalCrearDireccionFactura')
                    ).hide();

                    // Rellena formulario principal
                    document.querySelector('[name="nombre"]').value = razon.nombre;
                    document.querySelector('[name="rfc"]').value = razon.rfc;
                    document.querySelector('[name="id_uso_cfdi"]').value = razon.id_uso_cfdi;
                    document.querySelector('[name="id_metodo_pago"]').value = razon.id_metodo_pago;
                    document.querySelector('[name="id_forma_pago"]').value = razon.id_forma_pago;
                    document.querySelector('[name="id_regimen_fiscal"]').value = razon.id_regimen_fiscal;

                    document.querySelector('[name="calle"]').value = dir.calle || '';
                    document.querySelector('[name="num_ext"]').value = dir.num_ext || '';
                    document.querySelector('[name="num_int"]').value = dir.num_int || '';
                    document.querySelector('[name="colonia"]').value = dir.colonia || '';
                    document.querySelector('[name="cp"]').value = dir.cp || '';
                    document.querySelector('[name="municipio"]').value = dir.municipio || '';
                    document.querySelector('[name="estado"]').value = dir.estado || '';
                    document.querySelector('[name="pais"]').value = dir.pais || 'MÉXICO';
                    document.querySelector('[name="notas"]').value = dir.notas || '';
                });
            });
        </script>

        <script defer>
            //Autocompletado de direcciones de facturación
            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.getElementById('modalCrearDireccionFactura');

                if (!modal) return;

                const delay = (fn, ms = 400) => {
                    let t; return (...args) => {
                        clearTimeout(t);
                        t = setTimeout(() => fn(...args), ms);
                    };
                };

                function seleccionarPorTexto(select, texto) {
                    if (!select) return;

                    let opt = [...select.options].find(o =>
                        o.text.trim().toLowerCase() === texto.trim().toLowerCase()
                    );

                    if (!opt) opt = [...select.options].find(o => o.value === texto);

                    if (!opt) {
                        opt = new Option(texto, texto, true, true);
                        select.prepend(opt);
                    }

                    select.value = opt.value;
                }

                function limpiar(bloque) {
                    if (!bloque) return;

                    const estado = bloque.querySelector('.estado-field');
                    const municipio = bloque.querySelector('.municipio-field');
                    const colonia = bloque.querySelector('.colonia-select');

                    if (estado) estado.value = '';
                    if (municipio) municipio.value = '';
                    if (colonia) {
                        colonia.innerHTML = '<option value="">— Selecciona CP primero —</option>';
                        colonia.disabled = true;
                    }
                }

                modal.addEventListener('shown.bs.modal', () => {
                    const cpInput = modal.querySelector('.cp-field');
                    if (!cpInput) return;

                    cpInput.addEventListener('input', delay(async () => {
                        const cp = cpInput.value.trim();
                        const bloque = cpInput.closest('.facturacion-block') ?? modal;

                        if (!/^\d{5}$/.test(cp)) {
                            limpiar(bloque);
                            return;
                        }

                        try {
                            const r = await fetch(`/api/cp/${cp}`);
                            if (!r.ok) throw new Error(`No hay datos para el CP ${cp}`);

                            const data = await r.json();

                            seleccionarPorTexto(bloque.querySelector('.estado-field'), data.head.estado);
                            seleccionarPorTexto(bloque.querySelector('.municipio-field'), data.head.municipio);

                            const coloniaSel = bloque.querySelector('.colonia-select');
                            if (coloniaSel) {
                                coloniaSel.innerHTML = '';
                                data.colonias.forEach(c => {
                                    const opt = new Option(`${c.colonia} (${c.tipo})`, c.colonia);
                                    coloniaSel.appendChild(opt);
                                });
                                coloniaSel.disabled = false;
                            }

                        } catch (err) {
                            console.error('❌ Error cargando CP:', err);
                            limpiar(bloque);
                        }
                    }));
                });
            });
        </script>

        <script defer>
            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.getElementById('modalCrearDireccionFactura');
                if (!modal) return;

                // Campos dentro del modal
                const campoPais = modal.querySelector('.pais-field');
                const campoCP = modal.querySelector('.cp-field');
                const coloniaSel = modal.querySelector('.colonia-select');
                const municipioSel = modal.querySelector('.municipio-field');
                const estadoSel = modal.querySelector('.estado-field');

                // ① Aplica “readonly” vía CSS y atributos a municipio/estado
                [municipioSel, estadoSel].forEach(sel => {
                    if (!sel) return;
                    sel.setAttribute('readonly', 'readonly');      // marca semántica
                    sel.setAttribute('tabindex', '-1');            // no reciba focus
                    sel.style.pointerEvents = 'none';               // no clickable
                    sel.style.backgroundColor = '#e9ecef';          // tono disabled
                });

                // ─── Utilidades ────────────────────────────────
                const delay = (fn, ms = 400) => {
                    let t;
                    return (...args) => {
                        clearTimeout(t);
                        t = setTimeout(() => fn(...args), ms);
                    };
                };

                function seleccionarPorTexto(select, texto) {
                    if (!select) return;
                    let opt = [...select.options].find(o =>
                        o.text.trim().toLowerCase() === texto.trim().toLowerCase()
                    );
                    if (!opt) opt = [...select.options].find(o => o.value === texto);
                    if (!opt) {
                        opt = new Option(texto, texto, true, true);
                        select.prepend(opt);
                    }
                    select.value = opt.value;
                }

                function limpiarCampos() {
                    // limpia colonia, mantiene municipio/estado en blanco
                    if (coloniaSel) {
                        coloniaSel.innerHTML = '<option value="">— Selecciona CP primero —</option>';
                        coloniaSel.disabled = true;
                    }
                    municipioSel.value = '';
                    estadoSel.value = '';
                }

                // ─── Lógica país → habilita/deshabilita autocompletado ───────────────
                function togglePorPais() {
                    const nombre = campoPais.options[campoPais.selectedIndex].text.trim().toLowerCase();
                    const esMx = nombre === 'méxico';

                    // CP y Colonia sólo si es México
                    campoCP.disabled = !esMx;
                    coloniaSel.disabled = !esMx;
                    if (!esMx) {
                        coloniaSel.innerHTML = '<option value="">No aplicable fuera de México</option>';
                        limpiarCampos();
                    } else {
                        coloniaSel.innerHTML = '<option value="">— Selecciona CP primero —</option>';
                    }
                }

                campoPais.addEventListener('change', togglePorPais);

                // ─── Al abrir el modal ─────────────────────────────
                modal.addEventListener('shown.bs.modal', () => {
                    togglePorPais();  // estado inicial

                    // Listener CP → fetch
                    campoCP.removeEventListener('input', onCPInput);
                    campoCP.addEventListener('input', delay(onCPInput, 400));
                });

                // ─── Manejador de CP → autocompletado ─────────────
                async function onCPInput() {
                    const cp = campoCP.value.trim();
                    if (!/^\d{5}$/.test(cp)) {
                        limpiarCampos();
                        return;
                    }
                    try {
                        const res = await fetch(`/api/cp/${cp}`);
                        if (!res.ok) throw new Error('Sin datos');
                        const data = await res.json();

                        // llena municipio y estado (readonly)
                        seleccionarPorTexto(estadoSel, data.head.estado);
                        seleccionarPorTexto(municipioSel, data.head.municipio);

                        // llena colonias
                        coloniaSel.innerHTML = '';
                        data.colonias.forEach(c => {
                            const o = new Option(`${c.colonia} (${c.tipo})`, c.colonia);
                            coloniaSel.appendChild(o);
                        });
                        coloniaSel.disabled = false;
                    } catch {
                        limpiarCampos();
                    }
                }
            });
        </script>

        <script>
            // Seleccionar Razón Social para Cotización
            document.addEventListener('DOMContentLoaded', () => {

                const token = document.querySelector('input[name="_token"]').value;

                document.querySelectorAll('.seleccionar-direccion').forEach(btn => {

                    btn.addEventListener('click', async function () {
                        const url      = this.dataset.route;
                        const idRazon  = this.dataset.id;

                        try {
                            const res = await fetch(url, {
                                method  : 'POST',
                                headers : { 'X-CSRF-TOKEN': token }
                            });
                            const json = await res.json();

                            if (!json.success) {
                                alert('Hubo un error al seleccionar');
                                return;
                            }

                            // 1) Quitar marca verde a cualquier fila anterior
                            document.querySelectorAll('#tabla-razones tr.table-success')
                                    .forEach(tr => tr.classList.remove('table-success'));

                            // 2) Marcar la nueva fila
                            document
                            .getElementById(`rs-row-${idRazon}`)
                            .classList.add('table-success');

                            // 3) Feedback
                            actualizarCamposCotizacion(json.razon);

                        } catch (err) {
                            console.error(err);
                            alert('Error al conectar con el servidor');
                        }
                    });

                });

                function actualizarCamposCotizacion(rs) {
                    const dir = rs.direccion_facturacion;

                    /* ============ CARD VISIBLE ============ */
                    const setText = (id, v='') => {
                        const el = document.getElementById(id);
                        if (el) el.textContent = v ?? '';
                    };

                    setText('rs-nombre',  rs.nombre);
                    setText('rs-rfc',     rs.RFC);
                    setText('dir-calle',
                        `${dir.calle ?? ''} #${dir.num_ext ?? ''}${dir.num_int ? ' Int.'+dir.num_int : ''}`);
                    setText('dir-colonia', dir.colonia?.d_asenta);
                    setText('dir-ciudad',  dir.ciudad?.n_mnpio);
                    setText('dir-estado',  dir.estado?.d_estado);
                    setText('dir-cp',      dir.cp);

                    /* ============ INPUTS OCULTOS ============ */
                    const setVal = (id, v='') => {
                        const el = document.getElementById(id);
                        if (el) el.value = v ?? '';
                    };

                    setVal('id_razon_social', rs.id_razon_social);
                    setVal('rfc',             rs.RFC);
                    setVal('calle',           dir.calle);
                    setVal('num_ext',         dir.num_ext);
                    setVal('num_int',         dir.num_int);
                    setVal('cp',              dir.cp);
                    setVal('id_colonia',      dir.id_colonia);
                    setVal('id_ciudad',       dir.id_ciudad);
                    setVal('id_estado',       dir.id_estado);
                }
     
            });
        </script>

        <script>
            // Seleccionar Dirección de Entrega
            /** ----------------------------------------------------
             *  UTIL: formatea la dirección para la vista                                              
             * ---------------------------------------------------*/
            const formatDir = d =>
            `${d.calle || ''} #${d.numero || ''}, ${d.colonia || ''}, `
            + `${d.ciudad || ''}, ${d.estado || ''}, ${d.pais || ''}, CP ${d.cp || ''}`;

            /** ----------------------------------------------------
             *  Pinta la card + actualiza inputs invisibles                                            
             * ---------------------------------------------------*/
            function setDireccionEntrega(data)
            {
                const setVal = (id, v = '')  => { const el = document.getElementById(id); if (el) el.value = v ?? ''; };
                const setTxt = (id, v = '—') => { const el = document.getElementById(id); if (el) el.textContent = v ?? '—'; };

                /* ---------- inputs ocultos ---------- */
                setVal('id_direccion_entrega', data.id_direccion_entrega);
                setVal('id_contacto_entrega',  data.contacto.id_contacto);
                setVal('notas_entrega',        data.notas || '');

                /* ---------- vista en card ----------- */
                setTxt('entrega-contacto',  data.contacto.nombre);
                setTxt('entrega-telefono',  data.contacto.telefono);
                setTxt('entrega-email',     data.contacto.email);
                setTxt('entrega-direccion', formatDir(data.direccion));
            }

            /** ----------------------------------------------------
             *  Directorio ⇒ Seleccionar                                                                
             * ---------------------------------------------------*/
            document.addEventListener('click', async e => {
                const btn = e.target.closest('.seleccionar-entrega');
                if (!btn) return;

                e.preventDefault();
                const url = btn.dataset.url;   // la ruta que devuelve el JSON (GET ó POST)
                const token = document.querySelector('meta[name="csrf-token"]').content;

                try {
                    const resp = await fetch(url, { method: 'POST', headers:{ 'X-CSRF-TOKEN': token }});
                    const json = await resp.json();
                    if (json.success) {
                        setDireccionEntrega(json.entrega);
                        bootstrap.Modal.getInstance(btn.closest('.modal')).hide();
                    } else {
                        alert(json.message || 'Error al obtener la dirección');
                    }
                } catch (err) {
                    console.error(err);
                    alert('Error de red al obtener la dirección');
                }
            });

            /** ----------------------------------------------------
             *  Alta rápida ⇒ Guardar                                                                   
             * ---------------------------------------------------*/
            document.getElementById('formCrearEntrega')
                    .addEventListener('submit', async function (e) {
                e.preventDefault();
                const form  = e.currentTarget;
                const token = document.querySelector('meta[name="csrf-token"]').content;
                const data  = new FormData(form);

                try {
                    const resp = await fetch(form.action, { method:'POST', headers:{ 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }, body:data });
                    const json = await resp.json();
                    if (json.success) {
                        setDireccionEntrega(json.entrega);
                        form.reset();
                        bootstrap.Modal.getInstance(form.closest('.modal')).hide();
                    } else {
                        alert(json.message || 'No se guardó la dirección');
                    }
                } catch (err) {
                    console.error(err);
                    alert('Error al guardar la dirección');
                }
            });
        </script>





    @endpush

@endsection