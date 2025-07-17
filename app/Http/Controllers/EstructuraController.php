<?php

namespace App\Http\Controllers;

use App\Models\Segmento;
use App\Models\Sucursal;
use Illuminate\Http\Request;

class EstructuraController extends Controller
{
    public function index()
    {
        $segmentos = Segmento::with([
            'sucursales.equipos.usuarios',
            'sucursales.equipos.lider',
        ])->get();

        return view('estructura.index', compact('segmentos'));
    }

    // 🔽 SEGMENTOS
    public function storeSegmento(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
        ]);
        Segmento::create(['nombre' => $request->nombre]);
        return back()->with('success', 'Segmento creado correctamente.');
    }

    public function updateSegmento(Request $request, Segmento $segmento)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
        ]);
        $segmento->update(['nombre' => $request->nombre]);
        return back()->with('success', 'Segmento actualizado correctamente.');
    }

    public function destroySegmento(Segmento $segmento)
    {
        $segmento->delete();
        return back()->with('success', 'Segmento eliminado correctamente.');
    }

    // 🔽 SUCURSALES
    public function storeSucursal(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'id_segmento' => 'required|exists:segmentos,id_segmento',
        ]);
        Sucursal::create($request->only('nombre', 'id_segmento'));
        return back()->with('success', 'Sucursal creada correctamente.');
    }

    public function updateSucursal(Request $request, Sucursal $sucursal)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
        ]);
        $sucursal->update(['nombre' => $request->nombre]);
        return back()->with('success', 'Sucursal actualizada correctamente.');
    }

    public function destroySucursal(Sucursal $sucursal)
    {
        $sucursal->delete();
        return back()->with('success', 'Sucursal eliminada correctamente.');
    }
}
