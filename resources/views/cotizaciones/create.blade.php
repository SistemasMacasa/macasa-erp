@extends('layouts.app')
@section('title', 'SIS 3.0 | Levantar Cotización')

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
            <button id="btnGuardarCotizacion" class="btn btn-success btn-principal col-xxl-2 col-xl-2 col-lg-3">
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

                    <form id="formHiddenFact" class="h-100" method="POST" action="{{ route('cotizaciones.store') }}">
                            @csrf
                            {{-- Campos invisibles que se sobreescriben por JS --}}
                            <input type="hidden" name="id_razon_social"  id="id_razon_social">
                            

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
                                                #{{ $rsPredet->direccion_facturacion->num_ext }}
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
                                    <hr class="m-0">
                                </div>

                                <!-- Al final de la card de facturación -->
                                <div class="col-12">
                                    <label class="text-muted mb-1">
                                        <i class="fas fa-sticky-note me-1 text-primary"></i> Nota para Facturación
                                    </label>
                                    <textarea id="fact-notas" class="form-control form-control-sm" rows="3"
                                              placeholder="Ej. Favor de facturar con PO 123…"
                                              style="resize: none; overflow: auto;"></textarea>
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

                    
                    <form id="formHiddenEntrega" class="h-100">
                            @csrf
                            {{-- Inputs invisibles --}}
                            <input type="hidden" name="id_cliente" value="{{ $cliente->id_cliente }}">
                            <input type="hidden" name="id_contacto_entrega"   id="id_contacto_entrega">

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
                                    <div class="d-flex align-items-center mb-2">
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
                                                <span id="entrega-contacto" title="{{ $contacto_entrega->nombreCompleto ?? '—' }}">
                                                    {{ isset($contacto_entrega->nombreCompleto) ? \Illuminate\Support\Str::limit($contacto_entrega->nombreCompleto, 24, '…') : '—' }}
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
                                            #{{ $contacto_entrega->direccion_entrega->num_ext ?? '—' }}
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

                                {{-- REFERENCIAS --}}
                                <div class="col-12">
                                    <div class="d-flex align-items-start mb-1">
                                        <i class="fas fa-sticky-note fa-fw text-muted me-2 mt-1"></i>
                                        <div>
                                            <span class="text-muted">Referencias</span>
                                            <div id="entrega-notas" class="fw-semibold">
                                                {{ $contacto_entrega->direccion_entrega->notas ?? '—' }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>

                                <div class="col-12">
                                    <hr class="m-0">
                                </div>

                                <div class="col-12">
                                    <label class="text-muted mb-1">
                                        <i class="fas fa-sticky-note me-1 text-primary"></i> Nota para Entrega
                                    </label>
                                    <textarea id="entrega-notas" class="form-control form-control-sm" rows="3"
                                              placeholder="Ej. Favor de facturar con PO 123…"
                                              style="resize: none; overflow: auto;"></textarea>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>

            </div>
        </div>

{{-- 📦 Sección: Partidas --}}
<div class="card shadow-sm mt-4 border-0">
  <div class="card-header border-bottom">
    <strong class="mb-0 me-auto text-subtitulo ">Agregar Partidas</strong>
  </div>

  <div class="card-body">
    <form id="formPartida" class="row g-2">

  {{-- Descripción (100 % móvil / 6-cols ≥md) --}}
  <div class="col-12 col-md-6">
    <label class="form-label">Descripción *</label>
    <textarea name="descripcion"
              class="form-control"
              placeholder="Ej. Servidor Dell…" required
              style="height:80px;resize:none;"></textarea>
  </div>

  {{-- Bloque derecho: SKU + fila de numéricos + botón --}}
  <div class="col-12 col-md-6">

    {{-- SKU (ocupa el 100 % del ancho disponible) --}}
    <label class="form-label">SKU <span class="text-muted">(opcional)</span></label>
    <input name="sku" class="form-control mb-2" placeholder="Código interno">

    {{-- Fila flex: 3 inputs + botón --}}
    <div class="d-flex gap-2">

      <input name="precio"   type="number" min="0" step="0.01" required
             class="form-control" placeholder="Precio *">

      <input name="costo"    type="number" min="0" step="0.01" required
             class="form-control" placeholder="Costo *">

      <input name="cantidad" type="number" min="1" step="1" required
             class="form-control" placeholder="Cant. *">

      {{-- Botón: sin crecer, pegado a la derecha, alineado abajo --}}
      <button type="submit"
              class="btn btn-success flex-shrink-0 align-self-end px-4">
        <i class="fa fa-plus me-1"></i> Agregar
      </button>
    </div>

  </div>

</form>



    {{-- Tabla de partidas --}}
    <div class="table-responsive mt-4">
      <table id="tablaPartidas" class="table table-sm table-bordered align-middle mb-0">
        <thead class="table-light text-center">
          <tr>
            <th>#</th>
            <th>SKU</th>
            <th>Descripción</th>
            <th class="text-end">Precio</th>
            <th class="text-end">Costo</th>
            <th class="text-end">Cantidad</th>
            <th class="text-end">Importe</th>
            <th class="text-center"></th>
          </tr>
        </thead>
        <tbody></tbody>
        <tfoot class="table-light fw-semibold">
          <tr>
            <td colspan="6" class="text-end">Subtotal</td>
            <td id="partidasSubtotal" class="text-end">0.00</td>
            <td></td>
          </tr>
          <tr>
            <td colspan="6" class="text-end">IVA (16 %)</td>
            <td id="partidasIVA" class="text-end">0.00</td>
            <td></td>
          </tr>
          <tr>
            <td colspan="6" class="text-end">Total</td>
            <td id="partidasTotal" class="text-end text-dark fw-bold">0.00</td>
            <td></td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>




    </div> <!-- End SECCION PRINCIPAL -->

    <!-- Modal 1: Directorio de Razones Sociales + Direccion de Facturación -->
    <div class="modal fade" id="modalFacturacion" tabindex="-1" aria-labelledby="modalFacturacionLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border rounded">
            
            <div class="modal-header flex-column border-0">
                <h4 class="modal-title w-100 fw-bold text-primary-emphasis" id="modalFacturacionLabel">
                <i class="fa fa-address-book me-2 text-primary"></i>
                Seleccionar Dirección de Facturación
                </h4>
                <hr class="w-100 my-2 opacity-25">
                <button type="button" class="btn-close position-absolute end-0 top-0 mt-3 me-3"
                        data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            
            <div class="modal-body">        
                <!-- contenedor con borde y padding -->
                <div class="table-responsive border rounded p-3">
                <table id="tabla-razones"
                        class="table table-hover table-bordered align-middle small text-start mb-0">
                        <thead class="table-light">
                    <tr>
                        <th class="text-center select-col"></th>
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
                        <tr id="rs-row-{{ $rs->id_razon_social }}"
                            class="{{ $rs->predeterminado ? 'table-success' : '' }}">
                        <td class="text-center select-col">
                            <button type="button"
                                    class="btn btn-primary btn-sm seleccionar-direccion"
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
    <!-- End Modal: Directorio de Razones Sociales + Direccion de Facturación -->

    <!-- Modal 2: Crear nueva razón social + dirección de facturación -->
    <div class="modal fade" id="modalCrearDireccionFactura" tabindex="-1" aria-labelledby="modalCrearDireccionFacturaLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 rounded-3 shadow">

            <!-- Header -->
            <div class="modal-header border-0 bg-white text-center pb-0">
                <h4 class="modal-title w-100 fw-bold text-primary-emphasis" id="modalCrearDireccionFacturaLabel">
                <i class="fa fa-plus me-2 text-primary"></i>
                Nueva Razón Social y Dirección
                </h4>
                <button type="button" class="btn-close position-absolute end-0 top-0 mt-3 me-3"
                        data-bs-dismiss="modal" aria-label="Cerrar"></button>
                <hr class="w-100 my-2 opacity-25">
            </div>

            <div class="modal-body py-3 px-4">
                <form id="formNuevaRazonSocialFactura">
                @csrf
                <input type="hidden" name="id_cliente" value="{{ $cliente->id_cliente }}">

                <!-- Sección Razón Social -->
                <div class="form-section mb-4">
                    <div class="section-header mb-3">
                    <i class="fa fa-id-card-alt text-primary me-2"></i>
                    <span class="fw-semibold fs-6">Razón Social</span>
                    </div>
                    <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small text-secondary">Razón Social *</label>
                        <input type="text" name="nombre" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-secondary">RFC *</label>
                        <input type="text" name="rfc" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-secondary">Uso CFDI *</label>
                        <select name="id_uso_cfdi" class="form-select form-select-sm" required>
                        <option value="" disabled selected>Selecciona uno</option>
                        @foreach($uso_cfdis as $uso)
                            <option value="{{ $uso->id_uso_cfdi }}">
                            {{ $uso->clave }} – {{ $uso->nombre }}
                            </option>
                        @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-secondary">Método de pago *</label>
                        <select name="id_metodo_pago" class="form-select form-select-sm" required>
                        <option value="" disabled selected>Selecciona uno</option>
                        @foreach($metodos->unique('clave') as $metodo)
                            <option value="{{ $metodo->id_metodo_pago }}">
                            {{ $metodo->clave }} – {{ $metodo->nombre }}
                            </option>
                        @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-secondary">Forma de pago *</label>
                        <select name="id_forma_pago" class="form-select form-select-sm" required>
                        <option value="" disabled selected>Selecciona uno</option>
                        @foreach($formas->unique('clave') as $forma)
                            <option value="{{ $forma->id_forma_pago }}">
                            {{ $forma->clave }} – {{ $forma->nombre }}
                            </option>
                        @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-secondary">Régimen Fiscal *</label>
                        <select name="id_regimen_fiscal" class="form-select form-select-sm" required>
                        <option value="" disabled selected>Selecciona uno</option>
                        @foreach($regimenes as $regimen)
                            <option value="{{ $regimen->id_regimen_fiscal }}">
                            {{ $regimen->clave }} – {{ $regimen->nombre }}
                            </option>
                        @endforeach
                        </select>
                    </div>
                    </div>
                </div>

                <!-- Sección Dirección de Facturación -->
                <div class="form-section mb-4">
                    <div class="section-header mb-3">
                    <i class="fa fa-map-marked-alt text-primary me-2"></i>
                    <span class="fw-semibold fs-6">Dirección de Facturación</span>
                    </div>
                    <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small text-secondary">Calle *</label>
                        <input type="text" name="direccion[calle]" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-secondary">Num. Ext. *</label>
                        <input type="text" name="direccion[num_ext]" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-secondary">Num. Int.</label>
                        <input type="text" name="direccion[num_int]" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-secondary">C.P. *</label>
                        <input type="text"
                            name="direccion[cp]"
                            maxlength="5"
                            class="form-control form-control-sm cp-field-factura"
                            required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-secondary">Colonia *</label>
                        <select name="direccion[id_colonia]" class="form-select form-select-sm colonia-select-factura" required>
                        <option value="">— Selecciona CP primero —</option>
                        </select>
                    </div>

                    <!-- Estado y Municipio son informativos, no se procesan en el backend -->
                    <div class="col-md-3">
                        <label class="form-label small text-secondary">Municipio *</label>
                        <select name="municipio" class="form-select form-select-sm municipio-field-factura" required>
                        <option value="">— Selecciona CP primero —</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-secondary">Estado *</label>
                        <select name="estado" class="form-select form-select-sm estado-field-factura" required>
                        <option value="">— Selecciona CP primero —</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-secondary">País *</label>
                        <select name="direccion[id_pais]" class="form-select form-select-sm pais-field-factura" required>
                        @foreach($paises as $pais)
                            <option value="{{ $pais->id_pais }}"
                            {{ $pais->nombre === 'México' ? 'selected' : '' }}>
                            {{ $pais->nombre }}
                            </option>
                        @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label small text-secondary">Notas / Referencias</label>
                        <textarea name="notas"
                                class="form-control form-control-sm"
                                style="height: 100px; resize: none;"></textarea>
                    </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">
                    Cancelar
                    </button>
                    <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fa fa-save me-1"></i> Guardar
                    </button>
                </div>
                </form>
            </div>

            </div>
        </div>
    </div>
    <!-- End Modal: Crear nueva razón social + dirección de facturación -->

<!-- Modal 3: Directorio de contactos + direcciones de entrega -->
<div class="modal fade" id="modalEntrega" tabindex="-1" aria-labelledby="modalEntregaLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border rounded">

      <div class="modal-header flex-column border-0">
        <h4 class="modal-title w-100 fw-bold text-primary-emphasis" id="modalEntregaLabel">
          <i class="fa fa-address-book me-2 text-primary"></i>
          Seleccionar Dirección de Entrega
        </h4>
        <hr class="w-100 my-2 opacity-25">
        <button type="button" class="btn-close position-absolute end-0 top-0 mt-3 me-3"
                data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <div class="table-responsive border rounded p-3">
          <table id="tabla-entregas"
                 class="table table-hover table-bordered align-middle small text-start mb-0">
            <thead class="table-light">
              <tr>
                <th class="text-center select-col"></th>
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
                <tr id="entrega-row-{{ $c->id_contacto }}" class="{{ $c->predeterminado ? 'table-success' : '' }}">
                  <td class="text-center select-col">
                    <button
                      type="button"
                      class="btn btn-primary btn-sm seleccionar-entrega"
                      data-id="{{ $c->id_contacto }}"
                      data-route="{{ route('contactos.seleccionar', $c->id_contacto) }}">
                      <i class="fa fa-check"></i>
                    </button>
                  </td>
                  <td>{{ $dir->nombre }}</td>
                  <td>{{ $c->nombreCompleto }}</td>
                  <td>{{ $c->telefono1 }}</td>
                  <td>{{ $c->email }}</td>
                  <td>{{ $dir->calle }} #{{ $dir->num_ext }}</td>
                  <td>{{ $dir->colonia->d_asenta ?? '-' }}</td>
                  <td>{{ $dir->cp }}</td>
                  <td>{{ $dir->ciudad->n_mnpio ?? '-' }}</td>
                  <td>{{ $dir->estado->d_estado ?? '-' }}</td>
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
                            <select name="direccion[id_pais]" required class="form-select pais-field-entrega">
                                @foreach($paises as $pais)
                                    <option value="{{ $pais->id_pais }}"
                                    {{ $pais->nombre === 'México' ? 'selected' : '' }}>
                                    {{ $pais->nombre }}
                                    </option>
                                @endforeach
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
    </div> 
    <!-- End Modal: Alta rápida Dirección de Entrega -->

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
        const cpInput = modalF.querySelector('.cp-field-factura');
        const colSel  = modalF.querySelector('.colonia-select-factura');
        const munSel  = modalF.querySelector('.municipio-field-factura');
        const edoSel  = modalF.querySelector('.estado-field-factura');
        const paisSel = modalF.querySelector('.pais-field-factura');

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
            d.colonias.forEach(c => colSel.add(new Option(`${c.colonia} (${c.tipo})`, c.id_colonia)));
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
        if (!res.ok) {
            const txt = await res.text();          // <-- obtenemos cuerpo crudo
            console.error('❌ Guardado falló', res.status, txt);
            alert('Error al guardar (abre la consola para ver detalles)');
            return;
        }
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
        function pintarCard(rs) {
            const d = rs.direccion_facturacion;
            setT('rs-nombre',   rs.nombre);
            setT('rs-rfc',      rs.RFC);
            setT('dir-calle',   `${d.calle} #${d.num_ext}${d.num_int?' Int.'+d.num_int:''}`);
            setT('dir-colonia', d.colonia?.d_asenta);
            setT('dir-ciudad',  d.ciudad?.n_mnpio);
            setT('dir-estado',  d.estado?.d_estado);
            setT('dir-cp',      d.cp);

            // Aquí es donde se llenan los campos ocultos
            setV('id_razon_social', rs.id_razon_social);

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

          // ➊ Invocación inicial con el predeterminado
        document.addEventListener('DOMContentLoaded', () => {
            @if(isset($rsPredet))
            pintarCard(@json($rsPredet));
            @endif
        });
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

                function valStr(v, prop = '') {
                    if (!v)           return '—';               // null / undefined
                    if (typeof v === 'string') return v;        // ya es texto
                    if (prop && v[prop] !== undefined) return v[prop];   // modelo → campo
                    // fallback genérico («nombre» o primera prop encontrada)
                    return v.nombre ?? Object.values(v)[0] ?? '—';
                }

                function getTel(c){
                return c?.telefono  ?? c?.telefono1 ?? '';   // acepta ambos nombres
                }
                function getExt(c){
                return c?.ext       ?? c?.ext1      ?? '';
                }

                function pintarEntrega(ent) 
                {
                    if (!ent.contacto && ent.id_contacto) 
                    {
                        ent = {
                        contacto : ent,
                        direccion: ent.direccion_entrega,   // el atributo en el modelo Contacto
                        };
                        ent.id_direccion_entrega = ent.direccion?.id_direccion;
                    }
                      const d = ent.direccion ?? {};
                      const c = ent.contacto ?? {};

                        /* ► visibles */
                        setT('entrega-nombre',   valStr(d.nombre));
                        let nombreCompleto = [c?.nombre, c?.apellido_p, c?.apellido_m].filter(Boolean).join(' ') || '—';
                        if (nombreCompleto.length > 27) nombreCompleto = nombreCompleto.slice(0, 24) + '…';
                        setT('entrega-contacto', nombreCompleto);
                        setT('entrega-telefono', getTel(c) || '—');
                        setT('entrega-ext',      getExt(c) || '—');
                        setT('entrega-email',    valStr(ent.contacto?.email));

                        setT('entrega-calle',    `${valStr(d.calle)} #${valStr(d.num_ext)}${d.num_int ? ' Int. '+d.num_int : ''}`);
                        setT('entrega-colonia',  valStr(d.colonia, 'd_asenta'));
                        setT('entrega-ciudad',   valStr(d.ciudad,  'n_mnpio'));
                        setT('entrega-estado',   valStr(d.estado,  'd_estado'));
                        setT('entrega-cp',       valStr(d.cp));
                        setT('entrega-pais',     valStr(d.pais,    'nombre'));
                        setT('entrega-notas',    valStr(ent.notas));

                        /* ► ocultos  */
                        setV('id_contacto_entrega', ent.contacto?.id_contacto);


                }

                document.addEventListener('DOMContentLoaded', () => {
                    @if(isset($contacto_entrega))
                        pintarEntrega(@json($contacto_entrega));
                    @endif
                });




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
                        data-id="${ent.contacto.id_contacto}"
                        data-route="/contactos/${ent.contacto.id_contacto}/seleccionar">
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

        /* 3) Selección dentro del modal --------------------------- */
        document
            .getElementById('modalEntrega')
            .addEventListener('click', async e => 
            {

                const btn = e.target.closest('.seleccionar-entrega');
                if (!btn) return;               // no es el botón ✔️

                const url        = btn.dataset.route;
                const idContacto = btn.dataset.id;

                try 
                {
                    const res = await fetch(url, {
                    method : 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
                    });
                    const j = await res.json();
                    if (!j.success) {
                    alert(j.message || 'Error al seleccionar');
                    return;
                    }

                    /* 1️⃣ Pinta la card (y los inputs ocultos) */
                    pintarEntrega(j.entrega);

                    /* 2️⃣ Resalta la fila correcta */
                    document
                    .querySelectorAll('#tabla-entregas tbody tr')
                    .forEach(tr => tr.classList.remove('table-success'));

                    document
                    .getElementById('entrega-row-' + idContacto)
                    ?.classList.add('table-success');

                    /* 3️⃣ Cierra el modal */
                    bootstrap
                    .Modal
                    .getOrCreateInstance(document.getElementById('modalEntrega'))
                    .hide();

                } catch (err) {
                    console.error(err);
                    alert('Error de red');
                }
            });
        })();
    </script>


<!-- =======================================================================
     BLOQUE PARTIDAS (alta rápida + eliminar)
     ===================================================================== -->
<script defer>
(() => {
  /* -----  Config  ----- */
  const LS_KEY   = 'cotizacion_{{ $cliente->id_cliente }}'; // key por cliente
  const TTL_MIN  = 20;                                      // 20 min de vida
  const IVA_RATE = 0.16;                                    // 16 %

  /* -----  utilidades  ----- */
  const nowSec = ()   => Math.floor(Date.now()/1000);
  const fmt    = num  => (+num).toLocaleString('es-MX',{minimumFractionDigits:2});

  /* -----  elementos  ----- */
  const tbody       = document.querySelector('#tablaPartidas tbody');
  const subtotalEl  = document.getElementById('partidasSubtotal');
  const ivaEl       = document.getElementById('partidasIVA');
  const totalEl     = document.getElementById('partidasTotal');
  const formP       = document.getElementById('formPartida');

  /* -----  LS: cargar / guardar  ----- */
  const loadState = () => {
    try {
      const raw = localStorage.getItem(LS_KEY);
      if (!raw) return { ts: nowSec(), partidas: [] };

      const data = JSON.parse(raw);
      if (nowSec() - data.ts > TTL_MIN * 60) {
        localStorage.removeItem(LS_KEY);
        return { ts: nowSec(), partidas: [] };
      }
      return data;
    } catch { return { ts: nowSec(), partidas: [] }; }
  };

  const saveState = st => {
    st.ts = nowSec();                       // renueva TTL
    localStorage.setItem(LS_KEY, JSON.stringify(st));
  };

  /* -----  render  ----- */
  const render = () => {
    tbody.innerHTML = '';
    state.partidas.forEach((p, i) => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${i+1}</td>
        <td>${p.sku || '-'}</td>
        <td>${p.descripcion}</td>
        <td class="text-end">${fmt(p.precio)}</td>
        <td class="text-end">${fmt(p.costo)}</td>
        <td class="text-end">${p.cantidad}</td>
        <td class="text-end">${fmt(p.importe)}</td>
        <td class="text-center">
          <button data-idx="${i}" class="btn btn-xs btn-outline-danger borrar-partida">
            <i class="fa fa-trash"></i>
          </button>
        </td>`;
      tbody.appendChild(tr);
    });

    const sub = state.partidas.reduce((s, p) => s + p.importe, 0);
    const iva = sub * IVA_RATE;
    const tot = sub + iva;

    subtotalEl.textContent = fmt(sub);
    ivaEl.textContent      = fmt(iva);
    totalEl.textContent    = fmt(tot);
  };

  /* -----  estado inicial  ----- */
  const state = loadState();
  render();

  /* -----  agregar partida  ----- */
  formP.addEventListener('submit', e => {
    e.preventDefault();
    const fd = new FormData(formP);

    const partida = {
      sku:         fd.get('sku')?.trim() || '',
      descripcion: fd.get('descripcion').trim(),
      cantidad:    +fd.get('cantidad'),
      precio:      +fd.get('precio'),
      costo:       +fd.get('costo'),
      importe:     +fd.get('cantidad') * +fd.get('precio'),
      score:       (+fd.get('precio') - +fd.get('costo')) * +fd.get('cantidad')
    };

    if (!partida.descripcion) return alert('La descripción es obligatoria');
    if (partida.cantidad <= 0 || partida.precio < 0 || partida.costo < 0)
      return alert('Datos numéricos no válidos');

    state.partidas.push(partida);
    saveState(state);
    render();
    formP.reset();
    formP.descripcion.focus();
  });

  /* -----  borrar partida  ----- */
  tbody.addEventListener('click', e => {
    const btn = e.target.closest('.borrar-partida');
    if (!btn) return;
    state.partidas.splice(+btn.dataset.idx, 1);
    saveState(state);
    render();
  });

  /* -----  exportador para el form grande  ----- */
  window.getPartidasForSubmit = () => state.partidas;
})();
</script>



<!-- =======================================================================
     ENVÍO DE COTIZACIÓN COMPLETA (Submit Global)
     ===================================================================== -->
<script defer>
(() => {

  /* ---- elemento & llave ---- */
  const btnGuardar    = document.getElementById('btnGuardarCotizacion');   // tu botón
  const formFact      = document.getElementById('formHiddenFact');
  const formEntrega   = document.getElementById('formHiddenEntrega');
  const partidas      = window.getPartidasForSubmit;        // función expuesta antes
  const csrf          = document.querySelector('meta[name=csrf-token]').content;
  const clienteID     = {{ $cliente->id_cliente }};         // ya lo tienes en Blade

  /* ---- click ---- */
  btnGuardar.addEventListener('click', async () => {

    if (partidas().length === 0){
      return alert('Agrega al menos una partida antes de guardar.');
    }

    /* 1️⃣  Armamos payload */
    const fd = new FormData();

    // a) datos “estáticos” (clonamos los formularios ocultos)
    [...new FormData(formFact).entries()].forEach(([k,v]) => fd.append(k,v));
    [...new FormData(formEntrega).entries()].forEach(([k,v]) => fd.append(k,v));

    // b) partidas ⇒ enviamos JSON
    fd.append('partidas', JSON.stringify(partidas()));

    /* 2️⃣  POST */
    try
    {
      const res  = await fetch('{{ route("cotizaciones.store") }}', {
        method : 'POST',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept':'application/json' },
        body   : fd
      });

      const j = await res.json();
      if (!j.success){
        throw new Error(j.message || 'Error al guardar');
      }

      /* 3️⃣  éxito */
      localStorage.removeItem(`cotizacion_${clienteID}`);
      alert('¡Cotización guardada!');
      window.location.href = j.redirect_to;     // p.ej. /cotizaciones/123

    }catch(err){
      console.error(err);
      alert('No se pudo guardar la cotización');
    }
  });

})();
</script>



@endpush


@endsection