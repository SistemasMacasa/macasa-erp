@extends('layouts.app')
@php
    $titulo = 'SIS 3.0 | Ver Cuenta ' . '[' . $cliente->id_cliente . ']'. ' - '. $cliente->nombre;
    @endphp
@section('title', $titulo )

{{-- Estilos específicos de esta vista --}}

@section('content')

<div class="container-fluid" >

    {{-- 🏷 Mensajes de estado --}}
    {{-- 🧭 Migas de pan --}}
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('inicio') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Mis Cuentas</a></li>
        <li class="breadcrumb-item active">Ver Cuenta</li>
    @endsection

    <h2 class="mb-3">Información de la Cuenta [{{ $cliente->id_cliente }}] - {{ $cliente->nombre }} {{ $cliente->apellido_p ?? '' }} {{ $cliente->apellido_m ?? '' }}</h2>
    

    {{-- 🎛 Botonera --}}
    <div class="d-flex flex-wrap gap-2 mb-3 align-items-center">
        <a href="{{ url()->previous() }}" class="btn btn-secondary btn-principal">
            <i class="fa fa-arrow-left me-1"></i> Regresar
        </a>


        <button type="submit"
                class="btn btn-secondary btn-principal btnGuardarCuenta"
                disabled
                form="formCuenta"
                style="opacity: 1;">
        <i class="fa fa-save me-1"></i> Guardar
        </button>


        <a href="{{ route('clientes.index') }}" class="btn btn-primary btn-principal">
            <i class="fa fa-list me-1"></i> Mis Cuentas
        </a>

        <a href="{{ route('inicio', ['cliente' => $cliente->id]) }}" class="btn btn-primary btn-principal">
            <i class="fa fa-file-invoice-dollar me-1"></i> Levantar Cotización
        </a>

        <a href="{{ route('inicio', ['cliente' => $cliente->id]) }}" class="btn btn-primary btn-principal">
            <i class="fa fa-address-book me-1"></i> Libreta de Contactos
        </a>
    </div>

    @if($cliente->sector === 'privada' || $cliente->sector === 'gobierno')
        <!-- ╭━━━━━━━━━━━━━━━━━━ Ficha persona Moral (Privada o Gobierno) ━━━━━━━━━━━━━━╮ -->
        <form id="formCuenta" action="{{ route('clientes.update', $cliente->id_cliente) }}" method="POST" autocomplete="off">
            @csrf
            @method('PUT')
            <div class="form-wrapper" style="margin-right: auto;">

                {{-- ── Tarjeta: Cuenta Empresarial ─────────────────────────── --}}

                <!-- ╭━━━━━━━━━━ Cuenta Empresarial + Contacto ━━━━━━━━━━╮ -->
                <div class="card shadow-lg mb-4 section-card section-card-cuenta-empresarial">
                        <div class="card-header card-header--row">
                            <h5 class="mb-0">Cuenta Empresarial</h5>
                            @if($usuario->es_admin)
                                <button type="button" id="btnEditar"
                                        class="btn btn-sm btn-success btn-15ch btn-editar-cuenta"
                                        style="margin-right:5ch;">
                                <i class="fa fa-edit me-1"></i> Editar cuenta
                                </button>

                            @endif
                        </div>


                    <div class="card-body">
                        {{-- ── DATOS DE LA EMPRESA ─────────────────────────── --}}
                        <div class="row gx-3 gy-2 mb-2">
                            <div class="col campo-dato-secundario">
                                <label class="form-label">Estatus</label>
                                <select name="estatus" class="form-select" disabled>
                                    <option value="activo" @selected(old('estatus', $cliente->estatus) === 'activo')>Activo</option>
                                    <option value="inactivo" @selected(old('estatus', $cliente->estatus) === 'inactivo')>Inactivo</option>
                                </select>
                            </div>
                            <div class="col campo-dato-secundario">
                                <label class="form-label">Ciclo de Venta</label>
                                <select name="ciclo_venta" class="form-select" disabled>
                                    <option value="cotizacion" @selected(old('ciclo_venta', $cliente->ciclo_venta) === 'cotizacion')>Cotización</option>
                                    <option value="venta" @selected(old('ciclo_venta', $cliente->ciclo_venta) === 'venta')>Venta</option>
                                </select>
                            </div>

                            <div class="col campo-dato-secundario">
                                <label class="form-label">Asignado a: <span class="text-danger asterisco">*</span></label>
                                <select name="id_vendedor" class="form-select">
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

                            <div class="col campo-dato-secundario">
                                <label class="form-label">Sector <span class="text-danger asterisco">*</span></label>
                                <select name="sector" class="form-select" required>
                                    <option value="">-- Selecciona --</option>
                                    <option value="privada"   @selected(old('sector', $cliente->sector)=='privada')>Empresa Privada</option>
                                    <option value="gobierno"  @selected(old('sector', $cliente->sector)=='gobierno')>Empresa Gobierno</option>
                                </select>
                            </div>

                            <div class="campo-dato-secundario">
                                <label class="form-label">Segmento <span class="text-danger asterisco">*</span></label>
                                <select name="segmento" class="form-select" required>
                                    <option value="">-- Selecciona --</option>
                                    <option value="macasa cuentas especiales"
                                            @selected(old('segmento', $cliente->segmento)=='macasa cuentas especiales')>
                                        Macasa Cuentas Especiales
                                    </option>
                                    <option value="tekne store ecommerce"
                                            @selected(old('segmento', $cliente->segmento)=='tekne store ecommerce')>
                                        Tekne Store E-Commerce
                                    </option>
                                    <option value="la plaza ecommerce"
                                            @selected(old('segmento', $cliente->segmento)=='la plaza ecommerce')>
                                        LaPlazaEnLinea E-Commerce
                                    </option>
                                </select>
                            </div>

                            <div class="col campo-dato-secundario">
                                <label class="form-label">Origen de la Cuenta</label>
                                <select name="tipo" class="form-select" disabled>
                                    <option value="erp" @selected(old('tipo', $cliente->tipo) === 'erp')>SIS</option>
                                    <option value="crm" @selected(old('tipo', $cliente->tipo) === 'ecommerce')>E-Commerce</option>
                                </select>
                            </div>
                        </div>

                                                <hr>



                        <div class="row gx-3 gy-2 mb-3">
                            <div class="col campo-dato-principal">
                                <label class="form-label">Nombre de la Empresa <span class="text-danger asterisco">*</span></label>
                                <input  id="nombre" name="nombre" type="text"
                                        class="form-control guarda-mayus @error('nombre') is-invalid @enderror"
                                        value="{{ $cliente->nombre }}" required minlength="3" maxlength="45">
                                @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>


                        </div>

                        {{-- ── CONTACTO PRINCIPAL ─────────────────────────── --}}
                        <h6 class="fw-semibold mb-3">Contacto principal</h6>

                        <div class="row g-3 contacto-block" data-index="0">
                            {{-- Ojo: El operador ?-> es para navegación segura --}}

                            {{-- Nombre(s) de Contacto --}}
                            <div class="col campo-dato-principal">
                                <label class="form-label">Nombre(s) <span class="text-danger asterisco">*</span></label>
                                <input  name="contacto[0][nombre]"
                                        value="{{ old('contacto.0.nombre', $cliente->contacto_predet?->nombre) }}"
                                        class="form-control guarda-mayus @error('contacto.0.nombre') is-invalid @enderror"
                                        minlength="2"
                                        maxlength="45"
                                        required>
                                @error('contacto.0.nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror

                            </div>
                            {{-- Primer Apellido --}}
                            <div class="col campo-dato-principal">
                                <label class="form-label">Primer Apellido <span class="text-danger asterisco">*</span></label>
                                <input  name="contacto[0][apellido_p]"
                                        value="{{ old('contacto.0.apellido_p', $cliente->contacto_predet?->apellido_p) }}"
                                        class="form-control guarda-mayus"
                                        maxlength="27"
                                        required>
                                @error('contacto.0.apellido_p') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            {{-- Segundo Apellido --}}
                            <div class="col campo-dato-principal">
                                <label class="form-label">Segundo Apellido <span class="text-danger asterisco">*</span></label>
                                <input  name="contacto[0][apellido_m]" 
                                        value="{{ old('contacto.0.apellido_m', $cliente->contacto_predet?->apellido_m) }}" 
                                        class="form-control guarda-mayus" 
                                        maxlength="27" 
                                        required>
                            </div>
                            {{-- Email --}}
                            <div class="col campo-dato-principal">
                                <label class="form-label">Correo Electrónico <span class="text-danger asterisco">*</span></label>
                                <input name="contacto[0][email]" 
                                        value="{{ old('contacto.0.email', $cliente->contacto_predet?->email) }}" 
                                        type="email" 
                                        class="form-control guarda-minus" 
                                        maxlength="50" 
                                        required>
                                @error('contacto.0.email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            {{-- Puesto --}}
                            <div class="col campo-dato-secundario">
                                <label class="form-label">Puesto <span class="text-danger asterisco">*</span></label>
                                <input  name="contacto[0][puesto]" 
                                        value="{{ old('contacto.0.puesto', $cliente->contacto_predet?->puesto) }}" 
                                        class="form-control guarda-mayus" 
                                        maxlength="20" 
                                        required>
                                @error('contacto.0.puesto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col campo-dato-secundario">
                                <label class="form-label">Género <span class="text-danger asterisco">*</span></label>
                                @php
                                    $genero = old('contacto.0.genero', $cliente->contacto_predet?->genero);
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
                                    <div class="col campo-dato-telefono" id="telefonos-col--view" style="padding-right: 0px !important;">

                                        {{-- Teléfono fila 1 --}}
                                          <div class="mb-2 telefono-item">
                                            <label>Teléfono 1</label>
                                            <div class="input-group input-group-separated">
                                            <input  name="contacto[0][telefono1]" 
                                                    class="form-control phone-field" 
                                                    value="{{ old('contacto.0.telefono1', $cliente->contacto_predet?->telefono1) }}" 
                                                    placeholder="Teléfono" 
                                                    style="min-width: 16ch; max-width: 16ch;">
                                            @error('contacto.0.telefono1') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            <input  name="contacto[0][ext1]" 
                                                    class="form-control ext-field div-10ch" 
                                                    value="{{ old('contacto.0.ext1', $cliente->contacto_predet?->ext1) }}" 
                                                    placeholder="Ext." 
                                                    maxlength="7">
                                            @error('contacto.0.ext1') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            <button type="button" class="btn btn-outline-primary agregar-telefono btn-field d-none">+</button>
                                            </div>
                                        </div>

                                        @for ($i = 2; $i <= 5; $i++)
                                            @continue(!$hasTel($i))
                                            <div class="mb-2 telefono-item">
                                                <label>Teléfono {{ $i }}</label>
                                                <div class="input-group input-group-separated">
                                                    <input  type="text" name="contacto[0][telefono{{ $i }}]"
                                                            value="{{ old('contacto.0.telefono'.$i, $cliente->contacto_predet?->{'telefono'.$i}) }}"
                                                            class="form-control phone-field"
                                                            style="min-width: 16ch; max-width: 16ch;">
                                                    @error('contacto.0.telefono'.$i) <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                    <input  type="text" name="contacto[0][ext{{ $i }}]"
                                                            value="{{ old('contacto.0.ext'.$i, $cliente->contacto_predet?->{'ext'.$i}) }}"
                                                            class="form-control ext-field div-10ch" 
                                                            maxlength="7">
                                                    @error('contacto.0.ext'.$i) <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                    <button type="button"
                                                            class="btn btn-outline-danger eliminar-item btn-field d-none">X</button>
                                                </div>
                                            </div>
                                        @endfor
                                        <small id="tel-limit-msg" class="text-danger mt-1 d-none">
                                            Solo puedes agregar hasta 5 teléfonos.
                                        </small>


                                    </div>

                                    {{-- Celulares --}}
                                    <div class="col campo-dato-secundario" id="celulares-col--view" style="padding-right: 0px !important;" >
                                        {{-- Celular fila 1 --}}
                                        <div class="mb-2 celular-item campo-dato-secundario">
                                            <label>Teléfono Celular 1</label>
                                            <div class="input-group input-group-separated">
                                                <input  name="contacto[0][celular1]" 
                                                        type="text"
                                                        placeholder="Celular" 
                                                        value="{{ old('contacto.0.celular1', $cliente->contacto_predet?->celular1) }}" 
                                                        class="form-control phone-field" 
                                                        style="min-width: 16ch; max-width: 16ch;">
                                                @error('contacto.0.celular1') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                <button type="button" class="btn btn-outline-primary agregar-celular btn-field d-none">+</button>
                                            </div>
                                        </div>
                                        @for ($i = 2; $i <= 5; $i++)
                                            @continue(!$hasCel($i))
                                            <div class="mb-2 celular-item">
                                            <label>Teléfono Celular {{ $i }}</label>
                                            <div class="input-group input-group-separated campo-dato-secundario">
                                                <input type="text" name="contacto[0][celular{{ $i }}]"
                                                        value="{{ old('contacto.0.celular'.$i, $cliente->contacto_predet?->{'celular'.$i}) }}"
                                                        class="form-control phone-field"
                                                        style="min-width: 16ch; max-width: 16ch;">
                                                @error('contacto.0.celular'.$i) <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                <button type="button"
                                                        class="btn btn-outline-danger eliminar-item btn-field d-none">X</button>
                                            </div>
                                            </div>
                                        @endfor
                                        <small id="cel-limit-msg" class="text-danger mt-1 d-none">
                                            Solo puedes agregar hasta 5 celulares.
                                        </small>
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
        <form id="formCuenta" action="{{ route('clientes.update', $cliente->id_cliente) }}" method="POST" autocomplete="off">
            @csrf
            @method('PUT')
            <div class="form-wrapper" style="margin-right: auto;">

                {{-- ── Tarjeta: Cuenta Personal ─────────────────────────── --}}

                <!-- Valores por defecto / lógica de negocio -->
                <input type="hidden" name="sector" value="persona">

                <!-- ╭━━━━━━━━━━ Datos Generales ━━━━━━━━━━╮ -->
                <div class="card shadow-lg mb-4 section-card section-card-cuenta-empresarial">
                        <div class="card-header card-header--row">
                            <h5 class="mb-0 flex-grow-1">Cuenta&nbsp;Personal</h5>
                            
                            @if ($usuario->es_admin)
                                <button type="button" id="btnEditar" class="btn btn-sm btn-success ms-auto btn-editar-cuenta" style="margin-right:5ch;">
                                    <i class="fa fa-edit me-1"></i> Editar cuenta
                                </button>
                            @endif
                        </div>
                    <div class="card-body">
                        <div class="row gx-3 gy-2 mb-2">
                            <div class="col campo-dato-secundario">
                                <label class="form-label">Estatus</label>
                                <select name="estatus" class="form-select" disabled>
                                    <option value="activo" @selected(old('estatus', $cliente->estatus) === 'activo')>Activo</option>
                                    <option value="inactivo" @selected(old('estatus', $cliente->estatus) === 'inactivo')>Inactivo</option>
                                </select>
                            </div>
                            <div class="col campo-dato-secundario">
                                <label class="form-label">Ciclo de Venta</label>
                                <select name="ciclo_venta" class="form-select" disabled>
                                    <option value="cotizacion" @selected(old('ciclo_venta', $cliente->ciclo_venta) === 'cotizacion')>Cotización</option>
                                    <option value="venta" @selected(old('ciclo_venta', $cliente->ciclo_venta) === 'venta')>Venta</option>
                                </select>
                            </div>
                            <div class="col campo-dato-secundario">
                                <label class="form-label">Asignado a: <span class="text-danger asterisco">*</span></label>
                                <select name="id_vendedor" class="form-select">
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

                            <div class="col campo-dato-secundario">
                                <label class="form-label">Sector <span class="text-danger">*</span></label>
                                <select name="sector" class="form-select" required>
                                    <option value="">-- Selecciona --</option>
                                    <option value="privada"   @selected(old('sector', $cliente->sector)=='persona')>Persona</option>
                                </select>
                            </div>

                            <div class="campo-dato-secundario">
                                <label class="form-label">Segmento <span class="text-danger asterisco">*</span></label>
                                <select name="segmento" class="form-select" required>
                                    <option value="">-- Selecciona --</option>
                                    <option value="macasa cuentas especiales"
                                            @selected(old('segmento', $cliente->segmento)=='macasa cuentas especiales')>
                                        Macasa Cuentas Especiales
                                    </option>
                                    <option value="tekne store ecommerce"
                                            @selected(old('segmento', $cliente->segmento)=='tekne store ecommerce')>
                                        Tekne Store E-Commerce
                                    </option>
                                    <option value="la plaza ecommerce"
                                            @selected(old('segmento', $cliente->segmento)=='la plaza ecommerce')>
                                        LaPlazaEnLinea E-Commerce
                                    </option>
                                </select>
                            </div>

                            <div class="col campo-dato-secundario">
                                <label class="form-label">Origen de la Cuenta</label>
                                <select name="tipo" class="form-select" disabled>
                                    <option value="erp" @selected(old('tipo', $cliente->tipo) === 'erp')>SIS</option>
                                    <option value="crm" @selected(old('tipo', $cliente->tipo) === 'ecommerce')>E-Commerce</option>
                                </select>
                            </div>

                            
                        </div>
                        <hr>
                        <div class="row gx-3 gy-2 mb-2">
                            <div class="col campo-dato-principal">
                                <label for="nombre" class="form-label">
                                    Nombre(s) <span class="text-danger asterisco">*</span>
                                </label>
                                <input  type="text" 
                                        name="nombre" 
                                        id="nombre"
                                        class="form-control guarda-mayus" 
                                        value="{{ old('nombre', $cliente->contacto_predet?->nombre) }}"
                                        required 
                                        minlength="3" 
                                        maxlength="40">
                                @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col campo-dato-principal">
                                <label for="apellido_p" class="form-label">
                                    Primer Apellido <span class="text-danger asterisco">*</span>
                                </label>
                                <input  type="text" 
                                        name="apellido_p" 
                                        id="apellido_p"
                                        class="form-control guarda-mayus" 
                                        value="{{ old('apellido_p', $cliente->contacto_predet?->apellido_p) }}"
                                        required 
                                        minlength="3" 
                                        maxlength="27">
                                @error('apellido_p')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col campo-dato-principal">
                                <label for="apellido_m" class="form-label">
                                    Segundo Apellido <span class="text-danger">*</span>
                                </label>
                                <input  type="text" 
                                        name="apellido_m" 
                                        id="apellido_m"
                                        class="form-control guarda-mayus" 
                                        value="{{ old('apellido_m', $cliente->contacto_predet?->apellido_m) }}"
                                        required  
                                        maxlength="27">
                                @error('apellido_m')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Correo Electrónico -->
                            <div class="col campo-dato-principal">
                                <label for="email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                                <input  name="email"
                                        type="email" 
                                        class="form-control guarda-minus"  
                                        maxlength="40" 
                                        value="{{ old( 'email', $cliente->contacto_predet?->email) }}" 
                                        required>
                            </div>

                            <!-- Género -->
                            <div class="col campo-dato-secundario">
                                <label class="form-label">Género <span class="text-danger asterisco">*</span></label>
                                @php
                                    $genero = old('genero', $cliente->contacto_predet?->genero);
                                @endphp
                                <select name="genero" class="form-select" required>
                                    <option value="">-- Selecciona --</option>
                                    <option value="masculino"       @selected($genero == 'masculino')>Masculino</option>
                                    <option value="femenino"        @selected($genero == 'femenino')>Femenino</option>
                                    <option value="no-especificado" @selected($genero == 'no-especificado')>No especificado</option>
                                </select>
                            </div>

                            
                            
                            {{-- Contacto Principal ─ Teléfonos PERSONAL --}}
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
                                    <div class="col campo-dato-telefono" id="telefonos-col--view" style="padding-right: 0px !important;">

                                        {{-- Teléfono fila 1 --}}
                                          <div class="mb-2 telefono-item">
                                            <label>Teléfono 1</label>
                                            <div class="input-group input-group-separated">
                                            <input  name="contacto[0][telefono1]" 
                                                    class="form-control phone-field" 
                                                    value="{{ old("contacto.0.telefono1", $cliente->contacto_predet?->telefono1) }}" 
                                                    placeholder="Teléfono" 
                                                    style="min-width: 16ch; max-width: 16ch;">
                                            @error('contacto.0.telefono1') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            <input  name="contacto[0][ext1]" 
                                                    class="form-control ext-field div-10ch" 
                                                    value="{{ old("contacto.0.ext1", $cliente->contacto_predet?->ext1) }}" 
                                                    placeholder="Ext." 
                                                    maxlength="7">
                                            @error('contacto.0.ext1') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            <button type="button" class="btn btn-outline-primary agregar-telefono btn-field d-none">+</button>
                                            </div>
                                        </div>

                                        @for ($i = 2; $i <= 5; $i++)
                                            @continue(!$hasTel($i))
                                            <div class="mb-2 telefono-item">
                                                <label>Teléfono {{ $i }}</label>
                                                <div class="input-group input-group-separated">
                                                    <input  type="text" name="contacto[0][telefono{{ $i }}]"
                                                            value="{{ old("contacto.0.telefono".$i, $cliente->contacto_predet?->{'telefono'.$i}) }}"
                                                            class="form-control phone-field"
                                                            style="min-width: 16ch; max-width: 16ch;">
                                                    <input  type="text" name="contacto[0][ext{{ $i }}]"
                                                            value="{{ old("contacto.0.ext".$i, $cliente->contacto_predet?->{'ext'.$i}) }}"
                                                            class="form-control ext-field div-10ch" maxlength="7">
                                                    <button type="button"
                                                            class="btn btn-outline-danger eliminar-item btn-field d-none">X</button>
                                                </div>
                                            </div>
                                        @endfor
                                        <small id="tel-limit-msg" class="text-danger mt-1 d-none">
                                            Solo puedes agregar hasta 5 teléfonos.
                                        </small>


                                    </div>

                                    {{-- Celulares --}}
                                    <div class="col campo-dato-secundario" id="celulares-col--view" style="padding-right: 0px !important;" >
                                        {{-- Celular fila 1 --}}
                                        <div class="mb-2 celular-item campo-dato-secundario">
                                            <label>Teléfono Celular 1</label>
                                            <div class="input-group input-group-separated">
                                                <input  type="text" 
                                                        name="contacto[0][celular1]" 
                                                        placeholder="Celular" 
                                                        value="{{ old("contacto.0.celular1", $cliente->contacto_predet?->celular1) }}" 
                                                        class="form-control phone-field" 
                                                        style="min-width: 16ch; max-width: 16ch;">
                                                <button type="button" class="btn btn-outline-primary agregar-celular btn-field d-none">+</button>
                                            </div>
                                        </div>
                                        @for ($i = 2; $i <= 5; $i++)
                                            @continue(!$hasCel($i))
                                            <div class="mb-2 celular-item">
                                                <label>Teléfono Celular {{ $i }}</label>
                                                <div class="input-group input-group-separated campo-dato-secundario">
                                                    <input type="text" name="contacto[0][celular{{ $i }}]"
                                                            value="{{ old("contacto.0.celular".$i, $cliente->contacto_predet?->{'celular'.$i}) }}"
                                                            class="form-control phone-field"
                                                            style="min-width: 16ch; max-width: 16ch;">
                                                    <button type="button" class="btn btn-outline-danger eliminar-item btn-field d-none">X</button>
                                                </div>
                                            </div>
                                        @endfor
                                        <small id="cel-limit-msg" class="text-danger mt-1 d-none">
                                            Solo puedes agregar hasta 5 celulares.
                                        </small>
                                    </div>
                                </div>
                            </div>


                        </div>

                    </div>
                </div>

            </div>
        </form>
    @endif

    {{-- Historial de cotizaciones ---------------------------------------------------}}
    <div class="card shadow-lg">
        {{-- Botón para archivar cuenta --}}
        {{-- Cabecera de la tarjeta --}}
        <div class="card-header card-header--view">
            <h5 class="mb-0 flex-grow-1">Historial de cotizaciones</h5>
        </div>

        <div class="card-body p-0"> {{-- p-0 = quitamos padding extra --}}
            {{-- contenedor scroll con altura máx (ajusta a tu gusto) --}}
            <div class="table-responsive" style="max-height: 380px; overflow-y: auto; background: var(--tabla-header-bg);">
                @php
                    $totalSubtotal = $cotizaciones->sum('subtotal');
                    $totalMargen   = $cotizaciones->sum('margen');   // o lo que te pidan mostrar
                    $totalFactor   = $cotizaciones->sum('factor');
                @endphp

                <table id="tblCotizaciones" class="table table-sm table-striped mb-0" style="border-style: none !important;">
                    <thead class="table-light position-sticky top-0" style="z-index:1">
                        <tr>
                            <th data-type="date" class="div-10ch">Fecha <span class="sort-arrow"></span></th>
                            <th data-type="text" class="div-10ch">ID&nbsp;cotización <span class="sort-arrow"></span></th>
                            <th data-type="text" class="campo-dato-secundario">Razón social <span class="sort-arrow"></span></th>
                            <th data-type="number" class="div-15ch text-end">Subtotal <span class="sort-arrow"></span></th>
                            <th data-type="number" class="div-15ch text-end">Margen <span class="sort-arrow"></span></th>
                            <th data-type="text" class="div-10ch text-end">Factor <span class="sort-arrow"></span></th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($cotizaciones as $c)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($c['fecha'])->format('d-m-Y') }}</td>
                                <td>{{ $c['id'] }}</td>
                                <td>{{ $c['razon'] }}</td>
                                <td class="text-end">$ {{ number_format($c['subtotal'], 2) }}</td>
                                <td class="text-end">{{ number_format($c['margen'], 2) }}</td>
                                <td class="text-end">{{ number_format($c['factor'], 2) }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Sin cotizaciones registradas…</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-light position-sticky bottom-0" style="border-style: none !important; z-index:1;">
                        <tr style="border-style: none !important;">
                            <th colspan="3" class="text-end">Totales</th>
                            <th class="text-end">$ {{ number_format($totalSubtotal, 2) }}</th>
                            <th class="text-end">{{ number_format($totalMargen, 2) }}</th>
                            <th class="text-end">{{ number_format($totalFactor, 2) }}%</th>
                        </tr>
                    </tfoot>

                </table>
            </div>
        </div>
    </div>


    {{-- Historial de pedidos ---------------------------------------------------}}
    <div class="card shadow-lg">
        {{-- Botón para archivar cuenta --}}
        {{-- Cabecera de la tarjeta --}}
        <div class="card-header card-header--view">
            <h5 class="mb-0 flex-grow-1">Historial de pedidos</h5>
        </div>

        <div class="card-body p-0"> {{-- p-0 = quitamos padding extra --}}
            {{-- contenedor scroll con altura máx (ajusta a tu gusto) --}}
            <div class="table-responsive" style="max-height: 380px; overflow-y: auto; background: var(--tabla-header-bg);">
                @php
                    $totalSubtotal = $pedidos->sum('subtotal');
                    $totalMargen   = $pedidos->sum('margen');   // o lo que te pidan mostrar
                    $totalFactor   = $pedidos->sum('factor');
                @endphp

                <table id="tblPedidos" class="table table-sm table-striped mb-0" style="border-style: none !important;">
                    <thead class="table-light position-sticky top-0" style="z-index:1">
                        <tr>
                            <th data-type="date" class="div-10ch" >Fecha <span class="sort-arrow"></span></th>
                            <th data-type="text" class="div-10ch">ID&nbsp;pedido <span class="sort-arrow"></span></th>
                            <th data-type="text" class="campo-dato-secundario">Razón social <span class="sort-arrow"></span></th>
                            <th data-type="number" class="div-15ch text-end">Subtotal <span class="sort-arrow"></span></th>
                            <th data-type="number" class="div-15ch text-end">Margen <span class="sort-arrow"></span></th>
                            <th data-type="text" class="div-10ch text-end">Factor <span class="sort-arrow"></span></th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($pedidos as $p)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($p['fecha'])->format('d-m-Y') }}</td>
                                <td>{{ $p['id'] }}</td>
                                <td>{{ $p['razon'] }}</td>
                                <td class="text-end">$ {{ number_format($p['subtotal'], 2) }}</td>
                                <td class="text-end">{{ number_format($p['margen'], 2) }}</td>
                                <td class="text-end">{{ number_format($p['factor'], 2) }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Sin pedidos registrados…</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-light position-sticky bottom-0" style="border-style: none !important; z-index:1;">
                        <tr style="border-style: none !important;">
                            <th colspan="3" class="text-end">Totales</th>
                            <th class="text-end">$ {{ number_format($totalSubtotal, 2) }}</th>
                            <th class="text-end">{{ number_format($totalMargen, 2) }}</th>
                            <th class="text-end">{{ number_format($totalFactor, 2) }}%</th>
                        </tr>
                    </tfoot>

                </table>
            </div>
        </div>
    </div>


    {{-- 📝 Historial de notas --}}
    <div class="card shadow-lg">
        {{-- Cabecera de la tarjeta --}}
        <div class="card-header fw-bold" style="background-color: rgba(81, 86, 190, 0.1);">
            Historial de notas
        </div>
        <div class="card-body">

            {{-- Área scrolleable con historial --}}
            <div class="form-group mb-4">
                <textarea class="form-control" rows="10"  disabled style="resize: none; overflow-y: scroll;">
                    @foreach ($notas as $nota)
                    {{ \Carbon\Carbon::parse($nota->fecha_registro)->format('d-m-Y h:i A') }} - EJECUTIVO: {{ $nota->usuario->username ?? '—' }} - ETAPA: {{ strtoupper($nota->etapa) }}

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
        //Eric: Comentario, considerar poner un script para la tabla de cotizaciones.
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
        document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('formCuenta');

        form.querySelectorAll('.phone-field').forEach(inp => {
            // Mientras el usuario teclea...
            inp.addEventListener('input', () => {
            const digits = inp.value.replace(/\D/g, '');

            if (digits.length === 0 || digits.length === 10) {
                inp.setCustomValidity('');              // válido
            } else {
                inp.setCustomValidity('Número incompleto');
            }
            });
        });

        // Seguridad extra: justo antes de enviar,
        // limpia cualquier máscara incompleta
        form.addEventListener('submit', () => {
            form.querySelectorAll('.phone-field').forEach(inp => {
            const digits = inp.value.replace(/\D/g, '');
            if (digits.length < 10) inp.value = '';   // lo deja vacío → válido
            });
        });
        });
    </script>


<script>
    document.addEventListener('DOMContentLoaded', () => {
    const MAX    = 5;
    const telCol = document.getElementById('telefonos-col--view');
    const celCol = document.getElementById('celulares-col--view');
    const addTel = telCol.querySelector('.agregar-telefono');
    const addCel = celCol.querySelector('.agregar-celular');
    const btnEdit= document.getElementById('btnEditar');
    const form   = document.getElementById('formCuenta');
    if (!btnEdit || !form) return;

    // fila 1 ⇢ incluye botón ➕ (oculto con d-none)
    const mkTelRowPlus = () => `
    <div class="mb-2 telefono-item">
        <label>Teléfono 1</label>
        <div class="input-group input-group-separated">
        <input type="text" class="form-control phone-field" placeholder="Teléfono" style="min-width: 16ch; max-width: 16ch;">
        <input type="text" class="form-control ext-field div-10ch"   placeholder="Ext." maxlength="7">
        <button type="button" class="btn btn-outline-primary agregar-telefono btn-field d-none">+</button>
        </div>
    </div>`;
    const mkCelRowPlus = () => `
    <div class="mb-2 celular-item">
        <label>Teléfono Celular 1</label>
        <div class="input-group input-group-separated">
        <input type="text" class="form-control phone-field" placeholder="Celular" style="min-width: 16ch; max-width: 16ch;">
        <button type="button" class="btn btn-outline-primary agregar-celular btn-field d-none">+</button>
        </div>
    </div>`;


    // ——— Crea filas sin ➕ ———
    const mkTelRow = () => `
        <div class="mb-2 telefono-item">
        <label></label>
        <div class="input-group input-group-separated">
            <input type="text" class="form-control phone-field"  placeholder="Teléfono" style="min-width: 16ch; max-width: 16ch;">
            <input type="text" class="form-control ext-field div-10ch"    placeholder="Ext." maxlength="7">
            <button type="button" class="btn btn-outline-danger eliminar-item btn-field">X</button>
        </div>
        </div>`;
    const mkCelRow = () => `
        <div class="mb-2 celular-item">
        <label></label>
        <div class="input-group input-group-separated">
            <input type="text" class="form-control phone-field" placeholder="Celular" style="min-width: 16ch; max-width: 16ch;">
            <button type="button" class="btn btn-outline-danger eliminar-item btn-field">X</button>
        </div>
        </div>`;

    // ——— Actualiza nombres/textos y botones ➕/❌ ———
    function updateRowButtons() {
        // Teléfonos
        telCol.querySelectorAll('.telefono-item').forEach((item, idx) => {
        const plus  = item.querySelector('.agregar-telefono');
        const minus = item.querySelector('.eliminar-item');
        if (idx === 0) { plus?.classList.remove('d-none'); minus?.classList.add('d-none'); }
        else           { plus?.classList.add('d-none');   minus?.classList.remove('d-none'); }
        });
        // Celulares
        celCol.querySelectorAll('.celular-item').forEach((item, idx) => {
        const plus  = item.querySelector('.agregar-celular');
        const minus = item.querySelector('.eliminar-item');
        if (idx === 0) { plus?.classList.remove('d-none'); minus?.classList.add('d-none'); }
        else           { plus?.classList.add('d-none');   minus?.classList.remove('d-none'); }
        });
    }

    let telTimeout, celTimeout;
    function toggleLimitMessages() {
    const telMsg = document.getElementById('tel-limit-msg');
    const celMsg = document.getElementById('cel-limit-msg');
    const telCount = telCol.querySelectorAll('.telefono-item').length;
    const celCount = celCol.querySelectorAll('.celular-item').length;

    // —— Teléfonos ——
    if (telMsg) {
        if (telCount >= MAX) {
        // muestra y mueve al final
        telMsg.classList.remove('d-none');
        telCol.appendChild(telMsg);
        clearTimeout(telTimeout);
        telTimeout = setTimeout(() => {
            telMsg.classList.add('d-none');
        }, 5000);
        } else {
        telMsg.classList.add('d-none');
        clearTimeout(telTimeout);
        }
    }

    // —— Celulares ——
    if (celMsg) {
        if (celCount >= MAX) {
        celMsg.classList.remove('d-none');
        celCol.appendChild(celMsg);
        clearTimeout(celTimeout);
        celTimeout = setTimeout(() => {
            celMsg.classList.add('d-none');
        }, 5000);
        } else {
        celMsg.classList.add('d-none');
        clearTimeout(celTimeout);
        }
    }
    }



    function ensureFirstPlusButtons() {
    // —— Teléfonos ——
    const firstTel = telCol.querySelector('.telefono-item');
    if (firstTel && !firstTel.querySelector('.agregar-telefono')) {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn btn-outline-primary agregar-telefono btn-field';
        btn.textContent = '+';
        // lo metemos al final de la input-group
        firstTel.querySelector('.input-group').appendChild(btn);
    }
    // —— Celulares ——
    const firstCel = celCol.querySelector('.celular-item');
    if (firstCel && !firstCel.querySelector('.agregar-celular')) {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn btn-outline-primary agregar-celular btn-field';
        btn.textContent = '+';
        firstCel.querySelector('.input-group').appendChild(btn);
    }
    }


    // ——— Ajusta índices y etiquetas ———
    const reindex = tipo => {
        const items = (tipo === 'telefono')
        ? telCol.querySelectorAll('.telefono-item')
        : celCol.querySelectorAll('.celular-item');
        items.forEach((item, i) => {
        const idx = i + 1;
        if (tipo === 'telefono') {
            const [tel, ext] = item.querySelectorAll('input');
            tel.name = `contacto[0][telefono${idx}]`;
            ext.name = `contacto[0][ext${idx}]`;
            item.querySelector('label').textContent = `Teléfono ${idx}`;
        } else {
            item.querySelector('input').name = `contacto[0][celular${idx}]`;
            item.querySelector('label').textContent = `Teléfono Celular ${idx}`;
        }
        });
        updateRowButtons();
    };

    // ——— Elimina filas vacías ———
    const removeEmptyRows = () => {
    // borra vacías…
    telCol.querySelectorAll('.telefono-item').forEach(el => {
        const [tel, ext] = el.querySelectorAll('input');
        if (!tel.value.trim() && !ext.value.trim()) el.remove();
    });
    celCol.querySelectorAll('.celular-item').forEach(el => {
        if (!el.querySelector('input').value.trim()) el.remove();
    });

    // si quedó a 0, recrea fila 1 (con ➕ oculto)
    if (telCol.querySelectorAll('.telefono-item').length === 0) {
        telCol.insertAdjacentHTML('afterbegin', mkTelRowPlus());
    }
    if (celCol.querySelectorAll('.celular-item').length === 0) {
        celCol.insertAdjacentHTML('afterbegin', mkCelRowPlus());
    }

    ensureFirstPlusButtons();

    // re-indexa y ajusta botones
    reindex('telefono');
    reindex('celular');
    toggleLimitMessages();

    };

    // ——— Ocultar inputs/botones ———
    const setEditing = (state) => {
        /* 1. Inputs y selects */
        form.querySelectorAll('input:not(.no-editar), select:not(.no-editar), textarea:not(.no-editar)')
            .forEach(el => { el.disabled = !state; });

        /* 2. Botones dinámicos de teléfonos / celulares */
        const toggleBtns = (col, sel) => {
        col.querySelectorAll(sel).forEach(btn => {
            btn.disabled = !state;
            btn.classList.toggle('d-none', !state);   // oculta en modo lectura
        });
        };
        toggleBtns(telCol, '.agregar-telefono, .eliminar-item');
        toggleBtns(celCol, '.agregar-celular, .eliminar-item');

        /* 3. Botón Guardar */
        const btnGuardar = document.querySelector('.btnGuardarCuenta');
        if (btnGuardar) {
            if (state) {
                // Activar y poner en verde MACASA
                btnGuardar.classList.remove('btn-secondary');
                btnGuardar.classList.add('btn-success');
                btnGuardar.disabled = false;
            } else {
                // Desactivar y poner gris Bootstrap
                btnGuardar.classList.remove('btn-success');
                btnGuardar.classList.add('btn-secondary');
                btnGuardar.disabled = true;
            }
        }

        
    };

    // ——— Lógica de Editar / Guardar ———
    let editing = false;
    btnEdit.addEventListener('click', () => {
    editing = !editing;
    if (editing) {
        // — Entrar a edición —
        btnEdit.classList.remove('btn-success');
        btnEdit.classList.add('btn-secondary');
        btnEdit.innerHTML = '<i class="fa fa-lock-open me-1"></i> Edición habilitada';
        document.querySelectorAll('.asterisco').forEach (el => el.classList.add('ocultar-asterisco'));
        setEditing(true);

        // Asegura fila 1 de celular si no existía
        if (celCol.querySelectorAll('.celular-item').length === 0) {
        celCol.insertAdjacentHTML(
            'afterbegin',
            mkCelRow().replace('<label></label>','<label>Teléfono Celular 1</label>')
        );
        }

        ensureFirstPlusButtons();

        // Reindex para mostrar ➕/❌ correctamente
        reindex('telefono');
        reindex('celular');
        toggleLimitMessages();

    } else {
        // — Cerrar edición —
        removeEmptyRows();        // limpia vacías y deja al menos 1 fila
        ensureFirstPlusButtons();

        reindex('telefono');      // reposiciona y reaplica updateRowButtons
        reindex('celular');
        toggleLimitMessages();

        setEditing(false);        // por último, oculta todos los ➕/❌ y bloquea inputs

        btnEdit.classList.remove('btn-secondary');
        btnEdit.classList.add('btn-success');
        btnEdit.innerHTML = '<i class="fa fa-edit me-1"></i> Editar cuenta';
        document.querySelectorAll('.asterisco').forEach(el => el.classList.remove('ocultar-asterisco'));

    }
    });


    // ——— Añadir filas ———
    telCol.addEventListener('click', e => {
    if (!e.target.closest('.agregar-telefono')) return;
    if (telCol.querySelectorAll('.telefono-item').length >= MAX) return;
    telCol.insertAdjacentHTML('beforeend', mkTelRow()); // fila sin ➕
    reindex('telefono');
    toggleLimitMessages();

    });

    celCol.addEventListener('click', e => {
    if (!e.target.closest('.agregar-celular')) return;
    if (celCol.querySelectorAll('.celular-item').length >= MAX) return;
    celCol.insertAdjacentHTML('beforeend', mkCelRow());
    reindex('celular');
    toggleLimitMessages();

    });


    // ——— Eliminar filas ———
    document.addEventListener('click', e => {
        if (!e.target.classList.contains('eliminar-item')) return;
        const item = e.target.closest('.telefono-item, .celular-item');
        const isTel = !!item.closest('#telefonos-col--view');
        item.remove();
        reindex(isTel ? 'telefono' : 'celular');
    });

    ensureFirstPlusButtons();

    // Estado inicial
        reindex('telefono');
        reindex('celular');
        toggleLimitMessages();

        setEditing(false);

    });
</script>

{{-- <script>
    document.getElementById('btnEditar').addEventListener('clic', function(){
        console.log('clci para asterisco');
        document.getElementById('asterisco').classList.add('ocultar-asterisco');
    });
</script> --}}



</div><!-- Fin contenedor principal -->

@endsection