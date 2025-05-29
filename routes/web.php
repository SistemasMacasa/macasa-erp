<?php

//Importar cada controlador que necesites, uno por uno.
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;


//La ruta necesita dos parámetros: La dirección y una función, o un método de controlador.
// Route::get('/', function () {
//     return view('inicio');
// })->name('inicio');

Route::get('/', [DashboardController::class, 'index'])->name('inicio');


//CRUD Clientes (Cuentas Eje)
//create
Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
//read
Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
//update
Route::get('/clientes/{cliente}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
Route::put('/clientes/update/{id}', [ClienteController::class, 'update'])->name('clientes.update');
//delete
Route::delete('/clientes/{cliente}', [ClienteController::class, 'delete'])->name('clientes.delete');

Route::get('/clientes/view/{id}', [ClienteController::class, 'view'])->name('clientes.view');

Route::middleware('auth')->group(function() {
    // Mostrar formulario de transferencia
    Route::get('/clientes/transfer', [ClienteController::class, 'transfer'])
         ->name('clientes.transfer');

    // Procesar transferencia
    Route::post('/clientes/transfer', [ClienteController::class, 'transferStore'])
         ->name('clientes.transfer.store');
});

//name sirve para darle un alias a la ruta y que puedes usar en todo el código de Laravel
// por ejemplo: <a href="{{ route('clientes.create') }}">Crear Cliente</a>

//CRUD Usuarios internos de SIS
Route::resource('usuarios', UsuarioController::class);

//Login y Logout
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::middleware(['auth'])->get('/', function () {
        return view('inicio');
    })->name('inicio');
    
    // ... y cualquier otra ruta que quieras proteger
});

