<?php

use App\Models\Nota;

if (!function_exists('registrarNota')) {
    function registrarNota($id_cliente, $contenido, $etapa = null, $fecha_reprogramacion = null, $es_automatico = null)
    {
        return Nota::create([
            'id_cliente'           => $id_cliente,
            'id_usuario'           => auth()->id(),  
            'etapa'                => $etapa,
            'contenido'            => trim($contenido),
            'fecha_registro'       => now(),
            'fecha_reprogramacion' => $fecha_reprogramacion,
            'es_automatico'        => $es_automatico
        ]);
    }
}
