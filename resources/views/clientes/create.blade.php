@extends('layouts.app')

@section('title', 'Crear Nuevo Cliente')

@section('content')

<h1>Crear Nuevo Cliente</h1>

@if ($errors->any())
    <div style="color: red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

<form action="{{ route('clientes.store') }}" method="POST">
    @csrf

    <label>Nombre:</label>
    <input type="text" name="nombre" value="{{ old('nombre') }}">

    <br><br>

    <label>Apellido:</label>
    <input type="text" name="apellido" value="{{ old('apellido') }}">

    <br><br>

    <label>Estatus:</label>
    <select name="estatus">
        <option value="Activo">Activo</option>
        <option value="Inactivo">Inactivo</option>
    </select>

    <br><br>

    <label>Tipo:</label>
    <select name="tipo">
        <option value="ERP">ERP</option>
        <option value="ECOMMERCE">ECOMMERCE</option>
    </select>

    <br><br>

    <label>Vendedor (id_vendedor):</label>
    <input type="number" name="id_vendedor" value="{{ old('id_vendedor') }}">

    <br><br>

    <button type="submit">Guardar</button>
</form>


@endsection
