<?php

namespace App\Http\Controllers;

use App\Models\Colonia;
use Illuminate\Support\Facades\Cache;

class ColoniaController extends Controller
{
    public function porCp(string $cp)
    {
        // Sólo acepta 5 dígitos
        if (!preg_match('/^\d{5}$/', $cp)) {
            return response()->json([], 400);
        }

        /*  Cache 7 días —  reduce carga si piden el mismo CP */
        $payload = Cache::remember("cp:$cp", now()->addDays(7), function () use ($cp) {
            $colonias = Colonia::where('d_codigo', $cp)
                ->select([
                    'd_asenta      as colonia',
                    'd_tipo_asenta as tipo',
                    'D_mnpio       as municipio',
                    'd_estado      as estado'
                ])
                ->orderBy('colonia')
                ->get();

            if ($colonias->isEmpty()) {
                return null;   // para devolver 404
            }

            return [
                'cp'       => $cp,
                'head'     => $colonias->first()->only(['municipio', 'estado']),
                'colonias' => $colonias->makeHidden(['municipio', 'estado']),
            ];
        });

        if (!$payload) {
            return response()->json([], 404);
        }

        return response()->json($payload);
    }
}
