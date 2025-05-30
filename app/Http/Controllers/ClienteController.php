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
use App\Models\Nota;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        // 1) consulta base + relaciones
        $query = Cliente::with(['primerContacto', 'vendedor']);

        /* ---------- Filtros ---------- */

        // Búsqueda global
        if ($term = $request->input('search')) {
            $query->where(function ($q) use ($term) {
                $q->where('nombre', 'like', "%{$term}%")
                   ->orWhere('id_cliente', 'like', "%{$term}%")
                   ->orWhereHas('primerContacto', fn ($q2) =>
                        $q2->where('telefono1', 'like', "%{$term}%"));
            });
        }

        // Ejecutivos
        if ($ejecutivos = $request->input('ejecutivos')) 
        {
            $query->where(function ($q) use ($ejecutivos) 
            {
                // Si se seleccionó "Base General", filtramos por id_vendedor NULL
                if (in_array('base_general', $ejecutivos)) {
                    $q->orWhereNull('id_vendedor');
                }

                // IDs reales de ejecutivos (ignoramos "base_general")
                $ids = array_filter($ejecutivos, fn($id) => $id !== 'base_general');

                if (!empty($ids)) {
                    $q->orWhereIn('id_vendedor', $ids);
                }
            });
        }


        // ⤵️  LOS QUE FALTABAN
        if ($sector = $request->input('sector')) {          // sector = 'privada' | 'gobierno' | ...
            $query->where('sector', $sector);
        }

        if ($segmento = $request->input('segmento')) {      // segmento = 'macasa cuentas especiales' | …
            $query->where('segmento', $segmento);
        }

        // Ciclo de venta
        if ($cycle = $request->input('cycle')) {            // cycle = 'cotizacion' | 'venta'
            $query->where('ciclo_venta', $cycle);
        }

        /* ---------- Orden y paginación ---------- */
        $query->orderBy(
            $request->input('order', 'id_cliente'),
            $request->input('direction', 'asc')
        );

        // 6) Paginación dinámica
        $perPageParam = $request->input('perPage', 25);

        if ($perPageParam === 'all') {
            // “Todos”  ⇒  ponemos como tamaño de página el total de registros
            $perPage = $query->count() ?: 1;   // evita división por cero
        } else {
            $perPage = (int) $perPageParam;    // 10, 25, 50, 100…
        }

        $clientes = $query->paginate($perPage)
                        ->appends($request->all());   // conserva filtros

        /* ---------- Catálogos para los <select> ---------- */
        $vendedores = Usuario::whereNull('id_cliente')->get();

        // Los sacamos directo de la tabla para que no se “desincronicen”
        $sectores   = Cliente::select('sector')->distinct()->pluck('sector');
        $segmentos  = Cliente::select('segmento')->distinct()->pluck('segmento');
        $ciclos     = Cliente::select('ciclo_venta')->distinct()->pluck('ciclo_venta');

        return view('clientes.index', compact(
            'clientes',
            'vendedores',
            'sectores',
            'segmentos',
            'ciclos',
        ));
    }

    public function create(Request $request)
    {
        $tipo           = $request->input('tipo', 'moral'); // por defecto: moral
        $vendedores     = Usuario::whereNull('id_cliente')->where('estatus', 'activo')->get(); // usuarios internos activos
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
        $cliente = Cliente::with([
            'contacto_predet',
            'razon_social_predet',
            'contacto_entrega_predet',
            'contacto_entrega_predet.direccion_entrega',
            'razon_social_predet.direccion_facturacion',
            'direccionesEntrega',
            'notas',
            'razon_social_predet.uso_cfdi',
            'razon_social_predet.metodo_pago',
            'razon_social_predet.forma_pago',
            'razon_social_predet.regimen_fiscal',
            'razon_social_predet.direccion_facturacion',
            // ❗ Quitamos este bloque:
            // 'contacto_entrega_predet.direccion_entrega.ciudad',
            // 'contacto_entrega_predet.direccion_entrega.estado',
            // 'contacto_entrega_predet.direccion_entrega.pais',
        ])
        ->findOrFail($id);

        $notas = Nota::with('usuario')
            ->where('id_cliente', $cliente->id_cliente)
            ->orderByDesc('fecha_registro')
            ->get();

        // ===== Dummy de historial mientras no hay modelo Pedido =====
        $pedidos = collect([
            ['fecha' => '2025-04-02', 'id' => 'P-1023', 'razon' => 'ACME S.A. de C.V.',      'subtotal' => 15230.50, 'margen' => 2800.00],
            ['fecha' => '2025-03-17', 'id' => 'P-0976', 'razon' => 'Tech México S.A.',        'subtotal' =>  8650.00, 'margen' => 1910.00],
            ['fecha' => '2025-02-28', 'id' => 'P-0911', 'razon' => 'Comercial XYZ S. de R.L', 'subtotal' => 43120.90, 'margen' => 6550.00],
            ['fecha' => '2025-04-10', 'id' => 'P-1034', 'razon' => 'Distribuidora ABC S.A.',  'subtotal' => 12345.67, 'margen' => 2450.00],
            ['fecha' => '2025-03-25', 'id' => 'P-0987', 'razon' => 'Global Tech Solutions',   'subtotal' =>  9876.54, 'margen' => 2020.00],
            ['fecha' => '2025-02-15', 'id' => 'P-0899', 'razon' => 'Innovaciones S.A. de C.V.', 'subtotal' => 25678.90, 'margen' => 4450.00],
            ['fecha' => '2025-04-05', 'id' => 'P-1028', 'razon' => 'Servicios Integrales MX', 'subtotal' =>  7654.32, 'margen' => 1660.00],
            ['fecha' => '2025-03-12', 'id' => 'P-0965', 'razon' => 'Corporativo Delta',       'subtotal' => 18900.00, 'margen' => 3200.00],
            ['fecha' => '2025-02-20', 'id' => 'P-0905', 'razon' => 'Grupo Empresarial Omega', 'subtotal' => 34210.75, 'margen' => 5050.00],
            ['fecha' => '2025-04-08', 'id' => 'P-1031', 'razon' => 'Soluciones Avanzadas',    'subtotal' => 11234.56, 'margen' => 2050.00],
            ['fecha' => '2025-03-30', 'id' => 'P-0992', 'razon' => 'TechnoWorld S.A.',        'subtotal' =>  6543.21, 'margen' => 1500.00],
            ['fecha' => '2025-02-10', 'id' => 'P-0888', 'razon' => 'Comercializadora Alfa',   'subtotal' => 27890.12, 'margen' => 4600.00],
            ['fecha' => '2025-04-12', 'id' => 'P-1036', 'razon' => 'Distribuciones Beta',     'subtotal' =>  8765.43, 'margen' => 1780.00],
            ['fecha' => '2025-03-20', 'id' => 'P-0980', 'razon' => 'Innovación Global',       'subtotal' => 14567.89, 'margen' => 2750.00],
            ['fecha' => '2025-02-25', 'id' => 'P-0910', 'razon' => 'Corporativo Gamma',       'subtotal' => 39876.54, 'margen' => 6100.00],
            ['fecha' => '2025-04-15', 'id' => 'P-1040', 'razon' => 'Servicios Empresariales', 'subtotal' => 13456.78, 'margen' => 2500.00],
            ['fecha' => '2025-03-10', 'id' => 'P-0954', 'razon' => 'Tech Solutions MX',       'subtotal' =>  9876.54, 'margen' => 2100.00],
            ['fecha' => '2025-02-05', 'id' => 'P-0877', 'razon' => 'Comercial Delta',         'subtotal' => 31234.56, 'margen' => 4530.00],
            ['fecha' => '2025-04-18', 'id' => 'P-1045', 'razon' => 'Distribuidora Zeta',      'subtotal' =>  7654.32, 'margen' => 1710.00],
            ['fecha' => '2025-03-05', 'id' => 'P-0943', 'razon' => 'Global Innovators',       'subtotal' => 16789.01, 'margen' => 2980.00],
            ['fecha' => '2025-04-20', 'id' => 'P-1050', 'razon' => 'Alpha Solutions',         'subtotal' => 14500.00, 'margen' => 2750.00],
            ['fecha' => '2025-03-22', 'id' => 'P-0995', 'razon' => 'Beta Enterprises',        'subtotal' =>  9800.00, 'margen' => 1950.00],
            ['fecha' => '2025-02-18', 'id' => 'P-0920', 'razon' => 'Gamma Corp.',             'subtotal' => 32000.00, 'margen' => 4800.00],
            ['fecha' => '2025-04-25', 'id' => 'P-1060', 'razon' => 'Delta Innovations',       'subtotal' => 12000.00, 'margen' => 2200.00],
            ['fecha' => '2025-03-28', 'id' => 'P-1000', 'razon' => 'Epsilon Group',           'subtotal' =>  8700.00, 'margen' => 1830.00],
            ['fecha' => '2025-02-12', 'id' => 'P-0890', 'razon' => 'Zeta Solutions',          'subtotal' => 25000.00, 'margen' => 4000.00],
            ['fecha' => '2025-04-22', 'id' => 'P-1055', 'razon' => 'Omega Enterprises',       'subtotal' => 13500.00, 'margen' => 2600.00],
            ['fecha' => '2025-03-15', 'id' => 'P-0970', 'razon' => 'Sigma Tech',              'subtotal' =>  9400.00, 'margen' => 1950.00],
            ['fecha' => '2025-02-08', 'id' => 'P-0885', 'razon' => 'Lambda Corp.',            'subtotal' => 31000.00, 'margen' => 4600.00],
            ['fecha' => '2025-04-28', 'id' => 'P-1070', 'razon' => 'Theta Innovations',       'subtotal' => 11000.00, 'margen' => 2000.00],
            ['fecha' => '2025-03-18', 'id' => 'P-0985', 'razon' => 'Iota Enterprises',        'subtotal' =>  8900.00, 'margen' => 1920.00],
            ['fecha' => '2025-02-22', 'id' => 'P-0930', 'razon' => 'Kappa Solutions',         'subtotal' => 27000.00, 'margen' => 4200.00],
            ['fecha' => '2025-04-30', 'id' => 'P-1080', 'razon' => 'Lambda Innovations',      'subtotal' => 14000.00, 'margen' => 2650.00],
            ['fecha' => '2025-03-25', 'id' => 'P-0998', 'razon' => 'Mu Enterprises',          'subtotal' =>  9200.00, 'margen' => 1850.00],
            ['fecha' => '2025-02-28', 'id' => 'P-0945', 'razon' => 'Nu Corp.',                'subtotal' => 29000.00, 'margen' => 4500.00],
            ['fecha' => '2025-04-18', 'id' => 'P-1048', 'razon' => 'Xi Solutions',            'subtotal' => 12500.00, 'margen' => 2350.00],
            ['fecha' => '2025-03-12', 'id' => 'P-0968', 'razon' => 'Omicron Group',           'subtotal' =>  8800.00, 'margen' => 1870.00],
            ['fecha' => '2025-02-15', 'id' => 'P-0908', 'razon' => 'Pi Enterprises',          'subtotal' => 26000.00, 'margen' => 4300.00],
        ]);

        $vendedores     = Usuario::whereNull('id_cliente')->where('estatus', 'activo')->get(); // usuarios internos activos
        $metodos_pago   = MetodoPago::pluck('nombre', 'id_metodo_pago');
        $formas_pago    = FormaPago::pluck('nombre', 'id_forma_pago');
        $usos_cfdi      = UsoCfdi::pluck('nombre', 'id_uso_cfdi');
        $ciudades       = Ciudad::pluck('nombre', 'id_ciudad');
        $estados        = Estado::pluck('nombre', 'id_estado');
        $paises = [
            '1' => 'México',
            '2' => 'Estados Unidos',
            '3' => 'Canadá',
        ];
        $regimen_fiscales = RegimenFiscal::pluck('nombre', 'id_regimen_fiscal');
        $sectores = [
            '1' => 'privada',
            '2' => 'gobierno',
            '3' => 'persona',
        ];
        //Navegación entre clientes/cuentas eje
        $prevId = Cliente::where('id_cliente', '<', $cliente->id_cliente)
                 ->orderByDesc('id_cliente')
                 ->value('id_cliente');   // devuelve null si no hay anterior

        $nextId = Cliente::where('id_cliente', '>', $cliente->id_cliente)
                 ->orderBy('id_cliente')
                 ->value('id_cliente');   // devuelve null si no hay siguiente

        $usuario = auth()->user();

        return view('clientes.view',
                    compact(
                  'cliente',
                 'notas', 
                            'pedidos',
                            'vendedores',
                            'metodos_pago',
                            'formas_pago',
                            'usos_cfdi',
                            'ciudades',
                            'estados',
                            'paises',
                            'regimen_fiscales',
                            'prevId',
                            'nextId',
                            'sectores',
                            'usuario'
                        ));
    }
    public function update(Request $request, $id)
    {
        // Actualiza los datos de un cliente existente desde clientes.view
    
        if($request->sector == "privada" || $request->sector == "gobierno")
        {
            if(auth()->user()->es_admin != 1) {
                return redirect()->back()->with('error', 'No tienes permiso para actualizar este cliente.');
            }

            $request->merge([
                'contacto' => collect($request->input('contacto', []))
                    ->map(function ($c) {
                        foreach (range(1,5) as $i) {
                            foreach (['telefono', 'celular', 'ext'] as $campo) {
                                $key = "{$campo}{$i}";
                                $c[$key] = isset($c[$key]) ? preg_replace('/\D/', '', $c[$key]) : null;
                            }
                        }
                        return $c;
                    })->all()
            ]);

            // ── Reglas base ──────────────────────────────────────────────────────────
            $rules = [
                'estatus'     => 'nullable|string|max:100',
                'ciclo_venta' => 'nullable|string|max:100',
                'tipo'        => 'nullable|string|max:100',
                'nombre'      => 'nullable|string|max:60',
                'id_vendedor' => 'nullable|integer',
                'sector'      => 'nullable|string|max:100',
                'segmento'    => 'nullable|string|max:100',

                'contacto.0.nombre'      => 'nullable|string|max:60',
                'contacto.0.apellido_p'  => 'nullable|string|max:27',
                'contacto.0.apellido_m'  => 'nullable|string|max:27',
                'contacto.0.email'       => 'nullable|email|max:120',
                'contacto.0.puesto'      => 'nullable|string|max:60',
                'contacto.0.genero'      => 'nullable|string|max:17',
            ];

            // ── Teléfonos / Extensiones / Celulares (1‒5) ───────────────────────────
            for ($i = 1; $i <= 5; $i++) {
                $rules["contacto.0.telefono{$i}"] = 'nullable|digits:10';
                $rules["contacto.0.celular{$i}"]  = 'nullable|digits:10';
                $rules["contacto.0.ext{$i}"]      = 'nullable|digits_between:1,7';
            }

            // ── Validamos ───────────────────────────────────────────────────────────
            $data = $request->validate($rules);


            // Actualizar el cliente en la BD
            try{
                $cliente = Cliente::findOrFail($id);
                $cliente->update([
                    'estatus'       => $data['estatus'],
                    'nombre'        => $data['nombre'],
                    'ciclo_venta'   => $data['ciclo_venta'],
                    'tipo'          => $data['tipo'],
                    'id_vendedor'   => $data['id_vendedor'],
                    'sector'        => $data['sector'],
                    'segmento'      => $data['segmento'],
                ]);

                // Actualizar el contacto principal

                $contacto = Contacto::updateOrCreate(
                    [
                        'id_cliente'     => $cliente->id_cliente,
                        'predeterminado' => 1
                    ],
                    [ // ➊ datos “de cabecera”
                        'nombre'      => $data['contacto'][0]['nombre']  ?? null,
                        'apellido_p'  => $data['contacto'][0]['apellido_p'] ?? null,
                        'apellido_m'  => $data['contacto'][0]['apellido_m'] ?? null,
                        'email'       => $data['contacto'][0]['email'] ?? null,
                        'puesto'      => $data['contacto'][0]['puesto'] ?? null,
                        'genero'      => $data['contacto'][0]['genero'] ?? null,
                    ]
                );

                // ➋ teléfonos / extensiones
                for ($i = 1; $i <= 5; $i++) {
                    $contacto->{"telefono$i"} = digits_only($data['contacto'][0]["telefono$i"] ?? null);
                    $contacto->{"ext$i"}      = digits_only($data['contacto'][0]["ext$i"]      ?? null);
                    $contacto->{"celular$i"}  = digits_only($data['contacto'][0]["celular$i"]  ?? null);
                }
                $contacto->save();

                //registrarNota es una función Helper, está definida en app/Helpers/
                $mensaje = "El usuario " . auth()->user()->nombre." ".auth()->user()->apellido_p." ".auth()->user()->apellido_m .
                            " ha actualizado la cuenta empresarial [{$cliente->id_cliente}]  {$cliente->nombre}.";

                registrarNota(
                    $cliente->id_cliente,
                    $mensaje,
                    $cliente->ciclo_venta,
                );

                return redirect()->route('clientes.view', ['id' => $cliente->id_cliente])
                                 ->with('success', 'Cliente empresarial actualizado correctamente');

            }catch (Exception $e) {
                return redirect('clientes')->with('error', 'Error al actualizar el cliente: ' . $e->getMessage());
            }

        }elseif($request->sector == "persona")
        {
            if(auth()->user()->es_admin != 1) {
                return redirect()->back()->with('error', 'No tienes permiso para actualizar este cliente.');
            }

            $request->merge([
                'contacto' => collect($request->input('contacto', []))
                    ->map(function ($c) {
                        foreach (range(1,5) as $i) {
                            foreach (['telefono', 'celular', 'ext'] as $campo) {
                                $key = "{$campo}{$i}";
                                $c[$key] = isset($c[$key]) ? preg_replace('/\D/', '', $c[$key]) : null;
                            }
                        }
                        return $c;
                    })->all()
            ]);

            $rules = [
                'nombre'        => 'required|string|max:60',
                'apellido_p'    => 'required|string|max:27',
                'apellido_m'    => 'nullable|string|max:27',
                'id_vendedor'   => 'nullable|integer',

                'ciclo_venta'   => 'nullable|string|max:100',
                'estatus'       => 'required|string|max:100',
                'tipo'          => 'nullable|string|max:100',
                'sector'        => 'nullable|string|max:100',

                // Datos personales …
                'email'                  => 'nullable|email|max:120',
                'segmento'               => 'nullable|string|max:100',
                'genero'                 => 'nullable|string|max:17',
                   ];
            // ── Teléfonos / Extensiones / Celulares (1‒5) ───────────────────────────
            for ($i = 1; $i <= 5; $i++) {
                $rules["contacto.0.telefono{$i}"] = 'nullable|digits:10';
                $rules["contacto.0.celular{$i}"]  = 'nullable|digits:10';
                $rules["contacto.0.ext{$i}"]      = 'nullable|digits_between:1,7';
            }
            $request->validate($rules);
            // Actualizar el cliente en la BD
            try
            {
                $cliente = Cliente::findOrFail($id);
                $cliente->update([
                    'nombre'        => $request->input('nombre'),
                    'apellido_p'    => $request->input('apellido_p'),
                    'apellido_m'    => $request->input('apellido_m'),
                    'ciclo_venta'   => $request->input('ciclo_venta'),
                    'estatus'       => $request->input('estatus'),
                    'tipo'          => $request->input('tipo'),
                    'sector'        => $request->input('sector'),
                    'segmento'      => $request->input('segmento'),
                    'id_vendedor'   => $request->filled('id_vendedor')
                        ? $request->input('id_vendedor')
                        : null,
                ]);

                // Actualizar el contacto principal
                $contacto = Contacto::updateOrCreate(
                    [
                        'id_cliente'     => $cliente->id_cliente,
                        'predeterminado' => 1
                    ],
                    [
                        'nombre'      => $request->input('nombre'),
                        'apellido_p'  => $request->input('apellido_p'),
                        'apellido_m'  => $request->input('apellido_m'),
                        'email'       => $request->input('email'),
                        'puesto'      => null, // Cuentas personales no manejan puesto
                        'genero'      => $request->input('genero'),
                    ]
                );

                // ➋ teléfonos / extensiones
                for ($i = 1; $i <= 5; $i++) {
                    $contacto->{"telefono$i"} = digits_only($request->input("contacto.0.telefono$i"));
                    $contacto->{"ext$i"}      = digits_only($request->input("contacto.0.ext$i"));
                    $contacto->{"celular$i"}  = digits_only($request->input("contacto.0.celular$i"));
                }
                $contacto->save();

                //registrarNota es una función Helper, está definida en app/Helpers/
                $mensaje = "El usuario ".auth()->user()->nombre." ".
                            auth()->user()->apellido_p."".auth()->user()->apellido_m .
                            " ha actualizado la cuenta personal [{$cliente->id_cliente}] -> {$cliente->nombre}.";

                registrarNota(
                    $cliente->id_cliente,
                    $mensaje,
                    $cliente->ciclo_venta,
                );

                return redirect()->route('clientes.view', ['id' => $cliente->id_cliente])
                                 ->with('success', 'Cliente personal actualizado correctamente');
            } catch (Exception $e) {
                return redirect('clientes')->with('error', 'Error al actualizar el cliente: ' . $e->getMessage());
            }
        }
    }
    public function destroy($id)
    {
        // 1) Buscar el registro existente
        $cliente = Cliente::findOrFail($id);
        // 2) Eliminar el registro
        $cliente->delete();

        //registrarNota es una función Helper, está definida en app/Helpers/
                $mensaje = "El usuario " . auth()->user()->nombre_completo .
                            " ha creado la cuenta empresarial [{$cliente->id_cliente}]  {$cliente->nombre}.";

                registrarNota(
                    $cliente->id_cliente,
                    $mensaje,
                    $cliente->ciclo_venta,
                );
        return redirect('clientes')->with('success', 'Cliente eliminado correctamente');
    }
    public function store(Request $request)
    {
        //Guarda los datos de un cliente nuevo desde clientes.index
        \Log::info('Tipo recibido:', ['tipo' => $request->input('tipo')]);

        if ($request->input('sector') == 'persona') 
        { // SI ES FISICA
            $rules = [
                /* === Cuenta Personal === */
                'nombre'        => 'required|string|max:60',
                'apellido_p'    => 'required|string|max:27',
                'apellido_m'    => 'nullable|string|max:27',
                'id_vendedor'   => 'required|integer',

                'ciclo_venta'   => 'nullable|string|max:100',
                'estatus'       => 'required|string|max:100',
                'tipo'          => 'required|string|max:100',
                'sector'        => 'nullable|string|max:100',
            
                // Datos personales …
                'email'                  => 'nullable|email|max:120',
                'segmento'               => 'nullable|string|max:100',
                'genero'                 => 'nullable|string|max:17',
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
                    'apellido_p'  => $request->input('apellido_p'),
                    'apellido_m'  => $request->input('apellido_m'),
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
                    'id_cliente'  => $cliente->id_cliente,
                    'nombre'      => $request->input('nombre'),
                    'apellido_p'  => $request->input('apellido_p'),
                    'apellido_m'  => $request->input('apellido_m'),
                    'email'       => $request->input('email'),
                    'genero'      => $request->input('genero'),
                    'puesto'      => null, // o define si vendrá después
                
                    'telefono1'   => $contacto['telefono1'] ?? null,
                    'ext1'        => $contacto['ext1']      ?? null,
                    'telefono2'   => $contacto['telefono2'] ?? null,
                    'ext2'        => $contacto['ext2']      ?? null,
                    'telefono3'   => $contacto['telefono3'] ?? null,
                    'ext3'        => $contacto['ext3']      ?? null,
                    'telefono4'   => $contacto['telefono4'] ?? null,
                    'ext4'        => $contacto['ext4']      ?? null,
                    'telefono5'   => $contacto['telefono5'] ?? null,
                    'ext5'        => $contacto['ext5']      ?? null,
                
                    'celular1'    => $contacto['celular1']  ?? null,
                    'celular2'    => $contacto['celular2']  ?? null,
                    'celular3'    => $contacto['celular3']  ?? null,
                    'celular4'    => $contacto['celular4']  ?? null,
                    'celular5'    => $contacto['celular5']  ?? null,
                
                    'predeterminado' => 1,
                ]);
                

                //registrarNota es una función Helper, está definida en app/Helpers/
                $mensaje = "El usuario " . auth()->user()->nombre_completo .
                            " ha creado la cuenta empresarial [{$cliente->id_cliente}]  {$cliente->nombre}.";

                registrarNota(
                    $cliente->id_cliente,
                    $mensaje,
                    $cliente->ciclo_venta,
                );

                return redirect(to: '/clientes')->with('success', 'Cuenta personal creada correctamente');

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

                            'predeterminado' => 1, // solo el primer contacto, quitar si este formulario va a soportar muchos contactos iniciales.
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

                //registrarNota es una función Helper, está definida en app/Helpers/
                $mensaje = "El usuario " . auth()->user()->nombre_completo .
                            " ha creado la cuenta personal [{$cliente->id_cliente}]  {$cliente->nombre}.";

                registrarNota(
                    $cliente->id_cliente,
                    $mensaje,
                    $cliente->ciclo_venta,
                );

                return redirect(to: '/clientes')->with('success', 'Cliente creado correctamente');
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()]);
            }
        }
        // Redirigir a la lista
        $total = Cliente::count();
        $clientes = Cliente::paginate(25);
        $porPagina = 25;
        $ultimaPagina = ceil($total / $porPagina);


        return redirect()->route('clientes.index', ['page' => $ultimaPagina])
        ->with('success', 'Cliente creado correctamente');
        }

    /**
     * Muestra el formulario para traspasar múltiples clientes.
     */
    public function transfer()
    {
        // Ejecutivos de venta (usuarios internos)
        $vendedores = Usuario::whereNull('id_cliente')->get();


        $clientes = Cliente::with(['primerContacto', 'vendedor'])
            ->orderBy('id_cliente')
            ->get();

        return view('clientes.transfer', compact('vendedores','clientes'));
    }

    /**
     * Procesa el traspaso: actualiza id_vendedor de los clientes seleccionados.
     */
    public function transferStore(Request $request)
    {
        $data = $request->validate([
            'clientes' => 'required|array',
            'clientes.*' => 'integer|exists:clientes,id_cliente',
            'destino'  => 'required|integer|exists:usuarios,id'
        ]);

        // Actualizar en lote
        Cliente::whereIn('id_cliente', $data['clientes'])
               ->update(['id_vendedor' => $data['destino']]);

        return redirect()
            ->route('clientes.transfer')
            ->with('success','¡Clientes transferidos correctamente!');
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
