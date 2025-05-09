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

<div class="table-responsive card mb-4">
    <div class="card-header">
        Datos de contacto
    </div>
    <div class="card-body">
        <p><strong>Tipo de cuenta:</strong> {{ $cliente->tipo_cuenta }}</p>
        <p><strong>Estatus:</strong> {{ $cliente->estatus }}</p>
        <p><strong>Ciclo de venta:</strong> {{ $cliente->ciclo_venta }}</p>
        <p><strong>Sector:</strong> {{ $cliente->sector }}</p>
        <p><strong>Segmento:</strong> {{ $cliente->segmento }}</p>
        <p><strong>Nombre de la cuenta:</strong> 
            @if($cliente->apellido_p && $cliente->apellido_m)
                {{ $cliente->nombre }} {{ $cliente->apellido_p }} {{ $cliente->apellido_m }}
            @else
                {{ $cliente->nombre }}
            @endif
        </p>
        <p><strong>Asignado a:</strong> {{ optional($cliente->vendedor)->name }}</p>

        <hr>
        <span class="fw-bold">Datos de contacto</span>
        <p><strong>Nombre:</strong> {{ $cliente->contacto_predet->nombre }}</p>
        <p><strong>Apellido Paterno:</strong> {{ $cliente->contacto_predet->apellido_p }}</p>
        <p><strong>Apellido Materno:</strong> {{ $cliente->contacto_predet->apellido_m }}</p>
        <p><strong>Correo:</strong> {{ $cliente->contacto_predet->email }}</p>
        <p><strong>Puesto:</strong> {{ $cliente->contacto_predet->puesto }}</p>
        <p><strong>Género:</strong> {{ $cliente->contacto_predet->genero }}</p>

        @for($i = 1; $i <= 5; $i++)
            <p><strong>Teléfono {{ $i }}:</strong> {{ $cliente->contacto_predet->{'telefono'.$i} }}</p>
            <p><strong>Extensión {{ $i }}:</strong> {{ $cliente->contacto_predet->{'ext'.$i} }}</p>
            <p><strong>Celular {{ $i }}:</strong> {{ $cliente->contacto_predet->{'celular'.$i} }}</p>
        @endfor
    </div>
</div>

<div class="card mb-4">
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
</div>

<div class="card mb-4">
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


@endsection