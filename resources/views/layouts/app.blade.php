<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Metadatos básicos -->
    <meta charset="UTF-8">
    <title>@yield('title', 'MACASA')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- =======================================
         HOJAS DE ESTILO (en orden de dependencia)
    ======================================== -->

    <!-- Bootstrap core -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

    <!-- Librerías de terceros -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"/>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"/>
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-jvectormap-1.2.2.css') }}"/>

    <!-- Estilos del template -->
    <link rel="stylesheet" href="{{ asset('assets/css/icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/preloader.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

    <!-- Espacio para estilos extra de vistas hijas -->
    @stack('styles')

    <!-- =======================================
         SCRIPTS BASE (los que otras vistas necesitan)
    ======================================== -->

    <!-- jQuery (única copia) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap (popper incluido en bundle) -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- ApexCharts – sólo para los dashboards -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>

    <!-- Preferencias guardadas en LocalStorage (tema & sidebar) -->
    <script>
        // Modo oscuro / claro
        (() => {
            const saved = localStorage.getItem('macasa-theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', saved);
            document.body.setAttribute('data-bs-theme', saved);
            document.body.setAttribute('data-topbar', saved);
            document.body.setAttribute('data-sidebar', saved);
        })();

        // Tamaño del sidebar
        (() => {
            const saved = localStorage.getItem('sidebarSize') || 'lg';
            document.body.setAttribute('data-sidebar-size', saved);
        })();
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
        //FORMATEO DE NUMERO DE TELEFONO SOLO AGREGAR .phone-field
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

    <script>
        /**
         * Convierte el valor a MAYÚSCULAS cuando el input pierde el foco.
         * Funciona también con campos agregados dinámicamente.
         * AGREGAR LA CLASE guarda-mayus
         */
        document.addEventListener('focusout', function (e) {
        const el = e.target;
        if (el.matches('.guarda-mayus')) {
            // toLocaleUpperCase asegura Á, É, Í, Ó, Ú, Ü, Ñ en español
            el.value = el.value.toLocaleUpperCase('es-MX'); 
        }
        });
    </script>

    <script>
        /**
         * Convierte a minúsculas cuando el input pierde el foco.
         * Incluye caracteres acentuados y eñes (locale ES-MX).
         */
        document.addEventListener('focusout', function (e) {
        const el = e.target;
        if (el.matches('.guarda-minus')) {
            el.value = el.value.toLocaleLowerCase('es-MX');
        }
        });
    </script>

    <script>
        /**
         * Convierte a “Title Case” en ES-MX, omitiendo artículos y preposiciones
         * comunes salvo si van en la primera palabra.
         */
        document.addEventListener('focusout', function (e) {
        const el = e.target;
        if (!el.matches('.guarda-titulo')) return;

        // Lista extensible de palabras que NO se capitalizan
        const excluye = [
            'a','al','de','del','la','las','lo','los','un','una','unos','unas',
            'y','e','o','u','ni','para','por','con','sin','en','sobre','entre',
            'hacia','hasta'
        ];

        const resultado = el.value
            .toLocaleLowerCase('es-MX')            // normaliza minúsculas y tildes
            .split(/\s+/)                          // separa por uno o más espacios
            .map((palabra, i) => {
            // Mantén minúscula si es artículo/prep. *y* no es la primera palabra
            if (i !== 0 && excluye.includes(palabra)) return palabra;
            // Capitaliza primera letra (soporta tildes, ü, ñ, etc.)
            return palabra.charAt(0).toLocaleUpperCase('es-MX') + palabra.slice(1);
            })
            .join(' ');

        el.value = resultado;
        });
    </script>

    
    <script>
    $('#ejecutivos').select2({
    theme: 'bootstrap-5',
    placeholder: 'Seleccione uno o varios ejecutivos',
    width: '100%',
    closeOnSelect: false
});

    </script>



    @stack('scripts')
</body>

</html>