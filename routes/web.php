<?php

use App\Http\Controllers\CoordinacionController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\GerenciaController;
use App\Http\Controllers\JefaturaController;
use App\Http\Controllers\Todos;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionsController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [SessionsController::class, 'login'])
    ->name('login.index');

Route::post('/', [SessionsController::class, 'login_post'])
    ->name('login');

Route::get('logout', [SessionsController::class, 'destroy'])
    ->name('login.destroy');




//DIRECTOR
Route::get('/solicitudes', function () {
    Cookie::forget('laravel_session');
    Cache::flush();
    return view('Director/solicitudes');
})->middleware('auth.director');

Route::get('/solicitudes', [DirectorController::class, 'data1'])->middleware('auth.director');

Route::get('/solicitudes/datatable', [DirectorController::class, 'solicitudes'])->name('data.solicitudes')->middleware('auth.director');

//Esta ruta es para crear autorizaciones en todos los usuarios
Route::post('/solicitudes/crear', [DirectorController::class, 'solicitarAutorizacion'])->name('solicitar.autorizacion');

Route::post('/solicitudes/actualizar-{id}', [DirectorController::class, 'actualizardetalle'])->name('update.autorizacion');

Route::get('/filtrar', function () {
    Cookie::forget('laravel_session');
    Cache::flush();
    return view('Director.filtrar');
});

Route::get('/autorizacion', function () {
    Cookie::forget('laravel_session');
    Cache::flush();
    return view('Director.mostrarautorizacion');
});

Route::post('/autorizacion', [DirectorController::class, 'buscarautorizacion'])
->name('buscarautorizacion');


//COORDINACION

Route::middleware('auth.coord')->group(function () {
    Route::get('/validar', function () {
        Cookie::forget('laravel_session');
        Cache::flush();
        return view('Coordinacion/validarautorizacion');
    });

    Route::get('/validar', [CoordinacionController::class, 'data1'])->middleware('disable.cache');

    Route::get('validar/datatable', [CoordinacionController::class, 'solicitudes'])->name('datacoor.solicitudes')->middleware('disable.cache');

    Route::post('/validar/crear', [CoordinacionController::class, 'solicitarAutorizacion'])->name('solicitar.autorizacioncoor');

    Route::post('/validarautorizacion/actualizar-{id}', [CoordinacionController::class, 'validarAutorizacion'])->name('updatevalidarcoor.autorizacion');

    Route::post('/validar/actualizar-{id}', [CoordinacionController::class, 'actualizardetalle'])->name('updatecoor.autorizacion');

    Route::get('/filtrarconcepto', function () {
        Cookie::forget('laravel_session');
        Cache::flush();
        return view('Coordinacion/filtrarconcepto');
    });

    Route::get('filtrarconcepto/datatable', [CoordinacionController::class, 'filtrarconcepto'])->name('datacoor.filtrarconcepto');

    Route::get('/filtrarconcepto', [CoordinacionController::class, 'data2']);
});

//GERENCIA
Route::get('/aprobar', function () {
    Cookie::forget('laravel_session');
    Cache::flush();
    return view('Gerencia/aprobar');
})->middleware('auth.gerencia');

Route::get('/coordinacion9', function () {
    Cookie::forget('laravel_session');
    Cache::flush();
    return view('Gerencia/coordinacion9');
})->middleware('auth.gerencia');

Route::get('/otrabajo', function () {
    Cookie::forget('laravel_session');
    Cache::flush();
    return view('Gerencia/otrabajo');
})->middleware('auth.gerencia');
Route::get('otrabajo/datatable', [GerenciaController::class, 'otrabajodatatable'])->name('datager.otrabajodatatable')->middleware('auth.gerencia');

Route::get('/otrabajo', [GerenciaController::class, 'cargaragcoorjef'])
->name('cargaragcoorjef');

Route::get('/otrabajo/recargar', [GerenciaController::class, 'obtenerGrupos']);

Route::post('/otrabajo/crear', [GerenciaController::class, 'crearotrabajo'])->name('crearotrabajo.ger')->middleware('auth.gerencia');

Route::post('cambiar-estado', [GerenciaController::class, 'cambiarEstado'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

Route::post('/otrabajo/guardar-grupo', [GerenciaController::class, 'store']);

Route::get('/otrabajo/ruta-para-cargar-grupos', [GerenciaController::class, 'loadGroups']);

Route::delete('/otrabajo/eliminar-grupo/{id}', [GerenciaController::class, 'destroy']);

Route::delete('/otrabajo/eliminar-integrante/{grupoId}/{integranteId}', [GerenciaController::class, 'eliminarIntegrante']);

Route::get('/otrabajo/buscar-grupos', [GerenciaController::class, 'buscarGrupos']);

Route::put('/otrabajo/actualizar-grupo/{grupoId}', [GerenciaController::class, 'updateNombreGrupo']);

Route::get('aprobar', [GerenciaController::class, 'data1'])->middleware('auth.gerencia');

Route::get('aprobar/datatable', [GerenciaController::class, 'solicitudes'])->name('datager.solicitudes')->middleware('auth.gerencia');

Route::get('aprobados/datatable', [GerenciaController::class, 'aprobados'])->name('datager.aprobados')->middleware('auth.gerencia');

Route::get('rechazados/datatable', [GerenciaController::class, 'rechazados'])->name('datager.rechazados')->middleware('auth.gerencia');

Route::get('tramite/datatable', [GerenciaController::class, 'tramite'])->name('datager.tramite')->middleware('auth.gerencia');

Route::get('bloqueados/datatable', [GerenciaController::class, 'bloqueados'])->name('datager.bloqueados')->middleware('auth.gerencia');

Route::get('anulados/datatable', [GerenciaController::class, 'anulados'])->name('datager.anulados')->middleware('auth.gerencia');

Route::post('aprobar/actualizar-{id}', [GerenciaController::class, 'validarAutorizacion'])->name('updateger.autorizacion')->middleware('auth.gerencia');

Route::get('coordinacion9', [GerenciaController::class, 'data2'])->middleware('auth.gerencia');

Route::get('coordinacion9/datatable', [GerenciaController::class, 'solicitudescoordinacion'])->name('datagercoordi.solicitudes')->middleware('auth.gerencia');

Route::post('coordinacion9/actualizar-{id}', [GerenciaController::class, 'validarAutorizacioncoordinacion9'])->name('updategercoordi.autorizacion')->middleware('auth.gerencia');

Route::get('/estadisticas', function () {
    Cookie::forget('laravel_session');
    Cache::flush();
    return view('Gerencia/estadisticas');
})->middleware('auth.gerencia');

Route::get('/estadisticas', [GerenciaController::class, 'contarsolicitudes'])
->name('contarsolicitudes');

Route::get('/estadisticas/actualizar-datos', [GerenciaController::class, 'actualizardatos'])
->name('actualizardatos');



Route::get('/estadisticasindividual', function () {
    Cookie::forget('laravel_session');
    Cache::flush();
    return view('Gerencia/estadisticaindividual');
})->middleware('auth.gerencia');

Route::get('/filtrarconceptoger', function () {
    Cookie::forget('laravel_session');
    Cache::flush();
    return view('Gerencia/filtrarconcepto');
});

Route::get('filtrarconceptoger/datatable', [GerenciaController::class, 'filtrarconcepto'])->name('datager.filtrarconcepto');

Route::get('/filtrarconceptoger', [GerenciaController::class, 'concepto']);


Route::get('/admin', function () {
    Cookie::forget('laravel_session');
    Cache::flush();
    return view('Gerencia/admin');
})->middleware('auth.gerencia');

Route::get('coordinaciones/datatable', [GerenciaController::class, 'coordinaciones'])->name('coordinaciones')->middleware('auth.gerencia');

Route::get('dagencia/datatable', [GerenciaController::class, 'dagencia'])->name('datager.dagencia')->middleware('auth.gerencia');

Route::get('jefaturas/datatable', [GerenciaController::class, 'jefaturas'])->name('datager.jefaturas')->middleware('auth.gerencia');

Route::get('agencias/datatable', [GerenciaController::class, 'agencias'])->name('datager.agencias')->middleware('auth.gerencia');

Route::get('/admin', [GerenciaController::class, 'cargaragencias'])
->name('cargarinfo');

Route::post('/admin/crear', [GerenciaController::class, 'crearusuario'])
->name('crearusuario');


//JEFATURA
Route::get('/solicitudesjefatura', function () {
    Cookie::forget('laravel_session');
    Cache::flush();
    return view('Jefatura/solicitudesjefatura');
})->middleware('auth.jefatura');

Route::get('solicitudesjefatura', [JefaturaController::class, 'data1']);

Route::get('solicitudesjefatura/datatable', [JefaturaController::class, 'solicitudes'])->name('data.solicitudesjef')->middleware('auth.jefatura');

Route::post('/solicitudesjefatura/crear', [JefaturaController::class, 'solicitarAutorizacion'])->name('solicitar.autorizacionjef')->middleware('auth.jefatura');

Route::post('/solicitudesjefatura/actualizar-{id}', [JefaturaController::class, 'actualizardetalle'])->name('update.autorizacionjef')->middleware('auth.jefatura');


//TODOS LOS PERFILES

Route::get('/ordentrabajo', function () {
    Cookie::forget('laravel_session');
    Cache::flush();
    return view('otrabajo');
});


Route::get('ordentrabajo/datatable', [Todos::class, 'otrabajodatatable'])->name('data.otrabajotodos');

Route::get('celular', [Todos::class, 'celularpendiente'])->name('celular');

