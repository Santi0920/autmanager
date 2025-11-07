<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
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

            if ($rol == 'Jefatura' || $rol == 'Consultante' || $rol == 'Coordinacion') {
                $notificacionesindividual = DB::select(
                    'SELECT notificaciones FROM users WHERE email = ?',
                    [$email]
                );

                if (count($notificacionesindividual) > 0) {
                    $notificaciones = $notificacionesindividual[0]->notificaciones;
                } else {
                    $notificaciones = 0; // o un valor por defecto
                }

            }

            

            $view->with('notificaciones', $notificaciones);
        });


        View::composer('layouts.celular', function ($view) {

            $id = session('id');

            $celular = DB::table('users')->where('id', $id)->value('celular');

            $view->with('celular', $celular);
        });

        // Para que layouts/nav siempre reciba $usuario
        View::composer('layouts.nav', function ($view) {
            $userId = session('id');
            $usuario = null;

            if ($userId) {
                $user = User::find($userId);
                if ($user) {
                    $usuario = [
                        'name' => $user->name,
                        'celular' => $user->celular
                    ];
                }
            }

            $view->with('usuario', $usuario);
        });
    }
}
