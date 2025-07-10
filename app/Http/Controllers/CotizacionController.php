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
<<<<<<< HEAD
=======
use App\Models\Cotizacion;
use App\Models\CotizacionPartida;
>>>>>>> dev
use Illuminate\Support\Str;


class CotizacionController extends Controller
{
<<<<<<< HEAD
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
=======
    public function index()
    {
        $equipos = Equipo::with(['lider', 'usuarios'])->get();
        $usuarios = Usuario::all();
        // dd($equipos);

        return view('cotizaciones.index', compact('equipos', 'usuarios'));
>>>>>>> dev
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

        $contactos_entrega = Contacto::with([
            'direccion_entrega.colonia',
            'direccion_entrega.ciudad',
            'direccion_entrega.estado',
            'direccion_entrega.pais'
        ])
            ->where('id_cliente', $id_cliente)
            ->whereNotNull('id_direccion_entrega')
            ->orderBy('nombre')
            ->get();

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
                'contactos_entrega',
                'direccion_facturacion',
                'direccion_entrega',
                'paises'
            )
        );
    }

    public function store(Request $request)
    {
        /* 1. Validación */
        $v = $request->validate([
            'id_cliente'           => 'required|exists:clientes,id_cliente',
            'id_contacto_entrega'  => 'required|exists:contactos,id_contacto',
            'id_razon_social'      => 'required|exists:razones_sociales,id_razon_social',
            'partidas'             => 'required',               // JSON string
        ]);

        $partidas = json_decode($v['partidas'], true);
        if (!$partidas || !is_array($partidas)) {
            return response()->json(['success'=>false,'message'=>'Partidas mal formateadas'],422);
        }

        /* 2. Datos base */
        $hoy         = now();
        $vencimiento = $hoy->copy()->addWeeks(2);
        $vendedorId  = auth()->id();
        $folio       = $this->nextConsecutivo();      // MC2xxxxx

        \Log::debug('Payload recibido para cotización:', $v);

        /* 3. Transacción */
        DB::beginTransaction();
        try 
        {
            /* A) Cotización */
            $cot = Cotizacion::create([
                'id_cliente'          => $v['id_cliente'],
                'id_razon_social'     => $v['id_razon_social'],
                'id_contacto_entrega' => $v['id_contacto_entrega'],   // ← nombre correcto
                'id_vendedor'         => $vendedorId,
                'fecha_alta'          => $hoy,
                'vencimiento'         => $vencimiento,
                'num_consecutivo'     => $folio,
            ]);

            /* B) Partidas */
            $scoreTotal = 0;
            foreach ($partidas as $p){
                $p['id_cotizacion'] = $cot->id_cotizacion;
                CotizacionPartida::create([
                    'id_cotizacion' => $cot->id_cotizacion,
                    'sku'           => $p['sku'] ?? '',
                    'descripcion'   => $p['descripcion'],
                    'cantidad'      => $p['cantidad'],
                    'precio'        => $p['precio'],
                    'costo'         => $p['costo'],
                ]);
            }

            /* C) Score final */
            $scoreTotal = $cot->partidas->sum('score');
            $cot->update(['score_final' => $scoreTotal]);

            DB::commit();

            return response()->json([
                'success'     => true,
                'redirect_to' => route('cotizaciones.index', $cot->id_cotizacion),
            ]);
        } catch (\Throwable $e){
            DB::rollBack();
            \Log::error('Fallo al guardar cotización', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['success'=>false,'message'=>'Error interno'],500);
        }

    }


    /* ---------- helper para consecutivo seguro ---------- */
    protected function nextConsecutivo(): string
    {
        return DB::transaction(function () {

            /* ① buscamos (y bloqueamos) el registro del prefijo */
            $reg = DB::table('consecutivos')
                    ->lockForUpdate()
                    ->where('prefijo', 'MC2')
                    ->first();

            /* ② si no existe, lo creamos                             */
            if (!$reg) {
                DB::table('consecutivos')->insert([
                    'tipo'          => 'cotizaciones',
                    'prefijo'       => 'MC2',
                    'valor_actual'  => 1,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
                $next = 1;
            } else {
                $next = $reg->valor_actual + 1;
                DB::table('consecutivos')
                ->where('id', $reg->id)
                ->update([
                    'valor_actual' => $next,
                    'updated_at'   => now(),
                ]);
            }

            /* ③ devolvemos el folio formateado -- MC200001, MC200002, … */
            return sprintf('MC2%05d', $next);
        });
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
            'id_cliente' => 'required|exists:clientes,id_cliente',
            'nombre' => 'required|string|max:100',
            'rfc' => 'required|string|max:13',
            'id_uso_cfdi' => 'required|exists:uso_cfdis,id_uso_cfdi',
            'id_metodo_pago' => 'required|exists:metodo_pagos,id_metodo_pago',
            'id_forma_pago' => 'required|exists:forma_pagos,id_forma_pago',
            'id_regimen_fiscal' => 'required|exists:regimen_fiscales,id_regimen_fiscal',

            /* Dirección */
            'direccion.calle' => 'required|string|max:100',
            'direccion.num_ext' => 'required|string|max:20',
            'direccion.num_int' => 'nullable|string|max:20',
            'direccion.id_colonia' => 'required|integer|exists:colonias,id_colonia',
            'direccion.cp' => 'required|string|max:10',
            'direccion.id_pais' => 'nullable|integer|exists:paises,id_pais',
        ]);

        /* 2. Resolver Colonia → Ciudad/Estado */
        $colonia = Colonia::findOrFail($data['direccion']['id_colonia']);   // sin with()

        // Verificamos que el CP corresponda
        if ($colonia->d_codigo !== $data['direccion']['cp']) {
            return response()->json([
                'success' => false,
                'message' => 'El C.P. no coincide con la colonia seleccionada.'
            ], 422);
        }

<<<<<<< HEAD
        $idColonia = $coloniaObj->id_colonia;
        $idEstado  = Estado::where('c_estado',  $coloniaObj->c_estado)->value('id_estado');
        $idCiudad  = Ciudad::where('c_estado',  $coloniaObj->c_estado)
            ->where('c_mnpio', $coloniaObj->c_mnpio)
            ->value('id_ciudad');

        if (!$idEstado || !$idCiudad) {
            throw ValidationException::withMessages([
                'ubicacion' => ['No se pudo resolver ciudad/estado a partir de la colonia.']
            ]);
=======
        // Estado
        $estado = Estado::where('c_estado', $colonia->c_estado)->first();
        if (!$estado) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró el estado ligado a la colonia.'
            ], 422);
>>>>>>> dev
        }

        // Ciudad / municipio  ← dupla (c_estado, c_mnpio)
        $ciudad = Ciudad::where('c_estado', $colonia->c_estado)
            ->where('c_mnpio', $colonia->c_mnpio)
            ->first();

        if (!$ciudad) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró la ciudad ligada a la colonia.'
            ], 422);
        }



        /* 3. Crear todo en transacción */
<<<<<<< HEAD
        DB::transaction(function () use (
            &$razon,
            &$direccion,
            $data,
            $idColonia,
            $idCiudad,
            $idEstado
        ) {
=======
        DB::transaction(function () use (&$razon, &$direccion, $data, $colonia, $estado, $ciudad) {
>>>>>>> dev
            /* Dirección */
            $direccion = Direccion::create([
                'id_cliente' => $data['id_cliente'],
                'tipo' => 'facturacion',
                'calle' => $data['direccion']['calle'],
                'num_ext' => $data['direccion']['num_ext'],
                'num_int' => $data['direccion']['num_int'] ?? null,
                'cp' => $data['direccion']['cp'],
                'id_colonia' => $colonia->id_colonia,
                'id_ciudad' => $ciudad->id_ciudad,
                'id_estado' => $estado->id_estado,
                'id_pais' => $data['direccion']['id_pais'] ?? 1, // México por defecto
            ]);

            /* Desactiva predeterminada previa */
            RazonSocial::where('id_cliente', $data['id_cliente'])
                ->where('predeterminado', 1)
                ->update(['predeterminado' => 0]);

            /* Nueva razón social */
            $razon = RazonSocial::create([
                'nombre' => $data['nombre'],
                'id_cliente' => $data['id_cliente'],
                'RFC' => $data['rfc'],
                'id_uso_cfdi' => $data['id_uso_cfdi'],
                'id_metodo_pago' => $data['id_metodo_pago'],
                'id_forma_pago' => $data['id_forma_pago'],
                'id_regimen_fiscal' => $data['id_regimen_fiscal'],
                'dias_credito' => 0,
                'saldo' => 0,
                'limite_credito' => 0,
                'id_direccion_facturacion' => $direccion->id_direccion,
                'predeterminado' => 1,
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
<<<<<<< HEAD

=======
>>>>>>> dev
        return response()->json([
            'success' => true,
            'razon_social' => $razon,
            'direccion' => $direccion,
        ], 201);
    }


    /**
     * Alta rápida dirección de entrega + contacto predeterminado.
     */
    public function storeDireccionEntregaFactura(Request $request)
    {
        /* 1️⃣  VALIDACIÓN */
        $v = $request->validate([
            'id_cliente' => 'required|integer|exists:clientes,id_cliente',

            // contacto
            'contacto.nombre' => 'required|string|max:120',
            'contacto.apellido_p' => 'required|string|max:100',
            'contacto.apellido_m' => 'nullable|string|max:100',
            'contacto.telefono' => 'nullable|string|max:25',
            'contacto.ext' => 'nullable|string|max:10',
            'contacto.email' => 'nullable|email|max:120',

            // dirección
            'direccion.id_colonia' => 'required|integer|exists:colonias,id_colonia',
            'direccion.nombre' => 'nullable|string|max:27',
            'direccion.calle' => 'required|string|max:120',
            'direccion.num_ext' => 'required|string|max:15',
            'direccion.num_int' => 'nullable|string|max:15',
            'direccion.cp' => 'required|string|max:10',
            'direccion.id_pais' => 'nullable|integer|exists:paises,id_pais',

            'notas' => 'nullable|string|max:255',
        ]);

        /* 2️⃣  COLONIA + ESTADO + CIUDAD */
        $colonia = Colonia::findOrFail($v['direccion']['id_colonia']);   // sin with()

        // Verificamos que el CP corresponda
        if ($colonia->d_codigo !== $v['direccion']['cp']) {
            return response()->json([
                'success' => false,
                'message' => 'El C.P. no coincide con la colonia seleccionada.'
            ], 422);
        }

<<<<<<< HEAD
        $idEstado = Estado::where('c_estado', $colonia->c_estado)->value('id_estado');
        $idCiudad = Ciudad::where('c_estado', $colonia->c_estado)
            ->where('c_mnpio', $colonia->c_mnpio)
            ->value('id_ciudad');
=======
        // Estado
        $estado = Estado::where('c_estado', $colonia->c_estado)->first();
        if (!$estado) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró el estado ligado a la colonia.'
            ], 422);
        }
>>>>>>> dev

        // Ciudad / municipio  ← dupla (c_estado, c_mnpio)
        $ciudad = Ciudad::where('c_estado', $colonia->c_estado)
            ->where('c_mnpio', $colonia->c_mnpio)
            ->first();

        if (!$ciudad) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró la ciudad ligada a la colonia.'
            ], 422);
        }

        /* 3️⃣  TRANSACCIÓN  Dirección + Contacto */
        DB::beginTransaction();
        try {
            $direccion = Direccion::create([
                'id_cliente' => $v['id_cliente'],
                'nombre' => $v['direccion']['nombre'] ?? null,
                'tipo' => 'entrega',
                'calle' => $v['direccion']['calle'],
                'num_ext' => $v['direccion']['num_ext'],
                'num_int' => $v['direccion']['num_int'] ?? null,
                'cp' => $v['direccion']['cp'],         // <--  cp correcto
                'id_colonia' => $colonia->id_colonia,
                'id_ciudad' => $ciudad->id_ciudad,
                'id_estado' => $estado->id_estado,
                'id_pais' => $v['direccion']['id_pais'], // México
                'notas' => $v['notas'] ?? null,
            ]);

            // desactivar contacto predeterminado anterior
            Contacto::where('id_cliente', $v['id_cliente'])
                ->whereNotNull('id_direccion_entrega')
                ->where('predeterminado', 1)
                ->update(['predeterminado' => 0]);

            // nuevo contacto
            $contacto = Contacto::create([
<<<<<<< HEAD
                'id_cliente'          => $v['id_cliente'],
                'id_direccion_entrega' => $direccion->id_direccion,
                'nombre'              => $v['contacto']['nombre'],
                'apellido_p'          => $v['contacto']['apellido_p'],
                'apellido_m'          => $v['contacto']['apellido_m'] ?? null,
                'telefono1'           => $v['contacto']['telefono'] ?? null,
                'ext1'                => $v['contacto']['ext'] ?? null,
                'email'               => $v['contacto']['email'] ?? null,
                'predeterminado'      => 1,
=======
                'id_cliente' => $v['id_cliente'],
                'id_direccion_entrega' => $direccion->id_direccion,
                'nombre' => $v['contacto']['nombre'],
                'apellido_p' => $v['contacto']['apellido_p'],
                'apellido_m' => $v['contacto']['apellido_m'] ?? null,
                'telefono1' => $v['contacto']['telefono'] ?? null,
                'ext1' => $v['contacto']['ext'] ?? null,
                'email' => $v['contacto']['email'] ?? null,
                'predeterminado' => 1,
>>>>>>> dev
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
<<<<<<< HEAD
            return response()->json(['success' => false, 'message' => 'Error interno'], 500);
=======
            return response()->json([
                'success' => false,
                'message' => 'Error interno al guardar la dirección.'
            ], 500);
>>>>>>> dev
        }

        /* 4️⃣  PAYLOAD para el front */
        return response()->json([
            'success' => true,
            'entrega' => [
                    'id_direccion_entrega' => $direccion->id_direccion,
                    'contacto' => [
                            'id_contacto' => $contacto->id_contacto,
                            'nombre' => $contacto->nombreCompleto,
                            'telefono' => $contacto->telefono1,
                            'ext' => $contacto->ext1,
                            'email' => $contacto->email,
                        ],
                    'direccion' => [
                        'nombre' => $direccion->nombre,
                        'calle' => $direccion->calle,
                        'num_ext' => $direccion->num_ext,
                        'num_int' => $direccion->num_int,
                        'colonia' => $colonia->d_asenta,
                        'ciudad' => $ciudad->n_mnpio,
                        'estado' => $estado->d_estado,
                        'pais' => 'México',
                        'cp' => $direccion->cp,
                    ],
                    'notas' => $direccion->notas,
                ],
        ]);
    }
<<<<<<< HEAD
}
=======


    // app/Http/Controllers/CotizacionesController.php
    public function agregarPartida(Request $req, Cotizacion $cotizacion)
    {
        $v = $req->validate([
            'descripcion' => 'required|string|max:255',
            'sku' => 'nullable|string|max:50',
            'precio' => 'required|numeric|min:0',
            'costo' => 'required|numeric|min:0',
            'cantidad' => 'required|integer|min:1',
        ]);

        $v['importe'] = $v['precio'] * $v['cantidad'];
        $v['score'] = $v['importe'] - ($v['costo'] * $v['cantidad']); // utilidad simple

        $partida = $cotizacion->partidas()->create($v);

        return response()->json([
            'success' => true,
            'partida' => $partida
        ]);
    }

    public function eliminarPartida(CotizacionPartida $partida)
    {
        $partida->delete();
        return response()->json(['success' => true]);
    }



}
>>>>>>> dev
