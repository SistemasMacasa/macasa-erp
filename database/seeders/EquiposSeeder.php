<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquiposSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('equipos')->insert([
            [
                'nombre' => 'Equipo Alpha',
                'descripcion' => 'Equipo de prueba número 1',
                'lider_id' => 4, // usuario prueba4
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Equipo Beta',
                'descripcion' => 'Equipo de prueba número 2',
                'lider_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Equipo Gamma',
                'descripcion' => 'Equipo de prueba número 3',
                'lider_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Equipo Delta',
                'descripcion' => 'Equipo de prueba número 4',
                'lider_id' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Equipo Omega',
                'descripcion' => 'Equipo de prueba número 5',
                'lider_id' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
