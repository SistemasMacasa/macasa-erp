@extends('layouts.app')

@section('title', 'Listado de Clientes')

@section('content')
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('inicio') }}">Inicio</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('clientes.index') }}">Mis Cuentas</a></li>
    @endsection
    <h1 class="mb-4">Listado de Clientes</h1>

    <p>

    </p>

    <!-- ╭━━━━━━━━━━━━━━━━━━ Botonera superior ━━━━━━━━━━━━━━━━━╮ -->
    <div class="d-flex gap-2 mb-3">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">
            <i class="fa fa-arrow-left me-1"></i> Regresar
        </a>

        <a href="{{ route('clientes.create') }}" class="btn btn-primary">
            <i class="fa fa-phone me-1"></i> Mis Recall's
        </a>

        <a href="{{ route('clientes.create') }}" class="btn btn-primary">
            <i class="fa fa-check me-1"></i> Enviar carta de presentación
        </a>

    </div>

    <div class="mb-3">
        <input type="text" id="buscador-clientes" class="form-control" placeholder="Buscar cliente...">
    </div>

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
                        <a href="{{ route('clientes.edit', $cliente->id_cliente) }}" class="btn btn-warning btn-sm">
                            <i class="fa fa-edit me-1"></i> Editar
                        </a>

                        <form action="{{ route('clientes.destroy', $cliente->id_cliente) }}" method="POST"
                            style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('¿Estás seguro?')" class="btn btn-danger btn-sm">
                            <i class="fa fa-trash me-1"></i> Borrar
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection