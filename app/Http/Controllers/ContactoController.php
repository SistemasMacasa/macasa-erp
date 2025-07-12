<?php

namespace App\Http\Controllers;
use App\Models\Contacto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate; // opcional, si usas policies
use App\Http\Requests\ContactoRequest; // opcional, si tienes un FormRequest
use Illuminate\Http\Request;


class ContactoController extends Controller
{
    // app/Http/Controllers/ContactoController.php
    public function seleccionar(Request $request, Contacto $contacto)
    {
        // 1) Seguridad rápida: que sea de ese cliente
        // $this->authorize('update', $contacto->cliente);   // opcional / policy

        DB::transaction(function () use ($contacto) {

            // 2) Apagar el que antes era predeterminado
            Contacto::where('id_cliente', $contacto->id_cliente)
                ->whereNotNull('id_direccion_entrega')
                ->where('predeterminado', 1)
                ->update(['predeterminado' => 0]);

            // 3) Marcar éste
            $contacto->update(['predeterminado' => 1]);
        });

        /* 4)  Payload idéntico al alta rápida
               (reutiliza un Resource si lo prefieres) */
        $dir = $contacto->direccion_entrega->load(['colonia', 'ciudad', 'estado']);

        return response()->json([
            'success' => true,
            'entrega' => [
                'id_direccion_entrega' => $dir->id_direccion,
                'contacto' => [
                    'id_contacto' => $contacto->id_contacto,
                    'nombre' => $contacto->nombreCompleto,
                    'telefono' => $contacto->telefono1,
                    'ext' => $contacto->ext1,
                    'email' => $contacto->email,
                    'notas_entrega' => $contacto->notas_entrega
                ],
                'direccion' => [
                    'nombre' => $dir->nombre,
                    'calle' => $dir->calle,
                    'num_ext' => $dir->num_ext,
                    'num_int' => $dir->num_int,
                    'colonia' => $dir->colonia->d_asenta,
                    'ciudad' => $dir->ciudad->n_mnpio,
                    'estado' => $dir->estado->d_estado,
                    'pais' => 'México',
                    'cp' => $dir->cp,
                ],
            ],
        ]);
    }

    public function edit($id)
    {
        $contacto = Contacto::with('direccion_entrega.colonia', 'direccion_entrega.ciudad', 'direccion_entrega.estado', 'direccion_entrega.pais')
                            ->findOrFail($id);

        return view('contactos.edit', compact('contacto'));
    }

    public function update(Request $request, $id)
    {
        $contacto = Contacto::findOrFail($id);

        $request->validate([
            'nombre'         => 'required|string|max:255',
            'telefono1'      => 'nullable|string',
            'email'          => 'nullable|email',
            'notas_entrega'  => 'nullable|string',
            // validaciones para dirección si se edita
        ]);

        $contacto->update($request->only(['nombre', 'telefono1', 'email', 'notas_entrega']));

        return redirect()->route('cotizaciones.create', $contacto->id_cliente)
                        ->with('success', 'Contacto actualizado correctamente.');
    }


}
