<?php

//Importar cada controlador que necesites, uno por uno.
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\UsuarioController;


//La ruta necesita dos parámetros: La dirección y una función, o un método de controlador.
Route::get('/', function () {
    return view('inicio');
});

//CRUD Clientes
//create
Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
//read
Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
//update
Route::get('/clientes/{cliente}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
Route::put('/clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update');
//delete
Route::delete('/clientes/{cliente}', [ClienteController::class, 'delete'])->name('clientes.delete');


//name sirve para darle un alias a la ruta y que puedes usar en todo el código de Laravel
// por ejemplo: <a href="{{ route('clientes.create') }}">Crear Cliente</a>


//CRUD Usuarios
Route::resource('usuarios', UsuarioController::class);
Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create');
Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
Route::get('/usuarios/{usuario}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
