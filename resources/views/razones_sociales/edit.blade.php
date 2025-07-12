@extends('layouts.app')

@section('title', 'Editar Razón Social')

@section('content')
    <div class="container-fluid">

        {{-- 🧭 Migas de pan --}}
        @section('breadcrumb')
            <li class="breadcrumb-item"><a href="{{ route('inicio') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cotizaciones.index') }}">Cotizaciones</a></li>
            <li class="breadcrumb-item active">Editar Razón Social: {{ $razon->nombre }}</li>
        @endsection

        <h2 class="mb-3" style="color: inherit;">Editar Razón Social: {{ $razon->nombre }}</h2>

        {{-- 🎛 Botonera --}}
        <div class="d-flex flex-wrap gap-2 mb-3">
            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-principal col-xxl-2 col-xl-2 col-lg-3">
                <i class="fa fa-arrow-left me-1"></i> Regresar
            </a>
            <a href="{{ route('clientes.index') }}" class="btn btn-primary btn-principal col-xxl-2 col-xl-2 col-lg-3">
                <i class="fa fa-building me-1"></i> Mis cuentas
            </a>
        </div>

        <form method="POST" action="{{ route('razones_sociales.update', $razon->id_razon_social) }}">
            @csrf
            @method('PUT')

            <input type="hidden" name="id_cliente" value="{{ $razon->id_cliente }}">

            <div class="mb-3">
                <label class="form-label">Nombre o Razón Social</label>
                <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $razon->nombre) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">RFC</label>
                <input type="text" name="RFC" class="form-control" value="{{ old('RFC', $razon->RFC) }}" maxlength="13"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label">Notas para Facturación</label>
                <textarea name="notas_facturacion" class="form-control"
                    rows="4">{{ old('notas_facturacion', $razon->notas_facturacion) }}</textarea>
                <small class="text-muted">Estas notas se usarán al emitir CFDI y persisten entre cotizaciones.</small>
            </div>

            <div class="text-end">
                <a href="{{ route('cotizaciones.create', $razon->id_cliente) }}" class="btn btn-secondary">
                    <i class="fa fa-chevron-left me-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-1"></i> Guardar cambios
                </button>
            </div>
            <hr class="my-4">

            {{-- Catálogos Fiscales --}}
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Uso CFDI</label>
                    <select name="id_uso_cfdi" class="form-select" required>
                        @foreach ($usosCfdi as $uso)
                            <option value="{{ $uso->id_uso_cfdi }}" {{ old('id_uso_cfdi', $razon->id_uso_cfdi) == $uso->id_uso_cfdi ? 'selected' : '' }}>
                                {{ $uso->clave }} - {{ $uso->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Método de Pago</label>
                    <select name="id_metodo_pago" class="form-select" required>
                        @foreach ($metodosPago as $metodo)
                            <option value="{{ $metodo->id_metodo_pago }}" {{ old('id_metodo_pago', $razon->id_metodo_pago) == $metodo->id_metodo_pago ? 'selected' : '' }}>
                                {{ $metodo->clave }} - {{ $metodo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Forma de Pago</label>
                    <select name="id_forma_pago" class="form-select" required>
                        @foreach ($formasPago as $forma)
                            <option value="{{ $forma->id_forma_pago }}" {{ old('id_forma_pago', $razon->id_forma_pago) == $forma->id_forma_pago ? 'selected' : '' }}>
                                {{ $forma->clave }} - {{ $forma->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Régimen Fiscal</label>
                    <select name="id_regimen_fiscal" class="form-select" required>
                        @foreach ($regimenesFiscales as $regimen)
                            <option value="{{ $regimen->id_regimen_fiscal }}" {{ old('id_regimen_fiscal', $razon->id_regimen_fiscal) == $regimen->id_regimen_fiscal ? 'selected' : '' }}>
                                {{ $regimen->clave }} - {{ $regimen->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr class="my-4">

            <h5 class="mt-4 mb-2"><i class="fa fa-map-marker-alt me-2 text-danger"></i> Dirección de Facturación</h5>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Calle</label>
                    <input type="text" name="calle" class="form-control"
                        value="{{ old('calle', $razon->direccion_facturacion->calle) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Número exterior</label>
                    <input type="text" name="num_ext" class="form-control"
                        value="{{ old('num_ext', $razon->direccion_facturacion->num_ext) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Número interior</label>
                    <input type="text" name="num_int" class="form-control"
                        value="{{ old('num_int', $razon->direccion_facturacion->num_int) }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Código Postal</label>
                    <input type="text" name="cp" id="cp" class="form-control"
                        value="{{ old('cp', $razon->direccion_facturacion->cp) }}" maxlength="5" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Colonia</label>
                    <select name="id_colonia" id="colonia" class="form-select" required></select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Ciudad</label>
                    <input type="text" class="form-control" id="ciudad" disabled>
                    <input type="hidden" name="id_ciudad" id="id_ciudad">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Estado</label>
                    <input type="text" class="form-control" id="estado" disabled>
                    <input type="hidden" name="id_estado" id="id_estado">
                </div>

                <div class="col-md-4">
                    <label class="form-label">País</label>
                    <select name="id_pais" class="form-select" required>
                        @foreach($paises as $id => $nombre)
                            <option value="{{ $id }}" {{ old('id_pais', $razon->direccion_facturacion->id_pais) == $id ? 'selected' : '' }}>{{ $nombre->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


    </div>



    </form>
    </div>

    @push('scripts')

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const cpInput = document.getElementById('cp');
                const colSel = document.getElementById('colonia');
                const ciudad = document.getElementById('ciudad');
                const estado = document.getElementById('estado');
                const idCiudad = document.getElementById('id_ciudad');
                const idEstado = document.getElementById('id_estado');

                const coloniaActual = {{ $razon->direccion_facturacion->id_colonia ?? 'null' }};

                cpInput.addEventListener('input', async () => {
                    const cp = cpInput.value.trim();

                    if (cp.length !== 5 || !/^\d{5}$/.test(cp)) {
                        colSel.innerHTML = '<option value="">Ingresa un CP válido</option>';
                        ciudad.value = estado.value = idCiudad.value = idEstado.value = '';
                        return;
                    }

                    try {
                        const res = await fetch(`/api/cp/${cp}`);
                        if (!res.ok) throw new Error();

                        const data = await res.json();

                        colSel.innerHTML = '';
                        data.colonias.forEach(c => {
                            const option = new Option(`${c.colonia} (${c.tipo})`, c.id_colonia);
                            if (c.id_colonia == coloniaActual) {
                                option.selected = true;
                            }
                            colSel.appendChild(option);
                        });

                        ciudad.value = data.head.municipio;
                        estado.value = data.head.estado;
                        idCiudad.value = data.head.id_ciudad || '';
                        idEstado.value = data.head.id_estado || '';
                    } catch (e) {
                        colSel.innerHTML = '<option value="">CP no encontrado</option>';
                        ciudad.value = estado.value = idCiudad.value = idEstado.value = '';
                    }
                });

                // Disparar búsqueda si ya hay un CP cargado
                if (cpInput.value.length === 5) {
                    cpInput.dispatchEvent(new Event('input'));
                }

                // Evita submit con Enter en el CP
                cpInput.addEventListener('keydown', e => {
                    if (e.key === 'Enter') e.preventDefault();
                });
            });
        </script>



    @endpush
@endsection