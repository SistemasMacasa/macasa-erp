<div class="vertical-menu" style="max-height: 250ch;">
    <div data-simplebar class="h-100">
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled text-white" id="side-menu">

                {{-- ═══ Inicio (siempre visible para usuarios autenticados) ═══ --}}
                <li>
                    <a href="{{ route('inicio') }}">
                        <i data-feather="home"></i>
                        <span class="menu-item">Inicio</span>
                    </a>
                </li>

                {{-- ═══ CRM ═══════════════════════════════════════════════════ --}}
                @canany(['Nueva Cuenta', 'Mis Cuentas', 'Traspaso de Cuentas', 'Mis Recalls', 'Cuentas Archivadas'])
                    <li>
                        <a href="javascript:void(0);" class="has-arrow">
                            <span class="icon icon-crm"></span>
                            <span class="menu-item">CRM</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">

                            @can('Nueva Cuenta')
                                <li>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#modalTipoCuenta">
                                        Nueva Cuenta
                                    </a>
                                </li>
                            @endcan

                            @can('Mis Cuentas')
                                <li><a href="{{ route('clientes.index') }}">Mis Cuentas</a></li>
                            @endcan

                            @can('Traspaso de Cuentas')
                                <li><a href="{{ route('clientes.transfer') }}">Traspaso de Cuentas</a></li>
                            @endcan

                            @can('Mis Recalls')
                                <li><a href="{{ route('clientes.recalls') }}">Mis Recall's</a></li>
                            @endcan

                            @can('Cuentas Archivadas')
                                <li><a href="{{ route('clientes.archivadas') }}">Cuentas Archivadas</a></li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                {{-- ═══ Monitor de Cotizaciones ═══════════════════════════════ --}}
                @can('Monitor de Cotizaciones')
                    <li>
                        <a href="javascript:void(0);" class="has-arrow">
                            <span class="icon icon-cotizaciones"></span>
                            <span class="menu-item">Monitor de Cotizaciones</span>
                        </a>
                        <ul class="sub-menu">
                            <li><a href="{{ route('cotizaciones.index') }}">Monitor de Cotizaciones</a></li>
                        </ul>
                    </li>
                @endcan

                {{-- ═══ Monitor de Ventas ═════════════════════════════════════ --}}
                @canany(['Monitor de Ventas', 'Metas de Venta', 'Permisos'])
                    <li>
                        <a href="javascript:void(0);" class="has-arrow">
                            <span class="icon icon-ventas"></span>
                            <span class="menu-item">Monitor de Ventas</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            @can('Monitor de Ventas')
                                <li><a href="{{ route('inicio') }}">Monitor de Ventas</a></li>
                            @endcan
                            @can('Metas de Venta')
                                <li><a href="{{ route('inicio') }}">Metas de Venta</a></li>
                            @endcan
                            @can('Permisos')
                                <li><a href="{{ route('inicio') }}">Permisos</a></li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                {{-- ═══ Monitor de Cobranza ════════════════════════════════════ --}}
                @can('Monitor de Cobranza')
                    <li>
                        <a href="{{ route('inicio') }}">
                            <span class="icon icon-cobranza"></span>
                            <span class="menu-item">Monitor de Cobranza</span>
                        </a>
                    </li>
                @endcan

                {{-- ═══ E-Commerce ════════════════════════════════════════════ --}}
                @can('E Commerce')
                    <li>
                        <a href="{{ route('inicio') }}">
                            <i data-feather="globe"></i>
                            <span class="menu-item">E-Commerce</span>
                        </a>
                    </li>
                @endcan

                {{-- ═══ Marketing ═════════════════════════════════════════════ --}}
                @can('Marketing')
                    <li>
                        <a href="{{ route('inicio') }}">
                            <span class="icon icon-marketing"></span>
                            <span class="menu-item">Marketing</span>
                        </a>
                    </li>
                @endcan

                {{-- ═══ Administración ═══════════════════════════════════════ --}}
                @canany(['Alta de Proveedor', 'Mis proveedores', 'Usuarios de SIS', 'Asistencia', 'Permisos'])
                    <li>
                        <a href="javascript:void(0);" class="has-arrow">
                            <i data-feather="settings"></i>
                            <span class="menu-item">Administración</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">

                            @can('Alta de Proveedor')
                                <li><a href="{{ route('inicio') }}">Alta de Proveedor</a></li>
                            @endcan

                            @can('Mis proveedores')
                                <li><a href="{{ route('inicio') }}">Mis proveedores</a></li>
                            @endcan

                            @can('Usuarios de SIS')
                                <li><a href="{{ route('usuarios.index') }}">Usuarios de SIS</a></li>
                            @endcan

                            @can('Asistencia')
                                <li><a href="{{ route('inicio') }}">Asistencia</a></li>
                            @endcan

                            @can('Permisos')
                                <li><a href="{{ route('permisos.index') }}">Permisos</a></li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

            </ul>


            <div class="card sidebar-alert border-0 text-center mx-4 mb-0 mt-5">
                <div class="card-body">
                    <img src="{{ asset('assets/images/construction.png') }}" alt="En construcción" height="80">
                    <div class="mt-4">
                        <h5 class="alertcard-title font-size-16">¡SIS en desarrollo!</h5>
                        <p class="font-size-13">Algunas secciones del sistema todavía están en construcción.</p>

                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modalTipoCuenta" tabindex="-1" aria-labelledby="modalTipoCuentaLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-header flex-column border-0 bg-white">
                    <h4 class="modal-title w-100 fw-bold text-primary-emphasis">
                        <i class="fa fa-user-plus me-2 text-primary"></i> Nueva Cuenta
                    </h4>
                    <hr class="w-100 my-2 opacity-25">
                    <button type="button" class="btn-close position-absolute end-0 top-0 mt-3 me-3"
                        data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body pt-0">
                    <p class="text-muted mb-4">¿Qué tipo de cuenta deseas crear?</p>

                    <div class="row g-3 justify-content-center">
                        <div class="col-12">
                            <a href="{{ route('clientes.create', ['tipo' => 'moral']) }}" class="tipo-opcion-card">
                                <div class="opcion-card-body">
                                    <strong>Cuenta Empresarial</strong>
                                    <p class="mb-0 text-muted small">Para empresas privadas e instituciones de gobierno
                                    </p>
                                </div>
                            </a>
                        </div>
                        <div class="col-12">
                            <a href="{{ route('clientes.create', ['tipo' => 'fisica']) }}" class="tipo-opcion-card">
                                <div class="opcion-card-body">
                                    <strong>Cuenta Personal</strong>
                                    <p class="mb-0 text-muted small">Para personas físicas y profesionales
                                        independientes</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


</div>