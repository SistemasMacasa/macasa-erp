@extends('layouts.app')

@section('title', 'Editar Contacto de Entrega')

@section('content')
<div class="container">
    <h4 class="mb-3"><i class="fa fa-user me-2"></i> Editar Contacto de Entrega</h4>

    <form method="POST" action="{{ route('contacto_entrega.update', $contacto->id_contacto) }}">
    @csrf
    @method('PUT')
    <input type="hidden" name="id_cliente" value="{{ $contacto->id_cliente }}">

    {{-- 👤 Datos del contacto --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title text-subtitulo mb-3"><i class="fa fa-user me-2 text-primary"></i>Información del Contacto</h5>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nombre del contacto</label>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $contacto->nombre) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono1" class="form-control" value="{{ old('telefono1', $contacto->telefono1) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Extensión</label>
                    <input type="text" name="ext1" class="form-control" value="{{ old('ext1', $contacto->ext1) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $contacto->email) }}">
                </div>
            </div>
        </div>
    </div>

    {{-- 📍 Dirección de entrega --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title text-subtitulo mb-3"><i class="fa fa-map-marker-alt me-2 text-danger"></i>Dirección de Entrega</h5>

            @php $d = $contacto->direccion_entrega; @endphp

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Calle</label>
                    <input type="text" name="calle" class="form-control" value="{{ old('calle', $d->calle ?? '') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Num. Ext</label>
                    <input type="text" name="num_ext" class="form-control" value="{{ old('num_ext', $d->num_ext ?? '') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Num. Int</label>
                    <input type="text" name="num_int" class="form-control" value="{{ old('num_int', $d->num_int ?? '') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Código Postal</label>
                    <input type="text" name="cp" id="cp" class="form-control" value="{{ old('cp', $d->cp ?? '') }}" maxlength="5" required>
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
                        @foreach(App\Models\Pais::all() as $pais)
                            <option value="{{ $pais->id_pais }}" {{ old('id_pais', $d->id_pais ?? '') == $pais->id_pais ? 'selected' : '' }}>
                                {{ $pais->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">Notas de entrega</label>
                    <textarea name="notas_entrega" class="form-control" rows="3">{{ old('notas_entrega', $contacto->notas_entrega ?? '') }}</textarea>
                    <small class="text-muted">Estas notas se reinician con cada cotización.</small>
                </div>
            </div>
        </div>
    </div>

    {{-- 🎯 Botones --}}
    <div class="d-flex justify-content-end">
        <a href="{{ route('cotizaciones.create', $contacto->id_cliente) }}" class="btn btn-outline-secondary">
            <i class="fa fa-chevron-left me-1"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-save me-1"></i> Guardar cambios
        </button>
    </div>
</form>

</div>

@push('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cpInput    = document.getElementById('cp');
            const colSel     = document.getElementById('colonia');
            const ciudad     = document.getElementById('ciudad');
            const estado     = document.getElementById('estado');
            const idCiudad   = document.getElementById('id_ciudad');
            const idEstado   = document.getElementById('id_estado');

            const coloniaActual = {{ $d->id_colonia ?? 'null' }};

            async function buscarCP(cp) {
                colSel.innerHTML = '<option>Cargando colonias...</option>';
                ciudad.value = estado.value = idCiudad.value = idEstado.value = '';

                try {
                    const res = await fetch(`/api/cp/${cp}`);
                    if (!res.ok) throw new Error('CP inválido');

                    const data = await res.json();

                    // Limpiar y llenar select de colonias
                    colSel.innerHTML = '';
                    data.colonias.forEach(c => {
                        const opt = new Option(`${c.colonia} (${c.tipo})`, c.id_colonia);
                        if (c.id_colonia == coloniaActual) opt.selected = true;
                        colSel.appendChild(opt);
                    });

                    ciudad.value   = data.head.municipio;
                    estado.value   = data.head.estado;
                    idCiudad.value = data.head.id_ciudad || '';
                    idEstado.value = data.head.id_estado || '';

                } catch (err) {
                    colSel.innerHTML = '<option value="">CP no encontrado</option>';
                    ciudad.value = estado.value = idCiudad.value = idEstado.value = '';
                }
            }

            // Detectar cambio en CP cuando hay 5 dígitos válidos
            cpInput.addEventListener('input', () => {
                const cp = cpInput.value.trim();
                if (/^\d{5}$/.test(cp)) buscarCP(cp);
            });

            // Disparar búsqueda si ya hay CP cargado al abrir la vista
            if (/^\d{5}$/.test(cpInput.value)) {
                buscarCP(cpInput.value.trim());
            }

            // Evitar submit del form al presionar Enter en el campo CP
            cpInput.addEventListener('keydown', e => {
                if (e.key === 'Enter') e.preventDefault();
            });
        });
    </script>

@endpush
@endsection
