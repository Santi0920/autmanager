<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SessionsController extends Controller
{
    public function login()
    {
        return view("login");
    }

    public function login_post(Request $request)
    {

        $password = $request->input('password');

        // Obtener todos los usuarios
        $users = User::all();

        // Iterar sobre los usuarios y verificar si alguna contraseña coincide
        foreach ($users as $user) {

            if (Hash::check($password, $user->password)) {

                // La contraseña coincide, autenticar al usuario manualmente
                Auth::login($user);




                if ($user->rol == 'Consultante') {
                    $usuarioActual = Auth::user();
                    $nombre = $usuarioActual->name;
                    $rol = $usuarioActual->rol;

                    date_default_timezone_set('America/Bogota');

                    $fechaHoraActual = date('Y-m-d H:i:s');
                    $ip = $_SERVER['REMOTE_ADDR'];
                    // $agencia = $usuarioActual->agenciau;
        // $login = DB::insert("INSERT INTO auditoria (Hora_login, Usuario_nombre, Usuario_Rol, AgenciaU, Acción_realizada, Hora_Accion, Cedula_Registrada, cerro_sesion, IP) VALUES (?, ?, ?, ?, 'Ingreso', ?, ?, ?, ?)", [
        //                 null,
        //                 $nombre,
        //                 $rol,
        //                 $agencia,
        //                 $fechaHoraActual,
        //                 null,
        //                 null,
        //                 $ip
        //             ]);

                    return redirect()->to('/solicitudes');


                } else if ($user->rol == 'Coordinacion') {
                    $usuarioActual = Auth::user();
                    $nombre = $usuarioActual->name;
                    $rol = $usuarioActual->rol;

                    date_default_timezone_set('America/Bogota');

                    $fechaHoraActual = date('Y-m-d H:i:s');
                    $ip = $_SERVER['REMOTE_ADDR'];
                    // $agencia = $usuarioActual->agenciau;
        // $login = DB::insert("INSERT INTO auditoria (Hora_login, Usuario_nombre, Usuario_Rol, AgenciaU, Acción_realizada, Hora_Accion, Cedula_Registrada, cerro_sesion, IP) VALUES (?, ?, ?, ?, 'Ingreso', ?, ?, ?, ?)", [
        //                 null,
        //                 $nombre,
        //                 $rol,
        //                 $agencia,
        //                 $fechaHoraActual,
        //                 null,
        //                 null,
        //                 $ip
        //             ]);

                    return redirect()->to('/validar');


                } else if ($user->rol == 'Gerencia') {
                    $usuarioActual = Auth::user();
                    $nombre = $usuarioActual->name;
                    $rol = $usuarioActual->rol;

                    date_default_timezone_set('America/Bogota');

                    $fechaHoraActual = date('Y-m-d H:i:s');
                    $ip = $_SERVER['REMOTE_ADDR'];
                    // $agencia = $usuarioActual->agenciau;
        // $login = DB::insert("INSERT INTO auditoria (Hora_login, Usuario_nombre, Usuario_Rol, AgenciaU, Acción_realizada, Hora_Accion, Cedula_Registrada, cerro_sesion, IP) VALUES (?, ?, ?, ?, 'Ingreso', ?, ?, ?, ?)", [
        //                 null,
        //                 $nombre,
        //                 $rol,
        //                 $agencia,
        //                 $fechaHoraActual,
        //                 null,
        //                 null,
        //                 $ip
        //             ]);

                    return redirect()->to('/aprobar');


                } else {
                    return back()->withErrors([
                        'message' => 'La contraseña es incorrecta!'
                    ]);
                }
            }
        }

            return back()->withErrors([
                'message' => 'La contraseña es incorrecta!'
            ]);




    }

    public function destroy(Request $request)
    {
        $usuarioActual = Auth::user();
        $nombre = $usuarioActual->name;
        $rol = $usuarioActual->rol;

        date_default_timezone_set('America/Bogota');
        $fechaHoraActual = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        $agencia = $usuarioActual->agenciau;
        // $logout = DB::insert("INSERT INTO auditoria (Hora_login, Usuario_nombre, Usuario_Rol, AgenciaU, Acción_realizada, Hora_Accion, Cedula_Registrada, cerro_sesion, IP) VALUES (?, ?, ?, ?, 'Cerro Sesión', ?, ?, ?, ?)", [
        //         null,
        //         $nombre,
        //         $rol,
        //         $agencia,
        //         $fechaHoraActual,
        //         null,
        //         null,
        //         $ip
        //     ]);


        auth()->logout();



        return redirect()->to('/');
    }
}
