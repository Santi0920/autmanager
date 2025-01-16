<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSessionExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Verifica si la sesión ha expirado
        if (session('expires_at') && now()->greaterThan(session('expires_at'))) {
            // Invalida la sesión
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Redirige al login con un mensaje de sesión expirada
            return redirect()->route('login')->with('message', 'Sesión expirada. Por favor, inicia sesión de nuevo.');
        }

        return $next($request);
    }
}
