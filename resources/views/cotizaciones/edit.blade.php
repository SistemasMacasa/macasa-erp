@extends('layouts.app')
@section('title', 'SIS 3.0 | Levantar Cotización')

@section('content')

    <!-- SECCION PRINCIPAL -->
    <div class="container-fluid">

        {{-- 🧭 Migas de pan --}}
        @section('breadcrumb')
            <li class="breadcrumb-item"><a href="{{ route('inicio') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cotizaciones.index') }}">Cotizaciones</a></li>
            <li class="breadcrumb-item active">Editar Cotización - </li>
        @endsection

        <h2 class="mb-3" style="color: inherit;">Editar cotización -</h2>

        {{-- 🎛 Botonera --}}
        <div class="d-flex flex-wrap gap-2 mb-3">
            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-principal col-xxl-2 col-xl-2 col-lg-3">
                <i class="fa fa-arrow-left me-1"></i> Regresar
            </a>
            <button id="btnGuardarCotizacion" class="btn btn-success btn-principal col-xxl-2 col-xl-2 col-lg-3">
                <i class="fa fa-save me-1"></i> Guardar
            </button>
            <a href="{{ route('clientes.index') }}" class="btn btn-primary btn-principal col-xxl-2 col-xl-2 col-lg-3">
                <i class="fa fa-building me-1"></i> Mis cuentas
            </a>
            <a href="#" class="btn btn-primary btn-principal col-xxl-2 col-xl-2 col-lg-3">
                <i class="fa fa-user me-1"></i> Ver cuenta
            </a>
        </div>

        
@php
    $editable = auth()->user()->can('update', $cotizacion);
    $puedeEmitir = auth()->user()->can('emitir', $cotizacion);
@endphp

<form method="POST" action="{{ route('cotizaciones.update', $cotizacion) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- =====================================
         Sección: Datos del cliente (readonly si no editable)
    ====================================== --}}
    <div class="card mb-3">
        <div class="card-header fw-bold">Datos del Cliente</div>
        <div class="card-body">
            <div class="mb-2">
                <label>Cliente:</label>
                <input type="text" class="form-control" value="{{ $cotizacion->cliente->nombre }}" readonly>
            </div>
            {{-- Más campos según lo que tenías en create --}}
        </div>
    </div>

    {{-- =====================================
         Sección: Orden de Compra
    ====================================== --}}
    <div class="card mb-3">
        <div class="card-header fw-bold">Orden de Compra</div>
        <div class="card-body">
            @if (!$cotizacion->orden_de_venta && $editable)
                <input type="file" name="orden_de_venta" class="form-control mb-2"
                       accept=".pdf,.doc,.docx,.xls,.xlsx,.msg,.zip">
                <button class="btn btn-primary">Subir archivo</button>
            @else
                <a href="{{ route('cotizaciones.orden-compra', $cotizacion) }}" class="btn btn-outline-secondary">
                    Ver orden cargada
                </a>
            @endif
        </div>
    </div>

    {{-- =====================================
         Sección: Partidas (con edición condicional)
    ====================================== --}}
    <div class="card mb-3">
        <div class="card-header fw-bold">Partidas</div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cotizacion->partidas as $partida)
                    <tr>
                        <td>{{ $partida->descripcion }}</td>
                        <td>{{ $partida->cantidad }}</td>
                        <td>${{ number_format($partida->precio, 2) }}</td>
                        <td>
                            @can('update', $partida)
                                <button class="btn btn-sm btn-primary">Editar</button>
                            @endcan
                            @can('delete', $partida)
                                <button class="btn btn-sm btn-danger">Eliminar</button>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- =====================================
         Sección: Notas de entrega y facturación
    ====================================== --}}
    <div class="card mb-3">
        <div class="card-header fw-bold">Notas</div>
        <div class="card-body">
            <label>Notas de entrega:</label>
            <textarea class="form-control" name="notas_entrega" {{ !$editable ? 'readonly' : '' }}>
                {{ $cotizacion->notas_entrega }}
            </textarea>
        </div>
    </div>

    {{-- =====================================
         Botón Emitir Pedido
    ====================================== --}}
    @if ($puedeEmitir)
        <div class="text-end">
            <button type="submit" name="emitir_pedido" value="1" class="btn btn-success">
                Emitir Pedido
            </button>
        </div>
    @endif

</form>

    </div> <!-- End SECCION PRINCIPAL -->

    <!-- Modal 1: Directorio de Razones Sociales + Direccion de Facturación -->
    <div class="modal fade" id="modalFacturacion" tabindex="-1" aria-labelledby="modalFacturacionLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border rounded">
            
            <div class="modal-header flex-column border-0">
                <h4 class="modal-title w-100 fw-bold text-primary-emphasis" id="modalFacturacionLabel">
                <i class="fa fa-address-book me-2 text-primary"></i>
                Seleccionar Dirección de Facturación
                </h4>
                <hr class="w-100 my-2 opacity-25">
                <button type="button" class="btn-close position-absolute end-0 top-0 mt-3 me-3"
                        data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            
            <div class="modal-body">        
                <!-- contenedor con borde y padding -->
                <div class="table-responsive border rounded p-3">
                <table id="tabla-razones"
                        class="table table-hover table-bordered align-middle small text-start mb-0">
                        <thead class="table-light">
                    <tr>
                        <th class="text-center select-col"></th>
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
                        <tr id="rs-row-{{ $rs->id_razon_social }}"
                            class="{{ $rs->predeterminado ? 'table-success' : '' }}">
                        <td class="text-center select-col p-2">
                            <div class="d-flex flex-row gap-2 justify-content-center">
                                <button type="button"
                                        class="btn btn-warning btn-sm"
                                        onclick="window.location='{{ route('razones_sociales.edit', $rs->id_razon_social) }}'">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button type="button"
                                        class="btn btn-primary btn-sm seleccionar-direccion"
                                        data-id="{{ $rs->id_razon_social }}"
                                        data-route="{{ route('razones_sociales.seleccionar', $rs->id_razon_social) }}">
                                    <i class="fa fa-check"></i>
                                </button>
                            </div>
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
    <!-- End Modal: Directorio de Razones Sociales + Direccion de Facturación -->


<!-- Modal 3: Directorio de contactos + direcciones de entrega -->
<div class="modal fade" id="modalEntrega" tabindex="-1" aria-labelledby="modalEntregaLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border rounded">

      <div class="modal-header flex-column border-0">
        <h4 class="modal-title w-100 fw-bold text-primary-emphasis" id="modalEntregaLabel">
          <i class="fa fa-address-book me-2 text-primary"></i>
          Seleccionar Dirección de Entrega
        </h4>
        <hr class="w-100 my-2 opacity-25">
        <button type="button" class="btn-close position-absolute end-0 top-0 mt-3 me-3"
                data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <div class="table-responsive border rounded p-3">
          <table id="tabla-entregas"
                 class="table table-hover table-bordered align-middle small text-start mb-0">
            <thead class="table-light">
              <tr>
                <th class="text-center select-col"></th>
                <th>Alias</th>
                <th>Contacto</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Calle</th>
                <th>Colonia</th>
                <th>CP</th>
                <th>Municipio</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              @foreach($contactos_entrega as $c)
                @php $dir = $c->direccion_entrega @endphp
                <tr id="entrega-row-{{ $c->id_contacto }}" class="{{ $c->predeterminado ? 'table-success' : '' }}">
                <td class="text-center select-col p-2">
                    <div class="d-flex flex-row gap-2 justify-content-center">
                        <button
                            type="button"
                            class="btn btn-warning btn-sm"
                            onclick="window.location='{{ route('contacto_entrega.edit', $c->id_contacto) }}'">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary btn-sm seleccionar-entrega"
                            data-id="{{ $c->id_contacto }}"
                            data-route="{{ route('contactos.seleccionar', $c->id_contacto) }}">
                            <i class="fa fa-check"></i>
                        </button>
                    </div>
                </td>
                  <td>{{ $dir->nombre }}</td>
                  <td>{{ $c->nombreCompleto }}</td>
                  <td>{{ $c->telefono1 }}</td>
                  <td>{{ $c->email }}</td>
                  <td>{{ $dir->calle }} #{{ $dir->num_ext }}</td>
                  <td>{{ $dir->colonia->d_asenta ?? '-' }}</td>
                  <td>{{ $dir->cp }}</td>
                  <td>{{ $dir->ciudad->n_mnpio ?? '-' }}</td>
                  <td>{{ $dir->estado->d_estado ?? '-' }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</div>


@push('scripts')
<!-- =======================================================================
     HELPERS GLOBALES  (window.SIS) 
     ===================================================================== -->
    <script defer>
        window.SIS = (() => {
        const delay = (fn, ms = 400) => { let t; return (...a)=>{ clearTimeout(t); t = setTimeout(()=>fn(...a), ms); }; };
        const selTxt = (sel, txt) => {
            if (!sel) return;
            let opt = [...sel.options].find(o => o.text.trim().toLowerCase() === txt.trim().toLowerCase())
                    || [...sel.options].find(o => o.value === txt);
            if (!opt) { opt = new Option(txt, txt, true, true); sel.prepend(opt); }
            sel.value = opt.value;
        };
        const qs   = s => document.querySelector(s);
        const setV = (id,v='') => { const e=qs('#'+id); if(e) e.value = v ?? ''; };
        const setT = (id,v='—')=> { const e=qs('#'+id); if(e) e.textContent = v ?? '—'; };
        return { delay, selTxt, qs, setV, setT };
        })();
    </script>

<!-- =======================================================================
     FUNCIÓN insertarFila    (versión robusta + console.debug)
     =======================================================================-->
    <script defer>
        (() => {

        window.insertarFila = function (rs)
        {
            try {

            /* ── 1. Tabla ─────────────────────────────────────────────── */
            const tbody = document.querySelector('#tabla-razones tbody');
            if (!tbody) { console.warn('❗ #tabla-razones tbody no encontrado'); return; }

            /* ── 2. Limpio selección previa ───────────────────────────── */
            tbody.querySelectorAll('tr').forEach(r => r.classList.remove('table-success'));

            /* ── 3. Protejo contra “huecos” ───────────────────────────── */
            const dir    = rs.direccion_facturacion || {};
            const col    = dir.colonia || {};
            const ciudad = dir.ciudad  || {};
            const edo    = dir.estado  || {};

            /* ── 4. Construyo la fila ─────────────────────────────────── */
            const tr = document.createElement('tr');
            tr.id = `rs-row-${rs.id_razon_social}`;
            tr.classList.add('table-success');

            tr.innerHTML = `
                <td class="text-center select-col p-2">
                    <div class="d-flex flex-row gap-2 justify-content-center">
                        <button type="button"
                                class="btn btn-warning btn-sm"
                                onclick="window.location='/razones-sociales/${rs.id_razon_social}/edit'">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button type="button"
                                class="btn btn-primary btn-sm seleccionar-direccion"
                                data-id="${rs.id_razon_social}"
                                data-route="/razones-sociales/${rs.id_razon_social}/seleccionar">
                            <i class="fa fa-check"></i>
                        </button>
                    </div>
                </td>
                <td>${rs.nombre                  ?? ''}</td>
                <td>${rs.RFC                     ?? ''}</td>
                <td>${dir.calle      ?? ''} #${dir.num_ext ?? ''}</td>
                <td>${col.d_asenta   ?? ''}</td>
                <td>${dir.cp         ?? ''}</td>
                <td>${ciudad.n_mnpio ?? ''}</td>
                <td>${edo.d_estado   ?? ''}</td>
            `;

            tbody.appendChild(tr);

            } catch (e) {
            console.error('⚡ insertarFila falló:', e, rs);
            /*  — No vuelvo a lanzar la excepción para que el flujo continúe — */
            }
        };

        })();
    </script>

<!-- =======================================================================
     BLOQUE FACTURACIÓN  (autocompletado CP + alta rápida + directorio)
     ===================================================================== -->
    <script defer>
        (() => {
        const { delay, selTxt, setV, setT } = window.SIS;
        const csrf   = document.querySelector('meta[name="csrf-token"]').content;
        const modalF = document.getElementById('modalCrearDireccionFactura');
        const form   = document.getElementById('formNuevaRazonSocialFactura');
        const dirDlg = document.getElementById('modalFacturacion');
        if (!modalF || !form) return;

        // — Autocompletado CP (México) —
        const cpInput = modalF.querySelector('.cp-field-factura');
        const colSel  = modalF.querySelector('.colonia-select-factura');
        const munSel  = modalF.querySelector('.municipio-field-factura');
        const edoSel  = modalF.querySelector('.estado-field-factura');
        const paisSel = modalF.querySelector('.pais-field-factura');

        const resetDir = () => {
            colSel.innerHTML = '<option value="">— Selecciona CP primero —</option>';
            colSel.disabled = true; munSel.value=''; edoSel.value='';
        };
        async function buscarCP(cp) {
            const r = await fetch(`/api/cp/${cp}`);
            if (!r.ok) throw new Error('CP sin datos');
            return r.json();
        }
        function togglePais() {
            const esMx = paisSel.options[paisSel.selectedIndex].text.toLowerCase() === 'méxico';
            cpInput.disabled = colSel.disabled = !esMx;
            if (!esMx) { colSel.innerHTML = '<option>No aplicable fuera de México</option>'; resetDir(); }
        }
        paisSel.addEventListener('change', togglePais);
        cpInput.addEventListener('input', delay(async () => {
            const cp = cpInput.value.trim();
            if (!/^\d{5}$/.test(cp)) { resetDir(); return; }
            try {
            const d = await buscarCP(cp);
            selTxt(edoSel, d.head.estado);
            selTxt(munSel, d.head.municipio);
            colSel.innerHTML = '';
            d.colonias.forEach(c => colSel.add(new Option(`${c.colonia} (${c.tipo})`, c.id_colonia)));
            colSel.disabled = false;
            } catch {
            resetDir();
            }
        }));
        togglePais();

        // — Alta rápida (guardar) —
        form.addEventListener('submit', async e=>{
        e.preventDefault()
        const res = await fetch('{{ route("ajax.direccion.factura") }}',{
            method:'POST',
            headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'},
            body:new FormData(form)
        })
        if (!res.ok) {
            const txt = await res.text();          // <-- obtenemos cuerpo crudo
            console.error('❌ Guardado falló', res.status, txt);
            alert('Error al guardar (abre la consola para ver detalles)');
            return;
        }
        const { razon_social: rs, direccion } = await res.json();
        rs.direccion_facturacion = direccion;           
        bootstrap.Modal.getOrCreateInstance(modalF).hide()

        pintarCard(rs)
        insertarFila(rs)
        actualizarBadge()
        limpiarFormulario()
        })

        // — Selección en directorio —
        dirDlg.addEventListener('click', async e => {
            const btn = e.target.closest('.seleccionar-direccion');
            if (!btn) return;
            const { route:url, id:idRs } = btn.dataset;
            const r = await fetch(url, { method:'POST', headers:{'X-CSRF-TOKEN':csrf} });
            const j = await r.json();
            if (!j.success) { alert('Error'); return; }
            document.querySelectorAll('#tabla-razones tr').forEach(tr=>tr.classList.remove('table-success'));
            document.getElementById('rs-row-'+idRs)?.classList.add('table-success');
            pintarCard(j.razon);
            bootstrap.Modal.getOrCreateInstance(dirDlg).hide();
        });

        // — Helpers locales —
        function pintarCard(rs) {
            const d = rs.direccion_facturacion;
            setT('rs-nombre',   rs.nombre);
            setT('rs-rfc',      rs.RFC);
            setT('dir-calle',   `${d.calle} #${d.num_ext}${d.num_int?' Int.'+d.num_int:''}`);
            setT('dir-colonia', d.colonia?.d_asenta);
            setT('dir-ciudad',  d.ciudad?.n_mnpio);
            setT('dir-estado',  d.estado?.d_estado);
            setT('dir-cp',      d.cp);
            setT('fact-notas',  rs.notas_facturacion)

            // Aquí es donde se llenan los campos ocultos
            setV('id_razon_social', rs.id_razon_social);

        }
        function limpiarFormulario(){
            form.reset();
            resetDir();
            togglePais();
        }
        function actualizarBadge(){
            const btn = document.getElementById('btn-directorio');
            if (!btn) return;
            btn.removeAttribute('disabled');
            const badge = btn.querySelector('.badge');
            if (badge) {
            const total = document.querySelectorAll('#tabla-razones tbody tr').length;
            badge.textContent = total;
            }
        }

          // ➊ Invocación inicial con el predeterminado
        document.addEventListener('DOMContentLoaded', () => {
            @if(isset($rsPredet))
            pintarCard(@json($rsPredet));
            @endif
        });
        })();
    </script>

<!-- =======================================================================
     BLOQUE ENTREGA (autocompletado CP + alta rápida + directorio futuro)
     ===================================================================== -->
    <script defer>
        (() => 
        {
            const { delay, selTxt, setV, setT } = window.SIS;
            const csrf   = document.querySelector('meta[name="csrf-token"]').content;
            const modalE = document.getElementById('modalCrearDireccionEntrega');
            const formE  = document.getElementById('formCrearEntrega');
            if (!modalE || !formE) return;

            /* ── 1) Autocompletado CP (México) ─────────────────────────────── */
            const cpInput = modalE.querySelector('.cp-field-entrega');
            const colSel  = modalE.querySelector('.colonia-select-entrega');
            const munSel  = modalE.querySelector('.municipio-field-entrega');
            const edoSel  = modalE.querySelector('.estado-field-entrega');
            const paisSel = modalE.querySelector('.pais-field-entrega');

            function resetDir() {
                colSel.innerHTML = '<option value="">— Selecciona CP primero —</option>';
                colSel.disabled  = true;
                munSel.value = '';
                edoSel.value = '';
            }

            async function buscarCP(cp) {
                const r = await fetch(`/api/cp/${cp}`);
                if (!r.ok) throw new Error('CP sin datos');
                return r.json();
            }

            function togglePais() {
                const esMx = paisSel.options[paisSel.selectedIndex].text.toLowerCase() === 'méxico';
                cpInput.disabled = colSel.disabled = !esMx;
                if (!esMx) {
                colSel.innerHTML = '<option>No aplicable fuera de México</option>';
                resetDir();
                }
            }

            paisSel.addEventListener('change', togglePais);
            cpInput.addEventListener('input', delay(async () => {
                const cp = cpInput.value.trim();
                if (!/^\d{5}$/.test(cp)) { resetDir(); return; }
                try {
                const d = await buscarCP(cp);
                selTxt(edoSel, d.head.estado);
                selTxt(munSel, d.head.municipio);
                colSel.innerHTML = '';
                d.colonias.forEach(c=>{
                    colSel.add(new Option(`${c.colonia} (${c.tipo})`, c.id_colonia));
                });
                colSel.disabled = false;
                } catch {
                resetDir();
                }
            }));
            togglePais();

                /* ── 2) Helpers de entrega ──────────────────────────────────────── */
                function formatCalle(d) {
                    return `${d.calle||''} #${d.num_ext||''}` + (d.num_int ? ' Int. '+d.num_int : '');
                }

                function valStr(v, prop = '') {
                    if (!v)           return '—';               // null / undefined
                    if (typeof v === 'string') return v;        // ya es texto
                    if (prop && v[prop] !== undefined) return v[prop];   // modelo → campo
                    // fallback genérico («nombre» o primera prop encontrada)
                    return v.nombre ?? Object.values(v)[0] ?? '—';
                }

                function getTel(c){
                return c?.telefono  ?? c?.telefono1 ?? '';   // acepta ambos nombres
                }
                function getExt(c){
                return c?.ext       ?? c?.ext1      ?? '';
                }

                function pintarEntrega(ent) 
                {
                    if (!ent.contacto && ent.id_contacto) 
                    {
                        ent = {
                        contacto : ent,
                        direccion: ent.direccion_entrega,   // el atributo en el modelo Contacto
                        };
                        ent.id_direccion_entrega = ent.direccion?.id_direccion;
                    }
                      const d = ent.direccion ?? {};
                      const c = ent.contacto ?? {};

                        /* ► visibles */
                        setT('entrega-nombre',   valStr(d.nombre));
                        let nombreCompleto = [c?.nombre, c?.apellido_p, c?.apellido_m].filter(Boolean).join(' ') || '—';
                        if (nombreCompleto.length > 27) nombreCompleto = nombreCompleto.slice(0, 24) + '…';
                        setT('entrega-contacto', nombreCompleto);
                        setT('entrega-telefono', getTel(c) || '—');
                        setT('entrega-ext',      getExt(c) || '—');
                        setT('entrega-email',    valStr(ent.contacto?.email));

                        setT('entrega-calle',    `${valStr(d.calle)} #${valStr(d.num_ext)}${d.num_int ? ' Int. '+d.num_int : ''}`);
                        setT('entrega-colonia',  valStr(d.colonia, 'd_asenta'));
                        setT('entrega-ciudad',   valStr(d.ciudad,  'n_mnpio'));
                        setT('entrega-estado',   valStr(d.estado,  'd_estado'));
                        setT('entrega-cp',       valStr(d.cp));
                        setT('entrega-pais',     valStr(d.pais,    'nombre'));
                        setT('entrega-notas',    valStr(ent.contacto?.notas_entrega, 'notas_entrega'));

                        /* ► ocultos  */
                        setV('id_contacto_entrega', ent.contacto?.id_contacto);


                }

                document.addEventListener('DOMContentLoaded', () => {
                    @if(isset($contacto_entrega))
                        pintarEntrega(@json($contacto_entrega));
                    @endif
                });




            /* ── 3) Alta rápida contacto + dirección ───────────────────────── */
            formE.addEventListener('submit', async e => {
                e.preventDefault();
                try {
                const res = await fetch(formE.action, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                    body: new FormData(formE)
                });
                const j = await res.json();
                if (!j.success) {
                    alert(j.message || 'No se guardó la dirección');
                    return;
                }

                // 1) Pinto card y formulario oculto
                pintarEntrega(j.entrega);

                // 2) (Opcional) Directorio futuro
                if (typeof insertarFilaEntrega === 'function') insertarFilaEntrega(j.entrega);
                if (typeof actualizarBadgeEntrega === 'function') actualizarBadgeEntrega();

                // 3) Limpio form y cierro modal
                formE.reset();
                resetDir();
                togglePais();
                bootstrap.Modal.getOrCreateInstance(modalE).hide();

                } catch (err) {
                console.error(err);
                alert('Error de red al guardar la dirección');
                }
            });

            
        /* -----------------------------------------------------------
            1)  Insertar una fila nueva tras alta rápida
        ----------------------------------------------------------- */
        window.insertarFilaEntrega = function(ent){
            const tbody = document.querySelector('#tabla-entregas tbody');
            if(!tbody) return;

            // desmarca todo y arma fila
            tbody.querySelectorAll('tr').forEach(tr=>tr.classList.remove('table-success'));

            const d   = ent.direccion;
            const tel = ent.contacto.telefono||'';
            const row = document.createElement('tr');
            row.id = `entrega-row-${ent.id_direccion_entrega}`;
            row.classList.add('table-success');
            row.innerHTML = `
            <td>
                <button type="button" class="btn btn-sm btn-success seleccionar-entrega"
                        data-id="${ent.contacto.id_contacto}"
                        data-route="/contactos/${ent.contacto.id_contacto}/seleccionar">
                <i class="fa fa-check"></i>
                </button>
            </td>
            <td>${d.nombre||''}</td>
            <td>${ent.contacto.nombre}</td>
            <td>${tel}</td>
            <td>${ent.contacto.email||''}</td>
            <td>${d.calle} #${d.num_ext}</td>
            <td>${d.colonia}</td>
            <td>${d.cp}</td>
            <td>${d.ciudad}</td>
            <td>${d.estado}</td>`;
            tbody.appendChild(row);
        };

        /* -----------------------------------------------------------
            2)  Badge numérico del botón directorio
        ----------------------------------------------------------- */
        window.actualizarBadgeEntrega = function(){
            const btn   = document.getElementById('btn-directorio-entrega');
            if(!btn) return;
            btn.removeAttribute('disabled');
            const total = document.querySelectorAll('#tabla-entregas tbody tr').length;
            btn.querySelector('.badge').textContent = total;
        };

        /* 3) Selección dentro del modal --------------------------- */
        document
            .getElementById('modalEntrega')
            .addEventListener('click', async e => 
            {

                const btn = e.target.closest('.seleccionar-entrega');
                if (!btn) return;               // no es el botón ✔️

                const url        = btn.dataset.route;
                const idContacto = btn.dataset.id;

                try 
                {
                    const res = await fetch(url, {
                    method : 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
                    });
                    const j = await res.json();
                    if (!j.success) {
                    alert(j.message || 'Error al seleccionar');
                    return;
                    }

                    /* 1️⃣ Pinta la card (y los inputs ocultos) */
                    pintarEntrega(j.entrega);

                    /* 2️⃣ Resalta la fila correcta */
                    document
                    .querySelectorAll('#tabla-entregas tbody tr')
                    .forEach(tr => tr.classList.remove('table-success'));

                    document
                    .getElementById('entrega-row-' + idContacto)
                    ?.classList.add('table-success');

                    /* 3️⃣ Cierra el modal */
                    bootstrap
                    .Modal
                    .getOrCreateInstance(document.getElementById('modalEntrega'))
                    .hide();

                } catch (err) {
                    console.error(err);
                    alert('Error de red');
                }
            });
        })();
    </script>


<!-- =======================================================================
     BLOQUE PARTIDAS (alta rápida + eliminar)
     ===================================================================== -->
<script defer>
(() => {
  /* -----  Config  ----- */
  const LS_KEY   = 'cotizacion_{{ $cotizacion->cliente->id_cliente }}'; // key por cliente
  const TTL_MIN  = 20;                                      // 20 min de vida
  const IVA_RATE = 0.16;                                    // 16 %

  /* -----  utilidades  ----- */
  const nowSec = ()   => Math.floor(Date.now()/1000);
  const fmt    = num  => (+num).toLocaleString('es-MX',{minimumFractionDigits:2});

  /* -----  elementos  ----- */
  const tbody       = document.querySelector('#tablaPartidas tbody');
  const subtotalEl  = document.getElementById('partidasSubtotal');
  const ivaEl       = document.getElementById('partidasIVA');
  const totalEl     = document.getElementById('partidasTotal');
  const formP       = document.getElementById('formPartida');

  /* -----  LS: cargar / guardar  ----- */
  const loadState = () => {
    try {
      const raw = localStorage.getItem(LS_KEY);
      if (!raw) return { ts: nowSec(), partidas: [] };

      const data = JSON.parse(raw);
      if (nowSec() - data.ts > TTL_MIN * 60) {
        localStorage.removeItem(LS_KEY);
        return { ts: nowSec(), partidas: [] };
      }
      return data;
    } catch { return { ts: nowSec(), partidas: [] }; }
  };

  const saveState = st => {
    st.ts = nowSec();                       // renueva TTL
    localStorage.setItem(LS_KEY, JSON.stringify(st));
  };

  /* -----  render  ----- */
  const render = () => {
    tbody.innerHTML = '';
    state.partidas.forEach((p, i) => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${i+1}</td>
        <td>${p.sku || '-'}</td>
        <td>${p.descripcion}</td>
        <td class="text-end">${fmt(p.precio)}</td>
        <td class="text-end">${fmt(p.costo)}</td>
        <td class="text-end">${p.cantidad}</td>
        <td class="text-end">${fmt(p.importe)}</td>
        <td class="text-center">
          <button data-idx="${i}" class="btn btn-xs btn-outline-danger borrar-partida">
            <i class="fa fa-trash"></i>
          </button>
        </td>`;
      tbody.appendChild(tr);
    });

    const sub = state.partidas.reduce((s, p) => s + p.importe, 0);
    const iva = sub * IVA_RATE;
    const tot = sub + iva;

    subtotalEl.textContent = fmt(sub);
    ivaEl.textContent      = fmt(iva);
    totalEl.textContent    = fmt(tot);
  };

  /* -----  estado inicial  ----- */
  const state = loadState();
  render();

  /* -----  agregar partida  ----- */
  formP.addEventListener('submit', e => {
    e.preventDefault();
    const fd = new FormData(formP);

    const partida = {
      sku:         fd.get('sku')?.trim() || '',
      descripcion: fd.get('descripcion').trim(),
      cantidad:    +fd.get('cantidad'),
      precio:      +fd.get('precio'),
      costo:       +fd.get('costo'),
      importe:     +fd.get('cantidad') * +fd.get('precio'),
      score:       (+fd.get('precio') - +fd.get('costo')) * +fd.get('cantidad')
    };

    if (!partida.descripcion) return alert('La descripción es obligatoria');
    if (partida.cantidad <= 0 || partida.precio < 0 || partida.costo < 0)
      return alert('Datos numéricos no válidos');

    state.partidas.push(partida);
    saveState(state);
    render();
    formP.reset();
    formP.descripcion.focus();
  });

  /* -----  borrar partida  ----- */
  tbody.addEventListener('click', e => {
    const btn = e.target.closest('.borrar-partida');
    if (!btn) return;
    state.partidas.splice(+btn.dataset.idx, 1);
    saveState(state);
    render();
  });

  /* -----  exportador para el form grande  ----- */
  window.getPartidasForSubmit = () => state.partidas;
})();
</script>



<!-- =======================================================================
     ENVÍO DE COTIZACIÓN COMPLETA (Submit Global)
     ===================================================================== -->
<script defer>
(() => {

  /* ---- elemento & llave ---- */
  const btnGuardar    = document.getElementById('btnGuardarCotizacion');   // tu botón
  const formFact      = document.getElementById('formHiddenFact');
  const formEntrega   = document.getElementById('formHiddenEntrega');
  const partidas      = window.getPartidasForSubmit;        // función expuesta antes
  const csrf          = document.querySelector('meta[name=csrf-token]').content;
  const clienteID     = {{ $cotizacion->cliente->id_cliente }};         // ya lo tienes en Blade

  /* ---- click ---- */
  btnGuardar.addEventListener('click', async () => {

    if (partidas().length === 0){
      return alert('Agrega al menos una partida antes de guardar.');
    }

    /* 1️⃣  Armamos payload */
    const fd = new FormData();

    // a) datos “estáticos” (clonamos los formularios ocultos)
    [...new FormData(formFact).entries()].forEach(([k,v]) => fd.append(k,v));
    [...new FormData(formEntrega).entries()].forEach(([k,v]) => fd.append(k,v));

    // b) partidas ⇒ enviamos JSON
    fd.append('partidas', JSON.stringify(partidas()));

    /* 2️⃣  POST */
    try
    {
      const res  = await fetch('{{ route("cotizaciones.store") }}', {
        method : 'POST',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept':'application/json' },
        body   : fd
      });

      const j = await res.json();
      if (!j.success){
        throw new Error(j.message || 'Error al guardar');
      }

      /* 3️⃣  éxito */
      localStorage.removeItem(`cotizacion_${clienteID}`);
      alert('¡Cotización guardada!');
      window.location.href = j.redirect_to;     // p.ej. /cotizaciones/123

    }catch(err){
      console.error(err);
      alert('No se pudo guardar la cotización');
    }
  });

})();
</script>



@endpush


@endsection