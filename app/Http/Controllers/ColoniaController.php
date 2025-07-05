<?php

namespace App\Http\Controllers;

use App\Models\Colonia;
use Illuminate\Support\Facades\Cache;

class ColoniaController extends Controller
{
    public function porCp(string $cp)
    {
        // 1) Validación básica
        if (!preg_match('/^\d{5}$/', $cp)) {
            return response()->json([], 400);
        }

        /* 2) Cache 7 días */
        $payload = Cache::remember("cp:$cp", now()->addDays(7), function () use ($cp) {

            // 👉 Incluimos id_colonia en el SELECT
            $colonias = Colonia::where('d_codigo', $cp)
                ->select([
                    'id_colonia',
                    'd_asenta      as colonia',
                    'd_tipo_asenta as tipo',
                    'D_mnpio       as municipio',
                    'd_estado      as estado',
                ])
                ->orderBy('colonia')
                ->get();

            if ($colonias->isEmpty()) {
                return null;               // para responder 404
            }

            // ⚠️  NO ocultamos id_colonia; lo devolvemos tal cual
            return [
                'cp'   => $cp,
                'head' => [
                    'municipio' => $colonias->first()->municipio,
                    'estado'    => $colonias->first()->estado,
                ],
                'colonias' => $colonias->map(fn ($c) => [
                    'id_colonia' => $c->id_colonia,
                    'colonia'    => $c->colonia,
                    'tipo'       => $c->tipo,
                ]),
            ];
        });

        if (!$payload) {
            return response()->json([], 404);
        }

        return response()->json($payload);
    }


}
