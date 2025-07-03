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

        // Crear metasVentas dinámicamente con datos de prueba para cada usuario de los equipos
        $metasVentas = [];
        foreach ($equipos as $equipo) {
            foreach ($equipo->usuarios as $usuario) {
                $metasVentas[] = [
                    'id_usuario' => $usuario->id_usuario, // asegúrate que sea id_usuario, no id
                    'mes_aplicacion' => '2025-07-01',
                    'cuota_cotizaciones' => rand(70000, 150000), // valores aleatorios para simular
                    'cuota_marginal_cotizaciones' => rand(20000, 50000),
                ];
            }
        }

        // Recorrer equipos para agregar los datos calculados
        foreach ($equipos as $equipo) {
            // Inicializa variables del equipo
            $equipo->cuota_cotizacion = 0;
            $equipo->alcance_cotizacion = 0; // puedes calcularlo si tienes datos de alcance
            $equipo->porcentaje_cotizacion = 0; // idem
            $equipo->cuota_margen = 0;
            $equipo->alcance_margen = 0;
            $equipo->porcentaje_margen = 0;

            foreach ($equipo->usuarios as $usuario) {
                // Busca metas para este usuario
                $meta = collect($metasVentas)->firstWhere('id_usuario', $usuario->id_usuario);

                if ($meta) {
                    // Asigna las metas a cada usuario para que la vista las use
                    $usuario->cuota_cotizacion = $meta['cuota_cotizaciones'];
                    $usuario->alcance_cotizacion = $meta['cuota_cotizaciones'] * 0.8; // ejemplo de cálculo
                    $usuario->porcentaje_cotizacion = $usuario->cuota_cotizacion ? ($usuario->alcance_cotizacion / $usuario->cuota_cotizacion) * 100 : 0;

                    $usuario->cuota_margen = $meta['cuota_marginal_cotizaciones'];
                    $usuario->alcance_margen = $meta['cuota_marginal_cotizaciones'] * 0.7; // ejemplo
                    $usuario->porcentaje_margen = $usuario->cuota_margen ? ($usuario->alcance_margen / $usuario->cuota_margen) * 100 : 0;

                    // Creamos el array metas para la vista
                    $usuario->metas = [
                        'cuota_cotizacion' => $usuario->cuota_cotizacion,
                        'alcance_cotizacion' => $usuario->alcance_cotizacion,
                        'porcentaje_cotizacion' => $usuario->porcentaje_cotizacion,
                        'cuota_margen' => $usuario->cuota_margen,
                        'alcance_margen' => $usuario->alcance_margen,
                        'porcentaje_margen' => $usuario->porcentaje_margen,
                    ];

                    // Sumamos al equipo
                    $equipo->cuota_cotizacion += $usuario->cuota_cotizacion;
                    $equipo->alcance_cotizacion += $usuario->alcance_cotizacion;
                    $equipo->cuota_margen += $usuario->cuota_margen;
                    $equipo->alcance_margen += $usuario->alcance_margen;
                } else {
                    // Por si acaso no hay meta (no debería pasar)
                    $usuario->cuota_cotizacion = 0;
                    $usuario->alcance_cotizacion = 0;
                    $usuario->porcentaje_cotizacion = 0;
                    $usuario->cuota_margen = 0;
                    $usuario->alcance_margen = 0;
                    $usuario->porcentaje_margen = 0;

                    $usuario->metas = [
                        'cuota_cotizacion' => 0,
                        'alcance_cotizacion' => 0,
                        'porcentaje_cotizacion' => 0,
                        'cuota_margen' => 0,
                        'alcance_margen' => 0,
                        'porcentaje_margen' => 0,
                    ];
                }
            }

            // Calcular porcentaje total del equipo (evitar división por cero)
            $equipo->porcentaje_cotizacion = $equipo->cuota_cotizacion ? ($equipo->alcance_cotizacion / $equipo->cuota_cotizacion) * 100 : 0;
            $equipo->porcentaje_margen = $equipo->cuota_margen ? ($equipo->alcance_margen / $equipo->cuota_margen) * 100 : 0;
        }

        return view('Equipos.index', compact('equipos', 'usuarios', 'metasVentas'));
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
