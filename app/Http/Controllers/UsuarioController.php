<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Foundation\Providers\FoundationServiceProvider;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{

    public function index()
    {
        return view('usuarios.index', [
            'usuarios' => Usuario::activos()->get(),
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

        try {
            if ($usuario->update($datos)) {
                return redirect()->route('usuarios.index')->with('success', 'Se editó el usuario exitosamente');
            } else {
                return redirect()->back()->with('error', 'No se pudo actualizar el usuario.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al editar el usuario: ' . $e->getMessage());
        }
    }

    public function archivar($id)
    {
        $usuario = Usuario::findOrFail($id);

        if ($usuario->estaArchivado()) {
            return redirect()->back()->with('info', 'El usuario ya está archivado.');
        }

        $usuario->archivar();

        return redirect()->route('usuarios.index')->with('success', 'Usuario archivado correctamente.');
    }
    public function archivados(){
        return view('usuarios.archivados',[
            'usuarios' => Usuario::archivados()->get(),
        ]);
    }

    public function desarchivar($id)
    {
        $usuario = Usuario::findOrFail($id);

        if ($usuario->estaActivo()) {
            return redirect()->back()->with('info', 'El usuario ya está activo.');
        }

        $usuario->desarchivar();

        return redirect()->route('usuarios.index')->with('success', 'Usuario reactivado correctamente.');
    }
}
