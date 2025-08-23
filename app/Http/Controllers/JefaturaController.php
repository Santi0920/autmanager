<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class JefaturaController extends Controller
{

    public function data1()
    {

        $agenciaU = session('agenciau');

        // Traemos todo ordenado
        $user = DB::select("SELECT * FROM concepto_autorizaciones ORDER BY No ASC, Letra ASC");

        // Agrupar por No
        $grupos = [];
        foreach ($user as $u) {
            $grupos[$u->No][] = $u;
        }

        return view('Jefatura/solicitudesjefatura', ['grupos' => $grupos, 'user' => $user]);
    }





    public function solicitudes(Request $request)
    {
        if (session('email') == null) {
            return redirect()->route('login');
        }
        $agenciaU = session('agenciau');
        $solicitudes = DB::select("SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte,B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, B.Bloqueado, B.ID_Concepto, C.Letra, C.No, C.Concepto, C.Areas, D.FechaInsercion
        FROM persona A
        JOIN autorizaciones B ON B.ID_Persona = A.ID
        JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
        JOIN documentosintesis D ON A.ID = D.ID_Persona
        WHERE
        B.ID > 6000
        AND (B.Estado = 2 OR B.Estado = 0)
        AND B.NomAgencia = '$agenciaU'");
        return datatables()->of($solicitudes)->toJson();
    }

    public function aprobados(Request $request)
    {
        if (session('email') == null) {
            return redirect()->route('login');
        }
        $agenciaU = session('agenciau');
        $solicitudes = DB::select("SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte,B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, B.Bloqueado, B.ID_Concepto, C.Letra, C.No, C.Concepto, C.Areas, D.FechaInsercion
        FROM persona A
        JOIN autorizaciones B ON B.ID_Persona = A.ID
        JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
        JOIN documentosintesis D ON A.ID = D.ID_Persona
        WHERE B.Estado = 4 AND B.NomAgencia = '$agenciaU'");
        return datatables()->of($solicitudes)->toJson();
    }


    public function rechazados(Request $request)
    {
        if (session('email') == null) {
            return redirect()->route('login');
        }
        $agenciaU = session('agenciau');
        $solicitudes = DB::select("SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte,B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, B.Bloqueado, B.ID_Concepto, C.Letra, C.No, C.Concepto, C.Areas, D.FechaInsercion
        FROM persona A
        JOIN autorizaciones B ON B.ID_Persona = A.ID
        JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
        JOIN documentosintesis D ON A.ID = D.ID_Persona
        WHERE B.Estado = 0 AND B.NomAgencia = '$agenciaU'");
        return datatables()->of($solicitudes)->toJson();
    }



    public function anulados(Request $request)
    {
        if (session('email') == null) {
            return redirect()->route('login');
        }
        $agenciaU = session('agenciau');
        $solicitudes = DB::select("SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte,B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, B.Bloqueado, B.ID_Concepto, C.Letra, C.No, C.Concepto, C.Areas, D.FechaInsercion
        FROM persona A
        JOIN autorizaciones B ON B.ID_Persona = A.ID
        JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
        JOIN documentosintesis D ON A.ID = D.ID_Persona
        WHERE B.Estado = 7 AND B.NomAgencia = '$agenciaU'");
        return datatables()->of($solicitudes)->toJson();
    }


    public function actualizardetalle(Request $request, $id)
    {
        $documento = DB::select('SELECT DocumentoSoporte, Validacion FROM autorizaciones WHERE ID = ?', [$id]);
        $cedula = $request->Cedulamodal;
        $validacion = $documento[0]->Validacion;


        $nombre_documento = $documento[0]->DocumentoSoporte;
        $nombre_archivo = 'Soporte-'.$id.'.pdf';

        $inputName = 'Soporte_' . $id;

        if ($request->hasFile($inputName)) {
            $file = $request->file($inputName);
            $filename = 'Soporte-' . $id . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('Storage/files/soporteautorizaciones'), $filename);

            // Actualiza el registro en la base de datos
            DB::table('autorizaciones')
                ->where('ID', $id)
                ->update(['DocumentoSoporte' => $filename]);
        }

        $tipoautorizacion = $request->CodigoAutorizacion;
        $convencion = null;
        $cuenta = null;
        $idpersona = 7323;
        $url = "http://srv-owncloud.coopserp.com/conexion_s400/api/";



        //concepto traer el id
        $existingConcepto = DB::select('SELECT ID FROM concepto_autorizaciones WHERE ID = ?', [$tipoautorizacion]);
        $idconcepto = $existingConcepto[0]->ID;
        Log::info('ID Concepto: ' . $idconcepto);
        //DISPOSICIONES
        if($tipoautorizacion == '41'){

            $attempts = 0;
            $maxAttempts = 3; // INTENTOS MÁXIMOS
            $retryDelay = 500; // Milisegundos

            do {
                try {
                    $response = Http::get($url . 'nombre/' . $cedula);
                    $data = $response->json();
                  // Si llegamos aquí, la solicitud fue exitosa, podemos salir del bucle.
                    break;
                } catch (\Exception $e) {
                    $attempts++;
                    usleep($retryDelay * 1000);
                }
            } while ($attempts < $maxAttempts);
            $estado = $data['status'];
            if ($estado == '200') {
                $nombre = $data['asociado']['NOMBRES'];
                $cuenta = $data['asociado']['CUENTA'];
            }else{
                return back()->with("incorrecto", "¡PERSONA NO EXISTE EN AS400!");
            }

            $existingPerson = DB::select('SELECT * FROM persona WHERE Cedula = ?', [$cedula]);

            if(empty($existingPerson)){
                $nombre = $data['asociado']['NOMBRES'];
                $cuenta = $data['asociado']['CUENTA'];
            }else{
                //traer el ID
                $existingID = DB::select('SELECT ID, Nombre, Apellidos FROM persona WHERE Cedula = ?', [$cedula]);
                $idpersona = $existingID[0]->ID;
                $nombres = $existingID[0]->Nombre;
                $apellidos = $existingID[0]->Apellidos;
                $nombre = $nombres . ' '.$apellidos;
            }

            $convencion = $request->Convencionmodal;

            //< 1 AÑO
        }else if($tipoautorizacion == '22'){
            //NOMBRE EMPRESA
            $nombre = "COOPSERP";
            $cedula = "805.004.034";
            $cuenta = 9;
            $idpersona = 14920;
        }else{
            $cedulaSinPuntos = str_replace('.', '', $cedula);
            $proveedores = DB::table('proveedor')
            ->where('NIT', 'LIKE', '%' . $cedulaSinPuntos . '%')
            ->get();
            if(!$proveedores->isEmpty()){
                $idpersona = $proveedores[0]->ID_Persona;
                $nombre = $proveedores[0]->RazonSocial;

            }else{
                $existingPerson = DB::select('SELECT * FROM persona WHERE Cedula = ?', [$cedula]);


                if(empty($existingPerson)){
                    //NOMBRE EMPRESA
                    $nombre = $request->Nombremodal;
                }else{
                    //traer el ID
                    $existingID = DB::select('SELECT ID, Nombre, Apellidos FROM persona WHERE Cedula = ?', [$cedula]);
                    $idpersona = $existingID[0]->ID;

                    $nombres = $existingID[0]->Nombre;
                    $apellidos = $existingID[0]->Apellidos;
                    $nombre = $nombres . ' '.$apellidos;
                }
            }


            //Y LA CEDULA LA ESTA TOMANDO COMO NIT
            $cuenta = null;

            $attempts = 0;
            $maxAttempts = 3; // INTENTOS MÁXIMOS
            $retryDelay = 500; // Milisegundos

            do {
                try {
                    $response = Http::get($url . 'nombre/' . $cedula);
                    $data = $response->json();
                  // Si llegamos aquí, la solicitud fue exitosa, podemos salir del bucle.
                    break;
                } catch (\Exception $e) {
                    $attempts++;
                    usleep($retryDelay * 1000);
                }
            } while ($attempts < $maxAttempts);
            if(!empty($data['status'])){
                if ($data['status'] == '200') {
                    $cuenta = $data['asociado']['CUENTA'];
                }
            }else{
                $cuenta = null;
            }

        }

        if($validacion == 1){
            $estado='2';
        }else{
            $estado='2';
        }


        $cedulaSinPuntos = str_replace('.', '', $cedula);
        $proveedores = DB::table('proveedor')
        ->where('NIT', 'LIKE', '%' . $cedulaSinPuntos . '%')
        ->get();
        if(!$proveedores->isEmpty()){
            $idpersona = $proveedores[0]->ID_Persona;
            $nombre = $proveedores[0]->RazonSocial;

        }


        //AUDITORIA
        $nombreauditoria = session('name');
        $rol = session('rol');
        date_default_timezone_set('America/Bogota');
        $fechaHoraActual = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        $agencia = session('agenciau');
        $login = DB::insert("INSERT INTO auditoria (Hora_login, Usuario_nombre, Usuario_Rol, AgenciaU, Acción_realizada, Hora_Accion, Cedula_Registrada, cerro_sesion, IP) VALUES (?, ?, ?, ?, 'CreoAutorizacionJefatura', ?, ?, ?, ?)", [
            null,
            $nombreauditoria,
            $rol,
            $agencia,
            $fechaHoraActual,
            $id . ' '.$cedula,
            null,
            $ip
        ]);

        //fecha de la solicitud de la jefatura corregida
        $fechadeSolicitud = Carbon::now('America/Bogota');
        Carbon::setLocale('es');
        $fechaStringfechadeSolicitud = $fechadeSolicitud->translatedFormat('F d Y-H:i:s');
        // Si el archivo se proporcionó y se movió correctamente, actualiza la base de datos
        if (isset($nombre_archivo)) {
            // $existingCedula = DB::select('SELECT Cedula FROM autorizaciones WHERE ID = ?', [$id]);
            // $cedula = $existingCedula[0]->Cedula;
            $update = DB::table('autorizaciones')
                ->where('ID', $id)
                ->update([
                    'Fecha' => $fechaStringfechadeSolicitud,
                    'Detalle' => $request->input('Detalle'),
                    'Cedula' => $cedula,
                    'CuentaAsociado' => $cuenta,
                    'Convencion' => $convencion,
                    'NombrePersona' => $nombre,
                    'ID_Persona' => $idpersona,
                    'DocumentoSoporte' => $nombre_archivo,
                    'Estado' => $estado,
                    'Solicitud' => 1,
                    'Validacion' => 0,
                    'Aprobacion' => 0,
                    'ObservacionesGer' => null,
                    'Observaciones' => null,
                    'ID_Concepto' => $idconcepto,
                ]);

            // Devuelve un mensaje de éxito si se proporcionó un archivo y se actualizó la base de datos
            return response()->json(['message' => 'Datos recibidos correctamente']);
        } else {
            // Devuelve un mensaje de error si no se proporcionó ningún archivo
            $update = DB::table('autorizaciones')
                ->where('ID', $id)
                ->update([
                    'Fecha' => $fechaStringfechadeSolicitud,
                    'Detalle' => $request->input('Detalle'),
                    'Cedula' => $cedula,
                    'CuentaAsociado' => $cuenta,
                    'Convencion' => $convencion,
                    'NombrePersona' => $nombre,
                    'ID_Persona' => $idpersona,
                    'DocumentoSoporte' => $nombre_archivo,
                    'Estado' => $estado,
                    'Solicitud' => 1,
                    'Validacion' => 0,
                    'Aprobacion' => 0,
                    'ObservacionesGer' => null,
                    'Observaciones' => null,
                    'ID_Concepto' => $idconcepto,
                ]);
            return response()->json(['message' => 'Datos recibidos correctamente']);
        }


    }


}
