<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
class UsuarioController extends Controller
{
    //ENVIAR DATOS A LA VISTA, PARA CARGAR SELECTS DINAMICAMENTE
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
        $convencion = DB::select("SELECT * FROM convenciones ORDER BY ID ASC");

        return view('Usuario/solicitudes', ['grupos' => $grupos, 'user' => $user, 'convencion' => $convencion]);
    }

    //LISTO
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
        $agenciaU = session('agenciau');
        $nombreU = session('name');
        $rol = session('rol');




        //concepto traer el id
        $existingConcepto = DB::select('SELECT ID FROM concepto_autorizaciones WHERE ID = ?', [$tipoautorizacion]);
        $idconcepto = $existingConcepto[0]->ID;



        //si es igual a director
        $rol = session('rol');
        if($rol == 'Consultante'){
            //traer el numero de agencia PARA INSERTARLO
            $existeAgencia = DB::select('SELECT * FROM agencias WHERE NameAgencia = ?', [$agenciaU]);
            $numAgencia = $existeAgencia[0]->NumAgencia;
        }else if($rol == 'Coordinacion'){

            if(session('agenciau') == 'Coordinacion 1'){
                $numAgencia = 'C1';
            }else if(session('agenciau') == 'Coordinacion 2'){
                $numAgencia = 'C2';
            }else if(session('agenciau') == 'Coordinacion 3'){
                $numAgencia = 'C3';
            }else if(session('agenciau') == 'Coordinacion 4'){
                $numAgencia = 'C4';
            }else if(session('agenciau') == 'Coordinacion 5'){
                $numAgencia = 'C5';
            }
        }else if ($rol == 'Jefatura') {
            $numAgencia = $agenciaU;
        }
        //DISPOSICIONES
        if($tipoautorizacion == '41'){

            $cuenta = $request->cuenta;
            $existingPerson = DB::select('SELECT * FROM persona WHERE Cedula = ?', [$cedula]);

            if(empty($existingPerson)){
                $nombre = $request->nombre;

            }else{
                //traer el ID
                $existingID = DB::select('SELECT ID, Nombre, Apellidos FROM persona WHERE Cedula = ?', [$cedula]);
                $idpersona = $existingID[0]->ID;
                $nombres = $existingID[0]->Nombre;
                $apellidos = $existingID[0]->Apellidos;
                $nombre = $nombres . ' '.$apellidos;

            }

            $convencion = $request->convencion;

            //< 1 AÑO
        }else if($tipoautorizacion == '22'){
            //NOMBRE EMPRESA
            $nombre = "COOPSERP";
            $cedula = "805.004.034";
            $cuenta = 9;
            $idpersona = 14920;
        }else{
            $cuenta = $request->cuenta;
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
                    $nombre = $request->nombre;
                }else{
                    //traer el ID
                    $existingID = DB::select('SELECT ID, Nombre, Apellidos FROM persona WHERE Cedula = ?', [$cedula]);
                    $idpersona = $existingID[0]->ID;

                    $nombres = $existingID[0]->Nombre;
                    $apellidos = $existingID[0]->Apellidos;
                    $nombre = $nombres . ' '.$apellidos;
                }
            }


            // //Y LA CEDULA LA ESTA TOMANDO COMO NIT
            // $cuenta = null;

            // $attempts = 0;
            // $maxAttempts = 3; // INTENTOS MÁXIMOS
            // $retryDelay = 500; // Milisegundos

            // do {
            //     try {
            //         $response = Http::get($url . 'nombre/' . $cedula);
            //         $data = $response->json();
            //       // Si llegamos aquí, la solicitud fue exitosa, podemos salir del bucle.
            //         break;
            //     } catch (\Exception $e) {
            //         $attempts++;
            //         usleep($retryDelay * 1000);
            //     }
            // } while ($attempts < $maxAttempts);
            // if(!empty($data['status'])){
            //     if ($data['status'] == '200') {
            //         $cuenta = $data['asociado']['CUENTA'];
            //     }
            // }else{
            //     $cuenta = null;
            // }

        }

        $cedulaSinPuntos = str_replace('.', '', $cedula);
        $proveedores = DB::table('proveedor')
        ->where('NIT', 'LIKE', '%' . $cedulaSinPuntos . '%')
        ->get();
        if(!$proveedores->isEmpty()){
            $idpersona = $proveedores[0]->ID_Persona;
            $nombre = $proveedores[0]->RazonSocial;
        }

        $consultabloqueado = DB::select('SELECT ID, (SELECT COUNT(*) FROM historialestado WHERE Bloqueado = 1 AND NomArea = ?) as total FROM historialestado WHERE Bloqueado = 1 AND NomArea = ?', [$agenciaU, $agenciaU]);

        if(!empty($consultabloqueado)){
            if($consultabloqueado[0]->total > 0){
                return back()->with("incorrecto2", "<span class='fs-4'>La autorización No. <span class='badge bg-primary fw-bold'>".$consultabloqueado[0]->ID."</span> se encuentra <span class='text-danger fw-bold'>BLOQUEADA</span>. Por favor contactar con <span class='fw-bold'>Dirección General</span>.</span>");
            }
        }
        
        if($rol == "Coordinacion"){
            $estado = "REMITIDO";
        }else{
            $estado = "TRÁMITE";
        }
        //insercion
        $id_insertado = DB::table('autorizaciones_2')->insertGetId([
            'Cedula' => $cedula,
            'CuentaAsociado' => $cuenta,
            'NombrePersona' => $nombre,
            'Detalle' => $detalle,
            'Convencion' => $convencion,
            'ID_Persona' => $idpersona,
            'ID_Concepto' => $idconcepto,
            'ID_User' => session('id')
        ]);

        $id_insertadohistorial = DB::table('historialestado')->insertGetId([
            'NumArea' => $numAgencia,
            'NomArea' => $agenciaU,
            'Observaciones' => null,
            'Estado' => $estado,
            'Nombre' => session('name'),
            'Fecha' => $fechadeSolicitud,
            'FechaString' => $fechaStringfechadeSolicitud,
            'ID_Autorizacion' => $id_insertado,
        ]);


        // PROCESO PARA SUBIR ARCHIVO SOPORTE********
        // Verificar si se subió un archivo
        if (!$request->hasFile('SoporteScore')) {
            return back()->withErrors(['message' => 'No se subió ningún archivo.']);
        }

        $file = $request->file('SoporteScore');
        $filename = $file->getClientOriginalName();

        // Verificar si el archivo es PDF
        if ($file->getClientOriginalExtension() != 'pdf' && $file->getClientOriginalExtension() != 'PDF') {
            return back()->withErrors(['message' => 'Solo se pueden subir archivos PDF.']);
        }

        $newFilename = 'Soporte-' . $id_insertado.'.pdf';


        DB::table('historialestado')
        ->where('ID', $id_insertadohistorial)
        ->update([
            'DocumentoSoporte' => $newFilename,
        ]);



        // Subir el archivo
        $dir = 'Storage/files/soporteautorizaciones/';
        if (!$file->move($dir, $newFilename)) {
            return back()->withErrors(['message' => 'Error al subir el archivo.']);
        }


        //AUDITORIA
        $nombreauditoria = session('name');
        $rol = session('rol');
        date_default_timezone_set('America/Bogota');
        $fechaHoraActual = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        $agencia = session('agenciau');
        $login = DB::insert("INSERT INTO auditoria (Hora_login, Usuario_nombre, Usuario_Rol, AgenciaU, Acción_realizada, Hora_Accion, Cedula_Registrada, cerro_sesion, IP) VALUES (?, ?, ?, ?, 'CreoAutorizacionDirector', ?, ?, ?, ?)", [
            null,
            $nombreauditoria,
            $rol,
            $agencia,
            $fechaHoraActual,
            $cedula,
            null,
            $ip
        ]);


        return back()->with("correcto", "<span class='fs-4'>La autorización No. <span class='badge bg-primary fw-bold'>" . $id_insertado . "</span> está en trámite.</span>");



    }

    public function solicitudes(Request $request)
    {
        if (!session('email')) {
            return redirect()->route('login');
        }

        $agenciaU = session('agenciau');
        $rol = session('rol');
        if($rol == "Coordinacion"){
            $id = session('id');

            // Obtener coordinaciones del usuario
            $coordinaciones = DB::table('users')
                ->select('agenciau', 'agencias_id')
                ->where('agenciau', $agenciaU)
                ->where('id', $id)
                ->first();

            $agenciasIdArray = json_decode($coordinaciones->agencias_id ?? '[]', true);
            $numero = preg_replace('/[^0-9]/', '', $coordinaciones->agenciau);
            $coordinacionVariable = session('agenciau') == "Coordinacion $numero" ? "C" . $numero : null;

            // Query base con columnas originales
            $query = DB::table('autorizaciones_2 AS B')
                ->join('persona AS A', 'A.ID', '=', 'B.ID_Persona')
                ->join('concepto_autorizaciones AS C', 'B.ID_Concepto', '=', 'C.ID')
                ->join('documentosintesis AS D', 'A.ID', '=', 'D.ID_Persona')
                ->whereExists(function ($q) {
                    $q->select(DB::raw(1))
                    ->from('historialestado AS H')
                    ->whereRaw('H.ID_Autorizacion = B.ID');
                })
                ->select([
                    'A.ID AS IDPersona',
                    'A.Score',
                    'A.CuentaAsociada',
                    'A.Nombre',
                    'A.Apellidos',
                    'B.ID AS IDAutorizacion',
                    'B.Convencion',
                    'B.Cedula',
                    'B.CuentaAsociado',
                    'B.NombrePersona',
                    'B.Detalle',
                    'B.ID_User',
                    'B.ID_Concepto',
                    'C.Letra',
                    'C.No',
                    'C.Concepto',
                    'C.Areas',
                    'D.FechaInsercion'
                ])
                ->distinct();

            // // Filtros de estados y validación, adaptados
            // $query->where(function($q) {
            //     $q->whereIn('H.Estado', ["CORREGIR","TRÁMITE", "REMITIDO"])
            //     ->orWhere('H.Bloqueado', 1);
            // });

            // // Filtros por agencias y coordinación
            // if (!empty($agenciasIdArray) && $coordinacionVariable) {
            //     $query->where(function ($q) use ($agenciasIdArray, $coordinacionVariable) {
            //         $q->whereIn('B.NumAgencia', $agenciasIdArray)
            //         ->orWhere('B.NumAgencia', $coordinacionVariable);
            //     });
            // } elseif ($coordinacionVariable) {
            //     $query->where('B.NumAgencia', $coordinacionVariable);
            // }

            $autorizaciones = $query->get();



        }else{
            // 🔹 Traer solo las autorizaciones relacionadas con la agencia
            $autorizaciones = DB::table('autorizaciones_2 AS B')
                ->join('persona AS A', 'A.ID', '=', 'B.ID_Persona')
                ->join('concepto_autorizaciones AS C', 'B.ID_Concepto', '=', 'C.ID')
                ->join('documentosintesis AS D', 'A.ID', '=', 'D.ID_Persona')
                ->whereExists(function ($query) use ($agenciaU) {
                    $query->select(DB::raw(1))
                        ->from('historialestado AS H')
                        ->whereRaw('H.ID_Autorizacion = B.ID');
                })
                ->where('B.ID_User', '=', session('id'))
                ->select([
                    'A.ID AS IDPersona',
                    'A.Score',
                    'A.CuentaAsociada',
                    'A.Nombre',
                    'A.Apellidos',
                    'B.ID AS IDAutorizacion',
                    'B.Convencion',
                    'B.Cedula',
                    'B.CuentaAsociado',
                    'B.NombrePersona',
                    'B.Detalle',
                    'B.ID_User',
                    'B.ID_Concepto',
                    'C.Letra',
                    'C.No',
                    'C.Concepto',
                    'C.Areas',
                    'D.FechaInsercion'
                ])
                ->distinct()
                ->get();
        }
            // 🔹 Agregar historial completo + fecha del primer estado
            foreach ($autorizaciones as $aut) {
                $historial = DB::table('historialestado')
                    ->where('ID_Autorizacion', $aut->IDAutorizacion)
                    ->orderBy('ID', 'asc') // 🔹 Asegura que el primer registro sea el más antiguo realmente insertado
                    ->get();

                $aut->historial = $historial;

                // 🔹 Si hay historial, guardamos la primera fecha (que nunca cambiará)
                if ($historial->isNotEmpty()) {
                    $primerEstado = $historial->first();
                    $ultimoEstado = $historial->last();
                    $aut->Fecha = $primerEstado->Fecha;
                    $aut->FechaStringEstado = $primerEstado->FechaString;
                    $aut->Usuario = $primerEstado->Nombre;
                    $aut->NumArea = $primerEstado->NumArea;
                    $aut->NomArea = $primerEstado->NomArea;
                    $aut->PrimerEstado = $primerEstado->Estado;
                    $aut->UltimoEstado = $ultimoEstado->Estado;
                } else {
                    $aut->Fecha = null;
                    $aut->FechaStringEstado = null;
                    $aut->Usuario = null;
                    $aut->NumArea = null;
                    $aut->NomArea = null;
                    $aut->Estado = null;
                }
            }


        return response()->json(['data' => $autorizaciones]);
    }


    public function actualizardetalle(Request $request, $id)
    {



        $cedula = $request->Cedulamodal;

        $documentos = DB::select('SELECT ID, DocumentoSoporte, NumArea FROM historialestado WHERE ID_Autorizacion = ?', [$id]);
        $inputName = 'Soporte_' . $id;
        Log::info($documentos);

        // Encontrar el último documento con nombre y actualizar su Estado
        $ultimoDocumento = null;
        foreach ($documentos as $doc) {
            if ($doc->DocumentoSoporte) {
                $ultimoDocumento = $doc; // siempre queda el último que tiene documento
            }
        }

        if ($ultimoDocumento) {
            DB::table('historialestado')
                ->where('ID', $ultimoDocumento->ID)
                ->update(['Estado' => 'DONE']);
        }

        if ($request->hasFile($inputName)) {

            $file = $request->file($inputName);

            // Buscar las versiones existentes
            $versiones = [];
            foreach ($documentos as $doc) {
                if ($doc->DocumentoSoporte) {
                    if (preg_match('/Soporte-' . $id . '(?:\.(\d+))?\.pdf$/', $doc->DocumentoSoporte, $matches)) {
                        $versiones[] = isset($matches[1]) ? (int)$matches[1] : 0;
                    }
                }
            }

            // Determinar la siguiente versión
            $siguienteVersion = !empty($versiones) ? max($versiones) + 1 : 1;

            // Crear nombre del archivo
            $filename = 'Soporte-' . $id . '.' . $siguienteVersion . '.' . $file->getClientOriginalExtension();

            Log::info('Nombre archivo: ' . $filename . ' | Version: ' . $siguienteVersion);

            // Mover archivo
            $file->move(public_path('Storage/files/soporteautorizaciones'), $filename);
        }


        $tipoautorizacion = $request->CodigoAutorizacion;
        $convencion = null;
        $cuenta = null;
        $idpersona = 7323;
        $url = "http://190.66.10.150:10100/conexion_s400/api/";



        //concepto traer el id
        $existingConcepto = DB::select('SELECT ID FROM concepto_autorizaciones WHERE ID = ?', [$tipoautorizacion]);
        $idconcepto = $existingConcepto[0]->ID;
        
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


            $cuenta = $request->Cuentamodal;

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

        if (isset($filename)) {
            // $existingCedula = DB::select('SELECT Cedula FROM autorizaciones WHERE ID = ?', [$id]);
            // $cedula = $existingCedula[0]->Cedula;
            
            DB::table('autorizaciones_2')
            ->where('ID', $id)
            ->update([
                'Cedula' => $cedula,
                'CuentaAsociado' => $cuenta,
                'NombrePersona' => $nombre,
                'Detalle' => $request->input('Detalle'),
                'Convencion' => $convencion,
                'ID_Persona' => $idpersona,
                'ID_Concepto' => $idconcepto,
                'ID_User' => session('id')
            ]);

            $id_insertadohistorial = DB::table('historialestado')
            ->insertGetId([
                'NumArea' => $ultimoDocumento->NumArea,
                'NomArea' => session('name'),
                'Estado' => 'TRÁMITE',
                'Nombre' => session('name'),
                'Fecha' => $fechadeSolicitud,
                'DocumentoSoporte' => $filename,
                'FechaString' => $fechaStringfechadeSolicitud,
                'ID_Autorizacion' => $id
            ]);

            // Devuelve un mensaje de éxito si se proporcionó un archivo y se actualizó la base de datos
            return response()->json(['message' => 'Datos recibidos correctamente']);
        }else{
            DB::table('autorizaciones_2')
            ->where('ID', $id)
            ->update([
                'Cedula' => $cedula,
                'CuentaAsociado' => $cuenta,
                'NombrePersona' => $nombre,
                'Detalle' => $request->input('Detalle'),
                'Convencion' => $convencion,
                'ID_Persona' => $idpersona,
                'ID_Concepto' => $idconcepto,
                'ID_User' => session('id')
            ]);

            $id_insertadohistorial = DB::table('historialestado')
            ->insertGetId([
                'NumArea' => $ultimoDocumento->NumArea,
                'NomArea' => session('name'),
                'Estado' => 'TRÁMITE',
                'Nombre' => session('name'),
                'Fecha' => $fechadeSolicitud,
                'DocumentoSoporte' => $ultimoDocumento->DocumentoSoporte,
                'FechaString' => $fechaStringfechadeSolicitud,
                'ID_Autorizacion' => $id
            ]);
            return response()->json(['message' => 'Datos recibidos correctamente']);
        }


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
        WHERE B.Estado = 4 AND B.Aprobacion = 1 AND B.NomAgencia = '$agenciaU'");
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
        WHERE (B.Estado = 5 OR B.Estado = 0) AND B.NomAgencia = '$agenciaU'");
        return datatables()->of($solicitudes)->toJson();
    }

    public function bloqueados(Request $request)
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
        WHERE B.Bloqueado = 1 AND B.NomAgencia = '$agenciaU'");
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

    public function standby(Request $request)
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
        WHERE B.Estado = 8 AND B.NomAgencia = '$agenciaU'");
        return datatables()->of($solicitudes)->toJson();
    }





    public function buscarautorizacion(Request $request){
        {
            $id = $request->idautorizacion;
            $autorizacion = DB::table('autorizaciones')
                ->join('persona', 'autorizaciones.ID_Persona', '=', 'persona.ID')
                ->join('concepto_autorizaciones', 'autorizaciones.ID_Concepto', '=', 'concepto_autorizaciones.ID')
                ->join('documentosintesis', 'persona.ID', '=', 'documentosintesis.ID_Persona')
                ->select('autorizaciones.*', 'autorizaciones.Cedula as CedulaAutorizacion', 'autorizaciones.Estado as EstadoAutorizacion', 'persona.Cedula as CedulaPersona', 'persona.*' , 'documentosintesis.*', 'concepto_autorizaciones.*', 'autorizaciones.Observaciones as Observaciones')
                ->where('autorizaciones.ID', $id)
                ->first();
            if(!empty($autorizacion)){
                $fechaInsercion = Carbon::parse($autorizacion->FechaInsercion);
                $fechaActual = Carbon::now();
                $diferenciaDias = $fechaActual->diffInDays($fechaInsercion);



                // Definir el estado según la diferencia en días
                if($autorizacion->FechaInsercion == null){
                    $estado = '<span class="fs-2">⚪⚪⚪</span>';
                }
                else if ($diferenciaDias > 179) {
                    $estado = '<span class="fs-2">⚪⚪🔴</span>';
                } elseif ($diferenciaDias > 169) {
                    $estado = '<span class="fs-2">⚪🟡⚪</span>';
                } else {
                    $estado = '<span class="fs-2">🟢⚪⚪</span>';
                }
            }

            //AUDITORIA

            $nombreauditoria = session('name');
            $rol = session('rol');
            date_default_timezone_set('America/Bogota');
            $fechaHoraActual = date('Y-m-d H:i:s');
            $ip = $_SERVER['REMOTE_ADDR'];
            $agencia = session('agenciau');
            $login = DB::insert("INSERT INTO auditoria (Hora_login, Usuario_nombre, Usuario_Rol, AgenciaU, Acción_realizada, Hora_Accion, Cedula_Registrada, cerro_sesion, IP) VALUES (?, ?, ?, ?, 'BuscoAutorizacionDirector', ?, ?, ?, ?)", [
                null,
                $nombreauditoria,
                $rol,
                $agencia,
                $fechaHoraActual,
                $id,
                null,
                $ip
            ]);

            if(empty($autorizacion)){
                return back()->with("incorrecto", "Autorización No.$id, NO EXISTE!");
            }else{
                return view('Director/mostrarautorizacion', ['id' => $id,'autorizacion' => $autorizacion, 'estado' => $estado]);
            }
        }
    }


}
