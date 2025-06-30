<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class ActualizarRoleNamesSeeder extends Seeder
{
    public function run(): void
    {
        $renames = [
            'Direccion'     => 'Administrador',
            'Sistemas'      => 'Desarrollador',
            'Marketing'     => 'Marketing',
            'Ventas'        => 'Ejecutivo',
        ];

        foreach ($renames as $newName => $oldName) {
            $role = Role::where('name', $oldName)->first();

            if ($role) {
                $role->name = $newName;
                $role->save();
            }
        }
    }
}
