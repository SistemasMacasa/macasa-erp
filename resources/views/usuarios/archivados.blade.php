@extends('layouts.app')

@section('title', 'Usuarios del Sistema')

@section('content')
    <div class="container-fluid">



        {{-- 🧭 Migas de pan --}}
        @section('breadcrumb')
            <li class="breadcrumb-item"><a href="{{ route('inicio') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('usuarios.index') }}">Usuarios de SIS</a>
            </li>
        @endsection

        <h1 class="mb-4">Usuarios del Sistema <small>(Inactivos)</small> </h1>

        <div class="row-fluid gap-2 mb-4">
            <a href="{{ url()->previous() }}" class="col-md-2 btn btn-secondary">
                <i class="fa fa-arrow-left me-1"></i> Regresar
            </a>

        </div>

        <div class="mb-3">
            <input type="text" id="buscador-usuarios" class="form-control" placeholder="Buscar usuario...">
        </div>

        <table class="table table-striped table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Username</th>
                    <th>Nombre completo</th>
                    <th>Email</th>
                    <th>Cargo</th>
                    <th>Tipo</th>
                    <th>Estatus</th>
                    <th>Fecha Alta </th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->username }}</td>
                        <td>{{ $usuario->nombre }} {{ $usuario->apellido_p }} {{ $usuario->apellido_m }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>{{ $usuario->cargo }}</td>
                        <td>{{ $usuario->tipo }}</td>
                        <td>{{ $usuario->estatus }}</td>
                        <td>{{ \Carbon\Carbon::parse($usuario->fecha_alta)->translatedFormat('l j F Y') }}</td>
                        <td>
                            <a href="{{ route('usuarios.edit', $usuario->id_usuario) }}" class="btn btn-warning btn-sm">
                                Editar
                            </a>

                            @if ($usuario->estaActivo())
                                <form action="{{ route('usuarios.archivar', $usuario->id_usuario) }}" method="POST"
                                    style="display: inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" onclick="return confirm('¿Estas seguro de archivar este usuario?')"
                                        class="btn btn-danger btn-sm">
                                        Archivar
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('usuarios.desarchivar', $usuario->id_usuario) }}" method="POST"
                                    style="display: inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" onclick="return confirm('¿Estas seguro de archivar este usuario?')"
                                        class="btn btn-danger btn-sm">
                                        Reactivar
                                    </button>
                                </form>
                            @endif
                            {{-- <form action="{{ route('usuarios.delete', $usuario->id_usuario) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('¿Estás seguro?')" class="btn btn-danger btn-sm">
                                    Borrar
                                </button>
                            </form> --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection