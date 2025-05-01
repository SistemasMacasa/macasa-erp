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
        $tipo           = $request->input('tipo', 'moral'); // por defecto: moral
        $vendedores     = Usuario::whereNull('id_cliente')->get(); // usuarios internos
        $metodos_pago   = MetodoPago::pluck('nombre', 'id_metodo_pago');
        $formas_pago    = FormaPago::pluck('nombre', 'id_forma_pago');
        $usos_cfdi      = UsoCfdi::pluck('nombre', 'id_uso_cfdi');
        $ciudades       = Ciudad::pluck('nombre', 'id_ciudad');
        $estados        = Estado::pluck('nombre', 'id_estado');
        $paises = [
            '1' => 'México',
            '2' => 'Estados Unidos',
            '3' => 'Canadá',
            // Agrega más países según sea necesario
        ];
        $regimen_fiscales = RegimenFiscal::pluck('nombre', 'id_regimen_fiscal');
        return view('clientes.create', compact('vendedores', 'metodos_pago', 'formas_pago', 'usos_cfdi', 'ciudades', 'estados', 'paises', 'tipo', 'regimen_fiscales'));
    }

    public function edit($id)
    {
        $cliente    = Cliente::findOrFail($id);
        $vendedores = Usuario::whereNull('id_cliente')->get(); // usuarios internos
        return view('clientes.edit', compact('cliente', 'vendedores'));
    }

    public function update(Request $request, $id)
    {
        // 1) Validar datos
        $request->validate([
            'nombre'    => 'required|max:100',
            'apellido'  => 'nullable|max:100',
            'estatus'   => 'required',
            'tipo'      => 'required',
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
        if ($request->input('tipo') == 'fisica') { // SI ES FISICA
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
                //DATOS CUENTA EJE
                'nombre' => 'required|max:100',
                'sector' => 'required|max:100',
                'segmento' => 'required|max:100',
                'id_vendedor' => 'nullable|integer',

                // CONTACTO PRINCIPAL
                'contacto.nombre' => 'nullable|max:100',
                'contacto.apellido_p' => 'nullable|max:100',
                'contacto.apellido_m' => 'nullable|max:100',
                'contacto.email' => 'nullable|email|max:100',
                'contacto.puesto' => 'nullable|max:100',
                'contacto.telefono1' => 'nullable|max:100',
                'contacto.ext1' => 'nullable|max:10',
                'contacto.telefono2' => 'nullable|max:100',
                'contacto.ext2' => 'nullable|max:10',

                // DATOS DE ENTREGA
                // Contacto que recibe la mercancía
                'direcciones_entrega.*.contacto.nombre' => 'nullable|max:100',
                'direcciones_entrega.*.contacto.apellido_p' => 'nullable|max:100',
                'direcciones_entrega.*.contacto.apellido_m' => 'nullable|max:100',
                'direcciones_entrega.*.contacto.telefono' => 'nullable|max:100',
                'direcciones_entrega.*.contacto.ext' => 'nullable|max:10',
                'direcciones_entrega.*.contacto.email' => 'nullable|email|max:100',

                // Dirección de entrega
                'direcciones_entrega.*.nombre' => 'nullable|max:100',
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
                'razones.*.id_metodo_pago' => 'nullable|integer',
                'razones.*.id_forma_pago' => 'nullable|max:100',
                'razones.*.id_uso_cfdi' => 'nullable|max:100',
                'razones.*.id_regimen_fiscal' => 'nullable|max:100',

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
            // Para crear una cuenta eje, se necesitan los datos de la cuenta y al menos un contacto.
            try {
                // GUARDAR CUENTA EJE EMPRESARIAL
                $cliente = Cliente::create([
                    'nombre'      => $request->input('nombre'),
                    'ciclo_venta' => $request->input('ciclo_venta'),
                    'estatus'     => $request->input('estatus'),
                    'tipo'        => $request->input('tipo'),
                    'sector'      => $request->input('sector'),
                    'segmento'    => $request->input('segmento'),
                    'id_vendedor' => $request->filled('id_vendedor')
                        ? $request->input('id_vendedor')
                        : null,
                ]);

                // Crear contacto principal (oblogatorio)
                if (!empty($request->contacto['nombre'])) {

                    $contacto = $request->contacto;   // array con todos los campos

                    Contacto::create([
                        'id_cliente'   => $cliente->id_cliente,
                        'nombre'       => $contacto['nombre'] ?? null,
                        'apellido_p'   => $contacto['apellido_p'] ?? null,
                        'apellido_m'   => $contacto['apellido_m'] ?? null,
                        'email'        => $contacto['email'] ?? null,
                        'puesto'       => $contacto['puesto'] ?? null,
                        'telefono1'    => $contacto['telefono1'] ?? null,
                        'ext1'         => $contacto['ext1'] ?? null,
                        'telefono2'    => $contacto['telefono2'] ?? null,
                        'ext2'         => $contacto['ext2'] ?? null,
                    ]);
                }

                //GUARDAR DATOS DE ENTREGA - CONTACTO + DIRECCION (opcional)

                if (!empty($request->direcciones_entrega)) 
                {
                    foreach ($request->direcciones_entrega as $dir) 
                    {
                        // si no hay calle, salto
                        if (empty($dir['calle'])) {
                            continue;
                        }

                        // creo la dirección
                        $direccionEntrega = Direccion::create([
                            'id_cliente' => $cliente->id_cliente,
                            'tipo'      => 'entrega',
                            'nombre'    => $dir['nombre'] ?? null,
                            'calle'     => $dir['calle'],
                            'num_ext'   => $dir['num_ext'] ?? null,
                            'num_int'   => $dir['num_int'] ?? null,
                            'colonia'   => $dir['colonia'] ?? null,
                            'id_ciudad' => $dir['id_ciudad'] ?? null,
                            'id_estado' => $dir['id_estado'] ?? null,
                            'id_pais'   => $dir['id_pais'] ?? null,
                            'cp'        => $dir['cp'] ?? null,
                        ]);

                        // si viene el contacto, lo creo aquí mismo usando la propiedad ->id_direccion
                        if (!empty($dir['contacto']['nombre'])) 
                        {
                            Contacto::create([
                                'id_cliente'  => $cliente->id_cliente,
                                'nombre'      => $dir['contacto']['nombre'],
                                'apellido_p'  => $dir['contacto']['apellido_p'] ?? null,
                                'apellido_m'  => $dir['contacto']['apellido_m'] ?? null,
                                'email'       => $dir['contacto']['email'] ?? null,
                                'telefono1'   => $dir['contacto']['telefono'] ?? null,
                                'ext1'        => $dir['contacto']['ext'] ?? null,
                                'id_direccion_entrega' => $direccionEntrega->id_direccion,
                            ]);
                        }
                    }
                }

                // GUARDAR DATOS DE FACTURACION - Razón Social + Dirección (opcional)
                if (!empty($request->razones)) 
                {
                    foreach ($request->razones as $razon) 
                    {
                        if (empty($razon['nombre'])) {
                            continue;
                        }

                        // dirección de facturación (si existe)
                        $direccionFacturacion = null;
                        if (!empty($razon['direccion']['calle'])) 
                        {
                            $direccionFacturacion = Direccion::create([
                                'id_cliente' => $cliente->id_cliente,
                                'tipo'       => 'facturacion',
                                'calle'      => $razon['direccion']['calle'],
                                'num_ext'    => $razon['direccion']['num_ext'] ?? null,
                                'num_int'    => $razon['direccion']['num_int'] ?? null,
                                'colonia'    => $razon['direccion']['colonia'] ?? null,
                                'id_ciudad'  => $razon['direccion']['id_ciudad'] ?? null,
                                'id_estado'  => $razon['direccion']['id_estado'] ?? null,
                                'id_pais'    => $razon['direccion']['id_pais'] ?? null,
                                'cp'         => $razon['direccion']['cp'] ?? null,
                            ]);
                        }

                        // creo la razón social, usando ->id_direccion si existe
                        RazonSocial::create([
                            'id_cliente'        => $cliente->id_cliente,
                            'nombre'            => $razon['nombre'],
                            'rfc'               => $razon['rfc'],
                            'id_metodo_pago'    => $razon['id_metodo_pago'] ?? null,
                            'id_forma_pago'     => $razon['id_forma_pago'] ?? null,
                            'id_uso_cfdi'       => $razon['$id_uso_cfdi'] ?? null,
                            'id_regimen_fiscal' => $razon['id_regimen_fiscal'] ?? null,
                            'id_direccion_facturacion' => $direccionFacturacion
                                ? $direccionFacturacion->id_direccion
                                : null,
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
