@extends('layouts.app')

@section('title', 'SIS 3.0 | Nueva Cuenta')

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

            <!-- Aviso: formulario operativo -->
            <div class="alert alert-success d-flex align-items-center py-2 px-3 small mb-4 section-card-cuenta-empresarial">
                <i data-feather="check-circle" class="me-2"></i>
                <span>Este formulario está activo y tus datos se guardarán en el sistema al enviarlos.</span>
            </div>
            <script>
                feather.replace(); // Asegura que Feather Icons se rendericen correctamente
            </script>

            <!-- ╭━━━━━━━━━━━━━━━━━━ Formulario principal ━━━━━━━━━━━━━━╮ -->
            <form id="clienteForm" action="{{ route('clientes.store') }}" method="POST" autocomplete="off">
                @csrf

                <!-- Valores por defecto / lógica de negocio -->
                <input type="hidden" name="ciclo_venta" value="cotizacion">
                <input type="hidden" name="estatus" value="activo">
                <input type="hidden" name="tipo" value="erp"><!-- alta desde ERP -->

                <!-- ╭━━━━━━━━━━ Datos Generales ━━━━━━━━━━╮ -->
                <div class="card shadow-sm mb-4 section-card section-card-cuenta-empresarial">
                    <div class="card-header section-card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Cuenta Empresarial</h5>
                        <div class="d-flex align-items-center">
                            <label for="nombre" class="form-label me-2 mb-0">Asignado a: <span
                                    class="text-danger">*</span></label>

                            <select name="id_vendedor" id="id_vendedor" class="form-select form-select-sm" style="width:auto;"
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
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="nombre" class="form-label">
                                    Nombre de la Empresa <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nombre" id="nombre"
                                    class="form-control @error('nombre') is-invalid  @enderror" value="{{ old('nombre') }}"
                                    required>
                                @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label for="sector" class="form-label">Sector <span class="text-danger">*</span></label>
                                <select name="sector" id="sector" class="form-select" required>
                                    <option value="" selected>-- Selecciona -- </option>
                                    <option value="privada">Empresa Privada</option>
                                    <option value="gobierno">Empresa Gobierno</option>
                                </select>
                                @error('sector')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
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
                        </div>
                    </div>
                </div>

                <!-- ╭━━━━━━━━━━ Múltiples Contactos Principales ━━━━━━━━━━╮ -->
                <div class="card shadow-sm mb-4 section-card">
                    <div class="card-header section-card-header">
                        <h6>Contactos Principales</h6>
                    </div>
                    <!-- Contenedor de Contactos -->
                    <div class="card-body section-card-body">
                        <div class="row g-3 contenedorContactos" id="contenedorContactos">

                            <!-- FICHA DE CONTACTO (índice 0 en este ejemplo) -->
                            <div class="col-12 col-sm-6 col-md-4 mb-4 contacto-block" data-index="0">
                                <div class="card h-100">
                                    <div class="card-header position-relative">
                                        <span>Contacto 1</span>
                                        <button type="button" class="btn-close position-absolute top-0 end-0 eliminar-contacto"
                                            aria-label="Eliminar"></button>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-2">
                                            <!-- Nombre(s) -->
                                            <div class="col-sm-12">
                                                <label for="contacto[0][nombre]" class="form-label">Nombre(s) *</label>
                                                <input type="text" class="form-control" name="contacto[0][nombre]" required>
                                            </div>
                                            <!-- Primer Apellido -->
                                            <div class="col-sm-6">
                                                <label for="contacto[0][apellido_p]" class="form-label">Primer Apellido</label>
                                                <input type="text" class="form-control" name="contacto[0][apellido_p]">
                                            </div>
                                            <!-- Segundo Apellido -->
                                            <div class="col-sm-6">
                                                <label for="contacto[0][apellido_m]" class="form-label">Segundo Apellido</label>
                                                <input type="text" class="form-control" name="contacto[0][apellido_m]">
                                            </div>
                                            <!-- Correo Electrónico -->
                                            <div class="col-sm-12">
                                                <label for="contacto[0][email]" class="form-label">Correo Electrónico</label>
                                                <input type="email" class="form-control" name="contacto[0][email]">
                                            </div>
                                            <!-- Puesto -->
                                            <div class="col-sm-12">
                                                <label for="contacto[0][puesto]" class="form-label">Puesto *</label>
                                                <input type="text" class="form-control" name="contacto[0][puesto]" required>
                                            </div>
                                            <!-- Teléfono 1 -->
                                            <div class="col-sm-6">
                                                <label for="contacto[0][telefono1][0]" class="form-label">Teléfono 1 *</label>
                                                <input type="text" class="form-control" name="contacto[0][telefono1]" required>
                                            </div>
                                            <!-- Extensión -->
                                            <div class="col-sm-6">
                                                <label for="contacto[0][ext1][0]" class="form-label">Ext.</label>
                                                <input type="text" class="form-control" name="contacto[0][ext1]">
                                            </div>

                                        </div>

                                        <div class="telefonos-principal-extra"></div>
                                        <button type="button"
                                            class="btn btn-outline-secondary btn-sm w-100 mt-3 agregar-telefono">
                                            <i class="fa fa-plus me-2"></i>Agregar teléfono
                                        </button>

                                    </div>
                                </div>
                            </div>

                            <!-- Tu “tile” dentro del grid -->
                            <div class="col-12 col-sm-6 col-md-4 mb-4">
                                <div class="card tile-agregar-contacto h-100 d-flex align-items-center justify-content-center">
                                    <i class="fa fa-plus fa-2x"></i>
                                    <p class="mt-2">Agregar Contacto</p>
                                </div>
                            </div>

                        </div>
                    </div>


                </div>
                <!-- ╰━━━━━━━━━━━━━━━ Fin Contactos Principales ━━━━━━━━━━━━━━━╯ -->

                <!-- ╭━━━━━━━━━━ Datos de Entrega ━━━━━━━━━━╮ -->
                <div class="card shadow-sm mb-4 section-card">
                    <div class="card-header section-card-header">
                        <h6>Datos de Entrega</h6>
                    </div>
                    <div class="card-body section-card-body">
                        <div class="row g-3 contenedorEntregas" id="contenedorEntregas">

                            <!-- FICHA INICIAL de Datos de Entrega (índice 0) -->
                            <div class="col-12 col-md-6 mb-4 entrega-block border rounded p-3 bg-light-subtle" data-index="0">
                                <!-- Encabezado -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0 text-muted">Datos de Entrega 1</h6>
                                    <button type="button" class="btn-close eliminar-entrega" aria-label="Eliminar"></button>
                                </div>

                                <!-- ╭━━━━ Contacto de Entrega ━━━━╮ -->
                                <h6 class="mb-3">Contacto de Entrega</h6>
                                <div class="row g-3 mt-1">
                                    <div class="col-md-12">
                                        <label for="direcciones_entrega[0][contacto][nombre]"
                                            class="form-label">Nombre(s)</label>
                                        <input type="text" name="direcciones_entrega[0][contacto][nombre]"
                                            id="entrega_contacto_nombre_0"
                                            class="form-control @error('direcciones_entrega.0.contacto.nombre') is-invalid @enderror"
                                            value="{{ old('direcciones_entrega.0.contacto.nombre') }}">
                                        @error('direcciones_entrega.0.contacto.nombre')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="direcciones_entrega[0][contacto][apellido_p]" class="form-label">Primer
                                            apellido</label>
                                        <input type="text" name="direcciones_entrega[0][contacto][apellido_p]"
                                            id="entrega_contacto_apellido_p_0"
                                            class="form-control @error('direcciones_entrega.0.contacto.apellido_p') is-invalid @enderror"
                                            value="{{ old('direcciones_entrega.0.contacto.apellido_p') }}">
                                        @error('direcciones_entrega.0.contacto.apellido_p')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="direcciones_entrega[0][contacto][apellido_m]" class="form-label">Segundo
                                            apellido</label>
                                        <input type="text" name="direcciones_entrega[0][contacto][apellido_m]"
                                            id="entrega_contacto_apellido_m_0" class="form-control"
                                            value="{{ old('direcciones_entrega.0.contacto.apellido_m') }}">
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-md-12">
                                        <label for="direcciones_entrega[0][contacto][email]" class="form-label">Correo
                                            electrónico</label>
                                        <input type="email" name="direcciones_entrega[0][contacto][email]"
                                            id="entrega_contacto_email_0"
                                            class="form-control @error('direcciones_entrega.0.contacto.email') is-invalid @enderror"
                                            value="{{ old('direcciones_entrega.0.contacto.email') }}">
                                        @error('direcciones_entrega.0.contacto.email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-md-6">
                                        <label class="form-label">Teléfono 1</label>
                                        <input type="text" name="direcciones_entrega[0][contacto][telefono1]"
                                            class="form-control @error('direcciones_entrega.0.contacto.telefono1') is-invalid @enderror"
                                            value="{{ old('direcciones_entrega.0.contacto.telefono1') }}">
                                        @error('direcciones_entrega.0.contacto.telefono1')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Ext.</label>
                                        <input type="text" name="direcciones_entrega[0][contacto][ext1]" class="form-control"
                                            value="{{ old('direcciones_entrega.0.contacto.ext1') }}">
                                    </div>
                                </div>

                                <!-- Contenedor para telefonos 2–5 -->
                                <div class="telefonos-extra-0"></div>

                                <!-- Botón “Agregar teléfono” -->
                                <button type="button"
                                    class="btn btn-outline-secondary btn-sm w-100 mt-3 agregar-telefono-entrega" data-index="0">
                                    <i class="fa fa-plus me-2"></i>Agregar teléfono
                                </button>
                                <!-- ╰━━━━ Fin Contacto de Entrega ━━━━╯ -->

                                <hr class="my-4">

                                <!-- Dirección de Entrega -->
                                <h6 class="mb-3">Dirección de Entrega</h6>
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label">Razón Social</label>
                                        <input type="text" name="direcciones_entrega[0][nombre]" class="form-control"
                                            value="{{ old('direcciones_entrega.0.nombre') }}">
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-md-6">
                                        <label class="form-label">Calle</label>
                                        <input type="text" name="direcciones_entrega[0][calle]" class="form-control"
                                            value="{{ old('direcciones_entrega.0.calle') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Num. ext.</label>
                                        <input type="text" name="direcciones_entrega[0][num_ext]" class="form-control"
                                            value="{{ old('direcciones_entrega.0.num_ext') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Num. int.</label>
                                        <input type="text" name="direcciones_entrega[0][num_int]" class="form-control"
                                            value="{{ old('direcciones_entrega.0.num_int') }}">
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-md-6">
                                        <label class="form-label">Colonia</label>
                                        <select name="direcciones_entrega[0][colonia]" class="form-select colonia-select"
                                            disabled>
                                            <option value="">— Selecciona CP primero —</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Ciudad / Municipio</label>
                                        <select name="direcciones_entrega[0][id_ciudad]" class="form-select municipio-field"
                                            disabled>
                                            <option value="">— Selecciona CP primero—</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row g-3 mt-1">
                                    <div class="col-md-4">
                                        <label class="form-label">Estado</label>
                                        <select name="direcciones_entrega[0][id_estado]" class="form-select estado-field"
                                            disabled>
                                            <option value="">— Selecciona CP primero —</option>
                                            @foreach ($estados as $id => $state)
                                                <option value="{{ $id }}" @selected(old('direcciones_entrega.0.id_estado') == $id)>
                                                    {{ $state }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                   <div class="col-md-4">
                                        <label class="form-label">País</label>
                                        <select name="direcciones_entrega[0][id_pais]" class="form-select" disabled>
                                            <option value="">México</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">C.P.</label>
                                        <input type="text" name="direcciones_entrega[0][cp]" class="form-control cp-field"
                                            maxlength="5" value="{{ old('direcciones_entrega.0.cp') }}">
                                    </div>
                                </div>  
                            </div>


                            <div class="col-12 col-md-6 mb-2">
                                <div class="card tile-agregar-entrega h-100 d-flex align-items-center justify-content-center">
                                    <i class="fa fa-plus fa-2x"></i>
                                    <p class="mt-2">Agregar Datos de Entrega</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- ╰━━━━ Fin Dirección de ENTREGA ━━━━╯ -->

                <!-- ╭━━━━ Datos de Facturación (razón social + dirección) ━━━━╮ -->
                <div class="card shadow-sm mb-4 section-card">
                    <div class="card-header section-card-header">
                        <h6>Datos de Facturación</h6>
                    </div>
                    <div class="card-body section-card-body">
                        <div class="row g-3 contenedorFacturacion" id="contenedorFacturacion">

                        <!-- Bloque inicial (índice 0) -->
                        <div class="col-12 col-md-6 mb-4 facturacion-block border rounded p-3 bg-light-subtle"
                            data-index="0">
                            <!-- Encabezado de ficha -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 text-muted">Razón Social 1</h6>
                            <button type="button" class="btn-close eliminar-facturacion" aria-label="Eliminar"></button>
                            </div>

                            <!-- Datos básicos -->
                            <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Razón social</label>
                                <input type="text" name="razones[0][nombre]" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label">RFC</label>
                                <input type="text" name="razones[0][rfc]" class="form-control">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Método de pago</label>
                                <select name="razones[0][id_metodo_pago]" class="form-select">
                                <option value="">— Selecciona —</option>
                                @foreach ($metodos_pago as $id => $metodo)
                                    <option value="{{ $id }}" @selected(old('id_metodo_pago'))>
                                        {{ $metodo }}
                                    </option>
                                @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Forma de pago</label>
                                <select name="razones[0][id_forma_pago]" class="form-select">
                                <option value="">— Selecciona —</option>
                                @foreach ($formas_pago as $id => $forma)
                                    <option value="{{ $id }}" @selected(old('id_forma_pago'))>
                                        {{ $forma }}
                                    </option>
                                @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Uso del CFDI</label>
                                <select name="razones[0][id_uso_cfdi]" class="form-select">
                                <option value="">— Selecciona —</option>
                                @foreach ($usos_cfdi as $id => $uso)
                                    <option value="{{ $id }}" @selected(old('id_uso_cfdi'))>
                                        {{ $uso }}
                                    </option>
                                @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Régimen fiscal</label>
                                <select name="razones[0][id_regimen_fiscal]" class="form-select">
                                <option value="">— Selecciona —</option>
                                @foreach ($regimen_fiscales as $id => $regimen)
                                    <option value="{{ $id }}" @selected(old('id_regimen_fiscal'))>
                                        {{ $regimen }}
                                    </option>
                                @endforeach
                                </select>
                            </div>
                            </div>

                            <hr class="my-4">

                            <!-- Dirección de facturación -->
                            <div class="row g-3 mb-3">
                            <div class="col-3">
                                <label class="form-label">C.P.</label>
                                <input type="text"
                                    name="razones[0][cp]"
                                    class="form-control cp-field"
                                    maxlength="5">
                            </div>
                            </div>
                            <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label">Calle</label>
                                <input type="text" name="razones[0][direccion][calle]" class="form-control">
                            </div>
                            <div class="col-3">
                                <label class="form-label">Num. ext.</label>
                                <input type="text" name="razones[0][direccion][num_ext]" class="form-control">
                            </div>
                            <div class="col-3">
                                <label class="form-label">Num. int.</label>
                                <input type="text" name="razones[0][direccion][num_int]" class="form-control">
                            </div>
                            </div>
                            <div class="row g-3">
                            <div class="col-4">
                                <label class="form-label">Colonia</label>
                                <select name="razones[0][direccion][colonia]" class="form-select colonia-select" disabled>
                                <option>— Selecciona CP primero —</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <label class="form-label">Ciudad / Municipio</label>
                                <select name="razones[0][direccion][id_ciudad]" class="form-select municipio-field" disabled>
                                <option>— Selecciona CP primero —</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <label class="form-label">Estado</label>
                                <select name="razones[0][direccion][id_estado]" class="form-select estado-field" disabled>
                                <option>— Selecciona CP primero —</option>
                                </select>
                            </div>
                            </div>
                        </div>

                        <!-- Tile “Agregar razón social” -->
                        <div class="col-12 col-md-6 mb-4">
                            <div class="card tile-agregar-facturacion h-100 d-flex align-items-center justify-content-center">
                            <i class="fa fa-plus fa-2x"></i>
                            <p class="mt-2">Agregar razón social</p>
                            </div>
                        </div>

                        </div>
                    </div>
                </div>


            </form>
        </div>

        <script>
            // Agregar contactos al formulario de nueva cuenta
            // Solo se permite agregar hasta 10 contactos
            document.addEventListener('DOMContentLoaded', () => {
                const MAX_CONTACTS = 10;
                const MAX_PHONES = 5;

                const contenedor = document.getElementById('contenedorContactos');
                let contactIndex = contenedor.querySelectorAll('.contacto-block').length;

                // Helper: crea la ficha completa para el índice dado
                function createContactoBlock(idx) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'col-12 col-sm-6 col-md-4 mb-4 contacto-block';
                    wrapper.dataset.index = idx;
                    wrapper.innerHTML = `
                    <div class="card h-100">
                        <div class="card-header position-relative">
                        <span>Contacto ${idx + 1}</span>
                        <button type="button"
                                class="btn-close position-absolute top-0 end-0 eliminar-contacto"
                                aria-label="Eliminar"></button>
                        </div>
                        <div class="card-body">
                        <div class="row g-2">
                            <div class="col-sm-12">
                            <label class="form-label">Nombre(s) *</label>
                            <input type="text"
                                    name="contacto[${idx}][nombre]"
                                    class="form-control"
                                    required>
                            </div>
                            <div class="col-sm-6">
                            <label class="form-label">Primer Apellido</label>
                            <input type="text"
                                    name="contacto[${idx}][apellido1]"
                                    class="form-control">
                            </div>
                            <div class="col-sm-6">
                            <label class="form-label">Segundo Apellido</label>
                            <input type="text"
                                    name="contacto[${idx}][apellido2]"
                                    class="form-control">
                            </div>
                            <div class="col-sm-12">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email"
                                    name="contacto[${idx}][email]"
                                    class="form-control">
                            </div>
                            <div class="col-sm-12">
                            <label class="form-label">Puesto *</label>
                            <input type="text"
                                    name="contacto[${idx}][puesto]"
                                    class="form-control"
                                    required>
                            </div>
                            <div class="col-sm-6">
                            <label class="form-label">Teléfono 1 *</label>
                            <input type="text"
                                    name="contacto[${idx}][telefono1]"
                                    class="form-control"
                                    required>
                            </div>
                            <div class="col-sm-6">
                            <label class="form-label">Ext.</label>
                            <input type="text"
                                    name="contacto[${idx}][ext1]"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="telefonos-principal-extra"></div>
                        <button type="button"
                                class="btn btn-outline-secondary btn-sm w-100 mt-3 agregar-telefono">
                            <i class="fa fa-plus me-2"></i>Agregar teléfono
                        </button>
                        </div>
                    </div>`;
                    return wrapper;
                }

                // Delegación de click en todo el grid
                contenedor.addEventListener('click', e => {
                    // 1) Tile “Agregar Contacto”
                    const tile = e.target.closest('.tile-agregar-contacto');
                    if (tile) {
                        if (contactIndex >= MAX_CONTACTS) return;
                        const newBlock = createContactoBlock(contactIndex);
                        // Insertar antes del propio tile
                        const tileCol = tile.closest('.col-12, .col-sm-6, .col-md-4');
                        contenedor.insertBefore(newBlock, tileCol);
                        contactIndex++;
                        return;
                    }

                    // 2) Eliminar Contacto
                    const btnDel = e.target.closest('.eliminar-contacto');
                    if (btnDel) {
                        btnDel.closest('.contacto-block').remove();
                        return;
                    }

                    // 3) Agregar teléfono dentro de un contacto
                    const btnTel = e.target.closest('.agregar-telefono');
                    if (btnTel) {
                        const bloque = btnTel.closest('.contacto-block');
                        const idx = bloque.dataset.index;
                        const wrap = bloque.querySelector('.telefonos-principal-extra');
                        if (!wrap) return;

                        const existing = bloque.querySelectorAll(
                            `input[name^="contacto[${idx}][telefono"]`
                        );
                        if (existing.length >= MAX_PHONES) return;

                        const num = existing.length + 1;
                        const row = document.createElement('div');
                        row.className = 'row g-2 mt-2 telefono-block';
                        row.innerHTML = `
                        <div class="col-sm-6">
                        <label class="form-label">Teléfono ${num}</label>
                        <input type="text"
                                name="contacto[${idx}][telefono${num}]"
                                class="form-control">
                        </div>
                        <div class="col-sm-6">
                        <label class="form-label">Ext.</label>
                        <input type="text"
                                name="contacto[${idx}][ext${num}]"
                                class="form-control">
                        </div>`;
                        wrap.appendChild(row);
                    }
                });
            });
        </script>

        <script>
            //Agregar ficha datos de entrega al formulario de nueva cuenta
            document.addEventListener('DOMContentLoaded', () => {
                const MAX_ENTREGAS = 10;
                const contenedorE = document.getElementById('contenedorEntregas');
                const btnAddE = document.getElementById('agregarEntrega');

                // Índice inicial: cuántas fichas ya haya
                let entregaIndex = contenedorE.querySelectorAll('.entrega-block').length;

                // Función que genera el DOM de una ficha completa
                
                function createEntregaBlock(idx) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'col-12 col-md-6 mb-4 entrega-block border rounded p-3 bg-light-subtle';
                    wrapper.dataset.index = idx;
                    wrapper.innerHTML = `
                        <!-- Encabezado -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 text-muted">Datos de Entrega ${idx + 1}</h6>
                        <button type="button" class="btn-close eliminar-entrega" aria-label="Eliminar"></button>
                        </div>

                        <!-- Contacto de Entrega -->
                        <h6 class="mb-3">Contacto de Entrega</h6>
                        <div class="row g-3 mt-1">
                        <div class="col-md-12">
                            <label class="form-label">Nombre(s)</label>
                            <input type="text"
                                name="direcciones_entrega[${idx}][contacto][nombre]"
                                class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Primer apellido</label>
                            <input type="text"
                                name="direcciones_entrega[${idx}][contacto][apellido_p]"
                                class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Segundo apellido</label>
                            <input type="text"
                                name="direcciones_entrega[${idx}][contacto][apellido_m]"
                                class="form-control">
                        </div>
                        </div>
                        <div class="row g-3 mt-1">
                        <div class="col-md-12">
                            <label class="form-label">Correo electrónico</label>
                            <input type="email"
                                name="direcciones_entrega[${idx}][contacto][email]"
                                class="form-control">
                        </div>
                        </div>
                        <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <label class="form-label">Teléfono 1</label>
                            <input type="text"
                                name="direcciones_entrega[${idx}][contacto][telefono1]"
                                class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Ext.</label>
                            <input type="text"
                                name="direcciones_entrega[${idx}][contacto][ext1]"
                                class="form-control">
                        </div>
                        </div>

                        <!-- Contenedor para teléfonos extra -->
                        <div class="telefonos-extra-${idx}"></div>
                        <button type="button"
                                class="btn btn-outline-secondary btn-sm w-100 mt-3 agregar-telefono-entrega"
                                data-index="${idx}">
                        <i class="fa fa-plus me-2"></i>Agregar teléfono
                        </button>

                        <hr class="my-4">

                        <!-- Dirección de Entrega -->
                        <h6 class="mb-3">Dirección de Entrega</h6>
                        <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Razón Social</label>
                            <input type="text"
                                name="direcciones_entrega[${idx}][nombre]"
                                class="form-control">
                        </div>
                        </div>
                        <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Calle</label>
                            <input type="text"
                                name="direcciones_entrega[${idx}][calle]"
                                class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Num. ext.</label>
                            <input type="text"
                                name="direcciones_entrega[${idx}][num_ext]"
                                class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Num. int.</label>
                            <input type="text"
                                name="direcciones_entrega[${idx}][num_int]"
                                class="form-control">
                        </div>
                        </div>
                        <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Colonia</label>
                            <select name="direcciones_entrega[${idx}][colonia]"
                                    class="form-select colonia-select"
                                    disabled>
                            <option>— Selecciona CP primero —</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Ciudad / Municipio</label>
                            <select name="direcciones_entrega[${idx}][id_ciudad]"
                                    class="form-select municipio-field"
                                    disabled>
                            <option>— Selecciona CP primero —</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Estado</label>
                            <select name="direcciones_entrega[${idx}][id_estado]"
                                    class="form-select estado-field"
                                    disabled>
                            <option>— Selecciona CP primero —</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">C.P.</label>
                            <input type="text"
                                name="direcciones_entrega[${idx}][cp]"
                                class="form-control cp-field"
                                maxlength="5">
                        </div>
                        </div>`;
                    return wrapper;
                    }



                // Delegación dentro de contenedorEntregas
                contenedorE.addEventListener('click', e => {
                    // a) Tile “Agregar Entrega”
                    if (e.target.closest('.tile-agregar-entrega')) {
                        if (entregaIndex >= MAX_ENTREGAS) return;
                        const newBlock = createEntregaBlock(entregaIndex);
                        const tileCol = e.target.closest('.col-12, .col-md-6');
                        contenedorE.insertBefore(newBlock, tileCol);
                        entregaIndex++;
                        return;
                    }

                    // b) Eliminar ficha de entrega
                    if (e.target.closest('.eliminar-entrega')) {
                        e.target.closest('.entrega-block').remove();
                        return;
                    }

                    // c) Agregar teléfono extra en entrega
                    if (e.target.closest('.agregar-telefono-entrega')) {
                        const btn = e.target.closest('.agregar-telefono-entrega');
                        const idx = btn.dataset.index;
                        const wrap = document.querySelector(`.telefonos-extra-${idx}`);
                        const exist = wrap.querySelectorAll(`input[name^="direcciones_entrega[${idx}][telefono"]`);
                        if (exist.length >= 5) return;
                        const num = exist.length + 1;
                        const row = document.createElement('div');
                        row.className = 'row g-2 mt-2 telefono-block';
                        row.innerHTML = `
                        <div class="col-sm-6">
                        <label class="form-label">Teléfono ${num}</label>
                        <input type="text"
                                name="direcciones_entrega[${idx}][telefono${num}]"
                                class="form-control">
                        </div>
                        <div class="col-sm-6">
                        <label class="form-label">Ext.</label>
                        <input type="text"
                                name="direcciones_entrega[${idx}][ext${num}]"
                                class="form-control">
                        </div>`;
                        wrap.appendChild(row);
                    }
                });
            });
        </script>

        <script>
            //Agregar teléfonos de contacto al formulario de nueva cuenta
            // Solo se permite agregar hasta 5 teléfonos
            document.addEventListener('DOMContentLoaded', function () {
                const maxPhones = 5;
                const wrapper = document.getElementById('telefonosContacto');
                const btn = document.getElementById('agregarTelefono');

                btn.addEventListener('click', () => {
                    // Cuenta cuántos inputs de teléfono ya existen
                    const existing = document.querySelectorAll('input[name^="contacto[telefono"]').length;
                    if (existing >= maxPhones) return;

                    const next = existing + 1;
                    // Crea el bloque de fila para teléfono N
                    const row = document.createElement('div');
                    row.className = 'row g-3 mt-1 telefono-block';
                    row.innerHTML = `
                                <div class="col-md-6">
                                    <label class="form-label">Teléfono ${next}</label>
                                    <input
                                    name="contacto[telefono${next}]"
                                    class="form-control"
                                    >
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Ext.</label>
                                    <input
                                    name="contacto[ext${next}]"
                                    class="form-control"
                                    >
                                </div>
                                `;
                    wrapper.appendChild(row);
                });
            });
        </script>

        <script>
            //Tile para datos de facturación
            document.addEventListener('DOMContentLoaded', () => {
            const MAX_FACT = 5;
            const contF    = document.getElementById('contenedorFacturacion');
            const btnF     = document.getElementById('agregarFacturacion');
            let factIndex  = contF.querySelectorAll('.facturacion-block').length;

            function createFacturacionBlock(idx) {
                const wrap = document.createElement('div');
                wrap.className = 'col-12 col-md-6 mb-4 facturacion-block border rounded p-3 bg-light-subtle';
                wrap.dataset.index = idx;
                wrap.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 text-muted">Razón Social ${idx + 1}</h6>
                    <button type="button" class="btn-close eliminar-facturacion" aria-label="Eliminar"></button>
                </div>
                <div class="row g-3">
                    <div class="col-12">
                    <label class="form-label">Razón social</label>
                    <input type="text" name="razones[${idx}][nombre]" class="form-control">
                    </div>
                    <div class="col-12">
                    <label class="form-label">RFC</label>
                    <input type="text" name="razones[${idx}][rfc]" class="form-control">
                    </div>
                    <div class="col-6">
                    <label class="form-label">Método de pago</label>
                    <select name="razones[${idx}][id_metodo_pago]" class="form-select">
                        <option value="">— Selecciona —</option>
                    </select>
                    </div>
                    <div class="col-6">
                    <label class="form-label">Forma de pago</label>
                    <select name="razones[${idx}][id_forma_pago]" class="form-select">
                        <option value="">— Selecciona —</option>
                    </select>
                    </div>
                    <div class="col-6">
                    <label class="form-label">Uso del CFDI</label>
                    <select name="razones[${idx}][id_uso_cfdi]" class="form-select">
                        <option value="">— Selecciona —</option>
                    </select>
                    </div>
                    <div class="col-6">
                    <label class="form-label">Régimen fiscal</label>
                    <select name="razones[${idx}][id_regimen_fiscal]" class="form-select">
                        <option value="">— Selecciona —</option>
                    </select>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row g-3 mb-3">
                    <div class="col-3">
                    <label class="form-label">C.P.</label>
                    <input type="text"
                            name="razones[${idx}][cp]"
                            class="form-control cp-field"
                            maxlength="5">
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-6">
                    <label class="form-label">Calle</label>
                    <input type="text" name="razones[${idx}][direccion][calle]" class="form-control">
                    </div>
                    <div class="col-3">
                    <label class="form-label">Num. ext.</label>
                    <input type="text" name="razones[${idx}][direccion][num_ext]" class="form-control">
                    </div>
                    <div class="col-3">
                    <label class="form-label">Num. int.</label>
                    <input type="text" name="razones[${idx}][direccion][num_int]" class="form-control">
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-4">
                    <label class="form-label">Colonia</label>
                    <select name="razones[${idx}][direccion][colonia]"
                            class="form-select colonia-select" disabled>
                        <option>— Selecciona CP primero —</option>
                    </select>
                    </div>
                    <div class="col-4">
                    <label class="form-label">Ciudad / Municipio</label>
                    <select name="razones[${idx}][direccion][id_ciudad]"
                            class="form-select municipio-field" disabled>
                        <option>— Selecciona CP primero —</option>
                    </select>
                    </div>
                    <div class="col-4">
                    <label class="form-label">Estado</label>
                    <select name="razones[${idx}][direccion][id_estado]"
                            class="form-select estado-field" disabled>
                        <option>— Selecciona CP primero —</option>
                    </select>
                    </div>
                </div>`;
                return wrap;
            }

            contF.addEventListener('click', e => {
                // a) Tile “Agregar razón social”
                if (e.target.closest('.tile-agregar-facturacion')) {
                if (factIndex >= MAX_FACT) return;
                const block = createFacturacionBlock(factIndex);
                const tileCol = e.target.closest('.col-12, .col-md-6');
                contF.insertBefore(block, tileCol);
                factIndex++;
                return;
                }
                // b) Eliminar ficha
                if (e.target.closest('.eliminar-facturacion')) {
                e.target.closest('.facturacion-block').remove();
                }
            });
            });
            </script>


        <script>
            (() => {
                /* ──────────────────── utilidades ──────────────────── */
                const delay = (fn, ms = 400) => {           // debounce
                    let t; return (...a) => { clearTimeout(t); t = setTimeout(() => fn(...a), ms); };
                };

                /** Selecciona por texto (case-insensitive).  
                    Si no existe la opción, la crea y la marca. */
                function seleccionarPorTexto(select, texto) {
                    if (!select) return;

                    // 1. busca coincidencia exacta (ignorando mayúsculas / tildes sencillas)
                    let opt = [...select.options].find(o =>
                        o.text.trim().toLowerCase() === texto.trim().toLowerCase()
                    );

                    // 2. si no, busca que value == texto (por si envías la clave numérica)
                    if (!opt) opt = [...select.options].find(o => o.value === texto);

                    // 3. si sigue sin haber coincidencia, crea opción temporal
                    if (!opt) {
                        opt = new Option(texto, texto, true, true); // selected = true
                        select.prepend(opt);
                    }

                    select.value = opt.value;
                }

                /** Deja el bloque “vacío” cuando el CP es inválido */
                function limpiar(bloque) {
                    bloque.querySelectorAll('.estado-field, .municipio-field').forEach(s => s.value = '');
                    const sel = bloque.querySelector('.colonia-select');
                    if (sel) {
                        sel.innerHTML = '<option value="">— Selecciona CP primero —</option>';
                        sel.disabled = true;
                    }
                }

                /* ──────────────────── listener principal ──────────────────── */
                document.addEventListener('input', delay(async e => {
                    const cpInput = e.target.closest('.cp-field');
                    if (!cpInput) return;

                    const cp = cpInput.value.trim();
                    const bloque = cpInput.closest('.entrega-block,.facturacion-block') ?? cpInput.parentNode;

                    if (!/^\d{5}$/.test(cp)) { limpiar(bloque); return; }

                    try {
                        const r = await fetch(`/api/cp/${cp}`);
                        if (!r.ok) throw new Error(`CP ${cp} sin datos`);
                        const data = await r.json();

                        // select correspondientes dentro del mismo bloque
                        const estadoSel = bloque.querySelector('.estado-field');
                        const municSel = bloque.querySelector('.municipio-field');
                        const coloniaSel = bloque.querySelector('.colonia-select');

                        // Estado y Municipio
                        seleccionarPorTexto(estadoSel, data.head.estado);
                        seleccionarPorTexto(municSel, data.head.municipio);

                        // Colonias
                        if (coloniaSel) {
                            coloniaSel.innerHTML = '';
                            data.colonias.forEach(c =>
                                coloniaSel.add(new Option(`${c.colonia} (${c.tipo})`, c.colonia))
                            );
                            coloniaSel.disabled = false;
                        }

                        console.log('🔄 Autocompletado',
                            { estado: estadoSel?.value, municipio: municSel?.value, colonias: coloniaSel?.length });

                    } catch (err) {
                        console.error(err);
                        limpiar(bloque);
                    }
                }));
            })();
        </script>


        <!-- ━━━━━━━━━━━━━━━━━━━━━━━━━━ TerminaFormulario para Personas Morales ━━━━━━━━━━━━━━━━━━━━━━━━ -->

    @elseif ($tipo === 'fisica')

    @endif

@endsection