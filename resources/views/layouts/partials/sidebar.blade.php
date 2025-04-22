<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">

                <li class="menu-title" data-key="t-menu">Menú</li>

                <li>
                    <a href="{{ route('inicio') }}">
                        <i data-feather="home"></i>
                        <span>dev</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="users"></i>
                        <span>Mi CRM</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('inicio') }}">Nueva Cuenta Organizacional</a></li>
                        <li><a href="{{ route('inicio') }}">Cuentas Ecommerce</a></li>
                        <li><a href="{{ route('clientes.index') }}">Mis Cuentas</a></li>
                        <li><a href="{{ route('inicio') }}">Traspaso de Cuentas</a></li>
                        <li><a href="{{ route('inicio') }}">Mis Recall's</a></li>
                        <li><a href="{{ route('inicio') }}">Cuentas Archivadas</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="file-text"></i>
                        <span>Monitor de Cotizaciones</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('inicio') }}">Monitor de Cotizaciones</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="shopping-cart"></i>
                        <span>Monitor de Ventas</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('inicio') }}">Monitor de Ventas</a></li>
                        <li><a href="{{ route('inicio') }}">Metas de Venta</a></li>
                        <li><a href="{{ route('inicio') }}">Permisos</a></li>
                    </ul>
                </li>

                <li>
                    <a href="{{ route('inicio') }}">
                        <i data-feather="dollar-sign"></i>
                        <span>Monitor de Cobranza</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('inicio') }}">
                        <i data-feather="globe"></i>
                        <span>E-Commerce</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('inicio') }}">
                        <i data-feather="megaphone"></i>
                        <span>Marketing</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('inicio') }}">
                        <i data-feather="settings"></i>
                        <span>Administración</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div>
