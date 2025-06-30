<?php

namespace App\Http\Controllers;

use App\Models\RazonSocial;

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
            'razon'   => $razon->load('direccion_facturacion.colonia', 'direccion_facturacion.estado', 'direccion_facturacion.ciudad'),
        ]);
    }

}
