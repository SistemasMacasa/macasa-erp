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
use App\Models\Contacto;


class CotizacionController extends Controller
{
    public function index()
    {
        return view('cotizaciones.index');
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
        // 1. Valida los datos de entrada (nombres en lugar de IDs)
        $data = $request->validate([
            'id_cliente' => 'required|integer|exists:clientes,id_cliente',
            'nombre' => 'required|string|max:100',
            'rfc' => 'required|string|max:13',
            'id_uso_cfdi' => 'required|integer|exists:uso_cfdis,id_uso_cfdi',
            'id_metodo_pago' => 'required|integer|exists:metodo_pagos,id_metodo_pago',
            'id_forma_pago' => 'required|integer|exists:forma_pagos,id_forma_pago',
            'id_regimen_fiscal' => 'required|integer|exists:regimen_fiscales,id_regimen_fiscal',
            // dirección (textos y CP)
            'calle' => 'nullable|string|max:100',
            'num_ext' => 'nullable|string|max:20',
            'num_int' => 'nullable|string|max:20',
            'colonia' => 'nullable|string|max:100',
            'cp' => 'nullable|string|max:10',
            'municipio' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:100',
            'id_pais' => 'nullable|integer|exists:paises,id_pais',
            'notas' => 'nullable|string|max:255',
        ]);

        // 2. Obtiene los IDs a partir de los nombres (o lanza error si no existen)
        // ---------------------------------------------------------------
        // 1) Intento resolver colonia por CP + nombre normalizado
        // ---------------------------------------------------------------
        $cp = $data['cp'];
        $nombreCol = $this->normalize($data['colonia']);

        $colonia = Colonia::where('d_codigo', $cp)->get()
            ->first(function ($row) use ($nombreCol) {
                return $this->normalize($row->d_asenta) === $nombreCol;
            });

        if (!$colonia) {
            throw ValidationException::withMessages([
                'colonia' => ['La colonia o el código postal no concuerdan con SEPOMEX.']
            ]);
        }

        $idColonia = $colonia->id_colonia;

        // ---------------------------------------------------------------
        // 2) A partir de la colonia saco ciudad y estado por CLAVES,
        //    no por texto, así evito “México” vs “Estado de México”.
        // ---------------------------------------------------------------
        $idEstado = Estado::where('c_estado', $colonia->c_estado)->value('id_estado');

        $idCiudad = Ciudad::where('c_estado', $colonia->c_estado)
            ->where('c_mnpio', $colonia->c_mnpio)
            ->value('id_ciudad');

        if (!$idEstado || !$idCiudad) {
            throw ValidationException::withMessages([
                'ubicacion' => ['No se pudo resolver ciudad/estado a partir de la colonia.']
            ]);
        }

        // 4) CREO TODO EN TRANSACCIÓN
        DB::transaction(function () use (&$razon, &$direccion, $data, $idEstado, $idCiudad, $idColonia) {
            $direccion = Direccion::create([
                'id_cliente' => $data['id_cliente'],
                'tipo' => 'facturacion',
                'calle' => $data['calle'] ?? null,
                'num_ext' => $data['num_ext'] ?? null,
                'num_int' => $data['num_int'] ?? null,
                'cp' => $data['cp'] ?? null,
                'id_pais' => $data['pais'] ?? 1,
                'id_estado' => $idEstado,
                'id_ciudad' => $idCiudad,
                'id_colonia' => $idColonia,
                'notas' => $data['notas'] ?? null,
            ]);

            RazonSocial::where('id_cliente', $data['id_cliente'])
                ->where('predeterminado', 1)
                ->update(['predeterminado' => 0]);

            $razon = RazonSocial::create([
                'nombre' => $data['nombre'],
                'id_cliente' => $data['id_cliente'],
                'RFC' => $data['rfc'],
                'id_uso_cfdi' => $data['id_uso_cfdi'],
                'id_metodo_pago' => $data['id_metodo_pago'],
                'id_forma_pago' => $data['id_forma_pago'],
                'id_regimen_fiscal' => $data['id_regimen_fiscal'],
                'saldo' => 0,
                'limite_credito' => 0,
                'dias_credito' => $data['dias_credito'] ?? 0,
                'id_direccion_facturacion' => $direccion->id_direccion,
                'predeterminado' => 1,
            ]);
        });

        return response()->json([
            'razon_social' => $razon,
            'direccion' => $direccion,
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
            'direccion.nombre' => 'nullable|string|max:27', // alias interno
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

        DB::beginTransaction();
        try {
            /* 1) Crear dirección (tipo = entrega) */
            $direccion = Direccion::create([
                'id_cliente' => $v['id_cliente'],
                'nombre' => $v['direccion']['nombre'] ?? null, // alias interno
                'tipo' => 'entrega',
                'calle' => $v['direccion']['calle'],
                'num_ext' => $v['direccion']['num_ext'],
                'num_int' => $v['direccion']['num_int'] ?? null,
                'cp' => $v['direccion']['cp'],
                'id_colonia' => null,
                'id_ciudad' => null,
                'id_estado' => null,
                'id_pais' => 1,
            ]);

            /* 2) Apagar al contacto de entrega predeterminado anterior */
            Contacto::where('id_cliente', $v['id_cliente'])
                ->whereNotNull('id_direccion_entrega')
                ->where('predeterminado', 1)
                ->update(['predeterminado' => 0]);

            /* 3) Crear nuevo contacto y marcarlo predeterminado */
            $contacto = Contacto::create([
                'id_cliente' => $v['id_cliente'],
                'id_direccion_entrega' => $direccion->id_direccion,
                'nombre' => $v['contacto']['nombre'],
                'apellido_p' => $v['contacto']['apellido_p'],
                'apellido_m' => $v['contacto']['apellido_m'] ?? null,
                'telefono1' => $v['contacto']['telefono'] ?? null,
                'ext1' => $v['contacto']['ext'] ?? null,
                'email' => $v['contacto']['email'] ?? null,
                'predeterminado' => 1,
            ]);


            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'No se pudo guardar la dirección.',
            ], 500);
        }

        /* 4) JSON para el front */
        return response()->json([
            'success' => true,
            'entrega' => [
                'id_direccion_entrega' => $direccion->id_direccion,
                'contacto' => [
                    'id_contacto' => $contacto->id_contacto,
                    'nombre' => $contacto->nombre,
                    'telefono' => $contacto->telefono1,
                    'email' => $contacto->email,
                ],
                'direccion' => [
                    'calle' => $direccion->calle,
                    'num_ext' => $direccion->num_ext,
                    'num_int' => $direccion->num_int,
                    'colonia' => $v['direccion']['colonia'],
                    'ciudad' => $v['direccion']['ciudad'],
                    'estado' => $v['direccion']['estado'],
                    'pais' => $v['direccion']['pais'],
                    'cp' => $direccion->cp,
                ],
                'notas' => $v['notas'] ?? null,
            ],
        ]);
    }



}