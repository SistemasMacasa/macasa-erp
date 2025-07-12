@extends('layouts.app')

@section('title', 'Editar Contacto de Entrega')

@section('content')
<div class="container">
    <h4 class="mb-3"><i class="fa fa-user me-2"></i> Editar Contacto de Entrega</h4>

    <form method="POST" action="{{ route('contacto_entrega.update', $contacto->id_contacto) }}">
        @csrf
        @method('PUT')

        <input type="hidden" name="id_cliente" value="{{ $contacto->id_cliente }}">

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nombre del contacto</label>
                <input type="text" name="nombre" class="form-control"
                    value="{{ old('nombre', $contacto->nombre) }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono1" class="form-control"
                    value="{{ old('telefono1', $contacto->telefono1) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Extensión</label>
                <input type="text" name="ext1" class="form-control"
                    value="{{ old('ext1', $contacto->ext1) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Correo electrónico</label>
                <input type="email" name="email" class="form-control"
                    value="{{ old('email', $contacto->email) }}">
            </div>

            <div class="col-12">
                <hr class="my-2">
                <h5 class="mb-2"><i class="fa fa-map-marker-alt me-2"></i> Dirección de Entrega</h5>
            </div>

            @php $d = $contacto->direccion_entrega; @endphp

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

            <div class="col-md-4">
                <label class="form-label">Colonia</label>
                <input type="text" name="colonia" class="form-control" value="{{ old('colonia', $d->colonia ?? '') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Ciudad</label>
                <input type="text" name="ciudad" class="form-control" value="{{ old('ciudad', $d->ciudad ?? '') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Estado</label>
                <input type="text" name="estado" class="form-control" value="{{ old('estado', $d->estado ?? '') }}">
            </div>

            <div class="col-md-3">
                <label class="form-label">CP</label>
                <input type="text" name="cp" class="form-control" value="{{ old('cp', $d->cp ?? '') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">País</label>
                <input type="text" name="pais" class="form-control" value="{{ old('pais', $d->pais ?? '') }}">
            </div>

            <div class="col-12">
                <label class="form-label">Notas de entrega</label>
                <textarea name="notas_entrega" class="form-control" rows="3">{{ old('notas_entrega', $contacto->notas_entrega ?? '') }}</textarea>
                <small class="text-muted">Estas notas se reinician con cada cotización.</small>
            </div>
        </div>

        <div class="text-end mt-4">
            <a href="{{ route('cotizaciones.create', $contacto->id_cliente) }}" class="btn btn-secondary">
                <i class="fa fa-chevron-left me-1"></i> Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save me-1"></i> Guardar cambios
            </button>
        </div>
    </form>
</div>
@endsection
