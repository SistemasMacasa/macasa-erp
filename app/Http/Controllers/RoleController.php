<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users','permissions')->get();
        return response()->json($roles->map(fn($r)=>[
            'id'             => $r->id,
            'name'           => $r->name,
            'users_count'    => $r->users_count,
            'perms_count'    => $r->permissions_count,
        ]));
    }

    public function store(Request $req)
    {
        $req->validate(['name'=>'required|unique:roles,name']);
        $role = Role::create(['name'=>$req->name]);
        return response()->json(['ok'=>true,'role'=>['id'=>$role->id,'name'=>$role->name]]);
    }

    public function destroy(Role $role)
    {
        if ($role->users()->count()>0) {
            return response()->json(['ok'=>false,'message'=>"No se puede eliminar: tiene {$role->users()->count()} usuarios asignados"],409);
        }
        $role->delete();
        return response()->json(['ok'=>true]);
    }

    public function permisos(Role $role)
    {
        $all = Permission::pluck('name');
        $has = $role->permissions->pluck('name');
        return response()->json([
            'all' => $all,
            'has' => $has,
        ]);
    }

    public function syncPermisos(Request $req, Role $role)
    {
        $req->validate(['permisos'=>'array']);
        $role->syncPermissions($req->permisos);
        return response()->json(['ok'=>true,'perms'=>$role->permissions->pluck('name')]);
    }
}
