<?php

//Importar cada controlador que necesites, uno por uno.
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RazonSocialController;
use App\Http\Controllers\ContactoController;

//La ruta necesita dos parámetros: La dirección y una función, o un método de controlador.
// Route::get('/', function () {
//     return view('inicio');
// })->name('inicio');

//Login y Logout
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');


//Protección de rutas, se requiere login.
Route::middleware(['auth'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('inicio');
    Route::post('/clientes/{id}/nota', [ClienteController::class, 'storeNota'])->name('clientes.nota.store');
});

//Inicio de Sesión
Route::middleware(['auth'])->get('/', function () {
    return view('inicio');
})->name('inicio');

Route::middleware(['auth', 'permission:Nueva Cuenta'])->group(function () {

    //Mostrar Nueva Cuenta
    Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
    //Procesar Nueva Cuenta 
    Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
});

Route::middleware(['auth', 'permission:Mis Cuentas'])->group(callback: function () {

    //Mostrar Mis Cuentas
    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
});

Route::middleware(['auth', 'permission:Editar Cuenta'])->group(callback: function () {

    //Mostrar formulario Actualizar Cuenta
    Route::get('/clientes/{cliente}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
    //Procesar Actualizar Cuenta
    Route::put('/clientes/update/{id}', [ClienteController::class, 'update'])->name('clientes.update');
});

Route::middleware(['auth', 'permission:Archivar Cuenta'])->group(callback: function () {

    //Archivar y Borrar Cuenta
    Route::delete('/clientes/{cliente}', [ClienteController::class, 'delete'])->name('clientes.delete');
    //Archivar Cuenta desde clientes/trasnfer
    Route::post('clientes/archive', [ClienteController::class, 'archive'])
        ->name('clientes.archive');
});

Route::middleware(['auth', 'permission:Restaurar Cuenta'])->group(callback: function () {
    //Restaurar Cuenta desde clientes/archivadas
    Route::post('clientes/restaurar-multiples', [ClienteController::class, 'restaurarMultiples'])
        ->name('clientes.restaurar-multiples');
});

Route::middleware(['auth', 'permission:Ver Cuenta'])->group(callback: function () {

    //Ver Cuenta
    Route::get('/clientes/view/{id}', [ClienteController::class, 'view'])->name('clientes.view');
});

Route::middleware(['auth', 'permission:Traspaso de Cuenta'])->group(callback: function () {

    // Mostrar formulario de transferencia
    Route::get('/clientes/transfer', [ClienteController::class, 'transfer'])
        ->name('clientes.transfer');
    // Procesar transferencia
    Route::post('/clientes/transfer', [ClienteController::class, 'transferStore'])
        ->name('clientes.transfer.store');
});

Route::middleware(['auth', 'permission:Mis Recalls'])->group(callback: function () {

    //Mostrar Mis Recall's
    Route::get('/clientes/recalls', [ClienteController::class, 'recalls'])->name('clientes.recalls');
});

Route::middleware(['auth', 'permission:Cuentas Archivadas'])->group(callback: function () {

    //Mostrar Cuentas Archivadas
    Route::get('/clientes/archivadas', [ClienteController::class, 'archivadas'])
        ->name('clientes.archivadas');
});

Route::middleware(['auth', 'permission:Monitor de Cotizaciones'])->group(callback: function () {

    // === Cotizaciones ===
    Route::get('/cotizaciones', [CotizacionController::class, 'index'])->name('cotizaciones.index');
});

Route::middleware(['auth', 'permission:Levantar Cotizacion'])->group(callback: function () {

    //Mostrar Levantar Cotizacion
    Route::get('/cotizaciones/create/{cliente}', [CotizacionController::class, 'create'])->name('cotizaciones.create');
    //Seleccionar Razón Social para Cotización
    Route::post('/razones-sociales/{id}/seleccionar', [RazonSocialController::class, 'seleccionar'])->name('razones_sociales.seleccionar');
    //Procesar Guardar Cotización
    Route::post('/cotizaciones', [CotizacionController::class, 'store'])->name('cotizaciones.store');
    //Seleccionar Contacto para Cotización
    Route::post('/contactos/{contacto}/seleccionar', [ContactoController::class, 'seleccionar'])->name('contactos.seleccionar');
});

Route::middleware(['auth', 'permission:Crear Direcciones'])->group(callback: function () {

    //Guardar Dirección de factura mediante AJAX
    Route::post('/ajax/direccion-factura', [CotizacionController::class, 'storeRazonSocialFactura'])
        ->name('ajax.direccion.factura');
    //Guardar Dirección de entrega mediante AJAX
    Route::post(
        '/nueva-entrega',
        [CotizacionController::class, 'storeDireccionEntregaFactura']
    )->name('cotizaciones.nueva-entrega');

});

Route::middleware(['auth', 'permission:Permisos'])->group(callback: function () {

    //Mostrar Listado de Roles y Permisos
    Route::get('/permisos', [PermisoController::class, 'index'])->name('permisos.index');
    //Mostrar Modal del Catalogo de permisos
    Route::get(
        'permisos-catalogo',
        [PermisoController::class, 'catalogPermisos']
    )
        ->name('permisos.catalogo.index');
    // Ver roles en el catalogo de permisos
    Route::get(
        'permisos-catalogo/{permission}/roles',
        [PermisoController::class, 'rolesForPermission']
    )
        ->name('permisos.catalogo.roles');
    //Mostar la lista de permisos por usuario
    Route::get('api/permisos-usuario/{id}', [PermisoController::class, 'permisosUsuario'])
        ->middleware('auth')
        ->name('permisos.usuario');
    // Cargar roles de un usuario
    Route::get('/api/roles-usuario/{id}', [PermisoController::class, 'rolesUsuario'])
        ->name('roles.usuario');
    // Carga listado de roles con conteos (JSON)
    Route::get('/api/roles', [RoleController::class, 'index'])->name('api.roles');
    // Cargar permisos de un rol
    Route::get('/api/roles/{role}/permisos', [RoleController::class, 'permisos'])->name('api.roles.permisos');
});

Route::middleware(['auth', 'permission:Crear Nuevos Permisos'])->group(callback: function () {

    //Procesar Guardar un permiso
    Route::post(
        'permisos-catalogo',
        [PermisoController::class, 'storePermiso']
    )
        ->name('permisos.catalogo.store');
});

Route::middleware(['auth', 'permission:Eliminar Permisos'])->group(callback: function () {

    Route::delete(
        'permisos-catalogo/{permission}',
        [PermisoController::class, 'destroyPermisoCatalog']
    )
        ->name('permisos.catalogo.destroy');
});

Route::middleware(['auth', 'permission:Asignar Permisos'])->group(callback: function () {

    Route::post('/permisos/asignar', [PermisoController::class, 'asignarPermiso'])->name('permisos.asignar');
    // Sincronizar permisos de un rol
    Route::post('/roles/{role}/permisos', [RoleController::class, 'syncPermisos'])->name('roles.permisos.sync');
});

Route::middleware(['auth', 'permission:Desasignar Permisos'])->group(callback: function () {

    //Quitar un permiso desde el catálogo de roles
    Route::post('/permisos/remover', [PermisoController::class, 'removerPermiso'])->name('permisos.remover');
    // Quitar un permiso de un rol desde el catálogo de permisos
    Route::delete(
        'permisos-catalogo/{permission}/roles/{role}',
        [PermisoController::class, 'removerPermisoDeRol']
    )->name('permisos.catalogo.removerRol');
});

Route::middleware(['auth', 'permission:Asignar Rol'])->group(callback: function () {

    // Asignar rol
    Route::post('/roles/asignar', [PermisoController::class, 'asignarRol'])
        ->name('roles.asignar');
});

Route::middleware(['auth', 'permission:Desasignar Rol'])->group(callback: function () {

    // Quitar rol
    Route::post('/roles/remover', [PermisoController::class, 'removerRol'])
        ->name('roles.remover');
});

Route::middleware(['auth', 'permission:Crear Rol'])->group(callback: function () {

    // Crear un nuevo rol
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
});

Route::middleware(['auth', 'permission:Eliminar Rol'])->group(callback: function () {

    // Eliminar rol
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
});


Route::middleware(['auth', 'permission:Usuarios de SIS'])->group(function () {

    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
});

Route::middleware(['auth', 'permission:Crear Usuario'])->group(function () {

    Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create');
});

Route::middleware(['auth', 'permission:Editar Usuario'])->group(function () {

    Route::get('/usuarios/{usuario}/edit', [UsuarioController::class, 'edit'])
        ->name('usuarios.edit');

    Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])
        ->name('usuarios.update');
});


Route::middleware(['auth', 'permission:Eliminar Usuario'])->group(function () {

    Route::get('/usuarios/delete', [UsuarioController::class, 'delete'])->name('usuarios.delete');
});

Route::middleware(['auth', 'permission:Equipos de Trabajo'])->group(function () {
    Route::get('/equipos', [EquipoController::class, 'index'])->name('equipos.index');
});

Route::middleware(['auth', 'permission:Crear Equipos de Trabajo'])->group(function () {
    Route::get('/equipos/create', [EquipoController::class, 'create'])->name('equipos.create');
    Route::post('/equipos', [EquipoController::class, 'store'])->name('equipos.store');
});

Route::middleware(['auth', 'permission:Editar Equipos de Trabajo'])->group(function () {
    Route::get('/equipos/create', [EquipoController::class, 'create'])->name('equipos.create');
    Route::post('/equipos', [EquipoController::class, 'store'])->name('equipos.store');
});

Route::middleware(['auth', 'permission:Eliminar Equipos de Trabajo'])->group(function () {
    Route::delete('/equipos/{id}', [EquipoController::class, 'destroy'])->name('equipos.destroy');
});
