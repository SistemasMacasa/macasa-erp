<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'MACASA')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <link href="{{ asset('assets/css/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/preloader.min.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">

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
                    @yield('content')
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
    <script src="{{ asset('assets/js/dashboard.init.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            if (typeof feather !== 'undefined') {
                feather.replace();
            } else {
                console.error('Feather no está cargado o no está disponible.');
            }
        });
    </script>   
    @stack('scripts')
</body>
</html>
