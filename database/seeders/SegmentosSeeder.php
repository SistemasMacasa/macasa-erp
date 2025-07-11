<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SegmentosSeeder extends Seeder
{
    public function run()
    {
        $segmentos = [
            ['id_segmento' => 1, 'nombre' => 'Macasa cuentas especiales'],
            ['id_segmento' => 2, 'nombre' => 'Tekne store ecommerce'],
            ['id_segmento' => 3, 'nombre' => 'La plaza ecommerce'],
        ];

        foreach ($segmentos as $segmento) {
            DB::table('segmentos')->updateOrInsert(
                ['id_segmento' => $segmento['id_segmento']],
                ['nombre' => $segmento['nombre']]
            );
        }
    }
}
