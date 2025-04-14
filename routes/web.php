<?php

//Importar cada controlador que necesites, uno por uno.
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hola', function () {
    return "Hola Mundo :v/"; 
});

//name sirve para darle un alias a la ruta y que puedes usar en todo el código de Laravel
// por ejemplo: <a href="{{ route('clientes.create') }}">Crear Cliente</a>
Route::get('/clientes/create', [ClienteController::class, 'create'])
    ->name('clientes.create');

//Puedes tener una misma ruta para diferentes métodos HTTP
Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');

Route::post('/clientes', [ClienteController::class, 'store'])
    ->name('clientes.store');

// routes/web.php
Route::get('/demo', function () {
    return view('clientes.index');
});
