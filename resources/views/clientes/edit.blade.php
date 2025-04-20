@extends('layouts.app')

@section('title','Editar Cliente')

@section('content')
@section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('inicio') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Mis Cuentas</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('clientes.edit', $cliente->id_cliente) }}">Editar Cliente</a> </li>
    @endsection

<div class="card shadow">
    <div class="card-header">
        <h4 class="mb-0">Editar Cliente</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('clientes.update', $cliente->id_cliente) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text"
                       name="nombre"
                       id="nombre"
                       value="{{ old('nombre', $cliente->nombre) }}"
                       class="form-control">
            </div>

            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text"
                       name="apellido"
                       id="apellido"
                       value="{{ old('apellido', $cliente->apellido) }}"
                       class="form-control">
            </div>

            <div class="mb-3">
                <label for="estatus" class="form-label">Estatus</label>
                <select name="estatus" id="estatus" class="form-select">
                    <option value="Activo" {{ old('estatus', $cliente->estatus) == 'Activo' ? 'selected' : '' }}>Activo</option>
                    <option value="Inactivo" {{ old('estatus', $cliente->estatus) == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo</label>
                <select name="tipo" id="tipo" class="form-select">
                    <option value="ERP" {{ old('tipo', $cliente->tipo) == 'ERP' ? 'selected' : '' }}>ERP</option>
                    <option value="ECOMMERCE" {{ old('tipo', $cliente->tipo) == 'ECOMMERCE' ? 'selected' : '' }}>ECOMMERCE</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="id_vendedor" class="form-label">Vendedor Asignado</label>
                <select name="id_vendedor" id="id_vendedor" class="form-select">
                    <option value="">-- Selecciona un vendedor --</option>
                    @foreach ($vendedores as $vendedor)
                        <option value="{{ $vendedor->id_usuario }}"
                            {{ old('id_vendedor', $cliente->id_vendedor) == $vendedor->id_usuario ? 'selected' : '' }}>
                            {{ $vendedor->username }}
                        </option>
                    @endforeach
                </select>
            </div>


            <button type="submit" class="btn btn-primary">
                Actualizar
            </button>
        </form>
    </div>
</div>

@endsection
