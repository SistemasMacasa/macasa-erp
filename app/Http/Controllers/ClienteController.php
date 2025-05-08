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

    public function view($id)
{
    $cliente = Cliente::with(['contactos', 'razonesSociales', 'direccionesEntrega', 'notas'])
        ->findOrFail($id);
    return view('clientes.view', compact('cliente'));
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
        if ($request->input('tipo') == 'fisica') 
        { // SI ES FISICA
            $rules = [
                /* === Cuenta Personal === */
                'nombre'        => 'required|string|max:60',
                'apellido_p'    => 'required|string|max:27',
                'apellido_m'    => 'nullable|string|max:27',
                'id_vendedor'   => 'required|integer',
            
                // Datos personales …
                'contacto.0.email'       => 'nullable|email|max:120',
                'contacto.0.telefono*'   => 'nullable|digits:10',
                'contacto.0.celular*'    => 'nullable|digits:10',
                'contacto.0.ext*'        => 'nullable|digits_between:1,6',
            ];
            $request->validate($rules);
            // Guardar en la base de datos
            try
            {
                 // GUARDAR CUENTA EJE PERSONAL
                 $cliente = Cliente::create([
                    'nombre'      => $request->input('nombre'),
                    'apellido_p'=> $request->input('apellido_p'),
                    'apellido_m'=> $request->input('apellido_m'),
                    'ciclo_venta' => $request->input('ciclo_venta'),
                    'estatus'     => $request->input('estatus'),
                    'tipo'        => $request->input('tipo'),
                    'sector'      => $request->input('sector'),
                    'segmento'    => $request->input('segmento'),
                    'id_vendedor' => $request->filled('id_vendedor')
                        ? $request->input('id_vendedor')
                        : null,
                ]);

                // Crear contacto principal (obligatorio)
                $contactos = $request->input('contacto', []);
                $contacto = $contactos[0] ?? null; // solo un contacto
                  
                        // Normaliza phones/exts
                        foreach (range(1,5) as $n) {
                            $contacto["telefono$n"] = digits_only($contacto["telefono$n"] ?? null);
                            $contacto["celular$n"]  = digits_only($contacto["celular$n"]  ?? null);
                            $contacto["ext$n"]      = digits_only($contacto["ext$n"]      ?? null);
                        }
                    
                        Contacto::create([
                            'id_cliente' => $cliente->id_cliente,
                            'nombre'     => $request->input('nombre'),
                            'apellido_p' => $request->input('apellido_p') ?? null,
                            'apellido_m' => $request->input('apellido_m') ?? null,
                            'email'      => $contacto['email']      ?? null,
                            'puesto'     => $contacto['puesto']     ?? null,
                            'genero'     => $contacto['genero']     ?? null,
                    
                            // teléfono/ext celular limpias
                            'telefono1'  => $contacto['telefono1'],  'ext1' => $contacto['ext1'],
                            'telefono2'  => $contacto['telefono2'],  'ext2' => $contacto['ext2'],
                            'telefono3'  => $contacto['telefono3'],  'ext3' => $contacto['ext3'],
                            'telefono4'  => $contacto['telefono4'],  'ext4' => $contacto['ext4'],
                            'telefono5'  => $contacto['telefono5'],  'ext5' => $contacto['ext5'],
                            'celular1'   => $contacto['celular1'],   // …igual 1-5
                            'celular2'   => $contacto['celular2'],
                            'celular3'   => $contacto['celular3'],
                            'celular4'   => $contacto['celular4'],
                            'celular5'   => $contacto['celular5'],
                        ]);
                    
            }
            catch (Exception $e){
                return redirect('')->with('error', $e->getMessage());
            }
            

        } else // SI ES EMPRESA
        {
            $rules = [
                /* -------- Cuenta -------- */
                'nombre'      => ['required','string','max:100'],
                'sector'      => ['required','string','max:100'],
                'segmento'    => ['required','string','max:100'],
                'id_vendedor' => ['required','integer'],
            
                /* -------- Contacto(s) ---- */
                'contacto.*.nombre'      => ['nullable','string','max:60'],
                'contacto.*.apellido_p'  => ['nullable','string','max:27'],
                'contacto.*.apellido_m'  => ['nullable','string','max:27'],
                'contacto.*.email'       => ['nullable','email','max:120'],
                'contacto.*.puesto'      => ['nullable','string','max:60'],
            
                // Teléfonos / celulares  –  10 dígitos exactos o nulo
                'contacto.*.telefono*'   => ['nullable','string'],
                'contacto.*.celular*'    => ['nullable','string'],
            
                // Extensiones 1-6 dígitos
                'contacto.*.ext*'        => ['nullable','regex:/^\d{1,7}$/'],
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

                // Crear contacto principal (obligatorio)
                $contactos = $request->input('contacto', []);  // trae un array de arrays: [ 0 => [...], 1 => [...], ... ]
                if (count($contactos) > 0) 
                {
                    foreach ($contactos as $cont) 
                    {
                        if (empty($cont['nombre'])) continue;        // ficha vacía
                    
                        // Normaliza phones/exts
                        foreach (range(1,5) as $n) {
                            $cont["telefono$n"] = digits_only($cont["telefono$n"] ?? null);
                            $cont["celular$n"]  = digits_only($cont["celular$n"]  ?? null);
                            $cont["ext$n"]      = digits_only($cont["ext$n"]      ?? null);
                        }
                    
                        Contacto::create([
                            'id_cliente' => $cliente->id_cliente,
                            'nombre'     => $cont['nombre'],
                            'apellido_p' => $cont['apellido_p'] ?? null,
                            'apellido_m' => $cont['apellido_m'] ?? null,
                            'email'      => $cont['email']      ?? null,
                            'puesto'     => $cont['puesto']     ?? null,
                            'genero'     => $cont['genero']     ?? null,
                    
                            // teléfono/ext celular limpias
                            'telefono1'  => $cont['telefono1'],  'ext1' => $cont['ext1'],
                            'telefono2'  => $cont['telefono2'],  'ext2' => $cont['ext2'],
                            'telefono3'  => $cont['telefono3'],  'ext3' => $cont['ext3'],
                            'telefono4'  => $cont['telefono4'],  'ext4' => $cont['ext4'],
                            'telefono5'  => $cont['telefono5'],  'ext5' => $cont['ext5'],
                            'celular1'   => $cont['celular1'],   // …igual 1-5
                            'celular2'   => $cont['celular2'],
                            'celular3'   => $cont['celular3'],
                            'celular4'   => $cont['celular4'],
                            'celular5'   => $cont['celular5'],
                        ]);
                    }
                    
                }

                // GUARDAR DATOS DE ENTREGA - CONTACTO + DIRECCION (opcional)

                // if (!empty($request->direcciones_entrega)) 
                // {
                //     foreach ($request->direcciones_entrega as $dir) 
                //     {
                //         // si no hay calle, salto
                //         if (empty($dir['calle'])) {
                //             continue;
                //         }

                //         // creo la dirección
                //         $direccionEntrega = Direccion::create([
                //             'id_cliente' => $cliente->id_cliente,
                //             'tipo'      => 'entrega',
                //             'nombre'    => $dir['nombre'] ?? null,
                //             'calle'     => $dir['calle'],
                //             'num_ext'   => $dir['num_ext'] ?? null,
                //             'num_int'   => $dir['num_int'] ?? null,
                //             'colonia'   => $dir['colonia'] ?? null,
                //             'id_ciudad' => $dir['id_ciudad'] ?? null,
                //             'id_estado' => $dir['id_estado'] ?? null,
                //             'id_pais'   => $dir['id_pais'] ?? null,
                //             'cp'        => $dir['cp'] ?? null,
                //         ]);

                //         // si viene el contacto, lo creo aquí mismo usando la propiedad ->id_direccion
                //         if (!empty($dir['contacto']['nombre'])) 
                //         {
                //             Contacto::create([
                //                 'id_cliente'  => $cliente->id_cliente,
                //                 'nombre'      => $dir['contacto']['nombre'],
                //                 'apellido_p'  => $dir['contacto']['apellido_p'] ?? null,
                //                 'apellido_m'  => $dir['contacto']['apellido_m'] ?? null,
                //                 'email'       => $dir['contacto']['email'] ?? null,
                //                 'telefono1'   => $dir['contacto']['telefono1'] ?? null,
                //                 'ext1'        => $dir['contacto']['ext1'] ?? null,
                //                 'telefono2'   => $dir['contacto']['telefono2'] ?? null,
                //                 'ext2'        => $dir['contacto']['ext2'] ?? null,
                //                 'telefono3'   => $dir['contacto']['telefono3'] ?? null,
                //                 'ext3'        => $dir['contacto']['ext3'] ?? null,
                //                 'telefono4'   => $dir['contacto']['telefono4'] ?? null,
                //                 'ext4'        => $dir['contacto']['ext4'] ?? null,
                //                 'telefono5'   => $dir['contacto']['telefono5'] ?? null,
                //                 'ext5'        => $dir['contacto']['ext5'] ?? null,
                //                 'id_direccion_entrega' => $direccionEntrega->id_direccion,
                //             ]);
                //         }
                //     }
                // }

                //  GUARDAR DATOS DE FACTURACION - Razón Social + Dirección (opcional)
                // if (!empty($request->razones)) 
                // {
                //     foreach ($request->razones as $razon) 
                //     {
                //         if (empty($razon['nombre'])) {
                //             continue;
                //         }

                //         // dirección de facturación (si existe)
                //         $direccionFacturacion = null;
                //         if (!empty($razon['direccion']['calle'])) 
                //         {
                //             $direccionFacturacion = Direccion::create([
                //                 'id_cliente' => $cliente->id_cliente,
                //                 'tipo'       => 'facturacion',
                //                 'calle'      => $razon['direccion']['calle'],
                //                 'num_ext'    => $razon['direccion']['num_ext'] ?? null,
                //                 'num_int'    => $razon['direccion']['num_int'] ?? null,
                //                 'colonia'    => $razon['direccion']['colonia'] ?? null,
                //                 'id_ciudad'  => $razon['direccion']['id_ciudad'] ?? null,
                //                 'id_estado'  => $razon['direccion']['id_estado'] ?? null,
                //                 'id_pais'    => $razon['direccion']['id_pais'] ?? null,
                //                 'cp'         => $razon['direccion']['cp'] ?? null,
                //             ]);
                //         }

                //         // creo la razón social, usando ->id_direccion si existe
                //         RazonSocial::create([
                //             'id_cliente'        => $cliente->id_cliente,
                //             'nombre'            => $razon['nombre'],
                //             'rfc'               => $razon['rfc'],
                //             'id_metodo_pago'    => $razon['id_metodo_pago'] ?? null,
                //             'id_forma_pago'     => $razon['id_forma_pago'] ?? null,
                //             'id_uso_cfdi'       => $razon['$id_uso_cfdi'] ?? null,
                //             'id_regimen_fiscal' => $razon['id_regimen_fiscal'] ?? null,
                //             'id_direccion_facturacion' => $direccionFacturacion
                //                 ? $direccionFacturacion->id_direccion
                //                 : null,
                //         ]);
                //     }
                // }

                return redirect('/clientes')->with('success', 'Cliente creado correctamente');
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()]);
            }
        }
        // Redirigir a la lista
        return redirect('clientes.index')->with('success', 'Cliente creado correctamente');
    }

}

/**
 * Mantiene sólo dígitos (útil para teléfonos/extensiones)
 */
function digits_only(?string $v): ?string
{
    if ($v === null)   return null;
    $clean = preg_replace('/\D/', '', $v);
    return $clean === '' ? null : $clean;
}
