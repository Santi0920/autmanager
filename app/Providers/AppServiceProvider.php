<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        View::composer('layouts.notification', function ($view) {
            $usuarioActual = Auth::user();
            $rol = $usuarioActual->rol;
            $name = $usuarioActual->name;
            $notificaciones = 0;
    
            if ($rol == 'Coordinacion') {
                $notificacionesindividual = DB::select('SELECT notificaciones FROM users WHERE name = ?', [$name]); 
                
                $notificaciones = $notificacionesindividual[0]->notificaciones;
    
            } else if ($rol == 'Consultante') {
                $notificacionesindividual = DB::select('SELECT notificaciones FROM users WHERE name = ?', [$name]); 
                
                $notificaciones = $notificacionesindividual[0]->notificaciones;

            } else if ($rol == 'Jefatura') {
                $notificacionesindividual = DB::select('SELECT notificaciones FROM users WHERE name = ?', [$name]); 
                
                $notificaciones = $notificacionesindividual[0]->notificaciones;
            } 
    
            $view->with('notificaciones', $notificaciones);
        });


        View::composer('layouts.celular', function ($view) {
            $usuarioActual = Auth::user();
            $id = $usuarioActual->id;

            $celular = DB::table('users')->where('id', $id)->value('celular');

            $view->with('celular', $celular);
        });
    }
}
