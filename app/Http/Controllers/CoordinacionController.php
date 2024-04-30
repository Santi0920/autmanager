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
        $user = DB::select("SELECT * FROM concepto_autorizaciones");

        return view('Coordinacion/validarautorizacion', ['user' => $user]);
    }



    public function solicitudes(Request $request)
    {
        $usuarioActual = Auth::user();
        $agenciaU = $usuarioActual->agenciau;

        $agencias = DB::select("SELECT NumAgencia FROM autorizaciones");

        if ($agenciaU = $usuarioActual->agenciau == "Coordinacion 1") {
            $solicitudes = DB::select("SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte,B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, C.Letra, C.No, C.Concepto, C.Areas
            FROM persona A
            JOIN autorizaciones B ON B.ID_Persona = A.ID
            JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
            WHERE B.Solicitud = 1 AND B.NumAgencia IN (34, 36, 37, 38, 40, 41, 87, 93, 96) OR (B.Estado = 6)");
        } else if ($agenciaU = $usuarioActual->agenciau == "Coordinacion 2") {
            $solicitudes = DB::select("
            SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte,B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, C.Letra, C.No, C.Concepto, C.Areas
            FROM persona A
            JOIN autorizaciones B ON B.ID_Persona = A.ID
            JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
            WHERE B.Solicitud = 1 AND B.NumAgencia IN (33, 39, 46, 70, 77, 78, 80, 88, 92, 98) OR (B.Estado = 6)");
        } else if ($agenciaU = $usuarioActual->agenciau == "Coordinacion 3") {
            $solicitudes = DB::select("
            SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte,B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, C.Letra, C.No, C.Concepto, C.Areas
            FROM persona A
            JOIN autorizaciones B ON B.ID_Persona = A.ID
            JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
            WHERE B.Solicitud = 1 AND B.NumAgencia IN (32, 42, 47, 81, 82, 83, 85, 90, 94) OR (B.Estado = 6)");
        } else if ($agenciaU = $usuarioActual->agenciau == "Coordinacion 4") {
            $solicitudes = DB::select("
            SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte,B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, C.Letra, C.No, C.Concepto, C.Areas
            FROM persona A
            JOIN autorizaciones B ON B.ID_Persona = A.ID
            JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
            WHERE B.Solicitud = 1 AND B.NumAgencia IN (44, 45, 48, 49, 74, 75, 84, 89, 91, 95, 97) OR (B.Estado = 6)");
        } else if ($agenciaU = $usuarioActual->agenciau == "Coordinacion 5") {
            $solicitudes = DB::select("
            SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte,B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, C.Letra, C.No, C.Concepto, C.Areas
            FROM persona A
            JOIN autorizaciones B ON B.ID_Persona = A.ID
            JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
            WHERE B.Solicitud = 1 AND B.NumAgencia IN (13, 30, 31, 43, 68, 73, 76, 86) OR (B.Estado = 6)");
        }
        return datatables()->of($solicitudes)->toJson();
    }


    public function solicitarAutorizacion(Request $request)
    {

        $existingPerson = DB::select('SELECT * FROM persona WHERE Cedula = ?', [$request->cedula]);

        $tipoautorizacion = $request->tautorizacion;
        $cedula = $request->cedula;
        $attempts = 0;
        $maxAttempts = 3; // INTENTOS MÁXIMOS
        $retryDelay = 500; // Milisegundos
        $url = env('URL_SERVER_API');
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

        //VALIDACIONES SI NO CUMPLE CON LAS CONDICIONES
        if (($tipoautorizacion == '11A' || $tipoautorizacion == '11D') && empty($existingPerson)) {
                return back()->with("incorrecto", "¡PERSONA NO EXISTE EN DATACRÉDITO!");
        }else if (($tipoautorizacion != '10A' && $tipoautorizacion != '11A') && $estado == '422'){
                return back()->with("incorrecto", "¡PERSONA NO EXISTE EN AS400!");
        }else {
            //si el estado da 200 en la api que me traiga NOMBRE Y CUENTA
            if ($estado == '200') {
                $nombre = $data['asociado']['NOMBRES'];
                $cuenta = $data['asociado']['CUENTA'];
            }else{
                $cuenta = 'N/A';
                $nombre = null;
            }




            if (in_array($tipoautorizacion, ['1500A','1500B', '2150A', '2150B', '2250A', '2250B', '2350A' , '2350B', '2300A', '2300B', '2300C'])) {
                // Número y letra del concepto
                $No = substr($tipoautorizacion, 0, 4);
                $letra = substr($tipoautorizacion, 4, 1); // Cambiado a 1 para obtener solo una letra
            } else {
                // Número y letra del concepto
                $No = substr($tipoautorizacion, 0, 2);
                $letra = substr($tipoautorizacion, 2, 1); // Cambiado a 1 para obtener solo una letra
            }



            //detalle input
            $detalle = $request->detalle;

            //fecha de la solicitud del director
            $fechadeSolicitud = Carbon::now('America/Bogota');
            Carbon::setLocale('es');
            $fechaStringfechadeSolicitud = $fechadeSolicitud->translatedFormat('F d Y-H:i:s');
            //TRAER INFORMACION DE LA AGENCIA Y EL ROL, SI ES DIRECTOR
            $usuarioActual = Auth::user();
            $agenciaU = $usuarioActual->agenciau;
            $nombreU = $usuarioActual->name;
            $existingID = DB::select('SELECT ID, Nombre, Apellidos FROM Persona WHERE Cedula = ?', [$request->cedula]);
            //si el concepto es:
            if ($tipoautorizacion == '11A' || $tipoautorizacion == '11D') {
                $idpersona = $existingID[0]->ID;
            } else {
                $idpersona = 7323;
            }

            //concepto traer el id
            $existingConcepto = DB::select('SELECT ID FROM concepto_autorizaciones WHERE No = ? AND Letra = ?', [$No, $letra]);

            $idconcepto = $existingConcepto[0]->ID;


            //SI EL CONCEPTO ES CRUCES, ME SOLICITA LA CONVENCION, SINO NO
            if ($tipoautorizacion == '19B') {
                $convencion = $request->convencion;
            } else {
                $convencion = null;
            }

            //en caso tal de que sea 11A me trae el nombre que existe en datacredito porque no tiene cuenta, es decir no existe en as400
            if($tipoautorizacion == '11A'){
                $nombres = $existingID[0]->Nombre;
                $apellidos = $existingID[0]->Apellidos;
                $nombre = $nombres . ' '.$apellidos;
            }

            //permisos
            if($tipoautorizacion == '10A' && !empty($existingID)){
                $nombres = $existingID[0]->Nombre;
                $apellidos = $existingID[0]->Apellidos;
                $nombre = $nombres . ' '.$apellidos;
            }


            //para ver el retiro y validar si es >90
            if ($tipoautorizacion == '11B') {
                $cuenta = $request->Cuenta;
                $attempts = 0;
                $maxAttempts = 3; // INTENTOS MÁXIMOS
                $retryDelay = 500; // Milisegundos
                $url = env('URL_SERVER_API');
                do {
                    try {
                        $response = Http::get($url . 'retiro/' . $cuenta);
                        $data = $response->json();

                      // Si llegamos aquí, la solicitud fue exitosa, podemos salir del bucle.
                        break;
                    } catch (\Exception $e) {
                        $attempts++;
                        usleep($retryDelay * 1000);
                    }
                } while ($attempts < $maxAttempts);

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
                                return back()->with("incorrecto", "No necesita autorización, tiene ".$dias_restantes." dias asociado.!");
                        }
                    }else{
                        return back()->with("incorrecto","No aplica porque aun está vinculado a COOPSERP.");
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
                'NumAgencia' => null,
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
            ]);





            return back()->with("correcto", "<span class='fs-4'>La autorización No. <span class='badge bg-primary fw-bold'>" . $id_insertado . "</span> está en trámite.</span>");




        }


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
        }
        $fechadeSolicitud = Carbon::now('America/Bogota');

        Carbon::setLocale('es');
        $fechaStringfechadeSolicitud = $fechadeSolicitud->translatedFormat('F d Y-H:i:s');

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
}
