<?php

namespace App\Http\Controllers;

use App\Models\RazonSocial;
use App\Models\UsoCfdi;
use App\Models\FormaPago;
use App\Models\MetodoPago;
use App\Models\RegimenFiscal;
use App\Models\Direccion;
use App\Models\Pais;
use App\Models\Colonia;
use App\Models\Estado;
use App\Models\Ciudad;

use Illuminate\Http\Request;

class RazonSocialController extends Controller
{
    public function seleccionar(Request $request, $id)
    {
        $razon = RazonSocial::findOrFail($id);

        // Desmarcar todas las razones sociales del cliente
        RazonSocial::where('id_cliente', $razon->id_cliente)
            ->update(['predeterminado' => 0]);

        // Marcar esta como predeterminada
        $razon->predeterminado = 1;
        $razon->save();

        return response()->json([
            'success' => true,
            'mensaje' => 'Razón social seleccionada como predeterminada',
            'razon' => $razon->load('direccion_facturacion.colonia', 'direccion_facturacion.estado', 'direccion_facturacion.ciudad'),
        ]);
    }

    public function edit($id)
    {
        $razon = RazonSocial::with([
            'direccion_facturacion.colonia',
            'direccion_facturacion.pais',
            'direccion_facturacion.estado',
            'direccion_facturacion.ciudad',
            'uso_cfdi',
            'forma_pago',
            'metodo_pago',
            'regimen_fiscal'
        ])->findOrFail($id);

        return view('razones_sociales.edit', [
            'razon' => $razon,
            'usosCfdi' => UsoCfdi::all(),
            'formasPago' => FormaPago::all(),
            'metodosPago' => MetodoPago::all(),
            'regimenesFiscales' => RegimenFiscal::all(),
            'paises' => Pais::all(),
        ]);
    }


    public function update(Request $request, $id)
    {
        $razon = RazonSocial::with('direccion_facturacion')->findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'RFC' => 'required|string|max:13',
            'notas_facturacion' => 'nullable|string',

            // Dirección
            'calle' => 'required|string|max:100',
            'num_ext' => 'required|string|max:20',
            'num_int' => 'nullable|string|max:20',
            'id_colonia' => 'required|exists:colonias,id_colonia',
            'id_ciudad' => 'required|exists:ciudades,id_ciudad',
            'id_estado' => 'required|exists:estados,id_estado',
            'id_pais' => 'required|exists:paises,id_pais',
            'cp' => 'required|digits:5',
        ]);

        // Actualizar razón social
        $razon->update([
            'nombre' => $request->nombre,
            'RFC' => $request->RFC,
            'notas_facturacion' => $request->notas_facturacion,
        ]);

        // Actualizar dirección de facturación relacionada
        $razon->direccion_facturacion->update([
            'calle' => $request->calle,
            'num_ext' => $request->num_ext,
            'num_int' => $request->num_int,
            'cp' => $request->cp,
            'id_colonia' => $request->id_colonia,
            'id_ciudad' => $request->id_ciudad,
            'id_estado' => $request->id_estado,
            'id_pais' => $request->id_pais,
        ]);

        return redirect()->route('cotizaciones.create', $razon->id_cliente)
            ->with('success', 'Razón social actualizada correctamente.');
    }





}
