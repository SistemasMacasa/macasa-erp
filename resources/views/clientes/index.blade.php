@extends('layouts.app')

@section('title', 'Listado de Clientes')

@section('content')
<h1 class="mb-4">Listado de Clientes</h1>

<p>
    <a href="{{ route('clientes.create') }}" class="btn btn-primary">
        Crear Nuevo
    </a>
</p>

<table class="table table-striped table-bordered align-middle">
    <thead class="table-dark">
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Estatus</th>
            <th>Tipo</th>
            <th>Vendedor</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($clientes as $cliente)
            <tr>
                <td>{{ $cliente->nombre }}</td>
                <td>{{ $cliente->apellido }}</td>
                <td>{{ $cliente->estatus }}</td>
                <td>{{ $cliente->tipo }}</td>
                <td>{{ $cliente->vendedor->username ?? 'Sin vendedor' }}</td>
                <td>
                    <a href="{{ route('clientes.edit', $cliente->id_cliente) }}"
                       class="btn btn-warning btn-sm">
                        Editar
                    </a>
                    
                    <form action="{{ route('clientes.delete', $cliente->id_cliente) }}"
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
