<?php

use App\Http\Controllers\RoutingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\FichajeController;
use App\Http\Controllers\ExportController;
use Illuminate\Http\Request;
use App\Exports\ResumenHorasExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\TerminalFichajeController;


require __DIR__ . '/auth.php';

Route::group(['prefix' => '/', 'middleware' => 'auth'], function () {
    Route::get('', [RoutingController::class, 'index'])->name('root');


    Route::get('users', [RoutingController::class, 'users'])->name('users')->middleware('checkRole:admin,superadmin');
    Route::get('users/create', [RoutingController::class, 'create'])->name('users.create');
    Route::post('users/store', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');


    /* Rutas para empresas (Pagina Principal) */
    Route::get('/empresas', [RoutingController::class, 'empresas'])->name('empresas')->middleware('checkRole:admin,superadmin');
    Route::post('/empresas/store', [EmpresaController::class, 'store'])->name('empresas.store');
    Route::put('/empresas/update/{id}', [EmpresaController::class, 'update'])->name('empresas.update');
    Route::delete('/empresas/delete/{id}', [EmpresaController::class, 'destroy'])->name('empresas.destroy');

    /* Rutas para empleados */
    Route::get('/empleados', [RoutingController::class, 'empleados'])->name('empleados')->middleware('checkRole:admin,superadmin');
    Route::post('/empleados/store', [EmpleadoController::class, 'store'])->name('empleados.store');
    Route::put('/empleados/update/{id}', [EmpleadoController::class, 'update'])->name('empleados.update');
    Route::delete('empleados/delete/{id}', [EmpleadoController::class, 'destroy'])->name('empleados.destroy');

    /* Rutas de Registros de Fichaje */
    Route::get('/registrosFichajes/{empresaId}', [RoutingController::class, 'registrosFichajes'])->name('registrosFichajes');
    Route::get('/empresa/{empresaId}/alertas-fichajes', [RoutingController::class, 'verAlertasFichajes'])->name('alertas.fichajes');
    Route::delete('/incidencias/{empresaId}/limpiar', [IncidenciaController::class, 'limpiarAprobadasDescartadas'])->name('incidencias.limpiar');


    /* Rutas para fichar la hora de entrada */
    Route::get('/fichaje', [RoutingController::class, 'fichaje'])->name('fichaje')->middleware('checkRole:empleado');
    Route::post('/fichaje/store', [FichajeController::class, 'store'])->name('fichaje.store');
    Route::put('/fichaje/update', [FichajeController::class, 'update'])->name('fichaje.update')->middleware('checkRole:admin,superadmin');
    Route::delete('/fichaje/delete/{id}', [FichajeController::class, 'destroy'])->name('fichaje.destroy')->middleware('checkRole:admin,superadmin');

    /* Rutas para editar un fichaje */
    Route::get('/fichaje/manual/{id}', [RoutingController::class, 'fichajeManual'])->name('fichaje.manual')->middleware('checkRole:admin,superadmin');
    Route::post('/fichaje/manual/store', [FichajeController::class, 'storeManual'])->name('fichaje.manual.store');
    Route::get('/fichajes/por-dia', [FichajeController::class, 'getFichajesPorDia'])->name('fichajes.por-dia');
    Route::get('/empresa/{id}/resumen-horas', [FichajeController::class, 'resumenHoras'])->name('resumen.horas');

    /* Incidencias */
    Route::get('/incidencias', [RoutingController::class, 'incidencias'])->name('incidencias')->middleware('checkRole:empleado');
    Route::post('/incidencias/store', [IncidenciaController::class, 'store'])->name('incidencias.store');
    Route::get('/admin/incidencias/{empresaId}', [IncidenciaController::class, 'verPorEmpresa'])->name('admin.incidencias.empresa')->middleware('checkRole:admin,superadmin');
    Route::post('/admin/incidencias/aprobar/{id}', [IncidenciaController::class, 'aprobar'])->name('admin.incidencias.aprobar')->middleware('checkRole:admin,superadmin');
    Route::post('/admin/incidencias/descartar/{id}', [IncidenciaController::class, 'descartar'])
        ->name('admin.incidencias.descartar');




    Route::get('/provisional', [RoutingController::class, 'provicional'])->name('provisional');

    Route::get('/mi-actividad', [RoutingController::class, 'miActividad'])->name('mi.actividad')->middleware('auth', 'checkRole:empleado');


    Route::middleware(['auth', 'checkRole:encargado,admin,superadmin'])
        ->prefix('terminal')
        ->group(function () {

            Route::get('/fichaje', [TerminalFichajeController::class, 'index'])
                ->name('terminal.fichaje');
        });




    /* Rutas para exportar en EXCEL */
    Route::get('/exportar-fichajes/{empresaId}', [ExportController::class, 'export'])->name('export.fichajes');
    Route::get('/empresa/{empresaId}/exportar-horas', function (Request $request, $empresaId) {
        // Usa el mismo controlador que ya tienes para calcular el resumen
        $controller = app(\App\Http\Controllers\FichajeController::class);
        $datos = $controller->resumenHoras($request, $empresaId, true); // modo exportación

        return Excel::download(
            new ResumenHorasExport($datos['resumen'], $datos['empresa'], $datos['desde'], $datos['hasta']),
            'resumen_horas.xlsx'
        );
    });




    /* Ruta para comprobar si un email esta en uso */
    Route::get('/check-email', [UserController::class, 'checkEmail'])->name('users.checkEmail');


    /* Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
    Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
    Route::get('{any}', [RoutingController::class, 'root'])->name('any'); */
});
