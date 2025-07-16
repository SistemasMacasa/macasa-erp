<?php

namespace App\Http\Controllers;

use App\Models\User;
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
use App\Models\Cotizacion;
use App\Models\Consecutivo;
use Illuminate\Support\Facades\DB;
use App\Models\Segmento;
use Illuminate\Validation\Rules\Can;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->hasRole('Ventas')) {
            $query = Cliente::with(['primerContacto', 'vendedor', 'segmento'])
                ->where('estatus', 'activo')
                ->where('id_vendedor', auth()->user()->id_usuario);
        } else {
            // 1) consulta base + relaciones
            $query = Cliente::with(['primerContacto', 'vendedor', 'segmento'])
                ->where('estatus', 'activo');
        }


        /* ---------- Filtros ---------- */

        // Búsqueda global
        if ($term = $request->input('search')) {
            $query->where(function ($q) use ($term) {
                $q->where('nombre', 'like', "%{$term}%")
                    ->orWhere('id_cliente', 'like', "%{$term}%")
                    ->orWhereHas('primerContacto', fn($q2) =>
                    $q2->where('telefono1', 'like', "%{$term}%"));
            });
        }

        // Ejecutivos
        if ($ejecutivos = $request->input('ejecutivos')) {
            $query->where(function ($q) use ($ejecutivos) {
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

        if ($sector = $request->input('sector')) {          // sector = 'privada' | 'gobierno' | ...
            $query->where('sector', $sector);
        }

        if ($segmento = $request->input('segmento')) {
            $query->where('id_segmento', $segmento);
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

        if (auth()->user()->hasRole('Ventas')) {
            // Si es un ejecutivo, solo mostramos a el mismo como vendedor

            $vendedores = Usuario::where('id_usuario', auth()->user()->id_usuario)
                ->whereNull('id_cliente')
                ->where('estatus', 'activo')
                ->get();
        } else {
            // Si no es un ejecutivo, mostramos todos los vendedores
            $vendedores = Usuario::whereNull('id_cliente')
                ->where('estatus', 'activo')
                ->get();
        }
        // Los sacamos directo de la tabla para que no se “desincronicen”
        $sectores = Cliente::select('sector')->distinct()->pluck('sector');
        $segmentos = Segmento::orderBy('nombre')
            ->pluck('nombre', 'id_segmento');

        $ciclos = Cliente::select('ciclo_venta')->distinct()->pluck('ciclo_venta');

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
        $tipo = $request->input('tipo', 'moral'); // por defecto: moral
        $vendedores = Usuario::whereNull('id_cliente')->where('estatus', 'activo')->get(); // usuarios internos activos
        $metodos_pago = MetodoPago::pluck('nombre', 'id_metodo_pago');
        $formas_pago = FormaPago::pluck('nombre', 'id_forma_pago');
        $usos_cfdi = UsoCfdi::pluck('nombre', 'id_uso_cfdi');
        $ciudades = Ciudad::pluck('n_mnpio', 'id_ciudad');
        $estados = Estado::pluck('d_estado', 'id_estado');
        $paises = [
            '1' => 'México',
            '2' => 'Estados Unidos',
            '3' => 'Canadá',
            // Agrega más países según sea necesario
        ];
        $regimen_fiscales = RegimenFiscal::pluck('nombre', 'id_regimen_fiscal');
        $segmentos = Segmento::orderBy('nombre')->get(); // o el campo que represente el nombre
        return view('clientes.create', compact('vendedores', 'metodos_pago', 'formas_pago', 'usos_cfdi', 'ciudades', 'estados', 'paises', 'tipo', 'regimen_fiscales', 'segmentos'));
    }
    public function edit($id)
    {
        $cliente = Cliente::with('segmento')->findOrFail($id);
        $vendedores = Usuario::whereNull('id_cliente')->get(); // usuarios internos
        $segmentos = Segmento::orderBy('nombre')->get(); // Aquí cargas todos los segmentos

        return view('clientes.edit', compact('cliente', 'vendedores', 'segmentos'));
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
            'contactos_entrega'
        ])
            ->findOrFail($id);

        //Eric, en el modelo Cliente hay una función del ORM llamada notas().
        //validar si se puede cambiar este query por algo como $cliente->notas
        $notas = Nota::with('usuario')
            ->where('id_cliente', $cliente->id_cliente)
            ->orderBy('fecha_registro')
            ->get();

            $cotizaciones = Cotizacion::with(['razonSocial'])
            ->where('id_cliente', $cliente->id_cliente)
            ->orderByDesc('fecha_alta')
            ->get()
            ->map(function ($c) {
                $subtotal = $c->subtotal ?: 0;
                $margen = $c->score_final ?: 0;
                $factor = $subtotal > 0 ? ($margen / $subtotal) * 100 : 0;

                return [
                    'fecha'    => $c->fecha,
                    'id_cotizacion' => $c->id_cotizacion,
                    'num_consecutivo'       => $c->num_consecutivo,
                    'razon'    => optional($c->razonSocial)->nombre ?? 'Sin razón',
                    'subtotal' => $subtotal,
                    'margen'   => $margen,
                    'factor'   => $factor
            ];
            });


        // ===== Dummy de historial mientras no hay modelo Pedido =====
        $pedidos = collect([
        ]);

        $vendedores = Usuario::whereNull('id_cliente')->where('estatus', 'activo')->get(); // usuarios internos activos
        $metodos_pago = MetodoPago::pluck('nombre', 'id_metodo_pago');
        $formas_pago = FormaPago::pluck('nombre', 'id_forma_pago');
        $usos_cfdi = UsoCfdi::pluck('nombre', 'id_uso_cfdi');
        $ciudades = Ciudad::pluck('n_mnpio', 'id_ciudad');
        $estados = Estado::pluck('d_estado', 'id_estado');
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
        $segmentos = Segmento::all();
        // Navegación entre clientes/cuentas eje
        if (auth()->user()->hasRole('Ventas')) {
            // Solo navegar si el cliente pertenece al ejecutivo autenticado
            if ($cliente->id_vendedor == auth()->user()->id_usuario) {
                $prevId = Cliente::where('id_vendedor', auth()->user()->id_usuario)
                    ->where('id_cliente', '<', $cliente->id_cliente)
                    ->orderByDesc('id_cliente')
                    ->value('id_cliente');

                $nextId = Cliente::where('id_vendedor', auth()->user()->id_usuario)
                    ->where('id_cliente', '>', $cliente->id_cliente)
                    ->orderBy('id_cliente')
                    ->value('id_cliente');
            } else {
                $prevId = null;
                $nextId = null;
            }
        } else {
            // Navegación general para otros roles
            $prevId = Cliente::where('id_cliente', '<', $cliente->id_cliente)
                ->orderByDesc('id_cliente')
                ->value('id_cliente');

            $nextId = Cliente::where('id_cliente', '>', $cliente->id_cliente)
                ->orderBy('id_cliente')
                ->value('id_cliente');
        }


        $usuario = auth()->user();

        return view(
            'clientes.view',
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
                'usuario',
                'cotizaciones',
                'segmentos'
            )
        );
    }
    public function update(Request $request, $id)
    {
        // Actualiza los datos de un cliente existente desde clientes.view

        if ($request->sector == "privada" || $request->sector == "gobierno") {
            if (!$request->user()->can('Editar Cuenta')) {
                return redirect()->back()->with('error', 'No tienes permiso para actualizar este cliente.');
            }

            $request->merge([
                'contacto' => collect($request->input('contacto', []))
                    ->map(function ($c) {
                        foreach (range(1, 5) as $i) {
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
                'estatus' => 'nullable|string|max:100',
                'ciclo_venta' => 'nullable|string|max:100',
                'tipo' => 'nullable|string|max:100',
                'nombre' => 'nullable|string|max:60',
                'id_vendedor' => 'nullable|integer',
                'sector' => 'nullable|string|max:100',
                'id_segmento' => 'nullable|exists:segmentos,id_segmento',


                'contacto.0.nombre' => 'nullable|string|max:60',
                'contacto.0.apellido_p' => 'nullable|string|max:27',
                'contacto.0.apellido_m' => 'nullable|string|max:27',
                'contacto.0.email' => 'nullable|email|max:120',
                'contacto.0.puesto' => 'nullable|string|max:60',
                'contacto.0.genero' => 'nullable|string|max:17',
            ];

            // ── Teléfonos / Extensiones / Celulares (1‒5) ───────────────────────────
            for ($i = 1; $i <= 5; $i++) {
                $rules["contacto.0.telefono{$i}"] = 'nullable|digits:10';
                $rules["contacto.0.celular{$i}"] = 'nullable|digits:10';
                $rules["contacto.0.ext{$i}"] = 'nullable|digits_between:1,7';
            }

            // ── Validamos ───────────────────────────────────────────────────────────
            $data = $request->validate($rules);

            // Actualizar el cliente en la BD
            try {
                $cliente = Cliente::findOrFail($id);
                $estatus_anterior = $cliente->estatus;
                $estatus_nuevo = $data['estatus'];
                $vendedor_anterior = $cliente->id_vendedor;
                $vendedor_nuevo = $request->input('id_vendedor');
                $cliente->update([
                    'estatus' => $data['estatus'],
                    'nombre' => $data['nombre'],
                    'ciclo_venta' => $data['ciclo_venta'],
                    'tipo' => $data['tipo'],
                    'id_vendedor' => $data['id_vendedor'],
                    'sector' => $data['sector'],
                    'id_segmento'  => $data['id_segmento'] ?? null, // 👈 Aquí la clave
                ]);

                // Actualizar el contacto principal

                $contacto = Contacto::updateOrCreate(
                    [
                        'id_cliente' => $cliente->id_cliente,
                        'predeterminado' => 1
                    ],
                    [ // ➊ datos “de cabecera”
                        'nombre' => $data['contacto'][0]['nombre'] ?? null,
                        'apellido_p' => $data['contacto'][0]['apellido_p'] ?? null,
                        'apellido_m' => $data['contacto'][0]['apellido_m'] ?? null,
                        'email' => $data['contacto'][0]['email'] ?? null,
                        'puesto' => $data['contacto'][0]['puesto'] ?? null,
                        'genero' => $data['contacto'][0]['genero'] ?? null,
                    ]
                );

                // ➋ teléfonos / extensiones
                for ($i = 1; $i <= 5; $i++) {
                    $contacto->{"telefono$i"} = digits_only($data['contacto'][0]["telefono$i"] ?? null);
                    $contacto->{"ext$i"} = digits_only($data['contacto'][0]["ext$i"] ?? null);
                    $contacto->{"celular$i"} = digits_only($data['contacto'][0]["celular$i"] ?? null);
                }
                $contacto->save();


                // Verificar cambio de estatus
                if ($estatus_anterior !== $estatus_nuevo) {
                    if ($estatus_nuevo === 'inactivo') {
                        $mensaje = "El usuario " . auth()->user()->nombreCompleto . " archivó esta cuenta.";

                        registrarNota(
                            id_cliente: $cliente->id_cliente,
                            contenido: $mensaje,
                            etapa: null,
                            fecha_reprogramacion: null,
                            es_automatico: 1,
                        );
                    } elseif ($estatus_anterior === 'inactivo' && $estatus_nuevo === 'activo') {
                        $mensaje = "El usuario " . auth()->user()->nombreCompleto . " restauró esta cuenta.";

                        registrarNota(
                            id_cliente: $cliente->id_cliente,
                            contenido: $mensaje,
                            etapa: null,
                            fecha_reprogramacion: null,
                            es_automatico: 1,
                        );
                    }
                } else {
                    // Verificar cambio de asignación de vendedor
                    if ((int) $vendedor_anterior !== (int) $vendedor_nuevo) {
                        $nombre_origen = $vendedor_anterior
                            ? optional(Usuario::find($vendedor_anterior))->nombreCompleto ?? 'Vendedor desconocido'
                            : 'BASE GENERAL';

                        $nombre_destino = $vendedor_nuevo
                            ? optional(Usuario::find($vendedor_nuevo))->nombreCompleto ?? 'Vendedor desconocido'
                            : 'BASE GENERAL';

                        $mensaje = sprintf(
                            'El usuario %s reasignó esta cuenta del vendedor %s al vendedor %s.',
                            auth()->user()->nombreCompleto,
                            $nombre_origen,
                            $nombre_destino
                        );

                        registrarNota(
                            id_cliente: $cliente->id_cliente,
                            contenido: $mensaje,
                            etapa: null,
                            fecha_reprogramacion: null,
                            es_automatico: 1,
                        );
                    } else {
                        // Si no cambió ni estatus ni vendedor, se asume actualización general
                        $mensaje = "El usuario " . auth()->user()->nombreCompleto . " actualizó los datos de esta cuenta.";

                        registrarNota(
                            id_cliente: $cliente->id_cliente,
                            contenido: $mensaje,
                            etapa: $cliente->ciclo_venta,
                            fecha_reprogramacion: null,
                            es_automatico: 1,
                        );
                    }
                }

                return redirect()->route('clientes.view', ['id' => $cliente->id_cliente])
                    ->with('success', 'Cliente empresarial actualizado correctamente');
            } catch (Exception $e) {
                return redirect('clientes')->with('error', 'Error al actualizar el cliente: ' . $e->getMessage());
            }
        } elseif ($request->sector == "persona") {
            if (auth()->user()->es_admin != 1) {
                return redirect()->back()->with('error', 'No tienes permiso para actualizar este cliente.');
            }

            $request->merge([
                'contacto' => collect($request->input('contacto', []))
                    ->map(function ($c) {
                        foreach (range(1, 5) as $i) {
                            foreach (['telefono', 'celular', 'ext'] as $campo) {
                                $key = "{$campo}{$i}";
                                $c[$key] = isset($c[$key]) ? preg_replace('/\D/', '', $c[$key]) : null;
                            }
                        }
                        return $c;
                    })->all()
            ]);

            $rules = [
                'nombre' => 'required|string|max:60',
                'apellido_p' => 'required|string|max:27',
                'apellido_m' => 'nullable|string|max:27',
                'id_vendedor' => 'nullable|integer',

                'ciclo_venta' => 'nullable|string|max:100',
                'estatus' => 'required|string|max:100',
                'tipo' => 'nullable|string|max:100',
                'sector' => 'nullable|string|max:100',

                // Datos personales …
                'email' => 'nullable|email|max:120',
                'id_segmento' => 'required|exists:segmentos,id_segmento',
                'genero' => 'nullable|string|max:17',
            ];
            // ── Teléfonos / Extensiones / Celulares (1‒5) ───────────────────────────
            for ($i = 1; $i <= 5; $i++) {
                $rules["contacto.0.telefono{$i}"] = 'nullable|digits:10';
                $rules["contacto.0.celular{$i}"] = 'nullable|digits:10';
                $rules["contacto.0.ext{$i}"] = 'nullable|digits_between:1,7';
            }
            $request->validate($rules);
            // Actualizar el cliente en la BD
            try {
                $cliente = Cliente::findOrFail($id);
                $cliente->update([
                    'nombre' => $request->input('nombre'),
                    'apellido_p' => $request->input('apellido_p'),
                    'apellido_m' => $request->input('apellido_m'),
                    'ciclo_venta' => $request->input('ciclo_venta'),
                    'estatus' => $request->input('estatus'),
                    'tipo' => $request->input('tipo'),
                    'sector' => $request->input('sector'),
                    'id_segmento' => $request->input('id_segmento'),
                    'id_vendedor' => $request->filled('id_vendedor')
                        ? $request->input('id_vendedor')
                        : null,
                ]);

                // Actualizar el contacto principal
                $contacto = Contacto::updateOrCreate(
                    [
                        'id_cliente' => $cliente->id_cliente,
                        'predeterminado' => 1
                    ],
                    [
                        'nombre' => $request->input('nombre'),
                        'apellido_p' => $request->input('apellido_p'),
                        'apellido_m' => $request->input('apellido_m'),
                        'email' => $request->input('email'),
                        'puesto' => null, // Cuentas personales no manejan puesto
                        'genero' => $request->input('genero'),
                    ]
                );

                // ➋ teléfonos / extensiones
                for ($i = 1; $i <= 5; $i++) {
                    $contacto->{"telefono$i"} = digits_only($request->input("contacto.0.telefono$i"));
                    $contacto->{"ext$i"} = digits_only($request->input("contacto.0.ext$i"));
                    $contacto->{"celular$i"} = digits_only($request->input("contacto.0.celular$i"));
                }
                $contacto->save();

                //registrarNota es una función Helper, está definida en app/Helpers/
                $mensaje = "El usuario " . auth()->user()->nombre . " " .
                    auth()->user()->apellido_p . "" . auth()->user()->apellido_m .
                    " ha actualizado la cuenta personal [{$cliente->id_cliente}] -> {$cliente->nombre}.";

                registrarNota(
                    $cliente->id_cliente,
                    $mensaje,
                    $cliente->ciclo_venta,
                    null,
                    1
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
            null,
            1
        );
        return redirect('clientes')->with('success', 'Cliente eliminado correctamente');
    }
    public function store(Request $request)
    {
        //Guarda los datos de un cliente nuevo desde clientes.index
        \Log::info('Tipo recibido:', ['tipo' => $request->input('tipo')]);

        if ($request->input('sector') == 'persona') { // SI ES FISICA
            $rules = [
                /* === Cuenta Personal === */
                'nombre' => 'required|string|max:60',
                'apellido_p' => 'required|string|max:27',
                'apellido_m' => 'nullable|string|max:27',
                'id_vendedor' => 'nullable|integer|exists:usuarios,id_usuario',

                'ciclo_venta' => 'nullable|string|max:100',
                'estatus' => 'required|string|max:100',
                'tipo' => 'required|string|max:100',
                'sector' => 'nullable|string|max:100',

                // Datos personales …
                'email' => 'nullable|email|max:120',
                'id_segmento' => 'required|exists:segmentos,id_segmento',
                'genero' => 'nullable|string|max:17',
                'contacto.0.telefono*' => 'nullable|digits:10',
                'contacto.0.celular*' => 'nullable|digits:10',
                'contacto.0.ext*' => 'nullable|digits_between:1,6',
            ];
            $request->validate($rules);

            $datos = $request->all();
            $datos['id_vendedor'] = $request->filled('id_vendedor') ? (int) $request->input('id_vendedor') : null;

            // Guardar en la base de datos
            try {
                // GUARDAR CUENTA EJE PERSONAL
                $cliente = Cliente::create([
                    'nombre' => $request->input('nombre'),
                    'apellido_p' => $request->input('apellido_p'),
                    'apellido_m' => $request->input('apellido_m'),
                    'ciclo_venta' => $request->input('ciclo_venta'),
                    'estatus' => $request->input('estatus'),
                    'tipo' => $request->input('tipo'),
                    'sector' => $request->input('sector'),
                    'id_segmento' => $request->input('id_segmento'),

                    'id_vendedor' => $datos['id_vendedor']
                        ? $request->input('id_vendedor')
                        : null,
                ]);

                // Crear contacto principal (obligatorio)
                $contactos = $request->input('contacto', []);
                $contacto = $contactos[0] ?? null; // solo un contacto

                // Normaliza phones/exts
                foreach (range(1, 5) as $n) {
                    $contacto["telefono$n"] = digits_only($contacto["telefono$n"] ?? null);
                    $contacto["celular$n"] = digits_only($contacto["celular$n"] ?? null);
                    $contacto["ext$n"] = digits_only($contacto["ext$n"] ?? null);
                }

                Contacto::create([
                    'id_cliente' => $cliente->id_cliente,
                    'nombre' => $request->input('nombre'),
                    'apellido_p' => $request->input('apellido_p'),
                    'apellido_m' => $request->input('apellido_m'),
                    'email' => $request->input('email'),
                    'genero' => $request->input('genero'),
                    'puesto' => null, // o define si vendrá después

                    'telefono1' => $contacto['telefono1'] ?? null,
                    'ext1' => $contacto['ext1'] ?? null,
                    'telefono2' => $contacto['telefono2'] ?? null,
                    'ext2' => $contacto['ext2'] ?? null,
                    'telefono3' => $contacto['telefono3'] ?? null,
                    'ext3' => $contacto['ext3'] ?? null,
                    'telefono4' => $contacto['telefono4'] ?? null,
                    'ext4' => $contacto['ext4'] ?? null,
                    'telefono5' => $contacto['telefono5'] ?? null,
                    'ext5' => $contacto['ext5'] ?? null,

                    'celular1' => $contacto['celular1'] ?? null,
                    'celular2' => $contacto['celular2'] ?? null,
                    'celular3' => $contacto['celular3'] ?? null,
                    'celular4' => $contacto['celular4'] ?? null,
                    'celular5' => $contacto['celular5'] ?? null,

                    'predeterminado' => 1,
                ]);


                //registrarNota es una función Helper, está definida en app/Helpers/
                $mensaje = "El usuario " . auth()->user()->NombreCompleto .
                    " ha creado la cuenta empresarial [{$cliente->id_cliente}]  {$cliente->nombre}.";

                registrarNota(
                    $cliente->id_cliente,
                    $mensaje,
                    $cliente->ciclo_venta,
                    null,
                    1
                );

                return redirect(to: '/clientes')->with('success', 'Cuenta personal creada correctamente');
            } catch (Exception $e) {
                return redirect('')->with('error', $e->getMessage());
            }
        } else // SI ES EMPRESA
        {
            $rules = [
                /* -------- Cuenta -------- */
                'nombre' => ['required', 'string', 'max:100'],
                'sector' => ['required', 'string', 'max:100'],
                'id_segmento' => ['required','integer', 'exists:segmentos,id_segmento'],
                'id_vendedor' => ['nullable', 'integer', 'exists:usuarios,id_usuario'],

                /* -------- Contacto(s) ---- */
                'contacto.*.nombre' => ['nullable', 'string', 'max:60'],
                'contacto.*.apellido_p' => ['nullable', 'string', 'max:27'],
                'contacto.*.apellido_m' => ['nullable', 'string', 'max:27'],
                'contacto.*.email' => ['nullable', 'email', 'max:120'],
                'contacto.*.puesto' => ['nullable', 'string', 'max:60'],

                // Teléfonos / celulares  –  10 dígitos exactos o nulo
                'contacto.*.telefono*' => ['nullable', 'string'],
                'contacto.*.celular*' => ['nullable', 'string'],

                // Extensiones 1-6 dígitos
                'contacto.*.ext*' => ['nullable', 'regex:/^\d{1,7}$/'],
            ];
            $request->validate($rules);




            // Guardar en la base de datos
            // Para crear una cuenta eje, se necesitan los datos de la cuenta y al menos un contacto.
            try {
                // GUARDAR CUENTA EJE EMPRESARIAL
                $cliente = Cliente::create([
                    'nombre' => $request->input('nombre'),
                    'ciclo_venta' => $request->input('ciclo_venta'),
                    'estatus' => $request->input('estatus'),
                    'tipo' => $request->input('tipo'),
                    'sector' => $request->input('sector'),
                    'id_segmento' => $request->input('id_segmento'),
                    'id_vendedor' => $request->filled('id_vendedor')
                        ? $request->input('id_vendedor')
                        : null,
                ]);

                // Crear contacto principal (obligatorio)
                $contactos = $request->input('contacto', []);  // trae un array de arrays: [ 0 => [...], 1 => [...], ... ]
                if (count($contactos) > 0) {
                    foreach ($contactos as $cont) {
                        if (empty($cont['nombre']))
                            continue;        // ficha vacía

                        // Normaliza phones/exts
                        foreach (range(1, 5) as $n) {
                            $cont["telefono$n"] = digits_only($cont["telefono$n"] ?? null);
                            $cont["celular$n"] = digits_only($cont["celular$n"] ?? null);
                            $cont["ext$n"] = digits_only($cont["ext$n"] ?? null);
                        }

                        Contacto::create([
                            'id_cliente' => $cliente->id_cliente,
                            'nombre' => $cont['nombre'],
                            'apellido_p' => $cont['apellido_p'] ?? null,
                            'apellido_m' => $cont['apellido_m'] ?? null,
                            'email' => $cont['email'] ?? null,
                            'puesto' => $cont['puesto'] ?? null,
                            'genero' => $cont['genero'] ?? null,

                            // teléfono/ext celular limpias
                            'telefono1' => $cont['telefono1'],
                            'ext1' => $cont['ext1'],
                            'telefono2' => $cont['telefono2'],
                            'ext2' => $cont['ext2'],
                            'telefono3' => $cont['telefono3'],
                            'ext3' => $cont['ext3'],
                            'telefono4' => $cont['telefono4'],
                            'ext4' => $cont['ext4'],
                            'telefono5' => $cont['telefono5'],
                            'ext5' => $cont['ext5'],
                            'celular1' => $cont['celular1'],   // …igual 1-5
                            'celular2' => $cont['celular2'],
                            'celular3' => $cont['celular3'],
                            'celular4' => $cont['celular4'],
                            'celular5' => $cont['celular5'],

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
                    null,
                    1
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
    public function storeNota(Request $request, $id)
    {
        $request->validate([
            'contenido' => 'required|string',
            'fecha_reprogramacion' => 'nullable|date',
        ]);

        $fecha_reprogramacion = $request->input('fecha_reprogramacion') ?? now()->addDays(3);
        //Actualiza el recall del cliente
        Cliente::where('id_cliente', $id)->update(['recall' => $fecha_reprogramacion]);
        //Solo la llamada del helper en storeNota es manual, todas las demas son automáticas.
        registrarNota(
            id_cliente: $id,
            contenido: $request->input('contenido'),
            etapa: $request->input('ciclo_venta'),
            fecha_reprogramacion: $fecha_reprogramacion,
            es_automatico: $request->input('es_automatico')
        );

        return redirect()->route('clientes.view', $id)->with('success', 'Nota registrada correctamente.')->withFragment('historialNotas');
    }
    /**
     * Muestra el formulario para traspasar múltiples clientes.
     */
    public function transfer(Request $request)
    {
        $vendedores = Usuario::whereNull('id_cliente')->get();

        $lado = $request->input('lado'); // 'origen' o 'destino'
        $query = Cliente::with(['primerContacto', 'vendedor'])
            ->where('estatus', 'activo');

        // Valor correcto de ID vendedor según el lado
        $idVendedor = match ($lado) {
            'origen' => $request->input('id_vendedor_origen'),
            'destino' => $request->input('id_vendedor_destino'),
            default => null,
        };

        if ($idVendedor === 'base') {
            $query->where(fn($q) => $q->whereNull('id_vendedor')->orWhere('id_vendedor', 0));
        } elseif ($idVendedor) {
            $query->where('id_vendedor', $idVendedor);
        }

        // Resto de filtros visuales
        if ($ciclo = $request->input('ciclo_venta')) {
            $query->where('ciclo_venta', $ciclo);
        }

        if ($orden = $request->input('orden')) {
            $query->orderBy($orden);
        } else {
            $query->orderBy('id_cliente');
        }
        if ($request->input('lado')) {
            $perPage = is_numeric($request->input('per_page'))
                ? (int) $request->input('per_page')
                : 25;

            $clientes = $query->paginate($request->input('per_page', 25))->withQueryString();
        } else {
            $clientes = null;
        }

        return view('clientes.transfer', compact('vendedores', 'clientes', 'lado', 'idVendedor'));
    }
    /**
     * Procesa el traspaso: actualiza id_vendedor de los clientes seleccionados.
     */
    public function transferStore(Request $request)
    {
        $ids = $request->input('clientes', []);
        $origen = $request->input('origen');
        $destino = $request->input('destino');


        if (empty($ids)) {
            return back()->with('error', 'No se seleccionó ningún cliente.');
        }

        $destinoId = ($destino === 'base' || $destino === null || $destino === '0') ? null : (int) $destino;

        Cliente::whereIn('id_cliente', $ids)->update(['id_vendedor' => $destinoId]);

        // Registra UNA nota por cliente (recomendado para trazabilidad)
        foreach ($ids as $idCliente) {
            // Obtener nombres reales de origen y destino
            $nombre_origen = ($origen === 'base' || $origen === null || $origen === '0')
                ? 'BASE GENERAL'
                : optional(Usuario::find($origen))->nombreCompleto ?? 'Vendedor desconocido';

            $nombre_destino = ($destino === 'base' || $destino === null || $destino === '0')
                ? 'BASE GENERAL'
                : optional(Usuario::find($destino))->nombreCompleto ?? 'Vendedor desconocido';

            registrarNota(
                id_cliente: $idCliente,
                contenido: sprintf(
                    'El usuario %s traspasó la cuenta del vendedor %s al vendedor %s.',
                    auth()->user()->nombreCompleto,
                    $nombre_origen,
                    $nombre_destino
                ),
                etapa: $request->input('ciclo_venta'),
                fecha_reprogramacion: null,
                es_automatico: true
            );
        }


        return redirect()->route('clientes.transfer')
            ->with('success', count($ids) . ' cuentas traspasadas.');
    }
    //Archivado de Cuentas de Clientes desde Traspaso de Cuentas
    public function archive(Request $request)
    {
        $ids = $request->input('selected_clients', []);
        if (empty($ids)) {
            return redirect()
                ->route('clientes.transfer', $request->query())
                ->with('warning', 'No seleccionaste ninguna cuenta.');
        }

        Cliente::whereIn('id_cliente', $ids)
            ->update(['estatus' => 'inactivo']);

        // Registra nota por cada cuenta
        foreach ($ids as $idCliente) {
            registrarNota(
                id_cliente: $idCliente,
                contenido: sprintf(
                    'El usuario %s archivó esta cuenta.',
                    auth()->user()->nombre_completo
                ),
                etapa: 'inactivo',  // o la etapa que uses
                es_automatico: true
            );
        }

        return redirect()
            ->route('clientes.transfer', $request->query())
            ->with('success', 'Cuentas archivadas correctamente.');
    }

    // Recalls
    public function recalls(Request $request)
    {
        $id_vendedor = $request->input('id_vendedor');
        $busqueda = $request->input('busqueda');
        $perPage = $request->input('ver', 50);

        $ejecutivos = Usuario::where('estatus', 'activo')
            ->get()
            ->sortBy('nombre_completo')
            ->pluck('nombre_completo', 'id_usuario');

        $query = Cliente::with(['primerContacto', 'vendedor'])
            ->whereNotNull('recall')
            ->whereDate('recall', '<=', today());

        if ($id_vendedor) {
            $query->where('id_vendedor', $id_vendedor);
        }

        if (auth()->user()->hasRole('Ventas')) {
            $query->where('id_vendedor', auth()->user()->id_usuario);
        }

        if ($busqueda) {
            $query->where(function ($q) use ($busqueda) {
                $q->where('nombre', 'like', "%$busqueda%")
                    ->orWhere('id_cliente', 'like', "%$busqueda%")
                    ->orWhereHas('primerContacto', fn($q2) =>
                    $q2->where('telefono1', 'like', "%$busqueda%")
                        ->orWhere('email', 'like', "%$busqueda%"));
            });
        }

        $clientes = $query->orderBy('recall')->paginate($perPage)->appends($request->all());

        return view('clientes.recalls', compact('clientes', 'ejecutivos'));
    }

    public function archivadas(Request $request)
    {
        // 1) consulta base + relaciones
        $query = Cliente::with(['primerContacto', 'vendedor'])
            ->where('estatus', 'inactivo');
        /* ---------- Filtros ---------- */

        // Búsqueda global
        if ($term = $request->input('search')) {
            $query->where(function ($q) use ($term) {
                $q->where('nombre', 'like', "%{$term}%")
                    ->orWhere('id_cliente', 'like', "%{$term}%")
                    ->orWhereHas('primerContacto', fn($q2) =>
                    $q2->where('telefono1', 'like', "%{$term}%"));
            });
        }

        // Ejecutivos
        if ($ejecutivos = $request->input('ejecutivos')) {
            $query->where(function ($q) use ($ejecutivos) {
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
        $vendedores = Usuario::whereNull('id_cliente')
            ->where('estatus', 'activo')
            ->get();

        // Los sacamos directo de la tabla para que no se “desincronicen”
        $sectores = Cliente::select('sector')->distinct()->pluck('sector');
        $segmentos = Cliente::select('segmento')->distinct()->pluck('segmento');
        $ciclos = Cliente::select('ciclo_venta')->distinct()->pluck('ciclo_venta');

        return view('clientes.archivadas', compact(
            'clientes',
            'vendedores',
            'sectores',
            'segmentos',
            'ciclos',
        ));
    }

    public function restaurarMultiples(Request $request)
    {
        $ids = $request->input('ids', []);
        $id_vendedor = $request->input('id_vendedor');

        if (empty($ids)) {
            return back()->with('error', 'Selecciona al menos una cuenta.');
        }

        if (!$id_vendedor) {
            return back()->with('error', 'Selecciona un ejecutivo destino.');
        }

        // Determina si va a la BASE GENERAL (sin id_vendedor)
        $asignacion = $id_vendedor === 'base' ? null : $id_vendedor;

        // Actualiza las cuentas con estatus activo y el ejecutivo (o null)
        Cliente::whereIn('id_cliente', $ids)->update([
            'estatus'     => 'activo',
            'id_vendedor' => $asignacion,
        ]);

        // Obtiene nombre del ejecutivo si aplica
        $nombreDestino = $asignacion
            ? Usuario::find($asignacion)?->nombre_completo ?? "ID #$asignacion"
            : 'BASE GENERAL';

        // Registrar nota para cada cuenta
        foreach ($ids as $idCliente) {
            $contenidoNota = sprintf(
                'El usuario %s restauró esta cuenta y la asignó a %s.',
                auth()->user()->nombreCompleto,
                $nombreDestino
            );

            registrarNota(
                id_cliente: $idCliente,
                contenido: $contenidoNota,
                etapa: null,
                fecha_reprogramacion: null,
                es_automatico: true
            );
        }

        return back()->with('success', 'Cuentas restauradas correctamente.');
    }
}

/**
 * Mantiene sólo dígitos (útil para teléfonos/extensiones)
 */
function digits_only(?string $v): ?string
{
    if ($v === null)
        return null;
    $clean = preg_replace('/\D/', '', $v);
    return $clean === '' ? null : $clean;
}
