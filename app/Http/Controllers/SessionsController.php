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
        // Destruir completamente la sesión anterior
        session()->invalidate();
        session()->regenerateToken();

        return view('login');
    }

    public function login_post(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $email = strtolower($request->email);
        $password = $request->password;
 
        // Buscar usuario en la tabla local 'users'
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {

            // Incrementar contador
            session(['login_attempts' => session('login_attempts') + 1]);

            // Mostrar mensaje de error
            return back()->with('message', 'El usuario o la contraseña es incorrecto!')
                        ->with('show_captcha', session('login_attempts') >= 3);
        }

        if (session('login_attempts') >= 3) {
            if (empty($request->input('g-recaptcha-response'))) {
                return back()->with('message', 'Por favor, completa el Captcha')
                            ->with('show_captcha', true);
            }
        }

        $displayAgencias = ''; // Variable que contendrá el resultado final

        if ($user->rol === 'Consultante') {
            // Quitar los dos últimos ceros
            $userCC = substr((string)$user->codigo, 0, -2);

            // Traer agencias según el código
            $agencias = DB::select(
                'SELECT agenciau 
                FROM users 
                WHERE agencias_id LIKE ?',
                ['%"' . $userCC . '"%']
            );

            // Convertir a abreviación C1, C3, etc.
            $agenciasString = implode(', ', array_map(function($item) {
                preg_match('/\d+/', $item->agenciau, $matches);
                return isset($matches[0]) ? 'C'.$matches[0] : $item->agenciau;
            }, $agencias));

            $displayAgencias = $agenciasString;

        } elseif ($user->rol === 'Coordinacion') {
            // Obtener los IDs de agencias desde el campo JSON agencias_id
            $agenciasIDs = json_decode($user->agencias_id, true); // Supongo que es JSON
            
            if ($agenciasIDs && is_array($agenciasIDs)) {
                $agencias = DB::table('agencias')
                    ->whereIn('NumAgencia', $agenciasIDs)
                    ->get(); // traemos toda la fila
                    $agenciasFormatted = $agencias->map(function($item) {
                        return '<span style="display:inline-block; margin:2px 5px; padding:2px 6px; background-color:#f0f0f0; border-radius:5px;">
                                    ' . htmlspecialchars($item->NameAgencia) . ' <strong>(' . $item->NumAgencia . ')</strong>
                                </span>';
                    })->toArray();

                    // Unir en un string separado por comas
                    $displayAgencias = implode(' ', $agenciasFormatted);
            }
        }

        // Información para auditoría
        $nombreauditoria = $user->name ?? null;
        $rol = $user->rol ?? null;
        $agencia = $user->agenciau ?? null;
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        date_default_timezone_set('America/Bogota');
        $fechaHoraActual = now()->format('Y-m-d H:i:s');

        // Último acceso (antes del actual)
        $ultimoAcceso = DB::table('auditoria')
            ->where('Usuario_nombre', $nombreauditoria)
            ->orderByDesc('Hora_Accion')
            ->skip(1) // saltar el último registro
            ->value('Hora_Accion');

        // Última acción
        $ultimaAccion = DB::table('auditoria')
            ->where('Usuario_nombre', $nombreauditoria)
            ->orderByDesc('Hora_Accion')
            ->value('Acción_realizada');

        // Últimos 3 logins (fecha y hora)
        $loginsRecientes = DB::table('auditoria')
            ->where('Usuario_nombre', $nombreauditoria)
            ->orderByDesc('Hora_Accion')
            ->limit(3)
            ->pluck('Hora_Accion')
            ->toArray();

        $loginsRecientesFormatted = implode(', ', array_map(function($fecha){
            return date('Y-m-d H:i:s', strtotime($fecha));
        }, $loginsRecientes));

        // Registrar auditoría del login actual
        DB::table('auditoria')->insert([
            'Hora_login' => null,
            'Usuario_nombre' => $nombreauditoria,
            'Usuario_Rol' => $rol,
            'AgenciaU' => $agencia,
            'Acción_realizada' => 'Login',
            'Hora_Accion' => $fechaHoraActual,
            'cerro_sesion' => null,
            'IP' => $ip
        ]);

        // Guardar datos en sesión
        session([
            'id' => $user->id,
            'email' => $user->email,
            'rol' => $rol,
            'agenciau' => $agencia,
            'name' => $nombreauditoria,
            'celular' => $user->celular ?? null,
            'notificaciones' => $user->notificaciones ?? null,
            'activo' => $user->activo ?? null,
            'codigo' => $user->codigo ?? null,
            'agencias_id' => $user->agencias_id ?? null,
            'coordasignadas' => $displayAgencias ?? '',
            'expires_at' => now()->addHours(10),
            'ultimo_acceso' => $ultimoAcceso,
            'ultima_accion' => $ultimaAccion,
            'logins_recientes' => $loginsRecientesFormatted,
        ]);

        session()->flash('bienvenida', 'Bienvenido/a, ' . $nombreauditoria . ' 👋');
        session(['login_attempts' => 0]);

        return redirect()->to('/solicitudes');
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
