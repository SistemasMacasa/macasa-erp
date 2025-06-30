<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'Desarrollador',
            'Administrador', //direccion
            'Administracion',
            'Ejecutivo',    //ventas
            'Compras',
            'Gerente',      //
            'Supervisor',
            'Marketing',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}
