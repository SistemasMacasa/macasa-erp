@extends('layouts.app')

@section('title', 'Crear Nuevo Usuario')

@section('content')
<div class="card shadow">
    <div class="card-header">
        <h4 class="mb-0">Crear Nuevo Usuario</h4>
    </div>

    <div class="card-body">

        <form action="{{ route('usuarios.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text"
                       name="username"
                       id="username"
                       value="{{ old('username') }}"
                       class="form-control">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="text"
                       name="email"
                       id="email"
                       value="{{ old('email') }}"
                       class="form-control">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Cargo</label>
                <select name="id_cargo" id="id_cargo" class="form-select">
                        <option value="Dirección" {{ old('direccion') == "Direccion" ? 'selected' : '' }}>Dirección</option>
                        <option value="Administracion" {{ old('administracion') == "Administracion" ? 'selected' : '' }}>Administración</option>
                        <option value="Compras" {{ old('compras') == "Compras" ? 'selected' : '' }}>Compras</option>
                        <option value="Aux Compras" {{ old('contabilidad') == "Contabilidad" ? 'selected' : '' }}>Contabilidad</option>
                        <option value="Sistemas" {{ old('sistemas') == "Sistemas" ? 'selected' : '' }}>Sistemas</option>
                        <option value="Ejecutivo" {{ old('ventas') == "Ventas" ? 'selected' : '' }}>Ejecutivo de Ventas</option>

                </select>

            </div>

            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo</label>
                <select name="tipo" id="tipo" class="form-select">
                    <option value="ERP"        {{ old('tipo') === 'ERP' ? 'selected' : '' }}>ERP</option>
                    <option value="ECOMMERCE"  {{ old('tipo') === 'ECOMMERCE' ? 'selected' : '' }}>ECOMMERCE</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="estatus" class="form-label">Estatus</label>
                <select name="estatus" id="estatus" class="form-select">
                    <option value="Activo"  {{ old('estatus') === 'Activo' ? 'selected' : '' }}>Activo</option>
                    <option value="Inactivo" {{ old('estatus') === 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            

            <div class="mb-3">
                <label for="id_vendedor" class="form-label">Es admin</label>
                <input type="checkbox" id="es_admin" name="es_admin" style="margin-left:3px;" />

            </div>

            <button type="submit" class="btn btn-primary">
                Guardar
            </button>
        </form>
    </div>
</div>

@endsection
