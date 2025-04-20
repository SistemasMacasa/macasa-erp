@extends('layouts.app')

@section('title', 'Crear Nuevo Cliente')

@section('content')
@section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('inicio') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Mis Cuentas</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('clientes.create') }}">Nuevo Cliente</a></li>
    @endsection
<div class="card shadow">
    <div class="card-header">
        <h4 class="mb-0">Crear Nuevo Cliente</h4>
    </div>

    <div class="card-body">
        {{-- Muestra errores de validación (si los hay) --}}
        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('clientes.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text"
                       name="nombre"
                       id="nombre"
                       value="{{ old('nombre') }}"
                       class="form-control">
            </div>

            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text"
                       name="apellido"
                       id="apellido"
                       value="{{ old('apellido') }}"
                       class="form-control">
            </div>

            <div class="mb-3">
                <label for="estatus" class="form-label">Estatus</label>
                <select name="estatus" id="estatus" class="form-select">
                    <option value="Activo"  {{ old('estatus') === 'Activo' ? 'selected' : '' }}>Activo</option>
                    <option value="Inactivo" {{ old('estatus') === 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo</label>
                <select name="tipo" id="tipo" class="form-select">
                    <option value="SIS"        {{ old('tipo') === 'SIS' ? 'selected' : '' }}>SIS</option>
                    <option value="Ecommerce 1"  {{ old('tipo') === 'Ecommerce 1' ? 'selected' : '' }}>Ecommerce 1</option>
                    <option value="Ecommerce 2"  {{ old('tipo') === 'Ecommerce 2' ? 'selected' : '' }}>Ecommerce 2</option>

                </select>
            </div>

            <div class="mb-3">
                <label for="id_vendedor" class="form-label">Vendedor Asignado</label>
                <select name="id_vendedor" id="id_vendedor" class="form-select">
                    <option value="">-- Selecciona un vendedor --</option>
                    @foreach ($vendedores as $vendedor)
                        <option value="{{ $vendedor->id_usuario }}"
                            {{ old('id_vendedor', $vendedor->id_vendedor) == $vendedor->id_usuario ? 'selected' : '' }}>
                            {{ $vendedor->username }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                Guardar
            </button>
        </form>
    </div>
</div>

@endsection
