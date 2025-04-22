<?php

// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $cuentasAsignadas = 3;
        $pendientesHoy = 5;
        $alcanceCotizacion = 250000;
        $alcanceVenta = 37490;
        $seriesRadial = [80, 60, 35];
        $labelsRadial = ['Junio', 'Mayo', 'Abril'];
        $cotizaciones = [10, 20, 15, 30, 25, 35, 40];
        $ventas = [5, 15, 10, 20, 18, 30, 25];
        $progresoCotizacion = 60;
        $progresoVenta = 15;

        // Este array debe coincidir con lo que tu vista espera
        return view('inicio', [
            'cuentasAsignadas' => $cuentasAsignadas,
            'pendientesHoy' => $pendientesHoy,
            'alcanceCotizacion' => $alcanceCotizacion,
            'alcanceVenta' => $alcanceVenta,
            'seriesRadial' => $seriesRadial,
            'labelsRadial' => $labelsRadial,
            'cotizaciones' => $cotizaciones,
            'ventas' => $ventas,
            'progresoCotizacion' => $progresoCotizacion,
            'progresoVenta' => $progresoVenta,
            'data' => compact(
                'seriesRadial',
                'labelsRadial',
                'cotizaciones',
                'ventas',
                'progresoCotizacion',
                'progresoVenta'
            )
        ]);
    }


}
