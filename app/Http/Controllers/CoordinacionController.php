<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class CoordinacionController extends Controller
{
    public function data1()
    {

        $usuarioActual = Auth::user();
        $agenciaU = $usuarioActual->agenciau;
        $user = DB::select("SELECT * FROM concepto_autorizaciones ORDER BY Letra ASC");

        return view('Coordinacion/validarautorizacion', ['user' => $user]);
    }



    public function solicitudes(Request $request)
    {
        $usuarioActual = Auth::user();
        $agenciaU = $usuarioActual->agenciau;

        $agencias = DB::select("SELECT NumAgencia FROM autorizaciones");

        if ($agenciaU = $usuarioActual->agenciau == "Coordinacion 1") {
            $solicitudes = DB::select("SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte,B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, C.Letra, C.No, C.Concepto, C.Areas, D.FechaInsercion
            FROM persona A
            JOIN autorizaciones B ON B.ID_Persona = A.ID
            JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
            JOIN documentosintesis D ON A.ID = D.ID_Persona
            WHERE (B.Solicitud = 1 AND B.NumAgencia IN (35,34, 36, 37, 38, 40, 41, 87, 93, 96, 'C1'))");
        } else if ($agenciaU = $usuarioActual->agenciau == "Coordinacion 2") {
            $solicitudes = DB::select("SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte,B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, C.Letra, C.No, C.Concepto, C.Areas, D.FechaInsercion
            FROM persona A
            JOIN autorizaciones B ON B.ID_Persona = A.ID
            JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
            JOIN documentosintesis D ON A.ID = D.ID_Persona
            WHERE (B.Solicitud = 1 AND B.NumAgencia IN (33, 39, 46, 70, 77, 78, 80, 88, 92, 98, 'C2'))");
        } else if ($agenciaU = $usuarioActual->agenciau == "Coordinacion 3") {
            $solicitudes = DB::select("SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte,B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, C.Letra, C.No, C.Concepto, C.Areas, D.FechaInsercion
            FROM persona A
            JOIN autorizaciones B ON B.ID_Persona = A.ID
            JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
            JOIN documentosintesis D ON A.ID = D.ID_Persona
            WHERE (B.Solicitud = 1 AND B.NumAgencia IN (32, 42, 47, 81, 82, 83, 85, 90, 94, 'C3'))");
        } else if ($agenciaU = $usuarioActual->agenciau == "Coordinacion 4") {
            $solicitudes = DB::select("SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte,B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, C.Letra, C.No, C.Concepto, C.Areas, D.FechaInsercion
            FROM persona A
            JOIN autorizaciones B ON B.ID_Persona = A.ID
            JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
            JOIN documentosintesis D ON A.ID = D.ID_Persona
            WHERE (B.Solicitud = 1 AND B.NumAgencia IN (44, 45, 48, 49, 74, 75, 84, 89, 91, 95, 97, 'C4'))");
        } else if ($agenciaU = $usuarioActual->agenciau == "Coordinacion 5") {
            $solicitudes = DB::select("SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte,B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, C.Letra, C.No, C.Concepto, C.Areas, D.FechaInsercion
            FROM persona A
            JOIN autorizaciones B ON B.ID_Persona = A.ID
            JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
            JOIN documentosintesis D ON A.ID = D.ID_Persona
            WHERE (B.Solicitud = 1 AND B.NumAgencia IN (13, 30, 31, 43, 68, 73, 76, 86, 'C5'))");
        } else if ($agenciaU = $usuarioActual->agenciau == "Coordinacion 9") {
            $solicitudes = DB::select("SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte,B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, C.Letra, C.No, C.Concepto, C.Areas, D.FechaInsercion
            FROM persona A
            JOIN autorizaciones B ON B.ID_Persona = A.ID
            JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
            JOIN documentosintesis D ON A.ID = D.ID_Persona
            WHERE (B.Solicitud = 1 AND B.NumAgencia IN ('Jefatura'))");
        }
        return datatables()->of($solicitudes)->toJson();
    }


    public function solicitarAutorizacion(Request $request)
    {

        $tipoautorizacion = $request->tautorizacion;
        $detalle = $request->detalle;
        $cedula = $request->cedula;
        $convencion = null;
        $cuenta = null;
        $idpersona = 7323;
        $url = "http://srv-owncloud.coopserp.com/conexion_s400/api/";


        if (str_contains($tipoautorizacion, '1100')||str_contains($tipoautorizacion, '1200')||str_contains($tipoautorizacion, '1300')||str_contains($tipoautorizacion, '1400')||str_contains($tipoautorizacion, '1500') || str_contains($tipoautorizacion, '1600') || str_contains($tipoautorizacion, '1700') || str_contains($tipoautorizacion, '1800') ||str_contains($tipoautorizacion, '1900') || str_contains($tipoautorizacion, '2000') || str_contains($tipoautorizacion, '2100') ||str_contains($tipoautorizacion, '2200') || str_contains($tipoautorizacion, '2150') ||  str_contains($tipoautorizacion, '2250') || str_contains($tipoautorizacion, '2350') || str_contains($tipoautorizacion, '2300') ||str_contains($tipoautorizacion, '2400')|| str_contains($tipoautorizacion, '2500') || str_contains($tipoautorizacion, '2600') || str_contains($tipoautorizacion, '2700')){
            // Número y letra del concepto
            $No = substr($tipoautorizacion, 0, 4);
            $letra = substr($tipoautorizacion, 4, 3); // Cambiado a 1 para obtener solo una letra
        } else {
            // Número y letra del concepto
            $No = substr($tipoautorizacion, 0, 2);
            $letra = substr($tipoautorizacion, 2, 3); // Cambiado a 1 para obtener solo una letra
        }


        //fecha de la solicitud del director
        $fechadeSolicitud = Carbon::now('America/Bogota');
        Carbon::setLocale('es');
        $fechaStringfechadeSolicitud = $fechadeSolicitud->translatedFormat('F d Y-H:i:s');

        //TRAER INFORMACION DE LA AGENCIA Y EL ROL
        $usuarioActual = Auth::user();
        $agenciaU = $usuarioActual->agenciau;
        $nombreU = $usuarioActual->name;
        $rol = $usuarioActual->rol;

        //concepto traer el id
        $existingConcepto = DB::select('SELECT ID FROM concepto_autorizaciones WHERE No = ? AND Letra = ?', [$No, $letra]);
        $idconcepto = $existingConcepto[0]->ID;


        //si es igual a director
        $numAgencia = $usuarioActual->rol;
        if($rol == 'Consultante'){
            //traer el numero de agencia PARA INSERTARLO
            $existeAgencia = DB::select('SELECT * FROM agencias WHERE NameAgencia = ?', [$agenciaU]);
            $numAgencia = $existeAgencia[0]->NumAgencia;
        }else if($rol == 'Coordinacion'){
            if($usuarioActual->agenciau == 'Coordinacion 1'){
                $numAgencia = 'C1';
            }else if($usuarioActual->agenciau == 'Coordinacion 2'){
                $numAgencia = 'C2';
            }else if($usuarioActual->agenciau == 'Coordinacion 3'){
                $numAgencia = 'C3';
            }else if($usuarioActual->agenciau == 'Coordinacion 4'){
                $numAgencia = 'C4';
            }else if($usuarioActual->agenciau == 'Coordinacion 5'){
                $numAgencia = 'C5';
            }
        }

        $condicionTalento = in_array($tipoautorizacion, [
            "10A", "10B", "10C", "10D", "10E", "10F", "10G", "10H", "10I", "10J", "10K", "10L"
        ]);

        $condicionCoordinacion = in_array($tipoautorizacion, [
            // "11M", "11N", "11O", "11P", "11Q", "11R", "11S", "11T", "11U", "11V", "11X"
            "11K", "11L", "11M", "11N", "11O", "11P", "11Q", "11R"
        ]);

        $condicionSistemas = in_array($tipoautorizacion, [
            // "19A", "19J", "19D", "19E", "19F", "19G", "19H"
            "19B", "19C"
        ]);

        $condicionJurdicoZn = in_array($tipoautorizacion, ['2250C']); // Aquí puedes añadir tus condiciones específicas para JurdicoZn

        $condicionJurdicoZc = in_array($tipoautorizacion,['2150C']); // Aquí puedes añadir tus condiciones específicas para JurdicoZc

        $condicionJurdicoZs = in_array($tipoautorizacion,['2350C']); // Aquí puedes añadir tus condiciones específicas para JurdicoZs

        $condicionTesoreria = in_array($tipoautorizacion, ["15A", "15C"]);

        $condicionMeredian = in_array($tipoautorizacion, ["24A",
                // "2400B"
        ]);


        $condicionGlobal= in_array($tipoautorizacion, ['0A','0B','0F','0J','0K']);

        $condicionFundacion = in_array($tipoautorizacion, ["14A"]);

        $condicionSeguros = in_array(
            // $tipoautorizacion, ["2300D"]
        $tipoautorizacion, ["23A"]
    );

        $condicionConsejo = []; // Aquí puedes añadir tus condiciones específicas para Consejo

        $condicionit = $condicionTalento || $condicionSistemas || $condicionCoordinacion || $condicionJurdicoZn ||
                            $condicionJurdicoZc || $condicionJurdicoZs || $condicionTesoreria || $condicionMeredian ||
                            $condicionFundacion || $condicionConsejo || $condicionSeguros|| $condicionGlobal;


        //ASOCIACION POR SCORE BAJO
        if($tipoautorizacion == '11A'){
            $existingPerson = DB::select('SELECT * FROM persona WHERE Cedula = ?', [$cedula]);


            if(empty($existingPerson)){
                return back()->with("incorrecto", "¡PERSONA NO EXISTE EN DATACRÉDITO!");
            }
            //traer el ID
            $existingID = DB::select('SELECT ID, Nombre, Apellidos FROM persona WHERE Cedula = ?', [$request->cedula]);
            $idpersona = $existingID[0]->ID;

            $nombres = $existingID[0]->Nombre;
            $apellidos = $existingID[0]->Apellidos;
            $nombre = $nombres . ' '.$apellidos;

        //ASOCIACION < 90 DIAS ENTREGAR BONO
        }else if($tipoautorizacion == '11B'){
            $cuenta = $request->Cuenta;
            $attempts = 0;
            $maxAttempts = 3; // INTENTOS MÁXIMOS
            $retryDelay = 500; // Milisegundos

            do {
                try {
                    $response = Http::get($url . 'retiro/' . $cuenta);
                    $data = $response->json();

                    $response2 = Http::get($url . 'nombre/' . $cedula);
                    $data2 = $response2->json();
                  // Si llegamos aquí, la solicitud fue exitosa, podemos salir del bucle.
                    break;
                } catch (\Exception $e) {
                    $attempts++;
                    usleep($retryDelay * 1000);
                }
            } while ($attempts < $maxAttempts);
            $estado = $data2['status'];
            if ($estado == '200') {
                $nombre = $data['asociado']['NOMBRES'];
                $cuenta = $data['asociado']['CUENTA'];
            }else{
                return back()->with("incorrecto", "¡PERSONA NO EXISTE EN AS400!");
            }

            $fretiro = $data['asociado']['RETIRO'];

            if($fretiro != 0){
                $fechaActual = Carbon::now('America/Bogota');

                // Extraer los componentes de la fecha (año, mes, día)
                $año = substr($fretiro, 1, 2);
                $mes = substr($fretiro, 3, 2);
                $dia = substr($fretiro, 5, 2);

                // Corregir el año si es necesario
                if ($año < 200) {
                    $año += 2000; // Si el año es menor que 100, se asume que es en este siglo.
                }
                // Crear un objeto de fecha con los componentes
                $fecha_retiro = Carbon::create($año, $mes, $dia);
                $dias_restantes = $fechaActual->diffInDays($fecha_retiro);


                if($dias_restantes > 89){
                        return back()->with("incorrecto", "No necesita autorización, tiene ".$dias_restantes." dias asociado a COOPSERP.!");
                }

            }else{
                return back()->with("incorrecto","No aplica porque aun está vinculado a COOPSERP.");
            }

        //AUTORIZACION POR CREDITO SCORE BAJO
        }else if($tipoautorizacion == '11D' || $tipoautorizacion == '11L'){
            $existingPerson = DB::select('SELECT * FROM persona WHERE Cedula = ?', [$cedula]);


            if(empty($existingPerson)){
                return back()->with("incorrecto", "¡PERSONA NO EXISTE EN DATACRÉDITO!");
            }
            //traer el ID
            $existingID = DB::select('SELECT ID, Nombre, Apellidos, CuentaAsociada FROM persona WHERE Cedula = ?', [$request->cedula]);
            $idpersona = $existingID[0]->ID;

            $nombres = $existingID[0]->Nombre;
            $apellidos = $existingID[0]->Apellidos;
            $nombre = $nombres . ' '.$apellidos;
            $cuenta = $existingID[0]->CuentaAsociada;
            //Desembolso Creditos por Transferencia Electronica
        }else if($tipoautorizacion == '11L'){
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

        //DISPOSICINES
        }else if($tipoautorizacion == '11K'){

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

            $convencion = $request->convencion;

            //< 1 AÑO
        }else if($tipoautorizacion == '11C'){
            $nombre = $request->nombre;
            $cuenta = $request->cuenta;
        }else if($condicionit){

            //NOMBRE EMPRESA
            $nombre = $request->nombre;

            //Y LA CEDULA LA ESTA TOMANDO COMO NIT


            //NOMINA COOPSERP EMPLEADOS
        }else if($tipoautorizacion == '10D'){

            //NOMBRE EMPRESA
            $nombre = "COOPSERP";
            $cedula = "805.004.034-9";

        }else{

            //NOMBRE EMPRESA
            $nombre = $request->nombre;

            //Y LA CEDULA LA ESTA TOMANDO COMO NIT


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



        // Verificar si se subió un archivo
        if (!$request->hasFile('SoporteScore')) {
            return back()->withErrors(['message' => 'No se subió ningún archivo.']);
        }

        $file = $request->file('SoporteScore');
        $filename = $file->getClientOriginalName();

        // Verificar si el archivo es PDF
        if ($file->getClientOriginalExtension() !== 'pdf') {
            return back()->withErrors(['message' => 'Solo se pueden subir archivos PDF.']);
        }

        // Contar archivos existentes y crear nuevo nombre de archivo
        $existingFilesCount = DB::table('autorizaciones')
        ->where('Cedula', $cedula)
        ->where('DocumentoSoporte', 'like', 'Soporte-' . $cedula . '%')
        ->count();
        if ($existingFilesCount == 0) {
            // Si no hay archivos existentes, guardarlo como el primero
            $newFilename = 'Soporte-' . $cedula.'.pdf';
        } else {
            // Si hay archivos existentes, generar un nombre de archivo único
            $newFilename = 'Soporte-' . $cedula . '-' . ($existingFilesCount + 1).'.pdf';
        }

        // Subir el archivo
        $dir = 'Storage/files/soporteautorizaciones/';
        if (!$file->move($dir, $newFilename)) {
            return back()->withErrors(['message' => 'Error al subir el archivo.']);
        }

        //AUDITORIA
        $usuarioActual = Auth::user();
        $nombreauditoria = $usuarioActual->name;
        $rol = $usuarioActual->rol;
        date_default_timezone_set('America/Bogota');
        $fechaHoraActual = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        $agencia = $usuarioActual->agenciau;
        $login = DB::insert("INSERT INTO auditoria (Hora_login, Usuario_nombre, Usuario_Rol, AgenciaU, Acción_realizada, Hora_Accion, Cedula_Registrada, cerro_sesion, IP) VALUES (?, ?, ?, ?, 'CreoutorizacionCoordinador', ?, ?, ?, ?)", [
            null,
            $nombreauditoria,
            $rol,
            $agencia,
            $fechaHoraActual,
            $cedula,
            null,
            $ip
        ]);


        //insercion
        $id_insertado = DB::table('autorizaciones')->insertGetId([
            'Fecha' => $fechaStringfechadeSolicitud,
            'CodigoAutorizacion' => $tipoautorizacion,
            'CuentaAsociado' => $cuenta,
            'Convencion' => $convencion,
            'NumAgencia' => $numAgencia,
            'NomAgencia' => $agenciaU,
            'Cedula' => $cedula,
            'NombrePersona' => $nombre,
            'Detalle' => $detalle,
            'Estado' => 6,
            'Solicitud' => 1,
            'Validacion' => 1,
            'SolicitadoPor' => $nombreU,
            'ID_Persona' => $idpersona,
            'ID_Concepto' => $idconcepto,
            'DocumentoSoporte' => $newFilename
        ]);





            return back()->with("correcto", "<span class='fs-4'>La autorización No. <span class='badge bg-primary fw-bold'>" . $id_insertado . "</span> está en trámite.</span>");







    }


    public function validarAutorizacion(Request $request, $id)
    {
        $usuarioActual = Auth::user();
        $nombre = $usuarioActual->name;
        $noCoordinacion = $usuarioActual->agenciau;
        $estadoautorizacion = $request->Estado;

        if($noCoordinacion == 'Coordinacion 1'){
            $coordinacion = 'C1';
        }else if($noCoordinacion == 'Coordinacion 2'){
            $coordinacion = 'C2';
        }else if($noCoordinacion == 'Coordinacion 3'){
            $coordinacion = 'C3';
        }else if($noCoordinacion == 'Coordinacion 4'){
            $coordinacion = 'C4';
        }else if($noCoordinacion == 'Coordinacion 5'){
            $coordinacion = 'C5';
        }else if($noCoordinacion == 'Coordinacion 9'){
            $coordinacion = 'C9';
        }
        $fechadeSolicitud = Carbon::now('America/Bogota');

        Carbon::setLocale('es');
        $fechaStringfechadeSolicitud = $fechadeSolicitud->translatedFormat('F d Y-H:i:s');

        //AUDITORIA
        $usuarioActual = Auth::user();
        $nombreauditoria = $usuarioActual->name;
        $rol = $usuarioActual->rol;
        date_default_timezone_set('America/Bogota');
        $fechaHoraActual = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        $agencia = $usuarioActual->agenciau;
        $login = DB::insert("INSERT INTO auditoria (Hora_login, Usuario_nombre, Usuario_Rol, AgenciaU, Acción_realizada, Hora_Accion, Cedula_Registrada, cerro_sesion, IP) VALUES (?, ?, ?, ?, 'ValidoAutorizacionCoordinador', ?, ?, ?, ?)", [
            null,
            $nombreauditoria,
            $rol,
            $agencia,
            $fechaHoraActual,
            $id . ' '. $estadoautorizacion,
            null,
            $ip
        ]);


        if ($estadoautorizacion == '0' || $estadoautorizacion == '2' || $estadoautorizacion == '3') {
            $update = DB::table('autorizaciones')
                ->where('ID', $id)
                ->update([
                    'Observaciones' => $request->Observaciones,
                    'Estado' => $request->input('Estado'),
                    'ValidadoPor' => $nombre,
                    'Coordinacion' => $coordinacion,
                    'FechaValidacion' => $fechaStringfechadeSolicitud
                ]);
            //si fue validado
        } else if ($estadoautorizacion == '1') {
            $update = DB::table('autorizaciones')
                ->where('ID', $id)
                ->update([
                    'Observaciones' => $request->Observaciones,
                    'Estado' => $request->input('Estado'),
                    'ValidadoPor' => $nombre,
                    'Validacion' => 1,
                    'Coordinacion' => $coordinacion,
                    'FechaValidacion' => $fechaStringfechadeSolicitud
                ]);
        }



        return response()->json(['success' => true]);
    }



    public function actualizardetalle(Request $request, $id)
    {
        $documento = DB::select('SELECT DocumentoSoporte, Validacion FROM autorizaciones WHERE ID = ?', [$id]);
        $cedula = $request->Cedulamodal;
        $validacion = $documento[0]->Validacion;



        $nombre_documento = $documento[0]->DocumentoSoporte;
        if ($request->hasFile('Soporte')) {
            if (!empty($documento)) {

                // Buscar el número en el nombre del documento
                preg_match('/\d+$/', $nombre_documento, $matches);

                // Verificar si se encontró algún número en el nombre del documento
                if (!empty($matches)) {
                    $numero_documento = $matches[0];

                    // Incrementar el número para el nuevo documento
                    $nuevo_numero_documento = $numero_documento + 1;

                    // Construir el nuevo nombre de archivo con el número incrementado
                    $nombre_archivo = "Soporte-" . $cedula . "-" . $nuevo_numero_documento . ".pdf";
                } else {
                    // Si no se encontró ningún número en el nombre del documento, asignar un nombre con el número 1
                    $nombre_archivo = "Soporte-" . $cedula . "-1.pdf";
                }
            } else {
                // Si no existe un documento en la base de datos, asignar un nombre basado en la cédula
                $nombre_archivo = $nombre_documento;
            }
        }

        if ($request->hasFile('Soporte')) {
            $soporte = $request->file('Soporte');
            $dir = 'Storage/files/soporteautorizaciones/';

            // Mover el archivo con el nuevo nombre
            $soporte->move($dir, $nombre_archivo);
        }

        $tipoautorizacion = $request->CodigoAutorizacion;
        $convencion = null;
        $cuenta = null;
        $idpersona = 7323;
        $url = "http://srv-owncloud.coopserp.com/conexion_s400/api/";

        // Número y letra del concepto
        if (str_contains($tipoautorizacion, '1100')||str_contains($tipoautorizacion, '1200')||str_contains($tipoautorizacion, '1300')||str_contains($tipoautorizacion, '1400')||str_contains($tipoautorizacion, '1500') || str_contains($tipoautorizacion, '1600') || str_contains($tipoautorizacion, '1700') || str_contains($tipoautorizacion, '1800') ||str_contains($tipoautorizacion, '1900') || str_contains($tipoautorizacion, '2000') || str_contains($tipoautorizacion, '2100') ||str_contains($tipoautorizacion, '2200') || str_contains($tipoautorizacion, '2150') ||  str_contains($tipoautorizacion, '2250') || str_contains($tipoautorizacion, '2350') || str_contains($tipoautorizacion, '2300') ||str_contains($tipoautorizacion, '2400')|| str_contains($tipoautorizacion, '2500') || str_contains($tipoautorizacion, '2600') || str_contains($tipoautorizacion, '2700')){
            // Número y letra del concepto
            $No = substr($tipoautorizacion, 0, 4);
            $letra = substr($tipoautorizacion, 4, 1);
            $actual = substr($tipoautorizacion, 5, 6);
        } else {
            // Número y letra del concepto
            $No = substr($tipoautorizacion, 0, 2);
            $letra = substr($tipoautorizacion, 2, 1);
            $actual = substr($tipoautorizacion, 3, 6); // Cambiado a 1 para obtener solo una letra
        }


        $condicionTalento = in_array($tipoautorizacion, [
            "10A", "10B", "10C", "10D", "10E", "10F", "10G", "10H", "10I", "10J", "10K", "10L"
        ]);

        $condicionCoordinacion = in_array($tipoautorizacion, [
            // "11M", "11N", "11O", "11P", "11Q", "11R", "11S", "11T", "11U", "11V", "11X"
            "11K", "11L", "11M", "11N", "11O", "11P", "11Q", "11R"
        ]);

        $condicionSistemas = in_array($tipoautorizacion, [
            // "19A", "19J", "19D", "19E", "19F", "19G", "19H"
            "19B", "19C"
        ]);

        $condicionJurdicoZn = in_array($tipoautorizacion, ['2250C']); // Aquí puedes añadir tus condiciones específicas para JurdicoZn

        $condicionJurdicoZc = in_array($tipoautorizacion,['2150C']); // Aquí puedes añadir tus condiciones específicas para JurdicoZc

        $condicionJurdicoZs = in_array($tipoautorizacion,['2350C']); // Aquí puedes añadir tus condiciones específicas para JurdicoZs

        $condicionTesoreria = in_array($tipoautorizacion, ["15A", "15C"]);

        $condicionMeredian = in_array($tipoautorizacion, ["24A",
                // "2400B"
        ]);


        $condicionGlobal= in_array($tipoautorizacion, ['0A','0B','0F','0J','0K']);

        $condicionFundacion = in_array($tipoautorizacion, ["14A"]);

        $condicionSeguros = in_array(
            // $tipoautorizacion, ["2300D"]
        $tipoautorizacion, ["23A"]
    );

        $condicionConsejo = []; // Aquí puedes añadir tus condiciones específicas para Consejo

        $condicionit = $condicionTalento || $condicionSistemas || $condicionCoordinacion || $condicionJurdicoZn ||
                            $condicionJurdicoZc || $condicionJurdicoZs || $condicionTesoreria || $condicionMeredian ||
                            $condicionFundacion || $condicionConsejo || $condicionSeguros|| $condicionGlobal;

        //concepto traer el id
        $existingConcepto = DB::select('SELECT ID FROM concepto_autorizaciones WHERE No = ? AND Letra = ?', [$No, $letra]);
        $idconcepto = $existingConcepto[0]->ID;


        //ASOCIACION POR SCORE BAJO
        if($tipoautorizacion . $actual == $tipoautorizacion . 'actual'){
            $existingAutorizacion = DB::select('SELECT * FROM autorizaciones WHERE ID = ?', [$id]);

            $cedula = $existingAutorizacion[0]->Cedula;
            $cuenta = $existingAutorizacion[0]->CuentaAsociado;
            $convencion = $existingAutorizacion[0]->Convencion;
            $nombre = $existingAutorizacion[0]->NombrePersona;
        }else if($tipoautorizacion == '11A'){
            $existingPerson = DB::select('SELECT * FROM persona WHERE Cedula = ?', [$cedula]);


            if(empty($existingPerson)){
                return back()->with("incorrecto", "¡PERSONA NO EXISTE EN DATACRÉDITO!");
            }
            //traer el ID
            $existingID = DB::select('SELECT ID, Nombre, Apellidos FROM persona WHERE Cedula = ?', [$cedula]);
            $idpersona = $existingID[0]->ID;

            $nombres = $existingID[0]->Nombre;
            $apellidos = $existingID[0]->Apellidos;
            $nombre = $nombres . ' '.$apellidos;

        //ASOCIACION < 90 DIAS ENTREGAR BONO
        }else if($tipoautorizacion == '11B'){
            $cuenta = $request->Cuentamodal;
            $attempts = 0;
            $maxAttempts = 3; // INTENTOS MÁXIMOS
            $retryDelay = 500; // Milisegundos

            do {
                try {
                    $response = Http::get($url . 'retiro/' . $cuenta);
                    $data = $response->json();

                    $response2 = Http::get($url . 'nombre/' . $cedula);
                    $data2 = $response2->json();
                  // Si llegamos aquí, la solicitud fue exitosa, podemos salir del bucle.
                    break;
                } catch (\Exception $e) {
                    $attempts++;
                    usleep($retryDelay * 1000);
                }
            } while ($attempts < $maxAttempts);
            $estado = $data2['status'];
            if ($estado == '200') {
                $nombre = $data['asociado']['NOMBRES'];
                $cuenta = $data['asociado']['CUENTA'];
            }else{
                return back()->with("incorrecto", "¡PERSONA NO EXISTE EN AS400!");
            }

            $fretiro = $data['asociado']['RETIRO'];

            if($fretiro != 0){
                $fechaActual = Carbon::now('America/Bogota');

                // Extraer los componentes de la fecha (año, mes, día)
                $año = substr($fretiro, 1, 2);
                $mes = substr($fretiro, 3, 2);
                $dia = substr($fretiro, 5, 2);

                // Corregir el año si es necesario
                if ($año < 200) {
                    $año += 2000; // Si el año es menor que 100, se asume que es en este siglo.
                }
                // Crear un objeto de fecha con los componentes
                $fecha_retiro = Carbon::create($año, $mes, $dia);
                $dias_restantes = $fechaActual->diffInDays($fecha_retiro);


                if($dias_restantes > 89){
                        return back()->with("incorrecto", "No necesita autorización, tiene ".$dias_restantes." dias asociado a COOPSERP.!");
                }

            }else{
                return back()->with("incorrecto","No aplica porque aun está vinculado a COOPSERP.");
            }

        //AUTORIZACION POR CREDITO SCORE BAJO
        }else if($tipoautorizacion == '11D'|| $tipoautorizacion == '11L'){
            $existingPerson = DB::select('SELECT * FROM persona WHERE Cedula = ?', [$cedula]);


            if(empty($existingPerson)){
                return back()->with("incorrecto", "¡PERSONA NO EXISTE EN DATACRÉDITO!");
            }
            //traer el ID
            $existingID = DB::select('SELECT ID, Nombre, Apellidos, CuentaAsociada FROM persona WHERE Cedula = ?', [$cedula]);
            $idpersona = $existingID[0]->ID;

            $nombres = $existingID[0]->Nombre;
            $apellidos = $existingID[0]->Apellidos;
            $nombre = $nombres . ' '.$apellidos;
            $cuenta = $existingID[0]->CuentaAsociada;
            //Desembolso Creditos por Transferencia Electronica
        }else if($tipoautorizacion == '11L'){
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

        //DISPOSICINES
        }else if($tipoautorizacion == '11K'){

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

            $convencion = $request->Convencionmodal;

            //< 1 AÑO
        }else if($tipoautorizacion == '11C'){
            $nombre = $request->Nombremodal;
            $cuenta = $request->Cuentamodal;
        }else if($condicionit){

            //NOMBRE EMPRESA
            $nombre = $request->Nombremodal;

            //Y LA CEDULA LA ESTA TOMANDO COMO NIT


            //NOMINA COOPSERP EMPLEADOS
        }else if($tipoautorizacion == '10D'){

            //NOMBRE EMPRESA
            $nombre = "COOPSERP";
            $cedula = "805.004.034-9";

        }else{
            //NOMBRE EMPRESA
            $nombre = $request->Nombremodal;

            //Y LA CEDULA LA ESTA TOMANDO COMO NIT


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

        //AUDITORIA
        $usuarioActual = Auth::user();
        $nombreauditoria = $usuarioActual->name;
        $rol = $usuarioActual->rol;
        date_default_timezone_set('America/Bogota');
        $fechaHoraActual = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        $agencia = $usuarioActual->agenciau;
        $login = DB::insert("INSERT INTO auditoria (Hora_login, Usuario_nombre, Usuario_Rol, AgenciaU, Acción_realizada, Hora_Accion, Cedula_Registrada, cerro_sesion, IP) VALUES (?, ?, ?, ?, 'ModificoAutorizacionCoordinador', ?, ?, ?, ?)", [
            null,
            $nombreauditoria,
            $rol,
            $agencia,
            $fechaHoraActual,
            $id,
            null,
            $ip
        ]);


        // Si el archivo se proporcionó y se movió correctamente, actualiza la base de datos
        if (isset($nombre_archivo)) {
            // $existingCedula = DB::select('SELECT Cedula FROM autorizaciones WHERE ID = ?', [$id]);
            // $cedula = $existingCedula[0]->Cedula;
            $update = DB::table('autorizaciones')
                ->where('ID', $id)
                ->update([
                    'Detalle' => $request->input('Detalle'),
                    'Cedula' => $cedula,
                    'CuentaAsociado' => $cuenta,
                    'Convencion' => $convencion,
                    'NombrePersona' => $nombre,
                    'ID_Persona' => $idpersona,
                    'CodigoAutorizacion' => $No.$letra,
                    'DocumentoSoporte' => $nombre_archivo,
                    'Estado' => $estado,
                    'Solicitud' => 1,
                    'Validacion' => 1,
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
                    'Detalle' => $request->input('Detalle'),
                    'Cedula' => $cedula,
                    'CuentaAsociado' => $cuenta,
                    'Convencion' => $convencion,
                    'NombrePersona' => $nombre,
                    'ID_Persona' => $idpersona,
                    'CodigoAutorizacion' => $No.$letra,
                    'DocumentoSoporte' => $nombre_documento,
                    'Estado' => $estado,
                    'Solicitud' => 1,
                    'Validacion' => 1,
                    'Aprobacion' => 0,
                    'ObservacionesGer' => null,
                    'Observaciones' => null,
                    'ID_Concepto' => $idconcepto,
                ]);
            return response()->json(['message' => 'Datos recibidos correctamente']);
        }


    }
}
