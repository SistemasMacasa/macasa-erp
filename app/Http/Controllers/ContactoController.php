<?php

namespace App\Http\Controllers;
use App\Models\Contacto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate; // opcional, si usas policies
use App\Http\Requests\ContactoRequest; // opcional, si tienes un FormRequest
use Illuminate\Http\Request;
use App\Models\Colonia;
use App\Models\Direccion;
use App\Models\Pais;
use App\Models\Estado;
use App\Models\Ciudad;


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
        $contacto = Contacto::with('direccion_entrega')->findOrFail($id);

        $request->validate([
            'nombre'         => 'required|string|max:255',
            'telefono1'      => 'nullable|string',
            'ext1'           => 'nullable|string|max:10',
            'email'          => 'nullable|email',
            'notas_entrega'  => 'nullable|string',

            // Dirección
            'calle'          => 'nullable|string|max:100',
            'num_ext'        => 'nullable|string|max:20',
            'num_int'        => 'nullable|string|max:20',
            'cp'             => 'nullable|digits:5',
            'id_colonia'     => 'nullable|exists:colonias,id_colonia',
            'id_pais'        => 'nullable|exists:paises,id_pais',
        ]);

        // 🔁 Actualizar datos de contacto
        $contacto->update([
            'nombre'        => $request->nombre,
            'telefono1'     => $request->telefono1,
            'ext1'          => $request->ext1,
            'email'         => $request->email,
            'notas_entrega' => $request->notas_entrega,
        ]);

        // 🚚 Dirección (solo si se proporcionó CP y colonia)
        if ($request->filled(['cp', 'id_colonia'])) 
        {
            $colonia = Colonia::find($request->id_colonia);

            if (!$colonia) {
                return back()->withErrors([
                    'id_colonia' => 'Colonia no encontrada.',
                ])->withInput();
            }

            $ciudad = Ciudad::where('c_mnpio', $colonia->c_mnpio)
                            ->where('c_estado', $colonia->c_estado)
                            ->first();

            $estado = Estado::where('c_estado', $colonia->c_estado)->first();

            if (!$ciudad || !$estado) {
                return back()->withErrors([
                    'id_colonia' => 'No se pudo determinar ciudad o estado desde la colonia seleccionada.',
                ])->withInput();
            }

            $dataDireccion = [
                'calle'      => $request->calle,
                'num_ext'    => $request->num_ext,
                'num_int'    => $request->num_int,
                'cp'         => $request->cp,
                'id_colonia' => $colonia->id_colonia,
                'id_ciudad'  => $ciudad->id_ciudad,
                'id_estado'  => $estado->id_estado,
                'id_pais'    => $request->id_pais,
            ];

            if ($contacto->direccion_entrega) {
                $contacto->direccion_entrega->update($dataDireccion);
            } else {
                $direccion = new Direccion($dataDireccion);
                $direccion->id_cliente = $contacto->id_cliente;
                $direccion->tipo = 'entrega';
                $direccion->save();

                // Asignar dirección al contacto
                $contacto->id_direccion_entrega = $direccion->id_direccion;
                $contacto->save();
            }
        }

        return redirect()->route('cotizaciones.create', $contacto->id_cliente)
                        ->with('success', 'Contacto actualizado correctamente.');
    }



}
