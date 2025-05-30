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
                <div class="container-fluid" style="margin-left: 0ch; max-width: 193ch;">
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

        <!-- toggle del sidebar-->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('sidebar-toggle');
        btn?.addEventListener('click', () => {
            const size = document.body.getAttribute('data-sidebar-size') || 'lg';
            const next = size === 'lg' ? 'sm' : 'lg';
            document.body.setAttribute('data-sidebar-size', next);
            localStorage.setItem('sidebarSize', next);
            /* el CSS se encarga de girar el icono */
        });
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
        function ajustarPaddingTopbar() {
            const topbar = document.getElementById('topbar-fluid');
            const size = document.body.getAttribute('data-sidebar-size') || 'lg';
            if (!topbar) return;

            if (size === 'sm') {
                topbar.style.paddingLeft = '100px'; // puedes ajustar según lo que visualmente te guste más
            } else {
                topbar.style.paddingLeft = ''; // reset para volver al valor de Bootstrap
            }
        }

        // Ejecutar en carga inicial
        document.addEventListener("DOMContentLoaded", ajustarPaddingTopbar);

        // Ejecutar después de hacer toggle
        document.getElementById("vertical-menu-btn")?.addEventListener("click", () => {
            setTimeout(ajustarPaddingTopbar, 100); // esperamos 100ms por si hay animaciones
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
        //Fomateo de telefonos con clase phone-field
        document.addEventListener('DOMContentLoaded', () => {

        const formatPhone = (el) => {
            // 1) Leer contenido bruto
            let raw = ('value' in el)
            ? el.value
            : el.textContent;
            let digits = raw.replace(/\D/g, '');
            if (digits.length > 10) digits = digits.slice(0, 10);

            // 2) Dar formato “pretty”
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

            // 3) Escribir contenido formateado
            if ('value' in el) {
                el.value = pretty;
                el.maxLength = pretty.startsWith('(55)') ? 14 : 15;
                el.setCustomValidity(
                    (digits.length === 0 || digits.length === 10) ? '' : 'Número incompleto'
                );           
            } else {
            el.textContent = pretty;
            // Si es enlace, actualizar href a tel:
            if (el.tagName === 'A') {
                el.href = 'tel:' + digits;
            }
            }
        };

        // Formateo inicial de todos los .phone-field
        document.querySelectorAll('.phone-field').forEach(formatPhone);

        // Si editas inputs en tiempo real
        document.addEventListener('input', e => {
            const el = e.target.closest('.phone-field');
            if (el && 'value' in el) formatPhone(el);
        });

        });
    </script>


    <script>
        /**
         * Convierte el valor a MAYÚSCULAS **sin acentos** cuando el input pierde el foco.
         * Úsalo en cualquier <input> o <textarea> con la clase guarda-mayus.
         */
        document.addEventListener('focusout', e => {
            const el = e.target;
            if (!el.matches('.guarda-mayus')) return;

            /* 1) Normaliza: é -> e + ́   2) Quita diacríticos   3) Uppercase */
            el.value = el.value
                .normalize('NFD')                      // é → e + ́
                .replace(/[\u0300-\u036f]/g, '')       // fuera tildes/diéresis
                .toLocaleUpperCase('es-MX');           // → MAYÚSCULAS SIN ACENTOS
        });
    </script>

    <script>
        /**
         * Convierte a minúsculas SIN acentos cuando el input pierde el foco.
         * Aplícalo a cualquier input/textarea con la clase guarda-minus.
         */
        document.addEventListener('focusout', e => {
            const el = e.target;
            if (!el.matches('.guarda-minus')) return;

            /* 1) Descompone signos diacríticos   2) Los quita   3) a minúsculas */
            el.value = el.value
                .normalize('NFD')                      // Á → A + ́
                .replace(/[\u0300-\u036f]/g, '')       // elimina tildes/diéresis
                .toLocaleLowerCase('es-MX');           // → minusculas sin acento
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