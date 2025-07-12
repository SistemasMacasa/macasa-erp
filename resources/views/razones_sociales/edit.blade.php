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
            <input type="text" name="RFC" class="form-control" value="{{ old('RFC', $razon->RFC) }}" maxlength="13" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Notas para Facturación</label>
            <textarea name="notas_facturacion" class="form-control" rows="4">{{ old('notas_facturacion', $razon->notas_facturacion) }}</textarea>
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
    </form>
</div>
@endsection
