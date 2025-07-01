<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Usuario;
use Illuminate\Http\Request;

class EquipoController extends Controller
{

    public function index()
    {
        $equipos = Equipo::with(['lider', 'usuarios'])->get();
        $usuarios = Usuario::all();
        // dd($equipos);
        return view('Equipos.index', compact('equipos', 'usuarios'));
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
        ]);

        // Crear el equipo
        $equipo = Equipo::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'lider_id' => $request->lider_id,
        ]);

        // Relacionar líder como miembro con rol 'lider'
        $equipo->usuarios()->attach($request->lider_id, ['rol' => 'lider']);

        // Relacionar otros miembros con rol 'miembro'
        if ($request->filled('usuarios')) {
            foreach ($request->usuarios as $usuario_id) {
                // Evitar duplicar al líder si también está seleccionado
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

        $equipo->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'lider_id' => $request->lider_id,
        ]);

        // Prepara la lista de miembros para sync
        $usuariosSync = [];

        // Si tiene líder seleccionado, agregamos con rol 'lider'
        if ($request->lider_id) {
            $usuariosSync[$request->lider_id] = ['rol' => 'lider'];
        }

        // Si tiene otros usuarios seleccionados
        if ($request->filled('usuarios')) {
            foreach ($request->usuarios as $usuario_id) {
                // Evitar duplicar al líder si también está como miembro
                if ($usuario_id != $request->lider_id) {
                    $usuariosSync[$usuario_id] = ['rol' => 'miembro'];
                }
            }
        }

        // Sincronizamos todos los miembros y roles
        $equipo->usuarios()->sync($usuariosSync);

        return redirect()->route('equipos.index')->with('success', 'Equipo actualizado correctamente.');
    }


    public function destroy($id)
    {
        $equipo = Equipo::findOrFail($id);
        $equipo->delete();

        return redirect()->route('equipos.index')
            ->with('success', 'Equipo eliminado correctamente.');
    }
    public function datos($id)
    {
        $equipo = Equipo::with('usuarios')->findOrFail($id);

        return response()->json([
            'id'          => $equipo->id,
            'nombre'      => $equipo->nombre,
            'descripcion' => $equipo->descripcion,
            'lider_id'    => $equipo->lider_id,
            'usuarios'    => $equipo->usuarios->pluck('id_usuario')->toArray(),
        ]);
    }
}
