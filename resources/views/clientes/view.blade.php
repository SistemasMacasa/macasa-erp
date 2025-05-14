@extends('layouts.app')
@section('title', 'SIS 3.0 | Listado de Clientes')

@section('content')
    {{-- 🧭 Migas de pan --}}
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('inicio') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Ver Cuenta</li>
    @endsection

    <h1 class="mb-4">Información de la Cuenta [{{ $cliente->id_cliente }}]</h1>

    {{-- 🎛 Botonera --}}
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ url()->previous() }}" class="btn btn-light">
            <i class="fa fa-arrow-left me-1"></i> Regresar
        </a>

        <button type="submit" class="btn btn-success">
            <i class="fa fa-save me-1"></i> Guardar
        </button>

        <a href="{{ route('clientes.index') }}" class="btn btn-outline-primary">
            <i class="fa fa-list me-1"></i> Mis Cuentas
        </a>

        <a href="{{ route('inicio', ['cliente' => $cliente->id]) }}" class="btn btn-primary">
            <i class="fa fa-file-invoice-dollar me-1"></i> Levantar Cotización
        </a>

        <a href="{{ route('inicio', ['cliente' => $cliente->id]) }}" class="btn btn-secondary">
            <i class="fa fa-address-book me-1"></i> Libreta de Contactos
        </a>
    </div>

    <div class="row">
        <div class="col-md-6 col-xs-12 table-responsive card mb-4">
            <div class="card-header">
                Datos de la Cuenta
            </div>
            <div class="card-body p-0">
                <div class="row px-3 py-2">
                    <div class="col-md-3">
                        <label><strong>Estatus:</strong></label>
                        <input type="text" name="estatus" value="{{ $cliente->estatus }}" class="form-control mb-2">
                    </div>
                    <div class="col-md-3">
                        <label><strong>Ciclo de venta:</strong></label>
                        <input type="text" name="ciclo_venta" value="{{ $cliente->ciclo_venta }}" class="form-control mb-2">
                    </div>
                    <div class="col-md-3">
                        <label><strong>Sector:</strong></label>
                        <input type="text" name="sector" value="{{ $cliente->sector }}" class="form-control mb-2">
                    </div>
                    <div class="col-md-3">
                        <label><strong>Segmento:</strong></label>
                        <input type="text" name="segmento" value="{{ $cliente->segmento }}" class="form-control mb-2">
                    </div>
                    <div class="col-md-12">
                        <label><strong>Nombre de la cuenta:</strong></label>
                        <input type="text" name="nombre_cuenta"
                            value="{{ $cliente->apellido_p && $cliente->apellido_m ? $cliente->nombre . ' ' . $cliente->apellido_p . ' ' . $cliente->apellido_m : $cliente->nombre }}"
                            class="form-control mb-2">
                    </div>
                    <div class="col-md-6">
                        <label><strong>Asignado a:</strong></label>
                        <input type="text" name="asignado_a" value="{{ optional($cliente->vendedor)->name }}"
                            class="form-control mb-2">
                    </div>
                    <div class="col-md-6">
                        <label><strong>Origen de la cuenta:</strong></label>
                        <input type="text" name="tipo_cuenta" value="{{ $cliente->tipo }}" class="form-control mb-0">
                    </div>
                </div>
            </div>
            <div class="card-header">
                Datos de Contacto
            </div>
            <div class="card-body">

                <div class="row">

                    <div class="col-md-12">
                        <label><strong>Nombre:</strong></label>
                        <input type="text" name="contacto_nombre" value="{{ $cliente->contacto_predet->nombre }}"
                            class="form-control mb-2">
                    </div>
                    <div class="col-md-6">
                        <label><strong>Primer Apellido:</strong></label>
                        <input type="text" name="contacto_apellido_p" value="{{ $cliente->contacto_predet->apellido_p }}"
                            class="form-control mb-2">
                    </div>
    
                    <div class="col-md-6">
                        <label><strong>Segundo Apellido:</strong></label>
                        <input type="text" name="contacto_apellido_m" value="{{ $cliente->contacto_predet->apellido_m }}"
                            class="form-control mb-2">
                    </div>
                    <div class="col-md-4">
                        <label><strong>Género:</strong></label>
                        <input type="text" name="contacto_genero" value="{{ $cliente->contacto_predet->genero }}"
                            class="form-control mb-2">
                    </div>
                    <div class="col-md-4">
                        <label><strong>Puesto:</strong></label>
                        <input type="text" name="contacto_puesto" value="{{ $cliente->contacto_predet->puesto }}"
                            class="form-control mb-2">
                    </div>
                    <div class="col-md-4">
                        <label><strong>Activo desde:</strong></label>
                        <input type="text" name="contacto_puesto" value="{{ $cliente->contacto_predet->created_at }}"
                            class="form-control mb-2">
                    </div>
                    <div class="col-md-4">
                        <label><strong>Volver a llamar:</strong></label>
                        <input type="text" name="contacto_puesto" value="{{ $cliente->contacto_predet->created_at }}"
                            class="form-control mb-2">
                    </div>
                    <div class="col-md-4">
                        <label><strong>Entrega:</strong></label>
                        <input type="text" name="contacto_telefono" value=""
                            class="form-control mb-2">
                    </div>
                    <div class="col-md-4">
                        <label><strong>Condición de pago:</strong></label>
                        <input type="email" name="contacto_email" value=""
                            class="form-control mb-2">
                    </div>
    
                    @for($i = 1; $i <= 5; $i++)
                        @if($cliente->contacto_predet->{'telefono' . $i} || $cliente->contacto_predet->{'celular' . $i})
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <label><strong>Teléfono {{ $i }}:</strong></label>
                                    <input type="text" name="telefono{{ $i }}" value="{{ $cliente->contacto_predet->{'telefono' . $i} }}"
                                        class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label><strong>Extensión {{ $i }}:</strong></label>
                                    <input type="text" name="extension{{ $i }}" value="{{ $cliente->contacto_predet->{'ext' . $i} }}"
                                        class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label><strong>Celular {{ $i }}:</strong></label>
                                    <input type="text" name="celular{{ $i }}" value="{{ $cliente->contacto_predet->{'celular' . $i} }}"
                                        class="form-control">
                                </div>
                            </div>
                        @endif
                    @endfor
                    <div class="col-md-12">
                        <label><strong>Correo electrónico:</strong></label>
                        <input type="email" name="contacto_email" value="{{ $cliente->contacto_predet->email }}"
                            class="form-control mb-2">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xs-12 table-responsive card mb-4">
            <div class="card-header">
                Datos de facturación
            </div>
            <div class="card-body">
                @php
                    $razon = $cliente->razon_social_predet;
                    $direccion = $razon->direccion_facturacion ?? null;
                @endphp

                <div class="row">
                    <div class="col-md-12">
                        <label><strong>Razón Social:</strong></label>
                        <input type="text" name="razon_social" value="{{ old('razon_social', $razon->nombre ?? '') }}" class="form-control mb-2">
                    </div>
                    <div class="col-md-6">
                        <label><strong>RFC:</strong></label>
                        <input type="text" name="rfc" value="{{ old('rfc', $razon->RFC ?? '') }}" class="form-control mb-2">
                    </div>
                    <div class="w-100"></div> {{-- Fuerza nueva línea --}}

                    <div class="col-md-6">
                        <label><strong>Calle:</strong></label>
                        <input type="text" name="direccion" value="{{ old('direccion', $direccion->calle ?? '') }}" class="form-control mb-2">
                    </div>
                    <div class="col-md-3">
                        <label><strong>Num. Ext.:</strong></label>
                        <input type="text" name="direccion" value="{{ old('direccion', $direccion->calle ?? '') }}" class="form-control mb-2">
                    </div>
                    <div class="col-md-3">
                        <label><strong>Num. Int.:</strong></label>
                        <input type="text" name="direccion" value="{{ old('direccion', $direccion->calle ?? '') }}" class="form-control mb-2">
                    </div>
                    <div class="col-md-4">
                        <label><strong>Colonia:</strong></label>
                        <input type="text" name="direccion" value="{{ old('direccion', $direccion->calle ?? '') }}" class="form-control mb-2">
                    </div>
                    <div class="col-md-2">
                        <label><strong>CP:</strong></label>
                        <input type="text" name="direccion" value="{{ old('direccion', $direccion->calle ?? '') }}" class="form-control mb-2">
                    </div>
                    <div class="col-md-3">
                        <label><strong>Delegación o Municipio:</strong></label>
                        <input type="text" name="direccion" value="{{ old('direccion', $direccion->calle ?? '') }}" class="form-control mb-2">
                    </div>
                    <div class="col-md-3">
                        <label><strong>Estado:</strong></label>
                        <input type="text" name="direccion" value="{{ old('direccion', $direccion->calle ?? '') }}" class="form-control mb-2">
                    </div>
                    <div class="col-md-6">
                        <label><strong>País:</strong></label>
                        <input type="text" name="direccion" value="{{ old('direccion', $direccion->calle ?? '') }}" class="form-control mb-2">
                    </div>
                    <div class="w-100"></div> {{-- Fuerza nueva línea --}}


                    <div class="col-md-6">
                        <label><strong>Uso CFDI:</strong></label>
                        <input type="text" name="uso_cfdi" value="{{ old('uso_cfdi', $razon->uso_cfdi->nombre ?? '') }}" class="form-control mb-2">
                    </div>
                    <div class="col-md-6">
                        <label><strong>Método de pago:</strong></label>
                        <input type="text" name="metodo_pago" value="{{ old('metodo_pago', $razon->metodo_pago->nombre ?? '') }}" class="form-control mb-2">
                    </div>
                    <div class="col-md-6">
                        <label><strong>Forma de pago:</strong></label>
                        <input type="text" name="forma_pago" value="{{ old('forma_pago', $razon->forma_pago->nombre ?? '') }}" class="form-control mb-2">
                    </div>
                    <div class="col-md-6">
                        <label><strong>Régimen fiscal:</strong></label>
                        <input type="text" name="regimen_fiscal" value="{{ old('regimen_fiscal', $razon->regimen_fiscal->nombre ?? '') }}" class="form-control mb-2">
                    </div>
                </div>
            </div>


            <div class="card-header">
                Datos de entrega
            </div>
            <div class="card-body">
                @php
                    $contacto = $cliente->contacto_entrega_predet;
                    $direccion = $contacto->direccion_entrega ?? null;
                @endphp

                <div class="row">
                    <div class="col-md-6">
                        <label><strong>Contacto:</strong></label>
                        <input type="text" name="contacto_entrega" value="{{ old('contacto_entrega', $contacto->nombre ?? '') }}" class="form-control mb-2">
                    </div>

                    <div class="col-md-6">
                        <label><strong>Teléfono:</strong></label>
                        <input type="text" name="telefono_entrega" value="{{ old('telefono_entrega', $contacto->telefono1 ?? '') }}" class="form-control mb-2">
                    </div>

                    <div class="w-100"></div> {{-- Salto de línea --}}

                    <div class="col-md-6">
                        <label><strong>Calle y número:</strong></label>
                        <input type="text" name="direccion_calle" value="{{ old('direccion_calle', $direccion->calle ?? '') }} {{ old('direccion_num_ext', $direccion->num_ext ?? '') }} {{ old('direccion_num_int', $direccion->num_int ?? '') }}" class="form-control mb-2">
                    </div>

                    <div class="col-md-6">
                        <label><strong>Colonia:</strong></label>
                        <input type="text" name="colonia" value="{{ old('colonia', $direccion->colonia ?? '') }}" class="form-control mb-2">
                    </div>

                    <div class="w-100"></div>

                    <div class="col-md-4">
                        <label><strong>C.P.:</strong></label>
                        <input type="text" name="cp" value="{{ old('cp', $direccion->cp ?? '') }}" class="form-control mb-2">
                    </div>

                    <div class="col-md-4">
                        <label><strong>Ciudad:</strong></label>
                        <input type="text" name="ciudad" value="{{ old('ciudad', $direccion->ciudad->nombre ?? '') }}" class="form-control mb-2">
                    </div>

                    <div class="col-md-4">
                        <label><strong>Estado:</strong></label>
                        <input type="text" name="estado" value="{{ old('estado', $direccion->estado->nombre ?? '') }}" class="form-control mb-2">
                    </div>

                    <div class="w-100"></div>

                    <div class="col-md-4">
                        <label><strong>País:</strong></label>
                        <input type="text" name="pais" value="{{ old('pais', $direccion->pais->nombre ?? '') }}" class="form-control mb-2">
                    </div>
                </div>
            </div>


        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Historial de notas
                </div>
                <div class="card-body">

                    {{-- Área scrolleable con historial --}}
                    <div class="form-group mb-4">
                        <textarea class="form-control" rows="10" readonly style="resize: none; background: #f5f5f5; overflow-y: scroll;">
                            @foreach ($notas as $nota)
                            {{ \Carbon\Carbon::parse($nota->fecha_registro)->format('d-m-Y h:i A') }} - EJECUTIVO: {{ $nota->usuario->nombre_completo ?? '—' }} - ETAPA: {{ strtoupper($nota->etapa) }}

                            {!! $nota->contenido !!}

                            @if ($nota->fecha_reprogramacion)
                            ========
                            Llamada reprogramada para: {{ \Carbon\Carbon::parse($nota->fecha_reprogramacion)->format('d-m-Y') }}
                            @endif

                            ========

                            @endforeach
                        </textarea>
                    </div>

                    {{-- Formulario para anexar nueva nota --}}
                    <form action="{{ route('inicio') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_cliente" value="{{ $cliente->id_cliente }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Volver a llamar</label>
                                <input type="date" name="fecha_reprogramacion" class="form-control">
                            </div>

                            <div class="col-md-3">
                                <label>Ciclo de venta *</label>
                                <select name="etapa" class="form-control">
                                    <option value="cotizacion">Cotización</option>
                                    <option value="venta">Venta</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label>¿Es cotización?</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="es_cotizacion" value="1" id="es_cotizacion_si">
                                    <label class="form-check-label" for="es_cotizacion_si">Sí</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="es_cotizacion" value="0" id="es_cotizacion_no" checked>
                                    <label class="form-check-label" for="es_cotizacion_no">No</label>
                                </div>
                            </div>

                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-success w-100">Anexar nota</button>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <label>Nota:</label>
                            <textarea name="contenido" rows="3" class="form-control" required></textarea>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>





@endsection