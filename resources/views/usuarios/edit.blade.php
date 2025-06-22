@extends('layouts.app')

@section('title', 'Editar usuario')

@section('content')
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('inicio') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('usuarios.index') }}">Usuarios del SIS</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a
                href="{{ route('usuarios.edit', $usuario->id_usuario) }}">Editar Usuario</a></li>
    @endsection
    <div class="card shadow">
        <div class="card-header">
            <h4 class="mb-0">Editar Usuario</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nombre" class="form-label">Username</label>
                    <input type="text" name="username" id="nombre" value="{{ old('username', $usuario->username) }}"
                        class="form-control">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" name="password" id="password" class="form-control"
                        placeholder="Dejar en blanco para no cambiar">
                </div>


                <div class="mb-3">
                    <label for="apellido" class="form-label">Email</label>
                    <input type="text" name="email" id="apellido" value="{{ old('email', $usuario->email) }}"
                        class="form-control">
                </div>

                <div class="mb-3">
                    <label for="cargo" class="form-label">Cargo</label>
                    <select name="cargo" id="cargo" class="form-select">
                        <option value="Direccion" {{ old('cargo', $usuario->cargo) == "Direccion" ? 'selected' : '' }}>
                            Dirección</option>
                        <option value="Administracion" {{ old('cargo', $usuario->cargo) == "Administracion" ? 'selected' : '' }}>Administración</option>
                        <option value="Compras" {{ old('cargo', $usuario->cargo) == "Compras" ? 'selected' : '' }}>Compras
                        </option>
                        <option value="Aux Compras" {{ old('cargo', $usuario->cargo) == "Aux Compras" ? 'selected' : '' }}>Aux
                            Compras</option>
                        <option value="Marketing" {{ old('cargo', $usuario->cargo) == "Marketing" ? 'selected' : '' }}>
                            Marketing</option>
                        <option value="Sistemas" {{ old('cargo', $usuario->cargo) == "Sistemas" ? 'selected' : '' }}>Sistemas
                        </option>
                        <option value="Ejecutivo" {{ old('cargo', $usuario->cargo) == "Ejecutivo" ? 'selected' : '' }}>
                            Ejecutivo de Ventas</option>

                    </select>

                </div>

                <div class="mb-3">
                    <label for="estatus" class="form-label">Estatus</label>
                    <select name="estatus" id="estatus" class="form-select">
                        <option value="Activo" {{ old('estatus', $usuario->estatus) == 'Activo' ? 'selected' : '' }}>Activo
                        </option>
                        <option value="Inactivo" {{ old('estatus', $usuario->estatus) == 'Inactivo' ? 'selected' : '' }}>
                            Inactivo</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="tipo" class="form-label">Tipo</label>
                    <select name="tipo" id="tipo" class="form-select">
                        <option value="ERP" {{ old('tipo', $usuario->tipo) == 'ERP' ? 'selected' : '' }}>ERP</option>
                        <option value="ECOMMERCE" {{ old('tipo', $usuario->tipo) == 'ECOMMERCE' ? 'selected' : '' }}>ECOMMERCE
                        </option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="es_admin" class="form-label">Es admin</label>
                    <input type="checkbox" id="es_admin" name="es_admin" style="margin-left:3px;" {{ $usuario->es_admin ? 'checked' : '' }} />
                </div>

                <div class="mb-3">
                    <label class="form-label">Fecha Alta</label>
                    <p class="form-control-plaintext">
                        {{ \Carbon\Carbon::parse($usuario->fecha_alta)->translatedFormat('j \d\e F \d\e Y \a \l\a\s H:i') }}
                    </p>
                </div>





                <button type="submit" class="btn btn-primary">
                    Actualizar
                </button>
            </form>
        </div>
    </div>

@endsection