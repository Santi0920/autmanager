<?php

use App\Http\Controllers\CoordinacionController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\GerenciaController;
use App\Http\Controllers\JefaturaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionsController;

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
    return view('Director/solicitudes');
})->middleware('auth.director');

Route::get('/solicitudes', [DirectorController::class, 'data1'])->middleware('auth.director');

Route::get('/solicitudes/datatable', [DirectorController::class, 'solicitudes'])->name('data.solicitudes')->middleware('auth.director');

Route::post('/solicitudes/crear', [DirectorController::class, 'solicitarAutorizacion'])->name('solicitar.autorizacion')->middleware('auth.director');

Route::post('/solicitudes/actualizar-{id}', [DirectorController::class, 'actualizardetalle'])->name('update.autorizacion')->middleware('auth.director');



//COORDINACION

Route::middleware('auth.coord')->group(function () {
    Route::get('/validar', function () {
        return view('Coordinacion/validarautorizacion');
    });

    Route::get('validar/datatable', [CoordinacionController::class, 'solicitudes'])->name('datacoor.solicitudes');

    Route::post('validar/actualizar-{id}', [CoordinacionController::class, 'validarAutorizacion'])->name('updatecoor.autorizacion');
});

//GERENCIA
Route::get('/aprobar', function () {
    return view('Gerencia/aprobar');
})->middleware('auth.gerencia');

Route::get('aprobar/datatable', [GerenciaController::class, 'solicitudes'])->name('datager.solicitudes')->middleware('auth.gerencia');

Route::post('aprobar/actualizar-{id}', [GerenciaController::class, 'validarAutorizacion'])->name('updateger.autorizacion')->middleware('auth.gerencia');


//JEFATURA
Route::get('/solicitudesjefatura', function () {
    return view('Jefatura/solicitudesjefatura');
})->middleware('auth.jefatura');

Route::get('solicitudesjefatura', [JefaturaController::class, 'data1'])->middleware('auth.jefatura');

Route::get('solicitudesjefatura/datatable', [JefaturaController::class, 'solicitudes'])->name('data.solicitudesjef')->middleware('auth.jefatura');

Route::post('/solicitudesjefatura/crear', [JefaturaController::class, 'solicitarAutorizacion'])->name('solicitar.autorizacionjef')->middleware('auth.jefatura');

Route::post('/solicitudesjefatura/actualizar-{id}', [JefaturaController::class, 'actualizardetalle'])->name('update.autorizacionjef')->middleware('auth.jefatura');
