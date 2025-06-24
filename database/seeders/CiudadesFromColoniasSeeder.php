<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CiudadesFromColoniasSeeder extends Seeder
{
public function run()
{
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    DB::table('ciudades')->truncate();
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    $municipios = DB::table('colonias')
        ->select('c_estado', 'c_mnpio', 'd_mnpio')
        ->distinct()
        ->orderBy('c_estado')
        ->orderBy('c_mnpio')
        ->get();

    foreach ($municipios as $municipio) {
        DB::table('ciudades')->insert([
            'c_estado' => $municipio->c_estado,
            'c_mnpio' => $municipio->c_mnpio,
            'n_mnpio' => $municipio->d_mnpio,
        ]);
    }
}

}
