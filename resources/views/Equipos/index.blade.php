@extends('layouts.app')
@section('title', 'SIS 3.0 | Equipos de Trabajo')

@section('content')

<div class="container-fluid">
    {{-- 🧭 Migas de pan --}}
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('inicio') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Equipos de Trabajo</li>
    @endsection
</div>