{{-- resources/views/errors/403.blade.php --}}
@extends('layouts.app') {{-- o tu layout principal --}}

@section('title', 'Acceso denegado')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h1 class="display-4 text-danger">403</h1>
                <h3 class="mb-4">No tienes permisos para ver esta página</h3>

                <p class="text-muted">
                    Si crees que esto es un error, ponte en contacto con el equipo de Sistemas.
                </p>

                <a href="{{ url()->previous() }}" class="btn btn-outline-primary me-2">
                    <i class="fa fa-arrow-left me-1"></i> Volver
                </a>

                <a href="{{ route('inicio') }}" class="btn btn-primary">
                    <i class="fa fa-home me-1"></i> Inicio
                </a>
            </div>
        </div>
    </div>
@endsection