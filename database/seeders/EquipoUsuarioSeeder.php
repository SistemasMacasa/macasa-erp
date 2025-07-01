<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquipoUsuarioSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('equipo_usuario')->insert([
            // Equipo 1
            [
                'equipo_id' => 1,
                'usuario_id' => 4, // usuario4
                'rol' => 'lider',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'equipo_id' => 1,
                'usuario_id' => 5, // usuario5
                'rol' => 'miembro',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'equipo_id' => 1,
                'usuario_id' => 6, // usuario6
                'rol' => 'miembro',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Equipo 2
            [
                'equipo_id' => 2,
                'usuario_id' => 7, // usuario7
                'rol' => 'lider',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'equipo_id' => 2,
                'usuario_id' => 8,
                'rol' => 'miembro',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'equipo_id' => 2,
                'usuario_id' => 9,
                'rol' => 'miembro',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'equipo_id' => 2,
                'usuario_id' => 10,
                'rol' => 'miembro',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Equipo 3
            [
                'equipo_id' => 3,
                'usuario_id' => 10, // usuario10
                'rol' => 'lider',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'equipo_id' => 3,
                'usuario_id' => 11,
                'rol' => 'miembro',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'equipo_id' => 3,
                'usuario_id' => 12,
                'rol' => 'miembro',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'equipo_id' => 3,
                'usuario_id' => 13,
                'rol' => 'miembro',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
