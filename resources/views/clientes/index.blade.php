@extends('layouts.app')

@section('title', 'Listado de Clientes')

@section('content')
    <h1 class="mb-4">Listado de Clientes</h1>
    <h1>Clientes</h1>
@if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

<p><a href="{{ route('clientes.create') }}">Crear Nuevo</a></p>

<table border="1">
    <tr>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Estatus</th>
        <th>Tipo</th>
        <th>Vendedor</th>
    </tr>
    @foreach($clientes as $cliente)
    <tr>
        <td>{{ $cliente->nombre }}</td>
        <td>{{ $cliente->apellido }}</td>
        <td>{{ $cliente->estatus }}</td>
        <td>{{ $cliente->tipo }}</td>
        <td>{{ $cliente->vendedor->username ?? 'Sin vendedor' }}</td>
    </tr>
    @endforeach
</table>

@endsection
