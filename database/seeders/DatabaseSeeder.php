<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejecutar los seeders en orden correcto
        $this->call([
            PermissionsTableSeeder::class,
            RolesTableSeeder::class,
            ActualizarRoleNamesSeeder::class,
            RolePermissionSeeder::class, // Este será el que asigne los permisos a cada rol
            EstadosFromColoniasSeeder::class,
            CiudadesFromColoniasSeeder::class,
            EquiposSeeder::class,
            EquipoUsuarioSeeder::class,
            ConsecutivosSeeder::class,

        ]);
    }
}
