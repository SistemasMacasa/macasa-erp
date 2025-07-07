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

        $ordenarPor = $request->input('ordenar_por', 'username'); // campo por defecto
        $orden = $request->input('orden', 'asc'); // dirección por defecto

        $usuariosQuery = Usuario::activos()->where(function ($query) {
            $query->whereNull('id_cliente')->orWhereNotNull('id_cliente');
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
            ->appends($request->all()); // mantiene filtros en los links de paginación

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
