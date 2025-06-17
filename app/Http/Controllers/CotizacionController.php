<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Direccion;
use App\Models\Cliente;
use App\Models\UsoCfdi;
use App\Models\MetodoPago;
use App\Models\FormaPago;
use App\Models\RegimenFiscal;
use App\Models\RazonSocial;


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

        $uso_cfdis  = UsoCfdi::orderBy('clave')->get();
        $metodos    = MetodoPago::orderBy('clave')->get();
        $formas     = FormaPago::orderBy('clave')->get();
        $regimenes  = RegimenFiscal::orderBy('clave')->get();

        $direccion_facturacion = $cliente->razon_social_predet?->direccion_facturacion;
        $direccion_entrega     = $cliente->contacto_entrega_predet?->direccion_entrega;
        
        $direcciones_facturacion = Direccion::where('id_cliente', $id_cliente)
            ->where('tipo', 'facturacion')
            ->orderBy('id_cliente')
            ->get();

        $direcciones_entrega = Direccion::where('id_cliente', $id_cliente)
            ->where('tipo', 'entrega')
            ->orderBy('id_cliente')
            ->get();

        return view('cotizaciones.create', 
                    compact(
                        'cliente', 
                       'uso_cfdis',
                                  'metodos',
                                  'formas',
                                  'regimenes',
                                  'direcciones_facturacion', 
                                  'direcciones_entrega', 
                                  'direccion_facturacion', 
                                  'direccion_entrega'
                                ));
    }
    public function storeRazonSocialFactura(Request $request)
    {
        try 
        {
            $data = $request->validate([
                'id_cliente'         => 'required|integer|exists:clientes,id_cliente',
                'nombre'             => 'required|string|max:100',
                'rfc'                => 'required|string|max:13',
                'id_uso_cfdi'        => 'required|integer|exists:uso_cfdis,id_uso_cfdi',
                'id_metodo_pago'     => 'required|integer|exists:metodo_pagos,id_metodo_pago',
                'id_forma_pago'      => 'required|integer|exists:forma_pagos,id_forma_pago',
                'id_regimen_fiscal'  => 'required|integer|exists:regimen_fiscales,id_regimen_fiscal',
                // dirección
                'calle'                => 'nullable|string|max:100',
                'num_ext'              => 'nullable|string|max:20',
                'num_int'              => 'nullable|string|max:20',
                'colonia'              => 'nullable|string|max:100',
                'cp'                   => 'nullable|string|max:10',
                'municipio'            => 'nullable|string|max:100',
                'estado'               => 'nullable|string|max:100',
                'pais'                 => 'nullable|string|max:100',
                'notas'                => 'nullable|string|max:255',
            ]);

            DB::transaction(function() use (&$razon, &$direccion, $data) 
            {
                // 1) crear la dirección
                $direccion = Direccion::create([
                    'id_cliente' => $data['id_cliente'],
                    'tipo'       => 'facturacion',
                    'calle'      => $data['calle'] ?? null,
                    'num_ext'    => $data['num_ext'] ?? null,
                    'num_int'    => $data['num_int'] ?? null,
                    'colonia'    => $data['colonia'] ?? null,
                    'cp'         => $data['cp'] ?? null,
                    'municipio'  => $data['municipio'] ?? null,
                    'estado'     => $data['estado'] ?? null,
                    'pais'       => $data['pais'] ?? 'MÉXICO',
                    'notas'      => $data['notas'] ?? null,
                ]);

                // 2) desmarcar la razón social predet anterior
                RazonSocial::where('id_cliente', $data['id_cliente'])
                        ->where('predeterminado', 1)
                        ->update(['predeterminado' => 0]);

                // 3) crear la nueva razón social vinculada a esa dirección
                $razon = RazonSocial::create([
                    'nombre'               => $data['nombre'],
                    'id_cliente'           => $data['id_cliente'],
                    'RFC'                  => $data['rfc'],
                    'id_uso_cfdi'          => $data['id_uso_cfdi'],
                    'id_metodo_pago'       => $data['id_metodo_pago'],
                    'id_forma_pago'        => $data['id_forma_pago'],
                    'id_regimen_fiscal'    => $data['id_regimen_fiscal'],
                    'saldo'                => 0,
                    'limite_credito'       => 0,
                    'dias_credito'         => $data['dias_credito'] ?? 0,
                    'id_direccion_facturacion' => $direccion->id_direccion,
                    'predeterminado'       => 1,
                ]);            
            });

            return response()->json([
                'razon_social' => $razon,
                'direccion'    => $direccion,
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        } catch (\Throwable $e) {
            // Para depuración, devuelve el mensaje de error en JSON
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
