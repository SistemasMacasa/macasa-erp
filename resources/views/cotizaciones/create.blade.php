@extends('layouts.app')
@section('title', 'SIS 3.0 | Nueva Cotización')

@section('content')
<div class="container-fluid">

    {{-- 🧭 Migas de pan --}}
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('inicio') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('cotizaciones.index') }}">Cotizaciones</a></li>
        <li class="breadcrumb-item active">Nueva Cotización</li>
    @endsection

    <h2 class="mb-3" style="color: inherit;">Levantar cotización</h2>

    {{-- 🎛 Botonera --}}
    <div class="d-flex flex-wrap gap-2 mb-3">
        <a href="{{ url()->previous() }}" class="btn btn-secondary btn-principal">
            <i class="fa fa-arrow-left me-1"></i> Regresar
        </a>
        <button class="btn btn-success btn-principal">
            <i class="fa fa-save me-1"></i> Guardar
        </button>
        <a href="{{ route('clientes.index') }}" class="btn btn-primary btn-principal">
            <i class="fa fa-building me-1"></i> Mis cuentas
        </a>
        <a href="#" class="btn btn-primary btn-principal">
            <i class="fa fa-user me-1"></i> Ver cuenta
        </a>
    </div>

    {{-- 🧾 Sección: Dirección de Facturación y Entrega --}}
    <div class="row gy-4">
        {{-- Dirección de facturación --}}
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header fw-bold">Dirección de Facturación</div>
                <div class="card-body">
                    
                </div>
            </div>
        </div>

        {{-- Dirección de entrega --}}
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header fw-bold">Dirección de Entrega</div>
                <div class="card-body">
                    {{-- Aquí van los campos de entrega: empresa, dirección, referencias, teléfono, notas, etc. --}}
                </div>
            </div>
        </div>
    </div>

    {{-- 📦 Sección: Partidas --}}
    <div class="card shadow mt-4">
        <div class="card-header fw-bold">Agregar partidas</div>
        <div class="card-body">
        </div>
    </div>
</div>
@endsection
