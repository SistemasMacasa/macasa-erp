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
            <div class="d-flex gap-2 mb-3">
                <a href="{{ url()->previous() }}" class="btn btn-secondary btn-30ch">
                    <i class="fa fa-arrow-left me-1"></i> Regresar
                </a>

                <button form="clienteForm" type="submit" class="btn btn-success btn-30ch">
                    <i class="fa fa-save me-1"></i> Guardar
                </button>

                <a href="{{ route('clientes.index') }}" class="btn btn-primary btn-30ch">
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
                    <div class="card shadow-lg mb-4 section-card section-card-cuenta-empresarial">
                        <div class="card-header section-card-header text-center">
                            <h5 class="mb-0">Cuenta Empresarial</h5>
                        </div>

                        <div class="card-body">
                            {{-- ── DATOS DE LA EMPRESA ─────────────────────────── --}}
                            <div class="row gx-3 gy-2 mb-4">
                                <div class="col div-60ch">
                                    <label class="form-label">Nombre de la Empresa <span class="text-danger">*</span></label>
                                    <input  id="nombre" name="nombre" type="text"
                                            class="form-control guarda-mayus @error('nombre') is-invalid @enderror"
                                            value="{{ old('nombre') }}" required minlength="3" maxlength="45">
                                    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col div-30ch">
                                    <label class="form-label">Asignado a: <span class="text-danger">*</span></label>
                                    <select name="id_vendedor" class="form-select" required>
                                        <option value="">-- Ejecutivo --</option>
                                        <option value="" @selected(old('id_vendedor')==='')>Base General</option>
                                        @foreach($vendedores as $v)
                                            <option value="{{ $v->id_usuario }}" @selected(old('id_vendedor')==$v->id_usuario)>
                                                {{ $v->nombre }} {{ $v->apellido_p }} {{ $v->apellido_m }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col div-30ch">
                                    <label class="form-label">Sector <span class="text-danger">*</span></label>
                                    <select name="sector" class="form-select" required>
                                        <option value="">-- Selecciona --</option>
                                        <option value="privada">Empresa Privada</option>
                                        <option value="gobierno">Empresa Gobierno</option>
                                    </select>
                                </div>

                                <div class="col div-30ch">
                                    <label class="form-label">Segmento <span class="text-danger">*</span></label>
                                    <select name="segmento" class="form-select" required>
                                        <option value="">-- Selecciona --</option>
                                        <option value="macasa cuentas especiales">Macasa Cuentas Especiales</option>
                                        <option value="macasa ecommerce">Macasa E-commerce</option>
                                        <option value="tekne store ecommerce">Tekne Store E-commerce</option>
                                        <option value="la plaza ecommerce">La Plaza E-commerce</option>
                                    </select>
                                </div>
                            </div>
                            <hr>

                            {{-- ── CONTACTO PRINCIPAL ─────────────────────────── --}}
                            <h6 class="fw-semibold mb-3">Contacto principal</h6>

                            <div class="row g-3 contacto-block" data-index="0">

                                {{-- Nombre(s) / Apellidos --}}
                                <div class="col div-60ch">
                                    <label class="form-label">Nombre(s) <span class="text-danger">*</span></label>
                                    <input  name="contacto[0][nombre]" class="form-control guarda-mayus" minlength="2" maxlength="45" required>
                                </div>
                                <div class="col div-60ch">
                                    <label class="form-label">Primer Apellido <span class="text-danger">*</span></label>
                                    <input  name="contacto[0][apellido_p]" class="form-control guarda-mayus" maxlength="27" required>
                                </div>
                                <div class="col div-60ch">
                                    <label class="form-label">Segundo Apellido <span class="text-danger">*</span></label>
                                    <input  name="contacto[0][apellido_m]" class="form-control guarda-mayus" maxlength="27" required>
                                </div>

                                {{-- Email / Puesto / Género --}}
                                <div class="col div-60ch">
                                    <label class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                                    <input name="contacto[0][email]" type="email" class="form-control guarda-minus" maxlength="50" required>
                                </div>
                                <div class="col div-30ch">
                                    <label class="form-label">Puesto <span class="text-danger">*</span></label>
                                    <input name="contacto[0][puesto]" class="form-control guarda-mayus" maxlength="20" required>
                                </div>
                                <div class="col div-30ch">
                                    <label class="form-label">Género <span class="text-danger">*</span></label>
                                    <select name="contacto[0][genero]" class="form-select" required>
                                        <option value="">-- Selecciona --</option>
                                        <option value="masculino">Masculino</option>
                                        <option value="femenino">Femenino</option>
                                        <option value="no-especificado">No especificado</option>
                                    </select>
                                </div>

                                {{-- Contacto Principal ─ Teléfonos EMPRESARIAL --}}
                                <div id="telefonos-cel-wrapper">    {{-- ⬅️ NUEVO --}}

                                    <div class="row">
                                        {{-- Teléfonos fijos --}}
                                        <div class="col div-35ch" id="telefonos-col" style="padding-right: 0 !important;">
                                            <div class="mb-2 telefono-item">
                                                <label>Teléfono 1 <span class="text-danger">*</span></label>

                                                <div class="input-group input-group-separated">
                                                <input type="text"  name="contacto[0][telefono1]" class="form-control phone-field"  placeholder="Teléfono" style="min-width: 16ch; max-width: 16ch;" required>
                                                <input type="text"  name="contacto[0][ext1]"      class="form-control ext-field div-10ch"    placeholder="Ext." maxlength="7">
                                                <button type="button" class="btn btn-outline-primary agregar-telefono btn-field">+</button>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Celulares --}}
                                        <div class="col div-30ch" id="celulares-col">
                                            <div class="mb-2 celular-item">
                                                <label>Teléfono Celular 1</label>

                                                <div class="input-group input-group-separated">
                                                <input type="text" name="contacto[0][celular1]" class="form-control phone-field" placeholder="Celular" style="min-width: 16ch; max-width: 16ch;">
                                                <button type="button" class="btn btn-outline-primary agregar-celular btn-field">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>{{-- /telefonos-cel-wrapper --}}



                            </div>
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
            <div class="d-flex gap-2 mb-3">
                <a href="{{ url()->previous() }}" class="btn btn-secondary btn-30ch">
                    <i class="fa fa-arrow-left me-1"></i> Regresar
                </a>

                <button form="clienteForm" type="submit" class="btn btn-success btn-30ch">
                    <i class="fa fa-save me-1"></i> Guardar
                </button>

                <a href="{{ route('clientes.index') }}" class="btn btn-primary btn-30ch">
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
                    <div class="card shadow-lg mb-4 section-card section-card-cuenta-empresarial contacto-block">
                         <div class="card-header section-card-header text-center">
                            <h5 class="mb-0">Cuenta Personal</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col div-60ch">
                                    <label for="nombre" class="form-label">
                                        Nombre(s) <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="nombre" id="nombre"
                                        class="form-control guarda-mayus @error('nombre') is-invalid  @enderror" value="{{ old('nombre') }}"
                                        required minlength="3" maxlength="40">
                                    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col div-60ch">
                                    <label for="apellido_p" class="form-label">
                                        Primer Apellido <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="apellido_p" id="apellido_p"
                                        class="form-control guarda-mayus @error('apellido_p') is-invalid  @enderror" value="{{ old('apellido_p') }}"
                                        required minlength="3" maxlength="27">
                                    @error('apellido_p')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col div-60ch">
                                    <label for="apellido_m" class="form-label">
                                        Segundo Apellido <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="apellido_m" id="apellido_m"
                                        class="form-control guarda-mayus @error('apellido_m') is-invalid  @enderror" value="{{ old('apellido_m') }}"
                                        required minlength="3" maxlength="27">
                                    @error('apellido_m')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <!-- Correo Electrónico -->
                                <div class="col div-60ch">
                                    <label for="email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control guarda-minus" name="email" maxlength="40" required>
                                </div>

                                <div class="col div-30ch">
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
                                <div class="col div-30ch">
                                    <label for="genero" class="form-label">Género <span class="text-danger">*</span></label>
                                    <select name="genero" id="genero" class="form-select" required>
                                        <option value="">-- Selecciona --</option>
                                        <option value="masculino">Masculino</option>
                                        <option value="femenino">Femenino</option>
                                        <option value="no-especificado">No Especificado</option>
                                    </select>
                                </div>

                                <div class="col div-30ch">
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
                                        <div class="col div-35ch" id="telefonos-col" style="padding-right: 0 !important;">
                                            <div class="mb-2 telefono-item">
                                                <label>Teléfono 1 <span class="text-danger">*</span></label>

                                                <div class="input-group input-group-separated">
                                                <input type="text"  name="contacto[0][telefono1]" class="form-control phone-field"  placeholder="Teléfono" style="min-width: 16ch; max-width: 16ch;" required>
                                                <input type="text"  name="contacto[0][ext1]"      class="form-control ext-field div-10ch"    placeholder="Ext." maxlength="7">
                                                <button type="button" class="btn btn-outline-primary agregar-telefono btn-field">+</button>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Celulares --}}
                                        <div class="col div-30ch" id="celulares-col">
                                            <div class="mb-2 celular-item">
                                                <label>Teléfono Celular 1</label>

                                                <div class="input-group input-group-separated">
                                                <input type="text" name="contacto[0][celular1]" class="form-control phone-field" placeholder="Celular" style="min-width: 16ch; max-width: 16ch;">
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
                if (parentId === 'telefonos-col')    reindex('telefono');
                else if (parentId === 'celulares-col') reindex('celular');
                }
            });

            // Reindex inicial (por si hay valores viejos)
            reindex('telefono');
            reindex('celular');
            });
        </script>



@endsection

