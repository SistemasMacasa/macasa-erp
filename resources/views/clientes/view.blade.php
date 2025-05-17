@extends('layouts.app')
@section('title', 'SIS 3.0 | Listado de Clientes')

@section('content')
    {{-- 🧭 Migas de pan --}}
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('inicio') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Mis Cuentas</a></li>
        <li class="breadcrumb-item active">Ver Cuenta</li>
    @endsection

    <h1 class="mb-4">Información de la Cuenta [{{ $cliente->id_cliente }}]</h1>

    {{-- 🎛 Botonera --}}
    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="{{ url()->previous() }}" class="btn btn-light">
            <i class="fa fa-arrow-left me-1"></i> Regresar
        </a>

        <button type="submit"
                class="btn btn-success"
                form="formCuenta">   {{-- ⬅️ Vincula al form por id --}}
            <i class="fa fa-save me-1"></i> Guardar
        </button>

        <a href="{{ route('clientes.index') }}" class="btn btn-info">
            <i class="fa fa-list me-1"></i> Mis Cuentas
        </a>

        <a href="{{ route('inicio', ['cliente' => $cliente->id]) }}" class="btn btn-primary">
            <i class="fa fa-file-invoice-dollar me-1"></i> Levantar Cotización
        </a>

        <a href="{{ route('inicio', ['cliente' => $cliente->id]) }}" class="btn btn-secondary">
            <i class="fa fa-address-book me-1"></i> Libreta de Contactos
        </a>
    </div>

    <div class="alert alert-warning" role="alert">
        <i class="fa fa-exclamation-triangle me-2"></i>
        Este formulario sigue en construcción y actualmente no es funcional.
    </div>

    <div class="d-flex justify-content-between align-items-center mb-2">

        {{-- Flecha anterior --}}
        @if ($prevId)
            <a href="{{ route('clientes.view', $prevId) }}"
            class="btn btn-outline-secondary btn-sm">
            <i class="fa fa-chevron-left"></i>
            </a>
        @else
            <button class="btn btn-outline-secondary btn-sm" disabled>
                <i class="fa fa-chevron-left"></i>
            </button>
        @endif

        <span class="fw-bold">{{ $cliente->nombre }}</span>

        {{-- Flecha siguiente --}}
        @if ($nextId)
            <a href="{{ route('clientes.view', $nextId) }}"
            class="btn btn-outline-secondary btn-sm">
            <i class="fa fa-chevron-right"></i>
            </a>
        @else
            <button class="btn btn-outline-secondary btn-sm" disabled>
                <i class="fa fa-chevron-right"></i>
            </button>
        @endif
    </div>


    <form action="{{ route('clientes.update', $cliente->id_cliente) }}" id="formCuenta" method="POST">
        @csrf
        @method('PUT')
        {{-- Inicio row superior --}}
        <div class="row">
            
            {{-- Columna izquierda (6/12) --------------------------------------------------}}
            <div class="col-md-6 col-xs-12">

                {{-- ───── Tarjeta: Datos de la Cuenta ───── --}}
                <div class="card mb-3 shadow-lg"> 
                    <div class="card-header fw-bold d-flex justify-content-between align-items-center" style="background-color: rgba(81, 86, 190, 0.1);">
                        <span>Datos de la Cuenta</span>
                        <button id="btnEditar" type="button" class="btn btn-primary btn-sm">
                            <i class="fa fa-edit me-1"></i> Editar Cuenta
                        </button>
                    </div>


                    <div class="card-body px-3 py-2">
                        <div class="row g-3"> {{-- g-3 = gutter vertical + horizontal uniforme --}}
                            
                        
                            @php
                                $estatusActual = old('estatus', $cliente->estatus);   // activo | inactivo
                            @endphp

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Estatus</label>

                                <select name="estatus"
                                        id="selectEstatus"
                                        class="form-select form-select-sm"
                                        disabled>           {{-- se habilita con el botón “Editar cuenta” --}}

                                    <option value="activo"   {{ $estatusActual === 'activo'   ? 'selected' : '' }}>Activo</option>
                                    <option value="inactivo" {{ $estatusActual === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>


                            <div class="col-md-3">
                                <label class="form-label fw-bold">Ciclo de venta</label>
                                <input type="text" 
                                    value="{{ $cliente->ciclo_venta === 'cotizacion' ? 'Cotización' : ($cliente->ciclo_venta === 'venta' ? 'Venta' : $cliente->ciclo_venta) }}" 
                                    class="form-control form-control-sm no-editar"  
                                    disabled>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Sector</label>

                                @if ($cliente->sector === 'persona')
                                    <select class="form-select form-select-sm" disabled>
                                        <option value="{{ $cliente->sector }}">Persona Física</option>
                                    </select>

                                @else
                                    {{-- Empresa: privada | gobierno (se habilita con “Editar cuenta”) --}}
                                    <select name="sector"
                                            class="form-select form-select-sm"
                                            disabled>        {{-- tu botón quitará este atributo --}}

                                        <option value="">— Selecciona —</option>

                                        <option value="privada"
                                            {{ $cliente->sector === 'privada' ? 'selected' : '' }}>
                                            Empresa Privada
                                        </option>

                                        <option value="gobierno"
                                            {{ $cliente->sector === 'gobierno' ? 'selected' : '' }}>
                                            Empresa Gobierno
                                        </option>
                                    </select>
                                @endif
                            </div>


                            <div class="col-md-3">
                                <label class="form-label fw-bold">Segmento</label>

                                <select name="segmento"
                                        class="form-select form-select-sm"
                                        disabled> 

                                    <option value="">— Selecciona —</option>

                                    <option value="macasa cuentas especiales"
                                        {{ $cliente->segmento === 'macasa cuentas especiales' ? 'selected' : '' }}>
                                        Macasa Cuentas Especiales
                                    </option>

                                    <option value="macasa ecommerce"
                                        {{ $cliente->segmento === 'macasa ecommerce' ? 'selected' : '' }}>
                                        Macasa E-commerce
                                    </option>

                                    <option value="tekne store ecommerce"
                                        {{ $cliente->segmento === 'tekne store ecommerce' ? 'selected' : '' }}>
                                        Tekne Store E-commerce
                                    </option>

                                    <option value="la plaza ecommerce"
                                        {{ $cliente->segmento === 'la plaza ecommerce' ? 'selected' : '' }}>
                                        La Plaza E-commerce
                                    </option>
                                </select>
                            </div>


                            <div class="col-md-12">
                                <label class="form-label fw-bold">Nombre de la cuenta</label>
                                <input type="text"
                                    value="{{ $cliente->apellido_p && $cliente->apellido_m
                                            ? $cliente->nombre.' '.$cliente->apellido_p.' '.$cliente->apellido_m
                                            : $cliente->nombre }}"
                                    class="form-control form-control-sm guarda-mayus"  disabled>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Asignado a</label>

                                <select name="id_vendedor"
                                        class="form-select form-select-sm"
                                        {{ old('modo') === 'edicion' ? '' : 'disabled' }}>

                                    <option value="">— Selecciona —</option>
                                    <option value="" @selected(old('id_vendedor')==='')>Base General</option>

                                    @foreach ($vendedores as $v)
                                        <option value="{{ $v->id_usuario }}"
                                            {{ old('id_vendedor', $cliente->id_vendedor) == $v->id_usuario ? 'selected' : '' }}>
                                            {{ $v->nombre }} {{ $v->apellido_p }} {{ $v->apellido_m }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Origen de la cuenta</label>
                                <input type="text" value="{{ $cliente->tipo }}"
                                    class="form-control form-control-sm no-editar"  disabled>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ───── Tarjeta: Datos de Contacto ───── --}}
                <div class="card mb-3 shadow-lg">
                    {{-- Cabecera --}}
                    <div class="card-header fw-bold" style="background-color: rgba(81, 86, 190, 0.1);">Datos de Contacto</div>

                    <div class="card-body px-3 py-2">
                        <div class="row g-3">

                            <div class="col-md-12">
                                <label class="form-label fw-bold">Nombre</label>
                                <input type="text" value="{{ $cliente->contacto_predet->nombre ?? ''}}"
                                    class="form-control form-control-sm guarda-mayus"  disabled>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Primer Apellido</label>
                                <input type="text" value="{{ $cliente->contacto_predet->apellido_p ?? ''}}"
                                    class="form-control form-control-sm guarda-mayus"  disabled>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Segundo Apellido</label>
                                <input type="text" value="{{ $cliente->contacto_predet->apellido_m ?? ''}}"
                                    class="form-control form-control-sm guarda-mayus"  disabled>
                            </div>

                            @php
                                // Toma el género guardado o, si hay reenvío de formulario con error,
                                // conserva el valor que el usuario había elegido.
                                $generoActual = old('genero', optional($cliente->contacto_predet)->genero);
                            @endphp

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Género</label>

                                <select name="genero"
                                        class="form-select form-select-sm"
                                        disabled>   {{-- se habilita al pulsar "Editar cuenta" --}}

                                    <option value="">— Selecciona —</option>

                                    <option value="masculino"
                                        {{ $generoActual === 'masculino' ? 'selected' : '' }}>
                                        Masculino
                                    </option>

                                    <option value="femenino"
                                        {{ $generoActual === 'femenino' ? 'selected' : '' }}>
                                        Femenino
                                    </option>

                                    <option value="no-especificado"
                                        {{ $generoActual === 'no-especificado' ? 'selected' : '' }}>
                                        No especificado
                                    </option>
                                </select>
                            </div>


                            <div class="col-md-4">
                                <label class="form-label fw-bold">Puesto</label>
                                <input type="text" value="{{ $cliente->contacto_predet->puesto ?? ''}}"
                                    class="form-control form-control-sm guarda-mayus"  disabled>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Activo desde</label>
                                <input type="text" value="{{ $cliente->contacto_predet->created_at ?? ''}}"
                                    class="form-control form-control-sm"  disabled>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Volver a llamar</label>
                                <input type="text" value="{{ $cliente->contacto_predet->created_at ?? ''}}"
                                    class="form-control form-control-sm"  disabled>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Entrega</label>
                                <input type="text" class="form-control form-control-sm guarda-mayus"  disabled>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Condición de pago</label>
                                <input type="text" class="form-control form-control-sm guarda-mayus"  disabled>
                            </div>

                            {{-- Teléfonos / celulares dinámicos --}}
                            @for ($i = 1; $i <= 5; $i++)
                                @php
                                    $tel  = $cliente->contacto_predet->{'telefono'.$i} ?? '';
                                    $cel  = $cliente->contacto_predet->{'celular'.$i} ?? '';
                                    $ext  = $cliente->contacto_predet->{'ext'.$i} ?? '';
                                @endphp
                                @if ($tel || $cel || $i == 1)
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Teléfono {{ $i }}</label>
                                        <input type="text" value="{{ $tel ?? ''}}" class="form-control form-control-sm phone-field"  disabled>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-bold">Extensión {{ $i }}</label>
                                        <input type="text" value="{{ $ext ?? '' }}" class="form-control form-control-sm" maxlength="7" disabled>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Celular {{ $i }}</label>
                                        <input type="text" value="{{ $cel ?? '' }}" class="form-control form-control-sm phone-field"  disabled>
                                    </div>
                                @endif
                            @endfor

                            <div class="col-md-12">
                                <label class="form-label fw-bold">Correo electrónico</label>
                                <input type="email" value="{{ $cliente->contacto_predet->email ?? '' }}"
                                    class="form-control form-control-sm guarda-minus"  disabled>
                            </div>
                        </div>
                    </div>
                </div>

            </div>{{-- Fin columna izquierda --}}

            {{-- ───────────── Columna derecha 6/12 ───────────── --}}
            <div class="col-md-6 col-xs-12">

                {{-- ╭━━━━ Tarjeta: Datos de facturación ━━━━╮ --}}
                <div class="card mb-3 shadow-lg">
                    <div class="card-header fw-bold" style="background-color: rgba(81, 86, 190, 0.1);">Datos de facturación</div>

                    @php
                        $razon     = $cliente->razon_social_predet;
                        $dirFac    = $razon->direccion_facturacion ?? null;
                    @endphp

                    <div class="card-body px-3 py-2">
                        
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Razón Social</label>
                                <input class="form-control form-control-sm guarda-mayus" type="text"
                                    value="{{ $razon->nombre ?? '' }}"  disabled>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">RFC</label>
                                <input class="form-control form-control-sm guarda-mayus" type="text"
                                    value="{{ $razon->RFC ?? '' }}"  disabled>
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            {{-- ------------- Dirección ------------- --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Calle</label>
                                <input class="form-control form-control-sm guarda-titulo" type="text"
                                    value="{{ $dirFac->calle ?? '' }}"  disabled>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Num. Ext.</label>
                                <input class="form-control form-control-sm" type="text"
                                    value="{{ $dirFac->num_ext ?? '' }}"  disabled>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Num. Int.</label>
                                <input class="form-control form-control-sm" type="text"
                                    value="{{ $dirFac->num_int ?? '' }}"  disabled>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Colonia</label>
                                <input class="form-control form-control-sm" type="text"
                                    value="{{ $dirFac->colonia ?? '' }}"  disabled>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-bold">C.P.</label>
                                <input class="form-control form-control-sm" type="text"
                                    value="{{ $dirFac->cp ?? '' }}"  disabled>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Municipio</label>
                                <input class="form-control form-control-sm" type="text"
                                    value="{{ $dirFac->ciudad->nombre ?? '' }}"  disabled>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Estado</label>
                                <input class="form-control form-control-sm" type="text"
                                    value="{{ $dirFac->estado->nombre ?? '' }}"  disabled>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">País</label>
                                <input class="form-control form-control-sm" type="text"
                                    value="{{ $dirFac->pais->nombre ?? '' }}"  disabled>
                            </div>
                        </div>
                        <div class="row g-3 mt-1">
                            {{-- ------------- Catálogos SAT ------------- --}}

                            {{-- Uso CFDI --}}
                            @php
                                // id guardado en la razón social (o el valor reenviado tras validación)
                                $usoActual = old('id_uso_cfdi', optional($razon)->id_uso_cfdi);
                            @endphp
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Uso CFDI</label>

                                <select name="id_uso_cfdi"
                                        class="form-select form-select-sm"
                                        disabled>           {{-- el botón “Editar cuenta” quitará este atributo --}}

                                    <option value="">— Selecciona —</option>

                                    @foreach ($usos_cfdi as $id => $nombre)
                                        <option value="{{ $id }}" {{ $usoActual == $id ? 'selected' : '' }}>
                                            {{ $nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Método de pago --}}
                            @php
                                // id guardado en la razón social (o el valor reenviado tras validación)
                                $metodoActual = old('id_metodo_pago', optional($razon)->id_metodo_pago);
                            @endphp
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Método de pago</label>

                                <select name="id_metodo_pago"
                                        class="form-select form-select-sm"
                                        disabled>           {{-- se habilita con el botón Editar cuenta --}}

                                    <option value="">— Selecciona —</option>

                                    @foreach ($metodos_pago as $id => $nombre)
                                        <option value="{{ $id }}" {{ $metodoActual == $id ? 'selected' : '' }}>
                                            {{ $nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Forma de pago --}}
                            @php
                                // id guardado o valor reenviado tras validación
                                $formaActual = old('id_forma_pago', optional($razon)->id_forma_pago);
                            @endphp
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Forma de pago</label>

                                <select name="id_forma_pago"
                                        class="form-select form-select-sm"
                                        disabled>           {{-- se habilita con el botón “Editar cuenta” --}}

                                    <option value="">— Selecciona —</option>

                                    @foreach ($formas_pago as $id => $nombre)
                                        <option value="{{ $id }}" {{ $formaActual == $id ? 'selected' : '' }}>
                                            {{ $nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Regimen Fiscal --}}
                            @php
                                // id guardado (o el reenviado tras un fallo de validación)
                                $regimenActual = old('id_regimen_fiscal', optional($razon)->id_regimen_fiscal);
                            @endphp
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Régimen fiscal</label>

                                <select name="id_regimen_fiscal"
                                        class="form-select form-select-sm"
                                        disabled>           {{-- se habilita con el botón “Editar cuenta” --}}

                                    <option value="">— Selecciona —</option>

                                    @foreach ($regimen_fiscales as $id => $nombre)
                                        <option value="{{ $id }}" {{ $regimenActual == $id ? 'selected' : '' }}>
                                            {{ $nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                        </div>
                    </div>
                </div> {{-- Fin tarjeta: Datos de facturación --}}


                {{-- ╭━━━━ Tarjeta: Datos de entrega ━━━━╮ --}}
                <div class="card shadow-lg">
                    <div class="card-header fw-bold" style="background-color: rgba(81, 86, 190, 0.1);">Datos de entrega</div>

                    @php
                        $cteEnt  = $cliente->contacto_entrega_predet;
                        $dirEnt  = $cteEnt->direccion_entrega ?? null;
                    @endphp

                    <div class="card-body px-3 py-2">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Contacto</label>
                                <input class="form-control form-control-sm guarda-mayus" type="text"
                                    value="{{ trim(implode(' ', array_filter([optional($cteEnt)->nombre, optional($cteEnt)->apellido_p, optional($cteEnt)->apellido_m]))) }}"
                                    disabled>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Teléfono</label>
                                <input class="form-control form-control-sm phone-field" type="text"
                                    value="{{ $cteEnt->telefono1 ?? '' }}"  disabled>
                            </div>

                            {{-- Dirección --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Calle</label>
                                <input class="form-control form-control-sm guarda-titulo" type="text"
                                    value="{{ $dirEnt->calle ?? '' }}"  disabled>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Núm Ext</label>
                                <input class="form-control form-control-sm" type="text"
                                    value="{{ $dirEnt->num_ext ?? '' }}"  disabled>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Núm Int</label>
                                <input class="form-control form-control-sm" type="text"
                                    value="{{ $dirEnt->num_int ?? '' }}"  disabled>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Colonia</label>
                                <input class="form-control form-control-sm" type="text"
                                    value="{{ $dirEnt->colonia ?? '' }}"  disabled>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-bold">C.P.</label>
                                <input class="form-control form-control-sm" type="text"
                                    value="{{ $dirEnt->cp ?? '' }}"  disabled>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Municipio</label>
                                <input class="form-control form-control-sm" type="text"
                                    value="{{ $dirEnt->ciudad->nombre ?? '' }}"  disabled>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Estado</label>
                                <input class="form-control form-control-sm" type="text"
                                    value="{{ $dirEnt->estado->nombre ?? '' }}"  disabled>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">País</label>
                                <input class="form-control form-control-sm" type="text"
                                    value="{{ $dirEnt->pais->nombre ?? '' }}"  disabled>
                            </div>

                        </div>
                    </div>
                </div> {{-- Fin tarjeta: Datos de entrega --}}

            </div>{{-- Fin columna derecha --}}
                                
        </div>{{-- Fin row superior --}}
    </form>                               

    {{-- Inicio row inferior --}}
    <div class="row mt-4">
        {{-- 📝 Historial de notas --}}
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header fw-bold" style="background-color: rgba(81, 86, 190, 0.1);">
                    Historial de notas
                </div>
                <div class="card-body">

                    {{-- Área scrolleable con historial --}}
                    <div class="form-group mb-4">
                        <textarea class="form-control" rows="10"  disabled style="resize: none; overflow-y: scroll;">
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

        {{-- Historial de pedidos ---------------------------------------------------}}
        <div class="col-md-6">

            <div class="card shadow-lg">
                <div class="card-header fw-bold" style="background-color: rgba(81, 86, 190, 0.1);">Historial de pedidos</div>

                <div class="card-body p-0"> {{-- p-0 = quitamos padding extra --}}
                    {{-- contenedor scroll con altura máx (ajusta a tu gusto) --}}
                    <div class="table-responsive" style="max-height: 470px; overflow-y: auto;">
                        <table id="tblPedidos" class="table table-sm table-striped mb-0">
                            <thead class="table-light position-sticky top-0" style="z-index:1">
                                <tr>
                                    <th data-type="date">Fecha <span class="sort-arrow"></span></th>
                                    <th data-type="text">ID&nbsp;pedido <span class="sort-arrow"></span></th>
                                    <th data-type="text">Razón social <span class="sort-arrow"></span></th>
                                    <th data-type="number" class="text-end">Subtotal <span class="sort-arrow"></span></th>
                                    <th data-type="number" class="text-end">Margen&nbsp;% <span class="sort-arrow"></span></th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($pedidos as $p)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($p['fecha'])->format('d-m-Y') }}</td>
                                        <td>{{ $p['id'] }}</td>
                                        <td>{{ $p['razon'] }}</td>
                                        <td class="text-end">$ {{ number_format($p['subtotal'], 2) }}</td>
                                        <td class="text-end">{{ number_format($p['margen'], 2) }}%</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Sin pedidos registrados…</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>{{-- Fin row inferior --}}

    {{-- Modal confirmación de archivado --}}
    <div class="modal fade" id="confirmArchivar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar archivado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                La cuenta se marcará como <strong>inactiva</strong> (archivada).<br>
                ¿Deseas continuar?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="btnConfirmArchivar" class="btn btn-danger">Sí, archivar</button>
            </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const form        = document.getElementById('formCuenta');
            const selEstatus  = document.getElementById('selectEstatus');
            const modalEl     = document.getElementById('confirmArchivar');
            const modal       = new bootstrap.Modal(modalEl);
            const btnConfirm  = document.getElementById('btnConfirmArchivar');

            if (!form || !selEstatus) return;

            let archivarPendiente = false;   // flag interno

            form.addEventListener('submit', e => {

                // Si el estatus elegido es inactivo y aún no confirmamos → mostrar modal
                if (selEstatus.value === 'inactivo' && !archivarPendiente) {
                    e.preventDefault();      // detiene el submit original
                    modal.show();
                }
            });

            // Cuando confirman en el modal lanzamos el submit real
            btnConfirm.addEventListener('click', () => {
                archivarPendiente = true;    // evita bucle
                modal.hide();
                form.submit();
            });

        });
    </script>



    {{-- Navegador entre Clientes --}}
    <div class="d-flex justify-content-between align-items-center mb-2">

        {{-- Flecha anterior --}}
        @if ($prevId)
            <a href="{{ route('clientes.view', $prevId) }}"
            class="btn btn-outline-secondary btn-sm">
            <i class="fa fa-chevron-left"></i>
            </a>
        @else
            <button class="btn btn-outline-secondary btn-sm" disabled>
                <i class="fa fa-chevron-left"></i>
            </button>
        @endif

        <span class="fw-bold">{{ $cliente->nombre }}</span>

        {{-- Flecha siguiente --}}
        @if ($nextId)
            <a href="{{ route('clientes.view', $nextId) }}"
            class="btn btn-outline-secondary btn-sm">
            <i class="fa fa-chevron-right"></i>
            </a>
        @else
            <button class="btn btn-outline-secondary btn-sm" disabled>
                <i class="fa fa-chevron-right"></i>
            </button>
        @endif
    </div>

        {{-- 🎛 Botonera --}}
    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="{{ url()->previous() }}" class="btn btn-light">
            <i class="fa fa-arrow-left me-1"></i> Regresar
        </a>

        <button type="submit" class="btn btn-success" form="formCuenta">
            <i class="fa fa-save me-1"></i> Guardar
        </button>

        <a href="{{ route('clientes.index') }}" class="btn btn-info">
            <i class="fa fa-list me-1"></i> Mis Cuentas
        </a>

        <a href="{{ route('inicio', ['cliente' => $cliente->id]) }}" class="btn btn-primary">
            <i class="fa fa-file-invoice-dollar me-1"></i> Levantar Cotización
        </a>

        <a href="{{ route('inicio', ['cliente' => $cliente->id]) }}" class="btn btn-secondary">
            <i class="fa fa-address-book me-1"></i> Libreta de Contactos
        </a>
    </div>

    <script>
        // Script para ordenar tabla de pedidos
        document.addEventListener('DOMContentLoaded', () => {

            const table  = document.getElementById('tblPedidos');
            const tbody  = table.querySelector('tbody');
            const ths    = table.querySelectorAll('thead th');
            const dirMap = {};

            ths.forEach((th, idx) => {

                th.addEventListener('click', () => {

                    // Alterna dirección
                    dirMap[idx] = dirMap[idx] === 'asc' ? 'desc' : 'asc';

                    // Convertir NodeList filas a array
                    const rows  = Array.from(tbody.querySelectorAll('tr'));
                    const type  = th.dataset.type || 'text';
                    const parse = (txt) => {
                        if (type === 'number') return parseFloat(txt.replace(/[^\d.-]/g, '')) || 0;
                        if (type === 'date')   return new Date(txt.split('-').reverse().join('-')).getTime();
                        return txt.toLowerCase();
                    };

                    rows.sort((a, b) => {
                        const A = parse(a.children[idx].innerText);
                        const B = parse(b.children[idx].innerText);
                        return (A < B ? -1 : A > B ? 1 : 0) * (dirMap[idx] === 'asc' ? 1 : -1);
                    });

                    // Repinta filas ordenadas
                    rows.forEach(r => tbody.appendChild(r));

                    /* ——— Actualiza flechas ——— */
                    ths.forEach(h => h.classList.remove('asc', 'desc'));
                    th.classList.add(dirMap[idx]);
                });
            });
        });
    </script>

    <script>
        //Script para habilitar/deshabilitar edición de formulario clientes.view
        // (solo para el botón de editar cuenta)
    document.addEventListener('DOMContentLoaded', () => {

        const btn   = document.getElementById('btnEditar');
        const form  = document.getElementById('formCuenta');
        if (!btn || !form) return;

        let editing = false;   //  ← estado actual

        const habilitar = () => {
            form.querySelectorAll('input, select, textarea').forEach(el => {
            if (el.classList.contains('no-editar')) return;
            el.disabled = false;
            el.removeAttribute('disabled');
            });
            btn.classList.replace('btn-primary', 'btn-secondary');
            btn.innerHTML = '<i class="fa fa-unlock me-1"></i> Edición habilitada';
            editing = true;
        };

        const bloquear = () => {
            form.querySelectorAll('input:not([readonly]), select, textarea').forEach(el => {
                el.disabled = true;
            });
            btn.classList.replace('btn-secondary', 'btn-primary');
            btn.innerHTML = '<i class="fa fa-edit me-1"></i> Editar cuenta';
            editing = false;
        };

        btn.addEventListener('click', () => {
            editing ? bloquear() : habilitar();
        });

    });
    </script>



@endsection