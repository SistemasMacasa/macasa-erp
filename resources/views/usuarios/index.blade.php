@extends('layouts.app')

@section('title', 'Usuarios del Sistema')

@section('content')
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
                <td></td>
                <td>
                    <a href="{{ route('usuarios.edit', $usuario->id_usuario) }}"
                       class="btn btn-warning btn-sm">
                        Editar
                    </a>
                    
                    <form action="{{ route('usuarios.destroy', $usuario->id_usuario) }}"
                          method="POST"
                          style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                onclick="return confirm('¿Estás seguro?')"
                                class="btn btn-danger btn-sm">
                            Borrar
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection
