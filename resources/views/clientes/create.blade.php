@extends('layouts.app')

@section('title', 'SIS 3.0 | Nueva Cuenta Organizacional')

@section('content')
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('inicio') }}">Inicio</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('clientes.index') }}">Nueva Cuenta</a></li>
    @endsection
    @if ($tipo === 'moral')

        <!-- ╭━━━━━━━━━━━━━━━━━━━━━━━━━━ Formulario para Personas Morales ━━━━━━━━━━━━━━━━━━━━━━━━╮ -->
        <h1 class="mb-4">Nueva Cuenta Empresarial</h1>
        <div class="container-fluid">
            <!-- ╭━━━━━━━━━━━━━━━━━━ Botonera superior ━━━━━━━━━━━━━━━━━╮ -->
            <div class="d-flex gap-2 mb-3">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left me-1"></i> Regresar
                </a>

                <button form="clienteForm" type="submit" class="btn btn-success">
                    <i class="fa fa-save me-1"></i> Guardar
                </button>

                <a href="{{ route('clientes.index') }}" class="btn btn-primary">
                    <i class="fa fa-list me-1"></i> Mis cuentas
                </a>
            </div>

            <!-- Aviso temporal para dirección -->
            <div class="alert alert-warning py-2 px-3 small mb-4">
                <i class="fa fa-info-circle me-1"></i>
                Esta pantalla es sólo de demostración. Los datos aún no se envían al sistema.
            </div>
            <!-- ╭━━━━━━━━━━━━━━━━━━ Formulario principal ━━━━━━━━━━━━━━╮ -->
            <form id="clienteForm" action="{{ route('clientes.store') }}" method="POST" autocomplete="off">
                @csrf

                <!-- Valores por defecto / lógica de negocio -->
                <input type="hidden" name="ciclo_venta" value="Cotizacion">
                <input type="hidden" name="estatus" value="Activo">
                <input type="hidden" name="tipo" value="ERP"><!-- alta desde ERP -->

                <!-- ╭━━━━━━━━━━ Datos Generales ━━━━━━━━━━╮ -->
                <div class="card shadow-sm mb-4 section-card">
                    <div class="card-header section-card-header">
                        <h5 class="mb-0">Cuenta Empresarial</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre de la Empresa</label>
                                <input name="nombre" id="nombre" class="form-control" value="{{ old('nombre') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="sector" class="form-label">Sector</label>
                                <select name="sector" id="sector" class="form-select">
                                    <option value="">—</option>
                                    <option value="privada">Empresa Privada</option>
                                    <option value="gobierno">Empresa Gobierno</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="segmento" class="form-label">Segmento</label>
                                <select name="segmento" id="segmento" class="form-select">
                                    <option value="">—</option>
                                    <option value="Macasa Cuentas Especiales">Macasa Cuentas Especiales</option>
                                    <option value="Macasa Ecommerce">Macasa Ecommerce</option>
                                    <option value="Tekne Store ECommerce">Tekne Store ECommerce</option>
                                    <option value="La Plaza Ecommerce">La Plaza Ecommerce</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ╭━━━━━━━━━━ Contacto Principal ━━━━━━━━━━╮ -->
                <div class="card shadow-sm mb-4 section-card">
                    <div class="card-header section-card-header">
                        <h6 class="mb-0">Contacto</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="contacto[nombre]" class="form-label">Nombre(s)</label>
                                <input name="contacto[nombre]" class="form-control" value="{{ old('contacto.nombre') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="contacto[apellido_paterno]" class="form-label">Primer Apellido</label>
                                <input name="contacto[apellido_paterno]" class="form-control"
                                    value="{{ old('contacto.apellido_paterno') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="contacto[apellido_materno]" class="form-label">Segundo Apellido</label>
                                <input name="contacto[apellido_materno]" class="form-control"
                                    value="{{ old('contacto.apellido_materno') }}">
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label for="contacto[email]" class="form-label">Correo Electrónico</label>
                                <input name="contacto[email]" class="form-control" value="{{ old('contacto.email') }}">
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-3">
                                <label class="form-label">Teléfono 1</label>
                                <input name="contacto[telefono]" class="form-control" value="{{ old('contacto.telefono') }}">
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">Ext.</label>
                                <input name="contacto[ext]" class="form-control" value="{{ old('contacto.ext') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Teléfono 2</label>
                                <input name="contacto[telefono2]" class="form-control">
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">Ext.</label>
                                <input name="contacto[ext2]" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ╭━━━━ Dirección de ENTREGA (múltiples) ━━━━╮ -->
                <div class="card shadow-sm mb-4 section-card">
                    <div class="card-header section-card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Direcciones de Entrega</h6>
                        <button type="button" id="agregarDireccionEntrega" class="btn btn-sm btn-outline-primary">
                            <i class="fa fa-plus"></i> Agregar otra
                        </button>
                    </div>

                    <div class="card-body">
                        <div id="contenedorDireccionesEntrega">
                            <!-- Bloque inicial (índice 0) -->
                            <div class="entrega-block mb-4 border rounded p-3 bg-light-subtle">
                                <h6 class="mb-3 text-muted">Dirección 1</h6>
                                <div class="row g-3">
                                    <div class="col-md-6"><label class="form-label">Nombre de la Dirección</label>
                                        <input name="direcciones_entrega[${index}][nombre]" class="form-control">
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Calle</label>
                                        <input name="direcciones_entrega[0][calle]" class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Num. ext.</label>
                                        <input name="direcciones_entrega[0][num_ext]" class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Num. int.</label>
                                        <input name="direcciones_entrega[0][num_int]" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Colonia</label>
                                        <input name="direcciones_entrega[0][colonia]" class="form-control">
                                    </div>
                                </div>
                                <div class="row g-3 mt-1">
                                    <div class="col-md-4">
                                        <label class="form-label">Ciudad / Municipio</label>
                                        <input name="direcciones_entrega[0][ciudad]" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Estado</label>
                                        <input name="direcciones_entrega[0][estado]" class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">País</label>
                                        <input name="direcciones_entrega[0][pais]" class="form-control" value="México">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">C.P.</label>
                                        <input name="direcciones_entrega[0][cp]" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ╭━━━━ Datos de Facturación (razón social + dirección) ━━━━╮ -->
                <div class="card shadow-sm mb-4 section-card">
                    <div class="card-header section-card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Datos de Facturación</h6>
                        <button type="button" id="agregarFacturacion" class="btn btn-sm btn-outline-primary">
                            <i class="fa fa-plus"></i> Agregar razón social
                        </button>
                    </div>

                    <div class="card-body">
                        <div id="contenedorFacturacion">
                            <!-- Bloque inicial (índice 0) -->
                            <div class="facturacion-block mb-4 border rounded p-3 bg-light-subtle">
                                <h6 class="mb-3 text-muted">Razón Social 1</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Razón social</label>
                                        <input name="razones[0][nombre]" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">RFC</label>
                                        <input name="razones[0][rfc]" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Método de pago</label>
                                        <select name="razones[0][metodo_pago]" class="form-select">
                                            <option value="">—</option>
                                            <option value="PUE">PUE - Pago en una sola exhibición</option>
                                            <option value="PPD">PPD - Pago en parcialidades o diferido</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Forma de pago</label>
                                        <select name="razones[0][forma_pago]" class="form-select">
                                            <option value="">—</option>
                                            <option value="01">01 - Efectivo</option>
                                            <option value="03">03 - Transferencia electrónica</option>
                                            <option value="04">04 - Tarjeta de crédito</option>
                                            <option value="28">28 - Tarjeta de débito</option>
                                            <option value="99">99 - Por definir</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Régimen fiscal</label>
                                        <select name="razones[0][regimen_fiscal]" class="form-select">
                                            <option value="">—</option>
                                            <option value="601">601 - General de Ley Personas Morales</option>
                                            <option value="603">603 - Personas Morales con Fines no Lucrativos</option>
                                            <option value="605">605 - Sueldos y Salarios</option>
                                            <option value="612">612 - Personas Físicas con Actividades Empresariales</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Límite de crédito</label>
                                        <input name="razones[0][limite_credito]" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Días de crédito</label>
                                        <input name="razones[0][dias_credito]" class="form-control">
                                    </div>
                                </div>

                                <hr class="my-4">

                                <!-- Dirección de facturación -->
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Calle</label>
                                        <input name="razones[0][direccion][calle]" class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Num. ext.</label>
                                        <input name="razones[0][direccion][num_ext]" class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Num. int.</label>
                                        <input name="razones[0][direccion][num_int]" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Colonia</label>
                                        <input name="razones[0][direccion][colonia]" class="form-control">
                                    </div>
                                </div>
                                <div class="row g-3 mt-1">
                                    <div class="col-md-4">
                                        <label class="form-label">Ciudad / Municipio</label>
                                        <input name="razones[0][direccion][ciudad]" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Estado</label>
                                        <input name="razones[0][direccion][estado]" class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">País</label>
                                        <input name="razones[0][direccion][pais]" class="form-control" value="México">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">C.P.</label>
                                        <input name="razones[0][direccion][cp]" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </form>
        </div>
        <!-- ━━━━━━━━━━━━━━━━━━━━━━━━━━ TerminaFormulario para Personas Morales ━━━━━━━━━━━━━━━━━━━━━━━━ -->

    @elseif ($tipo === 'fisica')

        <!-- ╭━━━━━━━━━━━━━━━━━━━━━━━━━━ Formulario para Personas Físicas ━━━━━━━━━━━━━━━━━━━━━━━━╮ -->
        <h1 class="mb-4">Nueva Cuenta Personal</h1>
        <div class="container-fluid">
            <!-- ╭━━━━━━━━━━━━━━━━━━ Botonera superior ━━━━━━━━━━━━━━━━━╮ -->
            <div class="d-flex gap-2 mb-3">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left me-1"></i> Regresar
                </a>

                <button form="clienteForm" type="submit" class="btn btn-success">
                    <i class="fa fa-save me-1"></i> Guardar
                </button>

                <a href="{{ route('clientes.index') }}" class="btn btn-primary">
                    <i class="fa fa-list me-1"></i> Mis cuentas
                </a>
            </div>

            <!-- Aviso temporal para dirección -->
            <div class="alert alert-warning py-2 px-3 small mb-4">
                <i class="fa fa-info-circle me-1"></i>
                Esta pantalla es sólo de demostración. Los datos aún no se envían al sistema.
            </div>
            <!-- ╭━━━━━━━━━━━━━━━━━━ Formulario principal ━━━━━━━━━━━━━━╮ -->
            <form id="clienteForm" action="{{ route('clientes.store') }}" method="POST" autocomplete="off">
                @csrf

                <!-- Valores por defecto / lógica de negocio -->
                <input type="hidden" name="ciclo_venta" value="Cotizacion">
                <input type="hidden" name="estatus" value="Activo">
                <input type="hidden" name="tipo" value="ERP"><!-- alta desde ERP -->

                <!-- ╭━━━━ 1. Cuenta eje ━━━━╮ -->
                <div class="card shadow-sm mb-4 section-card">
                    <div class="card-header section-card-header">
                        <h5 class="mb-0">Datos de la cuenta eje</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre de la cuenta *</label>
                                <input type="text" name="nombre" id="nombre"
                                    class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}"
                                    required>
                                @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="idvendedor" class="form-label">Asignado a</label>

                                <select name="idvendedor" id="idvendedor" class="form-select" required>
                                    {{-- placeholder: se marca si old() viene vacío --}}
                                    <option value="" @selected(old('idvendedor') === null || old('idvendedor') === '') disabled
                                        hidden>— Selecciona —</option>

                                    {{-- opción “Base General” --}}
                                    <option value="0" @selected(old('idvendedor') == '0')>Base General</option>

                                    {{-- resto de vendedores --}}
                                    @foreach ($vendedores as $id => $vendedor)
                                        <option value="{{ $id }}" @selected(old('idvendedor') == $id)>
                                            {{ $vendedor->username }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="row g-3 mt-1">
                            <div class="col-md-4">
                                <label for="tipo_cliente" class="form-label">Teléfono</label>
                                <input type="text" name="telefono" id="telefono"
                                    class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono') }}"
                                    required>
                            </div>
                            <div class="col-md-2">
                                <label for="fuente_contacto" class="form-label>">Ext.</label>
                                <input type="text" name="ext" id="ext" class="form-control @error('ext') is-invalid @enderror"
                                    value="{{ old('ext') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="idvendedor" class="form-label">Tipo de Cuenta</label>
                                <select name="sector" id="sector" class="form-select">
                                    <option value="">— Selecciona —</option>
                                    <option value="privada">Empresa Privada</option>
                                    <option value="gobierno">Empresa de Gobierno</option>
                                </select>

                            </div>

                        </div>
                        <div class="row g-3 mt-1 mb-4">

                            <div class="col-md-3">
                                <label for="contacto_fuente" class="form-label">Fuente</label>
                                <select name="contacto[fuente]" id="contacto_fuente" class="form-select">
                                    <option value="">—</option>
                                    <option value="Femenino" @selected(old('contacto.fuente') == 'Femenino')>BD SIEM</option>
                                    <option value="Masculino" @selected(old('contacto.sexo') == 'Masculino')>Contacto</option>
                                    <option value="Femenino" @selected(old('contacto.fuente') == 'Femenino')>Página Web</option>
                                    <option value="Masculino" @selected(old('contacto.sexo') == 'Masculino')>Redes Sociales
                                    </option>
                                    <option value="Femenino" @selected(old('contacto.fuente') == 'Femenino')>Recomendación
                                    </option>
                                    <option value="Masculino" @selected(old('contacto.sexo') == 'Masculino')>SIS</option>
                                    <option value="Femenino" @selected(old('contacto.fuente') == 'Femenino')>Sistemas</option>
                                    <option value="Masculino" @selected(old('contacto.sexo') == 'Masculino')>Ecommerce</option>

                                </select>
                            </div>
                        </div>


                        <!-- ╭━━━━ 2. Contacto principal (opcional) ━━━━╮ -->
                        <div class="card shadow-sm mb-4 section-card">
                            <div class="card-header section-card-header">
                                <h6 class="mb-0">Contacto principal <span class="text-muted">(opcional)</span></h6>
                            </div>
                            <div class="card-body">
                                <!-- Nombre completo -->
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="contacto_nombre" class="form-label">Nombre</label>
                                        <input name="contacto[nombre]" id="contacto_nombre" class="form-control"
                                            value="{{ old('contacto.nombre') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="contacto_apellido_paterno" class="form-label">Apellido paterno</label>
                                        <input name="contacto[apellido_paterno]" id="contacto_apellido_paterno"
                                            class="form-control" value="{{ old('contacto.apellido_paterno') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="contacto_apellido_materno" class="form-label">Apellido materno</label>
                                        <input name="contacto[apellido_materno]" id="contacto_apellido_materno"
                                            class="form-control" value="{{ old('contacto.apellido_materno') }}">
                                    </div>
                                </div>

                                <!-- Datos adicionales -->
                                <div class="row g-3 mt-1">
                                    <div class="col-md-4">
                                        <label for="contacto_sexo" class="form-label">Sexo</label>
                                        <select name="contacto[sexo]" id="contacto_sexo" class="form-select">
                                            <option value="">—</option>
                                            <option value="Femenino" @selected(old('contacto.sexo') == 'Femenino')>Femenino
                                            </option>
                                            <option value="Masculino" @selected(old('contacto.sexo') == 'Masculino')>Masculino
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="contacto_puesto" class="form-label">Puesto</label>
                                        <input name="contacto[puesto]" id="contacto_puesto" class="form-control"
                                            value="{{ old('contacto.puesto') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="contacto_email" class="form-label">Email</label>
                                        <input type="email" name="contacto[email]" id="contacto_email" class="form-control"
                                            value="{{ old('contacto.email') }}">
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-md-4">
                                        <label for="contacto_telefono" class="form-label">Teléfono</label>
                                        <input name="contacto[telefono]" id="contacto_telefono" class="form-control"
                                            value="{{ old('contacto.telefono') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="contacto_ext" class="form-label">Ext.</label>
                                        <input name="contacto[ext]" id="contacto_ext" class="form-control"
                                            value="{{ old('contacto.ext') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="contacto_celular" class="form-label">Celular</label>
                                        <input name="contacto[celular]" id="contacto_celular" class="form-control"
                                            value="{{ old('contacto.celular') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ╭━━━━ 3. Razón social (opcional) ━━━━╮ -->
                        <div class="card shadow-sm mb-4 section-card">
                            <div class="card-header section-card-header">
                                <h6 class="mb-0">Razón social <span class="text-muted">(opcional)</span></h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="razon_social" class="form-label">Razón social</label>
                                        <input name="razon_social[razon_social]" id="razon_social" class="form-control"
                                            value="{{ old('razon_social.razon_social') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="razon_rfc" class="form-label">RFC</label>
                                        <input name="razon_social[rfc]" id="razon_rfc" class="form-control"
                                            value="{{ old('razon_social.rfc') }}">
                                    </div>
                                </div>
                                <div class="row g-3">
                                    {{-- Catálogos: tiempo de entrega / condición de pago --}}
                                    <div class="col-md-3">
                                        <label class="form-label" for="entrega_tiempo">Tiempo de entrega</label>
                                        <select name="direccion_entrega[entrega]" id="entrega_tiempo" class="form-select">
                                            <option value="">—</option>
                                            @foreach ($catalogoEntregas as $valor)
                                                <option value="{{ $valor }}" @selected(old('direccion_entrega.entrega') === $valor)>
                                                    {{ $valor }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label" for="entrega_condicion">Condición de pago</label>
                                        <select name="direccion_entrega[condicion_pago]" id="entrega_condicion"
                                            class="form-select">
                                            <option value="">—</option>
                                            @foreach ($catalogoCondicionesPago as $valor)
                                                <option value="{{ $valor }}"
                                                    @selected(old('direccion_entrega.condicion_pago') === $valor)>
                                                    {{ $valor }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ============================= 4. Direcciones (ambas opcionales) ============================= -->

                        {{-- ╭━━ Dirección de ENTREGA ━━╮ --}}
                        <div class="card shadow-sm mb-4 section-card">
                            <div class="card-header section-card-header">
                                <h6 class="mb-0">Dirección de entrega <span class="text-muted">(opcional)</span></h6>
                            </div>

                            <div class="card-body">


                                <hr class="my-3">

                                {{-- Datos de calle --}}
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label" for="entrega_calle">Calle</label>
                                        <input name="direccion_entrega[calle]" id="entrega_calle" class="form-control"
                                            value="{{ old('direccion_entrega.calle') }}">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label" for="entrega_num_ext">Num. ext.</label>
                                        <input name="direccion_entrega[num_ext]" id="entrega_num_ext" class="form-control"
                                            value="{{ old('direccion_entrega.num_ext') }}">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label" for="entrega_num_int">Num. int.</label>
                                        <input name="direccion_entrega[num_int]" id="entrega_num_int" class="form-control"
                                            value="{{ old('direccion_entrega.num_int') }}">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label" for="entrega_colonia">Colonia</label>
                                        <input name="direccion_entrega[colonia]" id="entrega_colonia" class="form-control"
                                            value="{{ old('direccion_entrega.colonia') }}">
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-md-4">
                                        <label class="form-label" for="entrega_ciudad">Ciudad</label>
                                        <input name="direccion_entrega[ciudad]" id="entrega_ciudad" class="form-control"
                                            value="{{ old('direccion_entrega.ciudad') }}">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label" for="entrega_estado">Estado</label>
                                        <input name="direccion_entrega[estado]" id="entrega_estado" class="form-control"
                                            value="{{ old('direccion_entrega.estado') }}">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label" for="entrega_cp">C.P.</label>
                                        <input name="direccion_entrega[cp]" id="entrega_cp" class="form-control"
                                            value="{{ old('direccion_entrega.cp') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ╭━━ Dirección de FACTURACIÓN ━━╮ --}}
                        <div class="card shadow-sm mb-4 section-card">
                            <div class="card-header section-card-header">
                                <h6 class="mb-0">Dirección de facturación <span class="text-muted">(opcional)</span></h6>
                            </div>

                            <div class="card-body">
                                {{-- Puedes clonar campos del bloque anterior: --}}
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label" for="fact_calle">Calle</label>
                                        <input name="direccion_factura[calle]" id="fact_calle" class="form-control"
                                            value="{{ old('direccion_factura.calle') }}">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label" for="fact_num_ext">Num. ext.</label>
                                        <input name="direccion_factura[num_ext]" id="fact_num_ext" class="form-control"
                                            value="{{ old('direccion_factura.num_ext') }}">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label" for="fact_num_int">Num. int.</label>
                                        <input name="direccion_factura[num_int]" id="fact_num_int" class="form-control"
                                            value="{{ old('direccion_factura.num_int') }}">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label" for="fact_colonia">Colonia</label>
                                        <input name="direccion_factura[colonia]" id="fact_colonia" class="form-control"
                                            value="{{ old('direccion_factura.colonia') }}">
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-md-4">
                                        <label class="form-label" for="fact_ciudad">Ciudad</label>
                                        <input name="direccion_factura[ciudad]" id="fact_ciudad" class="form-control"
                                            value="{{ old('direccion_factura.ciudad') }}">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label" for="fact_estado">Estado</label>
                                        <input name="direccion_factura[estado]" id="fact_estado" class="form-control"
                                            value="{{ old('direccion_factura.estado') }}">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label" for="fact_cp">C.P.</label>
                                        <input name="direccion_factura[cp]" id="fact_cp" class="form-control"
                                            value="{{ old('direccion_factura.cp') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- =============================
                                                                     5. Notas
                                                                     ============================= -->
                        <div class="card shadow-sm mb-5">
                            <div class="card-header section-card-header">
                                <h6 class="mb-0">Notas</h6>
                            </div>
                            <div class="card-body">
                                <textarea name="notas" rows="3" class="form-control"
                                    placeholder="Observaciones adicionales…">{{ old('notas') }}</textarea>
                            </div>
                        </div>

            </form>
        </div>

        <!-- ━━━━━━━━━━━━━━━━━━━━━━━━━━ Termina Formulario para Personas Físicas ━━━━━━━━━━━━━━━━━━━━━━━━ -->
    @endif

@endsection

{{-- =============================================================
Notas de implementación
-------------------------------------------------------------
• El controlador debe enviar al Blade:
$vendedores = User::pluck('nombre','id');
$fuentesContacto = ["BD SIEM","CONTACTO","PAGINA WEB","REDES SOCIALES","RECOMENDACION","SIS","SISTEMAS","ECOMMERCE"];
$catalogoEntregas = [...]; // lista dura o CatTiempoEntrega::pluck('nombre')
$catalogoCondicionesPago= [...]; // idem

• Puedes extraer los <option> a components si decides crear
    <x-select> más adelante.
        • Acordeón: arranca colapsado para no abrumar al usuario;
        sólo "Cuenta eje" está visible por defecto.
        ================================================================= --}}