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

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css"
        />

    @stack('styles')

    <!-- Incluye esto en tu layout, tras jQuery y Bootstrap.js -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>

<script>
  $(function(){
    $('#ejecutivos').multiselect({
      includeSelectAllOption: true,       // agrega el checkbox “Seleccionar todo”
      selectAllText: 'Seleccionar todo',  // texto para ese checkbox
      allSelectedText: 'Todo seleccionado ({0})',
      nonSelectedText: 'Ninguno seleccionado',
      enableFiltering: true,              // buscador interno
      enableCaseInsensitiveFiltering: true,
      buttonWidth: '100%',
      maxHeight: 250,
      numberDisplayed: 2,                 // cuántos mostrar antes de “+3 más”
      // **NO** pongas maxSelectionLength: 1 (ese forzaría sólo 1)
    });
  });
</script>


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
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>

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
document.addEventListener('DOMContentLoaded', () => {

    const format = (input) => {
        let digits = input.value.replace(/\D/g, '');
        if (digits.length > 10) digits = digits.slice(0, 10);

        // --- aplica formato ---
        let pretty = digits;
        if (digits.length === 10) {
            pretty = digits.startsWith('55')
                     ? digits.replace(/(\d{2})(\d{4})(\d{4})/, '($1)-$2-$3')
                     : digits.replace(/(\d{3})(\d{3})(\d{4})/, '($1)-$2-$3');
        } else if (digits.length >= 7) {
            pretty = digits.replace(
                /(\d{3})(\d{0,3})(\d{0,4})/,
                (_, a, b, c) => a + (b ? '-' + b : '') + (c ? '-' + c : '')
            );
        }

        input.value      = pretty;
        input.maxLength  = pretty.startsWith('(55)') ? 14 : 15;
        input.setCustomValidity(digits.length === 10 ? '' : 'Número incompleto');
    };

    /* ---- Formateo INICIAL de todos los campos phone-field ---- */
    document.querySelectorAll('.phone-field').forEach(format);

    /* ---- Formateo en tiempo real cuando el usuario edite ---- */
    document.addEventListener('input', e => {
        const input = e.target.closest('.phone-field');
        if (input) format(input);
    });

});
</script>


    @stack('scripts')
</body>

</html>