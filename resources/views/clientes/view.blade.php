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
    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="{{ url()->previous() }}" class="btn btn-light">
            <i class="fa fa-arrow-left me-1"></i> Regresar
        </a>

        <button type="submit" class="btn btn-success">
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
    <form action="{{ route('clientes.update', $cliente->id_cliente) }}" id="formCuenta"></form>
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
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Estatus</label>
                                <input type="text" value="{{ $cliente->estatus }}" class="form-control form-control-sm"  disabled>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Ciclo de venta</label>
                                <input type="text" value="{{ $cliente->ciclo_venta }}" class="form-control form-control-sm"  disabled>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Sector</label>
                                <input type="text" value="{{ $cliente->sector }}" class="form-control form-control-sm"  disabled>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Segmento</label>
                                <input type="text" value="{{ $cliente->segmento }}" class="form-control form-control-sm"  disabled>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold">Nombre de la cuenta</label>
                                <input type="text"
                                    value="{{ $cliente->apellido_p && $cliente->apellido_m
                                            ? $cliente->nombre.' '.$cliente->apellido_p.' '.$cliente->apellido_m
                                            : $cliente->nombre }}"
                                    class="form-control form-control-sm"  disabled>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Asignado a</label>
                                <input type="text" value="{{ optional($cliente->vendedor)->name }}"
                                    class="form-control form-control-sm"  disabled>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Origen de la cuenta</label>
                                <input type="text" value="{{ $cliente->tipo }}"
                                    class="form-control form-control-sm"  disabled>
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
                                    class="form-control form-control-sm"  disabled>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Primer Apellido</label>
                                <input type="text" value="{{ $cliente->contacto_predet->apellido_p ?? ''}}"
                                    class="form-control form-control-sm"  disabled>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Segundo Apellido</label>
                                <input type="text" value="{{ $cliente->contacto_predet->apellido_m ?? ''}}"
                                    class="form-control form-control-sm"  disabled>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Género</label>
                                <input type="text" value="{{ $cliente->contacto_predet->genero ?? ''}}"
                                    class="form-control form-control-sm"  disabled>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Puesto</label>
                                <input type="text" value="{{ $cliente->contacto_predet->puesto ?? ''}}"
                                    class="form-control form-control-sm"  disabled>
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
                                <input type="text" class="form-control form-control-sm"  disabled>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Condición de pago</label>
                                <input type="text" class="form-control form-control-sm"  disabled>
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
                                        <input type="text" value="{{ $tel ?? ''}}" class="form-control form-control-sm"  disabled>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Extensión {{ $i }}</label>
                                        <input type="text" value="{{ $ext ?? '' }}" class="form-control form-control-sm"  disabled>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Celular {{ $i }}</label>
                                        <input type="text" value="{{ $cel ?? '' }}" class="form-control form-control-sm"  disabled>
                                    </div>
                                @endif
                            @endfor

                            <div class="col-md-12">
                                <label class="form-label fw-bold">Correo electrónico</label>
                                <input type="email" value="{{ $cliente->contacto_predet->email ?? '' }}"
                                    class="form-control form-control-sm"  disabled>
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
                                <input class="form-control form-control-sm" type="text"
                                    value="{{ $razon->nombre ?? '' }}"  disabled>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">RFC</label>
                                <input class="form-control form-control-sm" type="text"
                                    value="{{ $razon->RFC ?? '' }}"  disabled>
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            {{-- ------------- Dirección ------------- --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Calle</label>
                                <input class="form-control form-control-sm" type="text"
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
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Uso CFDI</label>
                                <input class="form-control form-control-sm" type="text"
                                    value="{{ $razon->uso_cfdi->nombre ?? '' }}"  disabled>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Método de pago</label>
                                <input class="form-control form-control-sm" type="text"
                                    value="{{ $razon->metodo_pago->nombre ?? '' }}"  disabled>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Forma de pago</label>
                                <input class="form-control form-control-sm" type="text"
                                    value="{{ $razon->forma_pago->nombre ?? '' }}"  disabled>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Régimen fiscal</label>
                                <input class="form-control form-control-sm" type="text"
                                    value="{{ $razon->regimen_fiscal->nombre ?? '' }}"  disabled>
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
                                <input class="form-control form-control-sm" type="text"
                                    value="{{ trim(implode(' ', array_filter([optional($cteEnt)->nombre, optional($cteEnt)->apellido_p, optional($cteEnt)->apellido_m]))) }}"
                                    disabled>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Teléfono</label>
                                <input class="form-control form-control-sm" type="text"
                                    value="{{ $cteEnt->telefono1 ?? '' }}"  disabled>
                            </div>

                            {{-- Dirección --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Calle</label>
                                <input class="form-control form-control-sm" type="text"
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
        // Script para habilitar/deshabilitar campos de edición
        document.addEventListener('DOMContentLoaded', () => {

            const btn   = document.getElementById('btnEditar');
            const form  = document.getElementById('formCuenta');

            if (!btn || !form) return;

            btn.addEventListener('click', () => {
                // 1) Habilitar todos los campos form-control / selects / textareas
                form.querySelectorAll('input[disabled], select[disabled], textarea[disabled]')
                    .forEach(el => {
                    el.disabled = false;            // propiedad
                    el.removeAttribute('disabled'); // atributo
                });


                // 2) Opcional: enfocar el primer campo
                const first = form.querySelector('input, select, textarea');
                if (first) first.focus();

                // 3) Opcional: ocultar o desactivar el propio botón para no repetir
                btn.setAttribute('disabled', true);
                btn.classList.add('btn-secondary');
                btn.innerHTML = '<i class="bi bi-unlock"></i> Edición habilitada';
            });

        });
    </script>

@endsection