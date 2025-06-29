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

        $direcciones_facturacion = Direccion::where('id_cliente', $id_cliente)
            ->where('tipo', 'facturacion')
            ->orderBy('id_cliente')
            ->get();

        $direcciones_entrega = Direccion::where('id_cliente', $id_cliente)
            ->where('tipo', 'entrega')
            ->orderBy('id_cliente')
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
                'direcciones_facturacion',
                'direcciones_entrega',
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
        $nombreCol = normalize($data['colonia']);

        $colonia = Colonia::where('d_CP', $cp)->get()
            ->first(function ($row) use ($nombreCol) {
                return normalize($row->d_asenta) === $nombreCol;
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



}