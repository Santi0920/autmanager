<?php

use App\Http\Controllers\CoordinacionController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\GerenciaController;
use App\Http\Controllers\JefaturaController;
use App\Http\Controllers\Todos;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionsController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;

//LOGIN
Route::get('/', [SessionsController::class, 'login'])
    ->name('login.index');

Route::post('/', [SessionsController::class, 'login_post'])
    ->name('login');

Route::get('logout', [SessionsController::class, 'destroy'])
    ->name('login.destroy');


//USUARIO
Route::middleware(['session.expired'])->group(function () {
    Route::get('/solicitudes', function () {
        // Cookie::forget('laravel_session');
        // Cache::flush();
        return view('Usuario/solicitudes');
    });

    Route::get('/tyc', function () {
        // Cookie::forget('laravel_session');
        // Cache::flush();
        return view('Usuario/tyc');
    });

    Route::get('/privacidad', function () {
        // Cookie::forget('laravel_session');
        // Cache::flush();
        return view('Usuario/privacidad');
    });

    Route::get('/solicitudes', [UsuarioController::class, 'data1']);

    Route::get('/solicitudes/datatable', [UsuarioController::class, 'solicitudes'])->name('data.solicitudes');

    Route::get('/solicitudesc9/datatable', [UsuarioController::class, 'c9'])->name('data.c9');

    Route::get('/solicitudesaprobadas/datatable', [UsuarioController::class, 'aprobados'])->name('data.aprobados');

    Route::get('/solicitudesrechazadas/datatable', [UsuarioController::class, 'rechazados'])->name('data.rechazados');
    
    Route::get('/solicitudestramite/datatable', [UsuarioController::class, 'tramite'])->name('data.tramite');
    
    Route::get('/solicitudesbloqueadas/datatable', [UsuarioController::class, 'bloqueados'])->name('data.bloqueados');

    Route::get('/solicitudesanuladas/datatable', [UsuarioController::class, 'anulados'])->name('data.anulados');

    Route::get('/solicitudesstandby/datatable', [UsuarioController::class, 'standby'])->name('data.standby');

    Route::get('/solicitudesenviados/datatable', [UsuarioController::class, 'enviado'])->name('data.enviado');

    Route::get('/solicitudesreportes/datatable', [UsuarioController::class, 'reportes'])->name('data.reporte');

    Route::get('/solicitudesvencido/datatable', [UsuarioController::class, 'vencido'])->name('data.vencido');

    Route::get('/modal-autorizacion/{id}', [UsuarioController::class, 'modalAutorizacion']);

    Route::post('/password/update', [UsuarioController::class, 'updatePassword'])->name('password.update');

    Route::post('/perfil/actualizar', [UsuarioController::class, 'updatePerfil'])->name('perfil.update');

    Route::post('/bug-report', [UsuarioController::class, 'store'])->name('bug-report.store');


    //SOLICITUDES ANTIGUAS
    Route::get('/gerencia', function () {
        // Cookie::forget('laravel_session');
        // Cache::flush();
        return view('Usuario/Gerencia/solicitudes_antiguas');
    });

    Route::get('/gerencia', [UsuarioController::class, 'data2antiguo']);

    Route::get('/gerencia/datatable', [UsuarioController::class, 'solicitudesantiguas'])->name('data.solicitudesantiguas');

    Route::get('/gerenciaaprobadas/datatable', [UsuarioController::class, 'aprobadosantiguas'])->name('data.aprobadosantiguas');

    Route::get('/gerenciarechazadas/datatable', [UsuarioController::class, 'rechazadosantiguas'])->name('data.rechazadosantiguas');
    
    Route::get('/gerenciatramite/datatable', [UsuarioController::class, 'tramiteantiguas'])->name('data.tramiteantiguas');
    
    Route::get('/gerenciabloqueadas/datatable', [UsuarioController::class, 'bloqueadosantiguas'])->name('data.bloqueadosantiguas');

    Route::get('/gerenciaanuladas/datatable', [UsuarioController::class, 'anuladosantiguas'])->name('data.anuladosantiguas');

    Route::get('/gerenciastandby/datatable', [UsuarioController::class, 'standbyantiguas'])->name('data.standbyantiguas');

    Route::post('gerencia/actualizar-{id}', [UsuarioController::class, 'validarAutorizacionAntiguas'])->name('updateger.autorizacionantiguas');

    Route::get('/gerenciac9', function () {
        // Cookie::forget('laravel_session');
        // Cache::flush();
        return view('Usuario/Gerencia/coordinacion9');
    });

    Route::get('/gerenciac9', [UsuarioController::class, 'data3antiguo']);

    Route::get('/gerenciac9/datatable', [UsuarioController::class, 'solicitudescoordinacionantiguas'])->name('data.gerenciac9');


    



    //VERSION 1.0 TODOS LOS ROLES MENOS GERENCIA, ESTA ARRIBA
    //director
    Route::get('/solicitudes-antiguas', function () {
        // Cookie::forget('laravel_session');
        // Cache::flush();
        return view('Usuario.Director.solicitudes_antiguas');
    });
    
    Route::get('/solicitudes-antiguas/datatable', [Todos::class, 'solicitudes'])->name('data.solantiguas');

    Route::get('/solicitudes-antiguasapro/datatable', [Todos::class, 'aprobados'])->name('data.solantiguasapro');
    
    Route::get('/solicitudes-antiguasanu/datatable', [Todos::class, 'anulados'])->name('data.solantiguasanu');

    Route::get('/solicitudes-antiguasstand/datatable', [Todos::class, 'standby'])->name('data.solantiguasstand');

















    //Esta ruta es para crear autorizaciones en todos los usuarios, los demas quedaron obsoletos
    Route::post('/solicitudes/crear', [UsuarioController::class, 'solicitarAutorizacion'])->name('solicitar.autorizacion');

    Route::post('/solicitudes/actualizar-{id}', [UsuarioController::class, 'actualizardetalle'])->name('update.autorizacion');

    Route::get('/filtrar', function () {
        // Cookie::forget('laravel_session');
        // Cache::flush();
        return view('Usuario.filtrar');
    });

    Route::get('/autorizacion', function () {
        // Cookie::forget('laravel_session');
        // Cache::flush();
        return view('Usuario.mostrarautorizacion');
    });

    Route::post('/autorizacion', [UsuarioController::class, 'buscarautorizacion'])
    ->name('buscarautorizacion');


    //COORDINACION
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

        Route::get('standby/datatable', [GerenciaController::class, 'standby'])->name('datager.standby');

        Route::get('standby/aprobar', [GerenciaController::class, 'aprobarStandBy'])->name('datager.aprobarstandby');

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

        // Route::get('/filtrarconceptoger', function () {
        //     Cookie::forget('laravel_session');
        //     Cache::flush();
        //     return view('Gerencia/filtrarconcepto');
        // });

        // Route::get('filtrarconceptoger/datatable', [GerenciaController::class, 'filtrarconcepto'])->name('datager.filtrarconcepto');

        // Route::get('/filtrarconceptoger', [GerenciaController::class, 'concepto']);


        Route::get('/admin', function () {
            Cookie::forget('laravel_session');
            Cache::flush();
            return view('Gerencia/admin');
        });

        Route::get('coordinaciones/datatable', [GerenciaController::class, 'coordinaciones'])->name('coordinaciones');

        Route::get('dagencia/datatable', [GerenciaController::class, 'dagencia'])->name('datager.dagencia');

        Route::get('jefaturas/datatable', [GerenciaController::class, 'jefaturas'])->name('datager.jefaturas');

        Route::get('agencias/datatable', [GerenciaController::class, 'agenciastabla'])->name('agenciastabla');

        Route::get('conceptos/datatable', [GerenciaController::class, 'conceptos'])->name('conceptos');

        Route::get('suspendidas/datatable', [GerenciaController::class, 'cuentasSuspendidas'])->name('suspendidas');


        Route::get('/admin', [GerenciaController::class, 'cargaragencias'])
        ->name('cargarinfo');

        Route::post('/admin/crear', [GerenciaController::class, 'crearusuario'])
        ->name('crearusuario');

        Route::post('/admin/editar', [GerenciaController::class, 'editarusuario'])
        ->name('editarusuario');

        Route::get('/admin/eliminar/{id}', [GerenciaController::class, 'eliminarUsuario'])
        ->name('eliminarusuario');

        Route::get('/admin/suspendida/{id}', [GerenciaController::class, 'activarCSuspendida'])
        ->name('activarcsuspendida');

                Route::get('/admin/eliminararea/{id}/{area}', [GerenciaController::class, 'eliminarConcepto'])
        ->name('eliminararea');

        Route::get('/admin/obtener-agencias/{id}', [GerenciaController::class, 'obtenerAgencias']);

        Route::get('/admin/obtener-agencias-select/{id}', [GerenciaController::class, 'obtenerAgenciasSelect']);

    //TODOS LOS PERFILES

    Route::get('/ordentrabajo', function () {
        // Cookie::forget('laravel_session');
        // Cache::flush();
        return view('otrabajo');
    });


    Route::get('ordentrabajo/datatable', [Todos::class, 'otrabajodatatable'])->name('data.otrabajotodos');

    Route::get('celular', [Todos::class, 'celularpendiente'])->name('celular');

});
