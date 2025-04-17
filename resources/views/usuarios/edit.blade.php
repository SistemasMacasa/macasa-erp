@extends('layouts.app')

@section('title','Editar usuario')

@section('content')
<div class="card shadow">
    <div class="card-header">
        <h4 class="mb-0">Editar Usuario</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('usuarios.update', $usuario->id_usuario) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nombre" class="form-label">Username</label>
                <input type="text"
                       name="nombre"
                       id="nombre"
                       value="{{ old('nombre', $usuario->username) }}"
                       class="form-control">
            </div>

            <div class="mb-3">
                <label for="apellido" class="form-label">Email</label>
                <input type="text"
                       name="apellido"
                       id="apellido"
                       value="{{ old('apellido', $usuario->email) }}"
                       class="form-control">
            </div>

            <div class="mb-3">
                <label for="estatus" class="form-label">Estatus</label>
                <select name="estatus" id="estatus" class="form-select">
                    <option value="Activo" {{ old('estatus', $usuario->estatus) == 'Activo' ? 'selected' : '' }}>Activo</option>
                    <option value="Inactivo" {{ old('estatus', $usuario->estatus) == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo</label>
                <select name="tipo" id="tipo" class="form-select">
                    <option value="ERP" {{ old('tipo', $usuario->tipo) == 'ERP' ? 'selected' : '' }}>ERP</option>
                    <option value="ECOMMERCE" {{ old('tipo', $usuario->tipo) == 'ECOMMERCE' ? 'selected' : '' }}>ECOMMERCE</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                Actualizar
            </button>
        </form>
    </div>
</div>

@endsection
