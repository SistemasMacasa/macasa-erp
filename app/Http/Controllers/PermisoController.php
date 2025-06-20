<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermisoController extends Controller
{
    public function index(Request $request)
    {
        // Filtro por ejecutivo
        $query = Usuario::query();

        if ($request->filled('ejecutivo')) {
            $query->where('id_usuario', $request->input('ejecutivo'));
        }

        $usuarios = $query->with(['roles', 'permissions'])->get();
        //Role y Permission son modelos de Spatie\Permission
        //están en config/permission.php
        //Spatie\Permission\Models\Role;
        //Spatie\Permission\Models\Permission;
        $roles    = Role::all();
        $permisos = Permission::orderBy('name')->get();

        return view('permisos.index', compact('usuarios', 'roles', 'permisos'));
    }

    public function asignarPermiso(Request $request)
    {
        $usuario = Usuario::findOrFail($request->input('usuario_id'));
        $permiso = $request->input('permiso');

        if (!$usuario->hasPermissionTo($permiso)) {
            $usuario->givePermissionTo($permiso);
        }

        return response()->json(['ok' => true,         
                                       'permisos'  => $usuario->getDirectPermissions()->pluck('name'),
                                      ]);
    }

    public function removerPermiso(Request $request)
    {
        $usuario = Usuario::findOrFail($request->input('usuario_id'));
        $permiso = $request->input('permiso');

        if ($usuario->hasPermissionTo($permiso)) {
            $usuario->revokePermissionTo($permiso);
        }

        return response()->json(['ok' => true,
                                       'permisos'  => $usuario->getDirectPermissions()->pluck('name'),
                                      ]);
    }

    // GET  /api/permisos-usuario/{id}
    public function permisosUsuario($id)
    {
        $u = Usuario::findOrFail($id);
        return [
            'permisos' => $u->getDirectPermissions()->pluck('name'),
            'heredados'=> $u->getPermissionsViaRoles()->pluck('name'),
        ];
    }

    //  GET /api/roles-usuario/{id}
    public function rolesUsuario($id)
    {
        $u = Usuario::findOrFail($id);

        return [
            // sólo nombres directos de rol
            'roles'    => $u->getRoleNames(),  
            // catálogo completo de roles
            'catalogo' => Role::pluck('name'),
        ];
    }

    //  POST /roles/asignar
    public function asignarRol(Request $r)
    {
        $u    = Usuario::findOrFail($r->usuario_id);
        $rol  = $r->input('rol');

        if (! $u->hasRole($rol)) {
            $u->assignRole($rol);
        }

        return ['ok' => true, 'roles' => $u->getRoleNames()];
    }

    //  POST /roles/remover
    public function removerRol(Request $r)
    {
        $u   = Usuario::findOrFail($r->usuario_id);
        $rol = $r->input('rol');

        if ($u->hasRole($rol)) {
            $u->removeRole($rol);
        }

        return ['ok' => true, 'roles' => $u->getRoleNames()];
    }

     // GET /api/permisos-catalogo
    public function catalogPermisos()
    {
        $perms = Permission::withCount('roles')->get();
        return response()->json(
            $perms->map(fn($p)=>[
              'id'          => $p->id,
              'name'        => $p->name,
              'roles_count' => $p->roles_count,
            ])
        );
    }

    // POST /api/permisos-catalogo
    public function storePermiso(Request $req)
    {
        $req->validate(['name'=>'required|unique:permissions,name']);
        $p = Permission::create(['name'=>$req->name,'guard_name'=>'web']);
        return response()->json([
            'ok'=>true,
            'perm'=>[
              'id'          => $p->id,
              'name'        => $p->name,
              'roles_count' => 0
            ]
        ]);
    }

    // DELETE /api/permisos-catalogo/{permission}
    public function destroyPermisoCatalog(Permission $permission)
    {
        if ($permission->roles()->count()>0) {
            return response()->json([
               'ok'=>false,
               'message'=>"No se puede eliminar: asignado a {$permission->roles()->count()} roles"
            ],409);
        }
        $permission->delete();
        return response()->json(['ok'=>true]);
    }

    public function removerPermisoDeRol(Permission $permission, Role $role)
    {
        if ($role->hasPermissionTo($permission)) {
            $role->revokePermissionTo($permission);
        }

        // Devolver:
        //  roles_count actualizado para la tabla
        //  lista de roles que aún tienen este permiso
        $rolesWith = $permission->roles()->pluck('name');

        return response()->json([
            'ok'           => true,
            'roles_count'  => $permission->roles()->count(),
            'roles_with'   => $rolesWith,
        ]);
    }

    public function rolesForPermission(Permission $permission)
    {
        // Devuelve id y name de cada rol
        return $permission->roles->map(fn(Role $r)=>[
            'id'   => $r->id,
            'name' => $r->name,
        ]);
    }

}
