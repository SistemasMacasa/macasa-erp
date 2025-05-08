<div class="card mb-4">
    <div class="card-header">
        Datos de contacto
    </div>
    <div class="card-body">
        <p><strong>Estatus:</strong> {{ $cliente->estatus }}</p>
        <p><strong>Ciclo de venta:</strong> {{ $cliente->ciclo_venta }}</p>
        <p><strong>Sector:</strong> {{ $cliente->sector }}</p>
        <p><strong>Segmento:</strong> {{ $cliente->segmento }}</p>
        <p><strong>Nombre de la cuenta:</strong> 
            @if($cliente->tipo == 'personal')
                {{ $cliente->nombre }} {{ $cliente->apellido_p }} {{ $cliente->apellido_m }}
            @else
                {{ $cliente->nombre_cuenta }}
            @endif
        </p>
        <p><strong>Asignado a:</strong> {{ optional($cliente->asignadoA)->name }}</p>

        <hr>
        <span class="fw-bold">Datos de contacto</span>
        <p><strong>Nombre:</strong> {{ $cliente->contacto->nombre }}</p>
        <p><strong>Apellido Paterno:</strong> {{ $cliente->contacto->apellido_p }}</p>
        <p><strong>Apellido Materno:</strong> {{ $cliente->contacto->apellido_m }}</p>
        <p><strong>Correo:</strong> {{ $cliente->contacto->correo }}</p>
        <p><strong>Puesto:</strong> {{ $cliente->contacto->puesto }}</p>
        <p><strong>Género:</strong> {{ $cliente->contacto->genero }}</p>

        @for($i = 1; $i <= 5; $i++)
            <p><strong>Teléfono {{ $i }}:</strong> {{ $cliente->contacto->{'telefono'.$i} }}</p>
            <p><strong>Extensión {{ $i }}:</strong> {{ $cliente->contacto->{'ext'.$i} }}</p>
            <p><strong>Celular {{ $i }}:</strong> {{ $cliente->contacto->{'celular'.$i} }}</p>
        @endfor
    </div>
</div>
