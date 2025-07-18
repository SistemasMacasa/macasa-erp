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

            <button class="col-md-2 btn btn-primary btn-principal" data-bs-toggle="modal"
                data-bs-target="#modalCrearSegmento">
                + Crear Segmento
            </button>
        </div>

        {{-- Tabla colapsable de segmentos --}}
        <div class="accordion" id="segmentosAccordion">
            <div class="tabla-header-top">
                <div class="header-title">
                    <i class="fa fa-users me-1"></i> Organización
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
                            {{-- Info segmento + acciones --}}
                            <div
                                class="d-flex justify-content-between align-items-center mb-2 p-2 gap-2 bg-light rounded shadow-sm border border-primary border-2">
                                <strong class="mb-0">
                                    {{-- <i class="fa fa-layer-group me-1"></i> --}}
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
                                    <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalAgregarSucursal-{{ $segmento->id_segmento }}">
                                        <i class="fa fa-plus me-1"></i> Agregar Sucursal
                                    </button>
                                </div>
                            </div>

                            {{-- Sucursales --}}
                            <div class="accordion" id="sucursalesAccordion-{{ $segmento->id_segmento }}" style="padding: 22px">
                                @foreach($segmento->sucursales as $sucursal)
                                    <div class="accordion-item mb-2">
                                        <h2 class="accordion-header" id="headingSucursal{{ $sucursal->id_sucursal }}">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseSucursal{{ $sucursal->id_sucursal }}">
                                                <strong>Sucursal:</strong> {{ $sucursal->nombre }}
                                            </button>
                                        </h2>
                                        <div id="collapseSucursal{{ $sucursal->id_sucursal }}" class="accordion-collapse collapse"
                                            data-bs-parent="#sucursalesAccordion-{{ $segmento->id_segmento }}">
                                            <div class="accordion-body">
                                                <div
                                                    class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light border rounded">
                                                    <strong>
                                                        <i class="fa fa-building me-1"></i> {{ $sucursal->nombre }}
                                                    </strong>
                                                    <div class="btn-group gap-2">
                                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                            data-bs-target="#modalEditarSucursal{{ $sucursal->id_sucursal }}">Editar</button>
                                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                            data-bs-target="#modalEliminarSucursal{{ $sucursal->id_sucursal }}">Eliminar</button>
                                                    </div>
                                                </div>

                                                {{-- Equipos --}}
                                                <div class="accordion" id="equiposAccordion-{{ $sucursal->id_sucursal }}">
                                                    @foreach($sucursal->equipos as $equipo)
                                                        <div class="accordion-item mb-2">
                                                            <h2 class="accordion-header" id="headingEquipo{{ $equipo->id_equipo }}">
                                                                <button class="accordion-button collapsed border border-purple"
                                                                    type="button" data-bs-toggle="collapse"
                                                                    data-bs-target="#collapseEquipo{{ $equipo->id_equipo }}">
                                                                    <strong>Equipo:</strong> {{ $equipo->nombre }}
                                                                </button>
                                                            </h2>
                                                            <div id="collapseEquipo{{ $equipo->id_equipo }}"
                                                                class="accordion-collapse collapse"
                                                                data-bs-parent="#equiposAccordion-{{ $sucursal->id_sucursal }}">
                                                                <div class="accordion-body">
                                                                    <div class="bg-light border rounded p-2">
                                                                        <strong>
                                                                            <i class="fa fa-users me-1"></i> Miembros del equipo
                                                                        </strong>
                                                                        <div class="table-responsive">
                                                                            <table class="table table-hover mt-2 mb-0">
                                                                                <tbody>
                                                                                    @forelse($equipo->usuarios as $usuario)
                                                                                        <tr>
                                                                                            <td width="40">
                                                                                                <i class="fa fa-user text-muted"></i>
                                                                                            </td>
                                                                                            <td>
                                                                                                <strong>{{ $usuario->nombre_completo }}</strong>
                                                                                                <small class="text-muted">
                                                                                                    ({{ $equipo->lider && $usuario->id_usuario === $equipo->lider->id_usuario ? 'líder' : 'miembro' }})
                                                                                                </small>
                                                                                            </td>
                                                                                        </tr>
                                                                                    @empty
                                                                                        <tr>
                                                                                            <td colspan="2" class="text-center text-muted">
                                                                                                Este equipo no tiene miembros.</td>
                                                                                        </tr>
                                                                                    @endforelse
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @foreach($segmentos as $segmento)
            {{-- Editar segmento --}}
            <div class="modal fade" id="modalEditarSegmento{{ $segmento->id_segmento }}" tabindex="-1">
                <div class="modal-dialog">
                    <form action="{{ route('segmentos.update', $segmento) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Editar Segmento</h5>
                                <button class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="text" name="nombre" class="form-control" value="{{ $segmento->nombre }}" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button class="btn btn-primary" type="submit">Guardar cambios</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Eliminar segmento --}}
            <div class="modal fade" id="modalEliminarSegmento{{ $segmento->id_segmento }}" tabindex="-1">
                <div class="modal-dialog">
                    <form action="{{ route('segmentos.destroy', $segmento) }}" method="POST">
                        @csrf @method('DELETE')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Eliminar Segmento</h5>
                                <button class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                ¿Estás seguro que deseas eliminar el segmento <strong>{{ $segmento->nombre }}</strong>?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button class="btn btn-danger" type="submit">Eliminar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Agregar sucursal --}}
            <div class="modal fade" id="modalAgregarSucursal-{{ $segmento->id_segmento }}" tabindex="-1">
                <div class="modal-dialog">
                    <form action="{{ route('sucursales.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_segmento" value="{{ $segmento->id_segmento }}">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Agregar Sucursal a {{ $segmento->nombre }}</h5>
                                <button class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="text" name="nombre" class="form-control" placeholder="Nombre de sucursal" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button class="btn btn-success" type="submit">Crear</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @foreach($segmento->sucursales as $sucursal)
                {{-- Editar sucursal --}}
                <div class="modal fade" id="modalEditarSucursal{{ $sucursal->id_sucursal }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form action="{{ route('sucursales.update', $sucursal) }}" method="POST">
                            @csrf @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Editar Sucursal</h5>
                                    <button class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="text" name="nombre" class="form-control" value="{{ $sucursal->nombre }}" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button class="btn btn-primary" type="submit">Guardar cambios</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Eliminar sucursal --}}
                <div class="modal fade" id="modalEliminarSucursal{{ $sucursal->id_sucursal }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form action="{{ route('sucursales.destroy', $sucursal) }}" method="POST">
                            @csrf @method('DELETE')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Eliminar Sucursal</h5>
                                    <button class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    ¿Seguro que deseas eliminar la sucursal <strong>{{ $sucursal->nombre }}</strong>?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button class="btn btn-danger" type="submit">Eliminar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>
    <script>
        function toggleEquipo(id) {
            const detalle = document.getElementById('equipo-detalle-' + id);
            detalle.classList.toggle('abierto');
        }
    </script>
@endsection