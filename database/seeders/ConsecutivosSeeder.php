<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Consecutivo;

class ConsecutivosSeeder extends Seeder
{
    public function run(): void
    {
        Consecutivo::firstOrCreate(
            ['tipo' => 'cotizaciones'],
            ['prefijo' => 'MC2', 'valor_actual' => 0]
        );
    }
}
