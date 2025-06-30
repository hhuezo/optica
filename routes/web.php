<?php

use App\Http\Controllers\administracion\BodegaController;
use App\Http\Controllers\administracion\ClienteController;
use App\Http\Controllers\administracion\EmpresaController;
use App\Http\Controllers\administracion\ProductoController;
use App\Http\Controllers\administracion\SucursalController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\catalogo\MarcaController;
use App\Http\Controllers\inventario\DocumentoController;
use App\Http\Controllers\inventario\ReportesController;
use App\Http\Controllers\seguridad\PermissionController;
use App\Http\Controllers\seguridad\RoleController;
use App\Http\Controllers\seguridad\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('/', [LoginController::class, 'showLoginForm']);

Auth::routes();




Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


    //seguridad
    Route::resource('seguridad/permission', PermissionController::class);
    Route::post('seguridad/role/update_permission', [RoleController::class, 'updatePermission']);
    Route::resource('seguridad/role', RoleController::class);
    Route::post('seguridad/user/update_password/{id}', [UserController::class, 'updatePassword']);
    Route::resource('seguridad/user', UserController::class);

    //catalogos
    Route::resource('catalogo/marca', MarcaController::class);
    Route::resource('empresa', EmpresaController::class);
    Route::resource('sucursal', SucursalController::class);
    Route::resource('bodega', BodegaController::class);

    Route::get('producto/get_productos/{id}', [ProductoController::class, 'get_productos']);
    Route::resource('producto', ProductoController::class);

    //ventas
    Route::get('cliente/contrato_show/{id}', [ClienteController::class, 'contrato_show'])->name('cliente.contrato.show');


    //eliminar
    Route::get('cliente/contrato_create/{id}', [ClienteController::class, 'contrato_create']);



    Route::post('cliente/validar_contrato_store/{id}', [ClienteController::class, 'validar_contrato_store']);
    Route::post('cliente/contrato_detalle_store/{id}', [ClienteController::class, 'contrato_detalle_store'])->name('cliente.contrato_detalle.store');
    Route::delete('cliente/contrato_detalle_delete/{id}', [ClienteController::class, 'contrato_detalle_delete'])->name('cliente.contrato_detalle.delete');
    Route::post('cliente/contrato_store/{id}', [ClienteController::class, 'contrato_store'])->name('cliente.contrato.store');
    Route::post('cliente/contrato_store/{id}', [ClienteController::class, 'contrato_store'])->name('cliente.contrato.store');
    Route::post('cliente/contrato_procesar/{id}', [ClienteController::class, 'processContract']);
    Route::post('cliente/contrato_empleado_store/{id}', [ClienteController::class, 'contrato_empleado_store']);
    Route::post('cliente/contrato_abono/{id}', [ClienteController::class, 'contrato_abono']);


    Route::get('cliente/contrato_reporte/{id}', [ClienteController::class, 'contrato_reporte']);
    Route::post('cliente/contrato_abono/{id}', [ClienteController::class, 'contrato_abono']);
    Route::get('cliente/get_cliente/{id}', [ClienteController::class, 'get_cliente']);
    Route::get('clientes/data', [ClienteController::class, 'data']);
    Route::post('clientes/update_record/{id}', [ClienteController::class, 'update_record']);
    Route::resource('cliente', ClienteController::class);


    //inventario
    Route::post('documento/detalle/store/{id}', [DocumentoController::class, 'detalle_store'])->name('documento.detalle.store');

    Route::get('documento/ingreso/{id}', [DocumentoController::class, 'ingreso']);
    Route::post('documento/procesar_ingreso/{id}', [DocumentoController::class, 'procesarIngreso'])->name('documento.procesar_ingreso');

    Route::get('documento/traslado/{id}', [DocumentoController::class, 'traslado']);
    Route::post('documento/procesar_traslado/{id}', [DocumentoController::class, 'procesarTraslado'])->name('documento.procesar_traslado');

    Route::get('documento/salida/{id}', [DocumentoController::class, 'salida']);
    Route::post('documento/procesar_salida/{id}', [DocumentoController::class, 'procesarSalida'])->name('documento.procesar_salida');

    Route::get('documento/ajuste/{id}', [DocumentoController::class, 'ajuste']);
    Route::post('documento/procesar_ajuste/{id}', [DocumentoController::class, 'procesarAjuste'])->name('documento.procesar_ajuste');

    Route::post('documento/detalle_store', [DocumentoController::class, 'detalleStore']);
    Route::delete('documento/detalle_destroy/{id}', [DocumentoController::class, 'detalleDestroy'])->name('documento.detalle.destroy');

    Route::get('documento/reporte/{id}', [DocumentoController::class, 'reporte']);
    Route::get('documento/data', [DocumentoController::class, 'data']);

    Route::resource('documento', DocumentoController::class);


    //reportes
    Route::get('reportes/sector/{id}/{exportar}', [ReportesController::class, 'sector']);
    Route::get('reportes/estado_cuenta_empresa/{exportar}', [ReportesController::class, 'estado_cuenta_empresa']);
    Route::get('reportes/estado_cuenta_por_empresa/{id}/{exportar}', [ReportesController::class, 'estado_cuenta_por_empresa']);
    Route::get('reportes/comisiones/{id}', [ReportesController::class, 'comisiones']);
    Route::get('reportes/estado_pago', [ReportesController::class, 'estado_pago']);

    Route::get('reportes/existencia', [ReportesController::class, 'existencia']);
    Route::get('reportes/generales', [ReportesController::class, 'generales']);
    Route::get('reportes/pagos_mensuales', [ReportesController::class, 'pagos_mensuales']);
});
