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

    public function edit($id)
    {
        $razon = RazonSocial::with('direccion_facturacion.colonia', 'uso_cfdi', 'forma_pago', 'metodo_pago', 'regimen_fiscal')
                            ->findOrFail($id);

        return view('razones_sociales.edit', compact('razon'));
    }

    public function update(Request $request, $id)
    {
        $razon = RazonSocial::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'RFC'    => 'required|string|max:13',
            'notas_facturacion' => 'nullable|string',
            // y validaciones extra para dirección si corresponde
        ]);

        $razon->update($request->only(['nombre', 'RFC', 'notas_facturacion']));
        
        // Opcional: Actualizar dirección relacionada

        return redirect()->route('cotizaciones.create', $razon->id_cliente)
                        ->with('success', 'Razón social actualizada correctamente.');
    }


}
