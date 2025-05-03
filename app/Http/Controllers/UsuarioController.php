<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{

    public function index()
    {
        return view('usuarios.index', [
            'usuarios' => Usuario::all(),
        ]);
    }

    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|max:100',
            'email' => 'nullable|email|max:100',
        ]);

        $datos = [
            'username' => $request->username,
            'email' => $request->email,
            'cargo' => $request->cargo,
            'tipo' => 'erp',
            'estatus' => 'activo',
            'es_admin' => $request->has('es_admin') ? 1 : 0,
            'fecha_alta' => now() // alternativa moderna a date()
        ];

        try {
            Usuario::create($datos);
            return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function show(Usuario $usuario)
    {
        //
    }

    public function edit($id)
    {
        $usuario = Usuario::find($id);
        return view('usuarios.edit', compact('usuario'));

    }

    public function update(Request $request, Usuario $usuario)
    {
        $datos = $request->only([
            'username',
            'email',
            'cargo',
            'estatus',
            'tipo',
            'es_admin'
        ]);

        if ($request->filled('password')) {
            $datos['password'] = bcrypt($request->password);
        }

        // Se tiene que preparar $datos a causa de 'es_admin' que manda 'on' en vez de 1
        $request->validate([
            'username' => 'required|max:100',
            'email' => 'nullable|email|max:100',
            'cargo' => 'required|max:100',
            'estatus' => 'required|in:Activo,Archivado',
        ]);


        $datos['username'] = $request->username ?? $usuario->username;
        $datos['email'] = $request->email ?? $usuario->email;
        $datos['cargo'] = $request->cargo ?? $usuario->cargo;
        $datos['estatus'] = $request->estatus ?? $usuario->estatus;
        $datos['es_admin'] = $request->has('es_admin') ? 1 : 0;


        $usuario->update($datos);
        return redirect()->route('usuarios.index')->with('success', 'Se editó el usuario exitosamente');
    }

    public function destroy(Usuario $usuario)
    {
        $usuario->delete();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario eliminado exitosamente');
    }

}
