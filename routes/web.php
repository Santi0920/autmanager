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
});

Route::get('/solicitudes', [DirectorController::class, 'data1']);

Route::get('/solicitudes/datatable', [DirectorController::class, 'solicitudes'])->name('data.solicitudes');

//Esta ruta es para crear autorizaciones en todos los usuarios, los demas quedaron obsoletos
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


    Route::get('/validar', function () {
        Cookie::forget('laravel_session');
        Cache::flush();
        return view('Coordinacion/validarautorizacion');
    });

    Route::get('/validar', [CoordinacionController::class, 'data1']);

    Route::get('validar/datatable', [CoordinacionController::class, 'solicitudes'])->name('datacoor.solicitudes');

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


//GERENCIA
Route::get('/aprobar', function () {
    Cookie::forget('laravel_session');
    Cache::flush();
    return view('Gerencia/aprobar');
});

Route::get('/coordinacion9', function () {
    Cookie::forget('laravel_session');
    Cache::flush();
    return view('Gerencia/coordinacion9');
});

Route::get('/otrabajo', function () {
    Cookie::forget('laravel_session');
    Cache::flush();
    return view('Gerencia/otrabajo');
});
Route::get('otrabajo/datatable', [GerenciaController::class, 'otrabajodatatable'])->name('datager.otrabajodatatable');

Route::get('/otrabajo', [GerenciaController::class, 'cargaragcoorjef'])
->name('cargaragcoorjef');

Route::get('/otrabajo/recargar', [GerenciaController::class, 'obtenerGrupos']);

Route::post('/otrabajo/crear', [GerenciaController::class, 'crearotrabajo'])->name('crearotrabajo.ger');

Route::post('cambiar-estado', [GerenciaController::class, 'cambiarEstado'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

Route::post('/otrabajo/guardar-grupo', [GerenciaController::class, 'store']);

Route::get('/otrabajo/ruta-para-cargar-grupos', [GerenciaController::class, 'loadGroups']);

Route::delete('/otrabajo/eliminar-grupo/{id}', [GerenciaController::class, 'destroy']);

Route::delete('/otrabajo/eliminar-integrante/{grupoId}/{integranteId}', [GerenciaController::class, 'eliminarIntegrante']);

Route::get('/otrabajo/buscar-grupos', [GerenciaController::class, 'buscarGrupos']);

Route::put('/otrabajo/actualizar-grupo/{grupoId}', [GerenciaController::class, 'updateNombreGrupo']);

Route::get('/otrabajo/{id}/integrantes', [GerenciaController::class, 'getIntegrantes'])->name('grupo.integrantes');

Route::get('aprobar', [GerenciaController::class, 'data1']);

Route::get('aprobar/datatable', [GerenciaController::class, 'solicitudes'])->name('datager.solicitudes');

Route::get('aprobados/datatable', [GerenciaController::class, 'aprobados'])->name('datager.aprobados');

Route::get('rechazados/datatable', [GerenciaController::class, 'rechazados'])->name('datager.rechazados');

Route::get('tramite/datatable', [GerenciaController::class, 'tramite'])->name('datager.tramite');

Route::get('bloqueados/datatable', [GerenciaController::class, 'bloqueados'])->name('datager.bloqueados');

Route::get('anulados/datatable', [GerenciaController::class, 'anulados'])->name('datager.anulados');

Route::post('aprobar/actualizar-{id}', [GerenciaController::class, 'validarAutorizacion'])->name('updateger.autorizacion');

Route::get('coordinacion9', [GerenciaController::class, 'data2']);

Route::get('coordinacion9/datatable', [GerenciaController::class, 'solicitudescoordinacion'])->name('datagercoordi.solicitudes');

Route::post('coordinacion9/actualizar-{id}', [GerenciaController::class, 'validarAutorizacioncoordinacion9'])->name('updategercoordi.autorizacion');

Route::get('/estadisticas', function () {
    Cookie::forget('laravel_session');
    Cache::flush();
    return view('Gerencia/estadisticas');
});

Route::get('/estadisticas', [GerenciaController::class, 'contarsolicitudes'])
->name('contarsolicitudes');

Route::get('/estadisticas/actualizar-datos', [GerenciaController::class, 'actualizardatos'])
->name('actualizardatos');

Route::get('/otrabajoestadisticas', function () {
    Cookie::forget('laravel_session');
    Cache::flush();
    return view('Gerencia/otraestadisticas');
});

Route::get('/otrabajoestadisticas', [GerenciaController::class, 'contarsolicitudesotrabajo'])
->name('contarsolicitudesotrabajo');

Route::get('/otrabajoestadisticas/actualizar-datos', [GerenciaController::class, 'actualizardatos'])
->name('actualizardatos');

Route::get('/estadisticasindividual', function () {
    Cookie::forget('laravel_session');
    Cache::flush();
    return view('Gerencia/estadisticaindividual');
});

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
});

Route::get('coordinaciones/datatable', [GerenciaController::class, 'coordinaciones'])->name('coordinaciones');

Route::get('dagencia/datatable', [GerenciaController::class, 'dagencia'])->name('datager.dagencia');

Route::get('jefaturas/datatable', [GerenciaController::class, 'jefaturas'])->name('datager.jefaturas');

Route::get('agencias/datatable', [GerenciaController::class, 'agenciastabla'])->name('agenciastabla');


Route::get('/admin', [GerenciaController::class, 'cargaragencias'])
->name('cargarinfo');

Route::post('/admin/crear', [GerenciaController::class, 'crearusuario'])
->name('crearusuario');

Route::post('/admin/editar', [GerenciaController::class, 'editarusuario'])
->name('editarusuario');

Route::get('/admin/eliminar/{id}', [GerenciaController::class, 'eliminarUsuario'])
->name('eliminarusuario');

Route::get('/admin/obtener-agencias/{id}', [GerenciaController::class, 'obtenerAgencias']);

Route::get('/admin/obtener-agencias-select/{id}', [GerenciaController::class, 'obtenerAgenciasSelect']);



//JEFATURA
Route::get('/solicitudesjefatura', function () {
    Cookie::forget('laravel_session');
    Cache::flush();
    return view('Jefatura/solicitudesjefatura');
});

Route::get('solicitudesjefatura', [JefaturaController::class, 'data1']);

Route::get('solicitudesjefatura/datatable', [JefaturaController::class, 'solicitudes'])->name('data.solicitudesjef');

Route::post('/solicitudesjefatura/crear', [JefaturaController::class, 'solicitarAutorizacion'])->name('solicitar.autorizacionjef');

Route::post('/solicitudesjefatura/actualizar-{id}', [JefaturaController::class, 'actualizardetalle'])->name('update.autorizacionjef');


//TODOS LOS PERFILES

Route::get('/ordentrabajo', function () {
    Cookie::forget('laravel_session');
    Cache::flush();
    return view('otrabajo');
});


Route::get('ordentrabajo/datatable', [Todos::class, 'otrabajodatatable'])->name('data.otrabajotodos');

Route::get('celular', [Todos::class, 'celularpendiente'])->name('celular');

