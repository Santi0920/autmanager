<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\CorreoInfo;
use App\Jobs\SendCorreoJob;

class GerenciaController extends Controller
{



    public function aprobarStandBy(){
        $nombre = session('name');
        $fechadeSolicitud = Carbon::now('America/Bogota');
        Carbon::setLocale('es');
        $fechaStringfechadeSolicitud = $fechadeSolicitud->translatedFormat('F d Y-H:i:s');

        $standbyCount = DB::table('historialestado as h1') ->where('h1.Estado', 'STAND BY') ->whereRaw('h1.ID = ( SELECT MAX(h2.ID) FROM historialestado h2 WHERE h2.ID_Autorizacion = h1.ID_Autorizacion )') ->count();
        // IDs de los registros que cumplen la condición
        $standbyAutorizaciones = DB::table('historialestado as h1')
            ->where('h1.Estado', 'STAND BY')
            ->whereRaw('h1.ID = (
                SELECT MAX(h2.ID) 
                FROM historialestado h2 
                WHERE h2.ID_Autorizacion = h1.ID_Autorizacion
            )')
            ->pluck('h1.ID_Autorizacion') // <-- Aquí tomamos ID_Autorizacion
            ->toArray();

        // Filtramos los que realmente existen en autorizaciones_2
        $validIds = DB::table('autorizaciones_2')
            ->whereIn('ID', $standbyAutorizaciones)
            ->pluck('ID')
            ->toArray();

        $insertData = [];

        foreach ($validIds as $idAutorizacion) {
            $insertData[] = [
                'NumArea' => "DR",
                'NomArea' => "DIRECCIÓN GENERAL",
                'Estado' => 'APROBADO',
                'Nombre' => $nombre,
                'Fecha' => $fechadeSolicitud,
                'FechaString' => $fechaStringfechadeSolicitud,
                'ID_Autorizacion' => $idAutorizacion
            ];
        }

        // Insertamos todos los registros a la vez
        DB::table('historialestado')->insert($insertData);

   


        return back()->with("correcto", "<span class='fs-4'>Se han aprobado todas las solicitudes con estado <span class='fw-bold'>STAND BY</span>(".$standbyCount.")</span>");
    }

    //TOOLTIP
    public function data1()
    {


        $agenciaU = session('agenciau');
        $user = DB::select("SELECT * FROM concepto_autorizaciones ORDER BY Letra ASC");
        $convencion = DB::select("SELECT * FROM convenciones ORDER BY ID ASC");

        return view('Gerencia/aprobar', ['user' => $user, 'convencion' => $convencion]);
    }

    public function data2()
    {


        $agenciaU = session('agenciau');
        $user = DB::select("SELECT * FROM concepto_autorizaciones ORDER BY Letra ASC");
        $convencion = DB::select("SELECT * FROM convenciones ORDER BY ID ASC");

        return view('Gerencia/coordinacion9', ['user' => $user, 'convencion' => $convencion]);
    }

    public function solicitudescoordinacion(Request $request)
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


    public function validarAutorizacioncoordinacion9(Request $request, $id)
    {

        $nombre = session('name');
        $noCoordinacion = session('agenciau');
        $estadoautorizacion = $request->Estado;


        $coordinacion = 'C9';

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
        } else if ($estadoautorizacion == '8' || $estadoautorizacion== "1") {
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

        //AUDITORIA

        $nombreauditoria = session('name');
        $rol = session('rol');
        date_default_timezone_set('America/Bogota');
        $fechaHoraActual = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        $agencia = session('agenciau');
        $login = DB::insert("INSERT INTO auditoria (Hora_login, Usuario_nombre, Usuario_Rol, AgenciaU, Acción_realizada, Hora_Accion, Cedula_Registrada, cerro_sesion, IP) VALUES (?, ?, ?, ?, 'ValidoAutorizacionCoordinacion9', ?, ?, ?, ?)", [
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


    public function contarsolicitudes(Request $request)
    {
        $base = DB::table('autorizaciones_2 AS B')
            ->join(DB::raw('(
                SELECT
                    ID_Autorizacion,
                    MAX(ID) AS UltimoHistorialID
                FROM historialestado
                GROUP BY ID_Autorizacion
            ) AS HMAX'), 'HMAX.ID_Autorizacion', '=', 'B.ID')
            ->join('historialestado AS H', 'H.ID', '=', 'HMAX.UltimoHistorialID');



        // En trámite
        $tramite = (clone $base)
            ->whereIn('H.Estado', [
                'TRÁMITE',
            ])
            ->count();

        // Validados
        $validadocoordinadores = (clone $base)
            ->where('H.Estado', 'VALIDADO')
            ->count();

        // Rechazados
        $rechazados = (clone $base)
            ->whereIn('H.Estado', [
                'CORREGIR',
            ])
            ->count();

        // Aprobados
        $aprobadogerencia = (clone $base)
            ->where('H.Estado', 'APROBADO')
            ->count();

        // Anulados
        $anuladosgerencia = (clone $base)
            ->where('H.Estado', 'ANULADO')
            ->count();


        $total = DB::table('autorizaciones_2')->max('ID');

        $nombresArea = DB::table('historialestado')
            ->select('NomArea', 'NumArea')
            ->whereNotNull('NomArea')
            ->where('NomArea', '!=', '')
            ->distinct()
            ->orderBy('NomArea', 'asc')
            ->get();


        $year = DB::table('historialestado')
            ->select(DB::raw("SUBSTRING_INDEX(SUBSTRING_INDEX(Fecha, ' ', -1), '-', 1) AS year"))
            ->whereNotNull('Fecha')
            ->distinct()
            ->orderBy('year', 'asc')
            ->get();


        $porcentaje_tramite = ($total != 0) ? ($tramite / $total * 100) : 0;
        $porcentaje_tramite_con_decimales = round($porcentaje_tramite, 2);

        $porcentajevalidos = ($total != 0) ? ($validadocoordinadores / $total * 100) : 0;
        $decimalvalidados = round($porcentajevalidos, 2);

        $porcentajerechazados = ($total != 0) ? ($rechazados / $total * 100) : 0;
        $decimalrechazados = round($porcentajerechazados, 2);

        $porcentajeaprobados = ($total != 0) ? ($aprobadogerencia / $total * 100) : 0;
        $decimalaprobados = round($porcentajeaprobados, 2);

        $porcentajeanulados = ($total != 0) ? ($anuladosgerencia / $total * 100) : 0;
        $decimalanulados = round($porcentajeanulados, 2);

        return view('Gerencia/estadisticas', [
            'porcentajeanulados' => $porcentajeanulados,
            'anuladosgerencia' => $anuladosgerencia,
            'decimalanulados' => $decimalanulados,
            'decimalaprobados' => $decimalaprobados,
            'decimalrechazados' => $decimalrechazados,
            'decimalvalidados' => $decimalvalidados,
            'porcentajetramite' => $porcentaje_tramite_con_decimales,
            'tramite' => $tramite,
            'validadocoordinadores' => $validadocoordinadores,
            'rechazados' => $rechazados,
            'aprobadogerencia' => $aprobadogerencia,
            'total' => $total,
            'nombresArea' => $nombresArea,
            'year' => $year
        ]);
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


    public function cuentasSuspendidas(Request $request)
    {
        if (session('email') == null) {
            return redirect()->route('login');
        }
        $agenciaU = session('agenciau');

        $solicitudes = DB::select("SELECT * FROM users WHERE activo = 0 ORDER BY agenciau ASC");


        return datatables()->of($solicitudes)->toJson();
    }
    public function agenciastabla(Request $request)
    {
        if (session('email') == null) {
            return redirect()->route('login');
        }

        $solicitudes = DB::table('agencias')
            ->select('ID', 'NameAgencia', 'NumAgencia')
            ->where('activo', 1)
            ->orderBy('NameAgencia', 'ASC')
            ->get();

    
        $data = $solicitudes->map(function ($a) {
            return [
                'ID_Agencia'   => $a->ID,
                'NameAgencia'      => $a->NameAgencia,
                'NumAgencia' => $a->NumAgencia,
            ];
        });

        return datatables()->of($data)->toJson();
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
        $validacionnombre = DB::select('select * from users WHERE name = ?',[$nombre]);

        if (!empty($validacionnombre) || !empty($validacioncorreo)) {

            if (!empty($validacioncorreo) && isset($validacioncorreo[0]->email)) {
                return back()->with("incorrecto", "<span class='fs-4'>Ya existe un usuario vinculado al correo <b>".$correo."</b></span>");
            }else{
                return back()->with("incorrecto", "<span class='fs-4'>Ya existe un usuario con el nombre <b>".$nombre."</b></span>");
            }
        }


        if($tipocreacion == "crearDAgencia"){
            $consultaCC = DB::table('agencias')->where('NameAgencia', '=', $request->agenciaDAgencia)->get();
            $codigoDAgencia = $consultaCC[0]->NumAgencia . '00';


            $id_insertado = DB::table('users')->insertGetId([
                'name' => $nombre,
                'rol' => 'Consultante',
                'agenciau' => $request->agenciaDAgencia,
                'codigo' => $codigoDAgencia,
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
                'codigo' => '11'.$request->selectcoordinacion.'0',
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

    public function eliminarUsuario($id, Request $request)
    {
        $tipo = $request->input('tipo');

        $nombreauditoria = session('name'); 
        $rol = session('rol'); 
        date_default_timezone_set('America/Bogota'); 
        $fechaHoraActual = date('Y-m-d H:i:s'); 
        $ip = $_SERVER['REMOTE_ADDR']; 
        $agencia = session('agenciau'); 
        $login = DB::insert("INSERT INTO auditoria (Hora_login, Usuario_nombre, Usuario_Rol, AgenciaU, Acción_realizada, Hora_Accion, Cedula_Registrada, cerro_sesion, IP) VALUES (?, ?, ?, ?, 'SeEliminoUsuarioenelpaneladmin', ?, ?, ?, ?)", [ null, $nombreauditoria, $rol, $agencia, $fechaHoraActual, $id, null, $ip ]);

        try {
            $result = DB::transaction(function () use ($tipo, $id) {

                switch ($tipo) {
                    case 'concepto': {
                        $concepto = DB::table('concepto_autorizaciones')->where('ID', $id)->first();

                        if (!$concepto) {
                            return [
                                'ok' => false,
                                'message' => "No existe el concepto.",
                                'tipo' => $tipo,
                                'id' => $id
                            ];
                        }

                        DB::table('concepto_autorizaciones')->where('ID', $id)->update(['activo' => 0]);

                        return [
                            'ok' => true,
                            'message' => "Se eliminó satisfactoriamente el concepto: {$concepto->Concepto}.",
                            'tipo' => $tipo,
                            'id' => $id,
                            // Si en el front quieres remover fila, sirve devolver rowId
                        ];
                    }

                    case 'agencia': {
                        $agencia = DB::table('agencias')->where('NameAgencia', $id)->first();

                        if (!$agencia) {
                            return [
                                'ok' => false,
                                'message' => "No existe la agencia.",
                                'tipo' => $tipo,
                                'id' => $id
                            ];
                        }

                        $idagencia = $agencia->NumAgencia;

                        DB::table('users')
                            ->where('agenciau', $id)
                            ->update(['password' => bcrypt("bloqueada")]);

                        DB::table('agencias')
                            ->where('NameAgencia', $id)
                            ->update(['activo' => 0]);

                        DB::table('users')
                            ->whereJsonContains('agencias_id', $idagencia)
                            ->update([
                                'agencias_id' => DB::raw(
                                    "JSON_REMOVE(agencias_id, JSON_UNQUOTE(JSON_SEARCH(agencias_id, 'one', {$idagencia})))"
                                )
                            ]);

                        return [
                            'ok' => true,
                            'message' => "Se eliminó satisfactoriamente la agencia: {$id}.",
                            'tipo' => $tipo,
                            'id' => $id
                        ];
                    }

                    case 'usuario': {
                        $usuario = DB::table('users')->select('id', 'name', 'agenciau')->where('id', $id)->first();

                        if (!$usuario) {
                            return [
                                'ok' => false,
                                'message' => "No existe el usuario.",
                                'tipo' => $tipo,
                                'id' => $id
                            ];
                        }

                        DB::table('users')->where('id', $id)->update(['activo' => 0]);

                        $grupos = DB::table('grupos_otrabajo')
                            ->whereRaw("JSON_SEARCH(integrantes, 'one', ?) IS NOT NULL", [$id])
                            ->get();

                        foreach ($grupos as $grupo) {
                            $integrantes = json_decode($grupo->integrantes, true) ?: [];

                            $key = array_search((string)$id, array_map('strval', $integrantes), true);
                            if ($key !== false) unset($integrantes[$key]);

                            DB::table('grupos_otrabajo')
                                ->where('id', $grupo->id)
                                ->update(['integrantes' => json_encode(array_values($integrantes))]);
                        }

                        return [
                            'ok' => true,
                            'message' => "Se eliminó satisfactoriamente el usuario: {$usuario->name}.",
                            'tipo' => $tipo,
                            'id' => $id
                        ];
                    }

                    default:
                        return [
                            'ok' => false,
                            'message' => "Tipo inválido para eliminar.",
                            'tipo' => $tipo,
                            'id' => $id
                        ];
                }
            });

            return response()->json($result, $result['ok'] ? 200 : 422);

        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error interno al procesar la eliminación.',
                'debug' => app()->environment('production') ? null : $e->getMessage(),
            ], 500);
        }
    }

    public function activarCSuspendida($id, Request $request)
    {
        $nombreauditoria = session('name');
        $rol = session('rol');
        date_default_timezone_set('America/Bogota');
        $fechaHoraActual = date('Y-m-d H:i:s');
        $ip = $request->ip(); // ✅
        $agencia = session('agenciau');

        try {
            $res = DB::transaction(function () use ($id, $nombreauditoria, $rol, $agencia, $fechaHoraActual, $ip) {

                DB::insert(
                    "INSERT INTO auditoria
                    (Hora_login, Usuario_nombre, Usuario_Rol, AgenciaU, Acción_realizada, Hora_Accion, Cedula_Registrada, cerro_sesion, IP)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
                    [null, $nombreauditoria, $rol, $agencia, "SeActivoCuentaSuspendida - $id", $fechaHoraActual, $id, null, $ip]
                );

                $user = DB::table('users')->where('id', $id)->first();

                if (!$user) {
                    return [
                        'ok' => false,
                        'message' => 'No existe el usuario.'
                    ];
                }

                DB::table('users')
                    ->where('id', $id)
                    ->update(['activo' => 1]);

                return [
                    'ok' => true,
                    'message' => "Se habilitó satisfactoriamente la cuenta ({$user->email})."
                ];
            });

            return response()->json($res, $res['ok'] ? 200 : 422);

        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error interno al habilitar la cuenta.',
                'debug' => app()->environment('production') ? null : $e->getMessage(),
            ], 500);
        }
    }

    public function eliminarConcepto($id, $area, Request $request)
    {
        $nombreauditoria = session('name');
        $rol = session('rol');
        date_default_timezone_set('America/Bogota');
        $fechaHoraActual = date('Y-m-d H:i:s');
        $ip = $request->ip();
        $agencia = session('agenciau');

        try {
            $res = DB::transaction(function () use ($id, $area, $nombreauditoria, $rol, $agencia, $fechaHoraActual, $ip) {

                // 1) Auditoría
                DB::insert(
                "INSERT INTO auditoria
                (Hora_login, Usuario_nombre, Usuario_Rol, AgenciaU, Acción_realizada, Hora_Accion, Cedula_Registrada, cerro_sesion, IP)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [
                    null,
                    $nombreauditoria,
                    $rol,
                    $agencia,
                    'SeEliminoConceptoenelpaneladmin',
                    $fechaHoraActual,
                    $id,
                    null,
                    $ip
                ]
                );

                // 2) Update
                $updated = DB::table('concepto_autorizaciones')
                    ->where('Areas', $area)
                    ->update([
                        'Areas' => 'GLOBAL',
                        'No' => 0,
                    ]);

                // Esto NO es error, pero te ayuda a ver si realmente encontró filas
                if ($updated === 0) {
                    return [
                        'ok' => false,
                        'message' => "No se actualizó ningún registro. Verifica si existe el área '{$area}'."
                    ];
                }

                return [
                    'ok' => true,
                    'message' => "Se eliminó satisfactoriamente el ÁREA ({$area})."
                ];
            });

            return response()->json($res, $res['ok'] ? 200 : 422);

        } catch (\Throwable $e) {

            // ✅ Log completo (míralo en storage/logs/laravel.log)
            Log::error('Error eliminarConcepto', [
                'id' => $id,
                'area' => $area,
                'ip' => $ip,
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            // ✅ Debug visible SIEMPRE mientras estás probando
            return response()->json([
                'ok' => false,
                'message' => 'Error interno al eliminar el área.',
                'debug' => $e->getMessage(),   // <- AQUÍ ya verás el error real
                'code' => $e->getCode(),
            ], 500);
        }
    }
    public function guardarcoordinacion(Request $request)
    {
        try {

            $members = $request->members ?? [];

            // 1️⃣ Buscar el usuario Coordinacion dentro de los miembros
            $coordinador = DB::table('users')
                ->select('id', 'rol', 'agenciau')
                ->whereIn('id', $members)
                ->where('rol', 'Coordinacion')
                ->first();

            if (!$coordinador) {
                return response()->json([
                    'ok' => false,
                    'message' => 'No se encontró un usuario con rol Coordinacion dentro de los integrantes.'
                ], 422);
            }

            // 2️⃣ Extraer número de "Coordinacion X"
            $agenciaU = (string) $coordinador->agenciau; // ej: "Coordinacion 4"

            if (!preg_match('/(\d+)\s*$/', $agenciaU, $m)) {
                return response()->json([
                    'ok' => false,
                    'message' => "No se pudo determinar el número de la coordinación desde '{$agenciaU}'."
                ], 422);
            }

            $num = (int) $m[1];          // 4
            $codigo = '11' . ($num * 10);  // 11.40
            
            // 3️⃣ ACTUALIZAR ese usuario en tabla users
            DB::table('users')
                ->where('id', $coordinador->id)
                ->update([
                    'codigo' => $codigo
                ]);

            // 4️⃣ TU LÓGICA ORIGINAL (guardar grupo)
            $integrantesJson = json_encode($members);

            $validarnombre = DB::select(
                'SELECT * FROM grupos_otrabajo WHERE nombregrupo = ?',
                [$request->name]
            );

            if (empty($validarnombre)) {

                $consultantes = DB::select(
                    'SELECT id FROM users WHERE rol = ?',
                    ['D. de Agencia']
                );

                $consultantesArray = [];
                foreach ($consultantes as $consultante) {
                    $consultantesArray[] = $consultante->id;
                }

                $integrantesArray = array_merge($members, $consultantesArray);
                $integrantesJson = json_encode($integrantesArray);

                $id_insertado = DB::table('grupos_otrabajo')->insertGetId([
                    'nombregrupo' => $request->name,
                    'integrantes' => $integrantesJson,
                ]);

                return response()->json([
                    'ok' => true,
                    'mode' => 'created',
                    'id' => $id_insertado,
                    'codigo_asignado' => $codigo
                ]);
            } else {
                Log::info("El grupo");
                $grupoExistente = $validarnombre[0];
                $integrantesExistentes = json_decode($grupoExistente->integrantes, true) ?: [];
                $nuevosIntegrantes = json_decode($integrantesJson, true) ?: [];

                $integrantesCombinados = array_values(
                    array_unique(array_merge($integrantesExistentes, $nuevosIntegrantes))
                );

                DB::table('grupos_otrabajo')
                    ->where('nombregrupo', $request->name)
                    ->update([
                        'integrantes' => json_encode($integrantesCombinados)
                    ]);

                return response()->json([
                    'ok' => true,
                    'mode' => 'updated',
                    'codigo_asignado' => $codigo
                ]);
            }

        } catch (\Throwable $e) {
            Log::error('Error guardarcoordinacion', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Error interno al guardar coordinación.',
                'debug' => $e->getMessage()
            ], 500);
        }
    }
    public function editarusuario(Request $request)
    {
        try {

            $nombre         = $request->nombre;
            $agencia        = $request->agencia;
            $celular        = $request->celular;
            $contrasena     = $request->contrasena;
            $correo         = $request->correo;

            $agencianame    = $request->agencianame;
            $centrocosto    = $request->cc;

            $id             = $request->id;

            $nombreConcepto = $request->concepto;
            $area           = $request->area;
            $codigoArea     = $request->codigoarea;

            // -------- VALIDACIONES GENERALES --------
            $consultaRol = DB::select("SELECT * FROM users WHERE email = ?", [$correo]);

            $consultaNombre = DB::select(
                "SELECT * FROM users WHERE name = ? AND id != ?",
                [$nombre, $id]
            );

            if (count($consultaNombre) >= 1) {
                return response()->json([
                    'ok' => false,
                    'message' => "Ya existe un usuario con el nombre {$nombre}. Por favor, elija otro nombre."
                ], 422);
            }

            // -------- 1) EDITAR CONCEPTO --------
            if ($area != null || $nombreConcepto != null) {

                $consultaConcepto = DB::table("concepto_autorizaciones")
                    ->where("Concepto", $nombreConcepto)
                    ->first();

                // (Tu validación estaba rara: si existe, siempre va a ser igual)
                // Lo dejo coherente: si existe y no es el mismo ID, error
                if ($consultaConcepto && (string)$consultaConcepto->ID !== (string)$id) {
                    return response()->json([
                        'ok' => false,
                        'message' => "El concepto {$nombreConcepto} ya existe!"
                    ], 422);
                }

                // Buscar si ya existe el área
                $existeConcepto = DB::table('concepto_autorizaciones')
                    ->where('Areas', $area)
                    ->get();

                if ($codigoArea != null) {
                    $existeCodigoArea = DB::table('concepto_autorizaciones')
                        ->where('No', $codigoArea)
                        ->get();

                    if ($existeCodigoArea->isNotEmpty()) {
                        return response()->json([
                            'ok' => false,
                            'message' => "Ya existe un ÁREA con el código {$codigoArea} vinculado a: {$existeCodigoArea[0]->Areas}"
                        ], 422);
                    }
                }

                if ($existeConcepto->isNotEmpty()) {
                    $codigoArea = $existeConcepto[0]->No;
                } else {
                    $codigoArea = 0;
                }

                if ($area == 'OTRO') {
                    $area = $request->otroArea;
                    $codigoArea = $request->codigoarea;
                }

                DB::table('concepto_autorizaciones')
                    ->where('ID', $id)
                    ->update([
                        'Concepto' => $nombreConcepto,
                        'Areas'    => $area,
                        'No'       => $codigoArea,
                    ]);

                return response()->json([
                    'ok' => true,
                    'message' => "Se actualizó satisfactoriamente el concepto ({$nombreConcepto})."
                ]);
            }

            // -------- 2) EDITAR AGENCIA --------
            if ($agencianame != null || $centrocosto != null) {

                $consultaagencia = DB::table("agencias")
                    ->where("NameAgencia", $agencianame)
                    ->where("activo", 1)
                    ->count();

                $consultacentrocosto = DB::table("agencias")
                    ->where("NumAgencia", $centrocosto)
                    ->where("activo", 1)
                    ->count();

                if ($consultaagencia > 0) {
                    return response()->json([
                        'ok' => false,
                        'message' => "La agencia {$agencianame} ya existe!"
                    ], 422);
                } else if ($consultacentrocosto > 0) {
                    return response()->json([
                        'ok' => false,
                        'message' => "El centro de costo {$centrocosto} ya existe!"
                    ], 422);
                }

                DB::table('agencias')
                    ->where('ID', $id)
                    ->update([
                        'NameAgencia' => $agencianame,
                        'NumAgencia'  => $centrocosto,
                    ]);

                return response()->json([
                    'ok' => true,
                    'message' => "Se actualizó satisfactoriamente la agencia ({$agencianame} - {$centrocosto})."
                ]);
            }

            // -------- 3) EDITAR USUARIO --------
            if (empty($consultaRol)) {
                return response()->json([
                    'ok' => false,
                    'message' => "No se encontró el usuario por correo {$correo}."
                ], 422);
            }

            $rolUser = $consultaRol[0]->rol;
            $codigodpto = null;



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

            if ($rolUser == 'Jefatura') {

                $agencia = $request->jefatura;
                $codigodpto = $request->codigodpto;

            } else if ($rolUser == 'Coordinacion') {

                $agencia = $request->coordinacion2; 

            
                if (preg_match('/(\d+)\s*$/', $agencia, $match)) {

                    $numero = (int) $match[1]; 

                    $codigodpto = '11' . ($numero * 10);

                } else {
                    $codigodpto = null; 
                }
            } else if ($rolUser == 'Consultante') {
                $codigodpto = $consultaRol[0]->codigo;
            }


  
            foreach ($agenciasConCodigos as $nombreAgencia => $codigo) {
                DB::table('users')
                    ->where('agenciau', $nombreAgencia)
                    ->update(['codigo' => $codigo]);
            }
            // Update base
            $updateData = [
                'name'       => $nombre,
                'agenciau'   => $agencia,
                'codigo'     => $codigodpto,
                'celular'    => $celular,
                'agencias_id'=> $request->agencias_hidden ?: null,
            ];

            // ✅ Solo actualiza password si viene una nueva
            if (!empty($contrasena)) {
                $updateData['password'] = bcrypt($contrasena);
            }

            DB::table('users')
                ->where('email', $correo)
                ->update($updateData);

            return response()->json([
                'ok' => true,
                'message' => "Se actualizó satisfactoriamente el usuario ({$nombre} - {$agencia})."
            ]);

        } catch (\Throwable $e) {
            Log::error('Error editarusuario', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Error interno al editar.',
                'debug' => $e->getMessage(),
            ], 500);
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
