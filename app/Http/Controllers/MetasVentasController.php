<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\MetasVentas;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class MetasVentasController extends Controller
{

    public function index(Request $request)
    {
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);
        $busqueda = $request->input('busqueda');
        $porPagina = $request->input('por_pagina', 10);

        $ordenarPor = $request->input('ordenar_por', 'username');
        $orden = $request->input('orden', 'asc');

        $usuariosQuery = Usuario::where(function ($query) use ($month, $year) {
            $query
                ->where('estatus', 'Activo')
                ->orWhere(function ($q) use ($month, $year) {
                    $q->where('estatus', 'Inactivo')
                        ->where('archivado', 1)
                        ->whereNotNull('fecha_baja')
                        ->whereYear('fecha_baja', $year)
                        ->whereMonth('fecha_baja', $month);
                });
        });

        if ($busqueda) {
            $usuariosQuery->where(function ($query) use ($busqueda) {
                $query->where('username', 'like', "%$busqueda%")
                    ->orWhere('nombre', 'like', "%$busqueda%")
                    ->orWhere('apellido_p', 'like', "%$busqueda%")
                    ->orWhere('apellido_m', 'like', "%$busqueda%");
            });
        }

        $usuarios = $usuariosQuery->with(['metasVentas' => function ($query) use ($month, $year) {
            $query->where('mes_aplicacion', "$year-$month");
        }])
            ->orderBy($ordenarPor, $orden)
            ->paginate($porPagina)
            ->appends($request->all());

        return view('ventas.metas', compact(
            'usuarios',
            'month',
            'year',
            'busqueda',
            'porPagina',
            'ordenarPor',
            'orden'
        ));
    }


    public function guardar(Request $request)
    {
        $validated = $request->validate([
            'id_usuario' => 'required|integer',
            'cuota_facturacion' => 'required|numeric',
            'cuota_marginal_facturacion' => 'required|numeric',
            'dias_meta' => 'required|integer',
            'cotizaciones_diarias' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
        ]);

        $mes = $validated['month'];
        $anio = $validated['year'];

        MetasVentas::updateOrCreate(
            [
                'id_usuario' => $validated['id_usuario'],
                'mes' => $mes,
                'anio' => $anio,
            ],
            [
                'cuota_facturacion' => $validated['cuota_facturacion'],
                'cuota_marginal_facturacion' => $validated['cuota_marginal_facturacion'],
                'dias_meta' => $validated['dias_meta'],
                'cotizaciones_diarias' => $validated['cotizaciones_diarias'],
            ]
        );

        return back()->with('success', 'Metas guardadas correctamente.');
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
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
