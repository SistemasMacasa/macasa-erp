<?php

//Importar cada controlador que necesites, uno por uno.
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CotizacionController;


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

Route::post('/clientes/{id}/nota', [ClienteController::class, 'storeNota'])->name('clientes.nota.store');
// Mostrar formulario de transferencia
Route::get('/clientes/transfer', [ClienteController::class, 'transfer'])
        ->name('clientes.transfer');

// Procesar transferencia
Route::post('/clientes/transfer', [ClienteController::class, 'transferStore'])
        ->name('clientes.transfer.store');

Route::get('/clientes/recalls', [ClienteController::class, 'recalls'])->name('clientes.recalls');
//CRUD Usuarios internos de SIS
//Eric: Cambiar el ruteo, no usar resource(), definir cada ruta a mano
Route::resource('usuarios', UsuarioController::class);

//Login y Logout
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');

//Protección de rutas, se requiere login.
Route::middleware(['auth'])->group(function () {

    Route::middleware(['auth'])->get('/', function () {
        return view('inicio');
    })->name('inicio');
    
});


// === Cotizaciones ===
Route::get('/cotizaciones', [CotizacionController::class, 'index'])->name('cotizaciones.index');
Route::get('/cotizaciones/create/{cliente}', [CotizacionController::class, 'create'])->name('cotizaciones.create');

//Guardar Dirección de factura mediante AJAX
Route::post('/ajax/direccion-factura', [CotizacionController::class, 'storeRazonSocialFactura'])
     ->name('ajax.direccion.factura');