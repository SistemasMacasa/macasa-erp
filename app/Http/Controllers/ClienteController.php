<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::with('vendedor')->get();

        //dd(Cliente::all());

        return view('clientes.index', compact('clientes'));
    }
    
    public function create()
    {
        //dd('create ok');
        return view('clientes.create');
    }
    
    
    public function store(Request $request)
    {
        \Log::info('Entró a store correctamente');

        // Validar datos mínimos
        $request->validate([
            'nombre' => 'required|max:100',
            'apellido' => 'nullable|max:100',
            'estatus' => 'required',
            'tipo' => 'required',
            'id_vendedor' => 'required|integer'
        ]);
        //dd($request->all());
        
        // Guardar en la base de datos
        try {
            \App\Models\Cliente::create($request->all());
            return redirect('/clientes')->with('success', 'Cliente creado correctamente');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    
        // Redirigir a la lista
        return redirect('/clientes')
            ->with('success', 'Cliente creado correctamente');
    }
}
