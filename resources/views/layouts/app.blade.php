<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'MACASA')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <script>
        //Cargar info de LocalStorage para Botón dark mode
        (function () {
            const savedTheme = localStorage.getItem("macasa-theme") || "light";
            document.documentElement.setAttribute("data-bs-theme", savedTheme);
            document.body.setAttribute("data-bs-theme", savedTheme);
            document.body.setAttribute("data-topbar", savedTheme);
            document.body.setAttribute("data-sidebar", savedTheme);
        })();
    </script>

    <script>
        //Cargar info de LocalStorage para Botón hamburguesa que contrae el menú lateral
        (function () {
            const savedSidebarSize = localStorage.getItem("sidebarSize") || 'lg';
            document.body.setAttribute('data-sidebar-size', savedSidebarSize);
        })();
    </script>

    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <link href="{{ asset('assets/css/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/preloader.min.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

    @stack('styles')
</head>

<body>
    <div id="layout-wrapper">

        {{-- Topbar --}}
        @include('layouts.partials.topbar')

        {{-- Sidebar --}}
        @include('layouts.partials.sidebar')

        {{-- Contenido --}}
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    {{-- Breadcrumbs --}}
                    @includeWhen(View::hasSection('breadcrumb'), 'layouts.partials.breadcrumbs')

                    @yield(section: 'content')
                </div>
            </div>

            @include('layouts.partials.footer')
        </div>
    </div>
    {{-- RightBar --}}
    @include('layouts.partials.rightbar')

    {{-- Scripts --}}
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/waves.min.js') }}"></script>
    <script src="{{ asset('assets/js/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/pace.min.js') }}"></script>
    <script src="{{ asset('assets/js/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <!-- <script src="{{ asset('assets/js/dashboard.init.js') }}"></script> -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            if (typeof feather !== 'undefined') {
                feather.replace();
            } else {
                console.error('Feather no está cargado o no está disponible.');
            }

            const toggleBtn = document.getElementById("vertical-menu-btn");

            if (toggleBtn) {
                toggleBtn.addEventListener("click", function (e) {
                    e.preventDefault();

                    // Alternar clase
                    document.body.classList.toggle("sidebar-enable");

                    // Alternar data-sidebar-size
                    const currentSize = document.body.getAttribute("data-sidebar-size") || 'lg';
                    const newSize = currentSize === 'lg' ? 'sm' : 'lg';
                    document.body.setAttribute('data-sidebar-size', newSize);
                    localStorage.setItem('sidebarSize', newSize);
                });
            }
        });
    </script>
    <script>
        //Quitar mensaje de éxito después de 5 segundos
        // Se utiliza setTimeout para eliminar el mensaje después de 5 segundos
        setTimeout(function () {
            const alert = document.getElementById('success-alert');
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 500); // se elimina del DOM después del fade
            }
        }, 5000);
    </script>
    <script>
        $('#vertical-menu-btn').on('click', function (t) {
            t.preventDefault();
            $('body').toggleClass('sidebar-enable');

            if ($(window).width() >= 992) {
                const currentSize = document.body.getAttribute('data-sidebar-size') || 'lg';
                const newSize = currentSize === 'lg' ? 'sm' : 'lg';

                document.body.setAttribute('data-sidebar-size', newSize);
                localStorage.setItem('sidebarSize', newSize);
            }
        });

    </script>

    <script>
        // Buscador de clientes
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('buscador-clientes');
            const filas = document.querySelectorAll('table tbody tr');

            input.addEventListener('input', function () {
                const filtro = input.value.toLowerCase();

                filas.forEach(fila => {
                    const texto = fila.textContent.toLowerCase();
                    fila.style.display = texto.includes(filtro) ? '' : 'none';
                });
            });
        });
    </script>

    <script>
        // Buscador de usuarios
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('buscador-usuarios');
            const filas = document.querySelectorAll('table tbody tr');

            input.addEventListener('input', function () {
                const filtro = input.value.toLowerCase();

                filas.forEach(fila => {
                    const texto = fila.textContent.toLowerCase();
                    fila.style.display = texto.includes(filtro) ? '' : 'none';
                });
            });
        });
    </script>
    <script>
        /*   alterna todos los checks de clientes.index   */
        document.addEventListener('DOMContentLoaded', () => {
            const master = document.getElementById('check-all');
            master?.addEventListener('change', () => {
                document
                    .querySelectorAll('.chk-row')
                    .forEach(chk => chk.checked = master.checked);
            });
        });
    </script>
    <script>
        //Agregar direcciones de entrega al formulario de nueva cuenta
        document.addEventListener('DOMContentLoaded', function () {
            const btnAgregar = document.getElementById('agregarDireccionEntrega');
            const contenedor = document.getElementById('contenedorDireccionesEntrega');
            let index = 1;

            btnAgregar.addEventListener('click', function () {
                if (index >= 10) return;

                const nuevo = document.createElement('div');
                nuevo.className = "entrega-block mb-4 border rounded p-3 bg-light-subtle";
                nuevo.innerHTML = `
                <h6 class="mb-3 text-muted">Datos de Entrega ${index + 1}</h6>
                
                    <!-- ╭━━━━ Contacto de Entrega ━━━━╮ -->
                <h6 class="mb-3">Contacto de Entrega</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="direccion_entrega[${index + 1}][contacto][nombre]" class="form-label">
                                Nombre(s) <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="direccion_entrega[${index + 1}][contacto][nombre]"
                                id="entrega_contacto_nombre_${index + 1}"
                                class="form-control @error('direccion_entrega.${index + 1}.contacto.nombre') is-invalid @enderror"
                                value="{{ old('direccion_entrega.${index + 1}.contacto.nombre') }}" required>
                            @error('direccion_entrega.${index + 1}.contacto.nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="direccion_entrega[${index + 1}][contacto][apellido_p]" class="form-label">
                                Primer Apellido <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="direccion_entrega[${index + 1}][contacto][apellido_p]"
                                id="entrega_contacto_apellido_p_${index + 1}"
                                class="form-control @error('direccion_entrega.${index + 1}.contacto.apellido_p') is-invalid @enderror"
                                value="{{ old('direccion_entrega.${index + 1}.contacto.apellido_p') }}" required>
                            @error('direccion_entrega.${index + 1}.contacto.apellido_p')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="direccion_entrega[${index + 1}][contacto][apellido_m]" class="form-label">
                                Apellido materno
                            </label>
                            <input type="text" name="direccion_entrega[${index + 1}][contacto][apellido_m]"
                                id="entrega_contacto_apellido_m_${index + 1}" class="form-control"
                                value="{{ old('direccion_entrega.${index + 1}.contacto.apellido_m') }}">
                        </div>
                    </div>
                    <div class="row g-3 mt-1">
                        <div class="col-md-4">
                            <label for="direccion_entrega[${index + 1}][contacto][telefono]" class="form-label">
                                Teléfono <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="direccion_entrega[${index + 1}][contacto][telefono]"
                                id="entrega_contacto_telefono_${index + 1}"
                                class="form-control @error('direccion_entrega.${index + 1}.contacto.telefono') is-invalid @enderror"
                                value="{{ old('direccion_entrega.${index + 1}.contacto.telefono') }}" required>
                            @error('direccion_entrega.${index + 1}.contacto.telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2">
                            <label for="direccion_entrega[${index + 1}][contacto][ext]" class="form-label">
                                Ext.
                            </label>
                            <input type="text" name="direccion_entrega[${index + 1}][contacto][ext]"
                                id="entrega_contacto_ext_${index + 1}" class="form-control"
                                value="{{ old('direccion_entrega.${index + 1}.contacto.ext') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="direccion_entrega[${index + 1}][contacto][email]" class="form-label">
                                Correo electrónico
                            </label>
                            <input type="email" name="direccion_entrega[${index + 1}][contacto][email]"
                                id="entrega_contacto_email_${index + 1}"
                                class="form-control @error('direccion_entrega.${index + 1}.contacto.email') is-invalid @enderror"
                                value="{{ old('direccion_entrega.${index + 1}.contacto.email') }}">
                            @error('direccion_entrega.${index + 1}.contacto.email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- ╰━━━━ Fin Contacto de Entrega ━━━━╯ -->
                    
                    <hr class="my-4">
                    <!-- ╭━━━━ Contacto de Entrega ━━━━╮ -->
                    <h6 class="mb-3">Dirección de Entrega</h6>
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Nombre de la Dirección</label>
                            <input name="direcciones_entrega[${index}][nombre]" class="form-control">
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4"><label class="form-label">Calle</label>
                            <input name="direcciones_entrega[${index}][calle]" class="form-control">
                        </div>
                        <div class="col-md-2"><label class="form-label">Num. ext.</label>
                            <input name="direcciones_entrega[${index}][num_ext]" class="form-control">
                        </div>
                        <div class="col-md-2"><label class="form-label">Num. int.</label>
                            <input name="direcciones_entrega[${index}][num_int]" class="form-control">
                        </div>
                        <div class="col-md-4"><label class="form-label">Colonia</label>
                            <input name="direcciones_entrega[${index}][colonia]" class="form-control">
                        </div>
                    </div>
                    <div class="row g-3 mt-1">
                        <div class="col-md-4"><label class="form-label">Ciudad / Municipio</label>
                            <input name="direcciones_entrega[${index}][ciudad]" class="form-control">
                        </div>
                        <div class="col-md-4"><label class="form-label">Estado</label>
                            <input name="direcciones_entrega[${index}][estado]" class="form-control">
                        </div>
                        <div class="col-md-2"><label class="form-label">País</label>
                            <input name="direcciones_entrega[${index}][pais]" class="form-control" value="México">
                        </div>
                        <div class="col-md-2"><label class="form-label">C.P.</label>
                            <input name="direcciones_entrega[${index}][cp]" class="form-control">
                        </div>
                    </div>
            `;

                contenedor.appendChild(nuevo);
                index++;
            });
        });
    </script>
    <script>
        //Agregar datos de facturación al formulario de nueva cuenta
        // Solo se permite agregar hasta 10 bloques de facturación
        document.addEventListener('DOMContentLoaded', function () {
            const btnAgregar = document.getElementById('agregarFacturacion');
            const contenedor = document.getElementById('contenedorFacturacion');
            let index = 1;

            btnAgregar.addEventListener('click', function () {
                if (index >= 10) return;

                const bloque = contenedor.querySelector('.facturacion-block').cloneNode(true);
                bloque.querySelector('h6').innerText = `Razón Social ${index + 1}`;

                // Reemplazar todos los índices [0] por [index]
                bloque.innerHTML = bloque.innerHTML.replace(/\[0\]/g, `[${index}]`);
                contenedor.appendChild(bloque);

                index++;
            });
        });
    </script>
    @stack('scripts')
</body>

</html>