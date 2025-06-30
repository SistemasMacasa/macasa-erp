@extends('layouts.app')
@section('title', 'SIS 3.0 | Nueva Cotización')

@section('content')
    <div class="container-fluid">

        {{-- 🧭 Migas de pan --}}
        @section('breadcrumb')
            <li class="breadcrumb-item"><a href="{{ route('inicio') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cotizaciones.index') }}">Cotizaciones</a></li>
            <li class="breadcrumb-item active">Nueva Cotización</li>
        @endsection

        <h2 class="mb-3" style="color: inherit;">Levantar cotización</h2>

        {{-- 🎛 Botonera --}}
        <div class="d-flex flex-wrap gap-2 mb-3">
            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-principal">
                <i class="fa fa-arrow-left me-1"></i> Regresar
            </a>
            <button class="btn btn-success btn-principal">
                <i class="fa fa-save me-1"></i> Guardar
            </button>
            <a href="{{ route('clientes.index') }}" class="btn btn-primary btn-principal">
                <i class="fa fa-building me-1"></i> Mis cuentas
            </a>
            <a href="#" class="btn btn-primary btn-principal">
                <i class="fa fa-user me-1"></i> Ver cuenta
            </a>
        </div>


        {{-- 🧾 Sección: Dirección de Facturación y Entrega --}}
        <div class="row gy-4">
            {{-- Dirección de facturación --}}
            <div class="col-md-6">
                {{-- 🔲 DIRECCIÓN DE FACTURACIÓN --}}
                <div class="card shadow mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <strong>Dirección de Facturación</strong>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#modalFacturacion">
                                <i class="fa fa-address-book"></i> Directorio
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#modalCrearDireccionFactura">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>


                    <div class="card-body">
                        <div class="row gy-2">
                            {{-- Fecha, Monto y Utilidad --}}
                            <div class="col-xxl-4">
                                <label class="form-label">Fecha de facturación</label>
                                <input type="date" name="fecha_facturacion" class="form-control"
                                    value="{{ now()->toDateString() }}">
                            </div>

                            <div class="col-xxl-4">
                                <label class="form-label">Monto de la factura</label>
                                <input type="text" name="monto" class="form-control">
                            </div>

                            <div class="col-xxl-4">
                                <label class="form-label">Utilidad</label>
                                <input type="text" name="utilidad" class="form-control">
                            </div>

                            <hr class="mt-3">

                            {{-- Razón social y RFC --}}
                            <div class="col-xxl-8">
                                <label class="form-label">Razón Social *</label>
                                <input value="{{ $cliente->razon_social_predet?->nombre }}" type="text" name="razon_social"
                                    class="form-control" required>
                            </div>

                            <div class="col-xxl-4">
                                <label class="form-label">RFC *</label>
                                <input value="{{ $cliente->razon_social_predet?->RFC }}" type="text" name="rfc"
                                    class="form-control" required>
                            </div>

                            <hr class="mt-3">

                            {{-- Dirección --}}
                            <div class="col-xxl-4">
                                <label class="form-label">Calle</label>
                                <input value="{{ $direccion_facturacion?->calle }}" type="text" name="calle"
                                    class="form-control">
                            </div>

                            <div class="col-xxl-2">
                                <label class="form-label">Num. Ext.</label>
                                <input value="{{ $direccion_facturacion?->num_ext }}" type="text" name="num_ext"
                                    class="form-control">
                            </div>

                            <div class="col-xxl-2">
                                <label class="form-label">Num. Int.</label>
                                <input value="{{ $direccion_facturacion?->num_int }}" type="text" name="num_int"
                                    class="form-control">
                            </div>

                            <div class="col-xxl-4">
                                <label class="form-label">Colonia</label>
                                <input value="{{ $direccion_facturacion?->colonia->d_asenta }}" type="text" name="colonia"
                                    class="form-control">
                            </div>

                            <div class="col-xxl-4">
                                <label class="form-label">Código Postal</label>
                                <input value="{{ $direccion_facturacion?->cp }}" type="text" name="cp" class="form-control">
                            </div>

                            <div class="col-xxl-4">
                                <label class="form-label">Municipio</label>
                                <input value="{{ $direccion_facturacion?->ciudad?->n_mnpio }}" type="text" name="municipio"
                                    class="form-control">
                            </div>

                            <div class="col-xxl-4">
                                <label class="form-label">Estado</label>
                                <input value="{{ $direccion_facturacion?->estado?->d_estado }}" type="text" name="estado"
                                    class="form-control">
                            </div>

                            <div class="col-xxl-4">
                                <label class="form-label">País</label>
                                <input value="{{ $direccion_facturacion?->pais?->nombre ?? 'MÉXICO' }}" type="text"
                                    name="pais" class="form-control">
                            </div>

                            <hr class="mt-3">

                            {{-- CFDI y pagos --}}
                            <div class="col-xxl-4">
                                <label class="form-label">Uso CFDI</label>
                                <select name="uso_cfdi" class="form-select">
                                    <option value="">Selecciona uno</option>
                                    <option value="G01" {{ $direccion_facturacion?->uso_cfdi == 'G01' ? 'selected' : '' }}>
                                        G01, Adquisición de mercancías</option>
                                    <option value="G03" {{ $direccion_facturacion?->uso_cfdi == 'G03' ? 'selected' : '' }}>
                                        G03, Gastos en general</option>
                                </select>
                            </div>

                            <div class="col-xxl-4">
                                <label class="form-label">Método de pago CFDI</label>
                                <select name="metodo_pago" class="form-select">
                                    <option value="">Selecciona uno</option>
                                    <option value="PUE" {{ $direccion_facturacion?->metodo_pago == 'PUE' ? 'selected' : '' }}>
                                        PUE</option>
                                    <option value="PPD" {{ $direccion_facturacion?->metodo_pago == 'PPD' ? 'selected' : '' }}>
                                        PPD</option>
                                </select>
                            </div>

                            <div class="col-xxl-4">
                                <label class="form-label">Forma de pago</label>
                                <select name="forma_pago" class="form-select">
                                    <option value="">Selecciona uno</option>
                                    <option value="01" {{ $direccion_facturacion?->forma_pago == '01' ? 'selected' : '' }}>01,
                                        Efectivo</option>
                                    <option value="99" {{ $direccion_facturacion?->forma_pago == '99' ? 'selected' : '' }}>99,
                                        Por definir</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Régimen Fiscal</label>
                                <select name="regimen_fiscal" class="form-select">
                                    <option value="">Selecciona uno</option>
                                    <option value="601" {{ $direccion_facturacion?->regimen_fiscal == '601' ? 'selected' : '' }}>601, General de Ley Personas Morales</option>
                                    <option value="605" {{ $direccion_facturacion?->regimen_fiscal == '605' ? 'selected' : '' }}>605, Sueldos y Salarios</option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Notas</label>
                                <textarea name="notas" class="form-control"
                                    rows="3">{{ $direccion_facturacion?->notas }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            {{-- Dirección de entrega --}}
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header fw-bold">Dirección de Entrega</div>
                    <div class="card-body">
                        {{-- Aquí van los campos de entrega: empresa, dirección, referencias, teléfono, notas, etc. --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- 📦 Sección: Partidas --}}
        <div class="card shadow mt-4">
            <div class="card-header fw-bold">Agregar partidas</div>
            <div class="card-body">
            </div>
        </div>
    </div> <!-- End container-fluid -->


    <!-- Modal: Directorio de direcciones de facturación -->
    <div class="modal fade" id="modalFacturacion" tabindex="-1" aria-labelledby="modalFacturacionLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content text-center">

                <div class="modal-header flex-column border-0 bg-white">
                    <h4 class="modal-title w-100 fw-bold text-primary-emphasis">
                        <i class="fa fa-address-book me-2 text-primary"></i> Seleccionar Dirección de Facturación
                    </h4>
                    <hr class="w-100 my-2 opacity-25">
                    <button type="button" class="btn-close position-absolute end-0 top-0 mt-3 me-3" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>

                <div class="modal-body pt-0">
                    <p class="text-muted mb-4">Selecciona una dirección registrada para el cliente actual</p>

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle small text-start">
                            <thead class="table-light">
                                <tr>
                                    <th></th>
                                    <th>Razón Social</th>
                                    <th>RFC</th>
                                    <th>Calle</th>
                                    <th>Colonia</th>
                                    <th>CP</th>
                                    <th>Municipio</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($razones_sociales as $rs)
                                    <tr>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-success seleccionar-direccion"
                                                data-direccion='@json($rs)' data-bs-dismiss="modal">
                                                <i class="fa fa-check"></i>
                                            </button>
                                        </td>
                                        <td>{{ $rs->nombre }}</td>
                                        <td>{{ $rs->RFC }}</td>
                                        <td>{{ $rs->direccion_facturacion->calle }} #{{ $rs->direccion_facturacion->num_ext }}</td>
                                        <td>{{ $rs->direccion_facturacion->colonia?->d_asenta }}</td>
                                        <td>{{ $rs->direccion_facturacion?->cp }}</td>
                                        <td>{{ $rs->direccion_facturacion->ciudad?->n_mnpio }}</td>
                                        <td>{{ $rs->direccion_facturacion->estado?->d_estado }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <span>No se encontraron direcciones de facturación</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal: Crear nueva razón social + dirección de facturación -->
    <div class="modal fade" id="modalCrearDireccionFactura" tabindex="-1" aria-labelledby="modalCrearDireccionFacturaLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header flex-column border-0 bg-white text-center">
                    <h4 class="modal-title w-100 fw-bold text-primary-emphasis">
                        <i class="fa fa-plus me-2 text-primary"></i> Nueva Razón Social y Dirección
                    </h4>
                    <hr class="w-100 my-2 opacity-25">
                    <button type="button" class="btn-close position-absolute end-0 top-0 mt-3 me-3" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>

                <div class="modal-body pt-0">
                    <form id="formNuevaRazonSocialFactura">
                        @csrf
                        <input type="hidden" name="id_cliente" value="{{ $cliente->id_cliente }}">

                        <div class="row g-3">
                            {{-- Razón Social --}}
                            <div class="col-md-6">
                                <label class="form-label">Razón Social *</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>

                            {{-- RFC --}}
                            <div class="col-md-6">
                                <label class="form-label">RFC *</label>
                                <input type="text" name="rfc" class="form-control" required>
                            </div>

                            {{-- Uso CFDI --}}
                            <div class="col-md-4">
                                <label class="form-label">Uso CFDI *</label>
                                <select name="id_uso_cfdi" class="form-select" required>
                                    <option value="" disabled selected>Selecciona uno</option>
                                    @foreach($uso_cfdis as $uso)
                                        <option value="{{ $uso->id_uso_cfdi }}">{{ $uso->clave }} – {{ $uso->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Método de pago CFDI --}}
                            <div class="col-md-4">
                                <label class="form-label">Método de pago CFDI *</label>
                                <select name="id_metodo_pago" class="form-select" required>
                                    <option value="" disabled selected>Selecciona uno</option>
                                    @foreach($metodos->unique('clave') as $metodo)
                                        <option value="{{ $metodo->id_metodo_pago }}">{{ $metodo->clave }} –
                                            {{ $metodo->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Forma de pago --}}
                            <div class="col-md-4">
                                <label class="form-label">Forma de pago *</label>
                                <select name="id_forma_pago" class="form-select" required>
                                    <option value="" disabled selected>Selecciona uno</option>
                                    @foreach($formas->unique('clave') as $forma)
                                        <option value="{{ $forma->id_forma_pago }}">{{ $forma->clave }} – {{ $forma->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Régimen Fiscal --}}
                            <div class="col-md-6">
                                <label class="form-label">Régimen Fiscal *</label>
                                <select name="id_regimen_fiscal" class="form-select" required>
                                    <option value="" disabled selected>Selecciona uno</option>
                                    @foreach($regimenes as $regimen)
                                        <option value="{{ $regimen->id_regimen_fiscal }}">{{ $regimen->clave }} –
                                            {{ $regimen->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Notas --}}
                            <div class="col-md-12">
                                <label class="form-label">Notas</label>
                                <textarea name="notas" class="form-control" rows="2"></textarea>
                            </div>

                            {{-- — Separador — --}}
                            <div class="col-12">
                                <hr>
                            </div>

                            {{-- Calle --}}
                            <div class="col-md-4">
                                <label class="form-label">Calle</label>
                                <input type="text" name="calle" class="form-control">
                            </div>

                            {{-- Num. Ext. --}}
                            <div class="col-md-2">
                                <label class="form-label">Num. Ext.</label>
                                <input type="text" name="num_ext" class="form-control">
                            </div>

                            {{-- Num. Int. --}}
                            <div class="col-md-2">
                                <label class="form-label">Num. Int.</label>
                                <input type="text" name="num_int" class="form-control">
                            </div>

                            {{-- Colonia --}}
                            <div class="col-md-4">
                                <label class="form-label">Colonia</label>
                                <select name="colonia" class="form-select colonia-select">
                                    <option value="">— Selecciona CP primero —</option>
                                </select>
                            </div>

                            {{-- Código Postal --}}
                            <div class="col-md-3">
                                <label class="form-label">CP</label>
                                <input type="text" name="cp" maxlength="5" class="form-control cp-field">
                            </div>

                            {{-- Municipio --}}
                            <div class="col-md-3">
                                <label class="form-label">Municipio</label>
                                <select name="municipio" class="form-select municipio-field">
                                    <option value="">— Selecciona CP primero —</option>
                                </select>

                            </div>

                            {{-- Estado --}}
                            <div class="col-md-3">
                                <label class="form-label">Estado</label>
                                <select name="estado" class="form-select estado-field">
                                    <option value="">— Selecciona CP primero —</option>
                                </select>
                            </div>

                            <!-- País -->
                            <div class="col-md-3">
                                <label class="form-label">País</label>
                                <select name="id_pais" class="form-select pais-field">
                                    @foreach($paises as $pais)
                                        <option value="{{ $pais->id_pais }}" {{ $pais->nombre === 'México' ? 'selected' : '' }}>
                                            {{ $pais->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="mt-4 text-end">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar razón social</button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>

    @push('scripts')

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const form = document.getElementById('formNuevaRazonSocialFactura');

                form.addEventListener('submit', async e => {
                    e.preventDefault();
                    const datos = new FormData(form);

                    const resp = await fetch('{{ route('ajax.direccion.factura') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'           // ← pide JSON de Laravel
                        },
                        body: datos
                    });

                    if (!resp.ok) {
                        const contentType = resp.headers.get('Content-Type') || '';
                        if (contentType.includes('application/json')) {
                            const err = await resp.json();
                            console.error('Error JSON:', err);
                            alert('❌ ' + (err.error || JSON.stringify(err.errors) || 'Error al guardar'));
                        } else {
                            const text = await resp.text();
                            console.error('Error HTML:', text);
                            alert('❌ Error inesperado. Revisa consola.');
                        }
                        return;
                    }

                    const { razon_social: razon, direccion: dir } = await resp.json();

                    // Cierra modal
                    bootstrap.Modal.getInstance(
                        document.getElementById('modalCrearDireccionFactura')
                    ).hide();

                    // Rellena formulario principal
                    document.querySelector('[name="nombre"]').value = razon.nombre;
                    document.querySelector('[name="rfc"]').value = razon.rfc;
                    document.querySelector('[name="id_uso_cfdi"]').value = razon.id_uso_cfdi;
                    document.querySelector('[name="id_metodo_pago"]').value = razon.id_metodo_pago;
                    document.querySelector('[name="id_forma_pago"]').value = razon.id_forma_pago;
                    document.querySelector('[name="id_regimen_fiscal"]').value = razon.id_regimen_fiscal;

                    document.querySelector('[name="calle"]').value = dir.calle || '';
                    document.querySelector('[name="num_ext"]').value = dir.num_ext || '';
                    document.querySelector('[name="num_int"]').value = dir.num_int || '';
                    document.querySelector('[name="colonia"]').value = dir.colonia || '';
                    document.querySelector('[name="cp"]').value = dir.cp || '';
                    document.querySelector('[name="municipio"]').value = dir.municipio || '';
                    document.querySelector('[name="estado"]').value = dir.estado || '';
                    document.querySelector('[name="pais"]').value = dir.pais || 'MÉXICO';
                    document.querySelector('[name="notas"]').value = dir.notas || '';
                });
            });
        </script>

        <script defer>
            //Autocompletado de direcciones de facturación
            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.getElementById('modalCrearDireccionFactura');

                if (!modal) return;

                const delay = (fn, ms = 400) => {
                    let t; return (...args) => {
                        clearTimeout(t);
                        t = setTimeout(() => fn(...args), ms);
                    };
                };

                function seleccionarPorTexto(select, texto) {
                    if (!select) return;

                    let opt = [...select.options].find(o =>
                        o.text.trim().toLowerCase() === texto.trim().toLowerCase()
                    );

                    if (!opt) opt = [...select.options].find(o => o.value === texto);

                    if (!opt) {
                        opt = new Option(texto, texto, true, true);
                        select.prepend(opt);
                    }

                    select.value = opt.value;
                }

                function limpiar(bloque) {
                    if (!bloque) return;

                    const estado = bloque.querySelector('.estado-field');
                    const municipio = bloque.querySelector('.municipio-field');
                    const colonia = bloque.querySelector('.colonia-select');

                    if (estado) estado.value = '';
                    if (municipio) municipio.value = '';
                    if (colonia) {
                        colonia.innerHTML = '<option value="">— Selecciona CP primero —</option>';
                        colonia.disabled = true;
                    }
                }

                modal.addEventListener('shown.bs.modal', () => {
                    const cpInput = modal.querySelector('.cp-field');
                    if (!cpInput) return;

                    cpInput.addEventListener('input', delay(async () => {
                        const cp = cpInput.value.trim();
                        const bloque = cpInput.closest('.facturacion-block') ?? modal;

                        if (!/^\d{5}$/.test(cp)) {
                            limpiar(bloque);
                            return;
                        }

                        try {
                            const r = await fetch(`/api/cp/${cp}`);
                            if (!r.ok) throw new Error(`No hay datos para el CP ${cp}`);

                            const data = await r.json();

                            seleccionarPorTexto(bloque.querySelector('.estado-field'), data.head.estado);
                            seleccionarPorTexto(bloque.querySelector('.municipio-field'), data.head.municipio);

                            const coloniaSel = bloque.querySelector('.colonia-select');
                            if (coloniaSel) {
                                coloniaSel.innerHTML = '';
                                data.colonias.forEach(c => {
                                    const opt = new Option(`${c.colonia} (${c.tipo})`, c.colonia);
                                    coloniaSel.appendChild(opt);
                                });
                                coloniaSel.disabled = false;
                            }

                        } catch (err) {
                            console.error('❌ Error cargando CP:', err);
                            limpiar(bloque);
                        }
                    }));
                });
            });
        </script>

        <script defer>
            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.getElementById('modalCrearDireccionFactura');
                if (!modal) return;

                // Campos dentro del modal
                const campoPais = modal.querySelector('.pais-field');
                const campoCP = modal.querySelector('.cp-field');
                const coloniaSel = modal.querySelector('.colonia-select');
                const municipioSel = modal.querySelector('.municipio-field');
                const estadoSel = modal.querySelector('.estado-field');

                // ① Aplica “readonly” vía CSS y atributos a municipio/estado
                [municipioSel, estadoSel].forEach(sel => {
                    if (!sel) return;
                    sel.setAttribute('readonly', 'readonly');      // marca semántica
                    sel.setAttribute('tabindex', '-1');            // no reciba focus
                    sel.style.pointerEvents = 'none';               // no clickable
                    sel.style.backgroundColor = '#e9ecef';          // tono disabled
                });

                // ─── Utilidades ────────────────────────────────
                const delay = (fn, ms = 400) => {
                    let t;
                    return (...args) => {
                        clearTimeout(t);
                        t = setTimeout(() => fn(...args), ms);
                    };
                };

                function seleccionarPorTexto(select, texto) {
                    if (!select) return;
                    let opt = [...select.options].find(o =>
                        o.text.trim().toLowerCase() === texto.trim().toLowerCase()
                    );
                    if (!opt) opt = [...select.options].find(o => o.value === texto);
                    if (!opt) {
                        opt = new Option(texto, texto, true, true);
                        select.prepend(opt);
                    }
                    select.value = opt.value;
                }

                function limpiarCampos() {
                    // limpia colonia, mantiene municipio/estado en blanco
                    if (coloniaSel) {
                        coloniaSel.innerHTML = '<option value="">— Selecciona CP primero —</option>';
                        coloniaSel.disabled = true;
                    }
                    municipioSel.value = '';
                    estadoSel.value = '';
                }

                // ─── Lógica país → habilita/deshabilita autocompletado ───────────────
                function togglePorPais() {
                    const nombre = campoPais.options[campoPais.selectedIndex].text.trim().toLowerCase();
                    const esMx = nombre === 'méxico';

                    // CP y Colonia sólo si es México
                    campoCP.disabled = !esMx;
                    coloniaSel.disabled = !esMx;
                    if (!esMx) {
                        coloniaSel.innerHTML = '<option value="">No aplicable fuera de México</option>';
                        limpiarCampos();
                    } else {
                        coloniaSel.innerHTML = '<option value="">— Selecciona CP primero —</option>';
                    }
                }

                campoPais.addEventListener('change', togglePorPais);

                // ─── Al abrir el modal ─────────────────────────────
                modal.addEventListener('shown.bs.modal', () => {
                    togglePorPais();  // estado inicial

                    // Listener CP → fetch
                    campoCP.removeEventListener('input', onCPInput);
                    campoCP.addEventListener('input', delay(onCPInput, 400));
                });

                // ─── Manejador de CP → autocompletado ─────────────
                async function onCPInput() {
                    const cp = campoCP.value.trim();
                    if (!/^\d{5}$/.test(cp)) {
                        limpiarCampos();
                        return;
                    }
                    try {
                        const res = await fetch(`/api/cp/${cp}`);
                        if (!res.ok) throw new Error('Sin datos');
                        const data = await res.json();

                        // llena municipio y estado (readonly)
                        seleccionarPorTexto(estadoSel, data.head.estado);
                        seleccionarPorTexto(municipioSel, data.head.municipio);

                        // llena colonias
                        coloniaSel.innerHTML = '';
                        data.colonias.forEach(c => {
                            const o = new Option(`${c.colonia} (${c.tipo})`, c.colonia);
                            coloniaSel.appendChild(o);
                        });
                        coloniaSel.disabled = false;
                    } catch {
                        limpiarCampos();
                    }
                }
            });
        </script>



    @endpush

@endsection