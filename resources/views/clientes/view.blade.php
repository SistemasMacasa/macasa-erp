@extends('layouts.app')
@section('title', 'SIS 3.0 | Listado de Clientes')

@section('content')

<div class="container-fluid">
    {{-- 🧭 Migas de pan --}}
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('inicio') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Mis Cuentas</a></li>
        <li class="breadcrumb-item active">Ver Cuenta</li>
    @endsection

    <h2 class="mb-4">Información de la Cuenta [{{ $cliente->id_cliente }}] - {{ $cliente->nombre }}</h2>

    {{-- 🎛 Botonera --}}
    <div class="d-flex flex-wrap gap-2 mb-4 align-items-center">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">
            <i class="fa fa-arrow-left me-1"></i> Regresar
        </a>

        <button type="submit"
                class="btn btn-success"
                form="formCuenta">
            <i class="fa fa-save me-1"></i> Guardar
        </button>

        <a href="{{ route('clientes.index') }}" class="btn btn-primary">
            <i class="fa fa-list me-1"></i> Mis Cuentas
        </a>

        <a href="{{ route('inicio', ['cliente' => $cliente->id]) }}" class="btn btn-primary">
            <i class="fa fa-file-invoice-dollar me-1"></i> Levantar Cotización
        </a>

        <a href="{{ route('inicio', ['cliente' => $cliente->id]) }}" class="btn btn-primary">
            <i class="fa fa-address-book me-1"></i> Libreta de Contactos
        </a>

        <div class="ms-auto">
            <div class="alert alert-warning mb-0 py-2 px-3 d-inline-block" role="alert" style="white-space: nowrap;">
                <i class="fa fa-exclamation-triangle me-2"></i>
                Esta sección se encuentra en construcción.
            </div>
        </div>
    </div>

    @if($cliente->sector === 'privada' || $cliente->sector === 'gobierno')
        <!-- ╭━━━━━━━━━━━━━━━━━━ Ficha persona Moral (Privada o Gobierno) ━━━━━━━━━━━━━━╮ -->
        <form id="formCuenta" action="{{ route('clientes.store') }}" method="POST" autocomplete="off">
            <div class="form-wrapper" style="margin-right: auto;">

                {{-- ── Tarjeta: Cuenta Empresarial ─────────────────────────── --}}

                @csrf

                <!-- Valores por defecto / lógica de negocio -->
                <input type="hidden" name="ciclo_venta" value="cotizacion">
                <input type="hidden" name="estatus" value="activo">
                <input type="hidden" name="tipo" value="erp"><!-- alta desde ERP -->

                <!-- ───────────────────────────────
                    TARJETA ÚNICA  →  Cuenta Empresarial
                    (contiene también los datos del contacto principal)
                ─────────────────────────────────── -->
                <!-- ╭━━━━━━━━━━ Cuenta Empresarial + Contacto ━━━━━━━━━━╮ -->
                <div class="card shadow-lg mb-4 section-card section-card-cuenta-empresarial">
                        <div class="card-header section-card-header section-card-header--view d-flex align-items-center">
                            <h5 class="mb-0 flex-grow-1">Cuenta&nbsp;Empresarial</h5>

                            
                            @if ($usuario->es_admin)
                                <button type="button" id="btnEditar" class="btn btn-sm btn-primary ms-auto btn-editar-cuenta">
                                    <i class="fa fa-edit me-1"></i> Editar cuenta
                                </button>
                            @endif
                        </div>

                    <div class="card-body">
                        {{-- ── DATOS DE LA EMPRESA ─────────────────────────── --}}
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Nombre de la Empresa <span class="text-danger">*</span></label>
                                <input  id="nombre" name="nombre" type="text"
                                        class="form-control guarda-mayus @error('nombre') is-invalid @enderror"
                                        value="{{ $cliente->nombre }}" required minlength="3" maxlength="45">
                                @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Asignado a: <span class="text-danger">*</span></label>
                                <select name="id_vendedor" class="form-select" required>
                                    <option value="">-- Ejecutivo --</option>
                                    <option value=""
                                            @selected(old('id_vendedor', $cliente->id_vendedor) === '')>
                                        Base General
                                    </option>

                                    @foreach($vendedores as $v)
                                        <option value="{{ $v->id_usuario }}"
                                                @selected(old('id_vendedor', $cliente->id_vendedor) == $v->id_usuario)>
                                            {{ $v->nombre }} {{ $v->apellido_p }} {{ $v->apellido_m }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Sector <span class="text-danger">*</span></label>
                                <select name="sector" class="form-select" required>
                                    <option value="">-- Selecciona --</option>
                                    <option value="privada"   @selected(old('sector', $cliente->sector)=='privada')>Empresa Privada</option>
                                    <option value="gobierno"  @selected(old('sector', $cliente->sector)=='gobierno')>Empresa Gobierno</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Segmento <span class="text-danger">*</span></label>
                                <select name="segmento" class="form-select" required>
                                    <option value="">-- Selecciona --</option>
                                    <option value="macasa cuentas especiales"
                                            @selected(old('segmento', $cliente->segmento)=='macasa cuentas especiales')>
                                        Macasa Cuentas Especiales
                                    </option>
                                    <option value="macasa ecommerce"
                                            @selected(old('segmento', $cliente->segmento)=='macasa ecommerce')>
                                        Macasa E-commerce
                                    </option>
                                    <option value="tekne store ecommerce"
                                            @selected(old('segmento', $cliente->segmento)=='tekne store ecommerce')>
                                        Tekne Store E-commerce
                                    </option>
                                    <option value="la plaza ecommerce"
                                            @selected(old('segmento', $cliente->segmento)=='la plaza ecommerce')>
                                        La Plaza E-commerce
                                    </option>
                                </select>
                            </div>
                        </div>
                        <hr>

                        {{-- ── CONTACTO PRINCIPAL ─────────────────────────── --}}
                        <h6 class="fw-semibold mb-3">Contacto principal</h6>

                        <div class="row g-2 contacto-block" data-index="0">

                            {{-- Nombre(s) / Apellidos --}}
                            <div class="col-sm-4">
                                <label class="form-label">Nombre(s) <span class="text-danger">*</span></label>
                                <input  name="contacto[0][nombre]" value="{{ $cliente->contacto_predet->nombre ?? '' }}" class="form-control guarda-mayus" minlength="2" maxlength="45" required>
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label">Primer Apellido <span class="text-danger">*</span></label>
                                <input  name="contacto[0][apellido_p]" value="{{ $cliente->contacto_predet->apellido_p ?? '' }}" class="form-control guarda-mayus" maxlength="27" required>
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label">Segundo Apellido <span class="text-danger">*</span></label>
                                <input  name="contacto[0][apellido_m]" value="{{ $cliente->contacto_predet->apellido_m ?? '' }}" class="form-control guarda-mayus" maxlength="27" required>
                            </div>

                            {{-- Email / Puesto / Género --}}
                            <div class="col-sm-4">
                                <label class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                                <input name="contacto[0][email]" value="{{ $cliente->contacto_predet->email ?? '' }}" type="email" class="form-control guarda-minus" maxlength="50" required>
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label">Puesto <span class="text-danger">*</span></label>
                                <input name="contacto[0][puesto]" value="{{ $cliente->contacto_predet->puesto ?? '' }}" class="form-control guarda-mayus" maxlength="20" required>
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label">Género <span class="text-danger">*</span></label>
                                @php
                                    $genero = old('contacto.0.genero', $cliente->contacto_predet->genero ?? '');
                                @endphp
                                <select name="contacto[0][genero]" class="form-select" required>
                                    <option value="">-- Selecciona --</option>
                                    <option value="masculino"       @selected($genero == 'masculino')>Masculino</option>
                                    <option value="femenino"        @selected($genero == 'femenino')>Femenino</option>
                                    <option value="no-especificado" @selected($genero == 'no-especificado')>No especificado</option>
                                </select>
                            </div>

                            {{-- ───── Teléfonos / Celulares  (VIEW) ───── --}}
                            @php
                                // helpers
                                $hasTel = fn($i)=> !empty($cliente->contacto_predet->{'telefono'.$i}) ||
                                                !empty($cliente->contacto_predet->{'ext'.$i});
                                $hasCel = fn($i)=> !empty($cliente->contacto_predet->{'celular'.$i});
                            @endphp

                            <div id="telefonos-cel-wrapper--view">
                                <div class="row">

                                    {{-- Teléfonos fijos --}}
                                    <div class="col-md-4 col-sm-6" id="telefonos-col--view">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @continue(!$hasTel($i))
                                            <div class="mb-2 telefono-item">
                                            <label>Teléfono {{ $i }}</label>
                                            <div class="input-group input-group-separated">
                                                <input  type="text" name="contacto[0][telefono{{ $i }}]"
                                                        value="{{ $cliente->contacto_predet->{'telefono'.$i} }}"
                                                        class="form-control phone-field">
                                                <input  type="text" name="contacto[0][ext{{ $i }}]"
                                                        value="{{ $cliente->contacto_predet->{'ext'.$i} }}"
                                                        class="form-control ext-field" maxlength="7">
                                                <button type="button"
                                                        class="btn btn-outline-danger eliminar-item btn-field d-none">X</button>
                                            </div>
                                            </div>
                                        @endfor
                                        {{-- botón + (arranca oculto) --}}
                                        <button type="button"
                                                class="btn btn-outline-primary w-100 agregar-telefono btn-field d-none">
                                            + Añadir teléfono
                                        </button>
                                    </div>

                                    {{-- Celulares --}}
                                    <div class="col-md-4 col-sm-6" id="celulares-col--view">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @continue(!$hasCel($i))
                                            <div class="mb-2 celular-item">
                                            <label>Teléfono Celular {{ $i }}</label>
                                            <div class="input-group input-group-separated">
                                                <input type="text" name="contacto[0][celular{{ $i }}]"
                                                        value="{{ $cliente->contacto_predet->{'celular'.$i} }}"
                                                        class="form-control phone-field">
                                                <button type="button"
                                                        class="btn btn-outline-danger eliminar-item btn-field d-none">X</button>
                                            </div>
                                            </div>
                                        @endfor
                                        <button type="button"
                                                class="btn btn-outline-primary w-100 agregar-celular btn-field d-none">
                                            + Añadir celular
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- /card-body -->

                </div>

                <!-- ─────────────────────────────── Fin tarjeta ─────────────────────────────── -->

            </div>
        </form>

    @elseif($cliente->sector === 'persona')
        <!-- ╭━━━━━━━━━━━━━━━━━━ Ficha persona Fisica (Persona) ━━━━━━━━━━━━━━╮ -->
        <form id="formCuenta" style="max-width: 100%;" action="{{ route('clientes.store') }}" method="POST" autocomplete="off">
            <div class="form-wrapper" style="margin-right: auto;">

                {{-- ── Tarjeta: Cuenta Empresarial ─────────────────────────── --}}

                @csrf

                <!-- Valores por defecto / lógica de negocio -->
                <input type="hidden" name="ciclo_venta" value="cotizacion">
                <input type="hidden" name="estatus" value="activo">
                <input type="hidden" name="tipo" value="erp"><!-- alta desde ERP -->
                <input type="hidden" name="sector" value="persona"><!-- Sector: gobierno, privada, persona -->

                <!-- ╭━━━━━━━━━━ Datos Generales ━━━━━━━━━━╮ -->
                <div class="card shadow-lg mb-4 section-card section-card-cuenta-empresarial contacto-block" style="max-width: 100%;">
                        <div class="card-header section-card-header section-card-header--view text-center">
                            <h5 class="mb-0">Cuenta Personal</h5>
                            
                            <button type="button" id="btnEditar" class="btn btn-sm btn-primary">
                                <i class="fa fa-edit me-1"></i> Editar cuenta
                            </button>
                        </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="nombre" class="form-label">
                                    Nombre(s) <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nombre" id="nombre"
                                    class="form-control guarda-mayus @error('nombre') is-invalid  @enderror" value="{{ old('nombre') }}"
                                    required minlength="3" maxlength="40">
                                @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label for="apellido_p" class="form-label">
                                    Primer Apellido <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="apellido_p" id="apellido_p"
                                    class="form-control guarda-mayus @error('apellido_p') is-invalid  @enderror" value="{{ old('apellido_p') }}"
                                    required minlength="3" maxlength="27">
                                @error('apellido_p')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label for="apellido_m" class="form-label">
                                    Segundo Apellido <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="apellido_m" id="apellido_m"
                                    class="form-control guarda-mayus @error('apellido_m') is-invalid  @enderror" value="{{ old('apellido_m') }}"
                                    required minlength="3" maxlength="27">
                                @error('apellido_m')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Correo Electrónico -->
                            <div class="col-sm-3">
                                <label for="email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                                <input type="email" class="form-control guarda-minus" name="email" maxlength="40" required>
                            </div>

                            <div class="col-md-3">
                                <label for="segmento" class="form-label">Segmento <span class="text-danger">*</span></label>
                                <select name="segmento" id="segmento" class="form-select" required>
                                    <option value="">-- Selecciona --</option>
                                    <option value="macasa cuentas especiales">Macasa Cuentas Especiales</option>
                                    <option value="macasa ecommerce">Macasa Ecommerce</option>
                                    <option value="tekne store ecommerce">Tekne Store ECommerce</option>
                                    <option value="la plaza ecommerce">La Plaza Ecommerce</option>
                                </select>
                                @error('segmento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Género -->
                            <div class="col-sm-3">
                                <label for="genero" class="form-label">Género <span class="text-danger">*</span></label>
                                <select name="genero" id="genero" class="form-select" required>
                                    <option value="">-- Selecciona --</option>
                                    <option value="masculino">Masculino</option>
                                    <option value="femenino">Femenino</option>
                                    <option value="no-especificado">No Especificado</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="nombre" class="form-label">Asignado a: <span
                                        class="text-danger">*</span></label>
                                <select name="id_vendedor" id="id_vendedor" class="form-select" style=""
                                    required>
                                    <option value="">-- Ejecutivo --</option>

                                    {{-- Base General = NULL --}}
                                    <option value="" @selected(old('id_vendedor') === '')>Base General</option>
                                    @foreach ($vendedores as $vendedor)
                                        <option value="{{ $vendedor->id_usuario }}"
                                            @selected(old('id_vendedor') == $vendedor->id_usuario)>
                                            {{ $vendedor->username }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_vendedor')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            
                            {{-- Contacto Principal ─ Teléfonos PERSONAL --}}
                            <div id="telefonos-cel-wrapper">    {{-- ⬅️ NUEVO --}}

                                <div class="row">
                                    {{-- Teléfonos fijos --}}
                                    <div class="col-md-4" id="telefonos-col" style="padding: 0 !important;">
                                        <div class="mb-2 telefono-item">
                                            <label>Teléfono 1 <span class="text-danger">*</span></label>

                                            <div class="input-group input-group-separated">
                                            <input type="text"  name="contacto[0][telefono1]" class="form-control phone-field"  placeholder="Teléfono" required>
                                            <input type="text"  name="contacto[0][ext1]"      class="form-control ext-field"    placeholder="Ext." maxlength="7">
                                            <button type="button" class="btn btn-outline-primary agregar-telefono btn-field" style="padding-left: 10px;">+</button>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Celulares --}}
                                    <div class="col-md-4" id="celulares-col" style="">
                                        <div class="mb-2 celular-item">
                                            <label>Teléfono Celular 1</label>
                                            <div class="input-group input-group-separated">
                                            <input type="text" name="contacto[0][celular1]" class="form-control phone-field" placeholder="Celular">
                                            <button type="button" class="btn btn-outline-primary agregar-celular btn-field">+</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>{{-- /telefonos-cel-wrapper --}}


                        </div>

                    </div>
                </div>

            </div>
        </form>
    @endif

    {{-- Historial de pedidos ---------------------------------------------------}}
    <div class="card shadow-lg" style="margin-right: auto; max-width: 1200px;">
        {{-- Cabecera de la tarjeta --}}
        <div class="card-header fw-bold" style="background-color: rgba(81, 86, 190, 0.1);">Historial de pedidos</div>

        <div class="card-body p-0"> {{-- p-0 = quitamos padding extra --}}
            {{-- contenedor scroll con altura máx (ajusta a tu gusto) --}}
            <div class="table-responsive" style="max-height: 470px; overflow-y: auto;">
                <table id="tblPedidos" class="table table-sm table-striped mb-0">
                    <thead class="table-light position-sticky top-0" style="z-index:1">
                        <tr>
                            <th data-type="date">Fecha <span class="sort-arrow"></span></th>
                            <th data-type="text">ID&nbsp;pedido <span class="sort-arrow"></span></th>
                            <th data-type="text">Razón social <span class="sort-arrow"></span></th>
                            <th data-type="number" class="text-end">Subtotal <span class="sort-arrow"></span></th>
                            <th data-type="number" class="text-end">Margen&nbsp;% <span class="sort-arrow"></span></th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($pedidos as $p)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($p['fecha'])->format('d-m-Y') }}</td>
                                <td>{{ $p['id'] }}</td>
                                <td>{{ $p['razon'] }}</td>
                                <td class="text-end">$ {{ number_format($p['subtotal'], 2) }}</td>
                                <td class="text-end">{{ number_format($p['margen'], 2) }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Sin pedidos registrados…</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    {{-- 📝 Historial de notas --}}
    <div class="card shadow-lg" style="margin-righ: auto; max-width: 1200px;">
        {{-- Cabecera de la tarjeta --}}
        <div class="card-header fw-bold" style="background-color: rgba(81, 86, 190, 0.1);">
            Historial de notas
        </div>
        <div class="card-body">

            {{-- Área scrolleable con historial --}}
            <div class="form-group mb-4">
                <textarea class="form-control" rows="10"  disabled style="resize: none; overflow-y: scroll;">
                    @foreach ($notas as $nota)
                    {{ \Carbon\Carbon::parse($nota->fecha_registro)->format('d-m-Y h:i A') }} - EJECUTIVO: {{ $nota->usuario->nombre_completo ?? '—' }} - ETAPA: {{ strtoupper($nota->etapa) }}

                    {!! $nota->contenido !!}

                    @if ($nota->fecha_reprogramacion)
                    ========
                    Llamada reprogramada para: {{ \Carbon\Carbon::parse($nota->fecha_reprogramacion)->format('d-m-Y') }}
                    @endif

                    ========

                    @endforeach
                </textarea>
            </div>

            {{-- Formulario para anexar nueva nota --}}
            <form action="{{ route('inicio') }}" method="POST">
                @csrf
                <input type="hidden" name="id_cliente" value="{{ $cliente->id_cliente }}">
                <div class="row">
                    <div class="col-md-3">
                        <label>Volver a llamar</label>
                        <input type="date" name="fecha_reprogramacion" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class=" col-md-8 form-group mt-3">
                        <label>Nota:</label>
                        <textarea name="contenido" rows="3" class="form-control" required></textarea>
                    </div>
                    <div class="col-md-4 align-items-end mt-3">
                        <button type="submit" class="btn btn-success">Anexar nota</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    {{-- Modal confirmación de archivado --}}
    <div class="modal fade" id="confirmArchivar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar archivado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                La cuenta se marcará como <strong>inactiva</strong> (archivada).<br>
                ¿Deseas continuar?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="btnConfirmArchivar" class="btn btn-danger">Sí, archivar</button>
            </div>
            </div>
        </div>
    </div>

    <script>
        // Script para confirmar archivado de cuenta
        document.addEventListener('DOMContentLoaded', () => {

            const form        = document.getElementById('formCuenta');
            const selEstatus  = document.getElementById('selectEstatus');
            const modalEl     = document.getElementById('confirmArchivar');
            const modal       = new bootstrap.Modal(modalEl);
            const btnConfirm  = document.getElementById('btnConfirmArchivar');

            if (!form || !selEstatus) return;

            let archivarPendiente = false;   // flag interno

            form.addEventListener('submit', e => {

                // Si el estatus elegido es inactivo y aún no confirmamos → mostrar modal
                if (selEstatus.value === 'inactivo' && !archivarPendiente) {
                    e.preventDefault();      // detiene el submit original
                    modal.show();
                }
            });

            // Cuando confirman en el modal lanzamos el submit real
            btnConfirm.addEventListener('click', () => {
                archivarPendiente = true;    // evita bucle
                modal.hide();
                form.submit();
            });

        });
    </script>



    {{-- Navegador entre Clientes --}}
    <div class="d-flex justify-content-between align-items-center mb-2">

        {{-- Flecha anterior --}}
        @if ($prevId)
            <a href="{{ route('clientes.view', $prevId) }}"
            class="btn btn-outline-secondary btn-sm">
            <i class="fa fa-chevron-left"></i>
            </a>
        @else
            <button class="btn btn-outline-secondary btn-sm" disabled>
                <i class="fa fa-chevron-left"></i>
            </button>
        @endif


        {{-- Flecha siguiente --}}
        @if ($nextId)
            <a href="{{ route('clientes.view', $nextId) }}"
            class="btn btn-outline-secondary btn-sm">
            <i class="fa fa-chevron-right"></i>
            </a>
        @else
            <button class="btn btn-outline-secondary btn-sm" disabled>
                <i class="fa fa-chevron-right"></i>
            </button>
        @endif
    </div>


    <script>
        // Script para ordenar tabla de pedidos
        document.addEventListener('DOMContentLoaded', () => {

            const table  = document.getElementById('tblPedidos');
            const tbody  = table.querySelector('tbody');
            const ths    = table.querySelectorAll('thead th');
            const dirMap = {};

            ths.forEach((th, idx) => {

                th.addEventListener('click', () => {

                    // Alterna dirección
                    dirMap[idx] = dirMap[idx] === 'asc' ? 'desc' : 'asc';

                    // Convertir NodeList filas a array
                    const rows  = Array.from(tbody.querySelectorAll('tr'));
                    const type  = th.dataset.type || 'text';
                    const parse = (txt) => {
                        if (type === 'number') return parseFloat(txt.replace(/[^\d.-]/g, '')) || 0;
                        if (type === 'date')   return new Date(txt.split('-').reverse().join('-')).getTime();
                        return txt.toLowerCase();
                    };

                    rows.sort((a, b) => {
                        const A = parse(a.children[idx].innerText);
                        const B = parse(b.children[idx].innerText);
                        return (A < B ? -1 : A > B ? 1 : 0) * (dirMap[idx] === 'asc' ? 1 : -1);
                    });

                    // Repinta filas ordenadas
                    rows.forEach(r => tbody.appendChild(r));

                    /* ——— Actualiza flechas ——— */
                    ths.forEach(h => h.classList.remove('asc', 'desc'));
                    th.classList.add(dirMap[idx]);
                });
            });
        });
    </script>

    <script>
        //Botón editar clientes/view
        document.addEventListener('DOMContentLoaded', () => {

            const btn  = document.getElementById('btnEditar');
            const form = document.getElementById('formCuenta');
            if (!btn || !form) return;

            /* 1) Al cargar, deja todo des-habilitado */
            form.querySelectorAll('input, select, textarea').forEach(el => {
                if (el.classList.contains('no-editar')) return;
                el.disabled = true;
            });

            let editing = false;

            const habilitar = () => {
                form.querySelectorAll('input, select, textarea').forEach(el => {
                    if (el.classList.contains('no-editar')) return;
                    el.disabled = false;
                });
                btn.classList.replace('btn-primary', 'btn-secondary');
                btn.innerHTML = '<i class="fa fa-unlock me-1"></i> Edición habilitada';
                editing = true;
            };

            const bloquear = () => {
                form.querySelectorAll('input:not([readonly]), select, textarea').forEach(el => {
                    el.disabled = true;
                });
                btn.classList.replace('btn-secondary', 'btn-primary');
                btn.innerHTML = '<i class="fa fa-edit me-1"></i> Editar cuenta';
                editing = false;
            };

            btn.addEventListener('click', () => editing ? bloquear() : habilitar());
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const MAX = 5;
            const telCol = document.getElementById('telefonos-col--view');
            const celCol = document.getElementById('celulares-col--view');
            const addTel = telCol.querySelector('.agregar-telefono');
            const addCel = celCol.querySelector('.agregar-celular');
            const btnEdit = document.querySelector('.btn-editar-cuenta');

            /* ── utilidades ─────────────────────────── */
            const mkTelRow = () => `
            <div class="mb-2 telefono-item">
                <label></label>
                <div class="input-group input-group-separated">
                <input type="text" class="form-control phone-field"  placeholder="Teléfono">
                <input type="text" class="form-control ext-field"   placeholder="Ext." maxlength="7">
                <button type="button" class="btn btn-outline-danger eliminar-item btn-field">X</button>
                </div>
            </div>`;
            const mkCelRow = () => `
            <div class="mb-2 celular-item">
                <label></label>
                <div class="input-group input-group-separated">
                <input type="text" class="form-control phone-field" placeholder="Celular">
                <button type="button" class="btn btn-outline-danger eliminar-item btn-field">X</button>
                </div>
            </div>`;

            const reindex = (tipo) => {
                const items = (tipo==='telefono')
                                ? telCol.querySelectorAll('.telefono-item')
                                : celCol.querySelectorAll('.celular-item');
                items.forEach((item,i)=>{
                    const idx=i+1;
                    if (tipo==='telefono'){
                        const [tel,ext] = item.querySelectorAll('input');
                        tel.name=`contacto[0][telefono${idx}]`;
                        ext.name=`contacto[0][ext${idx}]`;
                        item.querySelector('label').textContent=`Teléfono ${idx}`;
                    }else{
                        item.querySelector('input').name=`contacto[0][celular${idx}]`;
                        item.querySelector('label').textContent=`Teléfono Celular ${idx}`;
                    }
                });
            };

            const removeEmptyRows = () => {
                telCol.querySelectorAll('.telefono-item').forEach(i=>{
                    const [tel,ext]=i.querySelectorAll('input');
                    if(!tel.value.trim() && !ext.value.trim()) i.remove();
                });
                celCol.querySelectorAll('.celular-item').forEach(i=>{
                    if(!i.querySelector('input').value.trim()) i.remove();
                });
                reindex('telefono'); reindex('celular');
            };

            const setEditing = (state) => {
                /* 1) Inputs ───────────────────────────── */
                telCol.querySelectorAll('input').forEach(el => el.disabled = !state);
                celCol.querySelectorAll('input').forEach(el => el.disabled = !state);

                /* 2) Botones de acción (+ / X) ────────── */
                const toggleBtns = (parent, selector) => {
                    parent.querySelectorAll(selector).forEach(btn => {
                        if (state) {
                            btn.classList.remove('d-none');
                            btn.disabled = false;
                            btn.style.display = '';      // resetea display
                        } else {
                            btn.disabled = true;
                            btn.classList.add('d-none');
                        }
                    });
                };
                toggleBtns(telCol, '.eliminar-item, .agregar-telefono');
                toggleBtns(celCol, '.eliminar-item, .agregar-celular');
            };

            /* ── estado inicial (solo-lectura) ── */
            setEditing(false);
            reindex('telefono'); reindex('celular');

            /* ── manejo del botón Editar ───────── */
            let editing=false;
            btnEdit.addEventListener('click', ()=>{
                editing = !editing;
                if(editing){
                    setEditing(true);
                }else{
                    removeEmptyRows();
                    setEditing(false);
                }
            });

            /* ── añadir filas ──────────────────── */
            addTel.addEventListener('click', ()=>{
                if(telCol.querySelectorAll('.telefono-item').length>=MAX) return;
                addTel.insertAdjacentHTML('beforebegin', mkTelRow());
                reindex('telefono');
            });
            addCel.addEventListener('click', ()=>{
                if(celCol.querySelectorAll('.celular-item').length>=MAX) return;
                addCel.insertAdjacentHTML('beforebegin', mkCelRow());
                reindex('celular');
            });

            /* ── eliminar filas ────────────────── */
            document.addEventListener('click', e=>{
                if(!e.target.classList.contains('eliminar-item')) return;
                const item = e.target.closest('.telefono-item, .celular-item');
                const col  = item.closest('#telefonos-col--view') ? 'telefono' : 'celular';
                item.remove();
                reindex(col);
            });
        });
    </script>



</div><!-- Fin contenedor principal -->

@endsection