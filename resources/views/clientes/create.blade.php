@extends('layouts.app')

@section('title', 'SIS 3.0 | Nueva Cuenta')

@section('content')
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('inicio') }}">Inicio</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('clientes.create') }}">Nueva Cuenta</a></li>
    @endsection
    @if ($tipo === 'moral')

        <!-- ╭━━━━━━━━━━━━━━━━━━━━━━━━━━ Formulario para Empresas ━━━━━━━━━━━━━━━━━━━━━━━━╮ -->
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
                    <div class="card shadow-sm mb-4 section-card section-card-cuenta-empresarial">
                        <div class="card-header section-card-header text-center">
                            <h5 class="mb-0">Cuenta Empresarial</h5>
                        </div>

                        <div class="card-body">
                            {{-- ── DATOS DE LA EMPRESA ─────────────────────────── --}}
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Nombre de la Empresa <span class="text-danger">*</span></label>
                                    <input  id="nombre" name="nombre" type="text"
                                            class="form-control @error('nombre') is-invalid @enderror"
                                            value="{{ old('nombre') }}" required minlength="3" maxlength="120">
                                    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label">Asignado a: <span class="text-danger">*</span></label>
                                    <select name="id_vendedor" class="form-select" required>
                                        <option value="">-- Ejecutivo --</option>
                                        <option value="" @selected(old('id_vendedor')==='')>Base General</option>
                                        @foreach($vendedores as $v)
                                            <option value="{{ $v->id_usuario }}" @selected(old('id_vendedor')==$v->id_usuario)>
                                                {{ $v->username }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label">Sector <span class="text-danger">*</span></label>
                                    <select name="sector" class="form-select" required>
                                        <option value="">-- Selecciona --</option>
                                        <option value="privada">Empresa Privada</option>
                                        <option value="gobierno">Empresa Gobierno</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
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

                            {{-- ── CONTACTO PRINCIPAL ─────────────────────────── --}}
                            <h6 class="fw-semibold mb-3">Contacto principal</h6>

                            <div class="row g-2 contacto-block" data-index="0">

                                {{-- Nombre(s) / Apellidos --}}
                                <div class="col-sm-4">
                                    <label class="form-label">Nombre(s)</label>
                                    <input  name="contacto[0][nombre]" class="form-control" minlength="2" maxlength="60">
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label">Primer Apellido</label>
                                    <input  name="contacto[0][apellido_p]" class="form-control" maxlength="27">
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label">Segundo Apellido</label>
                                    <input  name="contacto[0][apellido_m]" class="form-control" maxlength="27">
                                </div>

                                {{-- Email / Puesto / Género --}}
                                <div class="col-sm-4">
                                    <label class="form-label">Correo Electrónico</label>
                                    <input name="contacto[0][email]" type="email" class="form-control" maxlength="120">
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label">Puesto</label>
                                    <input name="contacto[0][puesto]" class="form-control" maxlength="100">
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label">Género</label>
                                    <select name="contacto[0][genero]" class="form-select">
                                        <option value="">-- Selecciona --</option>
                                        <option value="masculino">Masculino</option>
                                        <option value="femenino">Femenino</option>
                                        <option value="no-especificado">No especificado</option>
                                    </select>
                                </div>

                                {{-- Teléfono / Extensión base --}}
                                <div class="col-sm-4">
                                <label class="form-label">Teléfono&nbsp;1</label>
                                <input name="contacto[0][telefono1]" class="form-control phone-field" maxlength="14">
                                </div>
                                <div class="col-sm-2">
                                <label class="form-label">Ext.</label>
                                <input name="contacto[0][ext1]" class="form-control" maxlength="7">
                                </div>
                                <div class="col-sm-1 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-secondary w-100 agregar-tel-ext">
                                    <i class="fa fa-plus"></i>
                                </button>
                                </div>

                                {{-- Celular base --}}
                                <div class="col-sm-4">
                                <label class="form-label">Teléfono Celular&nbsp;1</label>
                                <input name="contacto[0][celular1]" class="form-control phone-field" maxlength="14">
                                </div>
                                <div class="col-sm-1 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-secondary w-100 agregar-celular">
                                    <i class="fa fa-plus"></i>
                                </button>
                                </div>

                                <!-- NUEVOS contenedores -->
                                <div class="row g-2 w-100 mt-3"><!-- g-2 para igual separación -->
                                    <div class="col-md-6">
                                        <div class="telefonos-tel-ext"><!-- Teléfono + Ext 2-5 --></div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="celulares-extra"><!-- Celular 2-5 --></div>
                                    </div>
                                </div>



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

            <!-- Aviso: formulario inactivo -->
            <div class="alert alert-warning d-flex align-items-center py-2 px-3 small mb-4 section-card-cuenta-empresarial">
                <i data-feather="alert-circle" class="me-2"></i>
                <span>Este formulario está inactivo y tus datos no se guardarán en el sistema al enviarlos.</span>
            </div>
            <script>
                feather.replace(); // Asegura que Feather Icons se rendericen correctamente
            </script>

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
                    <div class="card shadow-sm mb-4 section-card section-card-cuenta-empresarial contacto-block">
                        <div class="card-header section-card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Cuenta Personal</h5>
                            <div class="col-md-4">
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
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="nombre" class="form-label">
                                        Nombre(s) <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="nombre" id="nombre"
                                        class="form-control @error('nombre') is-invalid  @enderror" value="{{ old('nombre') }}"
                                        required minlength="3" maxlength="120">
                                    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="apellido_p" class="form-label">
                                        Primer Apellido
                                    </label>
                                    <input type="text" name="apellido_p" id="apellido_p"
                                        class="form-control @error('apellido_p') is-invalid  @enderror" value="{{ old('apellido_p') }}"
                                        required minlength="3" maxlength="120">
                                    @error('apellido_p')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="apellido_m" class="form-label">
                                        Segundo Apellido
                                    </label>
                                    <input type="text" name="apellido_m" id="apellido_m"
                                        class="form-control @error('apellido_m') is-invalid  @enderror" value="{{ old('apellido_m') }}"
                                        minlength="3" maxlength="120">
                                    @error('apellido_m')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <!-- Correo Electrónico -->
                                <div class="col-sm-4">
                                    <label for="contacto[0][email]" class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control" name="contacto[0][email]" maxlength="120">
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

                                <!-- Género -->
                                <div class="col-sm-4">
                                    <label for="contacto[0][genero]" class="form-label">Género</label>
                                    <select name="contacto[0][genero]" id="contacto[0][genero]" class="form-select">
                                        <option value="">-- Selecciona --</option>
                                        <option value="masculino">Masculino</option>
                                        <option value="femenino">Femenino</option>
                                        <option value="no-especificado">No Especificado</option>
                                    </select>
                                </div>
                                
                                <!-- Teléfono 1 -->
                                <div class="col-sm-4">
                                    <label for="contacto[0][telefono1]" class="form-label">Teléfono 1</label>
                                    <input type="text" maxlength="14" class="form-control phone-field" name="contacto[0][telefono1]" title="Número de 10 dígitos"> 
                                </div>
                                <!-- Extensión -->
                                <div class="col-sm-2">
                                    <label for="contacto[0][ext1]" class="form-label">Ext.</label>
                                    <input type="text" maxlength="7" class="form-control" name="contacto[0][ext1]">
                                </div>
                                <!-- Celular 1 -->
                                <div class="col-sm-4">
                                    <label class="form-label">Teléfono Celular 1</label>
                                    <input type="text"  class="form-control phone-field" name="contacto[0][celular1]"
                                            maxlength="14" title="Celular de 10 dígitos" id="celular">
                                </div>

                                <div class="col-sm-2 mt-4">
                                    <button type="button"
                                        class="btn btn-outline-secondary btn-sm agregar-telefono">
                                        <i class="fa fa-plus me-2"></i>Agregar teléfono
                                    </button>
                                </div>

                                <div class="telefonos-principal-extra"></div>

                            </div>

                        </div>
                    </div>

                </div>
            </form>
        </div>
    @endif

    {{-- phones.blade snippet v2 --}}
    
<script>
document.addEventListener('DOMContentLoaded', () => {

    /* ── Hace funcionar los botones [+] en clientes.create ───────────────────────────── */
        /* Teléfono + Ext (fila = 12 col) */
        const tplTel = (idx,n) => `
        <div class="row g-2 telefono-extra-row">
            <div class="col-sm-8">
                <label class="form-label">Teléfono ${n}</label>
                <input name="contacto[${idx}][telefono${n}]"
                    class="form-control phone-field" maxlength="14">
            </div>
            <div class="col-sm-3">
                <label class="form-label">Ext.</label>
                <input name="contacto[${idx}][ext${n}]"
                    class="form-control" maxlength="7">
            </div>
            <div class="col-sm-1 d-flex align-items-end">
                <button type="button" class="btn btn-link text-danger p-0 quitar-fila"
                        aria-label="Eliminar Teléfono ${n}">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>`;

        /* Celular (fila = 12 col) */
        const tplCel = (idx,n) => `
        <div class="row g-2 celular-extra-row">
            <div class="col-sm-8">
                <label class="form-label">Teléfono Celular ${n}</label>
                <input name="contacto[${idx}][celular${n}]"
                    class="form-control phone-field" maxlength="14">
            </div>
            <div class="col-sm-1 d-flex align-items-end">
                <button type="button" class="btn btn-link text-danger p-0 quitar-fila"
                        aria-label="Eliminar Celular ${n}">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>`;


    /* ── RESTO DEL SCRIPT (botones + / trash, validaciones, etc.) ───────── */
    const MAX = 5;

    document.addEventListener('click', e => {
        /* botón para Teléfono+Ext */
        if (e.target.closest('.agregar-tel-ext')) {
            const block = e.target.closest('.contacto-block');
            const idx   = block.dataset.index;
            const wrap  = block.querySelector('.telefonos-tel-ext');
            const filas = wrap.querySelectorAll('.telefono-extra-row').length;

            if (filas >= MAX-1) return;      // ya hay 2-5
            wrap.insertAdjacentHTML('beforeend', tplTel(idx, filas+2));
            return;
        }

        /* botón para Celular */
        if (e.target.closest('.agregar-celular')) {
            const block = e.target.closest('.contacto-block');
            const idx   = block.dataset.index;
            const wrap  = block.querySelector('.celulares-extra');
            const filas = wrap.querySelectorAll('.celular-extra-row').length;

            if (filas >= MAX-1) return;      // ya hay 2-5
            wrap.insertAdjacentHTML('beforeend', tplCel(idx, filas+2));
            return;
        }

        /* papelera */
        if (e.target.closest('.quitar-fila')) {
            e.target.closest('.row')?.remove();
        }
    });

});
</script>



    <script>
        document.addEventListener('input', e => {
            const input = e.target.closest('.phone-field');
            if (!input) return;                        // Sólo teléfonos/celulares

            /* ---- Normaliza --------------------------------------------------- */
            let digits = input.value.replace(/\D/g,'');   // quita todo salvo dígitos
            if (digits.length > 10) digits = digits.slice(0, 10);

            /* ---- Formatea ---------------------------------------------------- */
            let pretty = digits;
            if (digits.length === 10) {
                if (digits.startsWith('55')) {          // Celular CDMX
                    pretty = digits.replace(/(\d{2})(\d{4})(\d{4})/, '($1)-$2-$3');
                } else {                               // Fijo nacional 3-3-4
                    pretty = digits.replace(/(\d{3})(\d{3})(\d{4})/, '($1)-$2-$3');
                }
            } else if (digits.length >= 7) {            // Mientras escriben (parcial)
                pretty = digits.replace(/(\d{3})(\d{0,3})(\d{0,4})/,
                                        (_,a,b,c)=> a + (b?'-'+b:'') + (c?'-'+c:''));
            }

            input.value = pretty;
            /* ---- Sincroniza atributo "maxlength" para evitar +10 dígitos ------ */
            input.maxLength = pretty.startsWith('(55)') ? 14 : 15; // ()---------

            /* ---- HTML5 validity (pattern) ------------------------------------ */
            input.setCustomValidity(digits.length === 10 ? '' : 'Número incompleto');
        });
    </script>

@endsection