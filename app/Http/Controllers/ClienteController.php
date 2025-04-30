<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Cliente;
use App\Models\Contacto;
use App\Models\Direccion;
use App\Models\Ciudad;
use App\Models\Estado;
use App\Models\RazonSocial;
use App\Models\FormaPago;
use App\Models\MetodoPago;
use App\Models\UsoCfdi;
use App\Models\RegimenFiscal;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::with(['primerContacto'])
            ->paginate(25);

        return view('clientes.index', compact('clientes'));
    }

    public function create(Request $request)
    {
        $tipo = $request->input('tipo', 'moral'); // por defecto: moral
        $vendedores = Usuario::whereNull('id_cliente')->get(); // usuarios internos
        $metodos_pago = MetodoPago::all();
        $formas_pago = FormaPago::all();
        $usos_cfdi = UsoCfdi::all();
        $razones_sociales = RazonSocial::all();
        $ciudades = Ciudad::all();
        $estados = Estado::all();
        $paises = [
            'MX' => 'México',
            'US' => 'Estados Unidos',
            'CA' => 'Canadá',
            // Agrega más países según sea necesario
        ];
        $regimen_fiscales = RegimenFiscal::all();
        return view('clientes.create', compact('vendedores', 'metodos_pago', 'formas_pago', 'usos_cfdi', 'razones_sociales', 'ciudades', 'estados', 'paises', 'tipo', 'regimen_fiscales'));
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
                'id_vendedor' => 'nullable|integer',

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
            try {
                $cliente = Cliente::create([
                    'nombre' => $request->input('nombre'),
                    'apellido_p' => "" ?? null, // No se usa en cuentas empresariales
                    'apellido_m' => "" ?? null, // No se usa en cuentas empresariales
                    'ciclo_venta' => $request->input('ciclo_venta'),
                    'estatus' => $request->input('estatus'),
                    'tipo' => $request->input('tipo'),
                    'sector' => $request->input('sector'),
                    'segmento' => $request->input('segmento'),
                    'id_vendedor' => $request->filled('id_vendedor') ? $request->id_vendedor : null,
                ]);

                // Crear contacto (opcional)
                if (!empty($request->contacto['nombre'])) {

                    $c = $request->contacto;   // array con todos los campos

                    Contacto::create([
                        'id_cliente' => $cliente->id_cliente,
                        'nombre' => $c['nombre'] ?? null,
                        'apellido_p' => $c['apellido_p'] ?? null,
                        'apellido_m' => $c['apellido_m'] ?? null,
                        'email' => $c['email'] ?? null,
                        'puesto' => $c['puesto'] ?? null,
                        'telefono1' => $c['telefono1'] ?? null,
                        'ext1' => $c['ext1'] ?? null,
                        'telefono2' => $c['telefono2'] ?? null,
                        'ext2' => $c['ext2'] ?? null,
                    ]);
                }


                // Crear direcciones de entrega (opcional)
                if (!empty($request->direcciones_entrega)) {
                    foreach ($request->direcciones_entrega as $dir) {
                        if (!empty($dir['calle'])) {
                            Direccion::create([
                                'tipo' => 'entrega',
                                'nombre' => $dir['nombre'] ?? null,
                                'calle' => $dir['calle'] ?? null,
                                'num_ext' => $dir['num_ext'] ?? null,
                                'num_int' => $dir['num_int'] ?? null,
                                'colonia' => $dir['colonia'] ?? null,
                                'id_ciudad' => $dir['id_ciudad'] ?? null,
                                'id_estado' => $dir['id_estado'] ?? null,
                                'id_pais' => $dir['id_pais'] ?? null,
                                'cp' => $dir['cp'] ?? null,
                            ]);
                        }
                    }

                }

                // Crear datos de facturación - Razón Social + Dirección (opcional)
                if (!empty($request->razones)) {
                    foreach ($request->razones as $razon) {
                        if (empty($razon['nombre'])) {
                            continue; // Si no hay nombre, saltar esta razón social
                        }

                        $razon_social = RazonSocial::create([
                            'id_cliente' => $cliente->id_cliente,
                            'nombre' => $razon['nombre'],
                            'rfc' => $razon['rfc'],
                            'id_metodo_pago' => $razon['metodo_pago'] ?? null,
                            'id_forma_pago' => $razon['forma_pago'] ?? null,
                            'id_regimen_fiscal' => $razon['regimen_fiscal'] ?? null,
                            'limite_credito' => $razon['limite_credito'] ?? null,
                            'dias_credito' => $razon['dias_credito'] ?? null,
                            'saldo' => 0
                        ]);

                        // Crear dirección dentro de razón social (opcional)
                        if (!empty($razon['direccion']['calle'])) {
                            Direccion::create([
                                'id_cliente' => $cliente->id_cliente,
                                'calle' => $razon['direccion']['calle'] ?? null,
                                'num_ext' => $razon['direccion']['num_ext'] ?? null,
                                'num_int' => $razon['direccion']['num_int'] ?? null,
                                'colonia' => $razon['direccion']['colonia'] ?? null,
                                'id_ciudad' => $razon['direccion']['id_ciudad'] ?? null,
                                'id_estado' => $razon['direccion']['id_estado'] ?? null,
                                'id_pais' => $razon['direccion']['id_pais'] ?? null,
                                'cp' => $razon['direccion']['cp']
                            ]);
                        }
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
