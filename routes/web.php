<?php

//Importar cada controlador que necesites, uno por uno.
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\RoleController;

//La ruta necesita dos parámetros: La dirección y una función, o un método de controlador.
// Route::get('/', function () {
//     return view('inicio');
// })->name('inicio');

//Protección de rutas, se requiere login.
Route::middleware(['auth'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('inicio');

});

Route::middleware(['auth', 'permission:Nueva Cuenta'])->group(function () {
    //create
    Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
    Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');

});

Route::middleware(['auth', 'permission:Mis Cuentas'])->group(callback: function () {
    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
});
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

Route::get('/clientes/archivadas', [ClienteController::class, 'archivadas'])
    ->name('clientes.archivadas');

//CRUD Usuarios internos de SIS
//Eric: Cambiar el ruteo, no usar resource(), definir cada ruta a mano
Route::resource('usuarios', UsuarioController::class);


//Inicio de Sesión
Route::middleware(['auth'])->get('/', function () {
    return view('inicio');
})->name('inicio');

// === Cotizaciones ===
Route::get('/cotizaciones', [CotizacionController::class, 'index'])->name('cotizaciones.index');
Route::get('/cotizaciones/create/{cliente}', [CotizacionController::class, 'create'])->name('cotizaciones.create');

//Guardar Dirección de factura mediante AJAX
Route::post('/ajax/direccion-factura', [CotizacionController::class, 'storeRazonSocialFactura'])
    ->name('ajax.direccion.factura');


//Login y Logout
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');


Route::middleware(['auth', 'permission:permisos.index'])->group(function () {
    Route::get('/permisos', [PermisoController::class, 'index'])->name('permisos.index');
    Route::post('/permisos/asignar', [PermisoController::class, 'asignarPermiso'])->name('permisos.asignar');
    Route::post('/permisos/remover', [PermisoController::class, 'removerPermiso'])->name('permisos.remover');
    Route::get(
        'permisos-catalogo',
        [PermisoController::class, 'catalogPermisos']
    )
        ->name('permisos.catalogo.index');
    Route::post(
        'permisos-catalogo',
        [PermisoController::class, 'storePermiso']
    )
        ->name('permisos.catalogo.store');
    Route::delete(
        'permisos-catalogo/{permission}',
        [PermisoController::class, 'destroyPermisoCatalog']
    )
        ->name('permisos.catalogo.destroy');
    // Quitar un permiso de un rol desde el catálogo de permisos
    Route::delete(
        'permisos-catalogo/{permission}/roles/{role}',
        [PermisoController::class, 'removerPermisoDeRol']
    )->name('permisos.catalogo.removerRol');

    // GET /api/permisos-catalogo/{permission}/roles
    Route::get(
        'permisos-catalogo/{permission}/roles',
        [PermisoController::class, 'rolesForPermission']
    )
        ->name('permisos.catalogo.roles');

});

Route::get('api/permisos-usuario/{id}', [PermisoController::class, 'permisosUsuario'])
    ->middleware('auth')
    ->name('permisos.usuario');

Route::middleware('auth')->group(function () {
    // API para cargar roles de un usuario
    Route::get('/api/roles-usuario/{id}', [PermisoController::class, 'rolesUsuario'])
        ->name('roles.usuario');

    // Asignar rol
    Route::post('/roles/asignar', [PermisoController::class, 'asignarRol'])
        ->name('roles.asignar');

    // Quitar rol
    Route::post('/roles/remover', [PermisoController::class, 'removerRol'])
        ->name('roles.remover');
});

//Roles
Route::middleware(['auth', 'permission:roles.index'])->group(function () {

    // Carga listado de roles con conteos (JSON)
    Route::get('/api/roles', [RoleController::class, 'index'])->name('api.roles');

    // Crear un nuevo rol
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');

    // Eliminar rol
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    // Cargar permisos de un rol
    Route::get('/api/roles/{role}/permisos', [RoleController::class, 'permisos'])->name('api.roles.permisos');

    // Sincronizar permisos de un rol
    Route::post('/roles/{role}/permisos', [RoleController::class, 'syncPermisos'])->name('roles.permisos.sync');
});