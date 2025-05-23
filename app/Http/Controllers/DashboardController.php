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

        /* === Dummies ===================================================== */
        $birthdays = collect([
            ['name' => 'Ana López',     'date' => Carbon::parse('1993-06-07')],
            ['name' => 'Carlos Pérez',  'date' => Carbon::parse('1990-06-15')],
            ['name' => 'Mónica Ruiz',   'date' => Carbon::parse('1987-06-22')],
        ]);

        $events = collect([
            ['title' => 'Día del Padre',       'from' => '2025-06-15', 'to' => null],
            ['title' => 'Puente 20-22 Nov',    'from' => '2025-11-20', 'to' => '2025-11-22'],
            ['title' => 'Aniversario MACASA',  'from' => '2025-09-08', 'to' => null],
        ]);

        $focus = (object) [
            'title'   => 'Enfoque: Licenciamiento Microsoft 365',
            'image'   => asset('images/focus-dummy.jpg'),   // pon cualquier banner temporal
            'message' => 'Aprovecha 15 % de descuento en renovaciones antes del 30/06.'
        ];

        $topExecs = collect([
            ['name' => 'Marco C.', 'total' => 127_000],
            ['name' => 'Eliezer',  'total' =>  98_500],
            ['name' => 'Alicia',   'total' =>  73_100],
        ]);

        $topClients = collect([
            ['name' => 'Gob. de Jalisco', 'total' => 820_000],
            ['name' => 'IBM México',      'total' => 436_000],
            ['name' => 'Universidad X',   'total' => 295_900],
        ]);

        $notices = collect([
            ['body' => 'Mañana hay evento, portar camiseta institucional.'],
            ['body' => 'Se abre vacante para soporte nivel 1.'],
        ]);

        $usuario = auth()->user();

        // Este array debe coincidir con lo que tu vista espera
        return view('inicio', compact(
            'cuentasAsignadas',
            'pendientesHoy',
            'alcanceCotizacion',
            'alcanceVenta',
            'seriesRadial',
            'labelsRadial',
            'cotizaciones',
            'ventas',
            'progresoCotizacion',
            'progresoVenta',
            'birthdays',
            'events',
            'focus',
            'topExecs',
            'topClients',
            'notices',
            'usuario'
        ));
    }


}
