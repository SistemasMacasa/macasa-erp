@extends('layouts.app')

@push('scripts')
@endpush


@section('title', 'SIS 3.0 | Inicio')

@section('content')

<!-- Dashboard.blade.php -->
    <div class="row">
        <!-- KPIs superiores -->
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="text-normal">Cuentas Asignadas</h6>
                    <h3 class="color-fuente">3</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="text-normal">Pendientes para Hoy</h6>
                    <h3 class="color-fuente">5</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="text-normal">Alcance de Cotización</h6>
                    <h3 class="text-primary">$250,000</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="text-normal">Alcance de Venta</h6>
                    <h3 class="text-success">$37,490</h3>
                </div>
            </div>
        </div>
    </div>

<!-- Comunicados -->
<div class="card mt-4">
    <div class="card-body position-relative p-4">
        <h5 class="text-center text-primary mb-4 fw-bold text-subtitulo">Comunicados</h5>

        <div id="comunicadosCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">

                <div class="carousel-item active">
                    <p class="text-center text-dark text-subtitulo">
                        En un flujo de desarrollo: preguntamos cuando ignoramos (“duda”) 
                        y buscamos confirmar información cuando el costo de asumir algo 
                        incorrecto es alto (“confirmación”).
                     </p>
                </div>


            </div>

            <!-- Controles -->
            <button class="carousel-control-prev" type="button" data-bs-target="#comunicadosCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon bg-dark rounded-circle" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#comunicadosCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon bg-dark rounded-circle" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>

           
        </div>
    </div>
</div>
    <div class="row">
        <h4>¿Cómo vamos?</h4>
    </div>

    <!-- Graficas -->
    <div class="row">
        <div class="col-md-6">
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="mb-3 text-subtitulo">Alcance de Cotizaciones por Mes</h5>
                    <div id="radialBarBottom"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="mb-3 text-subtitulo">Cotizaciones y Ventas Mensuales</h5>
                    <div id="lineChart"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Barras de progreso -->
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="mb-3text-subtitulo">Alcances</h5>
            <div class="mb-4">
                <span class="d-block text-normal">De Cotización</span>
                <div id="progress1"></div>
            </div>
            <div class="mb-4">
                <span class="d-block text-normal">De Venta</span>
                <div id="progress2"></div>
            </div>
        </div>
    </div>

    <!-- Mejores Cotizaciones y Ventas -->
    <div class="row">
        <div class="col-md-6">
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="mb-3 text-subtitulo">Mis Mejores Cotizaciones</h5>
                    <!-- Contenido de tabla o tarjetas -->
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="mb-3 text-subtitulo">Mis Mejores Ventas</h5>
                    <!-- Contenido de tabla o tarjetas -->
                </div>
            </div>
        </div>
    </div>

@push('scripts')

@endpush

@endsection
