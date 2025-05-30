<?php

use App\Models\Nota;

if (!function_exists('registrarNota')) {
    function registrarNota($id_cliente, $contenido, $etapa = null, $fecha_reprogramacion = null)
    {
        return Nota::create([
            'id_cliente'           => $id_cliente,
            'id_usuario'           => auth()->id(),  // solo si estás en contexto web
            'etapa'                => $etapa,
            'contenido'            => trim($contenido),
            'fecha_registro'       => now(),
            'fecha_reprogramacion' => $fecha_reprogramacion,
        ]);
    }
}
