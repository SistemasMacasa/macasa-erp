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

}
