<div class="vertical-menu" style="max-height: 250ch;">
    <div data-simplebar class="h-100">
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">

                <li class="menu-title" data-key="t-menu" style="font-size: 0.9rem; padding: 10px 25px !important;">Menú</li>

                <li>
                    <a href="{{ route('inicio') }}">
                        <i data-feather="home"></i>
                        <span class="menu-item">Inicio</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <span class="icon icon-crm" ></span>
                        <span class="menu-item">CRM</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalTipoCuenta">
                                Nueva Cuenta
                            </a>
                        </li>
                        <li><a href="{{ route('clientes.index') }}">Mis Cuentas</a></li>
                        <li><a href="{{ route('clientes.transfer') }}">Traspaso de Cuentas</a></li>
                        <li><a href="{{ route('inicio') }}">Mis Recall's</a></li>
                        <li><a href="{{ route('inicio') }}">Cuentas Archivadas</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <span class="icon icon-cotizaciones"></span>
                        <span class="menu-item">Monitor de Cotizaciones</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('inicio') }}">Monitor de Cotizaciones</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <span class="icon icon-ventas"></span>
                        <span class="menu-item">Monitor de Ventas</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('inicio') }}">Monitor de Ventas</a></li>
                        <li><a href="{{ route('inicio') }}">Metas de Venta</a></li>
                        <li><a href="{{ route('inicio') }}">Permisos</a></li>
                    </ul>
                </li>

                <li>
                    <a href="{{ route('inicio') }}">
                        <span class="icon icon-cobranza"></span>
                        <span class="menu-item">Monitor de Cobranza</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('inicio') }}">
                        <i data-feather="globe"></i>
                        <span class="menu-item">E-Commerce</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('inicio') }}">
                        <span class="icon icon-marketing"></span>
                        <span class="menu-item">Marketing</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="settings"></i>
                        <span class="menu-item">Administración</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('inicio') }}">Alta de Proveedor</a></li>
                        <li><a href="{{ route('inicio') }}">Mis proveedores</a></li>
                        <li><a href="{{ route('usuarios.index') }}">Usuarios de SIS</a></li>
                        <li><a href="{{ route('inicio') }}">Asistencia</a></li>
                    </ul>
                </li>

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
                                    <p class="mb-0 text-muted small">Para empresas privadas e instituciones de gobierno	</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-12">
                            <a href="{{ route('clientes.create', ['tipo' => 'fisica']) }}" class="tipo-opcion-card">
                                <div class="opcion-card-body">
                                    <strong>Cuenta Personal</strong>
                                    <p class="mb-0 text-muted small">Para personas físicas y profesionales independientes</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


</div>