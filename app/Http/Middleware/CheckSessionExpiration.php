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
        if (!session('expires_at')) {
            // Si no hay fecha de expiración, establecer una
            session(['expires_at' => now()->addMinutes(config('session.lifetime'))]);
        }

        if (now()->greaterThan(session('expires_at'))) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('message', 'Sesión expirada. Por favor, inicia sesión de nuevo.');
        }

        // Renovar la sesión en cada request
        session(['expires_at' => now()->addMinutes(config('session.lifetime'))]);

        return $next($request);
    }
}
