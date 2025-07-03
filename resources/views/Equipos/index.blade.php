@extends('layouts.app')
@section('title', 'SIS 3.0 | Equipos de Trabajo')

@section('content')

    <div class="container-fluid">
        {{-- 🧭 Migas de pan --}}
        @section('breadcrumb')
            <li class="breadcrumb-item"><a href="{{ route('inicio') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Equipos de Trabajo</li>
        @endsection

        <h2 class="mb-3 text-titulo">Gestión de Equipos de Trabajo</h2>

        {{-- 🎛 Botonera --}}
        <div class="row-fluid gap-2 mb-3">
            <a href="{{ url()->previous() }}" class="col-md-2 btn btn-secondary btn-principal">
                <i class="fa fa-arrow-left me-1"></i> Regresar
            </a>

            {{-- Botón Modal Crear Equipo de Trabajo --}}
            <button type="button" id="btnCrearEquipo" class="col-md-2 btn btn-primary btn-principal">
                <i class="fa fa-users-cog me-1"></i> Crear Equipo de trabajo
            </button>


        </div>
        <div class="table-responsive mb-3 shadow-lg">
            @if ($equipos->isEmpty())
                <p>No hay equipos registrados.</p>
            @else
                <table class="table table-striped table-hover table-bordered align-middle">
                    <thead class="text-center">
                        <tr>
                            <th class="header-tabla col-5ch">ID</th>
                            <th class="header-tabla col-15ch">Nombre</th>
                            <th class="header-tabla col-15ch">Líder</th>
                            <th class="header-tabla col-15ch">Miembros</th>
                            <th class="header-tabla col-15ch">Descripcion</th>
                            <th class="header-tabla col-10ch">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($equipos as $equipo)
                            <tr>
                                <td>{{ $equipo->id }}</td>
                                <td>{{ $equipo->nombre }}</td>
                                <td>
                                    {{ $equipo->lider ? $equipo->lider->nombre . ' ' . $equipo->lider->apellido_p : 'Sin líder' }}
                                </td>
                                <td>
                                    @if ($equipo->usuarios->isEmpty())
                                        <em>Sin miembros</em>
                                    @else
                                        <ul>
                                            @foreach ($equipo->usuarios as $usuario)
                                                <li>
                                                    {{ $usuario->nombre }} {{ $usuario->apellido_p }}
                                                    ({{ $usuario->pivot->rol }})
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>
                                <td>
                                    @if (empty($equipo->descripcion))
                                        <em>Sin descripción</em>
                                    @else
                                        {{ $equipo->descripcion }}
                                    @endif
                                </td>
                                <td>
                                    {{-- <a href="{{ route('equipos.show', $equipo) }}" class="btn btn-info btn-sm">Ver</a> --}}
                                    <button class="btn btn-primary btn-sm btn-editar-equipo" data-id="{{ $equipo->id }}">
                                        Editar
                                    </button>
                                    <form action="{{ route('equipos.destroy', $equipo) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('¿Seguro que quieres eliminar este equipo?')">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade mt-4" id="crearEquipoModal" tabindex="-1" aria-labelledby="crearEquipoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formEquipo" action="{{ route('equipos.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="equipoModalLabel">Crear Equipo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del equipo</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="lider_id" class="form-label">Líder del equipo</label>
                            <select name="lider_id" id="lider_id" class="form-select">
                                <option value="">-- Sin líder --</option>
                                @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->id_usuario }}">
                                        {{ $usuario->nombre }} {{ $usuario->apellido_p }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="usuarios" class="form-label">Miembros del Equipo</label>
                            <select id="usuarios" name="usuarios[]" class="form-select select2" multiple
                                data-placeholder="Seleccione los miembros">
                                @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->id_usuario }}">
                                        {{ $usuario->nombre }} {{ $usuario->apellido_p }} {{ $usuario->apellido_m }}
                                        ({{ $usuario->username }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Guardar equipo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            // Inicializa Select2
            let $miembros = $('#usuarios').select2({
                placeholder: "Seleccione los miembros"
            });

            let previousLider = null;

            $('#lider_id').on('change', function () {
                const liderId = $(this).val();

                // Si había un líder previo, volvemos a habilitar su opción
                if (previousLider) {
                    const option = $('#usuarios option[value="' + previousLider + '"]');
                    option.prop('disabled', false);
                }

                // Si hay un líder nuevo, deshabilitamos su opción
                if (liderId) {
                    const option = $('#usuarios option[value="' + liderId + '"]');
                    option.prop('disabled', true);
                }

                // Actualizamos Select2 para que refleje cambios
                $miembros.select2();

                // Guardamos el líder actual para poder re-habilitarlo después
                previousLider = liderId;
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            let $miembros = $('#usuarios').select2({
                placeholder: "Seleccione los miembros",
                dropdownParent: $('#crearEquipoModal')
            });

            let previousLider = null;

            // Deshabilitar líder seleccionado
            $('#lider_id').on('change', function () {
                const liderId = $(this).val();
                if (previousLider) {
                    $('#usuarios option[value="' + previousLider + '"]').prop('disabled', false);
                }
                if (liderId) {
                    $('#usuarios option[value="' + liderId + '"]').prop('disabled', true);
                }
                $miembros.select2();
                previousLider = liderId;
            });

            // Botón Crear
            $('#btnCrearEquipo').on('click', function () {
                $('#formEquipo').attr('action', '{{ route("equipos.store") }}');
                $('#formEquipo').find('input[name="_method"]').remove();
                $('#equipoModalLabel').text('Crear Equipo');
                $('#formEquipo')[0].reset();
                $('#usuarios').val(null).trigger('change');
                $('#lider_id').val('');
                $('#crearEquipoModal').modal('show');
            });

            // Botón Editar
            $('.btn-editar-equipo').on('click', function () {
                let id = $(this).data('id');
                $.get(`/equipos/${id}/datos`, function (data) {
                    $('#formEquipo').attr('action', `/equipos/${id}`);
                    if (!$('#formEquipo input[name="_method"]').length) {
                        $('#formEquipo').append('<input type="hidden" name="_method" value="PUT">');
                    }
                    $('#equipoModalLabel').text('Editar Equipo');
                    $('#nombre').val(data.nombre);
                    $('#descripcion').val(data.descripcion);
                    $('#lider_id').val(data.lider_id);
                    $('#usuarios').val(data.usuarios).trigger('change');
                    previousLider = data.lider_id;

                    // Deshabilita el líder en select2
                    $('#usuarios option').prop('disabled', false);
                    if (data.lider_id) {
                        $('#usuarios option[value="' + data.lider_id + '"]').prop('disabled', true);
                    }
                    $miembros.select2();

                    $('#crearEquipoModal').modal('show');
                });
            });
        });
    </script>



@endsection