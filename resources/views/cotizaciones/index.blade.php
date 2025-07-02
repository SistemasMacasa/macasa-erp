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

        <div class="table-responsive mb-3 shadow-lg">
            @if ($equipos->isEmpty())
                <p>No hay equipos registrados.</p>
            @else
                <table class="table table-striped table-hover table-bordered align-middle">
                    <thead class="text-center">
                        <tr>
                            <th class="header-tabla">Equipo / Miembro</th>
                            <th class="header-tabla">Cuota Cotización</th>
                            <th class="header-tabla">Alcance Cotización</th>
                            <th class="header-tabla">% Cotización</th>
                            <th class="header-tabla">Cuota Margen</th>
                            <th class="header-tabla">Alcance Margen</th>
                            <th class="header-tabla">% Margen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($equipos as $equipo)
                            {{-- Fila del equipo --}}
                            <tr class="cursor-pointer" data-bs-toggle="collapse" data-bs-target=".equipo-{{ $equipo->id }}"
                                aria-expanded="false" aria-controls="equipo-{{ $equipo->id }}">
                                <td>
                                    <strong>{{ $equipo->nombre }}</strong>
                                </td>
                                <td class="text-end">{{ number_format($equipo->cuota_cotizacion, 2) }}</td>
                                <td class="text-end">{{ number_format($equipo->alcance_cotizacion, 2) }}</td>
                                <td class="text-end">{{ number_format($equipo->porcentaje_cotizacion, 2) }}%</td>
                                <td class="text-end">{{ number_format($equipo->cuota_margen, 2) }}</td>
                                <td class="text-end">{{ number_format($equipo->alcance_margen, 2) }}</td>
                                <td class="text-end">{{ number_format($equipo->porcentaje_margen, 2) }}%</td>
                            </tr>

                            {{-- Filas de miembros --}}
                            @if ($equipo->usuarios->isEmpty())
                                <tr class="collapse equipo-{{ $equipo->id }}">
                                    <td colspan="7" class="text-center text-muted">
                                        Este equipo no tiene miembros.
                                    </td>
                                </tr>
                            @else
                                @foreach ($equipo->usuarios as $usuario)
                                    <tr class="collapse equipo-{{ $equipo->id }}">
                                        <td>
                                            {{ $usuario->nombre }} {{ $usuario->apellido_p }} ({{ $usuario->pivot->rol }})
                                        </td>
                                        <td class="text-end">{{ number_format($usuario->metas['cuota_cotizacion'] ?? 0, 2) }}</td>
                                        <td class="text-end">{{ number_format($usuario->metas['alcance_cotizacion'] ?? 0, 2) }}</td>
                                        <td class="text-end">{{ number_format($usuario->metas['porcentaje_cotizacion'] ?? 0, 2) }}%</td>
                                        <td class="text-end">{{ number_format($usuario->metas['cuota_margen'] ?? 0, 2) }}</td>
                                        <td class="text-end">{{ number_format($usuario->metas['alcance_margen'] ?? 0, 2) }}</td>
                                        <td class="text-end">{{ number_format($usuario->metas['porcentaje_margen'] ?? 0, 2) }}%</td>
                                    </tr>
                                @endforeach
                            @endif

                            {{-- Fila de alcance del equipo --}}
                            <tr class="collapse equipo-{{ $equipo->id }} table-secondary fw-bold">
                                <td>Alcance del Equipo</td>
                                <td class="text-end">{{ number_format($equipo->cuota_cotizacion, 2) }}</td>
                                <td class="text-end">{{ number_format($equipo->alcance_cotizacion, 2) }}</td>
                                <td class="text-end">{{ number_format($equipo->porcentaje_cotizacion, 2) }}%</td>
                                <td class="text-end">{{ number_format($equipo->cuota_margen, 2) }}</td>
                                <td class="text-end">{{ number_format($equipo->alcance_margen, 2) }}</td>
                                <td class="text-end">{{ number_format($equipo->porcentaje_margen, 2) }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>








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