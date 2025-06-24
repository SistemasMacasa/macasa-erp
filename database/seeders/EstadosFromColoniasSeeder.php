<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadosFromColoniasSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('estados')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $estados = DB::table('colonias')
            ->select('c_estado', 'd_estado')
            ->distinct()
            ->orderBy('c_estado')
            ->get();

        foreach ($estados as $estado) {
            DB::table('estados')->insert([
                // No asignamos 'id_estado' porque es auto-increment
                'c_estado' => $estado->c_estado,
                'd_estado' => $estado->d_estado,
            ]);
        }
    }
}
