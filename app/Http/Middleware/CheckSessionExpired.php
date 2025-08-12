<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
class CheckSessionExpired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        // Obtener el ID real de la sesión
        $sessionId = Session::getId();

        // Buscar la sesión en la base de datos
        $session = DB::table('sessions')->where('id', $sessionId)->first();

        // Si NO existe la fila en la tabla 'sessions', es que expiró o fue eliminada
        if (!$session) {
            Session::flush();
            return redirect('/')
                ->with('correcto', 'Tu sesión ha expirado por inactividad.');
        }

        // Si existe pero no tiene el ID de usuario guardado
        if (!Session::has('id')) {
            Session::flush();
            return redirect('/')
                ->with('correcto', 'Tu sesión ha expirado por inactividad.');
        }

        return $next($request);
    }

}
