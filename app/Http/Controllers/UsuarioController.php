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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use App\Models\User;
use App\Models\BugReport;
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
        $usuariosEnviara = DB::select("SELECT * FROM users WHERE rol = 'Consultante' OR rol = 'Coordinacion' OR rol = 'Jefatura' ORDER BY codigo ASC");

        $userId = session('id');
        $userFiltrado = User::find($userId);
        $name = $userFiltrado->name;
        $celular = $userFiltrado->celular;
        Carbon::setLocale('es');
        // Mes anterior
        $mesAnterior = Carbon::now('America/Bogota')->subMonth();
        $mesActual = Carbon::now('America/Bogota');
        // Fecha de corte = último día del mes anterior
        $fechaCorteMesAnterior = $mesAnterior->copy()->endOfMonth();
        $fechaCorteTexto = $fechaCorteMesAnterior->translatedFormat('M d Y');
        $temp = $fechaCorteMesAnterior->translatedFormat('M d Y');
        $fechaCorteTexto = ucfirst(str_replace('.', '', $temp));

        $fechaCorteMesActual = $mesActual->copy()->endOfMonth();
        $fechaCorteActualTexto = $fechaCorteMesActual->translatedFormat('M d Y');
        $temp2 = $fechaCorteMesActual->translatedFormat('M d Y');
        $fechaCorteActualTexto = ucfirst(str_replace('.', '', $temp2));

        // Buscar el último ID (consecutivo) del mes anterior en historialestado
        $ultimoConsecutivoMesAnterior = DB::table('historialestado')
            ->whereMonth('Fecha', $mesAnterior->month)
            ->whereYear('Fecha', $mesAnterior->year)
            ->max('ID_Autorizacion');

        $ultimoConsecutivoMesActual = DB::table('historialestado')
            ->whereMonth('Fecha', $mesActual->month)
            ->whereYear('Fecha', $mesActual->year)
            ->max('ID_Autorizacion');

        return view('Usuario/solicitudes', [
            'user' => $user,
            'grupos' => $grupos,
            'convencion' => $convencion,
            'usuariosEnviara' => $usuariosEnviara,
            'corteMesAnterior' => $fechaCorteTexto,
            'ultimoConsecutivoMesAnterior' => $ultimoConsecutivoMesAnterior,
            'ultimoConsecutivoMesActual' => $ultimoConsecutivoMesActual,
            'fechaCorteActualTexto' => $fechaCorteActualTexto,
        ]);
    }

    public function bugs(){
        $bugs = DB::table('bug_reports')
        ->get();

        return datatables()->of($bugs)->toJson();
    }

    public function cambiarestado(Request $request, $id)
    {
        $accion = $request->input('accion');


        DB::table('bug_reports')
            ->where('id', $id)
            ->update(['status' => $accion]);


        return back()->with("correcto", "<span class='fs-4'>El bug No. <span class='badge bg-primary fw-bold'>" . $id . "</span> fue actualizado a <strong>" . $accion . "</strong>.</span>");
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

            $agencia = session('agenciau');
            if (preg_match('/Coordinacion (\d+)/', $agencia, $matches)) {
                $numAgencia = 'C' . $matches[1];
            } else {
                $numAgencia = null; // o un valor por defecto
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
                return response()->json([
                    'success' => false,
                    'message' => "<span class='fs-4'>La autorización No. <span class='badge bg-primary fw-bold'>".$consultabloqueado[0]->ID_Autorizacion."</span> se encuentra <span class='text-danger fw-bold'>BLOQUEADA</span>. Por favor contactar con <span class='fw-bold'>Dirección General</span>.</span>"
                ]);
            }
        }

        $autorizacionesCorregir = DB::select("
            SELECT DISTINCT ult.ID_Autorizacion
            FROM
                (
                    -- ÚLTIMO ESTADO DE LA AUTORIZACIÓN
                    SELECT h1.ID_Autorizacion
                    FROM historialestado h1
                    INNER JOIN (
                        SELECT ID_Autorizacion, MAX(ID) AS ultimo_id
                        FROM historialestado
                        GROUP BY ID_Autorizacion
                    ) x ON h1.ID = x.ultimo_id
                    WHERE h1.Estado = ?
                ) ult
            INNER JOIN
                (
                    -- PRIMER REGISTRO (AGENCIA ORIGINAL)
                    SELECT h2.ID_Autorizacion
                    FROM historialestado h2
                    INNER JOIN (
                        SELECT ID_Autorizacion, MIN(ID) AS primer_id
                        FROM historialestado
                        GROUP BY ID_Autorizacion
                    ) y ON h2.ID = y.primer_id
                    WHERE h2.NomArea = ?
                ) pri
            ON ult.ID_Autorizacion = pri.ID_Autorizacion
        ", ['CORREGIR', $agenciaU]);
        $ids = collect($autorizacionesCorregir)
            ->pluck('ID_Autorizacion')
            ->unique()
            ->values()
            ->implode('</span> <span class="badge bg-primary fw-bold">');


        if (!empty($autorizacionesCorregir)) {

            $ids = collect($autorizacionesCorregir)
                ->pluck('ID_Autorizacion')
                ->unique()
                ->values()
                ->implode('</span> <span class="badge bg-primary fw-bold">');

            return response()->json([
                'success' => false,
                'message' => "
                    <span class='fs-5'>
                        La(s) autorizacion(es) 
                        <span class='badge bg-primary fw-bold'>
                            {$ids}
                        </span>
                        se encuentran actualmente en estado 
                        <span class='text-primary fw-bold'>CORREGIR</span>.
                        <br><br>
                        Para continuar con la creación de una nueva solicitud, 
                        por favor <span class='fw-bold'>reutilice</span> una autorización existente 
                        o <span class='fw-bold'>realice las correcciones necesarias</span> sobre las solicitudes pendientes.
                    </span>
                "
            ], 409);
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
            return response()->json([
                'success' => false,
                'message' => 'No se subió ningún archivo.'
            ]);
        }

        $file = $request->file('SoporteScore');
        $filename = $file->getClientOriginalName();

        // Verificar si el archivo es PDF
        if ($file->getClientOriginalExtension() != 'pdf' && $file->getClientOriginalExtension() != 'PDF') {
            return response()->json([
                'success' => false,
                'message' => 'Solo se pueden subir archivos PDF.'
            ]);
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
            return response()->json([
                'success' => false,
                'message' => 'Error al subir el archivo.'
            ]);
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

        return response()->json([
            'success' => true,
            'message' => "<span class='fs-4'>La autorización No. 
            <span class='fs-4'>La autorización No. <span class='badge bg-primary fw-bold'>" . $id_insertado . "</span> está en trámite.</span>"
        ]);



    }
    
    private function procesarAutorizacion(&$aut)
    {
            $historial = DB::table('autorizaciones_2 AS B')
                ->join('historialestado AS H', 'H.ID_Autorizacion', '=', 'B.ID')
                ->leftJoin('persona AS A', 'A.ID', '=', 'H.ID_Persona')
                ->leftJoin('concepto_autorizaciones AS C', 'H.ID_Concepto', '=', 'C.ID')
                ->leftJoin('documentosintesis AS D', 'A.ID', '=', 'D.ID_Persona')
                ->leftJoin('users AS U', function($join){
                    $join->on(DB::raw("CONVERT(U.name USING utf8mb4) COLLATE utf8mb4_general_ci"),
                            '=',
                            DB::raw("CONVERT(H.Nombre USING utf8mb4) COLLATE utf8mb4_general_ci"));
                }) // ✅ compara sin error
                ->where('H.ID_Autorizacion', $aut->IDAutorizacion)
                ->select([
                    'H.*',
                    'A.Score',
                    'D.FechaInsercion',
                    'C.Concepto',
                    'U.name AS NombreUsuario',
                    'U.codigo AS CodigoUsuario'
                ])
                ->orderBy('H.ID', 'asc')
                ->get();

            // Adjuntamos el historial completo al objeto
            $aut->historial = $historial;

            // 🔹 Primer historial (más antiguo)
            $primer = $historial->first();
            if ($primer) {

                $aut->CodigoUsuario = $primer->CodigoUsuario;
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

            $ultimaRemitidoCorregir = $historial
                ->filter(function ($h) {
                    $estado = strtoupper(trim($h->Estado ?? ''));
                    return in_array($estado, ['REMITIDO', 'REMITIDOCORREGIR', 'STAND BY', 'BLOQUEADO', 'APROBADO']);
                })
                ->last();

            $aut->ultimaRemitidoCorregir = $ultimaRemitidoCorregir ? $ultimaRemitidoCorregir->FechaString : null;

            $ultimoConceptoID = DB::table('historialestado')
                ->where('ID_Autorizacion', $aut->IDAutorizacion)
                ->where('ID_Concepto', '=', '17')
                ->orderByDesc('ID')
                ->first();

            $aut->UltimoConceptoID = $ultimoConceptoID
                ? $ultimoConceptoID->ID_Concepto
                : null;

            $ultimoEnviadoa = DB::table('historialestado')
                ->where('ID_Autorizacion', $aut->IDAutorizacion)
                ->where('Estado', '=', 'ENVIADO')
                ->orderByDesc('ID')
                ->first();

            $aut->ultimoEnviadoa = $ultimoEnviadoa
                ? $ultimoEnviadoa->Nombre
                : null;

            $estadosSinFiltrar = [
                'TERMINADO',
                'ACLARAR',
                'ENCARGARSE',
                'PROCEDER',
                'SOLUCIONAR',
                'QUE PASO',
                'RECIBIDOCONFIRMADO',
                'RECIBIDO',


            ];

            // 1. Filtrar estados que SI deben mostrar solo el último
            $filtrar = $historial->filter(function ($item) use ($estadosSinFiltrar) {
                return !in_array($item->Estado, $estadosSinFiltrar);
            });

            // 2. Filtrar estados que deben mostrar TODOS
            $sinFiltrar = $historial->filter(function ($item) use ($estadosSinFiltrar) {
                return in_array($item->Estado, $estadosSinFiltrar);
            });


            // 1. Buscar el último DONE o REMITIDOCORREGIR
            $ultimoClave = $historial
                ->whereIn('Estado', ['DONE', 'REMITIDOCORREGIR', 'REMITIDO', 'REMITIDOCONFIRMADO', 'TRÁMITE'])
                ->sortByDesc('ID')
                ->first();

            // Si NO hay DONE ni REMITIDOCORREGIR → devolver vacío o todo (tú decides)
            if (!$ultimoClave) {
                $desdeClave = collect(); // vacío
            } else {

                // 2. Tomar todos los registros desde ese DONE/REMITIDOCORREGIR en adelante
                $desdeClave = $historial->filter(function ($item) use ($ultimoClave) {
                    return $item->ID >= $ultimoClave->ID;
                })->values();
            }

            $ultimoSec = DB::table('historialestado')
            ->where('ID_Autorizacion', $aut->IDAutorizacion)
            ->max('SEC');
            //traeria la ultima SEC asignada una vez que se aprueba.
            $aut->UltimaSECautorizacion = $ultimoSec ? $ultimoSec : null;

            // Resultado final
            $aut->historialEstadosUnicos = $desdeClave;
    }



    public function solicitudes(Request $request)
    {
        if (!session('email')) {
            return redirect()->route('login');
        }

        $agenciaU = session('agenciau');
        $rol = session('rol');

        $autorizacion = $request->search_term;

        if (!empty($autorizacion)) {

                if ($rol == "Gerencia") {

                    $query = DB::table('autorizaciones_2 AS B')
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
                        ->where('B.ID', $autorizacion)
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
                        ]);

                                // ✅ Ejecutar consulta
                        $autorizaciones = $query->get();

                }else if($rol == "Coordinacion"){
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
                                ->where('B.ID', $autorizacion)
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
                        // Excluir autorizaciones cuyo último estado global sea "APROBADO" o "STAND BY"
                        ->whereExists(function ($query) {
                            $query->select(DB::raw(1))
                                ->from('historialestado AS H2')
                                ->whereRaw('H2.ID_Autorizacion = B.ID');
                        })
                        ->where('B.ID', $autorizacion)
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

        }else if($rol == "Coordinacion"){
            $id = session('id');

            
            $coordinaciones = DB::select("SELECT DISTINCT agenciau, agencias_id, id FROM users WHERE agenciau = ? AND id = ?", [$agenciaU, $id]);

            $agenciasIdArray = json_decode($coordinaciones[0]->agencias_id, true);
            if ($agenciasIdArray === null) {
                $agenciasIdArray = [];
            }

            $numero = preg_replace('/[^0-9]/', '', $coordinaciones[0]->agenciau);

            if (session('agenciau') == "Coordinacion $numero") {
                $coordinacionVariable = "C" . $numero;
            }

            if (count($agenciasIdArray) > 0) {
                $idsFiltro = array_merge($agenciasIdArray, [$coordinacionVariable === 'C9' ? '' : $coordinacionVariable]);
                $userId = $coordinaciones[0]->id;
              
                $autorizaciones = DB::table('autorizaciones_2 AS B')
                    ->join('historialestado AS H', 'H.ID_Autorizacion', '=', 'B.ID')
                    ->leftJoin('persona AS A', 'A.ID', '=', 'H.ID_Persona')

                    // 🔹 ÚLTIMO ESTADO CORRECTO
                    ->whereRaw('H.ID = (
                        SELECT MAX(H3.ID)
                        FROM historialestado H3
                        WHERE H3.ID_Autorizacion = B.ID
                        AND (
                            -- CASO 1: EXISTE ESTADO NO ENVIADO → TOMAR ESE
                            (
                                H3.Estado <> "ENVIADO"
                                AND NOT EXISTS (
                                    SELECT 1
                                    FROM historialestado HX
                                    WHERE HX.ID_Autorizacion = B.ID
                                    AND HX.Estado = "ENVIADO"
                                )
                            )

                            -- CASO 2: SOLO ENVIADOS → TOMAR EL DEL USUARIO
                            OR (
                                H3.Estado = "ENVIADO"
                                AND H3.ID_User = H.ID_User
                            )
                        )
                    )')

                    // 🔹 EXCLUIR ESTADOS FINALES
                    ->whereNotExists(function ($sub) {
                        $sub->select(DB::raw(1))
                            ->from('historialestado AS HT')
                            ->whereColumn('HT.ID_Autorizacion', 'B.ID')
                            ->where('HT.Estado', 'TERMINADO');
                    })
                    // 🔹 LÓGICA DE VISIBILIDAD
                    ->where(function ($q) use ($idsFiltro, $userId) {

                        // ✅ ENVIADO SOLO AL USUARIO
                        $q->where(function ($a) use ($userId) {
                            $a->where('H.Estado', 'ENVIADO')
                            ->where('H.ID_User', $userId);
                        });

                        // ✅ YA PASÓ POR MI ÁREA
                        $q->orWhere(function ($b) use ($idsFiltro) {
                            $b->whereIn('H.Estado', [
                                'RECIBIDO',
                                'CORREGIR',
                                'REMITIDO',
                                'REMITIDOCORREGIR',
                                'ACLARAR',
                                'ENCARGARSE',
                                'PROCEDER',
                                'SOLUCIONAR',
                                'QUE PASO',
                                'TRÁMITE',
                                'VALIDADO',
                                'BLOQUEADO',
                                'DESBLOQUEADO',
                            ])
                            ->whereExists(function ($sub) use ($idsFiltro) {
                                $sub->select(DB::raw(1))
                                    ->from('historialestado AS HX')
                                    ->whereColumn('HX.ID_Autorizacion', 'B.ID')
                                    ->whereIn('HX.NumArea', $idsFiltro);
                            });
                        });
                    })

                    ->select([
                        'B.ID AS IDAutorizacion',
                        'H.Estado',
                        'H.NumArea',
                        'H.ID_User',
                        'A.Nombre',
                        'A.Apellidos',
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
                                ->orWhere('H2.Estado', 'REMITIDO')
                                ->orWhere('H2.Estado', 'RECIBIDO')
                                ->orWhere('H2.Estado', 'DESBLOQUEADO');
                        });
                })
                ->where('H.Bloqueado', '!=', '1')
                ->whereNotIn('H.Estado', [
                    'APROBADO',
                    'STAND BY',
                    'ANULADO',
                    'ENTERADO',
                    'CORREGIR',
                    'TRÁMITE',
                    'ENVIADO',
                    'BLOQUEADO',
                    'PROCEDER',
                    'SOLUCIONAR',
                    'QUE PASO',
                    'TERMINADO',
                    'ACLARAR',
                    'ENCARGARSE'

                ])
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
                    ))) NOT IN ("aprobado", "stand by", "anulado", "TERMINADO", "enterado", "vencido")')
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
            $this->procesarAutorizacion($aut);
        }




        return datatables()->of($autorizaciones)->toJson();

    }


    public function actualizardetalle(Request $request, $id)
    {

        $tipovalidacion = $request->Estado;
        //fecha de la solicitud de la jefatura corregida
        $fechadeSolicitud = Carbon::now('America/Bogota');
        Carbon::setLocale('es');
        $fechaStringfechadeSolicitud = $fechadeSolicitud->translatedFormat('F d Y-H:i:s');
        $nombre = session('name');
        $destinatario = null;
        if(session('rol') == "Gerencia"){


            $ultimoEstado = DB::table('historialestado')
                ->where('ID_Autorizacion', $id)
                ->where(function ($query) {
                    $query->where('Estado', 'TRÁMITE')
                        ->orWhere('Estado', 'REMITIDO')
                        ->orWhere('Estado', 'VALIDADO')
                        ->orWhere('Estado', 'TERMINADO')
                        ->orWhere('Estado', 'ACLARAR')
                        ->orWhere('Estado', 'ENCARGARSE')
                        ->orWhere('Estado', 'PROCEDER')
                        ->orWhere('Estado', 'SOLUCIONAR')
                        ->orWhere('Estado', 'QUE PASO')
                        ->orWhere('Estado', 'RECIBIDO')
                        ->orWhere('Estado', 'STAND BY')
                        ->orWhere('Estado', 'ANULADO')
                        ->orWhere('Estado', 'CORREGIR')
                        ->orWhere('Estado', 'DESBLOQUEADO');
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
                        ->update(['Estado' => $estado]);
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
                        ->update(['Estado' => $estado]);
                }

            } else if ($tipovalidacion == 'APROBADO') {
                $estado = "VALIDADOCONFIRMADO";

                //estado para buscar a cual se le asigna la SEC en caso tal sea tramite o remitido
                $primerEstado = DB::table('historialestado')
                    ->where('ID_Autorizacion', $id)
                    ->where(function ($query) {
                        $query->where('Estado', 'TRÁMITE')
                            ->orWhere('Estado', 'DONE')
                            ->orWhere('Estado', 'REMITIDO');
                    })
                    ->orderByDesc('ID')
                    ->first();
                $primerEstado_id = $primerEstado->ID;
                //logica para asignar la secuencia
                $ultimoSec = DB::table('historialestado')
                    ->max('SEC');

                $nuevoSec = $ultimoSec ? $ultimoSec + 1 : 1;

                DB::table('historialestado')
                    ->where('ID', $primerEstado_id)
                    ->update([
                        'SEC' => $nuevoSec
                    ]);

                if($ultimoEstado->Estado != "REMITIDO"){
                    if($ultimoEstado->Estado == "DESBLOQUEADO" || $ultimoEstado->Estado == "VALIDADO"){

                    }else if($ultimoEstado->Estado == "STAND BY"){
                        $estado = "STAND BY";
                    }else if ($ultimoEstado) {
                    DB::table('historialestado')
                        ->where('ID', $ultimoEstado->ID)
                        ->update(['Estado' => $estado]);
                    }
                }else{
                    if ($ultimoEstado) {
                    $estado = "REMITIDOCONFIRMADO";
                    DB::table('historialestado')
                        ->where('ID', $ultimoEstado->ID)
                        ->update(['Estado' => $estado]);
                    }
                }
 

            } /*bloqueado */else if ($tipovalidacion == "1") {
                $estado = "VALIDADOCONFIRMADO";

                if($ultimoEstado->Estado != "REMITIDO"){
                    if($ultimoEstado->Estado == "DESBLOQUEADO"){

                    }else if ($ultimoEstado) {
                        DB::table('historialestado')
                            ->where('ID', $ultimoEstado->ID)
                            ->update(['Estado' => $estado]);
                    }
                }else if($ultimoEstado->Estado == "REMITIDO"){
                    if ($ultimoEstado) {
                        $estado = "REMITIDOCONFIRMADO";
                        DB::table('historialestado')
                            ->where('ID', $ultimoEstado->ID)
                            ->update(['Estado' => $estado]);
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

            }else if ($tipovalidacion == 'ANULADO') {

                $primerHistorial = DB::table('historialestado')
                    ->where('ID_Autorizacion', $id)
                    ->orderBy('ID', 'asc')
                    ->first();
                //LINEA PARA QUE SI EL ULTIMO ESTADO ES TRAMITE LE QUITE EL BOTON
                if($ultimoEstado->Estado == "TRÁMITE" && $primerHistorial->Observaciones != "NADA"){
                    DB::table('historialestado')
                        ->where('ID_Autorizacion', $ultimoEstado->ID_Autorizacion)
                        ->update(['Observaciones' => 'NADA']);
                }

                if ($primerHistorial) {
                    DB::table('historialestado')
                        ->where('ID', $primerHistorial->ID)
                        ->update(['Bloqueado' => 0, 'Estado' => 'DONE']);
                }

            }else if ($tipovalidacion == 'STAND BY') {
                $estado = "VALIDADOCONFIRMADO";
                if($ultimoEstado->Estado == "DESBLOQUEADO"){

                }elseif($ultimoEstado->Estado != "REMITIDO"){
                    if ($ultimoEstado) {
                    DB::table('historialestado')
                        ->where('ID', $ultimoEstado->ID)
                        ->update(['Estado' => $estado]);
                    }
                }else if($ultimoEstado->Estado == "REMITIDO"){
                    if ($ultimoEstado) {
                        $estado = "REMITIDOCONFIRMADO";
                        DB::table('historialestado')
                            ->where('ID', $ultimoEstado->ID)
                            ->update(['Estado' => $estado]);
                    }
                }
            }else if ($tipovalidacion == 'DESBLOQUEADO') {

                $primerHistorial = DB::table('historialestado')
                    ->where('ID_Autorizacion', $id)
                    ->orderBy('ID', 'asc')
                    ->first();

                if ($primerHistorial) {
                    DB::table('historialestado')
                        ->where('ID', $primerHistorial->ID)
                        ->update(['Bloqueado' => 0]);
                }



            }else if ($tipovalidacion == 'ENTERADO') {

                $estado = "VALIDADOCONFIRMADO";

                if($ultimoEstado->Estado != "REMITIDO"){
                    if ($ultimoEstado) {
                    DB::table('historialestado')
                        ->where('ID', $ultimoEstado->ID)
                        ->update(['Estado' => $estado]);
                    }
                }else{
                    if ($ultimoEstado) {
                    $estado = "REMITIDOCONFIRMADO";
                    DB::table('historialestado')
                        ->where('ID', $ultimoEstado->ID)
                        ->update(['Estado' => $estado]);
                    }
                }




            }else if ($tipovalidacion == 'TERMINADO' || $tipovalidacion == 'ACLARAR' || $tipovalidacion == 'ENCARGARSE' || $tipovalidacion == 'PROCEDER' || $tipovalidacion == 'SOLUCIONAR' || $tipovalidacion == 'QUE PASO') {

                $estado = "RECIBIDOCONFIRMADO";

                $destinatario = $ultimoEstado->ID_User;

                if($ultimoEstado->Estado == "RECIBIDO"){
                    if ($ultimoEstado) {
                    DB::table('historialestado')
                        ->where('ID', $ultimoEstado->ID)
                        ->update(['ID_User' => $ultimoEstado->ID_User, 'Estado' => $estado]);
                    }
                }


            } else{
                
                $estado = "DONE";
                if($tipovalidacion !== "CORREGIRJEFATURA"){
                    if ($ultimoEstado) {
                        DB::table('historialestado')
                            ->where('ID', $ultimoEstado->ID)
                            ->update(['Estado' => $estado]);
                    }
                }

                
                if($ultimoEstado->Estado == "TRÁMITE"){
                    DB::table('historialestado')
                        ->where('ID_Autorizacion', $ultimoEstado->ID_Autorizacion)
                        ->where('Estado', 'TRÁMITE')
                        ->orwhere('Estado', 'DONE')
                        ->update(['Observaciones' => 'NADA']);
                }

                if($tipovalidacion == "CORREGIRJEFATURA"){
                    $NumArea = 'C9';
                    $NomArea = 'Coordinacion 9';
                    $tipovalidacion = 'CORREGIR';
                }else if($tipovalidacion == "VALIDADO"){
                    $NumArea = 'C9';
                    $NomArea = 'Coordinacion 9';
                }else{
                    $tipovalidacion = $tipovalidacion;

                }

            }


            if($tipovalidacion == 1){
                $estado = "BLOQUEADO";
            }else if($tipovalidacion == 'ENVIAR A'){
                $estado = 'ENVIADO';
            }else{
                $estado = $tipovalidacion;
            }


            $update = DB::table('historialestado')
            ->insert([
                'NumArea' => $NumArea,
                'NomArea' => $NomArea,
                'Observaciones' => $request->Observaciones,
                'Estado' => $estado,
                'Nombre' => $nombre,
                'Fecha' => $fechadeSolicitud,
                'FechaString' => $fechaStringfechadeSolicitud,
                'ID_User' => $destinatario,
                'ID_Autorizacion' => $id
            ]);

            // $consultatelefono = DB::table('historialestado AS H')
            //     ->join('users AS U', 'U.id', '=', 'H.ID_User')
            //     ->where('H.ID_Autorizacion', $id)
            //     ->select(
            //         'U.*',
            //         'H.*'
            //     )
            //     ->first();

            // $phone = '57'.$consultatelefono->celular;
            // if (!empty($phone)) {
            //     try {
            //         Http::timeout(1)          // tiempo TOTAL máximo
            //             ->connectTimeout(1)  // tiempo máximo para conectar
            //             ->post('http://localhost:3001/send', [
            //                 'phone' => $phone,
            //                 'name' => $consultatelefono->name,
            //                 'consecutivo' => $id,
            //                 'fecha' => $fechaStringfechadeSolicitud,
            //                 'estado' => $estado,
            //                 'aprobado_por' => $nombre,
            //                 'observaciones' => $request->Observaciones,
            //             ]);

            //     } catch (\Throwable $e) {
                    
            //     }
            // }

            return response()->json(['success' => true]);

        }else{
            if(($tipovalidacion == null || $request->Cedulamodal != null)){
                         
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
                $idconcepto = null;
                $observaciones = null;



                //concepto traer el id
                $existingConcepto = DB::select('SELECT ID FROM concepto_autorizaciones WHERE ID = ?', [$tipoautorizacion]);
                if(!empty($existingConcepto)){
                    $idconcepto = $existingConcepto[0]->ID;
                }


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
                    }

                    $existingPerson = DB::select('SELECT * FROM persona WHERE Cedula = ?', [$cedula]);

                    if(empty($existingPerson)){
                        if(isset($response)){
                            $nombre = $request->Nombremodal;
                            $cuenta = $request->Cuentamodal;
                        }else{
                            $nombre = $data['asociado']['NOMBRES'];
                            $cuenta = $data['asociado']['CUENTA'];
                        }

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
                }else if($tipovalidacion == 'RECIBIDO'){
                    $estado = $tipovalidacion;
                    $observaciones = $request->Observaciones;
                }else{
                    $estado = "TRÁMITE";
                }
                $agenciaU = session('agenciau');
                $existeAgencia = DB::select('SELECT * FROM agencias WHERE NameAgencia = ?', [$agenciaU]);

                if (isset($filename)) {
                    // $existingCedula = DB::select('SELECT Cedula FROM autorizaciones WHERE ID = ?', [$id]);
                    // $cedula = $existingCedula[0]->Cedula;
                    Log::info('Cedula: ' . $cedula);
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
                        'NumArea' => !empty($existeAgencia) ? $existeAgencia[0]->NumAgencia : session('rol'),
                        'NomArea' => session('agenciau'),
                        'Estado' => $estado,
                        'Observaciones' => $observaciones,
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
                        'NumArea' => !empty($existeAgencia) ? $existeAgencia[0]->NumAgencia : session('rol'),
                        'NomArea' => session('agenciau'),
                        'Estado' => $estado,
                        'Nombre' => session('name'),
                        'Observaciones' => $observaciones,
                        'Fecha' => $fechadeSolicitud,
                        'DocumentoSoporte' => $ultimoDocumento->DocumentoSoporte,
                        'FechaString' => $fechaStringfechadeSolicitud,
                        'ID_Autorizacion' => $id
                    ]);
                    return response()->json(['message' => 'Datos recibidos correctamente']);
                }
            }else{

                //coordinacion
                $nombre = session('name');
                $noCoordinacion = session('agenciau');
                $estadoautorizacion = $request->Estado;

                if (preg_match('/Coordinacion (\d+)/', $noCoordinacion, $matches)) {
                    $coordinacion = 'C' . $matches[1];
                } else {
                    $coordinacion = null; // O un valor por defecto
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
                            ->update(['Estado' => $estado]);
                    }

                }

                if($tipovalidacion == null){
                    $tipovalidacion = 'REMITIDO';
                }elseif($tipovalidacion == 'RECIBIDO'){
                    $tipovalidacion = 'RECIBIDO';
                }

                $update = DB::table('historialestado')
                    ->insert([
                        'NumArea' => $coordinacion,
                        'NomArea' => $noCoordinacion,
                        'Observaciones' => $request->Observaciones,
                        'Estado' => $tipovalidacion,
                        'Nombre' => $nombre,
                        'ID_User' => session('id'),
                        'Fecha' => $fechadeSolicitud,
                        'FechaString' => $fechaStringfechadeSolicitud,
                        'ID_Autorizacion' => $id
                    ]);



                return response()->json(['success' => true]);
            }
        }

    }






    public function enviar(Request $request)
    {
        try {
            $estado = "VALIDADOCONFIRMADO";

            $fechadeSolicitud = Carbon::now('America/Bogota');
            Carbon::setLocale('es');
            $fechaStringfechadeSolicitud = $fechadeSolicitud->translatedFormat('F d Y-H:i:s');
            $id = $request->autorizacion_id;
                        $ultimoEstado = DB::table('historialestado')
                    ->where('ID_Autorizacion', $id)
                    ->where(function ($query) {
                        $query->where('Estado', 'TRÁMITE')
                            ->orWhere('Estado', 'REMITIDO')
                            ->orWhere('Estado', 'VALIDADO')
                            ->orWhere('Estado', 'TERMINADO')
                            ->orWhere('Estado', 'ACLARAR')
                            ->orWhere('Estado', 'ENCARGARSE')
                            ->orWhere('Estado', 'PROCEDER')
                            ->orWhere('Estado', 'SOLUCIONAR')
                            ->orWhere('Estado', 'QUE PASO')
                            ->orWhere('Estado', 'RECIBIDO')
                            ->orWhere('Estado', 'STAND BY')
                            ->orWhere('Estado', 'ANULADO')
                            ->orWhere('Estado', 'CORREGIR')
                            ->orWhere('Estado', 'DESBLOQUEADO');
                    })
                    ->orderByDesc('ID') // o 'Fecha' si ese campo representa el orden cronológico
                    ->first();
            if($ultimoEstado->Estado != "REMITIDO"){
                if ($ultimoEstado) {
                DB::table('historialestado')
                    ->where('ID', $ultimoEstado->ID)
                    ->update(['Estado' => $estado]);
                }
            }else{
                if ($ultimoEstado) {
                $estado = "REMITIDOCONFIRMADO";
                DB::table('historialestado')
                    ->where('ID', $ultimoEstado->ID)
                    ->update(['Estado' => $estado]);
                }
            }



            foreach ($request->destinatarios as $destinatarioId) {

                $usuario = DB::table('users')
                    ->where('id', $destinatarioId)
                    ->first();

                if (!$usuario) {
                    continue;
                }


                $numArea = $usuario->codigo ?? 'SIN_CODIGO'; // valor por defecto
                $funcionariosEnviados = [];
                    if ($usuario->rol === 'Coordinacion') {
                        if (!empty($usuario->agenciau) &&
                            preg_match('/Coordinacion\s+(\d+)/i', $usuario->agenciau, $match)) {
                            $numArea = 'C' . $match[1];
                        }
                    } elseif ($usuario->rol === 'Consultante') {

                    $agencia = DB::table('agencias')
                        ->where('NameAgencia', $usuario->agenciau)
                        ->first();

                    if ($agencia && !empty($agencia->NumAgencia)) {
                        $numArea = $agencia->NumAgencia;
                    }

                } elseif ($usuario->rol === 'Jefatura') {

                    $numArea = 'Jefatura';
                }

                $funcionariosEnviados[] = sprintf(
                    '%s (%s - %s)',
                    $usuario->name,
                    $usuario->agenciau,
                    $numArea
                );

                DB::table('historialestado')->insert([
                    'NumArea'         => $numArea,
                    'NomArea'         => $usuario->agenciau,
                    'Observaciones'   => $request->Observaciones ?? null,
                    'Estado'          => 'ENVIADO',
                    'Nombre'          => $usuario->name,
                    'Fecha'           => $fechadeSolicitud,
                    'FechaString'     => $fechaStringfechadeSolicitud,
                    'ID_User'         => $usuario->id,
                    'ID_Autorizacion' => $request->autorizacion_id
                ]);
            }

            $badges = collect($funcionariosEnviados)->map(function ($nombre) {
                return "<span class='badge bg-secondary fw-semibold me-1 mb-1'>{$nombre}</span>";
            })->implode(' ');

    

            return response()->json([
                'success' => true,
                'message' => "
                    <div class='text-start'>
                        <div class='fw-bold mb-2'>
                            ✅ Se envió correctamente la solicitud No.
                            <span class='badge bg-primary fw-bold'>{$request->autorizacion_id}</span>
                            a los siguientes funcionarios:
                        </div>
                        <div class='d-flex flex-wrap gap-1'>
                            {$badges}
                        </div>
                    </div>
                ",
                'debug' => [
                    'autorizacion'  => $request->autorizacion_id,
                    'destinatarios' => $request->destinatarios
                ]
            ]);


        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el envío',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    public function aprobados(Request $request)
    {
        if (!session('email')) {
            return redirect()->route('login');
        }

        $agenciaU = session('agenciau');
        $rol = session('rol');
        $autorizacion = $request->search_term;

        if (!empty($autorizacion)) {

                if ($rol == "Gerencia") {

                    $query = DB::table('autorizaciones_2 AS B')
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
                        ->where('B.ID', $autorizacion)
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
                        ]);

                                // ✅ Ejecutar consulta
                        $autorizaciones = $query->get();

                }else if($rol == "Coordinacion"){
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
                                ->where('B.ID', $autorizacion)
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
                        // Excluir autorizaciones cuyo último estado global sea "APROBADO" o "STAND BY"
                        ->whereExists(function ($query) {
                            $query->select(DB::raw(1))
                                ->from('historialestado AS H2')
                                ->whereRaw('H2.ID_Autorizacion = B.ID');
                        })
                        ->where('B.ID', $autorizacion)
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

        }elseif($rol == "Coordinacion"){
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
                    ->whereRaw('LOWER(TRIM(H.Estado)) IN ("aprobado")')
                    // 🚫 Excluir otros estados no deseados (por seguridad)
                    ->whereNotIn('H.Estado', ['STAND BY', 'TRÁMITE', 'REMITIDO', 'REMITIDOCONFIRMADO', 'RECIBIDO'])
                    ->limit(200)
                    ->orderByDesc('B.ID')
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
                        ->whereIn('H2.Estado', ['APROBADO']);
                })
                ->limit(200)
                ->orderByDesc('B.ID')
                ->where('H.Bloqueado', '!=', '1')
                ->where('H.Estado', '!=', 'ANULADO')
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
                ->whereRaw('LOWER(TRIM((
                    SELECT H3.Estado
                    FROM historialestado AS H3
                    WHERE H3.ID_Autorizacion = B.ID
                    ORDER BY H3.ID DESC
                    LIMIT 1
                ))) IN ("aprobado")')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('historialestado AS H2')
                        ->whereRaw('H2.ID_Autorizacion = B.ID');
                })
                ->limit(200)
                ->orderByDesc('B.ID')
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
            $this->procesarAutorizacion($aut);
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

        $autorizacion = $request->search_term;

        if (!empty($autorizacion)) {

                if ($rol == "Gerencia") {

                    $query = DB::table('autorizaciones_2 AS B')
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
                        ->where('B.ID', $autorizacion)
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
                        ]);

                                // ✅ Ejecutar consulta
                        $autorizaciones = $query->get();

                }

        }

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
            ->whereNotIn('H.Estado', [
                'APROBADO',
                'BLOQUEADO',
                'VALIDADO',
                'TRÁMITE',
                'ANULADO',
                'ENTERADO',
                'RECIBIDO',
                'ENVIADO',
                'TERMINADO',
                'ACLARAR',
                'ENCARGARSE',
                'PROCEDER',
                'SOLUCIONAR',
                'QUE PASO',
                'RECIBIDOCONFIRMADO',
                'RECIBIDO',
                'VENCIDO',
                'REMITIDO',
                'REMITIDOCONFIRMADO',
            ])
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
            $this->procesarAutorizacion($aut);
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

        $autorizacion = $request->search_term;

        if (!empty($autorizacion)) {

                if ($rol == "Gerencia") {

                    $query = DB::table('autorizaciones_2 AS B')
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
                        ->where('B.ID', $autorizacion)
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
                        ]);

                                // ✅ Ejecutar consulta
                        $autorizaciones = $query->get();

                }

        }

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
            ->whereNotIn('H.Estado', [
                'APROBADO',
                'CORREGIR',
                'REMITIDOCORREGIR',
                'TRÁMITE',
                'ANULADO',
                'ENTERADO',
                'RECIBIDO',
                'ENVIADO',
                'TERMINADO',
                'ACLARAR',
                'ENCARGARSE',
                'PROCEDER',
                'SOLUCIONAR',
                'QUE PASO',
                'RECIBIDOCONFIRMADO',
                'RECIBIDO',
                'VENCIDO',
                'DESBLOQUEADO',
                'DONE',
                'VALIDADO',
                'VALIDADOCONFIRMADO',
            ])
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
            $this->procesarAutorizacion($aut);
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

        $autorizacion = $request->search_term;

        if (!empty($autorizacion)) {

                if ($rol == "Gerencia") {

                    $query = DB::table('autorizaciones_2 AS B')
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
                        ->where('B.ID', $autorizacion)
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
                        ]);

                                // ✅ Ejecutar consulta
                        $autorizaciones = $query->get();

                }

        }

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
            ->where('H.Estado', '!=', "ANULADO")
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
            $this->procesarAutorizacion($aut);
        }

        return response()->json(['data' => $autorizaciones]);

    }

    public function anulados(Request $request)
    {
        if (!session('email')) {
            return redirect()->route('login');
        }

        $agenciaU = session('agenciau');
        $rol = session('rol');


        $autorizacion = $request->search_term;

        if (!empty($autorizacion)) {

                if ($rol == "Gerencia") {

                    $query = DB::table('autorizaciones_2 AS B')
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
                        ->where('B.ID', $autorizacion)
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
                        ]);

                                // ✅ Ejecutar consulta
                        $autorizaciones = $query->get();

                }else if($rol == "Coordinacion"){
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
                                ->where('B.ID', $autorizacion)
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
                        // Excluir autorizaciones cuyo último estado global sea "APROBADO" o "STAND BY"
                        ->whereExists(function ($query) {
                            $query->select(DB::raw(1))
                                ->from('historialestado AS H2')
                                ->whereRaw('H2.ID_Autorizacion = B.ID');
                        })
                        ->where('B.ID', $autorizacion)
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

        }elseif($rol == "Coordinacion"){
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
                    ->whereRaw('LOWER(TRIM(H.Estado)) = "anulado"')
                    // 🚫 Excluir otros estados no deseados (por seguridad)
                    ->whereNotIn('H.Estado', ['APROBADO', 'TRÁMITE', 'REMITIDO', 'REMITIDOCONFIRMADO'])
                    ->limit(200)
                    ->orderByDesc('B.ID')
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
                    ->whereIn('H2.Estado', ['ANULADO']);
            })
            ->where('H.Estado', '!=', "APROBADO")
            ->where('H.Estado', '!=', "VALIDADO")
            ->where('H.Estado', '!=', "CORREGIR")
            ->where('H.Estado', '!=', "ENVIADO")
            ->limit(200)
            ->orderByDesc('B.ID')
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
                ))) = "anulado"')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('historialestado AS H2')
                        ->whereRaw('H2.ID_Autorizacion = B.ID');
                })
                ->limit(200)
                ->orderByDesc('B.ID')
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
            $this->procesarAutorizacion($aut);
        }

        return response()->json(['data' => $autorizaciones]);

    }

    public function standby(Request $request)
    {
        if (!session('email')) {
            return redirect()->route('login');
        }

        $agenciaU = session('agenciau');
        $rol = session('rol');

        $autorizacion = $request->search_term;

        if (!empty($autorizacion)) {

                if ($rol == "Gerencia") {

                    $query = DB::table('autorizaciones_2 AS B')
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
                        ->where('B.ID', $autorizacion)
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
                        ]);

                                // ✅ Ejecutar consulta
                        $autorizaciones = $query->get();

                }else if($rol == "Coordinacion"){
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
                                ->where('B.ID', $autorizacion)
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
                        // Excluir autorizaciones cuyo último estado global sea "APROBADO" o "STAND BY"
                        ->whereExists(function ($query) {
                            $query->select(DB::raw(1))
                                ->from('historialestado AS H2')
                                ->whereRaw('H2.ID_Autorizacion = B.ID');
                        })
                        ->where('B.ID', $autorizacion)
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

        }elseif($rol == "Coordinacion"){
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
                    ->whereNotIn('H.Estado', ['APROBADO', 'TRÁMITE', 'REMITIDO', 'REMITIDOCONFIRMADO', 'ANULADO'])
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
            ->where('H.Estado', '!=', "ANULADO")
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
                    ->leftJoin('users AS U', function($join){
                        $join->on(DB::raw("CONVERT(U.name USING utf8mb4) COLLATE utf8mb4_general_ci"),
                                '=',
                                DB::raw("CONVERT(H.Nombre USING utf8mb4) COLLATE utf8mb4_general_ci"));
                    }) // ✅ compara sin error
                    ->where('H.ID_Autorizacion', $aut->IDAutorizacion)
                    ->select([
                        'H.*',
                        'A.Score',
                        'D.FechaInsercion',
                        'C.Concepto',
                        'U.name AS NombreUsuario',
                        'U.codigo AS CodigoUsuario'
                    ])
                    ->orderBy('H.ID', 'asc')
                    ->get();

                // Adjuntamos el historial completo al objeto
                $aut->historial = $historial;

                // 🔹 Primer historial (más antiguo)
                $primer = $historial->first();
                if ($primer) {

                    $aut->CodigoUsuario = $primer->CodigoUsuario;
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

                $ultimaRemitidoCorregir = $historial
                    ->filter(function ($h) {
                        $estado = strtoupper(trim($h->Estado ?? ''));
                        return in_array($estado, ['REMITIDO', 'REMITIDOCORREGIR', 'STAND BY', 'BLOQUEADO', 'APROBADO']);
                    })
                    ->last();

                $aut->ultimaRemitidoCorregir = $ultimaRemitidoCorregir ? $ultimaRemitidoCorregir->FechaString : null;

                $ultimoConceptoID = DB::table('historialestado')
                    ->where('ID_Autorizacion', $aut->IDAutorizacion)
                    ->where('ID_Concepto', '=', '17')
                    ->orderByDesc('ID')
                    ->first();

                $aut->UltimoConceptoID = $ultimoConceptoID
                    ? $ultimoConceptoID->ID_Concepto
                    : null;

                $ultimoEnviadoa = DB::table('historialestado')
                    ->where('ID_Autorizacion', $aut->IDAutorizacion)
                    ->where('Estado', '=', 'ENVIADO')
                    ->orderByDesc('ID')
                    ->first();

                $aut->ultimoEnviadoa = $ultimoEnviadoa
                    ? $ultimoEnviadoa->Nombre
                    : null;

            $estadosSinFiltrar = [
                'TERMINADO',
                'ACLARAR',
                'ENCARGARSE',
                'PROCEDER',
                'SOLUCIONAR',
                'QUE PASO',
                'RECIBIDOCONFIRMADO',
                'RECIBIDO',


            ];

            // 1. Filtrar estados que SI deben mostrar solo el último
            $filtrar = $historial->filter(function ($item) use ($estadosSinFiltrar) {
                return !in_array($item->Estado, $estadosSinFiltrar);
            });

            // 2. Filtrar estados que deben mostrar TODOS
            $sinFiltrar = $historial->filter(function ($item) use ($estadosSinFiltrar) {
                return in_array($item->Estado, $estadosSinFiltrar);
            });


            // 1. Buscar el último DONE o REMITIDOCORREGIR
            $ultimoClave = $historial
                ->whereIn('Estado', ['DONE', 'REMITIDOCORREGIR', 'REMITIDO', 'REMITIDOCONFIRMADO', 'TRÁMITE'])
                ->sortByDesc('ID')
                ->first();

            // Si NO hay DONE ni REMITIDOCORREGIR → devolver vacío o todo (tú decides)
            if (!$ultimoClave) {
                $desdeClave = collect(); // vacío
            } else {

                // 2. Tomar todos los registros desde ese DONE/REMITIDOCORREGIR en adelante
                $desdeClave = $historial->filter(function ($item) use ($ultimoClave) {
                    return $item->ID >= $ultimoClave->ID;
                })->values();
            }

            // Resultado final
            $aut->historialEstadosUnicos = $desdeClave;








            }

        return response()->json(['data' => $autorizaciones]);

    }

    public function enviado(Request $request)
    {
        if (!session('email')) {
            return redirect()->route('login');
        }

        $agenciaU = session('agenciau');
        $rol = session('rol');

        $autorizacion = $request->search_term;

        if (!empty($autorizacion)) {

                if ($rol == "Gerencia") {

                    $query = DB::table('autorizaciones_2 AS B')
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
                        ->where('B.ID', $autorizacion)
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
                        ]);

                                // ✅ Ejecutar consulta
                        $autorizaciones = $query->get();

                }

        }

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
                    ->whereIn('H2.Estado', ['ENVIADO']);
            })
            ->where('H.Estado', '!=', "RECIBIDO")
            ->where('H.Estado', '!=', "TERMINADO")
            ->where('H.Estado', '!=', "APROBADO")
            ->where('H.Estado', '!=', "VALIDADO")
            ->where('H.Estado', '!=', "CORREGIR")
            ->where('H.Estado', '!=', "VENCIDO")
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
            $this->procesarAutorizacion($aut);
        }


        return response()->json(['data' => $autorizaciones]);
    }

    public function terminado(Request $request)
    {
        if (!session('email')) {
            return redirect()->route('login');
        }

        $agenciaU = session('agenciau');
        $rol = session('rol');

        $autorizacion = $request->search_term;

        if (!empty($autorizacion)) {

                if ($rol == "Gerencia") {

                    $query = DB::table('autorizaciones_2 AS B')
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
                        ->where('B.ID', $autorizacion)
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
                        ]);

                                // ✅ Ejecutar consulta
                        $autorizaciones = $query->get();

                }

        }

        if($rol == "Gerencia"){
            $autorizaciones = DB::table('autorizaciones_2 AS B')
                ->join(DB::raw('(
                    SELECT H1.*
                    FROM historialestado AS H1
                    INNER JOIN (
                        SELECT ID_Autorizacion, MAX(ID) AS MaxID
                        FROM historialestado
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
                ))) = "terminado"')
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
                // Excluir autorizaciones cuyo último estado global sea "APROBADO" o "STAND BY"
                ->whereRaw('LOWER(TRIM((
                    SELECT H3.Estado
                    FROM historialestado AS H3
                    WHERE H3.ID_Autorizacion = B.ID
                    ORDER BY H3.ID DESC
                    LIMIT 1
                ))) = "terminado"')
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
            $this->procesarAutorizacion($aut);
        }


        return response()->json(['data' => $autorizaciones]);
    }

    public function reportes(Request $request)
    {
        if (!session('email')) {
            return redirect()->route('login');
        }

        $agenciaU = session('agenciau');
        $rol = session('rol');
        $autorizacion = $request->search_term;

        if (!empty($autorizacion)) {

                if ($rol == "Gerencia") {

                    $query = DB::table('autorizaciones_2 AS B')
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
                        ->where('B.ID', $autorizacion)
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
                        ]);

                                // ✅ Ejecutar consulta
                        $autorizaciones = $query->get();

                }else if($rol == "Coordinacion"){
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
                                ->where('B.ID', $autorizacion)
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
                        // Excluir autorizaciones cuyo último estado global sea "APROBADO" o "STAND BY"
                        ->whereExists(function ($query) {
                            $query->select(DB::raw(1))
                                ->from('historialestado AS H2')
                                ->whereRaw('H2.ID_Autorizacion = B.ID');
                        })
                        ->where('B.ID', $autorizacion)
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

        }elseif($rol == "Coordinacion"){
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
                    ->whereRaw('LOWER(TRIM(H.Estado)) IN ("enterado")')
                    // 🚫 Excluir otros estados no deseados (por seguridad)
                    ->whereNotIn('H.Estado', ['STAND BY', 'TRÁMITE', 'REMITIDO', 'REMITIDOCONFIRMADO', 'RECIBIDO'])
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
                        ->whereIn('H2.Estado', ['ENTERADO']);
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
                ->whereRaw('LOWER(TRIM((
                    SELECT H3.Estado
                    FROM historialestado AS H3
                    WHERE H3.ID_Autorizacion = B.ID
                    ORDER BY H3.ID DESC
                    LIMIT 1
                ))) IN ("enterado")')
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
            $this->procesarAutorizacion($aut);
        }


        return response()->json(['data' => $autorizaciones]);
    }

    public function vencido(Request $request)
    {
        if (!session('email')) {
            return redirect()->route('login');
        }

        $agenciaU = session('agenciau');
        $rol = session('rol');
        $autorizacion = $request->search_term;

        if (!empty($autorizacion)) {

                if ($rol == "Gerencia") {

                    $query = DB::table('autorizaciones_2 AS B')
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
                        ->where('B.ID', $autorizacion)
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
                        ]);

                                // ✅ Ejecutar consulta
                        $autorizaciones = $query->get();

                }else if($rol == "Coordinacion"){
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
                                ->where('B.ID', $autorizacion)
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
                        // Excluir autorizaciones cuyo último estado global sea "APROBADO" o "STAND BY"
                        ->whereExists(function ($query) {
                            $query->select(DB::raw(1))
                                ->from('historialestado AS H2')
                                ->whereRaw('H2.ID_Autorizacion = B.ID');
                        })
                        ->where('B.ID', $autorizacion)
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

        }elseif($rol == "Coordinacion"){
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
                    ->whereRaw('LOWER(TRIM(H.Estado)) IN ("vencido")')
                    // 🚫 Excluir otros estados no deseados (por seguridad)
                    ->whereNotIn('H.Estado', ['STAND BY', 'TRÁMITE', 'REMITIDO', 'REMITIDOCONFIRMADO', 'RECIBIDO'])
                    ->limit(200)
                    ->orderByDesc('B.ID')
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
                        ->whereIn('H2.Estado', ['VENCIDO']);
                })
                ->where('H.Bloqueado', '!=', '1')
                ->limit(200)
                ->orderByDesc('B.ID')
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
                ->whereRaw('LOWER(TRIM((
                    SELECT H3.Estado
                    FROM historialestado AS H3
                    WHERE H3.ID_Autorizacion = B.ID
                    ORDER BY H3.ID DESC
                    LIMIT 1
                ))) IN ("vencido")')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('historialestado AS H2')
                        ->whereRaw('H2.ID_Autorizacion = B.ID');
                })
                ->limit(200)
                ->orderByDesc('B.ID')
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
            $this->procesarAutorizacion($aut);
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

        $autorizacion = $request->search_term;

        if (!empty($autorizacion)) {

                if ($rol == "Gerencia") {

                    $query = DB::table('autorizaciones_2 AS B')
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
                        ->where('B.ID', $autorizacion)
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
                        ]);

                                // ✅ Ejecutar consulta
                        $autorizaciones = $query->get();

                }

        }

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
            $this->procesarAutorizacion($aut);
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
                return view('Usuario/mostrarautorizacion', ['id' => $id,'autorizacion' => $autorizacion, 'estado' => $estado]);
            }
        }
    }

    public function modalAutorizacion($id)
    {
        $data = DB::table('autorizaciones_2')
            ->where('IDAutorizacion', $id)
            ->first();

        if (!$data) {
            return response()->json(['error' => 'No encontrado'], 404);
        }

        return view('modales.autorizacion', compact('data'))->render();
    }



    public function updatePassword(Request $request)
    {
        // Obtener el usuario logueado desde la sesión
        $userId = session('id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->back()->withErrors(['error' => 'Usuario no encontrado.']);
        }

        // Validaciones
        $validator = Validator::make($request->all(), [
            'current_password' => ['required'],
            'new_password' => [
                'required',
                'string',
                'min:8',              // mínimo 8 caracteres
                'regex:/[a-z]/',      // al menos 1 minúscula
                'regex:/[A-Z]/',      // al menos 1 mayúscula
                'regex:/[0-9]/',      // al menos 1 número
                'regex:/[@$!%*?&]/',  // al menos 1 símbolo
                'confirmed'           // debe coincidir con new_password_confirmation
            ],
        ], [
            'new_password.regex' => 'La nueva contraseña debe contener al menos una mayúscula, minúscula, número y símbolo.',
            'new_password.confirmed' => 'La confirmación de la contraseña no coincide.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Verificar contraseña actual
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.']);
        }

        // Actualizar la contraseña
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Contraseña actualizada correctamente.');

    }


    public function updatePerfil(Request $request)
    {
        $userId = session('id');
        $user = User::find($userId);

        // Verificar si el nombre ya existe en otro usuario
        $existe = User::where('name', $request->name)
                    ->where('id', '!=', $user->id)
                    ->exists();

        if ($existe) {
            return redirect()->back()->withErrors([
                'error' => 'El nombre ya está en uso. Por favor, elige uno diferente.'
            ])->withInput();
        }

        // Actualizar datos
        $user->update([
            'name' => $request->name,
            'celular' => $request->celular,
        ]);

        return redirect()->back()->with('success', 'Perfil actualizado correctamente.');
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|mimes:jpg,jpeg,png,gif,pdf', // imágenes o PDF hasta 5MB
        ]);

        // 1️⃣ Crear el registro sin la imagen
        $bugReport = BugReport::create([
            'title' => $request->title,
            'description' => $request->description,
            'solicitadopor' => session('name') . ' - '.session('agenciau'),
            'image' => null, // se actualizará después
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Carpeta donde guardaremos las imágenes
            $destinationPath = public_path('Storage/files/reporteimgs');

            // Crear la carpeta si no existe
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            // Nombre del archivo con el ID del registro
            $filename = 'bug_' . $bugReport->id . '.' . $file->getClientOriginalExtension();

            // Mover el archivo
            $file->move($destinationPath, $filename);

            // Guardar la ruta relativa
            $imagePath = 'Storage/files/reporteimgs/' . $filename;

            // Actualizar el registro con la ruta de la imagen
            $bugReport->update(['image' => $filename]);
        }

        return back()->with('correcto', 'Reporte enviado correctamente');
    }

    public function data2antiguo()
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

        $usuariosEnviara = DB::select("SELECT * FROM users WHERE rol = 'Consultante' OR rol = 'Coordinacion' OR rol = 'Jefatura' ORDER BY name ASC");

        $userId = session('id'); // tu ID de usuario
        $userFiltrado = User::find($userId);
        $name = $userFiltrado->name;
        $celular = $userFiltrado->celular;

        return view('Usuario/Gerencia/solicitudes_antiguas', [
            'user' => $user,
            'grupos' => $grupos,
            'convencion' => $convencion,
            'usuariosEnviara' => $usuariosEnviara,
        ]);
    }

    public function solicitudesantiguas(Request $request)
    {
        if (session('email') == null) {
            return redirect()->route('login');
        }
        $agenciaU = session('agenciau');

        $agencias = DB::select("SELECT NumAgencia FROM autorizaciones");

        $solicitudes = DB::select("SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte, B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, B.Bloqueado, B.ID_Concepto, C.Letra, C.No, C.Concepto, C.Areas, D.FechaInsercion
        FROM persona A
        JOIN autorizaciones B ON B.ID_Persona = A.ID
        JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
        JOIN documentosintesis D ON A.ID = D.ID_Persona
        WHERE  B.Bloqueado = 0 AND ((B.Estado = 1 AND B.Validacion = 1) OR (B.Estado = 6 AND B.Validacion = 1 ))");


        return datatables()->of($solicitudes)->toJson();
    }


    public function aprobadosantiguas(){
        if (session('email') == null) {
            return redirect()->route('login');
        }
        $agenciaU = session('agenciau');

        $ultimoId = DB::table('autorizaciones')->max('ID');


        $limiteId = $ultimoId - 2000;


        $solicitudes = DB::select("
            SELECT DISTINCT
                A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos,
                B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte, B.Fecha, B.CodigoAutorizacion,
                B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta,
                B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor,
                B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion,
                B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, B.Bloqueado, B.ID_Concepto,
                C.Letra, C.No, C.Concepto, C.Areas,
                D.FechaInsercion
            FROM persona A
            JOIN autorizaciones B ON B.ID_Persona = A.ID
            JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
            JOIN documentosintesis D ON A.ID = D.ID_Persona
            WHERE
                B.Aprobacion = 1
                AND B.Estado = 4
                AND B.ID > $limiteId
            ORDER BY A.ID ASC
        ");

        return datatables()->of($solicitudes)->toJson();
    }

    public function rechazadosantiguas(){
        if (session('email') == null) {
            return redirect()->route('login');
        }
        $agenciaU = session('agenciau');

        $agencias = DB::select("SELECT NumAgencia FROM autorizaciones");

        $solicitudes = DB::select("SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte, B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, C.Letra, C.No, C.Concepto, C.Areas, D.FechaInsercion
        FROM persona A
        JOIN autorizaciones B ON B.ID_Persona = A.ID
        JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
        JOIN documentosintesis D ON A.ID = D.ID_Persona
        WHERE B.Estado = 5 OR ((B.Estado = 0) AND B.Coordinacion = 'C9')
        ORDER BY A.ID ASC");


        return datatables()->of($solicitudes)->toJson();
    }

    public function tramiteantiguas(){
        if (session('email') == null) {
            return redirect()->route('login');
        }
        $agenciaU = session('agenciau');

        $agencias = DB::select("SELECT NumAgencia FROM autorizaciones");

        $solicitudes = DB::select("SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte, B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, C.Letra, C.No, C.Concepto, C.Areas, D.FechaInsercion
        FROM persona A
        JOIN autorizaciones B ON B.ID_Persona = A.ID
        JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
        JOIN documentosintesis D ON A.ID = D.ID_Persona
        WHERE B.Estado = 2 && B.Coordinacion = 'C#'
        ORDER BY A.ID ASC");


        return datatables()->of($solicitudes)->toJson();
    }

    public function anuladosantiguas(){
        if (session('email') == null) {
            return redirect()->route('login');
        }
        $agenciaU = session('agenciau');

        $agencias = DB::select("SELECT NumAgencia FROM autorizaciones");

        $solicitudes = DB::select("SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte, B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, C.Letra, C.No, C.Concepto, C.Areas, D.FechaInsercion
        FROM persona A
        JOIN autorizaciones B ON B.ID_Persona = A.ID
        JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
        JOIN documentosintesis D ON A.ID = D.ID_Persona
        WHERE B.Estado = 7
        ORDER BY A.ID ASC");


        return datatables()->of($solicitudes)->toJson();
    }

    public function bloqueadosantiguas(){
        if (session('email') == null) {
            return redirect()->route('login');
        }
        $agenciaU = session('agenciau');

        $agencias = DB::select("SELECT NumAgencia FROM autorizaciones");

        $solicitudes = DB::select("SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte, B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, C.Letra, C.No, C.Concepto, C.Areas, D.FechaInsercion
        FROM persona A
        JOIN autorizaciones B ON B.ID_Persona = A.ID
        JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
        JOIN documentosintesis D ON A.ID = D.ID_Persona
        WHERE B.Bloqueado = 1
        ORDER BY A.ID ASC");


        return datatables()->of($solicitudes)->toJson();
    }

    public function standbyantiguas(){
        if (session('email') == null) {
            return redirect()->route('login');
        }
        $agenciaU = session('agenciau');

        $agencias = DB::select("SELECT NumAgencia FROM autorizaciones");

        $solicitudes = DB::select("SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte, B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, C.Letra, C.No, C.Concepto, C.Areas, D.FechaInsercion
        FROM persona A
        JOIN autorizaciones B ON B.ID_Persona = A.ID
        JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
        JOIN documentosintesis D ON A.ID = D.ID_Persona
        WHERE B.Estado = 8
        ORDER BY A.ID ASC");


        return datatables()->of($solicitudes)->toJson();
    }

    public function validarAutorizacionAntiguas(Request $request, $id)
    {

        $nombre = session('name');
        $rol = session('agenciau');
        $estadoautorizacion = $request->Estado;

        $fechadeSolicitud = Carbon::now('America/Bogota');

        Carbon::setLocale('es');
        $fechaStringfechadeSolicitud = $fechadeSolicitud->translatedFormat('F d Y-H:i:s');
        if($estadoautorizacion == '7'){
            $update = DB::table('autorizaciones')
            ->where('ID', $id)
            ->update([
                'Bloqueado' => 0,
                'ObservacionesGer' => $request->input('Observaciones'),
                'FechaAprobacion' => $fechaStringfechadeSolicitud,
                'Aprobacion' => 0,
                'Estado' => $estadoautorizacion,
            ]);
        } else if($estadoautorizacion == '1'){
            $update = DB::table('autorizaciones')
            ->where('ID', $id)
            ->update([
                'Bloqueado' => $request->input('Estado'),
                'ObservacionesGer' => $request->input('Observaciones'),
                'FechaAprobacion' => $fechaStringfechadeSolicitud,
                'Aprobacion' => 0,
            ]);
        }else if($estadoautorizacion == '0'){
            $update = DB::table('autorizaciones')
            ->where('ID', $id)
            ->update([
                'Bloqueado' => $request->input('Estado'),
                'ObservacionesGer' => $request->input('Observaciones'),
                'FechaAprobacion' => $fechaStringfechadeSolicitud,
                'Aprobacion' => 0
            ]);
        }else if ($estadoautorizacion == '4' || $estadoautorizacion == '5' || $estadoautorizacion == '3') {
            $update = DB::table('autorizaciones')
                ->where('ID', $id)
                ->update([
                    'ObservacionesGer' => $request->input('Observaciones'),
                    'Estado' => $request->input('Estado'),
                    'AprobadoPor' => $nombre,
                    'FechaAprobacion' => $fechaStringfechadeSolicitud,
                    'Aprobacion' => 1
                ]);
        }else if ($estadoautorizacion == '8') {
            $update = DB::table('autorizaciones')
                ->where('ID', $id)
                ->update([
                    'ObservacionesGer' => $request->Observaciones,
                    'Estado' => $request->input('Estado'),
                    'AprobadoPor' => $nombre,
                    'FechaAprobacion' => $fechaStringfechadeSolicitud,
                ]);
        }

        //AUDITORIA

        $nombreauditoria = session('name');
        $rol = session('rol');
        date_default_timezone_set('America/Bogota');
        $fechaHoraActual = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        $agencia = session('agenciau');
        $login = DB::insert("INSERT INTO auditoria (Hora_login, Usuario_nombre, Usuario_Rol, AgenciaU, Acción_realizada, Hora_Accion, Cedula_Registrada, cerro_sesion, IP) VALUES (?, ?, ?, ?, 'ValidoAutorizacionGerencia', ?, ?, ?, ?)", [
            null,
            $nombreauditoria,
            $rol,
            $agencia,
            $fechaHoraActual,
            $id . ' - '. $estadoautorizacion,
            null,
            $ip
        ]);



        return response()->json(['success' => true]);
    }


    public function solicitudescoordinacionantiguas(Request $request)
    {
        if (session('email') == null) {
            return redirect()->route('login');
        }
        $agenciaU = session('agenciau');

        $agencias = DB::select("SELECT NumAgencia FROM autorizaciones");

        $solicitudes = DB::select("
        SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte,B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, B.ID_Concepto, C.Letra, C.No, C.Concepto, C.Areas, D.FechaInsercion
        FROM persona A
        JOIN autorizaciones B ON B.ID_Persona = A.ID
        JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
        JOIN documentosintesis D ON A.ID = D.ID_Persona
        WHERE (B.Solicitud = 1 AND B.NumAgencia IN ('Jefatura')) && (B.Estado = 2)");


        return datatables()->of($solicitudes)->toJson();
    }

    public function data3antiguo()
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

        $usuariosEnviara = DB::select("SELECT * FROM users WHERE rol = 'Consultante' OR rol = 'Coordinacion' OR rol = 'Jefatura' ORDER BY name ASC");

        $userId = session('id'); // tu ID de usuario
        $userFiltrado = User::find($userId);
        $name = $userFiltrado->name;
        $celular = $userFiltrado->celular;

        return view('Usuario/Gerencia/coordinacion9', [
            'user' => $user,
            'grupos' => $grupos,
            'convencion' => $convencion,
            'usuariosEnviara' => $usuariosEnviara,
        ]);
    }

}

