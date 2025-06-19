@extends('layouts.app')

@section('title', 'SIS 3.0 | Nueva Cuenta')

@section('content')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('inicio') }}">Inicio</a></li>
    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('clientes.create') }}">Nueva Cuenta</a></li>
@endsection
@if ($tipo === 'moral')

    <!-- ╭━━━━━━━━━━━━━━━━━━━━━━━━━━ Formulario para Empresas ━━━━━━━━━━━━━━━━━━━━━━━━╮ -->
    <div class="container-fluid">
        <h2 class="mb-3">Nueva Cuenta Empresarial</h2>
        <!-- ╭━━━━━━━━━━━━━━━━━━ Botonera superior ━━━━━━━━━━━━━━━━━╮ -->
        <div class="row-fluid gap-2 mb-3">
            <a href="{{ url()->previous() }}" class="btn btn-secondary col-md-2">
                <i class="fa fa-arrow-left me-1"></i> Regresar
            </a>

            <button form="clienteForm" type="submit" class="btn btn-success col-md-2">
                <i class="fa fa-save me-1"></i> Guardar
            </button>

            <a href="{{ route('clientes.index') }}" class="btn btn-primary col-md-2">
                <i class="fa fa-list me-1"></i> Mis Cuentas
            </a>

        </div>

        <!-- ╭━━━━━━━━━━━━━━━━━━ Formulario principal ━━━━━━━━━━━━━━╮ -->
        <form id="clienteForm" action="{{ route('clientes.store') }}" method="POST" autocomplete="off">
            <div class="form-wrapper">

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
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <h5 class="mb-0 text-subtitulo">Cuenta Empresarial</h5>
                    </div>

                    <div class="card-body">
                        {{-- ── DATOS DE LA EMPRESA ─────────────────────────── --}}
                        <div class="row gx-3 gy-2 mb-4">
                            <div class="col-md-4">
                                <label class="form-label text-normal">Nombre de la Empresa <span
                                        class="text-danger">*</span></label>
                                <input id="nombre" name="nombre" type="text"
                                    class="form-control guarda-mayus @error('nombre') is-invalid @enderror"
                                    value="{{ old('nombre') }}" required minlength="3" maxlength="45">
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-2">
                                <label class="form-label text-normal">Asignado a: <span
                                        class="text-danger">*</span></label>
                                <select name="id_vendedor" class="form-select" required>
                                    <option value="" disabled selected>-- Selecciona --</option>
                                    <option value="" @selected(old('id_vendedor') === '')>Base General</option>
                                    @foreach ($vendedores as $v)
                                        <option value="{{ $v->id_usuario }}" @selected(old('id_vendedor') == $v->id_usuario)>
                                            {{ $v->nombre }} {{ $v->apellido_p }} {{ $v->apellido_m }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label text-normal">Sector <span class="text-danger">*</span></label>
                                <select name="sector" class="form-select" required>
                                    <option value="" disabled selected>-- Selecciona --</option>
                                    <option value="privada">Empresa Privada</option>
                                    <option value="gobierno">Empresa Gobierno</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label text-normal">Segmento <span
                                        class="text-danger">*</span></label>
                                <select name="segmento" class="form-select" required>
                                    <option value="" disabled selected>-- Selecciona --</option>
                                    <option value="macasa cuentas especiales">Macasa Cuentas Especiales</option>
                                    <option value="macasa ecommerce">Macasa E-commerce</option>
                                    <option value="tekne store ecommerce">Tekne Store E-commerce</option>
                                    <option value="la plaza ecommerce">La Plaza E-commerce</option>
                                </select>
                            </div>
                        </div>
                        <hr>

                        {{-- ── CONTACTO PRINCIPAL ─────────────────────────── --}}
                        <h6 class="fw-semibold mb-3 text-normal">Contacto principal</h6>

                        <div class="row g-3 contacto-block" data-index="0">

                            {{-- Nombre(s) / Apellidos --}}
                            <div class="col-md-4">
                                <label class="form-label text-normal">Nombre(s) <span
                                        class="text-danger">*</span></label>
                                <input name="contacto[0][nombre]" class="form-control guarda-mayus" minlength="2"
                                    maxlength="45" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-normal">Primer Apellido <span
                                        class="text-danger">*</span></label>
                                <input name="contacto[0][apellido_p]" class="form-control guarda-mayus" maxlength="27"
                                    required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-normal">Segundo Apellido <span
                                        class="text-danger">*</span></label>
                                <input name="contacto[0][apellido_m]" class="form-control guarda-mayus" maxlength="27"
                                    required>
                            </div>
                        </div>
                        {{-- Email / Puesto / Género --}}
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label text-normal">Correo Electrónico <span
                                        class="text-danger">*</span></label>
                                <input name="contacto[0][email]" type="email" class="form-control guarda-minus"
                                    maxlength="50" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-normal">Puesto <span
                                        class="text-danger">*</span></label>
                                <input name="contacto[0][puesto]" class="form-control guarda-mayus" maxlength="20"
                                    required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-normal">Género <span
                                        class="text-danger">*</span></label>
                                <select name="contacto[0][genero]" class="form-select" required>
                                    <option value="" disabled selected>-- Selecciona --</option>
                                    <option value="masculino">Masculino</option>
                                    <option value="femenino">Femenino</option>
                                    <option value="no-especificado">No especificado</option>
                                </select>
                            </div>
                        </div>
                        {{-- Contacto Principal ─ Teléfonos EMPRESARIAL --}}
                        <div id="telefonos-cel-wrapper"> {{-- ⬅️ NUEVO --}}

                            <div class="row">
                                {{-- Teléfonos fijos --}}
                                <div class="col-md-4" id="telefonos-col"
                                    style="padding-right: 0 !important;">
                                    <div class="mb-2 telefono-item">
                                        <label class="text-normal">Teléfono 1 <span
                                                class="text-danger">*</span></label>

                                        <div class="input-group input-group-separated">
                                            <input type="text" name="contacto[0][telefono1]"
                                                class="form-control phone-field" placeholder="Teléfono"
                                                style="min-width: 16ch; max-width: 16ch;" required>
                                            <input type="text" name="contacto[0][ext1]"
                                                class="form-control ext-field div-10ch" placeholder="Ext."
                                                maxlength="7">
                                            <button type="button"
                                                class="btn btn-outline-primary agregar-telefono btn-field">+</button>
                                        </div>
                                    </div>
                                </div>

                                {{-- Celulares --}}
                                <div class="col campo-dato-secundario" id="celulares-col">
                                    <div class="mb-2 celular-item">
                                        <label class="text-normal">Teléfono Celular 1</label>

                                        <div class="input-group input-group-separated">
                                            <input type="text" name="contacto[0][celular1]"
                                                class="form-control phone-field" placeholder="Celular"
                                                style="min-width: 16ch; max-width: 16ch;">
                                            <button type="button"
                                                class="btn btn-outline-primary agregar-celular btn-field">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>{{-- /telefonos-cel-wrapper --}}
                    </div> <!-- /card-body -->

                </div>

                <!-- ─────────────────────────────── Fin tarjeta ─────────────────────────────── -->

            </div>
        </form>
    </div>

    <!-- ━━━━━━━━━━━━━━━━━━━━━━━━━━ TerminaFormulario para Personas Morales ━━━━━━━━━━━━━━━━━━━━━━━━ -->
@elseif ($tipo === 'fisica')
    <!-- ╭━━━━━━━━━━━━━━━━━━━━━━━━━━ Formulario para Personas Fisicas ━━━━━━━━━━━━━━━━━━━━━━━━╮ -->
    <div class="container-fluid">
        <h2 class="mb-3">Nueva Cuenta Personal</h2>
        <!-- ╭━━━━━━━━━━━━━━━━━━ Botonera superior ━━━━━━━━━━━━━━━━━╮ -->
        <div class="row-fluid gap-2 mb-3">
            <a href="{{ url()->previous() }}" class="col-md-2 btn btn-secondary btn-principal">
                <i class="fa fa-arrow-left me-1"></i> Regresar
            </a>

            <button form="clienteForm" type="submit" class="col-md-2 btn btn-success btn-principal">
                <i class="fa fa-save me-1"></i> Guardar
            </button>

            <a href="{{ route('clientes.index') }}" class="col-md-2 btn btn-primary btn-principal">
                <i class="fa fa-list me-1"></i> Mis Cuentas
            </a>

        </div>

        <!-- ╭━━━━━━━━━━━━━━━━━━ Formulario principal ━━━━━━━━━━━━━━╮ -->
        <form id="clienteForm" action="{{ route('clientes.store') }}" method="POST" autocomplete="off">
            <div class="form-wrapper">

                @csrf

                <!-- Valores por defecto / lógica de negocio -->
                <input type="hidden" name="ciclo_venta" value="cotizacion">
                <input type="hidden" name="estatus" value="activo">
                <input type="hidden" name="tipo" value="erp"><!-- alta desde ERP -->
                <input type="hidden" name="sector" value="persona"><!-- Sector: gobierno, privada, persona -->

                <!-- ╭━━━━━━━━━━ Datos Generales ━━━━━━━━━━╮ -->
                <div class="card mb-4 contacto-block">
                    <div class="card-header text-center">
                        <h5 class="mb-0 text-subtitulo">Cuenta Personal</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Nombre(s) del contacto = Nombre de la Cuenta -->
                            <div class="col-md-4">
                                <label for="nombre" class="form-label text-normal">
                                    Nombre(s) <span class="text-danger">*</span>
                                </label>
                                <input name="nombre" type="text" id="nombre"
                                    class="form-control guarda-mayus" value="{{ old('nombre') }}" required
                                    minlength="3" maxlength="40">
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Apellido 1 -->
                            <div class="col-md-4">
                                <label for="apellido_p" class="form-label text-normal">
                                    Primer Apellido <span class="text-danger">*</span>
                                </label>
                                <input name="apellido_p" type="text" id="apellido_p"
                                    class="form-control guarda-mayus" value="{{ old('apellido_p') }}" required
                                    minlength="3" maxlength="27">
                                @error('apellido_p')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Apellido 2 -->
                            <div class="col-md-4">
                                <label for="apellido_m" class="form-label text-normal">
                                    Segundo Apellido <span class="text-danger">*</span>
                                </label>
                                <input name="apellido_m" type="text" id="apellido_m"
                                    class="form-control guarda-mayus" value="{{ old('apellido_m') }}" required
                                    minlength="3" maxlength="27">
                                @error('apellido_m')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div><!-- /row g-3 -->
                        <div class="row">
                            <!-- Correo Electrónico -->
                            <div class="col-md-4">
                                <label for="email" class="form-label text-normal">Correo Electrónico <span
                                        class="text-danger">*</span></label>
                                <input name="email" type="email" class="form-control guarda-minus"
                                    value="{{ old('email') }}" maxlength="40" required>
                            </div>
                            <!-- Segmento -->
                            <div class="col-md-2">
                                <label for="segmento" class="form-label text-normal">Segmento <span
                                        class="text-danger">*</span></label>
                                <select name="segmento" id="segmento" class="form-select" required>
                                    <option value="" disabled selected>-- Selecciona --</option>
                                    <option value="macasa cuentas especiales" @selected(old('segmento') == 'macasa cuentas especiales')>
                                        Macasa Cuentas Especiales
                                    </option>
                                    <option value="tekne store ecommerce" @selected(old('segmento') == 'tekne store ecommerce')>
                                        Tekne Store E-Commerce
                                    </option>
                                    <option value="la plaza ecommerce" @selected(old('segmento') == 'la plaza ecommerce')>
                                        LaPlazaEnLinea E-Commerce
                                    </option>
                                </select>
                                @error('segmento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Género -->
                            <div class="col-md-2">
                                <label for="genero" class="form-label text-normal">Género <span
                                        class="text-danger">*</span></label>
                                <select name="genero" id="genero" class="form-select" required>
                                    <option value="" disabled selected>-- Selecciona --</option>
                                    <option value="masculino" @selected(old('genero') == 'masculino')>Masculino</option>
                                    <option value="femenino" @selected(old('genero') == 'femenino')>Femenino</option>
                                    <option value="no-especificado" @selected(old('genero') == 'no-especificado')>No Especificado
                                    </option>
                                </select>
                            </div>
                            <!-- Asignado a / id_vendedor -->
                            <div class="col-md-2">
                                <label for="nombre" class="form-label text-normal">Asignado a: <span
                                        class="text-danger">*</span></label>
                                <select name="id_vendedor" id="id_vendedor" class="form-select" style=""
                                    required>
                                    <option value="" disabled selected>-- Selecciona --</option>

                                    {{-- Base General = NULL --}}
                                    <option value="" @selected(old('id_vendedor') === '')>Base General</option>
                                    @foreach ($vendedores as $vendedor)
                                        <option value="{{ $vendedor->id_usuario }}" @selected(old('id_vendedor') == $vendedor->id_usuario)>
                                            {{ $vendedor->username }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_vendedor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div> <!-- row para correo-segmento-genero-asignado a -->
                        {{-- Contacto Principal ─ Teléfonos PERSONAL --}}
                        <div id="telefonos-cel-wrapper"> {{-- ⬅️ NUEVO --}}
                            <div class="row">
                                {{-- Teléfonos fijos --}}
                                <div class="col-md-4" id="telefonos-col">
                                    <div class="mb-2 telefono-item">
                                        <label class="text-normal">Teléfono 1 <span
                                                class="text-danger">*</span></label>

                                        <div class="input-group input-group-separated">
                                            <input name="contacto[0][telefono1]" type="text"
                                                class="form-control phone-field"
                                                value="{{ old('contacto.0.telefono1') }}" placeholder="Teléfono"
                                                style="min-width: 16ch; max-width: 16ch;" required>
                                            @error('contacto.0.telefono1')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <input name="contacto[0][ext1]" type="text"
                                                class="form-control ext-field div-10ch"
                                                value="{{ old('contacto.0.ext1') }}" placeholder="Ext."
                                                maxlength="7">
                                            @error('contacto.0.ext1')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <button type="button"
                                                class="btn btn-outline-primary agregar-telefono btn-field">+</button>
                                        </div>
                                    </div>
                                </div>

                                {{-- Celulares --}}
                                <div class="col-md-4" id="celulares-col">
                                    <div class="mb-2 celular-item">
                                        <label class="text-normal">Teléfono Celular 1</label>
                                        <div class="input-group input-group-separated">
                                            <input name="contacto[0][celular1]" type="text"
                                                class="form-control phone-field"
                                                value="{{ old('contacto.0.celular1') }}" placeholder="Celular"
                                                style="min-width: 16ch; max-width: 16ch;">
                                            @error('contacto.0.celular1')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <button type="button"
                                                class="btn btn-outline-primary agregar-celular btn-field">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>{{-- /telefonos-cel-wrapper --}}
                    </div> <!-- /card-body -->
                </div> <!-- /card-->
            </div><!-- /form-wrapper -->
        </form>
    </div>
@endif



<script>
    document.addEventListener('DOMContentLoaded', function() {
        const MAX = 5;
        const telCol = document.getElementById('telefonos-col');
        const celCol = document.getElementById('celulares-col');

        // Reindexa filas después de añadir/borrar
        function reindex(tipo) {
            const selector = tipo === 'telefono' ? '.telefono-item' : '.celular-item';
            document.querySelectorAll(selector).forEach((item, i) => {
                const idx = i + 1;
                const label = item.querySelector('label');
                if (tipo === 'telefono') {
                    const [inpTel, inpExt] = item.querySelectorAll('input');
                    inpTel.name = `contacto[0][telefono${idx}]`;
                    inpExt.name = `contacto[0][ext${idx}]`;
                    label.textContent = `Teléfono ${idx}`;
                } else {
                    const inpCel = item.querySelector('input');
                    inpCel.name = `contacto[0][celular${idx}]`;
                    label.textContent = `Teléfono Celular ${idx}`;
                }
            });
        }

        // Muestra un pequeño aviso que desaparece
        function showMsg(msj, col) {
            const id = col + '-msg';
            let box = document.getElementById(id);
            if (!box) {
                box = document.createElement('small');
                box.id = id;
                box.className = 'text-danger';
                (col === 'telefonos' ? telCol : celCol).appendChild(box);
            }
            box.textContent = msj;
            setTimeout(() => box.textContent = '', 3000);
        }

        // Añadir teléfono
        document.querySelector('.agregar-telefono').addEventListener('click', function() {
            if (telCol.querySelectorAll('.telefono-item').length >= MAX) {
                return showMsg('Solo puedes agregar hasta 5 teléfonos.', 'telefonos');
            }
            const wrapper = document.createElement('div');
            wrapper.className = 'mb-2 telefono-item';
            wrapper.innerHTML = `
                <label></label>
                <div class="input-group input-group-separated">
                    <input type="text" class="form-control phone-field" placeholder="Teléfono" style="min-width:16ch; max-width:16ch" required>
                    <input type="text" class="form-control ext-field div-10ch"   placeholder="Ext." maxlength="7">
                    <button type="button" class="btn btn-outline-danger eliminar-item btn-field">X</button>
                </div>
                `;

            telCol.appendChild(wrapper);
            reindex('telefono');
        });

        // Añadir celular
        document.querySelector('.agregar-celular').addEventListener('click', function() {
            if (celCol.querySelectorAll('.celular-item').length >= MAX) {
                return showMsg('Solo puedes agregar hasta 5 celulares.', 'celulares');
            }
            const wrapper = document.createElement('div');
            wrapper.className = 'mb-2 celular-item';
            wrapper.innerHTML = `
                <label></label>
                <div class="input-group input-group-separated">
                    <input type="text" class="form-control phone-field" style="min-width:16ch; max-width:16px;" placeholder="Celular">
                    <button type="button" class="btn btn-outline-danger eliminar-item btn-field">X</button>
                </div>
                `;

            celCol.appendChild(wrapper);
            reindex('celular');
        });

        // Borrar elemento y reindexar
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('eliminar-item')) {
                const item = e.target.closest('.telefono-item, .celular-item');
                const parentId = item.parentElement.id;
                item.remove();
                if (parentId === 'telefonos-col') reindex('telefono');
                else if (parentId === 'celulares-col') reindex('celular');
            }
        });

        // Reindex inicial (por si hay valores viejos)
        reindex('telefono');
        reindex('celular');
    });
</script>



@endsection
