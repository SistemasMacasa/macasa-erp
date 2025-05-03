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
            <div class="alert alert-success py-2 px-3 small mb-4">
                <i class="fa fa-check-circle me-1"></i>
                ¡Listo! Este formulario ya está activo y tus datos se guardarán en el sistema al enviarlos.
            </div>

            <!-- ╭━━━━━━━━━━━━━━━━━━ Formulario principal ━━━━━━━━━━━━━━╮ -->
            <form id="clienteForm" action="{{ route('clientes.store') }}" method="POST" autocomplete="off">
                @csrf

                <!-- Valores por defecto / lógica de negocio -->
                <input type="hidden" name="ciclo_venta" value="cotizacion">
                <input type="hidden" name="estatus" value="activo">
                <input type="hidden" name="tipo" value="erp"><!-- alta desde ERP -->

                <!-- ╭━━━━━━━━━━ Datos Generales ━━━━━━━━━━╮ -->
                <div class="card shadow-sm mb-4 section-card">
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
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">
                                    Nombre de la Empresa <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nombre" id="nombre"
                                    class="form-control @error('nombre') is-invalid  @enderror" value="{{ old('nombre') }}"
                                    required>
                                @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3">
                                <label for="sector" class="form-label">Sector <span class="text-danger">*</span></label>
                                <select name="sector" id="sector" class="form-select" required>
                                    <option value="" selected>-- Selecciona -- </option>
                                    <option value="privada">Empresa Privada</option>
                                    <option value="gobierno">Empresa Gobierno</option>
                                </select>
                                @error('sector')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                                <label for="contacto[nombre]" class="form-label">Nombre(s) <span
                                        class="text-danger">*</span></label>
                                <input name="contacto[nombre]" class="form-control" value="{{ old('contacto.nombre') }}"
                                    required>
                                @error('contacto.nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label for="contacto[apellido_p]" class="form-label">Primer Apellido</label>
                                <input name="contacto[apellido_p]" class="form-control"
                                    value="{{ old('contacto.apellido_p') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="contacto[apellido_m]" class="form-label">Segundo Apellido</label>
                                <input name="contacto[apellido_m]" class="form-control"
                                    value="{{ old('contacto.apellido_m') }}">
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-4">
                                <label for="contacto[email]" class="form-label">Correo Electrónico</label>
                                <input name="contacto[email]" class="form-control" value="{{ old('contacto.email') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="contacto[puesto]" class="form-label">Puesto <span
                                        class="text-danger">*</span></label>
                                <input name="contacto[puesto]" class="form-control" value="{{ old('contacto.puesto') }}"
                                    required>
                                @error('contacto.puesto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-3">
                                <label for="contacto[telefono1]" class="form-label">Teléfono 1 <span
                                        class="text-danger">*</span></label>
                                <input name="contacto[telefono1]" class="form-control" value="{{ old('contacto.telefono1') }}"
                                    required>
                                @error('contacto.telefono1')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-1">
                                <label for="contacto[ext1]" class="form-label">Ext.</label>
                                <input name="contacto[ext1]" class="form-control" value="{{ old('contacto.ext1') }}">
                            </div>
                        </div>
                        <!-- CONTENEDOR PARA LOS TELÉFONOS ADICIONALES -->
                        <div id="telefonosContacto"></div>

                        <!-- BOTÓN PARA AGREGAR TELÉFONO -->
                        <button type="button" id="agregarTelefono" class="btn btn-sm btn-outline-primary mt-3">
                            <i class="fa fa-plus"></i> Agregar teléfono
                        </button>

                    </div>
                </div>

                <!-- ╭━━━━ Dirección de ENTREGA (múltiples) ━━━━╮ -->
                <div class="card shadow-sm mb-4 section-card">
                    <div class="card-header section-card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Datos de Entrega</h6>
                        <button type="button" id="agregarDireccionEntrega" class="btn btn-sm btn-outline-primary">
                            <i class="fa fa-plus"></i> Agregar otra
                        </button>
                    </div>

                    <div class="card-body">
                        <div id="contenedorDireccionesEntrega">
                            <!-- Bloque inicial (índice 0) -->
                            <div class="entrega-block mb-4 border rounded p-3 bg-light-subtle" data-index="0">
                                <h6 class="mb-3 text-muted">Datos de Entrega 1</h6>

                                <!-- ╭━━━━ Contacto de Entrega ━━━━╮ -->
                                <h6 class="mb-3">Contacto de Entrega</h6>

                                <div class="row g-3 mt-1 telefono-contacto-wr">
                                    <div class="col-md-4">
                                        <label for="direcciones_entrega[0][contacto][nombre]" class="form-label">
                                            Nombre(s) <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="direcciones_entrega[0][contacto][nombre]"
                                            id="entrega_contacto_nombre_0"
                                            class="form-control @error('direcciones_entrega.0.contacto.nombre') is-invalid @enderror"
                                            value="{{ old('direcciones_entrega.0.contacto.nombre') }}" required>
                                        @error('direcciones_entrega.0.contacto.nombre')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="direcciones_entrega[0][contacto][apellido_p]" class="form-label">
                                            Primer apellido <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="direcciones_entrega[0][contacto][apellido_p]"
                                            id="entrega_contacto_apellido_p_0"
                                            class="form-control @error('direcciones_entrega.0.contacto.apellido_p') is-invalid @enderror"
                                            value="{{ old('direcciones_entrega.0.contacto.apellido_p') }}" required>
                                        @error('direcciones_entrega.0.contacto.apellido_p')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="direcciones_entrega[0][contacto][apellido_m]" class="form-label">
                                            Segundo apellido
                                        </label>
                                        <input type="text" name="direcciones_entrega[0][contacto][apellido_m]"
                                            id="entrega_contacto_apellido_m_0" class="form-control"
                                            value="{{ old('direcciones_entrega.0.contacto.apellido_m') }}">
                                    </div>
                                </div>
                                <div class="row g-3 mt-1">
                                    <div class="col-md-6">
                                        <label for="direcciones_entrega[0][contacto][email]" class="form-label">
                                            Correo electrónico
                                        </label>
                                        <input type="email" name="direcciones_entrega[0][contacto][email]"
                                            id="entrega_contacto_email_0"
                                            class="form-control @error('direcciones_entrega.0.contacto.email') is-invalid @enderror"
                                            value="{{ old('direcciones_entrega.0.contacto.email') }}">
                                        @error('direcciones_entrega.0.contacto.email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row g-3 mt-1 telefono-contacto-wrapper">
                                    <div class="col-md-4">
                                        <label class="form-label">
                                            Teléfono 1 <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="direcciones_entrega[0][contacto][telefono1]"
                                            class="form-control @error('direcciones_entrega.0.contacto.telefono1') is-invalid @enderror"
                                            value="{{ old('direcciones_entrega.0.contacto.telefono1') }}" required>
                                        @error('direcciones_entrega.0.contacto.telefono1')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Ext.</label>
                                        <input type="text" name="direcciones_entrega[0][contacto][ext1]" class="form-control"
                                            value="{{ old('direcciones_entrega.0.contacto.ext1') }}">
                                    </div>
                                </div>

                                <!-- CONTENEDOR PARA TELÉFONOS 2–5 -->
                                <div class="telefonos-extra"></div>

                                <!-- BOTÓN “+” PARA AGREGAR TELÉFONO (hasta 5) -->
                                <button type="button" class="btn btn-sm btn-outline-primary mt-3 agregar-telefono-contacto">
                                    <i class="fa fa-plus"></i> Agregar teléfono
                                </button>
                                <!-- ╰━━━━ Fin Contacto de Entrega ━━━━╯ -->

                                <hr class="my-4">

                                <h6 class="mb-3">Dirección de Entrega</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Razón Social</label>
                                        <input name="direcciones_entrega[0][nombre]" class="form-control"
                                            value="{{ old('direcciones_entrega.0.nombre') }}">
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Calle</label>
                                        <input name="direcciones_entrega[0][calle]" class="form-control"
                                            value="{{ old('direcciones_entrega.0.calle') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Num. ext.</label>
                                        <input name="direcciones_entrega[0][num_ext]" class="form-control"
                                            value="{{ old('direcciones_entrega.0.num_ext') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Num. int.</label>
                                        <input name="direcciones_entrega[0][num_int]" class="form-control"
                                            value="{{ old('direcciones_entrega.0.num_int') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Colonia</label>
                                        <input name="direcciones_entrega[0][colonia]" class="form-control"
                                            value="{{ old('direcciones_entrega.0.colonia') }}">
                                    </div>
                                </div>
                                <div class="row g-3 mt-1">
                                    <div class="col-md-4">
                                        <label class="form-label">Ciudad / Municipio</label>
                                        <select name="direcciones_entrega[0][id_ciudad]" class="form-select">
                                            <option value="">— Selecciona —</option>
                                            @foreach ($ciudades as $id => $city)
                                                <option value="{{ $id }}" @selected(old('direcciones_entrega.0.id_ciudad') == $id)>
                                                    {{ $city }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Estado</label>
                                        <select name="direcciones_entrega[0][id_estado]" class="form-select">
                                            <option value="">— Selecciona —</option>
                                            @foreach ($estados as $id => $state)
                                                <option value="{{ $id }}" @selected(old('direcciones_entrega.0.id_estado') == $id)>
                                                    {{ $state }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">País</label>
                                        <select name="direcciones_entrega[0][id_pais]" class="form-select">
                                            <option value="">— Selecciona —</option>
                                            @foreach ($paises as $id => $pais)
                                                <option value="{{ $id }}" @selected(old('direcciones_entrega.0.id_pais') == $id)>
                                                    {{ $pais }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">C.P.</label>
                                        <input name="direcciones_entrega[0][cp]" class="form-control"
                                            value="{{ old('direcciones_entrega.0.cp') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ╰━━━━ Fin Dirección de ENTREGA ━━━━╯ -->

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
                                    <div class="col-md-3">
                                        <label class="form-label">Método de pago</label>
                                        <select name="razones[0][id_metodo_pago]" id="id_metodo_pago" class="form-select">
                                            {{-- placeholder: se marca si old) viene vacío --}}
                                            <option value="" @selected(old('id_metodo_pago'))>— Selecciona —</option>

                                            @foreach ($metodos_pago as $id => $metodo)
                                                <option value="{{ $id }}" @selected(old('id_metodo_pago'))>
                                                    {{ $metodo }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Forma de pago</label>
                                        <select name="razones[0][id_forma_pago]" class="form-select">
                                            <option value="" @selected(old('id_forma_pago'))>— Selecciona —</option>

                                            @foreach ($formas_pago as $id => $forma)
                                                <option value="{{ $id }}" @selected(old('id_forma_pago'))>
                                                    {{ $forma }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Uso del CFDI</label>
                                        <select name="razones[0][id_uso_cfdi]" class="form-select">
                                            <option value="" @selected(old('id_uso_cfdi'))>— Selecciona —</option>
                                            @foreach ($usos_cfdi as $id => $uso)
                                                <option value="{{ $id }}" @selected(old('id_uso_cfdi'))>
                                                    {{ $uso }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Régimen fiscal</label>
                                        <select name="razones[0][id_regimen_fiscal]" class="form-select">
                                            <option value="" @selected(old('id_regimen_fiscal'))>— Selecciona —</option>
                                            @foreach ($regimen_fiscales as $id => $regimen)
                                                <option value="{{ $id }}" @selected(old('id_regimen_fiscal'))>
                                                    {{ $regimen }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row g-3 mt-1">
                                    <div class="col-md-2">
                                        <label class="form-label">C.P.</label>
                                        <input name="razones[0][direccion][cp]" class="form-control">
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
                                            <select name="razones[0][direccion][id_ciudad]" class="form-select">
                                                <option value="" @selected(old('id_ciudad'))>— Selecciona —</option>
                                                @foreach ($ciudades as $id => $city)
                                                    <option value="{{ $id }}" @selected(old('id_ciudad'))>
                                                        {{ $city }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Estado</label>
                                            <select name="razones[0][direccion][id_estado]" class="form-select">
                                                <option value="" @selected(old('id_estado'))>— Selecciona —</option>
                                                @foreach ($estados as $id => $state)
                                                    <option value="{{ $id }}" @selected(old('id_estado'))>
                                                        {{ $state }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">País</label>
                                            <select name="razones[0][direccion][id_pais]" class="form-select">
                                                <option value="" @selected(old('id_pais'))>— Selecciona —</option>
                                                @foreach ($paises as $id => $pais)
                                                    <option value="{{ $id }}" @selected(old('razones.0.direccion.id_pais') == $id)>
                                                        {{ $pais }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


            </form>
        </div>

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
                        <div class="col-md-3">
                            <label class="form-label">Teléfono ${next}</label>
                            <input
                            name="contacto[telefono${next}]"
                            class="form-control"
                            >
                        </div>
                        <div class="col-md-1">
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
        // Manejo dinámico de bloques de entrega y teléfonos (hasta 15 bloques de entrega, cada uno con hasta 5 teléfonos)
        document.addEventListener('DOMContentLoaded', () => {
            const MAX_BLOCKS = 15;
            const MAX_PHONES = 5;

            const contenedor = document.getElementById('contenedorDireccionesEntrega');
            const btnAgregar = document.getElementById('agregarDireccionEntrega');
            let bloqueIndex = 1;

            // 1) Delegación de "Agregar teléfono" en cualquier bloque
            contenedor.addEventListener('click', (e) => {
                const btnPhone = e.target.closest('.agregar-telefono-contacto');
                if (!btnPhone) return;

                const block = btnPhone.closest('.entrega-block');
                const idx = block.dataset.index;
                const wrapper = block.querySelector('.telefonos-extra');
                if (!wrapper) return;

                const existentes = block.querySelectorAll(
                    `input[name^="direcciones_entrega[${idx}][contacto][telefono"]`
                ).length;
                if (existentes >= MAX_PHONES) return;

                const next = existentes + 1;
                const row = document.createElement('div');
                row.className = 'row g-3 mt-1 telefono-block';
                row.innerHTML = `
                    <div class="col-md-4">
                        <label class="form-label">Teléfono ${next}</label>
                        <input
                        type="text"
                        name="direcciones_entrega[${idx}][contacto][telefono${next}]"
                        class="form-control"
                        >
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Ext.</label>
                        <input
                        type="text"
                        name="direcciones_entrega[${idx}][contacto][ext${next}]"
                        class="form-control"
                        >
                    </div>
                `;
                wrapper.appendChild(row);
            });

            // 2) Botón "Agregar otra" genera un bloque completo de entrega
            btnAgregar.addEventListener('click', () => {
                if (bloqueIndex >= MAX_BLOCKS) return;

                const idx = bloqueIndex;
                const nuevo = document.createElement('div');
                nuevo.className = 'entrega-block mb-4 border rounded p-3 bg-light-subtle';
                nuevo.dataset.index = idx;

                nuevo.innerHTML = `
                    <h6 class="mb-3 text-muted">Datos de Entrega ${idx + 1}</h6>

                    <!-- Contacto de Entrega -->
                    <h6 class="mb-3">Contacto de Entrega</h6>
                    <div class="row g-3 mt-1">
                        <div class="col-md-4">
                        <label class="form-label">Nombre(s) <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            name="direcciones_entrega[${idx}][contacto][nombre]"
                            class="form-control"
                            required
                        >
                        </div>
                        <div class="col-md-4">
                        <label class="form-label">Apellido Paterno <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            name="direcciones_entrega[${idx}][contacto][apellido_p]"
                            class="form-control"
                            required
                        >
                        </div>
                        <div class="col-md-4">
                        <label class="form-label">Apellido Materno</label>
                        <input
                            type="text"
                            name="direcciones_entrega[${idx}][contacto][apellido_m]"
                            class="form-control"
                        >
                        </div>
                    </div>
                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                        <label class="form-label">Correo Electrónico</label>
                        <input
                            type="email"
                            name="direcciones_entrega[${idx}][contacto][email]"
                            class="form-control"
                        >
                        </div>
                    </div>
                    <!-- Teléfono 1 + Ext 1 -->
                    <div class="row g-3 mt-1 telefono-contacto-wrapper">
                        <div class="col-md-4">
                        <label class="form-label">Teléfono 1 <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            name="direcciones_entrega[${idx}][contacto][telefono1]"
                            class="form-control"
                            required
                        >
                        </div>
                        <div class="col-md-2">
                        <label class="form-label">Ext.</label>
                        <input
                            type="text"
                            name="direcciones_entrega[${idx}][contacto][ext1]"
                            class="form-control"
                        >
                        </div>
                    </div>

                    <!-- Contenedor para teléfonos 2–5 -->
                    <div class="telefonos-extra"></div>

                    <!-- Botón para agregar más teléfonos -->
                    <button
                        type="button"
                        class="btn btn-sm btn-outline-primary mt-3 agregar-telefono-contacto"
                    >
                        <i class="fa fa-plus"></i> Agregar teléfono
                    </button>

                    <hr class="my-4">

                    <!-- Dirección de Entrega -->
                    <h6 class="mb-3">Dirección de Entrega</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                        <label class="form-label">Razón Social</label>
                        <input
                            name="direcciones_entrega[${idx}][nombre]"
                            class="form-control"
                        >
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                        <label class="form-label">Calle</label>
                        <input
                            name="direcciones_entrega[${idx}][calle]"
                            class="form-control"
                        >
                        </div>
                        <div class="col-md-2">
                        <label class="form-label">Num. ext.</label>
                        <input
                            name="direcciones_entrega[${idx}][num_ext]"
                            class="form-control"
                        >
                        </div>
                        <div class="col-md-2">
                        <label class="form-label">Num. int.</label>
                        <input
                            name="direcciones_entrega[${idx}][num_int]"
                            class="form-control"
                        >
                        </div>
                        <div class="col-md-4">
                        <label class="form-label">Colonia</label>
                        <input
                            name="direcciones_entrega[${idx}][colonia]"
                            class="form-control"
                        >
                        </div>
                    </div>
                    <div class="row g-3 mt-1">
                        <div class="col-md-4">
                        <label class="form-label">Ciudad / Municipio</label>
                        <select name="direcciones_entrega[${idx}][id_ciudad]" class="form-select">
                            <option value="">— Selecciona —</option>
                            @foreach($ciudades as $id => $city)
                                <option value="{{ $id }}">{{ $city }}</option>
                            @endforeach
                        </select>
                        </div>
                        <div class="col-md-4">
                        <label class="form-label">Estado</label>
                        <select name="direcciones_entrega[${idx}][id_estado]" class="form-select">
                            <option value="">— Selecciona —</option>
                            @foreach($estados as $id => $state)
                                <option value="{{ $id }}">{{ $state }}</option>
                            @endforeach
                        </select>
                        </div>
                        <div class="col-md-2">
                        <label class="form-label">País</label>
                        <select name="direcciones_entrega[${idx}][id_pais]" class="form-select">
                            <option value="">— Selecciona —</option>
                            @foreach($paises as $id => $pais)
                                <option value="{{ $id }}">{{ $pais }}</option>
                            @endforeach
                        </select>
                        </div>
                        <div class="col-md-2">
                        <label class="form-label">C.P.</label>
                        <input
                            name="direcciones_entrega[${idx}][cp]"
                            class="form-control"
                        >
                        </div>
                    </div>
                `;

                contenedor.appendChild(nuevo);
                bloqueIndex++;
            });
        });
        </script>

        <script>
            //Agregar datos de facturación al formulario de nueva cuenta
            // Solo se permite agregar hasta 10 bloques de facturación
            document.addEventListener('DOMContentLoaded', function () {
                const btnAgregar = document.getElementById('agregarFacturacion');
                const contenedor = document.getElementById('contenedorFacturacion');
                let index = 1;

                btnAgregar.addEventListener('click', function () {
                    if (index >= 10) return;

                    const bloque = contenedor.querySelector('.facturacion-block').cloneNode(true);
                    bloque.querySelector('h6').innerText = `Razón Social ${index + 1}`;

                    // Reemplazar todos los índices [0] por [index]
                    bloque.innerHTML = bloque.innerHTML.replace(/\[0\]/g, `[${index}]`);
                    contenedor.appendChild(bloque);

                    index++;
                });
            });
        </script>
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
                <input type="hidden" name="ciclo_venta" value="cotizacion">
                <input type="hidden" name="estatus" value="activo">
                <input type="hidden" name="tipo" value="erp"><!-- alta desde ERP -->

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
                                <label for="id_vendedor" class="form-label">Asignado a</label>

                                <select name="id_vendedor" id="id_vendedor" class="form-select">
                                    <option value="">— Ejecutiv@ —</option>

                                    <option value="" @selected(old('id_vendedor') === '')>
                                        Base General
                                    </option>

                                    @foreach ($vendedores as $vendedor)
                                        <option value="{{ $vendedor->id_usuario }}"
                                            @selected(old('id_vendedor') == $vendedor->id_usuario)>
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
                                    class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono') }}">
                            </div>
                            <div class="col-md-2">
                                <label for="fuente_contacto" class="form-label>">Ext.</label>
                                <input type="text" name="ext" id="ext" class="form-control @error('ext') is-invalid @enderror"
                                    value="{{ old('ext') }}">
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
                                        <label for="contacto_apellido_p" class="form-label">Apellido paterno</label>
                                        <input name="contacto[apellido_p]" id="contacto_apellido_p" class="form-control"
                                            value="{{ old('contacto.apellido_p') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="contacto_apellido_m" class="form-label">Apellido materno</label>
                                        <input name="contacto[apellido_m]" id="contacto_apellido_m" class="form-control"
                                            value="{{ old('contacto.apellido_m') }}">
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

                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label" for="entrega_condicion">Condición de pago</label>
                                        <select name="direccion_entrega[condicion_pago]" id="entrega_condicion"
                                            class="form-select">
                                            <option value="">—</option>

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
                                        <input name="direccion_entrega[0][calle]" id="entrega_calle" class="form-control"
                                            value="{{ old('direccion_entrega.0.calle') }}">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label" for="entrega_num_ext">Num. ext.</label>
                                        <input name="direccion_entrega[0][num_ext]" id="entrega_num_ext" class="form-control"
                                            value="{{ old('direccion_entrega.num_ext') }}">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label" for="entrega_num_int">Num. int.</label>
                                        <input name="direccion_entrega[0][num_int]" id="entrega_num_int" class="form-control"
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