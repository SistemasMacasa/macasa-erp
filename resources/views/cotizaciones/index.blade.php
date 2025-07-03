@extends('layouts.app') {{-- o el layout base que uses --}}

@section('title', 'SIS 3.0 | Monitor de Cotizaciones')

@section('content')
    <div class="container-fluid">
        <h1 class="mb-4">Cotizaciones</h1>

        {{-- 🎛 Botonera --}}
        <div class="row-fluid gap-2 mb-3">
            <a href="{{ url()->previous() }}" class="col-md-2 btn btn-secondary btn-principal">
                <i class="fa fa-arrow-left me-1"></i> Regresar
            </a>
        </div>



        <table class="table table-hover table-bordered">
            <thead class="text-center">
                <tr>
                    <th class="header-tabla text-start text-normal">Equipo</th>
                    <th class="cotiaziones-header text-normal">Cuota Cotización</th>
                    <th class="cotiaziones-header">Alcance Cotización</th>
                    <th class="cotiaziones-header">% Cotización</th>
                    <th class="margen-header">Cuota Margen</th>
                    <th class="margen-header">Alcance Margen</th>
                    <th class="margen-header">% Margen</th>
                </tr>
            </thead>

            @foreach($equipos as $equipo)
                {{-- 1) TBODY siempre visible con la fila del líder --}}
                <tbody>
                    <tr class="cursor-pointer" data-bs-toggle="collapse" data-bs-target="#members-{{ $equipo->id }}"
                        aria-expanded="false">
                        <td>
                            <strong>
                                {{ $equipo->lider->nombre ?? 'Sin líder' }}
                                {{ $equipo->lider->apellido_p ?? '' }}
                            </strong>
                        </td>
                        <td>{{ number_format($equipo->cuota_cotizacion, 2) }}</td>
                        <td>{{ number_format($equipo->alcance_cotizacion, 2) }}</td>
                        <td>{{ number_format($equipo->porcentaje_cotizacion, 2) }}%</td>
                        <td>{{ number_format($equipo->cuota_margen, 2) }}</td>
                        <td>{{ number_format($equipo->alcance_margen, 2) }}</td>
                        <td>{{ number_format($equipo->porcentaje_margen, 2) }}%</td>
                    </tr>
                </tbody>

                {{-- 2) TBODY colapsable con miembros + alcance --}}
                <tbody id="members-{{ $equipo->id }}" class="collapse">
                    @if($equipo->usuarios->filter(fn($u) => $u->pivot->rol !== 'lider')->isEmpty())
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">
                                Este equipo no tiene miembros.
                            </td>
                        </tr>
                    @else
                        @foreach($equipo->usuarios->filter(fn($u) => $u->pivot->rol !== 'lider') as $usuario)
                            <tr>
                                <td>
                                    👤 <strong>{{ $usuario->nombre }} {{ $usuario->apellido_p }}</strong>
                                    <small class="text-muted">({{ $usuario->pivot->rol }})</small>
                                </td>
                                <td>{{ number_format($usuario->metas['cuota_cotizacion'] ?? 0, 2) }}</td>
                                <td>{{ number_format($usuario->metas['alcance_cotizacion'] ?? 0, 2) }}</td>
                                <td>{{ number_format($usuario->metas['porcentaje_cotizacion'] ?? 0, 2) }}%</td>
                                <td>{{ number_format($usuario->metas['cuota_margen'] ?? 0, 2) }}</td>
                                <td>{{ number_format($usuario->metas['alcance_margen'] ?? 0, 2) }}</td>
                                <td>{{ number_format($usuario->metas['porcentaje_margen'] ?? 0, 2) }}%</td>
                            </tr>
                        @endforeach
                    @endif

                    {{-- fila de resumen “Alcance del Equipo” --}}
                    <tr class="fw-bold bg-light">
                        <td>📊 Alcance del Equipo</td>
                        <td>{{ number_format($equipo->cuota_cotizacion, 2) }}</td>
                        <td>{{ number_format($equipo->alcance_cotizacion, 2) }}</td>
                        <td>{{ number_format($equipo->porcentaje_cotizacion, 2) }}%</td>
                        <td>{{ number_format($equipo->cuota_margen, 2) }}</td>
                        <td>{{ number_format($equipo->alcance_margen, 2) }}</td>
                        <td>{{ number_format($equipo->porcentaje_margen, 2) }}%</td>
                    </tr>
                </tbody>
            @endforeach
        </table>









        {{-- Aquí se listarán las cotizaciones --}}
        {{-- <div class="card shadow">
            <div class="card-body">
                <p class="text-muted">Aquí aparecerán las cotizaciones registradas. Usa el botón "Nueva cotización" para
                    comenzar una.</p>
                <a href="#" class="btn btn-primary">+ Nueva cotización</a>
            </div>
        </div> --}}
    </div>

    {{--
    <script>
        $(document).ready(function () {
            $('.lider-toggle').click(function () {
                let id = $(this).data('equipo');
                $('#miembros-' + id).toggle();
            });
        });
    </script> --}}

@endsection