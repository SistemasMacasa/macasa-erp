@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Traspaso de cuentas</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('clientes.transfer.store') }}" method="POST">
            @csrf

            <div class="row">
                {{-- Catálogo de cuentas (origen) --}}
                <div class="col-md-5">
                    <h5>Catálogo de cuentas</h5>
                    <select id="listOrigen" class="form-control" size="12">
                        @foreach($clientes as $c)
                            <option value="{{ $c->id_cliente }}">
                                {{ $c->nombre }} — {{ $c->email }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Botones mover --}}
                <div class="col-md-2 text-center d-flex flex-column justify-content-center">
                    <button type="button" id="btnAgregar" class="btn btn-success mb-2">&gt;</button>
                    <button type="button" id="btnQuitar" class="btn btn-success">&lt;</button>
                </div>

                {{-- Lista destino + selector de vendedor destino --}}
                <div class="col-md-5">
                    <h5>Asignar a</h5>
                    <select id="listDestino" name="clientes[]" multiple class="form-control" size="12">
                        {{-- aquí irán las opciones movidas --}}
                    </select>

                    <div class="mt-3">
                        <label for="destino">Agente de destino</label>
                        <select name="destino" id="destino" class="form-control">
                            <option value="">-- Selecciona un ejecutivo --</option>
                            @foreach($vendedores as $v)
                                <option value="{{ $v->id }}">{{ $v->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-4">Transferir seleccionados</button>
        </form>
    </div>

    @push('scripts')
        <script>
            // JS básico para mover <option> entre selects
            const origen = document.getElementById('listOrigen');
            const destino = document.getElementById('listDestino');
            document.getElementById('btnAgregar').onclick = () => {
                Array.from(origen.selectedOptions).forEach(opt => {
                    destino.appendChild(opt);
                    opt.selected = true; // para que se envíe en el form
                });
            };
            document.getElementById('btnQuitar').onclick = () => {
                Array.from(destino.selectedOptions).forEach(opt => {
                    origen.appendChild(opt);
                    opt.selected = false;
                });
            };
        </script>
    @endpush
@endsection