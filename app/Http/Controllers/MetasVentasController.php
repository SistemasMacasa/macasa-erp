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

        // Rango del mes consultado
        $inicioMes = \Carbon\Carbon::create($year, $month, 1)->startOfDay();
        $finMes = \Carbon\Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();

        $usuariosQuery = Usuario::role('ventas') //  solo usuarios con rol de ventas
            ->whereDate('fecha_alta', '<=', $finMes)
            ->where(function ($q) use ($inicioMes) {
                $q->whereNull('fecha_baja')
                    ->orWhereDate('fecha_baja', '>=', $inicioMes);
            });


        // Búsqueda opcional
        if ($busqueda) {
            $usuariosQuery->where(function ($query) use ($busqueda) {
                $query->where('username', 'like', "%$busqueda%")
                    ->orWhere('nombre', 'like', "%$busqueda%")
                    ->orWhere('apellido_p', 'like', "%$busqueda%")
                    ->orWhere('apellido_m', 'like', "%$busqueda%");
            });
        }

        // Cargar metas del mes consultado
        $usuarios = $usuariosQuery
            ->with(['metasVentas' => function ($query) use ($month, $year) {
                $query->where('anio', $year)
                    ->where('mes', $month);
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
        // Pre-procesar campos monetarios antes de validar
        $request->merge([
            'cuota_facturacion' => str_replace([',', '$'], '', $request->cuota_facturacion),
            'cuota_marginal_facturacion' => str_replace([',', '$'], '', $request->cuota_marginal_facturacion),
        ]);

        $validated = $request->validate([
            'id_usuario' => 'required|integer',
            'cuota_facturacion' => 'required|numeric',
            'cuota_marginal_facturacion' => 'required|numeric',
            'dias_meta' => 'required|integer',
            'cotizaciones_diarias' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
        ]);

        // Calcular cuota_cotizaciones multiplicando días por cotizaciones diarias
        $cuotaCotizaciones = $validated['dias_meta'] * $validated['cotizaciones_diarias'];

        MetasVentas::updateOrCreate(
            [
                'id_usuario' => $validated['id_usuario'],
                'mes' => $validated['month'],
                'anio' => $validated['year'],
            ],
            [
                'cuota_facturacion' => $validated['cuota_facturacion'],
                'cuota_marginal_facturacion' => $validated['cuota_marginal_facturacion'],
                'dias_meta' => $validated['dias_meta'],
                'cotizaciones_diarias' => $validated['cotizaciones_diarias'],
                'cuota_cotizaciones' => $cuotaCotizaciones, // <-- Aquí el resultado
                'cuota_marginal_cotizaciones' => 0,
                'cuota_llamadas' => 0,
            ]
        );

        return redirect()->route('ventas.metas', [
            'month' => $validated['month'],
            'year' => $validated['year'],
        ])->with('success', 'Metas guardadas correctamente.');
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
