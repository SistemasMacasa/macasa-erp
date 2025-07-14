<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Usuario;
use App\Models\Sucursal;
use Illuminate\Http\Request;

class EquipoController extends Controller
{

    public function index()
    {
        $equipos = Equipo::with(['lider', 'usuarios'])->get();
        $usuarios = Usuario::all();
        $sucursales = Sucursal::all();

        return view('equipos.index', compact('equipos', 'usuarios', 'sucursales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'lider_id' => 'required|exists:usuarios,id_usuario',
            'usuarios' => 'array',
            'usuarios.*' => 'exists:usuarios,id_usuario',
            'id_sucursal' => 'required|exists:sucursales,id_sucursal', // Asegúrate que este campo venga del form
        ]);

        $lider = Usuario::find($request->lider_id);

        if ($lider->id_sucursal != $request->id_sucursal) {
            return redirect()->back()->withErrors(['lider_id' => 'El líder seleccionado no pertenece a la sucursal elegida.'])->withInput();
        }

        $equipo = Equipo::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'lider_id' => $request->lider_id,
            'id_sucursal' => $request->id_sucursal,
        ]);

        $equipo->usuarios()->attach($request->lider_id, ['rol' => 'lider']);

        if ($request->filled('usuarios')) {
            foreach ($request->usuarios as $usuario_id) {
                if ($usuario_id != $request->lider_id) {
                    $equipo->usuarios()->attach($usuario_id, ['rol' => 'miembro']);
                }
            }
        }

        return redirect()->route('equipos.index')->with('success', 'Equipo creado correctamente.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Equipo $equipo)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'lider_id' => 'nullable|exists:usuarios,id_usuario',
            'usuarios' => 'array',
            'usuarios.*' => 'exists:usuarios,id_usuario',
        ]);

        if ($request->filled('lider_id')) {
            $lider = Usuario::find($request->lider_id);

            if ($lider->id_sucursal != $equipo->id_sucursal) {
                return redirect()->back()->withErrors(['lider_id' => 'El líder seleccionado no pertenece a la sucursal del equipo.'])->withInput();
            }
        }

        $equipo->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'lider_id' => $request->lider_id,
        ]);

        $usuariosSync = [];

        if ($request->filled('lider_id')) {
            $usuariosSync[$request->lider_id] = ['rol' => 'lider'];
        }

        if ($request->filled('usuarios')) {
            foreach ($request->usuarios as $usuario_id) {
                if ($usuario_id != $request->lider_id) {
                    $usuariosSync[$usuario_id] = ['rol' => 'miembro'];
                }
            }
        }

        $equipo->usuarios()->sync($usuariosSync);

        return redirect()->route('equipos.index')->with('success', 'Equipo actualizado correctamente.');
    }



    public function destroy($id_equipo)
    {
        $equipo = Equipo::findOrFail($id_equipo);
        $equipo->delete();

        return redirect()->route('equipos.index')
            ->with('success', 'Equipo eliminado correctamente.');
    }
    public function datos($id_equipo)
    {
        $equipo = Equipo::with('usuarios')->findOrFail($id_equipo);

        return response()->json([
            'id'          => $equipo->id_equipo,
            'nombre'      => $equipo->nombre,
            'descripcion' => $equipo->descripcion,
            'lider_id'    => $equipo->lider_id,
            'usuarios'    => $equipo->usuarios->pluck('id_usuario')->toArray(),
        ]);
    }
}
