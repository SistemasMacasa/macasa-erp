<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Usuario;

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
        $vendedores = Usuario::whereNull('id_cliente')->get(); // usuarios internos
        return view('clientes.create', compact('vendedores'));
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
        // \Log::info('Entró a store correctamente');

        // Validar datos mínimos
        $request->validate([
            'nombre' => 'required|max:100',
            'apellido' => 'nullable|max:100',
            'estatus' => 'required',
            'tipo' => 'required',
            'id_vendedor' => 'required|integer'
        ]);        
        // Guardar en la base de datos
        try {
            \App\Models\Cliente::create($request->all());
            return redirect('/clientes')->with('success', 'Cliente creado correctamente');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    
        // Redirigir a la lista
        return redirect('clientes.index')->with('success', 'Cliente creado correctamente');
    }
}
