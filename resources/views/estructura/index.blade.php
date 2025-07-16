@extends('layouts.app')
@section('title', 'SIS 3.0 | Estructura Organizacional')

@section('content')
    <div class="container-fluid">
        {{-- 🧭 Migas de pan --}}
        @section('breadcrumb')
            <li class="breadcrumb-item"><a href="{{ route('inicio') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Estructura Organizacional</li>
        @endsection
        <h2 class="mb-3 text-titulo">Estructura: Segmentos y Sucursales</h2>

        {{-- 🎛 Botonera --}}
        <div class="row-fluid gap-2 mb-3">
            <a href="{{ url()->previous() }}" class="col-md-2 btn btn-secondary btn-principal">
                <i class="fa fa-arrow-left me-1"></i> Regresar
            </a>

            {{-- Botón Modal Crear Segmento --}}
            <button class="col-md-2 btn btn-primary btn-principal" data-bs-toggle="modal"
                data-bs-target="#modalCrearSegmento">+ Crear
                Segmento</button>
        </div>

        {{-- Tabla colapsable de segmentos --}}
        <div class="accordion" id="segmentosAccordion">
            <div class="tabla-header-top">
                <div class="header-title">
                    <i class="fa fa-users me-1"></i> Equipos de Trabajo
                </div>
            </div>
            @foreach($segmentos as $segmento)
                <div class="accordion-item mb-2">
                    <h2 class="accordion-header" id="heading{{ $segmento->id_segmento }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ $segmento->id_segmento }}">
                            <span><strong>Segmento:</strong> {{ $segmento->nombre }}</span>
                        </button>
                    </h2>
                    <div id="collapse{{ $segmento->id_segmento }}" class="accordion-collapse collapse"
                        data-bs-parent="#segmentosAccordion">
                        <div class="accordion-body">

                            {{-- Tabla de sucursales + acciones del segmento --}}
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr class="table-light">
                                        <th colspan="2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span>
                                                    <strong>Sucursales:</strong>
                                                    {{ $segmento->sucursales->pluck('nombre')->join(', ') ?: 'Sin sucursales' }}
                                                </span>

                                                <div>
                                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                        data-bs-target="#modalEditarSegmento{{ $segmento->id_segmento }}">Editar</button>
                                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                        data-bs-target="#modalEliminarSegmento{{ $segmento->id_segmento }}">Eliminar</button>
                                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                                        data-bs-target="#modalCrearSucursal{{ $segmento->id_segmento }}">+
                                                        Agregar Sucursal</button>
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($segmento->sucursales as $sucursal)
                                        <tr>
                                            <td>{{ $sucursal->nombre }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                    data-bs-target="#modalEditarSucursal{{ $sucursal->id_sucursal }}">Editar</button>
                                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#modalEliminarSucursal{{ $sucursal->id_sucursal }}">Eliminar</button>
                                            </td>
                                        </tr>

                                        {{-- Modal editar sucursal --}}
                                        @include('estructura.modales.editar_sucursal')

                                        {{-- Modal eliminar sucursal --}}
                                        @include('estructura.modales.eliminar_sucursal')
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>

                {{-- Modales segmento --}}
                {{-- @include('estructura.modales.editar_segmento')
                @include('estructura.modales.eliminar_segmento') --}}

                {{-- Modal crear sucursal --}}
                {{-- @include('estructura.modales.crear_sucursal') --}}
            @endforeach
        </div>
    </div>

    {{-- Modal crear segmento --}}
    {{-- @include('estructura.modales.crear_segmento') --}}
@endsection