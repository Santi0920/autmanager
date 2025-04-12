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
        Cookie::forget('laravel_session');
        Cache::flush();
        return view("login");
    }

    public function login_post(Request $request)
    {

        $url = "http://190.66.10.150:10100/menu-datacredito/api/";
        $attempts = 0;
        $maxAttempts = 3; // Intentos máximos
        $retryDelay = 1000; // Milisegundos

        do {
            try {
                $response = Http::get($url . 'usuarios/' . urlencode($request->email));

                if ($response->failed()) {
                    throw new \Exception('Error al obtener datos del usuario');
                }

                $data = $response->json();


                break;
            } catch (\Exception $e) {
                $attempts++;
                usleep($retryDelay * 1000);

            }
        } while ($attempts < $maxAttempts);

        // Verificar la autenticación con los datos obtenidos de la API
        if (isset($data['usuarios'][0])) {
            $user = $data['usuarios'][0];


            if ($user['email'] == strtolower($request->email) && Hash::check($request->password, $user['password'])) {
                session([
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'rol' => $user['rol'],
                    'agenciau' => $user['agenciau'],
                    'name' => $user['name'],
                    'celular' => $user['celular'],
                    'notificaciones' => $user['notificaciones'],
                    'activo' => $user['activo'],
                    'codigo' => $user['codigo'],
                    'agencias_id' => $user['agencias_id'],
                    'expires_at' => now()->addHours(10)
                ]);

                //auditoria
                $nombre = session('name');
                $rol = session('rol');
                $agencia = session('agenciau');

                date_default_timezone_set('America/Bogota');

                $fechaHoraActual = date('Y-m-d H:i:s');
                $ip = $_SERVER['REMOTE_ADDR'];
                $login = DB::insert("INSERT INTO auditoria (Hora_login, Usuario_nombre, Usuario_Rol, AgenciaU, Acción_realizada, Hora_Accion, Cedula_Registrada, cerro_sesion, IP) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                    $fechaHoraActual,
                    $nombre,
                    $rol,
                    $agencia,
                    'IngresoaAutorizaciones',
                    $fechaHoraActual,
                    null,
                    null,
                    $ip
                ]);
                if($user['rol'] == 'Consultante'){
                    return redirect()->to('/solicitudes');
                }else if($user['rol'] == 'Jefatura'){
                    return redirect()->to('/solicitudesjefatura');
                }else if($user['rol'] == 'Gerencia'){
                    return redirect()->to('/aprobar');
                }else if($user['rol'] == 'Coordinacion'){
                    return redirect()->to('/validar');
                }
            }
        }
        return back()->withErrors([
            'message' => 'El usuario o la contraseña es incorrecto!'
        ]);

    }

    public function destroy(Request $request)
    {
        $request->session()->invalidate(); // Invalida la sesión activa
        $request->session()->regenerateToken(); // Regenera el token CSRF
        Cookie::forget('laravel_session');
        Cache::flush();



        return redirect()->to('/');
    }
}
