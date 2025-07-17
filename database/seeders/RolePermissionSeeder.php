<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = Permission::all()->pluck('name', 'id');

        // Mapeo de permisos por rol
        $rolesPermissions = [
            'Sistemas' => $permissions->values()->toArray(), // todos los permisos
            'Direccion' => [
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
                'Equipos de Trabajo',
                'Crear Equipos de Trabajo',
                'Editar Equipos de Trabajo',
                'Eliminar Equipos de trabajo',
                'Editar Cotizacion',
                'Editar Pedido',
                'Emitir Pedido'
            ],
            'Ventas' => [
                'Mis Cuentas',
                'Ver Cuenta',
                'Levantar Cotizacion',
                'Libreta de Contactos',
                'Mis Recalls',
                'Cuentas Archivadas',
                'Monitor de Cotizaciones',
                'Monitor de Ventas',
                'Metas de Venta',
                'Editar Cotizacion',
                'Editar Pedido'
            ],
            // Agrega permisos base para otros roles si aplica:
            'Compras' => [],
            'Gerente' => [],
            'Supervisor' => [],
            'Administracion' => [],
            'Marketing' => [],
        ];

        foreach ($rolesPermissions as $roleName => $permissionNames) {
            $role = Role::where('name', $roleName)->first();

            if (!$role) continue;

            foreach ($permissionNames as $permissionName) {
                $permission = Permission::where('name', $permissionName)->first();
                if ($permission && !$role->hasPermissionTo($permission)) {
                    $role->givePermissionTo($permission);
                }
            }
        }
    }
}
