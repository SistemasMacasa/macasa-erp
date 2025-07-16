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
                    <i class="fa fa-users me-1"></i> Organizacion
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
                            <div
                                class="d-flex justify-content-between align-items-center mb-2 p-2 gap-2 bg-light rounded shadow-sm border">
                                <strong class="mb-0">
                                    <i class="fa fa-layer-group me-1"></i> Segmento: {{ $segmento->nombre }}
                                </strong>
                                <div class="btn-group gap-2">
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#modalEditarSegmento{{ $segmento->id_segmento }}">
                                        <i class="fa fa-edit me-1"></i> Editar Segmento
                                    </button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#modalEliminarSegmento{{ $segmento->id_segmento }}">
                                        <i class="fa fa-trash me-1"></i> Eliminar Segmento
                                    </button>
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                        data-bs-target="#modalCrearSucursal{{ $segmento->id_segmento }}">
                                        <i class="fa fa-plus me-1"></i> Agregar Sucursal
                                    </button>
                                </div>
                            </div>
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
                                                {{-- <div>
                                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                        data-bs-target="#modalEditarSegmento{{ $segmento->id_segmento }}">Editar</button>
                                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                        data-bs-target="#modalEliminarSegmento{{ $segmento->id_segmento }}">Eliminar</button>
                                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                                        data-bs-target="#modalCrearSucursal{{ $segmento->id_segmento }}">+
                                                        Agregar Sucursal</button>
                                                </div> --}}
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

                                        <!-- Modal Crear Segmento -->
                                        <div class="modal fade" id="modalCrearSucursal{{ $segmento->id_segmento }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <form action="{{ route('sucursales.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id_segmento" value="{{ $segmento->id_segmento }}">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Agregar Sucursal a {{ $segmento->nombre }}</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="text" name="nombre" class="form-control"
                                                                placeholder="Nombre de sucursal" required>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cancelar</button>
                                                            <button class="btn btn-success" type="submit">Crear</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- Modal Editar Sucursal -->
                                        <div class="modal fade" id="modalEditarSucursal{{ $sucursal->id_sucursal }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <form action="{{ route('sucursales.update', $sucursal) }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Editar Sucursal</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="text" name="nombre" class="form-control"
                                                                value="{{ $sucursal->nombre }}" required>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cancelar</button>
                                                            <button class="btn btn-primary" type="submit">Guardar cambios</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- Modal Eliminar Sucursal -->
                                        <div class="modal fade" id="modalEliminarSucursal{{ $sucursal->id_sucursal }}"
                                            tabindex="-1">
                                            <div class="modal-dialog">
                                                <form action="{{ route('sucursales.destroy', $sucursal) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Eliminar Sucursal</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            ¿Estás seguro que deseas eliminar la sucursal
                                                            <strong>{{ $sucursal->nombre }}</strong>?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cancelar</button>
                                                            <button class="btn btn-danger" type="submit">Eliminar</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                    @endforeach
                                </tbody>
                            </table>
                            <!-- Modal Editar Segmento -->
                            <div class="modal fade" id="modalEditarSegmento{{ $segmento->id_segmento }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="{{ route('segmentos.update', $segmento) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Editar Segmento</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="text" name="nombre" class="form-control"
                                                    value="{{ $segmento->nombre }}" required>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancelar</button>
                                                <button class="btn btn-primary" type="submit">Guardar cambios</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Modal Eliminar Segmento -->
                            <div class="modal fade" id="modalEliminarSegmento{{ $segmento->id_segmento }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="{{ route('segmentos.destroy', $segmento) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Eliminar Segmento</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                ¿Estás seguro que deseas eliminar el segmento
                                                <strong>{{ $segmento->nombre }}</strong>?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancelar</button>
                                                <button class="btn btn-danger" type="submit">Eliminar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
            <!-- Modal Crear Segmento -->
            <div class="modal fade" id="modalCrearSegmento" tabindex="-1" aria-labelledby="modalCrearSegmentoLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('segmentos.store') }}" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalCrearSegmentoLabel">Crear nuevo
                                    segmento</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body">
                                <label for="nombreSegmento" class="form-label">Nombre del
                                    segmento</label>
                                <input type="text" class="form-control" name="nombre" id="nombreSegmento"
                                    placeholder="Ej. Segmento Norte" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Crear segmento</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal crear segmento --}}
    {{-- @include('estructura.modales.crear_segmento') --}}
@endsection