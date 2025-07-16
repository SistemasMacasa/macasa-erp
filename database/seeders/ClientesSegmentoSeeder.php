<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientesSegmentoSeeder extends Seeder
{
    public function run()
    {
        // Obtenemos los segmentos y sus ids
        $segmentos = DB::table('segmentos')->pluck('id_segmento', 'nombre')->toArray();

        foreach ($segmentos as $nombre => $idSegmento) {
            DB::table('clientes')
                ->where('segmento', $nombre)
                ->update(['id_segmento' => $idSegmento]);
        }
    }
}
