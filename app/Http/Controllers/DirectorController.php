<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DirectorController extends Controller
{

    public function data1()
    {

        $usuarioActual = Auth::user();
        $agenciaU = $usuarioActual->agenciau;
        $user = DB::select("SELECT * FROM concepto_autorizaciones ORDER BY Letra ASC");

        return view('Director/solicitudes', ['user' => $user]);
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


        //fecha de la solicitud del director
        $fechadeSolicitud = Carbon::now('America/Bogota');
        Carbon::setLocale('es');
        $fechaStringfechadeSolicitud = $fechadeSolicitud->translatedFormat('F d Y-H:i:s');

        //TRAER INFORMACION DE LA AGENCIA Y EL ROL
        $usuarioActual = Auth::user();
        $agenciaU = $usuarioActual->agenciau;
        $nombreU = $usuarioActual->name;
        $rol = $usuarioActual->rol;

        if (str_contains($tipoautorizacion, '1100')||str_contains($tipoautorizacion, '1200')||str_contains($tipoautorizacion, '1300')||str_contains($tipoautorizacion, '1400')||str_contains($tipoautorizacion, '1500') || str_contains($tipoautorizacion, '1600') || str_contains($tipoautorizacion, '1700') || str_contains($tipoautorizacion, '1800') ||str_contains($tipoautorizacion, '1900') || str_contains($tipoautorizacion, '2000') || str_contains($tipoautorizacion, '2100') ||str_contains($tipoautorizacion, '2200') || str_contains($tipoautorizacion, '2150') ||  str_contains($tipoautorizacion, '2250') || str_contains($tipoautorizacion, '2350') || str_contains($tipoautorizacion, '2300') ||str_contains($tipoautorizacion, '2400')|| str_contains($tipoautorizacion, '2500') || str_contains($tipoautorizacion, '2600') || str_contains($tipoautorizacion, '2700')){
            // Número y letra del concepto
            $No = substr($tipoautorizacion, 0, 4);
            $letra = substr($tipoautorizacion, 4, 3); // Cambiado a 1 para obtener solo una letra
        } else {
            // Número y letra del concepto
            $No = substr($tipoautorizacion, 0, 2);
            $letra = substr($tipoautorizacion, 2, 3); // Cambiado a 1 para obtener solo una letra
        }

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


        //CONDICION PARA LOS QUE TIENEN NIT
        // $condicionit = ($tipoautorizacion === '10M' || $tipoautorizacion === '11E'|| $tipoautorizacion === '11AB'|| $tipoautorizacion === '10J' || $tipoautorizacion === '10G' || $tipoautorizacion === '10N' ||$tipoautorizacion === '10O' ||$tipoautorizacion === '10P' || $tipoautorizacion === '2300D' || $tipoautorizacion === '2400A' || $tipoautorizacion === '1500A' || $tipoautorizacion === '19J');
        $condicionit = ($tipoautorizacion === '10M' || $tipoautorizacion === '11E'||      $tipoautorizacion=== '11F'|| $tipoautorizacion==='11M'|| $tipoautorizacion==='11N'|| $tipoautorizacion=== '11O'|| $tipoautorizacion==='11P'|| $tipoautorizacion==='11Q' || $tipoautorizacion=== '11R'|| $tipoautorizacion==='11S'|| $tipoautorizacion==='11T'  || $tipoautorizacion=== '11U'|| $tipoautorizacion==='11V'|| $tipoautorizacion==='11W' || $tipoautorizacion=== '11X'|| $tipoautorizacion==='11Y'|| $tipoautorizacion==='11Z'||    $tipoautorizacion === '11AB'||    $tipoautorizacion=== '11AD'|| $tipoautorizacion==='11AE'|| $tipoautorizacion==='11AF'|| $tipoautorizacion==='11AG'|| $tipoautorizacion === '10J' || $tipoautorizacion === '10G' || $tipoautorizacion === '10N' ||$tipoautorizacion === '10O' ||$tipoautorizacion === '10P' || $tipoautorizacion === '2300D' || $tipoautorizacion === '2400A' || $tipoautorizacion==='2400B'||$tipoautorizacion === '1500A' || $tipoautorizacion === '19J'|| $tipoautorizacion === '19D'|| $tipoautorizacion === '19E'||  $tipoautorizacion === '1400A' ||  $tipoautorizacion === '1500C');


        //ASOCIACION SCORE BAJO
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
        }else if($tipoautorizacion == '11D'){
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
        }else if($tipoautorizacion == '11G'){
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

        //cruces
        }else if($tipoautorizacion == '19B'){

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
        }else if($tipoautorizacion == '11C'){
            $nombre = $request->nombre;
            $cuenta = $request->cuenta;
        }else if($condicionit){

            //NOMBRE EMPRESA
            $nombre = $request->nombre;

            //Y LA CEDULA LA ESTA TOMANDO COMO NIT
        }else if($tipoautorizacion == '10M'){

            //NOMBRE EMPRESA
            $nombre = "COOPSERP";
            $cedula = "805.004.034-9";

        }else{
            $attempts = 0;
            $maxAttempts = 3; // INTENTOS MÁXIMOS
            $retryDelay = 500; // Milisegundos
            $url = "http://srv-owncloud.coopserp.com/conexion_s400/api/";
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
            'Estado' => 2,
            'Solicitud' => 1,
            'SolicitadoPor' => $nombreU,
            'ID_Persona' => $idpersona,
            'ID_Concepto' => $idconcepto,
            'DocumentoSoporte' => $newFilename,

        ]);

        return back()->with("correcto", "<span class='fs-4'>La autorización No. <span class='badge bg-primary fw-bold'>" . $id_insertado . "</span> está en trámite.</span>");



    }

    public function solicitudes(Request $request)
    {
        $usuarioActual = Auth::user();
        $agenciaU = $usuarioActual->agenciau;
        $solicitudes = DB::select("SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte,B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, C.Letra, C.No, C.Concepto, C.Areas
        FROM persona A
        JOIN autorizaciones B ON B.ID_Persona = A.ID
        JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
        WHERE B.NomAgencia = '$agenciaU'");
        return datatables()->of($solicitudes)->toJson();
    }

    public function actualizardetalle(Request $request, $id)
    {
        $documento = DB::select('SELECT DocumentoSoporte FROM autorizaciones WHERE ID = ?', [$id]);
        if (!empty($documento)) {
            $nombre_documento = $documento[0]->DocumentoSoporte;

            if ($request->hasFile('Soporte')) {
                $soporte = $request->file('Soporte');
                $dir = 'Storage/files/soporteautorizaciones/';

                // Renombrar el archivo con el nombre obtenido de la base de datos
                $nombre_archivo = $nombre_documento;

                // Mover el archivo con el nuevo nombre
                $soporte->move($dir, $nombre_archivo . '.pdf');
            }
        }

        // Si el archivo se proporcionó y se movió correctamente, actualiza la base de datos
        if (isset($nombre_archivo)) {
            $existingCedula = DB::select('SELECT Cedula FROM autorizaciones WHERE ID = ?', [$id]);
            $cedula = $existingCedula[0]->Cedula;

            $update = DB::table('autorizaciones')
                ->where('ID', $id)
                ->update([
                    'Detalle' => $request->input('Detalle'),
                    'Estado' => 2,
                    'DocumentoSoporte' => $nombre_archivo,
                    'Validacion' => 0,
                    'Aprobacion' => 0,
                    'ObservacionesGer' => null,
                    'Observaciones' => null
                ]);

            // Devuelve un mensaje de éxito si se proporcionó un archivo y se actualizó la base de datos
            return response()->json(['message' => 'Datos recibidos correctamente']);
        } else {
            // Devuelve un mensaje de error si no se proporcionó ningún archivo
            $update = DB::table('autorizaciones')
                ->where('ID', $id)
                ->update([
                    'Detalle' => $request->input('Detalle'),
                    'Estado' => 2,
                    'DocumentoSoporte' => $nombre_documento,
                    'Validacion' => 0,
                    'Aprobacion' => 0,
                    'ObservacionesGer' => null,
                    'Observaciones' => null
                ]);
            return response()->json(['message' => 'Datos recibidos correctamente']);
        }


    }


}
