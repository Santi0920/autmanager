<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;

class SessionsController extends Controller
{
    public function login()
    {
        return view("login");
    }

    public function login_post(Request $request)
    {
$attempts = 0;
$maxAttempts = 3; // Intentos máximos
$retryDelay = 1000; // Milisegundos
$user = null;

do {
    try {
        // Buscar el usuario localmente
        $user = DB::table('users')
            ->whereRaw('LOWER(email) = ?', [strtolower($request->email)])
            ->first();

        if (!$user) {
            throw new \Exception('Usuario no encontrado');
        }

        break; // Usuario encontrado, salir del bucle

    } catch (\Exception $e) {
        $attempts++;
        Log::warning("Intento $attempts fallido al consultar usuario: " . $e->getMessage());
        usleep($retryDelay * 1000); // Esperar antes de reintentar
    }
} while ($attempts < $maxAttempts);

// Verificar autenticación con los datos locales
if ($user && Hash::check($request->password, $user->password)) {
    // Crear sesión
    session([
        'id' => $user->id,
        'email' => $user->email,
        'rol' => $user->rol ?? null,
        'agenciau' => $user->agenciau ?? null,
        'name' => $user->name ?? null,
        'celular' => $user->celular ?? null,
        'notificaciones' => $user->notificaciones ?? null,
        'activo' => $user->activo ?? null,
        'codigo' => $user->codigo ?? null,
        'agencias_id' => $user->agencias_id ?? null,
        'expires_at' => now()->addHours(10),
    ]);

    // Redirigir o retornar éxito
    return redirect('/solicitudes');
} else {
    return back()->withErrors([
        'email' => 'Credenciales inválidas o usuario no encontrado.',
    ]);
}

 

    }

    public function destroy(Request $request)
    {
        $request->session()->invalidate(); // Invalida la sesión activa
        $request->session()->regenerateToken(); // Regenera el token CSRF

        $request->session()->forget('expires_at');

        Cookie::forget('laravel_session');
        Cache::flush();



        return redirect()->to('/');
    }
}
