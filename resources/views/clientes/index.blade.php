@extends('layouts.app')
@section('title', 'SIS 3.0 | Listado de Clientes')

@section('content')
    {{-- 🧭 Migas de pan --}}
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('inicio') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Mis Cuentas</li>
    @endsection

    <h1 class="mb-4">Mis Cuentas</h1>

    {{-- 🎛 Botonera --}}
    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="{{ url()->previous() }}" class="btn btn-light">
            <i class="fa fa-arrow-left me-1"></i> Regresar
        </a>

        <a href="{{ route('inicio') }}" class="btn btn-outline-primary">
            <i class="fa fa-phone me-1"></i> Mis Recall's
        </a>

        <a href="{{ route('inicio') }}" class="btn btn-primary">
            <i class="fa fa-check me-1"></i> Enviar carta de presentación
        </a>
    </div>

    {{-- 🔎 Buscador --}}
    <div class="input-group mb-3">
        <span class="input-group-text bg-white"><i class="fa fa-search"></i></span>
        <input type="text" id="buscador-clientes" class="form-control" placeholder="Buscar cliente por nombre, correo, ID…">
    </div>

        {{-- ───────── Paginación ───────── --}}
        @if($clientes->hasPages())
    <div class="row align-items-center mb-4">
        <div class="col-sm">
        <p class="mb-0 text-muted small">
            Mostrando {{ $clientes->firstItem() }} a {{ $clientes->lastItem() }}
            de {{ $clientes->total() }} clientes
        </p>
        </div>
        <div class="col-sm-auto">
        <nav aria-label="Paginación de clientes">
            <ul class="pagination pagination-rounded pagination-sm mb-0">
            
            {{-- ← Anterior (icon only) --}}
            @if($clientes->onFirstPage())
                <li class="page-item disabled">
                <span class="page-link">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                    <span class="visually-hidden">Anterior</span>
                </span>
                </li>
            @else
                <li class="page-item">
                <a class="page-link" href="{{ $clientes->previousPageUrl() }}" rel="prev"
                    aria-label="Anterior">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                    <span class="visually-hidden">Anterior</span>
                </a>
                </li>
            @endif

            {{-- Páginas numéricas (current ±1) --}}
            @foreach ($clientes->getUrlRange(
                max(1, $clientes->currentPage() - 1),
                min($clientes->lastPage(), $clientes->currentPage() + 1)
                ) as $page => $url)
                <li class="page-item {{ $page == $clientes->currentPage() ? 'active' : '' }}">
                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach

            {{-- Siguiente → (icon only) --}}
            @if($clientes->hasMorePages())
                <li class="page-item">
                <a class="page-link" href="{{ $clientes->nextPageUrl() }}" rel="next"
                    aria-label="Siguiente">
                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                    <span class="visually-hidden">Siguiente</span>
                </a>
                </li>
            @else
                <li class="page-item disabled">
                <span class="page-link">
                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                    <span class="visually-hidden">Siguiente</span>
                </span>
                </li>
            @endif

            </ul>
        </nav>
        </div>
    </div>
    @endif
    {{-- ────────── Fin paginación ────────── --}}

    {{-- 📋 Tabla responsiva --}}
    <div class="table-responsive mb-4">
        <table id="tabla-clientes" class="table align-middle table-hover table-nowrap">
            <thead class="table-light">
                <tr>
                    <th class="w-10">Cliente ID</th>
                    <th>Cuenta (Eje)</th>
                    <th>Contacto</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Sector</th>
                    <th>Segmento</th>
                    <th>Ciclo</th>
                    <th class="text-center">…</th>
                </tr>
            </thead>
            <tbody>
                {{-- Filas de clientes --}}
                {{-- Iterar clientes --}}
                @foreach ($clientes as $c)
                    @php
                        $pc = $c->primerContacto;
                        $tel = optional($pc)->telefono1;
                        $email = optional($pc)->email;
                    @endphp
                    <tr>
                        {{-- ID con enlace al show --}}
                        <td>
                            <a href="{{ route('clientes.show', $c->id_cliente) }}"
                                class="fw-semibold text-decoration-underline">
                                {{ $c->id_cliente }}
                            </a>
                        </td>

                        <td>{{ $c->nombre }}</td>

                        {{-- Contacto principal o guion --}}
                        <td>
                            {{ optional($pc)->nombre_completo ?? '—' }}
                        </td>

                            {{-- Teléfono clic-to-call --}}
                        <td>
                            @if($tel)
                                <a href="mailto:{{ $tel }}">
                                    {{ $tel }}
                                </a>
                            @else
                                —
                            @endif
                        </td>

                        {{-- Email mailto --}}
                        <td>
                            @if ($email)
                                <a href="mailto:{{ $email }}">{{ $email }}</a>
                            @else
                                —
                            @endif
                        </td>

                        <td>{{ $c->sector ?? '—' }}</td>
                        <td>{{ $c->segmento ?? '—' }}</td>

                        {{-- Ciclo como badge --}}
                        <td>
                            <span class="badge bg-{{ $c->ciclo_venta === 'venta' ? 'success' : 'info' }}">
                                {{ $c->ciclo_venta }}
                            </span>
                        </td>

                        {{-- Acciones compactas en dropdown --}}
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-icon btn-light btn-sm" data-bs-toggle="dropdown">
                                    <i class="fa fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('clientes.edit', $c->id_cliente) }}">
                                            <i class="fa fa-edit me-2 text-warning"></i> Editar
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('clientes.destroy', $c->id_cliente) }}" method="POST"
                                            onsubmit="return confirm('¿Eliminar cliente?')">
                                            @csrf @method('DELETE')
                                            <button class="dropdown-item text-danger">
                                                <i class="fa fa-trash me-2"></i> Borrar
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div> {{-- /.table-responsive --}}

    {{-- ───────── Paginación ───────── --}}
    @if($clientes->hasPages())
    <div class="row align-items-center mb-4">
        <div class="col-sm">
        <p class="mb-0 text-muted small">
            Mostrando {{ $clientes->firstItem() }} a {{ $clientes->lastItem() }}
            de {{ $clientes->total() }} clientes
        </p>
        </div>
        <div class="col-sm-auto">
        <nav aria-label="Paginación de clientes">
            <ul class="pagination pagination-rounded pagination-sm mb-0">
            
            {{-- ← Anterior (icon only) --}}
            @if($clientes->onFirstPage())
                <li class="page-item disabled">
                <span class="page-link">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                    <span class="visually-hidden">Anterior</span>
                </span>
                </li>
            @else
                <li class="page-item">
                <a class="page-link" href="{{ $clientes->previousPageUrl() }}" rel="prev"
                    aria-label="Anterior">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                    <span class="visually-hidden">Anterior</span>
                </a>
                </li>
            @endif

            {{-- Páginas numéricas (current ±1) --}}
            @foreach ($clientes->getUrlRange(
                max(1, $clientes->currentPage() - 1),
                min($clientes->lastPage(), $clientes->currentPage() + 1)
                ) as $page => $url)
                <li class="page-item {{ $page == $clientes->currentPage() ? 'active' : '' }}">
                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach

            {{-- Siguiente → (icon only) --}}
            @if($clientes->hasMorePages())
                <li class="page-item">
                <a class="page-link" href="{{ $clientes->nextPageUrl() }}" rel="next"
                    aria-label="Siguiente">
                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                    <span class="visually-hidden">Siguiente</span>
                </a>
                </li>
            @else
                <li class="page-item disabled">
                <span class="page-link">
                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                    <span class="visually-hidden">Siguiente</span>
                </span>
                </li>
            @endif

            </ul>
        </nav>
        </div>
    </div>
    @endif
    {{-- ────────── Fin paginación ────────── --}}

    {{-- 🗂️ Modal de cliente --}}



@endsection