@extends('layouts.app') {{-- o el layout base que uses --}}

@section('title', 'SIS 3.0 | Monitor de Cotizaciones')

@section('content')
<div class="container">
    <h1 class="mb-4">Cotizaciones</h1>
    {{-- Aquí se listarán las cotizaciones --}}
    <div class="card shadow">
        <div class="card-body">
            <p class="text-muted">Aquí aparecerán las cotizaciones registradas. Usa el botón "Nueva cotización" para comenzar una.</p>
            <a href="#" class="btn btn-primary">+ Nueva cotización</a>
        </div>
    </div>
</div>
@endsection
