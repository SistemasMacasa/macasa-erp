<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;


use App\Models\Direccion;
use App\Models\Cliente;
use App\Models\UsoCfdi;
use App\Models\MetodoPago;
use App\Models\FormaPago;
use App\Models\RegimenFiscal;
use App\Models\RazonSocial;
use App\Models\Pais;
use App\Models\Estado;
use App\Models\Ciudad;
use App\Models\Colonia;
use App\Models\Equipo;
use App\Models\Usuario;
use App\Models\Contacto;
use Illuminate\Support\Str;


class CotizacionController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

        // Obtener equipos con usuarios y líderes
        $equipos = Equipo::with(['lider', 'usuarios'])->get();

        // Cargar metas y cotizaciones filtradas
        $usuarios = Usuario::with([
            'metasVentas' => function ($query) use ($year, $month) {
                $query->whereYear('mes_aplicacion', $year)
                    ->whereMonth('mes_aplicacion', $month);
            },
            'cotizaciones' => function ($query) use ($year, $month) {
                $query->whereYear('fecha_alta', $year)
                    ->whereMonth('fecha_alta', $month);
            }
        ])->get()->keyBy('id_usuario');

        foreach ($equipos as $equipo) {
            // Inicia en cero
            $equipo->cuota_cotizacion = 0;
            $equipo->alcance_cotizacion = 0;
            $equipo->porcentaje_cotizacion = 0;
            $equipo->cuota_margen = 0;
            $equipo->alcance_margen = 0;
            $equipo->porcentaje_margen = 0;

            foreach ($equipo->usuarios as $usuario) {
                $user = $usuarios->get($usuario->id_usuario);
                $meta = $user->metasVentas->first();

                // Simulación si no hay metas reales
                $cuota = $meta->cuota_cotizaciones ?? rand(8, 15);
                $alcance = $user->cotizaciones->count();
                $porcentaje = $cuota > 0 ? ($alcance / $cuota) * 100 : 0;

                $cuotaMargen = $meta->cuota_marginal_cotizaciones ?? rand(2000, 4000);
                $alcanceMargen = $user->cotizaciones->sum('score_final') ?? rand(1500, 3500);
                $porcentajeMargen = $cuotaMargen > 0 ? ($alcanceMargen / $cuotaMargen) * 100 : 0;

                // Inyectar datos al usuario
                $usuario->metas = [
                    'cuota_cotizacion' => $cuota,
                    'alcance_cotizacion' => $alcance,
                    'porcentaje_cotizacion' => $porcentaje,
                    'cuota_margen' => $cuotaMargen,
                    'alcance_margen' => $alcanceMargen,
                    'porcentaje_margen' => $porcentajeMargen,
                ];

                // Sumar al equipo
                $equipo->cuota_cotizacion += $cuota;
                $equipo->alcance_cotizacion += $alcance;
                $equipo->cuota_margen += $cuotaMargen;
                $equipo->alcance_margen += $alcanceMargen;
            }

            // Cálculos de porcentaje del equipo
            $equipo->porcentaje_cotizacion = $equipo->cuota_cotizacion > 0
                ? ($equipo->alcance_cotizacion / $equipo->cuota_cotizacion) * 100
                : 0;

            $equipo->porcentaje_margen = $equipo->cuota_margen > 0
                ? ($equipo->alcance_margen / $equipo->cuota_margen) * 100
                : 0;
        }

        return view('cotizaciones.index', compact('equipos', 'year', 'month'));
    }


    public function create($id_cliente)
    {
        $cliente = Cliente::with([
            'razon_social_predet',
            'razon_social_predet.direccion_facturacion',
            'contacto_entrega_predet',
            'contacto_entrega_predet.direccion_entrega'
        ])->findOrFail($id_cliente);

        $uso_cfdis = UsoCfdi::orderBy('clave')->get();
        $metodos = MetodoPago::orderBy('clave')->get();
        $formas = FormaPago::orderBy('clave')->get();
        $regimenes = RegimenFiscal::orderBy('clave')->get();

        $direccion_facturacion = $cliente->razon_social_predet?->direccion_facturacion;
        $direccion_entrega = $cliente->contacto_entrega_predet?->direccion_entrega;

        $razones_sociales = RazonSocial::with([
            'direccion_facturacion.colonia',
            'direccion_facturacion.ciudad',
            'direccion_facturacion.estado',
            'direccion_facturacion.pais',
            'uso_cfdi',
            'metodo_pago',
            'forma_pago',
            'regimen_fiscal'
        ])
            ->where('id_cliente', $id_cliente)
            ->orderBy('id_razon_social')
            ->get();

        $contacto_entrega = Contacto::with([
            'direccion_entrega.colonia',
            'direccion_entrega.ciudad',
            'direccion_entrega.estado',
            'direccion_entrega.pais'
        ])
            ->where('id_cliente', $id_cliente)
            ->whereNotNull('id_direccion_entrega')
            ->where('predeterminado', 1)
            ->first();

        $paises = Pais::whereIn('nombre', ['México', 'Estados Unidos', 'Canadá'])
            ->orderByRaw("FIELD(nombre, 'México', 'Estados Unidos', 'Canadá')")
            ->get();

        return view(
            'cotizaciones.create',
            compact(
                'cliente',
                'uso_cfdis',
                'metodos',
                'formas',
                'regimenes',
                'razones_sociales',
                'contacto_entrega',
                'direccion_facturacion',
                'direccion_entrega',
                'paises'
            )
        );
    }

    function normalize($str)
    {
        $str = trim(mb_strtoupper($str, 'UTF-8'));
        // reemplaza tildes y diéresis
        $map = ['Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U', 'Ü' => 'U', 'Ñ' => 'N'];
        return strtr($str, $map);
    }

    public function storeRazonSocialFactura(Request $request)
    {
        /* 1. Validación */
        $data = $request->validate([
            'id_cliente'        => 'required|exists:clientes,id_cliente',
            'nombre'            => 'required|string|max:100',
            'rfc'               => 'required|string|max:13',
            'id_uso_cfdi'       => 'required|exists:uso_cfdis,id_uso_cfdi',
            'id_metodo_pago'    => 'required|exists:metodo_pagos,id_metodo_pago',
            'id_forma_pago'     => 'required|exists:forma_pagos,id_forma_pago',
            'id_regimen_fiscal' => 'required|exists:regimen_fiscales,id_regimen_fiscal',

            /* Dirección */
            'calle'      => 'required|string|max:100',
            'num_ext'    => 'required|string|max:20',
            'num_int'    => 'nullable|string|max:20',
            'colonia'    => 'required|string|max:100',
            'cp'         => 'required|string|max:10',
            'municipio'  => 'nullable|string|max:100',
            'estado'     => 'nullable|string|max:100',
            'id_pais'    => 'nullable|integer|exists:paises,id_pais',
            'notas'      => 'nullable|string|max:255',
        ]);

        /* 2. Resolver Colonia → Ciudad/Estado */
        $cp         = $data['cp'];
        $nombreCol  = $this->normalize($data['colonia']);
        $coloniaObj = Colonia::where('d_codigo', $cp)->get()
            ->first(fn($c) => $this->normalize($c->d_asenta) === $nombreCol);

        if (!$coloniaObj) {
            throw ValidationException::withMessages([
                'colonia' => ['La colonia o el código postal no concuerdan con SEPOMEX.']
            ]);
        }

        $idColonia = $coloniaObj->id_colonia;
        $idEstado  = Estado::where('c_estado',  $coloniaObj->c_estado)->value('id_estado');
        $idCiudad  = Ciudad::where('c_estado',  $coloniaObj->c_estado)
            ->where('c_mnpio', $coloniaObj->c_mnpio)
            ->value('id_ciudad');

        if (!$idEstado || !$idCiudad) {
            throw ValidationException::withMessages([
                'ubicacion' => ['No se pudo resolver ciudad/estado a partir de la colonia.']
            ]);
        }

        /* 3. Crear todo en transacción */
        DB::transaction(function () use (
            &$razon,
            &$direccion,
            $data,
            $idColonia,
            $idCiudad,
            $idEstado
        ) {
            /* Dirección */
            $direccion = Direccion::create([
                'id_cliente' => $data['id_cliente'],
                'tipo'       => 'facturacion',
                'calle'      => $data['calle'],
                'num_ext'    => $data['num_ext'],
                'num_int'    => $data['num_int'] ?? null,
                'cp'         => $data['cp'],
                'id_colonia' => $idColonia,
                'id_ciudad'  => $idCiudad,
                'id_estado'  => $idEstado,
                'id_pais'    => $data['id_pais'] ?? 1,
                'notas'      => $data['notas'] ?? null,
            ]);

            /* Desactiva predeterminada previa */
            RazonSocial::where('id_cliente', $data['id_cliente'])
                ->where('predeterminado', 1)
                ->update(['predeterminado' => 0]);

            /* Nueva razón social */
            $razon = RazonSocial::create([
                'nombre'              => $data['nombre'],
                'id_cliente'          => $data['id_cliente'],
                'RFC'                 => strtoupper($data['rfc']),
                'id_uso_cfdi'         => $data['id_uso_cfdi'],
                'id_metodo_pago'      => $data['id_metodo_pago'],
                'id_forma_pago'       => $data['id_forma_pago'],
                'id_regimen_fiscal'   => $data['id_regimen_fiscal'],
                'dias_credito'        => 0,
                'saldo'               => 0,
                'limite_credito'      => 0,
                'id_direccion_facturacion' => $direccion->id_direccion,
                'predeterminado'      => 1,
            ]);
        });

        /* 4. Carga EAGER sus relaciones para el front */
        $razon->load([
            'direccion_facturacion.colonia',
            'direccion_facturacion.ciudad',
            'direccion_facturacion.estado',
            'direccion_facturacion.pais',
            'uso_cfdi',
            'metodo_pago',
            'forma_pago',
            'regimen_fiscal',
        ]);

        // También la dirección por separado (por comodidad del front)
        $direccion->load(['colonia', 'ciudad', 'estado', 'pais']);

        return response()->json([
            'razon_social' => $razon,
            'direccion'    => $direccion,
        ], 201);
    }


    /**
     * Alta rápida dirección de entrega + contacto predeterminado.
     */
    public function storeDireccionEntregaFactura(Request $request)
    {
        /* ---------- VALIDACIÓN ---------- */
        $v = $request->validate([
            'id_cliente' => 'required|exists:clientes,id_cliente|integer',
            'contacto.nombre' => 'required|string|max:120',
            'contacto.apellido_p' => 'required|string|max:100',
            'contacto.apellido_m' => 'nullable|string|max:100',
            'contacto.telefono' => 'nullable|string|max:25',
            'contacto.ext' => 'nullable|string|max:10',
            'contacto.email' => 'nullable|email|max:120',
            'direccion.nombre' => 'nullable|string|max:27',
            'direccion.calle' => 'required|string|max:120',
            'direccion.num_ext' => 'required|string|max:15',
            'direccion.num_int' => 'nullable|string|max:15',
            'direccion.colonia' => 'required|string|max:120',
            'direccion.cp' => 'required|string|max:10',
            'direccion.ciudad' => 'required|string|max:120',
            'direccion.estado' => 'required|string|max:120',
            'direccion.pais' => 'required|string|max:120',
            'notas' => 'nullable|string|max:255',
        ]);

        /* ── 1.  Resolver colonia por CP + nombre normalizado ───────── */
        $nombreCol = Str::of($v['direccion']['colonia'])->lower()->ascii()->trim();
        $colonia = Colonia::where('d_asenta', $v['direccion']['colonia'])
            ->get()
            ->first(fn($c) => Str::of($c->d_asenta)->lower()->ascii()->trim()->is($nombreCol));

        if (!$colonia) {
            return response()->json([
                'success' => false,
                'message' => 'Colonia / C.P. no encontrados en SEPOMEX.'
            ], 422);
        }

        $idEstado = Estado::where('c_estado', $colonia->c_estado)->value('id_estado');
        $idCiudad = Ciudad::where('c_estado', $colonia->c_estado)
            ->where('c_mnpio', $colonia->c_mnpio)
            ->value('id_ciudad');

        DB::beginTransaction();
        try {
            /* 2. Dirección de entrega */
            $direccion = Direccion::create([
                'id_cliente'  => $v['id_cliente'],
                'nombre'      => $v['direccion']['nombre'] ?? null,
                'tipo'        => 'entrega',
                'calle'       => $v['direccion']['calle'],
                'num_ext'     => $v['direccion']['num_ext'],
                'num_int'     => $v['direccion']['num_int'] ?? null,
                'cp'          => $v['direccion']['cp'],
                'id_colonia'  => $colonia->id_colonia,
                'id_ciudad'   => $idCiudad,
                'id_estado'   => $idEstado,
                'id_pais'     => 1,
                'notas'       => $v['notas'] ?? null,
            ]);

            /* 3. Contacto predeterminado */
            Contacto::where('id_cliente', $v['id_cliente'])
                ->whereNotNull('id_direccion_entrega')
                ->where('predeterminado', 1)
                ->update(['predeterminado' => 0]);

            $contacto = Contacto::create([
                'id_cliente'          => $v['id_cliente'],
                'id_direccion_entrega' => $direccion->id_direccion,
                'nombre'              => $v['contacto']['nombre'],
                'apellido_p'          => $v['contacto']['apellido_p'],
                'apellido_m'          => $v['contacto']['apellido_m'] ?? null,
                'telefono1'           => $v['contacto']['telefono'] ?? null,
                'ext1'                => $v['contacto']['ext'] ?? null,
                'email'               => $v['contacto']['email'] ?? null,
                'predeterminado'      => 1,
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return response()->json(['success' => false, 'message' => 'Error interno'], 500);
        }

        /* 4.  Devuelve TEXTO + IDs para el front */
        return response()->json([
            'success' => true,
            'entrega' => [
                'id_direccion_entrega' => $direccion->id_direccion,
                'contacto' => [
                    'id_contacto' => $contacto->id_contacto,
                    'nombre'      => $contacto->nombreCompleto,     // accessor
                    'telefono'    => $contacto->telefono1,
                    'ext'         => $contacto->ext1,
                    'email'       => $contacto->email,
                ],
                'direccion' => [
                    'nombre'  => $direccion->nombre,
                    'calle'   => $direccion->calle,
                    'num_ext' => $direccion->num_ext,
                    'num_int' => $direccion->num_int,
                    'colonia' => $colonia->d_asenta,
                    'ciudad'  => $colonia->ciudad->n_mnpio,
                    'estado'  => $colonia->estado->d_estado,
                    'pais'    => 'México',
                    'cp'      => $direccion->cp,
                ],
                'notas' => $direccion->notas,
            ],
        ]);
    }
}
