@extends('layouts.app')
@section('title', 'SIS 3.0 | Listado de Clientes')

@section('content')
    {{-- 🧭 Migas de pan --}}
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('inicio') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Ver Cuenta</li>
    @endsection

    <h1 class="mb-4">Información de la Cuenta [xxxx]</h1>

    {{-- 🎛 Botonera --}}
    <div class="d-flex flex-wrap gap-2 mb-4">
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
        <div class="card-body">
            <label><strong>Tipo de cuenta:</strong></label>
            <input type="text" name="tipo_cuenta" value="{{ $cliente->tipo }}" class="form-control mb-2">

            <label><strong>Estatus:</strong></label>
            <input type="text" name="estatus" value="{{ $cliente->estatus }}" class="form-control mb-2">

            <label><strong>Ciclo de venta:</strong></label>
            <input type="text" name="ciclo_venta" value="{{ $cliente->ciclo_venta }}" class="form-control mb-2">

            <label><strong>Sector:</strong></label>
            <input type="text" name="sector" value="{{ $cliente->sector }}" class="form-control mb-2">

            <label><strong>Segmento:</strong></label>
            <input type="text" name="segmento" value="{{ $cliente->segmento }}" class="form-control mb-2">

            <label><strong>Nombre de la cuenta:</strong></label>
            <input type="text" name="nombre_cuenta" value="{{ $cliente->apellido_p && $cliente->apellido_m ? $cliente->nombre . ' ' . $cliente->apellido_p . ' ' . $cliente->apellido_m : $cliente->nombre }}" class="form-control mb-2">

            <label><strong>Asignado a:</strong></label>
            <input type="text" name="asignado_a" value="{{ optional($cliente->vendedor)->name }}" class="form-control mb-2">

            <div class="card-header">
            Datos de la Cuenta
            </div>

            <label><strong>Nombre:</strong></label>
            <input type="text" name="contacto_nombre" value="{{ $cliente->contacto_predet->nombre }}" class="form-control mb-2">

            <label><strong>Primer Apellido:</strong></label>
            <input type="text" name="contacto_apellido_p" value="{{ $cliente->contacto_predet->apellido_p }}" class="form-control mb-2">

            <label><strong>Segundo Apellido:</strong></label>
            <input type="text" name="contacto_apellido_m" value="{{ $cliente->contacto_predet->apellido_m }}" class="form-control mb-2">

            <label><strong>Correo:</strong></label>
            <input type="email" name="contacto_email" value="{{ $cliente->contacto_predet->email }}" class="form-control mb-2">

            <label><strong>Puesto:</strong></label>
            <input type="text" name="contacto_puesto" value="{{ $cliente->contacto_predet->puesto }}" class="form-control mb-2">

            <label><strong>Género:</strong></label>
            <input type="text" name="contacto_genero" value="{{ $cliente->contacto_predet->genero }}" class="form-control mb-2">

            @for($i = 1; $i <= 5; $i++)
            <label><strong>Teléfono {{ $i }}:</strong></label>
            <input type="text" name="telefono{{ $i }}" value="{{ $cliente->contacto_predet->{'telefono'.$i} }}" class="form-control mb-2">

            <label><strong>Extensión {{ $i }}:</strong></label>
            <input type="text" name="extension{{ $i }}" value="{{ $cliente->contacto_predet->{'ext'.$i} }}" class="form-control mb-2">

            <label><strong>Celular {{ $i }}:</strong></label>
            <input type="text" name="celular{{ $i }}" value="{{ $cliente->contacto_predet->{'celular'.$i} }}" class="form-control mb-2">
            @endfor
        </div>
    </div>

    <div class="col-md-6 col-xs-12 table-responsive card mb-4">
        <div class="card-header">
            Datos de facturación
        </div>
        <div class="card-body">
            @if($cliente->razon_social_predet && $cliente->razon_social_predet->direccion_facturacion)
                <p><strong>Razón Social:</strong> {{ $cliente->razon_social_predet->nombre }}</p>
                <p><strong>RFC:</strong> {{ $cliente->razon_social_predet->RFC }}</p>
                <p><strong>Dirección:</strong>
                    {{ $cliente->razon_social_predet->direccion_facturacion->calle }}
                    {{ $cliente->razon_social_predet->direccion_facturacion->num_ext }}
                    {{ $cliente->razon_social_predet->direccion_facturacion->num_int }},
                    {{ $cliente->razon_social_predet->direccion_facturacion->colonia }},
                    C.P. {{ $cliente->razon_social_predet->direccion_facturacion->cp }},
                    {{ $cliente->razon_social_predet->direccion_facturacion->ciudad->nombre }},
                    {{ $cliente->razon_social_predet->direccion_facturacion->estado->nombre }},
                    {{ $cliente->razon_social_predet->direccion_facturacion->pais->nombre }},
                </p>
                <p><strong>Uso CFDI:</strong> {{ $cliente->razon_social_predet->uso_cfdi->nombre ?? '—' }}</p>
                <p><strong>Método de pago:</strong> {{ $cliente->razon_social_predet->metodo_pago->nombre ?? '—' }}</p>
                <p><strong>Forma de pago:</strong> {{ $cliente->razon_social_predet->forma_pago->nombre ?? '—' }}</p>
                <p><strong>Régimen fiscal:</strong> {{ $cliente->razon_social_predet->regimen_fiscal->nombre ?? '—' }}</p>
                <hr>
            @else
                <p>No hay datos de facturación registrados.</p>
            @endif
        </div>

            <div class="card-header">Datos de entrega</div>
            <div class="card-body">
            @if($cliente->contacto_entrega_predet && $cliente->contacto_entrega_predet->direccion_entrega)
                <p><strong>Contacto:</strong> {{ $cliente->contacto_entrega_predet->nombre }}</p>
                <p><strong>Teléfono:</strong> {{ $cliente->contacto_entrega_predet->telefono1 }}</p>
                <p><strong>Dirección:</strong>
                    {{ $cliente->contacto_entrega_predet->direccion_entrega->calle }}
                    {{ $cliente->contacto_entrega_predet->direccion_entrega->num_ext }}
                    {{ $cliente->contacto_entrega_predet->direccion_entrega->num_int }},
                    {{ $cliente->contacto_entrega_predet->direccion_entrega->colonia }},
                    C.P. {{ $cliente->contacto_entrega_predet->direccion_entrega->cp }},
                    {{ $cliente->contacto_entrega_predet->direccion_entrega->ciudad->nombre ?? '—' }},
                    {{ $cliente->contacto_entrega_predet->direccion_entrega->estado->nombre ?? '—' }},
                    {{ $cliente->contacto_entrega_predet->direccion_entrega->pais->nombre ?? '—' }}
                </p>

            @else
                <p>No hay datos de entrega predeterminados.</p>
            @endif

            </div>
    </div>
</div>


    


@endsection