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

            $rol = session('rol');
            $name = session('name');
            $email = session('email');
            $notificaciones = 0;

            if ($rol == 'Coordinacion') {
                $notificacionesindividual = DB::select('SELECT notificaciones FROM users WHERE email = ?', [$email]);

                $notificaciones = $notificacionesindividual[0]->notificaciones;

            } else if ($rol == 'Consultante') {
                $notificacionesindividual = DB::select('SELECT notificaciones FROM users WHERE email = ?', [$email]);

                $notificaciones = $notificacionesindividual[0]->notificaciones;

            } else if ($rol == 'Jefatura') {
                $notificacionesindividual = DB::select('SELECT notificaciones FROM users WHERE email = ?', [$email]);

                $notificaciones = $notificacionesindividual[0]->notificaciones;
            }

            $view->with('notificaciones', $notificaciones);
        });


        View::composer('layouts.celular', function ($view) {

            $id = session('id');

            $celular = DB::table('users')->where('id', $id)->value('celular');

            $view->with('celular', $celular);
        });
    }
}
