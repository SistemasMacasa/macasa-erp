@extends('layouts.app')

@section('title', 'Usuarios del Sistema')

@section('content')
    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary mb-2">
        ← Volver
    </a>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('inicio') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Clientes</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ver cliente</li>
        </ol>
    </nav>

    <h1 class="mb-4">Usuarios del Sistema</h1>

    <p>
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
            Nuevo Usuario
        </a>
    </p>

    <table class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Cargo</th>
                <th>Tipo</th>
                <th>Estatus</th>
                <th>Es Admin</th>
                <th>Fecha Alta </th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->username }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td>{{ $usuario->cargo }}</td>
                    <td>{{ $usuario->tipo }}</td>
                    <td>{{ $usuario->estatus }}</td>
                    <td>{{ $usuario->es_admin ? 'Sí' : 'No' }}</td>
                    <td>{{ \Carbon\Carbon::parse($usuario->fecha_alta)->translatedFormat('l j F Y') }}</td>
                    <td>
                        <a href="{{ route('usuarios.edit', $usuario->id_usuario) }}" class="btn btn-warning btn-sm">
                            Editar
                        </a>

                        <form action="{{ route('usuarios.destroy', $usuario->id_usuario) }}" method="POST"
                            style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('¿Estás seguro?')" class="btn btn-danger btn-sm">
                                Borrar
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection