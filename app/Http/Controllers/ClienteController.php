<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Usuario;
use App\Models\Contacto;
use App\Models\Direccion;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::with('vendedor')->get();
        //dd(Cliente::all());
        return view('clientes.index', compact('clientes'));
    }

    public function create(Request $request)
    {
        $tipo = $request->input('tipo', 'moral'); // por defecto: moral
        $vendedores = Usuario::whereNull('id_cliente')->get(); // usuarios internos
        $fuentesContacto = [];
        $catalogoEntregas = [];
        $catalogoCondicionesPago = [];
        return view('clientes.create', compact('vendedores', 'fuentesContacto', 'catalogoEntregas', 'catalogoCondicionesPago', 'tipo'));
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        $vendedores = Usuario::whereNull('id_cliente')->get(); // usuarios internos
        return view('clientes.edit', compact('cliente', 'vendedores'));
    }

    public function update(Request $request, $id)
    {
        // 1) Validar datos
        $request->validate([
            'nombre' => 'required|max:100',
            'apellido' => 'nullable|max:100',
            'estatus' => 'required',
            'tipo' => 'required',
            'id_vendedor' => 'required|integer'
        ]);

        // 2) Buscar el registro existente
        $cliente = Cliente::findOrFail($id);
        // 3) Actualizar usando mass assignment (requiere $fillable en el modelo)
        $cliente->update($request->all());
        return redirect('clientes')->with('success', 'Cliente actualizado correctamente');
    }

    public function destroy($id)
    {
        // 1) Buscar el registro existente
        $cliente = Cliente::findOrFail($id);
        // 2) Eliminar el registro
        $cliente->delete();
        return redirect('clientes')->with('success', 'Cliente eliminado correctamente');
    }

    public function store(Request $request)
    {
        if ($request->input('tipo') == 'fisica') {
            $request->validate([
                'nombre' => 'required|max:100',
                'apellido' => 'required|max:100',
                'rfc' => 'required|max:13',
                'curp' => 'required|max:18',
                'razon_social' => 'nullable|max:100',
                'nombre_comercial' => 'nullable|max:100'
            ]);

            try {
                $cliente = Cliente::create([
                    'nombre' => $request->input('nombre'),
                    'apellido' => $request->input('apellido'),
                    'rfc' => $request->input('rfc'),
                    'curp' => $request->input('curp'),
                    'razon_social' => $request->input('razon_social'),
                    'nombre_comercial' => $request->input('nombre_comercial'),
                    'sector' => $request->input('sector'),
                    'segmento' => $request->input('segmento'),
                    'tipo' => $request->input('tipo'),
                    'estatus' => $request->input('estatus'),
                    'id_vendedor' => $request->input('id_vendedor')
                ]);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()]);
            }

        } else // SI ES EMPRESA
        {
            $rules = [
                'nombre' => 'required|max:100',
                'sector' => 'required|max:100',
                'segmento' => 'required|max:100',
                'id_vendedor'=> 'required|integer',

                // Contacto
                'contacto.nombre' => 'nullable|max:100',
                'contacto.apellido_paterno' => 'nullable|max:100',
                'contacto.apellido_materno' => 'nullable|max:100',
                'contacto.email' => 'nullable|email|max:100',
                'contacto.telefono' => 'nullable|max:100',
                'contacto.ext' => 'nullable|max:100',
                'contacto.telefono2' => 'nullable|max:100',
                'contacto.ext2' => 'nullable|max:100',
                'contacto.puesto' => 'nullable|max:100',

                // Direcciones de entrega
                'direcciones_entrega.*.calle' => 'nullable|max:100',
                'direcciones_entrega.*.num_ext' => 'nullable|max:100',
                'direcciones_entrega.*.num_int' => 'nullable|max:100',
                'direcciones_entrega.*.colonia' => 'nullable|max:100',
                'direcciones_entrega.*.id_ciudad' => 'nullable|max:100',
                'direcciones_entrega.*.id_estado' => 'nullable|max:100',
                'direcciones_entrega.*.id_pais' => 'nullable|max:100',
                'direcciones_entrega.*.cp' => 'nullable|max:100',

                // Razones sociales
                'razones.*.nombre' => 'nullable|max:100',
                'razones.*.rfc' => 'nullable|max:13',
                'razones.*.metodo_pago' => 'nullable|max:100',
                'razones.*.forma_pago' => 'nullable|max:100',
                'razones.*.uso_cfdi' => 'nullable|max:100',
                'razones.*.regimen_fiscal' => 'nullable|max:100',

                // Dirección dentro de razón social
                'razones.*.direccion.calle' => 'nullable|max:100',
                'razones.*.direccion.num_ext' => 'nullable|max:100',
                'razones.*.direccion.num_int' => 'nullable|max:100',
                'razones.*.direccion.colonia' => 'nullable|max:100',
                'razones.*.direccion.id_ciudad' => 'nullable|max:100',
                'razones.*.direccion.id_estado' => 'nullable|max:100',
                'razones.*.direccion.id_pais' => 'nullable|max:100',
                'razones.*.direccion.cp' => 'nullable|max:100',
            ];
            $request->validate($rules);

            // Guardar en la base de datos
            try 
            {
                $cliente = Cliente::create([
                    'nombre' => $request->input('nombre'),
                    'sector' => $request->input('sector'),
                    'segmento' => $request->input('segmento'),
                    'estatus' => 'Activo',
                    'id_vendedor' => $request->input('id_vendedor'),
                ]);

                // Crear contacto (opcional)
                if (!empty($request->contacto['nombre'])) {
                    Contacto::create([
                        'id_cliente' => $cliente->id,
                        'nombre' => $request->input('contacto.nombre'),
                        'apellido_paterno' => $request->input('contacto.apellido_paterno'),
                        'apellido_materno' => $request->input('contacto.apellido_materno'),
                        'email' => $request->input('contacto.email'),
                        'telefono' => $request->input('contacto.telefono'),
                        'ext' => $request->input('contacto.ext'),
                        'telefono2' => $request->input('contacto.telefono2'),
                        'ext2' => $request->input('contacto.ext2'),
                        'puesto' => $request->input('contacto.puesto')
                    ]);
                }

                // Crear direcciones de entrega (opcional)
                if (!empty($request->direcciones_entrega)) {
                    foreach ($request->direcciones_entrega as $direccion) {
                        Direccion::create([
                            'id_cliente' => $cliente->id,
                            'calle' => $direccion['calle'],
                            'num_ext' => $direccion['num_ext'],
                            'num_int' => $direccion['num_int'],
                            'colonia' => $direccion['colonia'],
                            'id_ciudad' => $direccion['id_ciudad'],
                            'id_estado' => $direccion['id_estado'],
                            'id_pais' => $direccion['id_pais'],
                            'cp' => $direccion['cp']
                        ]);
                    }
                }


                return redirect('/clientes')->with('success', 'Cliente creado correctamente');
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()]);
            }


        }



        // Redirigir a la lista
        return redirect('clientes.index')->with('success', 'Cliente creado correctamente');
    }

}
