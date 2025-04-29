<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>SIS 3.0 | Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/preloader.min.css') }}" />
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" />

</head>

<body>
    <div class="auth-page">
        <div class="container-fluid p-0">
            <div class="row g-0">
                <!-- FORMULARIO -->
                <div class="col-xxl-3 col-lg-4 col-md-5">
                    <div class="auth-full-page-content d-flex p-sm-5 p-4">
                        <div class="w-100">
                            <div class="d-flex flex-column h-100">
                                <div class="mb-4 mb-md-5 text-center">
                                    <a href="{{ url('/') }}" class="d-block auth-logo">
                                        <img src="{{ asset('assets/images/imglogin/logo.png') }}" alt="MACASA"
                                            class="img-fluid" style="max-height: 50px;">
                                    </a>



                                </div>

                                <div class="auth-content my-auto">
                                    <div class="text-center">
                                        <h5 class="mb-0">Bienvenido de nuevo</h5>
                                        <p class="text-muted mt-2">Inicia sesión con tu cuenta ejecutiva.</p>
                                    </div>

                                    @if ($errors->any())
                                        <div class="alert alert-danger mt-3">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <form class="mt-4 pt-2" method="POST" action="{{ route('login.attempt') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Usuario</label>
                                            <input type="text" class="form-control" id="username" name="username"
                                                value="{{ old('username') }}" required autofocus>
                                        </div>

                                        <div class="mb-3">
                                            <div class="d-flex align-items-start">
                                                <div class="flex-grow-1">
                                                    <label for="password" class="form-label">Contraseña</label>
                                                </div>
                                            </div>

                                            <div class="input-group auth-pass-inputgroup">
                                                <input type="password" class="form-control" name="password"
                                                    id="password" placeholder="Contraseña" required>
                                                <button class="btn btn-light shadow-none ms-0" type="button"
                                                    id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="remember">
                                                    <label class="form-check-label" for="remember">
                                                        Recuérdame
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <button class="btn btn-primary w-100 waves-effect waves-light"
                                                type="submit">Iniciar Sesión</button>
                                        </div>
                                    </form>

                                    <div class="mt-5 text-center">
                                        <p class="text-muted mb-0">¿Olvidaste tu contraseña? </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- LADO DERECHO -->
                @php
                    $random = rand(1, 26);
                @endphp

                <div class="col-xxl-9 col-lg-8 col-md-7">
                    <div class="auth-bg pt-md-5 p-4 d-flex position-relative"
                        style="background: url('{{ asset("assets/images/imglogin/$random.jpg") }}') center center / cover no-repeat;">

                        <!-- Difuminado más transparente (antes era bg-primary) -->
                        <div class="bg-overlay"
                            style="background-color: rgba(0, 0, 0, 0.45); position: absolute; inset: 0;"></div>

                        <!-- Animaciones burbuja originales -->
                        <ul class="bg-bubbles">
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                        </ul>

                        <div class="row justify-content-center align-items-center w-100 z-1 position-relative">
                            <div class="col-xl-7">
                                <div class="p-0 p-sm-4 px-xl-0">
                                    <!-- puedes dejar contenido si lo necesitas -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FIN COL -->
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/waves.min.js') }}"></script>
    <script src="{{ asset('assets/js/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/pace.min.js') }}"></script>
    <script src="{{ asset('assets/js/pass-addon.init.js') }}"></script>
</body>

</html>