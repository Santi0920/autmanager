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
            $numAgencia = 'Jefatura';
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

        $consultabloqueado = DB::select('SELECT ID, ID_Autorizacion, (SELECT COUNT(*) FROM historialestado WHERE Bloqueado = 1 AND NomArea = ?) as total FROM historialestado WHERE Bloqueado = 1 AND NomArea = ?', [$agenciaU, $agenciaU]);

        if(!empty($consultabloqueado)){
            if($consultabloqueado[0]->total > 0){
                return back()->with("incorrecto2", "<span class='fs-4'>La autorización No. <span class='badge bg-primary fw-bold'>".$consultabloqueado[0]->ID_Autorizacion."</span> se encuentra <span class='text-danger fw-bold'>BLOQUEADA</span>. Por favor contactar con <span class='fw-bold'>Dirección General</span>.</span>");
            }
        }
        
        if($rol == "Coordinacion"){
            $estado = "REMITIDO";
        }else{
            $estado = "TRÁMITE";
        }
        //insercion
        $id_insertado = DB::table('autorizaciones_2')->insertGetId([
        ]);

        $id_insertadohistorial = DB::table('historialestado')->insertGetId([
            'Cedula' => $cedula,
            'CuentaAsociado' => $cuenta,
            'NombrePersona' => $nombre,
            'Detalle' => $detalle,
            'Convencion' => $convencion,
            'ID_Persona' => $idpersona,
            'ID_Concepto' => $idconcepto,
            'ID_User' => session('id'),
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


            $coordinaciones = DB::select("SELECT DISTINCT agenciau, agencias_id FROM users WHERE agenciau = ? AND id = ?", [$agenciaU, $id]);
        
            $agenciasIdArray = json_decode($coordinaciones[0]->agencias_id, true);
            if ($agenciasIdArray === null) {
                $agenciasIdArray = [];
            }
            
            $numero = preg_replace('/[^0-9]/', '', $coordinaciones[0]->agenciau);

            if (session('agenciau') == "Coordinacion $numero") {
                $coordinacionVariable = "C" . $numero;
            }

            if (count($agenciasIdArray) > 0) {
                //APARECEN RECHAZADOS AQUI Y FALTARIA BLOQUEADO
              
                $idsFiltro = array_merge($agenciasIdArray, [$coordinacionVariable]);
            

                $autorizaciones = DB::table('autorizaciones_2 AS B')
                    ->join('historialestado AS H', function ($join) {
                        $join->on('H.ID_Autorizacion', '=', 'B.ID');
                    })
                    ->leftJoin('persona AS A', 'A.ID', '=', 'H.ID_Persona')
                    ->leftJoin('concepto_autorizaciones AS C', 'H.ID_Concepto', '=', 'C.ID')
                    ->leftJoin('documentosintesis AS D', 'A.ID', '=', 'D.ID_Persona')
                    ->whereExists(function ($sub) use ($idsFiltro) {
                        $sub->select(DB::raw(1))
                            ->from('historialestado AS H2')
                            ->whereColumn('H2.ID_Autorizacion', 'B.ID')
                            ->whereIn('H2.NumArea', $idsFiltro);
                    })
                    ->whereRaw('H.ID = (SELECT MAX(H3.ID) FROM historialestado AS H3 WHERE H3.ID_Autorizacion = B.ID)')
                    ->where('H.Estado', '!=', 'APROBADO')
                    ->where('H.Estado', '!=', 'STAND BY')
                    ->select([
                        'A.ID AS IDPersona',
                        'A.Score',
                        'A.CuentaAsociada',
                        'A.Nombre',
                        'A.Apellidos',
                        'B.ID AS IDAutorizacion',
                        'H.Convencion',
                        'H.Cedula',
                        'H.CuentaAsociado',
                        'H.NombrePersona',
                        'H.Detalle',
                        'H.ID_User',
                        'H.ID_Concepto',
                        'C.Letra',
                        'C.No',
                        'C.Concepto',
                        'C.Areas',
                        'D.FechaInsercion'
                    ])
                    ->distinct()
                    ->get();



            }



        }elseif($rol == "Gerencia"){
           
            $autorizaciones = DB::table('autorizaciones_2 AS B')
                ->join(DB::raw('(SELECT 
                                    ID_Autorizacion, 
                                    MAX(ID) AS UltimoHistorialID
                                FROM historialestado
                                GROUP BY ID_Autorizacion
                                ) AS HMAX'), 'HMAX.ID_Autorizacion', '=', 'B.ID')
                ->join('historialestado AS H', 'H.ID', '=', 'HMAX.UltimoHistorialID')
                ->leftJoin('persona AS A', 'A.ID', '=', 'H.ID_Persona')
                ->leftJoin('concepto_autorizaciones AS C', 'H.ID_Concepto', '=', 'C.ID')
                ->leftJoin('documentosintesis AS D', 'A.ID', '=', 'D.ID_Persona')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('historialestado AS H2')
                        ->whereRaw('H2.ID_Autorizacion = B.ID')
                        ->where(function ($sub) {
                            $sub->where('H2.Estado', 'VALIDADO')
                                ->orWhere('H2.Estado', 'REMITIDO');
                        });
                })
                ->where('H.Bloqueado', '!=', '1')
                ->select([
                    'A.ID AS IDPersona',
                    'A.Score',
                    'A.CuentaAsociada',
                    'A.Nombre',
                    'A.Apellidos',
                    'B.ID AS IDAutorizacion',
                    'H.Convencion',
                    'H.Cedula',
                    'H.CuentaAsociado',
                    'H.NombrePersona',
                    'H.Detalle',
                    'H.ID_User',
                    'H.ID_Concepto',
                    'C.Letra',
                    'C.No',
                    'C.Concepto',
                    'C.Areas',
                    'D.FechaInsercion'
                ])
                ->get();

        }else{
            // 🔹 Traer solo las autorizaciones relacionadas con la agencia
            $autorizaciones = DB::table('autorizaciones_2 AS B')
                ->join(DB::raw('(
                    SELECT H1.*
                    FROM historialestado AS H1
                    INNER JOIN (
                        SELECT ID_Autorizacion, MAX(ID) AS MaxID
                        FROM historialestado
                        WHERE NomArea = "' . $agenciaU . '"
                        GROUP BY ID_Autorizacion
                    ) AS Ultimo
                    ON H1.ID = Ultimo.MaxID
                ) AS H'), 'H.ID_Autorizacion', '=', 'B.ID')
                ->leftJoin('persona AS A', 'A.ID', '=', 'H.ID_Persona')
                ->leftJoin('concepto_autorizaciones AS C', 'H.ID_Concepto', '=', 'C.ID')
                ->leftJoin('documentosintesis AS D', 'A.ID', '=', 'D.ID_Persona')
                // Excluir autorizaciones cuyo último estado global sea "APROBADO" o "STAND BY"
                ->whereRaw('LOWER(TRIM((
                    SELECT H3.Estado
                    FROM historialestado AS H3
                    WHERE H3.ID_Autorizacion = B.ID
                    ORDER BY H3.ID DESC
                    LIMIT 1
                ))) NOT IN ("aprobado", "stand by")')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('historialestado AS H2')
                        ->whereRaw('H2.ID_Autorizacion = B.ID');
                })
                ->select([
                    'A.ID AS IDPersona',
                    'A.Score',
                    'A.CuentaAsociada',
                    'A.Nombre',
                    'A.Apellidos',
                    'B.ID AS IDAutorizacion',
                    'H.Convencion',
                    'H.Cedula',
                    'H.CuentaAsociado',
                    'H.NombrePersona',
                    'H.Detalle',
                    'H.Estado',
                    'H.ID_User',
                    'H.ID_Concepto',
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
                $historial = DB::table('autorizaciones_2 AS B')
                    ->join('historialestado AS H', 'H.ID_Autorizacion', '=', 'B.ID')
                    ->leftJoin('persona AS A', 'A.ID', '=', 'H.ID_Persona')
                    ->leftJoin('concepto_autorizaciones AS C', 'H.ID_Concepto', '=', 'C.ID')
                    ->leftJoin('documentosintesis AS D', 'A.ID', '=', 'D.ID_Persona')
                    ->where('H.ID_Autorizacion', $aut->IDAutorizacion)
                    ->select([
                        'H.*',
                        'A.Score',
                        'D.FechaInsercion',
                        'C.Concepto'
                    ])
                    ->orderBy('H.ID', 'asc') // historial en orden cronológico
                    ->get();

                // Adjuntamos el historial completo al objeto
                $aut->historial = $historial;
            
                // 🔹 Primer historial (más antiguo)
                $primer = $historial->first();
                if ($primer) {
                    $aut->Concepto = $primer->Concepto;
                    $aut->Score = $primer->Score;
                    $aut->FechaInsercion = $primer->FechaInsercion;
                    $aut->Fecha = $primer->Fecha;
                    $aut->FechaStringEstado = $primer->FechaString;
                    $aut->Usuario = $primer->Nombre;
                    $aut->NumArea = $primer->NumArea;
                    $aut->NomArea = $primer->NomArea;
                    $aut->PrimerEstado = $primer->Estado;
                } else {
                    $aut->Score = null;
                    $aut->FechaInsercion = null;
                    $aut->Fecha = null;
                    $aut->FechaStringEstado = null;
                    $aut->Usuario = null;
                    $aut->NumArea = null;
                    $aut->NomArea = null;
                    $aut->PrimerEstado = null;
                }
                // 🔹 Inicializamos en null
                $ultimoConceptoNombre = null;

                // 🔹 Recorremos el historial desde el final
                for ($i = $historial->count() - 1; $i >= 0; $i--) {
                    $idConcepto = $historial[$i]->ID_Concepto;

                    if (!is_null($idConcepto)) {
                        // 🔹 Consultamos el nombre del concepto
                        $concepto = DB::table('concepto_autorizaciones')
                            ->where('ID', $idConcepto)
                            ->select('Concepto') // o el campo que tenga el nombre
                            ->first();

                        if ($concepto) {
                            $ultimoConceptoNombre = $concepto->Concepto;
                        }
                        break; // salimos apenas encontramos el primer concepto válido
                    }
                }
                // 🔹 Asignamos al objeto
                $aut->UltimoConcepto = $ultimoConceptoNombre;
                // 🔹 Último historial (más reciente)
                $ultimo = $historial->last();
                $aut->UltimoEstado = $ultimo ? $ultimo->Estado : null;
                $ultimoRemitidoCorregir = DB::table('historialestado')
                    ->where('ID_Autorizacion', $aut->IDAutorizacion) // 👈 filtra solo la autorización actual
                    ->where('Estado', 'REMITIDOCORREGIR')
                    ->orderByDesc('ID')
                    ->first();

                $aut->EstadoRemitidoBoton = $ultimoRemitidoCorregir
                    ? $ultimoRemitidoCorregir->Estado
                    : null;

                $ultimaCoord = $historial
                    ->filter(function ($h) {
                        return stripos($h->NomArea ?? '', 'Coordinacion') === 0;
                    })
                    ->last();

                $aut->UltimaFechaCoordinacion = $ultimaCoord ? $ultimaCoord->FechaString : null;

                $ultimaDoneTramite = $historial
                    ->filter(function ($h) {
                        $estado = strtoupper(trim($h->Estado ?? ''));
                        return in_array($estado, ['DONE', 'TRÁMITE']);
                    })
                    ->last();

                $aut->UltimaFechaDoneTramite = $ultimaDoneTramite ? $ultimaDoneTramite->FechaString : null;

                $ultimaCoordinacion = $historial
                    ->filter(function ($h) {
                        return stripos($h->NomArea ?? '', 'Coordinacion') === 0;
                    })
                    ->last();

                $aut->UltimaAreaCoordinacion = $ultimaCoordinacion ? $ultimaCoordinacion->NumArea : null;
       



            }


        return response()->json(['data' => $autorizaciones]);
    }


    public function actualizardetalle(Request $request, $id)
    {

        $tipovalidacion = $request->Estado;

        //fecha de la solicitud de la jefatura corregida
        $fechadeSolicitud = Carbon::now('America/Bogota');
        Carbon::setLocale('es');
        $fechaStringfechadeSolicitud = $fechadeSolicitud->translatedFormat('F d Y-H:i:s');
        if(session('rol') == "Gerencia"){


            $ultimoEstado = DB::table('historialestado')
                ->where('ID_Autorizacion', $id)
                ->where(function ($query) {
                    $query->where('Estado', 'TRÁMITE')
                        ->orWhere('Estado', 'REMITIDO')
                        ->orWhere('Estado', 'VALIDADO');
                })
                ->orderByDesc('ID') // o 'Fecha' si ese campo representa el orden cronológico
                ->first();
            $NumArea = 'DR';
            $NomArea = 'DIRECCIÓN GENERAL';

            if ($tipovalidacion == 'CORREGIR') {
                $estado = "TRÁMITE";

                // Buscar el último registro con estado DONE para esa autorización
                $ultimoDone = DB::table('historialestado')
                    ->where('ID_Autorizacion', $id)
                    ->where('Estado', 'DONE')
                    ->orderByDesc('ID')
                    ->first();

                //SI LO ENCUENTRA LO PASA A ESTADO TRAMITE CON EL FIN SE QUE LO CORRIJA
                if ($ultimoDone) {
                    DB::table('historialestado')
                        ->where('ID', $ultimoDone->ID)
                        ->update(['Estado' => $estado, 'Observaciones' => 'NADA']);
                }

                $ultimoRemitido = DB::table('historialestado')
                    ->where('ID_Autorizacion', $id)
                    ->where('Estado', 'REMITIDO')
                    ->orderByDesc('ID')
                    ->first();

                $estado = "REMITIDOCORREGIR";
                if ($ultimoRemitido) {
                    DB::table('historialestado')
                        ->where('ID', $ultimoRemitido->ID)
                        ->update(['Estado' => $estado, 'Observaciones' => null]);
                }

                $ultimoValidado = DB::table('historialestado')
                    ->where('ID_Autorizacion', $id)
                    ->where('Estado', 'VALIDADO')
                    ->orderByDesc('ID')
                    ->first();

                $estado = "VALIDADOCONFIRMADO";

                if ($ultimoValidado) {
                    DB::table('historialestado')
                        ->where('ID', $ultimoValidado->ID)
                        ->update(['Estado' => $estado, 'Observaciones' => null]);
                }

            } else if ($tipovalidacion == 'APROBADO') {
                $estado = "VALIDADOCONFIRMADO";

                if($ultimoEstado->Estado != "REMITIDO"){
                    if ($ultimoEstado) {
                    DB::table('historialestado')
                        ->where('ID', $ultimoEstado->ID)
                        ->update(['Estado' => $estado, 'Observaciones' => null]);
                    }
                }else{
                    if ($ultimoEstado) {
                    $estado = "REMITIDOCONFIRMADO";
                    DB::table('historialestado')
                        ->where('ID', $ultimoEstado->ID)
                        ->update(['Estado' => $estado, 'Observaciones' => null]);
                    }
                }
            } /*bloqueado */else if ($tipovalidacion == "1" || $tipovalidacion == 'STAND BY') {
                $estado = "VALIDADOCONFIRMADO";

                if($ultimoEstado->Estado != "REMITIDO"){
                    if ($ultimoEstado) {
                    DB::table('historialestado')
                        ->where('ID', $ultimoEstado->ID)
                        ->update(['Estado' => $estado, 'Observaciones' => null]);
                    }
                }else if($ultimoEstado->Estado == "REMITIDO"){
                    if ($ultimoEstado) {
                        $estado = "REMITIDOCONFIRMADO";
                        DB::table('historialestado')
                            ->where('ID', $ultimoEstado->ID)
                            ->update(['Estado' => $estado, 'Observaciones' => null]);
                    }
                }
                
                
                $primerHistorial = DB::table('historialestado')
                    ->where('ID_Autorizacion', $id)
                    ->orderBy('ID', 'asc')
                    ->first();

                if ($primerHistorial) {
                    DB::table('historialestado')
                        ->where('ID', $primerHistorial->ID)
                        ->update(['Bloqueado' => 1]);
                }
                
            }else if ($tipovalidacion == 'STAND BY') {
                //FALTA, PRIMERO BLOQUEADO. DESPUES DE STAND BY . PASAMOS AL FILTRADO POR BOTONES. UNA VEZ ESO. INTENTAR GENERAR LAS PETICIONES DESDE EL MISMO DATATABLE. Y LUEGO CONFIGURAR LA C9 GERENCIA Y PASO A CONFIGURAR TODO EL PERFIL DE GERENCIA. LUEGO HACER LO DEL REPORTE


            }else {
                $estado = "DONE";

                if ($ultimoEstado) {
                    DB::table('historialestado')
                        ->where('ID', $ultimoEstado->ID)
                        ->update(['Estado' => $estado, 'Observaciones' => null]);
                }
                $NumArea = 'C9';
                $NomArea = 'Coordinacion 9';
                if($tipovalidacion == "CORREGIRJEFATURA"){
                    $tipovalidacion = 'CORREGIR';
                }else{
                    $tipovalidacion = $tipovalidacion;
                }
            }


            if($tipovalidacion == 1){
                $estado = "BLOQUEADO";
            }else{
                $estado = $tipovalidacion;
            }
            

            $update = DB::table('historialestado')
            ->insert([
                'NumArea' => $NumArea,
                'NomArea' => $NomArea,
                'Observaciones' => $request->Observaciones,
                'Estado' => $estado,
                'Nombre' => session('name'),
                'Fecha' => $fechadeSolicitud,
                'FechaString' => $fechaStringfechadeSolicitud,
                'ID_Autorizacion' => $id
            ]);

            return response()->json(['success' => true]);

        }else{      
            if($tipovalidacion == null || $request->Cedulamodal != null){

                $cedula = $request->Cedulamodal;

                $documentos = DB::select('SELECT ID, DocumentoSoporte, NumArea FROM historialestado WHERE ID_Autorizacion = ?', [$id]);
                $inputName = 'Soporte_' . $id;
                
                // Encontrar el último documento con nombre y actualizar su Estado
                $ultimoDocumento = null;
                foreach ($documentos as $doc) {
                    if ($doc->DocumentoSoporte) {
                        $ultimoDocumento = $doc; // siempre queda el último que tiene documento
                    }
                }
                if(session('rol') == "Coordinacion"){
                    $estado = "REMITIDOCONFIRMADO";
                }else{
                    $estado = "DONE";
                }
                if ($ultimoDocumento) {
                    DB::table('historialestado')
                    ->where('ID', $ultimoDocumento->ID)
                    ->update(['Estado' => $estado]);
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


                // Si el archivo se proporcionó y se movió correctamente, actualiza la base de datos
                $ultimoNumArea = DB::table('historialestado')
                ->where('ID_Autorizacion', $id)
                ->orderByDesc('Fecha')
                ->value('NumArea');


                //para asignarle al actualizar
                if(session('rol') == "Coordinacion"){
                    $estado = "REMITIDO";
                }else{
                    $estado = "TRÁMITE";
                }
                if (isset($filename)) {
                    // $existingCedula = DB::select('SELECT Cedula FROM autorizaciones WHERE ID = ?', [$id]);
                    // $cedula = $existingCedula[0]->Cedula;

                    $id_insertadohistorial = DB::table('historialestado')
                    ->insertGetId([
                        'Cedula' => $cedula,
                        'CuentaAsociado' => $cuenta,
                        'NombrePersona' => $nombre,
                        'Detalle' => $request->input('Detalle'),
                        'Convencion' => $convencion,
                        'ID_Persona' => $idpersona,
                        'ID_Concepto' => $idconcepto,
                        'ID_User' => session('id'),
                        'NumArea' => $ultimoNumArea,
                        'NomArea' => session('agenciau'),
                        'Estado' => $estado,
                        'Observaciones' => null,
                        'Nombre' => session('name'),
                        'Fecha' => $fechadeSolicitud,
                        'DocumentoSoporte' => $filename,
                        'FechaString' => $fechaStringfechadeSolicitud,
                        'ID_Autorizacion' => $id
                    ]);

                    // Devuelve un mensaje de éxito si se proporcionó un archivo y se actualizó la base de datos
                    return response()->json(['message' => 'Datos recibidos correctamente']);
                }else{
                    $id_insertadohistorial = DB::table('historialestado')
                    ->insertGetId([
                        'Cedula' => $cedula,
                        'CuentaAsociado' => $cuenta,
                        'NombrePersona' => $nombre,
                        'Detalle' => $request->input('Detalle'),
                        'Convencion' => $convencion,
                        'ID_Persona' => $idpersona,
                        'ID_Concepto' => $idconcepto,
                        'ID_User' => session('id'),
                        'NumArea' => $ultimoNumArea,
                        'NomArea' => session('agenciau'),
                        'Estado' => $estado,
                        'Nombre' => session('name'),
                        'Observaciones' => null ,
                        'Fecha' => $fechadeSolicitud,
                        'DocumentoSoporte' => $ultimoDocumento->DocumentoSoporte,
                        'FechaString' => $fechaStringfechadeSolicitud,
                        'ID_Autorizacion' => $id
                    ]);
                    return response()->json(['message' => 'Datos recibidos correctamente']);
                }
            }else{

                $nombre = session('name');
                $noCoordinacion = session('agenciau');
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



                $ultimoEstado = DB::table('historialestado')
                    ->where('ID_Autorizacion', $id)
                    ->orderByDesc('ID') // o 'Fecha' si ese campo indica el orden cronológico
                    ->first();

                if ($request->input('Estado') == 'CORREGIR') {
                    $estado = "TRÁMITE";
                    

                    // Buscar el último registro con estado DONE para esa autorización
                    $ultimoDone = DB::table('historialestado')
                        ->where('ID_Autorizacion', $id)
                        ->where('Estado', 'TRÁMITE')
                        ->orderByDesc('ID')
                        ->first();

                    if ($ultimoDone) {
                        DB::table('historialestado')
                            ->where('ID', $ultimoDone->ID)
                            ->update(['Estado' => $estado, 'Observaciones' => 'NADA']);
                    }
                    //aqui unicamente esta sirviendo cuando es aprobado pero para el director
                } else {
                    $estado = "DONE";

                    $ultimoTramite = DB::table('historialestado')
                        ->where('ID_Autorizacion', $id)
                        ->where('Estado', 'TRÁMITE')
                        ->orderByDesc('ID')
                        ->first();

                    if ($ultimoTramite) {
                        DB::table('historialestado')
                            ->where('ID', $ultimoTramite->ID)
                            ->update(['Estado' => $estado, 'Observaciones' => null]);
                    }

                }

                if($tipovalidacion == null){
                    $tipovalidacion = 'REMITIDO';
                }

                $update = DB::table('historialestado')
                    ->insert([
                        'NumArea' => $coordinacion,
                        'NomArea' => $noCoordinacion,
                        'Observaciones' => $request->Observaciones,
                        'Estado' => $tipovalidacion,
                        'Nombre' => $nombre,
                        'Fecha' => $fechadeSolicitud,
                        'FechaString' => $fechaStringfechadeSolicitud,
                        'ID_Autorizacion' => $id
                    ]);



                return response()->json(['success' => true]);
            }
        } 

    }




    public function aprobados(Request $request)
    {
        if (!session('email')) {
            return redirect()->route('login');
        }

        $agenciaU = session('agenciau');
        $rol = session('rol');
        if($rol == "Coordinacion"){
            $id = session('id');


            $coordinaciones = DB::select("SELECT DISTINCT agenciau, agencias_id FROM users WHERE agenciau = ? AND id = ?", [$agenciaU, $id]);
        
            $agenciasIdArray = json_decode($coordinaciones[0]->agencias_id, true);
            if ($agenciasIdArray === null) {
                $agenciasIdArray = [];
            }
            
            $numero = preg_replace('/[^0-9]/', '', $coordinaciones[0]->agenciau);

            if (session('agenciau') == "Coordinacion $numero") {
                $coordinacionVariable = "C" . $numero;
            }

            if (count($agenciasIdArray) > 0) {
                //APARECEN RECHAZADOS AQUI Y FALTARIA BLOQUEADO
              
                $idsFiltro = array_merge($agenciasIdArray, [$coordinacionVariable]);
            

                $autorizaciones = DB::table('autorizaciones_2 AS B')
                    ->join('historialestado AS H', function ($join) {
                        $join->on('H.ID_Autorizacion', '=', 'B.ID');
                    })
                    ->leftJoin('persona AS A', 'A.ID', '=', 'H.ID_Persona')
                    ->leftJoin('concepto_autorizaciones AS C', 'H.ID_Concepto', '=', 'C.ID')
                    ->leftJoin('documentosintesis AS D', 'A.ID', '=', 'D.ID_Persona')
                    ->whereExists(function ($sub) use ($idsFiltro) {
                        $sub->select(DB::raw(1))
                            ->from('historialestado AS H2')
                            ->whereColumn('H2.ID_Autorizacion', 'B.ID')
                            ->whereIn('H2.NumArea', $idsFiltro);
                    })
                    // ✅ Solo el último estado de cada autorización
                    ->whereRaw('H.ID = (SELECT MAX(H3.ID) FROM historialestado AS H3 WHERE H3.ID_Autorizacion = B.ID)')
                    // ✅ Filtrar únicamente las que están aprobadas
                    ->whereRaw('LOWER(TRIM(H.Estado)) = "aprobado"')
                    // 🚫 Excluir otros estados no deseados (por seguridad)
                    ->whereNotIn('H.Estado', ['STAND BY', 'TRÁMITE', 'REMITIDO', 'REMITIDOCONFIRMADO'])
                    ->select([
                        'A.ID AS IDPersona',
                        'A.Score',
                        'A.CuentaAsociada',
                        'A.Nombre',
                        'A.Apellidos',
                        'B.ID AS IDAutorizacion',
                        'H.Convencion',
                        'H.Cedula',
                        'H.CuentaAsociado',
                        'H.NombrePersona',
                        'H.Detalle',
                        'H.ID_User',
                        'H.ID_Concepto',
                        'C.Letra',
                        'C.No',
                        'C.Concepto',
                        'C.Areas',
                        'D.FechaInsercion'
                    ])
                    ->distinct()
                    ->get();




            }



        }elseif($rol == "Gerencia"){
           
            $autorizaciones = DB::table('autorizaciones_2 AS B')
                ->join(DB::raw('(SELECT 
                                    ID_Autorizacion, 
                                    MAX(ID) AS UltimoHistorialID
                                FROM historialestado
                                GROUP BY ID_Autorizacion
                                ) AS HMAX'), 'HMAX.ID_Autorizacion', '=', 'B.ID')
                ->join('historialestado AS H', 'H.ID', '=', 'HMAX.UltimoHistorialID')
                ->leftJoin('persona AS A', 'A.ID', '=', 'H.ID_Persona')
                ->leftJoin('concepto_autorizaciones AS C', 'H.ID_Concepto', '=', 'C.ID')
                ->leftJoin('documentosintesis AS D', 'A.ID', '=', 'D.ID_Persona')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('historialestado AS H2')
                        ->whereRaw('H2.ID_Autorizacion = B.ID')
                        ->where(function ($sub) {
                            $sub->where('H2.Estado', 'APROBADO');
                        });
                })
                ->where('H.Bloqueado', '!=', '1')
                ->select([
                    'A.ID AS IDPersona',
                    'A.Score',
                    'A.CuentaAsociada',
                    'A.Nombre',
                    'A.Apellidos',
                    'B.ID AS IDAutorizacion',
                    'H.Convencion',
                    'H.Cedula',
                    'H.CuentaAsociado',
                    'H.NombrePersona',
                    'H.Detalle',
                    'H.ID_User',
                    'H.ID_Concepto',
                    'C.Letra',
                    'C.No',
                    'C.Concepto',
                    'C.Areas',
                    'D.FechaInsercion'
                ])
                ->get();

        }else{
            $autorizaciones = DB::table('autorizaciones_2 AS B')
                ->join(DB::raw('(
                    SELECT H1.*
                    FROM historialestado AS H1
                    INNER JOIN (
                        SELECT ID_Autorizacion, MAX(ID) AS MaxID
                        FROM historialestado
                        WHERE NomArea = "' . $agenciaU . '"
                        GROUP BY ID_Autorizacion
                    ) AS Ultimo
                    ON H1.ID = Ultimo.MaxID
                ) AS H'), 'H.ID_Autorizacion', '=', 'B.ID')
                ->leftJoin('persona AS A', 'A.ID', '=', 'H.ID_Persona')
                ->leftJoin('concepto_autorizaciones AS C', 'H.ID_Concepto', '=', 'C.ID')
                ->leftJoin('documentosintesis AS D', 'A.ID', '=', 'D.ID_Persona')
                // ✅ Mostrar solo autorizaciones cuyo último estado global sea "APROBADO"
                ->whereRaw('LOWER(TRIM((
                    SELECT H3.Estado
                    FROM historialestado AS H3
                    WHERE H3.ID_Autorizacion = B.ID
                    ORDER BY H3.ID DESC
                    LIMIT 1
                ))) = "aprobado"')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('historialestado AS H2')
                        ->whereRaw('H2.ID_Autorizacion = B.ID');
                })
                ->select([
                    'A.ID AS IDPersona',
                    'A.Score',
                    'A.CuentaAsociada',
                    'A.Nombre',
                    'A.Apellidos',
                    'B.ID AS IDAutorizacion',
                    'H.Convencion',
                    'H.Cedula',
                    'H.CuentaAsociado',
                    'H.NombrePersona',
                    'H.Detalle',
                    'H.Estado',
                    'H.ID_User',
                    'H.ID_Concepto',
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
                $historial = DB::table('autorizaciones_2 AS B')
                    ->join('historialestado AS H', 'H.ID_Autorizacion', '=', 'B.ID')
                    ->leftJoin('persona AS A', 'A.ID', '=', 'H.ID_Persona')
                    ->leftJoin('concepto_autorizaciones AS C', 'H.ID_Concepto', '=', 'C.ID')
                    ->leftJoin('documentosintesis AS D', 'A.ID', '=', 'D.ID_Persona')
                    ->where('H.ID_Autorizacion', $aut->IDAutorizacion)
                    ->select([
                        'H.*',
                        'A.Score',
                        'D.FechaInsercion',
                        'C.Concepto'
                    ])
                    ->orderBy('H.ID', 'asc') // historial en orden cronológico
                    ->get();

                // Adjuntamos el historial completo al objeto
                $aut->historial = $historial;
            
                // 🔹 Primer historial (más antiguo)
                $primer = $historial->first();
                if ($primer) {
                    $aut->Concepto = $primer->Concepto;
                    $aut->Score = $primer->Score;
                    $aut->FechaInsercion = $primer->FechaInsercion;
                    $aut->Fecha = $primer->Fecha;
                    $aut->FechaStringEstado = $primer->FechaString;
                    $aut->Usuario = $primer->Nombre;
                    $aut->NumArea = $primer->NumArea;
                    $aut->NomArea = $primer->NomArea;
                    $aut->PrimerEstado = $primer->Estado;
                } else {
                    $aut->Score = null;
                    $aut->FechaInsercion = null;
                    $aut->Fecha = null;
                    $aut->FechaStringEstado = null;
                    $aut->Usuario = null;
                    $aut->NumArea = null;
                    $aut->NomArea = null;
                    $aut->PrimerEstado = null;
                }
                // 🔹 Inicializamos en null
                $ultimoConceptoNombre = null;

                // 🔹 Recorremos el historial desde el final
                for ($i = $historial->count() - 1; $i >= 0; $i--) {
                    $idConcepto = $historial[$i]->ID_Concepto;

                    if (!is_null($idConcepto)) {
                        // 🔹 Consultamos el nombre del concepto
                        $concepto = DB::table('concepto_autorizaciones')
                            ->where('ID', $idConcepto)
                            ->select('Concepto') // o el campo que tenga el nombre
                            ->first();

                        if ($concepto) {
                            $ultimoConceptoNombre = $concepto->Concepto;
                        }
                        break; // salimos apenas encontramos el primer concepto válido
                    }
                }
                // 🔹 Asignamos al objeto
                $aut->UltimoConcepto = $ultimoConceptoNombre;
                // 🔹 Último historial (más reciente)
                $ultimo = $historial->last();
                $aut->UltimoEstado = $ultimo ? $ultimo->Estado : null;
                                $ultimoRemitidoCorregir = DB::table('historialestado')
                    ->where('ID_Autorizacion', $aut->IDAutorizacion) // 👈 filtra solo la autorización actual
                    ->where('Estado', 'REMITIDOCORREGIR')
                    ->orderByDesc('ID')
                    ->first();

                $aut->EstadoRemitidoBoton = $ultimoRemitidoCorregir
                    ? $ultimoRemitidoCorregir->Estado
                    : null;

                $ultimaCoord = $historial
                    ->filter(function ($h) {
                        return stripos($h->NomArea ?? '', 'Coordinacion') === 0;
                    })
                    ->last();

                $aut->UltimaFechaCoordinacion = $ultimaCoord ? $ultimaCoord->FechaString : null;

                $ultimaDoneTramite = $historial
                    ->filter(function ($h) {
                        $estado = strtoupper(trim($h->Estado ?? ''));
                        return in_array($estado, ['DONE', 'TRÁMITE']);
                    })
                    ->last();

                $aut->UltimaFechaDoneTramite = $ultimaDoneTramite ? $ultimaDoneTramite->FechaString : null;

                $ultimaCoordinacion = $historial
                    ->filter(function ($h) {
                        return stripos($h->NomArea ?? '', 'Coordinacion') === 0;
                    })
                    ->last();

                $aut->UltimaAreaCoordinacion = $ultimaCoordinacion ? $ultimaCoordinacion->NumArea : null;

                




            }


        return response()->json(['data' => $autorizaciones]);
    }

    public function rechazados(Request $request)
    {
        if (!session('email')) {
            return redirect()->route('login');
        }

        $agenciaU = session('agenciau');
        $rol = session('rol');
           
        $autorizaciones = DB::table('autorizaciones_2 AS B')
            ->join(DB::raw('(SELECT 
                                ID_Autorizacion, 
                                MAX(ID) AS UltimoHistorialID
                            FROM historialestado
                            GROUP BY ID_Autorizacion
                            ) AS HMAX'), 'HMAX.ID_Autorizacion', '=', 'B.ID')
            ->join('historialestado AS H', 'H.ID', '=', 'HMAX.UltimoHistorialID')
            ->leftJoin('persona AS A', 'A.ID', '=', 'H.ID_Persona')
            ->leftJoin('concepto_autorizaciones AS C', 'H.ID_Concepto', '=', 'C.ID')
            ->leftJoin('documentosintesis AS D', 'A.ID', '=', 'D.ID_Persona')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('historialestado AS H2')
                    ->whereRaw('H2.ID_Autorizacion = B.ID')
                    ->whereIn('H2.Estado', ['CORREGIR', 'REMITIDOCORREGIR']);
            })
            ->where('H.Estado', '!=', "APROBADO")
            ->where('H.Estado', '!=', "BLOQUEADO")
            ->where('H.Estado', '!=', "VALIDADO")
            ->where('H.Estado', '!=', "TRÁMITE")
            ->select([
                'A.ID AS IDPersona',
                'A.Score',
                'A.CuentaAsociada',
                'A.Nombre',
                'A.Apellidos',
                'B.ID AS IDAutorizacion',
                'H.Convencion',
                'H.Cedula',
                'H.CuentaAsociado',
                'H.NombrePersona',
                'H.Detalle',
                'H.ID_User',
                'H.ID_Concepto',
                'C.Letra',
                'C.No',
                'C.Concepto',
                'C.Areas',
                'D.FechaInsercion'
            ])
            ->get();

            // 🔹 Agregar historial completo + fecha del primer estado
            foreach ($autorizaciones as $aut) {
                $historial = DB::table('autorizaciones_2 AS B')
                    ->join('historialestado AS H', 'H.ID_Autorizacion', '=', 'B.ID')
                    ->leftJoin('persona AS A', 'A.ID', '=', 'H.ID_Persona')
                    ->leftJoin('concepto_autorizaciones AS C', 'H.ID_Concepto', '=', 'C.ID')
                    ->leftJoin('documentosintesis AS D', 'A.ID', '=', 'D.ID_Persona')
                    ->where('H.ID_Autorizacion', $aut->IDAutorizacion)
                    ->select([
                        'H.*',
                        'A.Score',
                        'D.FechaInsercion',
                        'C.Concepto'
                    ])
                    ->orderBy('H.ID', 'asc') // historial en orden cronológico
                    ->get();

                // Adjuntamos el historial completo al objeto
                $aut->historial = $historial;
            
                // 🔹 Primer historial (más antiguo)
                $primer = $historial->first();
                if ($primer) {
                    $aut->Concepto = $primer->Concepto;
                    $aut->Score = $primer->Score;
                    $aut->FechaInsercion = $primer->FechaInsercion;
                    $aut->Fecha = $primer->Fecha;
                    $aut->FechaStringEstado = $primer->FechaString;
                    $aut->Usuario = $primer->Nombre;
                    $aut->NumArea = $primer->NumArea;
                    $aut->NomArea = $primer->NomArea;
                    $aut->PrimerEstado = $primer->Estado;
                } else {
                    $aut->Score = null;
                    $aut->FechaInsercion = null;
                    $aut->Fecha = null;
                    $aut->FechaStringEstado = null;
                    $aut->Usuario = null;
                    $aut->NumArea = null;
                    $aut->NomArea = null;
                    $aut->PrimerEstado = null;
                }
                // 🔹 Inicializamos en null
                $ultimoConceptoNombre = null;

                // 🔹 Recorremos el historial desde el final
                for ($i = $historial->count() - 1; $i >= 0; $i--) {
                    $idConcepto = $historial[$i]->ID_Concepto;

                    if (!is_null($idConcepto)) {
                        // 🔹 Consultamos el nombre del concepto
                        $concepto = DB::table('concepto_autorizaciones')
                            ->where('ID', $idConcepto)
                            ->select('Concepto') // o el campo que tenga el nombre
                            ->first();

                        if ($concepto) {
                            $ultimoConceptoNombre = $concepto->Concepto;
                        }
                        break; // salimos apenas encontramos el primer concepto válido
                    }
                }
                // 🔹 Asignamos al objeto
                $aut->UltimoConcepto = $ultimoConceptoNombre;
                // 🔹 Último historial (más reciente)
                $ultimo = $historial->last();
                $aut->UltimoEstado = $ultimo ? $ultimo->Estado : null;
                                $ultimoRemitidoCorregir = DB::table('historialestado')
                    ->where('ID_Autorizacion', $aut->IDAutorizacion) // 👈 filtra solo la autorización actual
                    ->where('Estado', 'REMITIDOCORREGIR')
                    ->orderByDesc('ID')
                    ->first();

                $aut->EstadoRemitidoBoton = $ultimoRemitidoCorregir
                    ? $ultimoRemitidoCorregir->Estado
                    : null;

                $ultimaCoord = $historial
                    ->filter(function ($h) {
                        return stripos($h->NomArea ?? '', 'Coordinacion') === 0;
                    })
                    ->last();

                $aut->UltimaFechaCoordinacion = $ultimaCoord ? $ultimaCoord->FechaString : null;

                $ultimaDoneTramite = $historial
                    ->filter(function ($h) {
                        $estado = strtoupper(trim($h->Estado ?? ''));
                        return in_array($estado, ['DONE', 'TRÁMITE']);
                    })
                    ->last();

                $aut->UltimaFechaDoneTramite = $ultimaDoneTramite ? $ultimaDoneTramite->FechaString : null;

                $ultimaCoordinacion = $historial
                    ->filter(function ($h) {
                        return stripos($h->NomArea ?? '', 'Coordinacion') === 0;
                    })
                    ->last();

                $aut->UltimaAreaCoordinacion = $ultimaCoordinacion ? $ultimaCoordinacion->NumArea : null;

                




            }


        return response()->json(['data' => $autorizaciones]);
    }

    public function bloqueados(Request $request)
    {
        if (!session('email')) {
            return redirect()->route('login');
        }

        $agenciaU = session('agenciau');
        $rol = session('rol');
           
        $autorizaciones = DB::table('autorizaciones_2 AS B')
            ->join(DB::raw('(SELECT 
                                ID_Autorizacion, 
                                MAX(ID) AS UltimoHistorialID
                            FROM historialestado
                            GROUP BY ID_Autorizacion
                            ) AS HMAX'), 'HMAX.ID_Autorizacion', '=', 'B.ID')
            ->join('historialestado AS H', 'H.ID', '=', 'HMAX.UltimoHistorialID')
            ->leftJoin('persona AS A', 'A.ID', '=', 'H.ID_Persona')
            ->leftJoin('concepto_autorizaciones AS C', 'H.ID_Concepto', '=', 'C.ID')
            ->leftJoin('documentosintesis AS D', 'A.ID', '=', 'D.ID_Persona')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('historialestado AS H2')
                    ->whereRaw('H2.ID_Autorizacion = B.ID')
                    ->whereIn('H2.Estado', ['BLOQUEADO']);
            })
            ->where('H.Estado', '!=', "APROBADO")
            ->where('H.Estado', '!=', "VALIDADO")
            ->select([
                'A.ID AS IDPersona',
                'A.Score',
                'A.CuentaAsociada',
                'A.Nombre',
                'A.Apellidos',
                'B.ID AS IDAutorizacion',
                'H.Convencion',
                'H.Cedula',
                'H.CuentaAsociado',
                'H.NombrePersona',
                'H.Detalle',
                'H.ID_User',
                'H.ID_Concepto',
                'C.Letra',
                'C.No',
                'C.Concepto',
                'C.Areas',
                'D.FechaInsercion'
            ])
            ->get();

            // 🔹 Agregar historial completo + fecha del primer estado
            foreach ($autorizaciones as $aut) {
                $historial = DB::table('autorizaciones_2 AS B')
                    ->join('historialestado AS H', 'H.ID_Autorizacion', '=', 'B.ID')
                    ->leftJoin('persona AS A', 'A.ID', '=', 'H.ID_Persona')
                    ->leftJoin('concepto_autorizaciones AS C', 'H.ID_Concepto', '=', 'C.ID')
                    ->leftJoin('documentosintesis AS D', 'A.ID', '=', 'D.ID_Persona')
                    ->where('H.ID_Autorizacion', $aut->IDAutorizacion)
                    ->select([
                        'H.*',
                        'A.Score',
                        'D.FechaInsercion',
                        'C.Concepto'
                    ])
                    ->orderBy('H.ID', 'asc') // historial en orden cronológico
                    ->get();

                // Adjuntamos el historial completo al objeto
                $aut->historial = $historial;
            
                // 🔹 Primer historial (más antiguo)
                $primer = $historial->first();
                if ($primer) {
                    $aut->Concepto = $primer->Concepto;
                    $aut->Score = $primer->Score;
                    $aut->FechaInsercion = $primer->FechaInsercion;
                    $aut->Fecha = $primer->Fecha;
                    $aut->FechaStringEstado = $primer->FechaString;
                    $aut->Usuario = $primer->Nombre;
                    $aut->NumArea = $primer->NumArea;
                    $aut->NomArea = $primer->NomArea;
                    $aut->PrimerEstado = $primer->Estado;
                } else {
                    $aut->Score = null;
                    $aut->FechaInsercion = null;
                    $aut->Fecha = null;
                    $aut->FechaStringEstado = null;
                    $aut->Usuario = null;
                    $aut->NumArea = null;
                    $aut->NomArea = null;
                    $aut->PrimerEstado = null;
                }
                // 🔹 Inicializamos en null
                $ultimoConceptoNombre = null;

                // 🔹 Recorremos el historial desde el final
                for ($i = $historial->count() - 1; $i >= 0; $i--) {
                    $idConcepto = $historial[$i]->ID_Concepto;

                    if (!is_null($idConcepto)) {
                        // 🔹 Consultamos el nombre del concepto
                        $concepto = DB::table('concepto_autorizaciones')
                            ->where('ID', $idConcepto)
                            ->select('Concepto') // o el campo que tenga el nombre
                            ->first();

                        if ($concepto) {
                            $ultimoConceptoNombre = $concepto->Concepto;
                        }
                        break; // salimos apenas encontramos el primer concepto válido
                    }
                }
                // 🔹 Asignamos al objeto
                $aut->UltimoConcepto = $ultimoConceptoNombre;
                // 🔹 Último historial (más reciente)
                $ultimo = $historial->last();
                $aut->UltimoEstado = $ultimo ? $ultimo->Estado : null;
                                $ultimoRemitidoCorregir = DB::table('historialestado')
                    ->where('ID_Autorizacion', $aut->IDAutorizacion) // 👈 filtra solo la autorización actual
                    ->where('Estado', 'REMITIDOCORREGIR')
                    ->orderByDesc('ID')
                    ->first();

                $aut->EstadoRemitidoBoton = $ultimoRemitidoCorregir
                    ? $ultimoRemitidoCorregir->Estado
                    : null;

                $ultimaCoord = $historial
                    ->filter(function ($h) {
                        return stripos($h->NomArea ?? '', 'Coordinacion') === 0;
                    })
                    ->last();

                $aut->UltimaFechaCoordinacion = $ultimaCoord ? $ultimaCoord->FechaString : null;

                $ultimaDoneTramite = $historial
                    ->filter(function ($h) {
                        $estado = strtoupper(trim($h->Estado ?? ''));
                        return in_array($estado, ['DONE', 'TRÁMITE']);
                    })
                    ->last();

                $aut->UltimaFechaDoneTramite = $ultimaDoneTramite ? $ultimaDoneTramite->FechaString : null;

                $ultimaCoordinacion = $historial
                    ->filter(function ($h) {
                        return stripos($h->NomArea ?? '', 'Coordinacion') === 0;
                    })
                    ->last();

                $aut->UltimaAreaCoordinacion = $ultimaCoordinacion ? $ultimaCoordinacion->NumArea : null;






            }


        return response()->json(['data' => $autorizaciones]);
    }

    public function tramite(Request $request)
    {
        if (!session('email')) {
            return redirect()->route('login');
        }

        $agenciaU = session('agenciau');
        $rol = session('rol');
           
        $autorizaciones = DB::table('autorizaciones_2 AS B')
            ->join(DB::raw('(SELECT 
                                ID_Autorizacion, 
                                MAX(ID) AS UltimoHistorialID
                            FROM historialestado
                            GROUP BY ID_Autorizacion
                            ) AS HMAX'), 'HMAX.ID_Autorizacion', '=', 'B.ID')
            ->join('historialestado AS H', 'H.ID', '=', 'HMAX.UltimoHistorialID')
            ->leftJoin('persona AS A', 'A.ID', '=', 'H.ID_Persona')
            ->leftJoin('concepto_autorizaciones AS C', 'H.ID_Concepto', '=', 'C.ID')
            ->leftJoin('documentosintesis AS D', 'A.ID', '=', 'D.ID_Persona')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('historialestado AS H2')
                    ->whereRaw('H2.ID_Autorizacion = B.ID')
                    ->whereIn('H2.Estado', ['TRÁMITE']);
            })
            ->where('H.Estado', '!=', "APROBADO")
            ->where('H.Estado', '!=', "VALIDADO")
            ->where('H.Estado', '!=', "CORREGIR")
            ->select([
                'A.ID AS IDPersona',
                'A.Score',
                'A.CuentaAsociada',
                'A.Nombre',
                'A.Apellidos',
                'B.ID AS IDAutorizacion',
                'H.Convencion',
                'H.Cedula',
                'H.CuentaAsociado',
                'H.NombrePersona',
                'H.Detalle',
                'H.ID_User',
                'H.ID_Concepto',
                'C.Letra',
                'C.No',
                'C.Concepto',
                'C.Areas',
                'D.FechaInsercion'
            ])
            ->get();

            // 🔹 Agregar historial completo + fecha del primer estado
            foreach ($autorizaciones as $aut) {
                $historial = DB::table('autorizaciones_2 AS B')
                    ->join('historialestado AS H', 'H.ID_Autorizacion', '=', 'B.ID')
                    ->leftJoin('persona AS A', 'A.ID', '=', 'H.ID_Persona')
                    ->leftJoin('concepto_autorizaciones AS C', 'H.ID_Concepto', '=', 'C.ID')
                    ->leftJoin('documentosintesis AS D', 'A.ID', '=', 'D.ID_Persona')
                    ->where('H.ID_Autorizacion', $aut->IDAutorizacion)
                    ->select([
                        'H.*',
                        'A.Score',
                        'D.FechaInsercion',
                        'C.Concepto'
                    ])
                    ->orderBy('H.ID', 'asc') // historial en orden cronológico
                    ->get();

                // Adjuntamos el historial completo al objeto
                $aut->historial = $historial;
            
                // 🔹 Primer historial (más antiguo)
                $primer = $historial->first();
                if ($primer) {
                    $aut->Concepto = $primer->Concepto;
                    $aut->Score = $primer->Score;
                    $aut->FechaInsercion = $primer->FechaInsercion;
                    $aut->Fecha = $primer->Fecha;
                    $aut->FechaStringEstado = $primer->FechaString;
                    $aut->Usuario = $primer->Nombre;
                    $aut->NumArea = $primer->NumArea;
                    $aut->NomArea = $primer->NomArea;
                    $aut->PrimerEstado = $primer->Estado;
                } else {
                    $aut->Score = null;
                    $aut->FechaInsercion = null;
                    $aut->Fecha = null;
                    $aut->FechaStringEstado = null;
                    $aut->Usuario = null;
                    $aut->NumArea = null;
                    $aut->NomArea = null;
                    $aut->PrimerEstado = null;
                }
                // 🔹 Inicializamos en null
                $ultimoConceptoNombre = null;

                // 🔹 Recorremos el historial desde el final
                for ($i = $historial->count() - 1; $i >= 0; $i--) {
                    $idConcepto = $historial[$i]->ID_Concepto;

                    if (!is_null($idConcepto)) {
                        // 🔹 Consultamos el nombre del concepto
                        $concepto = DB::table('concepto_autorizaciones')
                            ->where('ID', $idConcepto)
                            ->select('Concepto') // o el campo que tenga el nombre
                            ->first();

                        if ($concepto) {
                            $ultimoConceptoNombre = $concepto->Concepto;
                        }
                        break; // salimos apenas encontramos el primer concepto válido
                    }
                }
                // 🔹 Asignamos al objeto
                $aut->UltimoConcepto = $ultimoConceptoNombre;
                // 🔹 Último historial (más reciente)
                $ultimo = $historial->last();
                $aut->UltimoEstado = $ultimo ? $ultimo->Estado : null;
                                $ultimoRemitidoCorregir = DB::table('historialestado')
                    ->where('ID_Autorizacion', $aut->IDAutorizacion) // 👈 filtra solo la autorización actual
                    ->where('Estado', 'REMITIDOCORREGIR')
                    ->orderByDesc('ID')
                    ->first();

                $aut->EstadoRemitidoBoton = $ultimoRemitidoCorregir
                    ? $ultimoRemitidoCorregir->Estado
                    : null;

                $ultimaCoord = $historial
                    ->filter(function ($h) {
                        return stripos($h->NomArea ?? '', 'Coordinacion') === 0;
                    })
                    ->last();

                $aut->UltimaFechaCoordinacion = $ultimaCoord ? $ultimaCoord->FechaString : null;

                $ultimaDoneTramite = $historial
                    ->filter(function ($h) {
                        $estado = strtoupper(trim($h->Estado ?? ''));
                        return in_array($estado, ['DONE', 'TRÁMITE']);
                    })
                    ->last();

                $aut->UltimaFechaDoneTramite = $ultimaDoneTramite ? $ultimaDoneTramite->FechaString : null;

                $ultimaCoordinacion = $historial
                    ->filter(function ($h) {
                        return stripos($h->NomArea ?? '', 'Coordinacion') === 0;
                    })
                    ->last();

                $aut->UltimaAreaCoordinacion = $ultimaCoordinacion ? $ultimaCoordinacion->NumArea : null;




            }


        return response()->json(['data' => $autorizaciones]);
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
        if (!session('email')) {
            return redirect()->route('login');
        }

        $agenciaU = session('agenciau');
        $rol = session('rol');
        if($rol == "Coordinacion"){
            $id = session('id');


            $coordinaciones = DB::select("SELECT DISTINCT agenciau, agencias_id FROM users WHERE agenciau = ? AND id = ?", [$agenciaU, $id]);
        
            $agenciasIdArray = json_decode($coordinaciones[0]->agencias_id, true);
            if ($agenciasIdArray === null) {
                $agenciasIdArray = [];
            }
            
            $numero = preg_replace('/[^0-9]/', '', $coordinaciones[0]->agenciau);

            if (session('agenciau') == "Coordinacion $numero") {
                $coordinacionVariable = "C" . $numero;
            }

            if (count($agenciasIdArray) > 0) {
                //APARECEN RECHAZADOS AQUI Y FALTARIA BLOQUEADO
              
                $idsFiltro = array_merge($agenciasIdArray, [$coordinacionVariable]);
            

                $autorizaciones = DB::table('autorizaciones_2 AS B')
                    ->join('historialestado AS H', function ($join) {
                        $join->on('H.ID_Autorizacion', '=', 'B.ID');
                    })
                    ->leftJoin('persona AS A', 'A.ID', '=', 'H.ID_Persona')
                    ->leftJoin('concepto_autorizaciones AS C', 'H.ID_Concepto', '=', 'C.ID')
                    ->leftJoin('documentosintesis AS D', 'A.ID', '=', 'D.ID_Persona')
                    ->whereExists(function ($sub) use ($idsFiltro) {
                        $sub->select(DB::raw(1))
                            ->from('historialestado AS H2')
                            ->whereColumn('H2.ID_Autorizacion', 'B.ID')
                            ->whereIn('H2.NumArea', $idsFiltro);
                    })
                    // ✅ Solo el último estado de cada autorización
                    ->whereRaw('H.ID = (SELECT MAX(H3.ID) FROM historialestado AS H3 WHERE H3.ID_Autorizacion = B.ID)')
                    // ✅ Filtrar únicamente las que están aprobadas
                    ->whereRaw('LOWER(TRIM(H.Estado)) = "stand by"')
                    // 🚫 Excluir otros estados no deseados (por seguridad)
                    ->whereNotIn('H.Estado', ['APROBADO', 'TRÁMITE', 'REMITIDO', 'REMITIDOCONFIRMADO'])
                    ->select([
                        'A.ID AS IDPersona',
                        'A.Score',
                        'A.CuentaAsociada',
                        'A.Nombre',
                        'A.Apellidos',
                        'B.ID AS IDAutorizacion',
                        'H.Convencion',
                        'H.Cedula',
                        'H.CuentaAsociado',
                        'H.NombrePersona',
                        'H.Detalle',
                        'H.ID_User',
                        'H.ID_Concepto',
                        'C.Letra',
                        'C.No',
                        'C.Concepto',
                        'C.Areas',
                        'D.FechaInsercion'
                    ])
                    ->distinct()
                    ->get();




            }



        }elseif($rol == "Gerencia"){
           
         $autorizaciones = DB::table('autorizaciones_2 AS B')
            ->join(DB::raw('(SELECT 
                                ID_Autorizacion, 
                                MAX(ID) AS UltimoHistorialID
                            FROM historialestado
                            GROUP BY ID_Autorizacion
                            ) AS HMAX'), 'HMAX.ID_Autorizacion', '=', 'B.ID')
            ->join('historialestado AS H', 'H.ID', '=', 'HMAX.UltimoHistorialID')
            ->leftJoin('persona AS A', 'A.ID', '=', 'H.ID_Persona')
            ->leftJoin('concepto_autorizaciones AS C', 'H.ID_Concepto', '=', 'C.ID')
            ->leftJoin('documentosintesis AS D', 'A.ID', '=', 'D.ID_Persona')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('historialestado AS H2')
                    ->whereRaw('H2.ID_Autorizacion = B.ID')
                    ->whereIn('H2.Estado', ['STAND BY']);
            })
            ->where('H.Estado', '!=', "APROBADO")
            ->where('H.Estado', '!=', "VALIDADO")
            ->where('H.Estado', '!=', "CORREGIR")
            ->select([
                'A.ID AS IDPersona',
                'A.Score',
                'A.CuentaAsociada',
                'A.Nombre',
                'A.Apellidos',
                'B.ID AS IDAutorizacion',
                'H.Convencion',
                'H.Cedula',
                'H.CuentaAsociado',
                'H.NombrePersona',
                'H.Detalle',
                'H.ID_User',
                'H.ID_Concepto',
                'C.Letra',
                'C.No',
                'C.Concepto',
                'C.Areas',
                'D.FechaInsercion'
            ])
            ->get();

        }else{
            $autorizaciones = DB::table('autorizaciones_2 AS B')
                ->join(DB::raw('(
                    SELECT H1.*
                    FROM historialestado AS H1
                    INNER JOIN (
                        SELECT ID_Autorizacion, MAX(ID) AS MaxID
                        FROM historialestado
                        WHERE NomArea = "' . $agenciaU . '"
                        GROUP BY ID_Autorizacion
                    ) AS Ultimo
                    ON H1.ID = Ultimo.MaxID
                ) AS H'), 'H.ID_Autorizacion', '=', 'B.ID')
                ->leftJoin('persona AS A', 'A.ID', '=', 'H.ID_Persona')
                ->leftJoin('concepto_autorizaciones AS C', 'H.ID_Concepto', '=', 'C.ID')
                ->leftJoin('documentosintesis AS D', 'A.ID', '=', 'D.ID_Persona')
                // ✅ Mostrar solo autorizaciones cuyo último estado global sea "APROBADO"
                ->whereRaw('LOWER(TRIM((
                    SELECT H3.Estado
                    FROM historialestado AS H3
                    WHERE H3.ID_Autorizacion = B.ID
                    ORDER BY H3.ID DESC
                    LIMIT 1
                ))) = "stand by"')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('historialestado AS H2')
                        ->whereRaw('H2.ID_Autorizacion = B.ID');
                })
                ->select([
                    'A.ID AS IDPersona',
                    'A.Score',
                    'A.CuentaAsociada',
                    'A.Nombre',
                    'A.Apellidos',
                    'B.ID AS IDAutorizacion',
                    'H.Convencion',
                    'H.Cedula',
                    'H.CuentaAsociado',
                    'H.NombrePersona',
                    'H.Detalle',
                    'H.Estado',
                    'H.ID_User',
                    'H.ID_Concepto',
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
                $historial = DB::table('autorizaciones_2 AS B')
                    ->join('historialestado AS H', 'H.ID_Autorizacion', '=', 'B.ID')
                    ->leftJoin('persona AS A', 'A.ID', '=', 'H.ID_Persona')
                    ->leftJoin('concepto_autorizaciones AS C', 'H.ID_Concepto', '=', 'C.ID')
                    ->leftJoin('documentosintesis AS D', 'A.ID', '=', 'D.ID_Persona')
                    ->where('H.ID_Autorizacion', $aut->IDAutorizacion)
                    ->select([
                        'H.*',
                        'A.Score',
                        'D.FechaInsercion',
                        'C.Concepto'
                    ])
                    ->orderBy('H.ID', 'asc') // historial en orden cronológico
                    ->get();

                // Adjuntamos el historial completo al objeto
                $aut->historial = $historial;
            
                // 🔹 Primer historial (más antiguo)
                $primer = $historial->first();
                if ($primer) {
                    $aut->Concepto = $primer->Concepto;
                    $aut->Score = $primer->Score;
                    $aut->FechaInsercion = $primer->FechaInsercion;
                    $aut->Fecha = $primer->Fecha;
                    $aut->FechaStringEstado = $primer->FechaString;
                    $aut->Usuario = $primer->Nombre;
                    $aut->NumArea = $primer->NumArea;
                    $aut->NomArea = $primer->NomArea;
                    $aut->PrimerEstado = $primer->Estado;
                } else {
                    $aut->Score = null;
                    $aut->FechaInsercion = null;
                    $aut->Fecha = null;
                    $aut->FechaStringEstado = null;
                    $aut->Usuario = null;
                    $aut->NumArea = null;
                    $aut->NomArea = null;
                    $aut->PrimerEstado = null;
                }
                // 🔹 Inicializamos en null
                $ultimoConceptoNombre = null;

                // 🔹 Recorremos el historial desde el final
                for ($i = $historial->count() - 1; $i >= 0; $i--) {
                    $idConcepto = $historial[$i]->ID_Concepto;

                    if (!is_null($idConcepto)) {
                        // 🔹 Consultamos el nombre del concepto
                        $concepto = DB::table('concepto_autorizaciones')
                            ->where('ID', $idConcepto)
                            ->select('Concepto') // o el campo que tenga el nombre
                            ->first();

                        if ($concepto) {
                            $ultimoConceptoNombre = $concepto->Concepto;
                        }
                        break; // salimos apenas encontramos el primer concepto válido
                    }
                }
                // 🔹 Asignamos al objeto
                $aut->UltimoConcepto = $ultimoConceptoNombre;
                // 🔹 Último historial (más reciente)
                $ultimo = $historial->last();
                $aut->UltimoEstado = $ultimo ? $ultimo->Estado : null;
                                $ultimoRemitidoCorregir = DB::table('historialestado')
                    ->where('ID_Autorizacion', $aut->IDAutorizacion) // 👈 filtra solo la autorización actual
                    ->where('Estado', 'REMITIDOCORREGIR')
                    ->orderByDesc('ID')
                    ->first();

                $aut->EstadoRemitidoBoton = $ultimoRemitidoCorregir
                    ? $ultimoRemitidoCorregir->Estado
                    : null;

                $ultimaCoord = $historial
                    ->filter(function ($h) {
                        return stripos($h->NomArea ?? '', 'Coordinacion') === 0;
                    })
                    ->last();

                $aut->UltimaFechaCoordinacion = $ultimaCoord ? $ultimaCoord->FechaString : null;

                $ultimaDoneTramite = $historial
                    ->filter(function ($h) {
                        $estado = strtoupper(trim($h->Estado ?? ''));
                        return in_array($estado, ['DONE', 'TRÁMITE']);
                    })
                    ->last();

                $aut->UltimaFechaDoneTramite = $ultimaDoneTramite ? $ultimaDoneTramite->FechaString : null;

                $ultimaCoordinacion = $historial
                    ->filter(function ($h) {
                        return stripos($h->NomArea ?? '', 'Coordinacion') === 0;
                    })
                    ->last();

                $aut->UltimaAreaCoordinacion = $ultimaCoordinacion ? $ultimaCoordinacion->NumArea : null;

                

                
                




            }

        return response()->json(['data' => $autorizaciones]);
   
    }

    public function c9(Request $request)
    {
        if (!session('email')) {
            return redirect()->route('login');
        }

        $agenciaU = session('agenciau');
        $rol = session('rol');
           
        $autorizaciones = DB::table('autorizaciones_2 AS B')
            ->join(DB::raw('(SELECT 
                                ID_Autorizacion, 
                                MAX(ID) AS UltimoHistorialID
                            FROM historialestado
                            GROUP BY ID_Autorizacion
                            ) AS HMAX'), 'HMAX.ID_Autorizacion', '=', 'B.ID')
            ->join('historialestado AS H', 'H.ID', '=', 'HMAX.UltimoHistorialID')
            ->leftJoin('persona AS A', 'A.ID', '=', 'H.ID_Persona')
            ->leftJoin('concepto_autorizaciones AS C', 'H.ID_Concepto', '=', 'C.ID')
            ->leftJoin('documentosintesis AS D', 'A.ID', '=', 'D.ID_Persona')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('historialestado AS H2')
                    ->whereRaw('H2.ID_Autorizacion = B.ID')
                    ->whereIn('H2.Estado', ['TRÁMITE']);
            })
            ->where('H.NumArea', '=', "Jefatura")
            ->select([
                'A.ID AS IDPersona',
                'A.Score',
                'A.CuentaAsociada',
                'A.Nombre',
                'A.Apellidos',
                'B.ID AS IDAutorizacion',
                'H.Convencion',
                'H.Cedula',
                'H.CuentaAsociado',
                'H.NombrePersona',
                'H.Detalle',
                'H.ID_User',
                'H.ID_Concepto',
                'C.Letra',
                'C.No',
                'C.Concepto',
                'C.Areas',
                'D.FechaInsercion'
            ])
            ->get();

            // 🔹 Agregar historial completo + fecha del primer estado
            foreach ($autorizaciones as $aut) {
                $historial = DB::table('autorizaciones_2 AS B')
                    ->join('historialestado AS H', 'H.ID_Autorizacion', '=', 'B.ID')
                    ->leftJoin('persona AS A', 'A.ID', '=', 'H.ID_Persona')
                    ->leftJoin('concepto_autorizaciones AS C', 'H.ID_Concepto', '=', 'C.ID')
                    ->leftJoin('documentosintesis AS D', 'A.ID', '=', 'D.ID_Persona')
                    ->where('H.ID_Autorizacion', $aut->IDAutorizacion)
                    ->select([
                        'H.*',
                        'A.Score',
                        'D.FechaInsercion',
                        'C.Concepto'
                    ])
                    ->orderBy('H.ID', 'asc') // historial en orden cronológico
                    ->get();

                // Adjuntamos el historial completo al objeto
                $aut->historial = $historial;
            
                // 🔹 Primer historial (más antiguo)
                $primer = $historial->first();
                if ($primer) {
                    $aut->Concepto = $primer->Concepto;
                    $aut->Score = $primer->Score;
                    $aut->FechaInsercion = $primer->FechaInsercion;
                    $aut->Fecha = $primer->Fecha;
                    $aut->FechaStringEstado = $primer->FechaString;
                    $aut->Usuario = $primer->Nombre;
                    $aut->NumArea = $primer->NumArea;
                    $aut->NomArea = $primer->NomArea;
                    $aut->PrimerEstado = $primer->Estado;
                } else {
                    $aut->Score = null;
                    $aut->FechaInsercion = null;
                    $aut->Fecha = null;
                    $aut->FechaStringEstado = null;
                    $aut->Usuario = null;
                    $aut->NumArea = null;
                    $aut->NomArea = null;
                    $aut->PrimerEstado = null;
                }
                // 🔹 Inicializamos en null
                $ultimoConceptoNombre = null;

                // 🔹 Recorremos el historial desde el final
                for ($i = $historial->count() - 1; $i >= 0; $i--) {
                    $idConcepto = $historial[$i]->ID_Concepto;

                    if (!is_null($idConcepto)) {
                        // 🔹 Consultamos el nombre del concepto
                        $concepto = DB::table('concepto_autorizaciones')
                            ->where('ID', $idConcepto)
                            ->select('Concepto') // o el campo que tenga el nombre
                            ->first();

                        if ($concepto) {
                            $ultimoConceptoNombre = $concepto->Concepto;
                        }
                        break; // salimos apenas encontramos el primer concepto válido
                    }
                }
                // 🔹 Asignamos al objeto
                $aut->UltimoConcepto = $ultimoConceptoNombre;
                // 🔹 Último historial (más reciente)
                $ultimo = $historial->last();
                $aut->UltimoEstado = $ultimo ? $ultimo->Estado : null;
                                $ultimoRemitidoCorregir = DB::table('historialestado')
                    ->where('ID_Autorizacion', $aut->IDAutorizacion) // 👈 filtra solo la autorización actual
                    ->where('Estado', 'REMITIDOCORREGIR')
                    ->orderByDesc('ID')
                    ->first();

                $aut->EstadoRemitidoBoton = $ultimoRemitidoCorregir
                    ? $ultimoRemitidoCorregir->Estado
                    : null;

                $ultimaCoord = $historial
                    ->filter(function ($h) {
                        return stripos($h->NomArea ?? '', 'Coordinacion') === 0;
                    })
                    ->last();

                $aut->UltimaFechaCoordinacion = $ultimaCoord ? $ultimaCoord->FechaString : null;

                $ultimaDoneTramite = $historial
                    ->filter(function ($h) {
                        $estado = strtoupper(trim($h->Estado ?? ''));
                        return in_array($estado, ['DONE', 'TRÁMITE']);
                    })
                    ->last();

                $aut->UltimaFechaDoneTramite = $ultimaDoneTramite ? $ultimaDoneTramite->FechaString : null;

                $ultimaCoordinacion = $historial
                    ->filter(function ($h) {
                        return stripos($h->NomArea ?? '', 'Coordinacion') === 0;
                    })
                    ->last();

                $aut->UltimaAreaCoordinacion = $ultimaCoordinacion ? $ultimaCoordinacion->NumArea : null;


            }


        return response()->json(['data' => $autorizaciones]);
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


    //GERENCIA
    public function contarsolicitudes(Request $request)
    {
        $directorestramite = DB::table('autorizaciones')
        ->select('autorizaciones.Estado as EstadoAutorizacion')
        ->where('autorizaciones.Estado', 2)
        ->get()
        ->count();

        $coordinadorestramite = DB::table('autorizaciones')
        ->select('autorizaciones.Estado as EstadoAutorizacion')
        ->where('autorizaciones.Estado', 6)
        ->get()
        ->count();

        //ESTE ESTADO YA NO SE UTILIZA PERO SE SUMA PORQUE EN LAS PRIMERAS VERSIONES, ALGUNAS AUTORIZACIONES QUEDARON CON ESE ESTADO
        $coordinadorestramitecorregir = DB::table('autorizaciones')
        ->select('autorizaciones.Estado as EstadoAutorizacion')
        ->where('autorizaciones.Estado', 3)
        ->get()
        ->count();

        //sumatoria de todos los que estan en tramite
        $tramite = ($directorestramite + $coordinadorestramite + $coordinadorestramitecorregir);



        $validadocoordinadores = DB::table('autorizaciones')
        ->select('autorizaciones.Estado as EstadoAutorizacion')
        ->where('autorizaciones.Estado', 1)
        ->get()
        ->count();


        $rechazadogerencia = DB::table('autorizaciones')
        ->select('autorizaciones.Estado as EstadoAutorizacion')
        ->where('autorizaciones.Estado', 5)
        ->get()
        ->count();

        $rechazadocoordinacion = DB::table('autorizaciones')
        ->select('autorizaciones.Estado as EstadoAutorizacion')
        ->where('autorizaciones.Estado', 0)
        ->get()
        ->count();

        $rechazados = $rechazadocoordinacion + $rechazadogerencia;

        $aprobadogerencia = DB::table('autorizaciones')
        ->select('autorizaciones.Estado as EstadoAutorizacion')
        ->where('autorizaciones.Estado', 4)
        ->get()
        ->count();

        $anuladosgerencia = DB::table('autorizaciones')
        ->select('autorizaciones.Estado as EstadoAutorizacion')
        ->where('autorizaciones.Estado', 7)
        ->get()
        ->count();

        $total = $tramite + $validadocoordinadores + $rechazados + $aprobadogerencia + $anuladosgerencia;

        $nombresAgencia = DB::table('autorizaciones')
        ->select('NomAgencia')
        ->distinct()
        ->orderBy('NomAgencia', 'asc')
        ->get();

        $year = DB::table('autorizaciones')
        ->select(DB::raw("SUBSTRING_INDEX(SUBSTRING_INDEX(Fecha, ' ', -1), '-', 1) AS year"))
        ->distinct()
        ->orderBy('year', 'asc')
        ->get();



        //esto se hace con el fin de que se rellene los circulo de forma dinamica
        $porcentaje_tramite = ($tramite != 0) ? ($tramite / $total * 100) : 0;
        $porcentaje_tramite_con_decimales = round($porcentaje_tramite, 2);

        $porcentajevalidos = ($validadocoordinadores != 0) ? ($validadocoordinadores / $total * 100) : 0;
        $decimalvalidados = round($porcentajevalidos, 2);

        $porcentajerechazados = ($rechazados != 0) ? ($rechazados / $total * 100) : 0;
        $decimalrechazados = round($porcentajerechazados, 2);

        $porcentajeaprobados = ($aprobadogerencia != 0) ? ($aprobadogerencia / $total * 100) : 0;
        $decimalaprobados = round($porcentajeaprobados, 2);

        $porcentajeanulados = ($anuladosgerencia != 0) ? ($anuladosgerencia / $total * 100) : 0;
        $decimalanulados = round($porcentajeanulados, 2);


        return view('Gerencia/estadisticas', ['porcentajeanulados' => $porcentajeanulados,'anuladosgerencia' => $anuladosgerencia,'decimalanulados' => $decimalanulados,'decimalaprobados' => $decimalaprobados, 'decimalrechazados' => $decimalrechazados, 'decimalvalidados' => $decimalvalidados, 'porcentajetramite' => $porcentaje_tramite_con_decimales, 'tramite' => $tramite, 'validadocoordinadores' => $validadocoordinadores, 'rechazados' => $rechazados, 'aprobadogerencia' => $aprobadogerencia, 'total' => $total, 'nombresAgencia' => $nombresAgencia, 'year' => $year]);

    }


    public function actualizardatos(Request $request)
    {

        $startDate = $request->start; // '2024-05-29'
        $endDate = $request->end;     // '2024-05-28'


        Log::info($startDate. ' '. $endDate. ' '.$request->agencia);

        // Asegúrate de que las fechas estén en formato Y-m-d
        $startDateFormatted = date('Y-m-d 00:00:00', strtotime($startDate));
        $endDateFormatted = date('Y-m-d 23:59:59', strtotime($endDate));

        $whererangosfecha = "
        STR_TO_DATE(
            REPLACE(
                REPLACE(
                    REPLACE(
                        REPLACE(
                            REPLACE(
                                REPLACE(
                                    REPLACE(
                                        REPLACE(
                                            REPLACE(
                                                REPLACE(
                                                    REPLACE(
                                                        REPLACE(Fecha, 'enero', 'January'),
                                                        'febrero', 'February'
                                                    ), 'marzo', 'March'
                                                ), 'abril', 'April'
                                            ), 'mayo', 'May'
                                        ), 'junio', 'June'
                                    ), 'julio', 'July'
                                ), 'agosto', 'August'
                            ), 'septiembre', 'September'
                        ), 'octubre', 'October'
                    ), 'noviembre', 'November'
                ), 'diciembre', 'December'
            ), '%M %d %Y-%H:%i:%s'
        ) BETWEEN ? AND ?
        ";

        $directorestramite = DB::table('autorizaciones')
        ->select('autorizaciones.Estado as EstadoAutorizacion')
        ->where('autorizaciones.Estado', 2)
        ->where('autorizaciones.NomAgencia', $request->agencia)
        ->whereRaw($whererangosfecha, [$startDateFormatted, $endDateFormatted])
        ->get()
        ->count();


        $coordinadorestramite = DB::table('autorizaciones')
        ->select('autorizaciones.Estado as EstadoAutorizacion')
        ->where('autorizaciones.Estado', 6)
        ->where('autorizaciones.NomAgencia', $request->agencia)
        ->whereRaw($whererangosfecha, [$startDateFormatted, $endDateFormatted])
        ->get()
        ->count();

        //ESTE ESTADO YA NO SE UTILIZA PERO SE SUMA PORQUE EN LAS PRIMERAS VERSIONES, ALGUNAS AUTORIZACIONES QUEDARON CON ESE ESTADO
        $coordinadorestramitecorregir = DB::table('autorizaciones')
        ->select('autorizaciones.Estado as EstadoAutorizacion')
        ->where('autorizaciones.Estado', 3)
        ->where('autorizaciones.NomAgencia', $request->agencia)
        ->whereRaw($whererangosfecha, [$startDateFormatted, $endDateFormatted])
        ->get()
        ->count();


        //sumatoria de todos los que estan en tramite
        $tramite = ($directorestramite + $coordinadorestramite + $coordinadorestramitecorregir);



        $validadocoordinadores = DB::table('autorizaciones')
        ->select('autorizaciones.Estado as EstadoAutorizacion')
        ->where('autorizaciones.Estado', 1)
        ->where('autorizaciones.NomAgencia', $request->agencia)
        ->whereRaw($whererangosfecha, [$startDateFormatted, $endDateFormatted])
        ->get()
        ->count();

        $rechazadogerencia = DB::table('autorizaciones')
        ->select('autorizaciones.Estado as EstadoAutorizacion')
        ->where('autorizaciones.Estado', 5)
        ->where('autorizaciones.NomAgencia', $request->agencia)
        ->whereRaw($whererangosfecha, [$startDateFormatted, $endDateFormatted])
        ->get()
        ->count();

        $rechazadocoordinacion = DB::table('autorizaciones')
        ->select('autorizaciones.Estado as EstadoAutorizacion')
        ->where('autorizaciones.Estado', 0)
        ->where('autorizaciones.NomAgencia', $request->agencia)
        ->whereRaw($whererangosfecha, [$startDateFormatted, $endDateFormatted])
        ->get()
        ->count();

        $rechazados = $rechazadocoordinacion + $rechazadogerencia;

        $aprobadogerencia = DB::table('autorizaciones')
        ->select('autorizaciones.Estado as EstadoAutorizacion')
        ->where('autorizaciones.Estado', 4)
        ->where('autorizaciones.NomAgencia', $request->agencia)
        ->whereRaw($whererangosfecha, [$startDateFormatted, $endDateFormatted])
        ->get()
        ->count();

        $anuladosgerencia = DB::table('autorizaciones')
        ->select('autorizaciones.Estado as EstadoAutorizacion')
        ->where('autorizaciones.Estado', 7)
        ->where('autorizaciones.NomAgencia', $request->agencia)
        ->whereRaw($whererangosfecha, [$startDateFormatted, $endDateFormatted])
        ->get()
        ->count();

        $total = $tramite + $validadocoordinadores + $rechazados + $aprobadogerencia+ $anuladosgerencia;



        //esto se hace con el fin de que se rellene los circulo de forma dinamica
        $porcentaje_tramite = ($tramite != 0) ? ($tramite / $total * 100) : 0;
        $porcentaje_tramite_con_decimales = round($porcentaje_tramite, 2);

        $porcentajevalidos = ($validadocoordinadores != 0) ? ($validadocoordinadores / $total * 100) : 0;
        $decimalvalidados = round($porcentajevalidos, 2);

        $porcentajerechazados = ($rechazados != 0) ? ($rechazados / $total * 100) : 0;
        $decimalrechazados = round($porcentajerechazados, 2);

        $porcentajeaprobados = ($aprobadogerencia != 0) ? ($aprobadogerencia / $total * 100) : 0;
        $decimalaprobados = round($porcentajeaprobados, 2);


        return response()->json([
            'decimalaprobados' => $decimalaprobados,
            'decimalrechazados' => $decimalrechazados,
            'decimalvalidados' => $decimalvalidados,
            'porcentaje_tramite' => $porcentaje_tramite_con_decimales,
            'tramite' => $tramite,
            'validadocoordinadores' => $validadocoordinadores,
            'rechazados' => $rechazados,
            'aprobadogerencia' => $aprobadogerencia,
            'total' => $total,
            'anuladosgerencia' => $anuladosgerencia
        ]);

    }


    public function concepto()
    {

        $agenciaU = session('agenciau');
        $user = DB::select("SELECT * FROM concepto_autorizaciones ORDER BY Letra ASC");
        $agencia = DB::select("SELECT DISTINCT NomAgencia FROM autorizaciones ORDER BY NomAgencia ASC");
        $solicitadopor = DB::select("SELECT DISTINCT SolicitadoPor FROM autorizaciones ORDER BY SolicitadoPor ASC");
        $validadopor = DB::select("SELECT DISTINCT ValidadoPor FROM autorizaciones ORDER BY ValidadoPor ASC");
        $area = DB::select("SELECT DISTINCT Areas FROM concepto_autorizaciones ORDER BY Areas ASC");

        return view('Gerencia/filtrarconcepto', [
            'user' => $user,
            'agencia' => $agencia,
            'area' => $area,
            'solicitadopor' => $solicitadopor,
            'validadopor' => $validadopor
        ]);
    }



    public function filtrarconcepto(Request $request)
    {
        if (session('email') == null) {
            return redirect()->route('login');
        }
        $agenciaU = session('agenciau');

        $agencias = DB::select("SELECT NumAgencia FROM autorizaciones");

        $solicitudes = DB::select("
            SELECT DISTINCT
                A.ID AS IDPersona,
                A.Score,
                A.CuentaAsociada,
                A.Nombre,
                A.Apellidos,
                B.ID AS IDAutorizacion,
                B.Convencion,
                B.DocumentoSoporte,
                B.Fecha,
                B.CodigoAutorizacion,
                B.NomAgencia,
                B.NumAgencia,
                B.Cedula,
                B.CuentaAsociado,
                B.EstadoCuenta,
                B.NombrePersona,
                B.Detalle,
                B.Observaciones,
                B.Estado,
                B.Solicitud,
                B.SolicitadoPor,
                B.Validacion,
                B.ValidadoPor,
                B.FechaValidacion,
                B.Coordinacion,
                B.Aprobacion,
                B.AprobadoPor,
                B.FechaAprobacion,
                B.ObservacionesGer,
                C.Letra,
                C.No,
                C.Concepto,
                C.Areas,
                D.FechaInsercion
            FROM persona A
            JOIN autorizaciones B ON B.ID_Persona = A.ID
            JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
            JOIN documentosintesis D ON A.ID = D.ID_Persona
            WHERE (B.Aprobacion = 1 AND B.Estado = 4) OR B.Estado = 0 OR B.Estado = 5 OR (B.Estado = 2 AND B.Coordinacion = 'C#') OR B.Bloqueado = 1
        ");

        return datatables()->of($solicitudes)->toJson();
    }


    public function cargaragcoorjef(Request $request)
    {//AND name != 'Santiago Henao'
        $cargos = DB::select("
            SELECT DISTINCT users.id, users.agenciau, users.name, users.rol, users.codigo, agencias.NumAgencia
            FROM users
            LEFT JOIN agencias ON users.agenciau = agencias.NameAgencia
            WHERE users.agenciau != 'Asociacion Virtual'
            AND users.name != 'Jesus H BOLAÑOS'
            ORDER BY users.name ASC
        ");
        $gruposcreados = DB::select("SELECT * FROM grupos_otrabajo");
        foreach ($gruposcreados as $grupo) {

            if (strpos($grupo->integrantes, '"') !== false) {

                DB::table('grupos_otrabajo')
                    ->where('id', $grupo->id)
                    ->update(['integrantes' => str_replace('"', '', $grupo->integrantes)]);
            }
        }



        $gruposcreados = DB::select("
        SELECT
            g.id AS grupo_id,
            g.nombregrupo,
            GROUP_CONCAT(u.name ORDER BY u.name ASC SEPARATOR ', ') AS integrantes
        FROM
            grupos_otrabajo g
        LEFT JOIN
            users u ON FIND_IN_SET(u.id, REPLACE(REPLACE(g.integrantes, '[', ''), ']', '')) > 0
        GROUP BY
            g.id, g.nombregrupo
        ORDER BY
            g.nombregrupo ASC
        ");
        $cargosConNameAgencia = collect($cargos)->map(function ($cargo) {
            return (object) [
                'id' => $cargo->id,
                'agenciau' => $cargo->agenciau,
                'name' => $cargo->name,
                'rol' => $cargo->rol,
                'codigo' => $cargo->codigo,
                'NumAgencia' => $cargo->NumAgencia,
            ];
        });

        return view('Gerencia/otrabajo', [
            'cargos' => $cargosConNameAgencia,
            'gruposcreados' => $gruposcreados
        ]);



    }

    public function otrabajodatatable(Request $request)
    {
        if (session('email') == null) {
            return redirect()->route('login');
        }
        $solicitudes = DB::select("SELECT * FROM ordentrabajo");


        return datatables()->of($solicitudes)->toJson();

    }


    public function crearotrabajo(Request $request){
        $fechadeSolicitud = Carbon::now('America/Bogota');

        Carbon::setLocale('es');
        $fechaStringfechadeSolicitud = $fechadeSolicitud->translatedFormat('F d Y-H:i:s');


        $validardescripcion = DB::select('SELECT descripcion FROM ordentrabajo WHERE descripcion = ? AND asignado = ?', [$request->descripcion, $request->nombreempleado]);
        if(!empty($validardescripcion)){
            return back()->with("incorrecto", "<span class='fs-4'>Ya existe una <b>Orden de Trabajo</b> con la misma descripción</span>");
        }

        $id_insertado = DB::table('ordentrabajo')->insertGetId([
            'tipo' => $request->tipoorden,
            'fecha' => $fechaStringfechadeSolicitud,
            'descripcion' => $request->descripcion,
            'asignado' => $request->selectedPeopleInput2,
            'estado' => $request->estadopolitica,
        ]);


        $selectedPeople = json_decode($request->selectedPeopleInput2, true);

        if (is_array($selectedPeople)) {

            $placeholders = implode(',', array_fill(0, count($selectedPeople), '?'));


            $groups = DB::select("SELECT * FROM grupos_otrabajo WHERE nombregrupo IN ($placeholders)", $selectedPeople);


            $users = DB::select("SELECT id, email, name, celular FROM users WHERE name IN ($placeholders)", $selectedPeople);

            $debugData = [];


            foreach ($groups as $group) {
                if (!empty($group)) {
                    $integrantes = json_decode($group->integrantes, true);

                    DB::table('users')
                        ->whereIn('id', $integrantes)
                        ->increment('notificaciones', 1);

                    $idsString = implode(',', $integrantes);

                    $correos = DB::select("SELECT id, email, name, celular FROM users WHERE id IN ($idsString)");

                    $emails = array_map(function ($user) {
                        return [
                            'id' => $user->id,
                            'email' => $user->email,
                            'name' => $user->name,
                            'celular' => $user->celular ?? 0,
                        ];
                    }, $correos);

                    foreach ($emails as $emailData) {
                        SendCorreoJob::dispatch(
                            $emailData['email'],
                            $emailData['name'],
                            $id_insertado,
                            $fechaStringfechadeSolicitud
                        );


                        $debugData[] = [
                            'from' => 'group',
                            'integrantes' => $integrantes,
                            'emailData' => $emailData,
                        ];
                    }
                }
            }


            foreach ($users as $user) {
                SendCorreoJob::dispatch(
                    $user->email,
                    $user->name,
                    $id_insertado,
                    $fechaStringfechadeSolicitud
                );


                $debugData[] = [
                    'from' => 'users',
                    'userId' => $user->id,
                    'email' => $user->email,
                    'name' => $user->name,
                    'celular' => $user->celular ?? 0,
                ];
            }



        }




        // else{
        //     DB::table('users')->where('name', $request->nombreempleado)->increment('notificaciones', 1);
        //     $queryindividual = DB::select('SELECT * FROM users WHERE name = ?', [$request->nombreempleado]);
        //     $email = $queryindividual[0]->email;
        //     $nombrecompleto = $queryindividual[0]->name;
        //     Mail::to($email)->send(new CorreoInfo($nombrecompleto, $id_insertado, $fechaStringfechadeSolicitud));
        //     $querycelular = DB::select('SELECT celular FROM users WHERE name = ?', [$request->nombreempleado]);
        //     $celular = $querycelular[0]->celular;

        //     if(!empty($celular)){
        //         $url = 'https://aio2.sigmamovil.com/api/sms';

        //         $bearerToken = '10827|FDDjj6eKpiYZxk68a1XJZ2xPxNxNZwMN6EEWe0Rz16607cfa';

        //         $data = [
        //             "idSmsCategory" => 1,
        //             "name" => "".$id_insertado."otrabajo",
        //             "receiver" => [
        //                 [
        //                     "indicative" => 57,
        //                     "phone" => $celular,
        //                     "message" => "Estimado(a) ".$nombrecompleto.", le informamos que ha sido asignado(a) a una nueva orden de trabajo por parte de la DIRECCIÓN GENERAL, identificada con el número ".$id_insertado.", con fecha ".$fechaStringfechadeSolicitud."."
        //                 ]
        //             ],
        //             "dateNow" => 1,
        //             "type" => "lote",
        //             "track" => 0,
        //             "sendPush" => 0,
        //             "api" => 1,
        //             "notification" => 0,
        //             "email" => "email@email.com.co",
        //             "rne" => 0
        //         ];

        //         $response = Http::withToken($bearerToken)->post($url, $data);
        //     }
        // }



        return back()->with("correcto", "<span class='fs-4'>La Orden de Trabajo No. <span class='badge bg-primary fw-bold'>" . $id_insertado . "</span> fue asignada a <b>" . $request->nombreempleado."</b>.</span>");

    }
    public function store(Request $request)
    {
        $integrantesJson = json_encode($request->members);
        $validarnombre = DB::select('SELECT * FROM grupos_otrabajo WHERE nombregrupo = ?', [$request->name]);

        if (empty($validarnombre)) {
            $consultantes = DB::select('SELECT id FROM users WHERE rol = ?', ['D. de Agencia']);

            // Crear un array con los IDs de los consultantes
            $consultantesArray = [];
            foreach ($consultantes as $consultante) {
                $consultantesArray[] = $consultante->id;
            }

            // Combinar los miembros recibidos con los consultantes
            $integrantesArray = array_merge($request->members, $consultantesArray);

            // Convertir el array combinado a JSON
            $integrantesJson = json_encode($integrantesArray);

            // Insertar el nuevo grupo en la base de datos
            $id_insertado = DB::table('grupos_otrabajo')->insertGetId([
                'nombregrupo' => $request->name,
                'integrantes' => $integrantesJson,
            ]);
            return response()->json(['success' => true, 'id' => $id_insertado]);
        } else {
            $grupoExistente = $validarnombre[0];
            $integrantesExistentes = json_decode($grupoExistente->integrantes, true);
            $nuevosIntegrantes = json_decode($integrantesJson, true);

            $integrantesCombinados = array_unique(array_merge($integrantesExistentes, $nuevosIntegrantes));

            DB::table('grupos_otrabajo')->where('nombregrupo', $request->name)->update([
                'integrantes' => json_encode($integrantesCombinados)
            ]);

            return response()->json(['success2' => true]);
        }
    }



    public function loadGroups()
    {
        $grupos = DB::table('grupos_otrabajo')->get();

        $result = $grupos->map(function ($grupo) {
        $integrantesArray = json_decode($grupo->integrantes, true);

        $integrantesDetalles = DB::table('users')
        ->whereIn('users.id', $integrantesArray)
        ->leftJoin('agencias', 'users.agenciau', '=', 'agencias.NameAgencia')
        ->select('users.name', 'users.agenciau', 'users.codigo', 'users.rol', 'agencias.NumAgencia')
        ->get();


        $nombresIntegrantes = $integrantesDetalles->map(function ($integrante) {
            if ($integrante->rol === 'Consultante') {
                return $integrante->name . ' - ' . $integrante->agenciau . ' - ' . $integrante->NumAgencia;
            } elseif ($integrante->rol === 'Jefatura') {
                return $integrante->name . ' - ' . $integrante->agenciau. ' - ' . $integrante->codigo;
            } else {
                return $integrante->name . ' - ' . $integrante->agenciau;
            }
        });


            return [
                'id' => $grupo->id,
                'nombregrupo' => $grupo->nombregrupo,
                'integrantes' => $nombresIntegrantes
            ];
        });

        return response()->json($result);
    }




    public function destroy($id)
    {
        try {

            $deleted = DB::table('grupos_otrabajo')->where('id', $id)->delete();

            if ($deleted) {
                Log::info("Grupo con ID $id eliminado.");
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false], 404);
            }
        } catch (\Exception $e) {
            Log::error("Error al eliminar el grupo: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al eliminar el grupo'], 500);
        }
    }


    public function eliminarIntegrante($grupoId, $integranteId)
    {
        $parts = explode(' - ', $integranteId);
        $integranteName = $parts[0];
        $agenciau = $parts[1];

        $grupo = DB::table('grupos_otrabajo')
            ->where('id', $grupoId)
            ->first();

        $integrante = DB::table('users')
            ->where('name', $integranteName)
            ->where('agenciau', $agenciau)
            ->first();

        if (!$integrante) {
            return response()->json(['success' => false, 'message' => 'Integrante no encontrado']);
        }

        $integranteIdToDelete = $integrante->id;

        if ($grupo) {
            $integrantesArray = json_decode($grupo->integrantes, true);


            if (($key = array_search($integranteIdToDelete, $integrantesArray)) !== false) {
                unset($integrantesArray[$key]);

                DB::table('grupos_otrabajo')->where('id', $grupoId)->update([
                    'integrantes' => json_encode(array_values($integrantesArray))
                ]);

                return response()->json(['success' => true]);
            }
        }

        return response()->json(['success' => false, 'message' => 'Grupo o integrante no encontrado']);
    }


    public function buscarGrupos(Request $request)
    {
        $termino = $request->input('query');

        $grupos = DB::table('grupos_otrabajo')
                    ->where('nombregrupo', 'LIKE', '%' . $termino . '%')
                    ->get();

        return response()->json($grupos);
    }


    public function updateNombreGrupo(Request $request, $id)
    {
        $grupo = DB::table('grupos_otrabajo')->where('id', $id)->first();

        if ($grupo) {
            DB::table('grupos_otrabajo')->where('id', $id)->update(['nombregrupo' => $request->name]);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Grupo no encontrado']);
    }


    public function cambiarEstado(Request $request)
    {
        $estado = $request->input('estado');
        $id = $request->input('id');

        DB::table('ordentrabajo')
        ->where('id', $id)
        ->update([
            'estado' => $estado,
        ]);
        Log::info($id);

        return response()->json(['success' => true, 'estado' => $estado]);
    }





    public function obtenerGrupos()
    {
        $gruposcreados = DB::select('SELECT * FROM grupos_otrabajo');

        return response()->json([
            'grupos' => $gruposcreados,
        ]);
    }

    public function dagencia(Request $request)
    {
        if (session('email') == null) {
            return redirect()->route('login');
        }

        $agenciaU = session('agenciau');

        $solicitudes = DB::select("SELECT * FROM users WHERE rol = 'Consultante' AND activo = 1 ORDER BY agenciau ASC");

        $agenciasActivas = DB::select("SELECT * FROM agencias WHERE activo = 1 ORDER BY NameAgencia ASC");

        $conceptos = DB::select("SELECT * FROM concepto_autorizaciones ORDER BY Areas ASC");

        return datatables()->of($solicitudes)
            ->addColumn('agencia_comparada', function($row) use ($agenciasActivas) {
                foreach ($agenciasActivas as $agencia) {
                    if ($row->agenciau == $agencia->NameAgencia) {
                        return $agencia->NumAgencia;
                    }
                }
                return ''; // por si no encuentra coincidencia
            })
            ->toJson();
    }



    public function coordinaciones(Request $request)
    {
        if (session('email') == null) {
            return redirect()->route('login');
        }
        $agenciaU = session('agenciau');

        $agencias = DB::select("SELECT NumAgencia FROM autorizaciones");

        $solicitudes = DB::select("SELECT * FROM users WHERE rol = 'Coordinacion'  AND activo = 1 ORDER BY agenciau ASC");



        return datatables()->of($solicitudes)->toJson();
    }

    public function jefaturas(Request $request)
    {
        if (session('email') == null) {
            return redirect()->route('login');
        }
        $agenciaU = session('agenciau');

        $agencias = DB::select("SELECT NumAgencia FROM autorizaciones");

        $solicitudes = DB::select("SELECT * FROM users WHERE rol = 'Jefatura'  AND activo = 1 ORDER BY agenciau ASC");



        return datatables()->of($solicitudes)->toJson();
    }

    public function conceptos(Request $request)
    {
        if (session('email') == null) {
            return redirect()->route('login');
        }
        $agenciaU = session('agenciau');

        $agencias = DB::select("SELECT NumAgencia FROM autorizaciones");

        $solicitudes = DB::select("
            SELECT ID as ConceptoID, Concepto, Areas, Activo
            FROM concepto_autorizaciones
            WHERE Activo = 1
            ORDER BY ID ASC
        ");


        return datatables()->of($solicitudes)->toJson();
    }

    public function agenciastabla(Request $request)
    {
        if (session('email') == null) {
            return redirect()->route('login');
        }
        $solicitudes = DB::select("SELECT * FROM agencias WHERE activo = 1 ORDER BY NameAgencia ASC");



        return datatables()->of($solicitudes)->toJson();
    }

    //compact
    public function cargaragencias(Request $request)
    {
        $cargos = DB::select("SELECT DISTINCT id,agenciau,name FROM users WHERE rol = 'Consultante' ORDER BY name ASC");
        $agencias = DB::select("SELECT * FROM agencias WHERE activo = 1 ORDER BY NumAgencia ASC");


        $jefaturas = DB::select("SELECT DISTINCT agenciau FROM users WHERE rol = 'Jefatura'");
        $codigos = DB::select("SELECT DISTINCT codigo FROM users WHERE rol = 'Jefatura'");
        $coordinaciones = DB::select("SELECT DISTINCT agenciau FROM users WHERE rol = 'Coordinacion'");
        $areas = DB::select("SELECT DISTINCT Areas FROM concepto_autorizaciones ORDER BY Areas ASC");
        return view('Gerencia/admin', ['cargos' => $cargos, 'agencias' => $agencias, 'jefaturas' => $jefaturas, 'coordinaciones' => $coordinaciones, 'codigos' => $codigos, 'areas' => $areas]);

    }


    public function crearusuario(Request $request){
        $tipocreacion = $request->crear;
        $nombre = $request->nombre;
        $correo = $request->correo;
        $password = $request->password;


        $validacioncorreo = DB::select('select * from users WHERE email = ?',[$correo]);

        if (!empty($validacionnombre) || !empty($validacioncorreo)) {

            if (!empty($validacioncorreo) && isset($validacioncorreo[0]->email)) {
                return back()->with("incorrecto", "<span class='fs-4'>Ya existe un usuario vinculado al correo <b>".$correo."</b></span>");
            }
        }


        if($tipocreacion == "crearDAgencia"){


            $id_insertado = DB::table('users')->insertGetId([
                'name' => $nombre,
                'rol' => 'Consultante',
                'agenciau' => $request->agenciaDAgencia,
                'email' => $correo,
                'password' => bcrypt($password),
                'activo' => 1
            ]);
            return back()->with("correcto", "<span class='fs-4'>Se creo satisfactoriamente el director de agencia o auxiliar <br>(<span class='badge bg-primary fw-bold'>".$nombre." - ".$request->agenciaDAgencia."</span>).</span>");
        }else if($tipocreacion == "crearCoord"){
            $selectedPeopleString = $request->input('selectedPeople');

            $selectedPeople = json_decode($selectedPeopleString, true);

            $id_insertado = DB::table('users')->insertGetId([
                'name' => $nombre,
                'rol' => 'Coordinacion',
                'agenciau' => 'Coordinacion '.$request->selectcoordinacion,
                'agencias_id' => json_encode($selectedPeople),
                'email' => $correo,
                'password' => bcrypt($password),
                'activo' => 1
            ]);
            return back()->with("correcto", "<span class='fs-4'>Se creo satisfactoriamente la coordinación ".$request->selectcoordinacion." <br>(<span class='badge bg-primary fw-bold'>".$nombre." - ".$request->selectcoordinacion."</span>).</span>");
        }else if($tipocreacion == "crearJefatura"){
            $validacionagenciau = DB::select("SELECT agenciau FROM users WHERE LOWER(agenciau) LIKE LOWER(?)", ["%{$request->jefatura}%"]);
            $jefatura = $request->jefatura;
            $codigo = $request->codigo;
            if(!empty($validacionagenciau)){
                $jefatura = $validacionagenciau[0]->agenciau;
            }
            $id_insertado = DB::table('users')->insertGetId([
                'name' => $nombre,
                'rol' => 'Jefatura',
                'agenciau' => $jefatura,
                'codigo' => $codigo,
                'email' => $correo,
                'password' => bcrypt($password),
                'activo' => 1
            ]);
            return back()->with("correcto", "<span class='fs-4'>Se creo satisfactoriamente la jefatura <br>(<span class='badge bg-primary fw-bold'>".$nombre." - ".$jefatura."</span>).</span>");
        }else if($tipocreacion == "crearAgencia"){
            $consultaagencia = DB::table("agencias")->where("NameAgencia", $request->agencianombre)->where("activo", 1)->count();
            $consultacentrocosto = DB::table("agencias")->where("NumAgencia", $request->centrocosto)->where("activo", 1)->count();

            if ($consultaagencia > 0) {
                return back()->with("incorrecto", "<span class='fs-4'>La agencia <b>" . $request->agencianombre . "</b> ya existe!</span>");
            }else if($consultacentrocosto > 0){
                return back()->with("incorrecto", "<span class='fs-4'>El centro de costo <b>" . $request->centrocosto . "</b> ya existe!</span>");
            }


            $id_insertado = DB::table('agencias')->insertGetId([
                'NameAgencia' => $request->agencianombre,
                'NumAgencia' => $request->centrocosto,
            ]);
            return back()->with("correcto", "<span class='fs-4'>Se creo satisfactoriamente la agencia <br>(<span class='badge bg-primary fw-bold'>".$request->agencianombre." - ".$request->centrocosto."</span>).</span>");
        }else if($tipocreacion == "crearConcepto"){
            $nombreConcepto = $request->concepto;
            $area = $request->area;
            $codigoArea = $request->codigoarea;

            $existeConcepto = DB::table('concepto_autorizaciones')
                ->where('Areas', $area)
                ->get();

            if ($codigoArea != null){
                $existeCodigoArea = DB::table('concepto_autorizaciones')
                    ->where('No', $codigoArea)
                    ->get();

                if ($existeCodigoArea->isNotEmpty()) {
                    return back()->with("incorrecto", "<span class='fs-4'>Ya existe un <b>ÁREA</b> con el código <b>" . $codigoArea . "</b> vinculado a: <b>" . $existeCodigoArea[0]->Areas . "</b></span>");
                }
            }


            if ($existeConcepto->isNotEmpty()) {
                $codigoArea = $existeConcepto[0]->No;
            }else{
                $codigoArea = 00;
            }

            if($area == 'OTRO'){
                $area = $request->otroArea;
                $codigoArea = $request->codigoarea;
            }

            $id_insertado = DB::table('concepto_autorizaciones')->insertGetId([
                'Concepto' => $nombreConcepto,
                'Areas' => $area,
                'No' => $codigoArea,
            ]);
            return back()->with("correcto", "<span class='fs-4'>Se creo satisfactoriamente el concepto <br>(<span class='badge bg-primary fw-bold'>".$nombreConcepto." - ".$area."</span>).</span>");
        }



    }

    public function eliminarUsuario($id){


        $nombreauditoria = session('name');
        $rol = session('rol');
        date_default_timezone_set('America/Bogota');
        $fechaHoraActual = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        $agencia = session('agenciau');
        $login = DB::insert("INSERT INTO auditoria (Hora_login, Usuario_nombre, Usuario_Rol, AgenciaU, Acción_realizada, Hora_Accion, Cedula_Registrada, cerro_sesion, IP) VALUES (?, ?, ?, ?, 'SeEliminoUsuarioenelpaneladmin', ?, ?, ?, ?)", [
            null,
            $nombreauditoria,
            $rol,
            $agencia,
            $fechaHoraActual,
            $id,
            null,
            $ip
        ]);
        $existeAgencia = DB::table('agencias')->where('NameAgencia', $id)->count();
        $existeConcepto = DB::table('concepto_autorizaciones')->where('ID', $id)->count();

        $usuarioRol = DB::select("SELECT agenciau, name from users WHERE id = ?",[$id]);

        if($existeConcepto>0){
            $existeConcepto = DB::table('concepto_autorizaciones')->where('ID', $id)->get();


            DB::table('concepto_autorizaciones')
                ->where('ID', $id)
                ->update([
                    'activo' => 0
            ]);
            return back()->with("correcto", "<span class='fs-4'>Se eliminó satisfactoriamente el concepto (<span class='fw-bold'>".$id."</span>).</span>");
        }else if($existeAgencia>0){
            $existeAgencia = DB::table('agencias')->where('NameAgencia', $id)->get();
            $idagencia = $existeAgencia[0]->NumAgencia;

            DB::table('users')
            ->where('agenciau', $id)
            ->update([
                'password' => bcrypt("bloqueada")
            ]);


            DB::table('agencias')
                ->where('NameAgencia', $id)
                ->update([
                    'activo' => 0
                ]);


            DB::table('users')
            ->whereJsonContains('agencias_id', $idagencia)
            ->update([
                'agencias_id' => DB::raw("JSON_REMOVE(agencias_id, JSON_UNQUOTE(JSON_SEARCH(agencias_id, 'one', '$idagencia')))")
            ]);
            return back()->with("correcto", "<span class='fs-4'>Se eliminó satisfactoriamente la agencia<br>(<span class='badge bg-primary fw-bold'>".$id."</span>).</span>");
        }else{

            DB::table('users')
            ->where('id', $id)
            ->update([
                'activo' => 0
            ]);

            $grupos = DB::table('grupos_otrabajo')
            ->whereRaw("JSON_SEARCH(integrantes, 'one', ?) IS NOT NULL", [$id])
            ->get();




            foreach ($grupos as $grupo) {
                $integrantes = json_decode($grupo->integrantes, true);

                if (($key = array_search($id, $integrantes)) !== false) {
                    unset($integrantes[$key]);
                }


                $nuevoIntegrantes = array_values($integrantes);
                $nuevoJson = json_encode($nuevoIntegrantes);

                DB::table('grupos_otrabajo')
                    ->where('id', $grupo->id)
                    ->update(['integrantes' => $nuevoJson]);
            }


            return back()->with("correcto", "<span class='fs-4'>Se eliminó satisfactoriamente el usuario <b>".$usuarioRol[0]->name."</b> <br>(<b>Rol:</b> <span class='badge bg-primary fw-bold'>".$usuarioRol[0]->agenciau."</span>).</span>");
        }



    }

    public function eliminarConcepto($id, $area)
    {
        $nombreauditoria = session('name');
        $rol = session('rol');
        date_default_timezone_set('America/Bogota');
        $fechaHoraActual = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        $agencia = session('agenciau');
        $login = DB::insert("INSERT INTO auditoria (Hora_login, Usuario_nombre, Usuario_Rol, AgenciaU, Acción_realizada, Hora_Accion, Cedula_Registrada, cerro_sesion, IP) VALUES (?, ?, ?, ?, 'SeEliminoConceptoenelpaneladmin', ?, ?, ?, ?)", [
            null,
            $nombreauditoria,
            $rol,
            $agencia,
            $fechaHoraActual,
            $id,
            null,
            $ip
        ]);


        DB::table('concepto_autorizaciones')
            ->where('Areas', $area)
            ->update([
                'Areas' => 'GLOBAL',
                'No' => 00,
            ]);
        return back()->with("correcto", "<span class='fs-4'>Se eliminó satisfactoriamente el ÁREA (<span class='fw-bold'>".$area."</span>).</span>");
    }

    public function guardarcoordinacion(Request $request)
    {
        $integrantesJson = json_encode($request->members);


        $validarnombre = DB::select('SELECT * FROM grupos_otrabajo WHERE nombregrupo = ?', [$request->name]);

        if (empty($validarnombre)) {
            $consultantes = DB::select('SELECT id FROM users WHERE rol = ?', ['D. de Agencia']);


            $consultantesArray = [];
            foreach ($consultantes as $consultante) {
                $consultantesArray[] = $consultante->id;
            }


            $integrantesArray = array_merge($request->members, $consultantesArray);


            $integrantesJson = json_encode($integrantesArray);


            $id_insertado = DB::table('grupos_otrabajo')->insertGetId([
                'nombregrupo' => $request->name,
                'integrantes' => $integrantesJson,
            ]);
            return response()->json(['success' => true, 'id' => $id_insertado]);
        } else {
            $grupoExistente = $validarnombre[0];
            $integrantesExistentes = json_decode($grupoExistente->integrantes, true);
            $nuevosIntegrantes = json_decode($integrantesJson, true);

            $integrantesCombinados = array_unique(array_merge($integrantesExistentes, $nuevosIntegrantes));

            DB::table('grupos_otrabajo')->where('nombregrupo', $request->name)->update([
                'integrantes' => json_encode($integrantesCombinados)
            ]);

            return response()->json(['success2' => true]);
        }
    }


    public function editarusuario(Request $request){
        $nombre = $request->nombre;
        $agencia = $request->agencia;
        $celular= $request->celular;
        $contrasena= $request->contrasena;
        $correo = $request->correo;
        $agencianame = $request->agencianame;
        $centrocosto = $request->cc;
        $id = $request->id;
        $nombreConcepto = $request->concepto;
        $area = $request->area;
        $codigoArea = $request->codigoarea;

        $consultaRol = DB::select("SELECT * FROM users WHERE email = ?", [$correo]);

            if ($area != null || $nombreConcepto != null) {
                        $consultaConcepto = DB::table("concepto_autorizaciones")
                            ->where("Concepto", $nombreConcepto)
                            ->first();

                        if ($consultaConcepto && $nombreConcepto != $consultaConcepto->Concepto) {
                            return back()->with("incorrecto", "<span class='fs-4'>El concepto <b>" . $nombreConcepto . "</b> ya existe!</span>");
                        } else {


                        //CONTINUAR Y LUEGO FALTA CLICK CUANDO LE DEL SE VAYA A LA VALIDACION
                            $existeConcepto = DB::table('concepto_autorizaciones')
                                ->where('Areas', $area)
                                ->get();


                            if ($codigoArea != null){
                                $existeCodigoArea = DB::table('concepto_autorizaciones')
                                    ->where('No', $codigoArea)
                                    ->get();

                                if ($existeCodigoArea->isNotEmpty()) {
                                    return back()->with("incorrecto", "<span class='fs-4'>Ya existe un <b>ÁREA</b> con el código <b>" . $codigoArea . "</b> vinculado a: <b>" . $existeCodigoArea[0]->Areas . "</b></span>");
                                }
                            }


                            if ($existeConcepto->isNotEmpty()) {
                                $codigoArea = $existeConcepto[0]->No;
                            }else{
                                $codigoArea = 00;
                            }

                            if($area == 'OTRO'){
                                $area = $request->otroArea;
                                $codigoArea = $request->codigoarea;
                            }



                            DB::table('concepto_autorizaciones')
                            ->where('ID', $id)
                            ->update([
                            'Concepto' => $nombreConcepto,
                            'Areas' => $area,
                            'No' => $codigoArea,
                    ]);
                    return back()->with("correcto", "<span class='fs-4'>Se actualizó satisfactoriamente el concepto(<b>".$nombreConcepto."</b>).</span>");
                }
            }else if($agencianame != null || $centrocosto != null){
            $consultaagencia = DB::table("agencias")->where("NameAgencia", $agencianame)->where("activo", 1)->count();
            $consultacentrocosto = DB::table("agencias")->where("NumAgencia", $centrocosto)->where("activo", 1)->count();


            if ($consultaagencia > 0) {
                return back()->with("incorrecto", "<span class='fs-4'>La agencia <b>" . $agencianame . "</b> ya existe!</span>");
            }else if($consultacentrocosto > 0){
                return back()->with("incorrecto", "<span class='fs-4'>El centro de costo <b>" . $centrocosto . "</b> ya existe!</span>");
            }


            DB::table('agencias')
            ->where('ID', $id)
            ->update([
                'NameAgencia' => $agencianame,
                'NumAgencia' => $centrocosto,
            ]);

            return back()->with("correcto", "<span class='fs-4'>Se actualizó satisfactoriamente la agencia <br>(<span class='badge bg-primary fw-bold'>".$agencianame." - ".$centrocosto."</span>).</span>");
        }else{
            $rol = $consultaRol[0]->rol;
            $codigodpto = null;
            if($rol == 'Jefatura'){
                $agencia = $request->jefatura;
                $codigodpto = $request->codigodpto;
            }else if($rol == 'Coordinacion'){
                $agencia = $request->coordinacion2;
            }

            $agenciasConCodigos = [
                'Reporte Bogota' => 13,
                'Juridico Zona Centro' => 2150,
                'Juridico Zona Norte' => 2250,
                'Juridico Zona Sur' => 2350,
                'Gerencia General' => 28,
                'Monitoreo' => null,
                'Tesoreria' => 15,
                'Contabilidad' => 18,
                'Sistemas' => 19,
                'Talento Humano' => 10,
                'Auditoria Interna' => 12,
                'Reporte Cali' => 14,
                'Meridian' => null,
                'Seguros' => 2300,
                'Asesora M-76' => 2400,
                'Fondo Solidaridad' => null,
                'Oficial de Cumplimiento' => 2805,
                'Programacion' => null,
                'Ficidet' => 2500,
            ];


            foreach ($agenciasConCodigos as $nombreAgencia => $codigo) {
                DB::table('users')
                    ->where('agenciau', $nombreAgencia)
                    ->update(['codigo' => $codigo]);
            }

            if($contrasena == null){
                DB::table('users')
                ->where('email', $correo)
                ->update([
                    'name' => $nombre,
                    'agenciau' => $agencia,
                    'codigo' => $codigodpto ?: null,
                    'celular' => $celular,
                    'password' => bcrypt($contrasena),
                    'agencias_id' => $request->agencias_hidden ?: null
                ]);
            }else{
                DB::table('users')
                ->where('email', $correo)
                ->update([
                    'name' => $nombre,
                    'agenciau' => $agencia,
                    'codigo' => $codigodpto ?: null,
                    'celular' => $celular,
                    'password' => bcrypt($contrasena),
                    'agencias_id' => $request->agencias_hidden ?: null
                ]);
            }
                return back()->with("correcto", "<span class='fs-4'>Se actualizó satisfactoriamente el usuario <br>(<span class='badge bg-primary fw-bold'>".$nombre." - ".$agencia."</span>).</span>");
            }





    }



    public function contarsolicitudesotrabajo(Request $request)
    {

            $permanentes = DB::table('ordentrabajo')->where('estado', "PERMANENTE")->count();
            $laboracumplir = DB::table('ordentrabajo')->where('estado', "LABOR A CUMPLIR")->count();
            $temporales = DB::table('ordentrabajo')->where('estado', "TEMPORAL")->count();
            $aplazadas = DB::table('ordentrabajo')->where('estado', "APLAZADA")->count();
            $derogadas = DB::table('ordentrabajo')->where('estado', "DEROGADA")->count();
            $anuladas = DB::table('ordentrabajo')->where('estado', "ANULAR")->count();
            $terminadas = DB::table('ordentrabajo')->where('estado', "TERMINADA")->count();
            $tareas = DB::table('ordentrabajo')->where('tipo', "tarea")->count();
            $politicas = DB::table('ordentrabajo')->where('tipo', "politica")->count();


            $total = $permanentes + $laboracumplir + $temporales + $aplazadas + $derogadas + $anuladas + $terminadas;


            $porcentaje_permanentes = ($total != 0) ? ($permanentes / $total * 100) : 0;
            $porcentaje_laboracumplir = ($total != 0) ? ($laboracumplir / $total * 100) : 0;
            $porcentaje_temporales = ($total != 0) ? ($temporales / $total * 100) : 0;
            $porcentaje_aplazadas = ($total != 0) ? ($aplazadas / $total * 100) : 0;
            $porcentaje_derogadas = ($total != 0) ? ($derogadas / $total * 100) : 0;
            $porcentaje_anuladas = ($total != 0) ? ($anuladas / $total * 100) : 0;
            $porcentaje_terminadas = ($total != 0) ? ($terminadas / $total * 100) : 0;
            $porcentaje_tareas = ($total != 0) ? ($tareas / $total * 100) : 0;
            $porcentaje_politicas = ($total != 0) ? ($politicas / $total * 100) : 0;


            $porcentaje_permanentes_con_decimales = round($porcentaje_permanentes, 2);
            $porcentaje_laboracumplir_con_decimales = round($porcentaje_laboracumplir, 2);
            $porcentaje_temporales_con_decimales = round($porcentaje_temporales, 2);
            $porcentaje_aplazadas_con_decimales = round($porcentaje_aplazadas, 2);
            $porcentaje_derogadas_con_decimales = round($porcentaje_derogadas, 2);
            $porcentaje_anuladas_con_decimales = round($porcentaje_anuladas, 2);
            $porcentaje_terminadas_con_decimales = round($porcentaje_terminadas, 2);
            $porcentaje_tareas_con_decimales = round($porcentaje_tareas, 2);
            $porcentaje_politicas_con_decimales = round($porcentaje_politicas, 2);


            $suma_porcentajes = $porcentaje_permanentes_con_decimales + $porcentaje_laboracumplir_con_decimales + $porcentaje_temporales_con_decimales + $porcentaje_aplazadas_con_decimales + $porcentaje_derogadas_con_decimales + $porcentaje_anuladas_con_decimales + $porcentaje_terminadas_con_decimales;


            $nombresAgencia = DB::table('autorizaciones')
            ->select('NomAgencia')
            ->distinct()
            ->orderBy('NomAgencia', 'asc')
            ->get();

            return view('Gerencia/otraestadisticas', [
                'permanentes' => $permanentes,
                'laboracumplir' => $laboracumplir,
                'temporales' => $temporales,
                'aplazadas' => $aplazadas,
                'derogadas' => $derogadas,
                'anuladas' => $anuladas,
                'terminadas' => $terminadas,
                'tareas' => $tareas,
                'politicas' => $politicas,
                'total' => $total,
                'porcentaje_permanentes' => $porcentaje_permanentes_con_decimales,
                'porcentaje_laboracumplir' => $porcentaje_laboracumplir_con_decimales,
                'porcentaje_temporales' => $porcentaje_temporales_con_decimales,
                'porcentaje_aplazadas' => $porcentaje_aplazadas_con_decimales,
                'porcentaje_derogadas' => $porcentaje_derogadas_con_decimales,
                'porcentaje_anuladas' => $porcentaje_anuladas_con_decimales,
                'porcentaje_terminadas' => $porcentaje_terminadas_con_decimales,
                'porcentaje_tareas' => $porcentaje_tareas_con_decimales,
                'porcentaje_politicas' => $porcentaje_politicas_con_decimales,
                'suma_porcentajes' => $suma_porcentajes,
                'nombresAgencia' => $nombresAgencia
            ]);


    }

    public function getIntegrantes($id)
    {
        $grupo = DB::table('grupos_otrabajo')
            ->where('nombregrupo', $id)
            ->first();


        if ($grupo) {
            $integrantesIds = json_decode($grupo->integrantes);

            if($id == "D. de Agencia"){
                $nombres = DB::table('users')
                ->join('agencias', 'users.agenciau', '=', 'agencias.NameAgencia')
                ->whereIn('users.id', $integrantesIds)
                ->select(DB::raw("CONCAT(COALESCE(users.name, ''), ' - ', COALESCE(users.agenciau, ''), ' - ', COALESCE(agencias.NumAgencia, '')) as detalle"))
                ->pluck('detalle');

            }else{
                $nombres = DB::table('users')
                ->whereIn('id', $integrantesIds)
                ->select(DB::raw("CONCAT(COALESCE(name, ''), ' - ', COALESCE(agenciau, ''),
                    CASE WHEN codigo IS NOT NULL THEN CONCAT(' - ', codigo) ELSE '' END) as detalle"))
                ->pluck('detalle');

            }


            $integrantes = DB::table('grupos_otrabajo')
                ->where('integrantes', $grupo->integrantes)
                ->get();

            return response()->json($nombres);
        } else {
            return response()->json(['error' => 'Grupo no encontrado'], 404);
        }
    }


    public function obtenerAgencias($id)
    {

        $usuario = DB::table('users')->where('id', $id)->first();

        if ($usuario && $usuario->agencias_id) {

            $agenciasIds = json_decode($usuario->agencias_id, true);


            if (is_array($agenciasIds)) {

                $agencias = DB::table('agencias')
                    ->whereIn('NumAgencia', $agenciasIds)
                    ->select('NumAgencia', 'NameAgencia')
                    ->get();


                return response()->json($agencias);
            }
        }


        return response()->json([]);
    }


    public function obtenerAgenciasSelect($id)
    {
        $usuario = DB::table('users')->where('id', $id)->first();

        if ($usuario && $usuario->agencias_id) {

            $agenciasIds = json_decode($usuario->agencias_id, true);

            if (is_array($agenciasIds)) {


                $agenciasExistentes = DB::table('agencias')
                    ->whereIn('NumAgencia', $agenciasIds)
                    ->pluck('NumAgencia')
                    ->toArray();


                $agenciasRestantes = DB::table('agencias')
                    ->whereNotIn('NumAgencia', $agenciasIds)
                    ->get();


                return response()->json($agenciasRestantes);
            }
        }

        return response()->json([]);
    }

}
