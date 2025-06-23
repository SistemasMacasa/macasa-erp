<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'Nueva Cuenta',
            'Mis Cuentas',
            'Ver Cuenta',
            'Levantar Cotizacion',
            'Libreta de Contactos',
            'Mis Recalls',
            'Cuentas Archivadas',
            'Monitor de Cotizaciones',
            'Monitor de Ventas',
            'Metas de Venta',
            'Monitor de Cobranza',
            'E Commerce',
            'Marketing',
            'Mis Proveedores',
            'Usuarios de SIS',
            'Asistencia',
            'Permisos',
            'Editar Cuenta',
            'Traspaso de Cuenta',
            'Crear Nuevos Permisos',
            'Archivar Cuenta',
            'Crear Direcciones',
            'Eliminar Permisos',
            'Asignar Permisos',
            'Desasignar Permisos',
            'Asignar Rol',
            'Desasignar Rol',
            'Crear Rol',
            'Eliminar Rol',
            'Crear Usuario',
            'Editar Usuario',
            'Eliminar Usuario',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
