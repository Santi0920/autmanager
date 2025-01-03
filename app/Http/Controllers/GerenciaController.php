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
    public function solicitudes(Request $request)
    {
        $usuarioActual = Auth::user();
        $agenciaU = $usuarioActual->agenciau;

        $agencias = DB::select("SELECT NumAgencia FROM autorizaciones");

        $solicitudes = DB::select("SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte, B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, B.Bloqueado, C.Letra, C.No, C.Concepto, C.Areas, D.FechaInsercion
        FROM persona A
        JOIN autorizaciones B ON B.ID_Persona = A.ID
        JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
        JOIN documentosintesis D ON A.ID = D.ID_Persona
        WHERE  B.Bloqueado = 0 AND ((B.Estado = 1 AND B.Validacion = 1) OR (B.Estado = 6 AND B.Validacion = 1 ))");


        return datatables()->of($solicitudes)->toJson();
    }


    public function aprobados(){
        $usuarioActual = Auth::user();
        $agenciaU = $usuarioActual->agenciau;


        $ultimoId = DB::table('persona')->max('ID');


        $limiteId = $ultimoId - 1000;

        $solicitudes = DB::select("
            SELECT DISTINCT
                A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos,
                B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte, B.Fecha, B.CodigoAutorizacion,
                B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta,
                B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor,
                B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion,
                B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer,
                C.Letra, C.No, C.Concepto, C.Areas,
                D.FechaInsercion
            FROM persona A
            JOIN autorizaciones B ON B.ID_Persona = A.ID
            JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
            JOIN documentosintesis D ON A.ID = D.ID_Persona
            WHERE
                B.Aprobacion = 1
                AND B.Estado = 4
                AND B.ID > 4000
            ORDER BY A.ID ASC
        ");

        return datatables()->of($solicitudes)->toJson();
    }



    public function rechazados(){
        $usuarioActual = Auth::user();
        $agenciaU = $usuarioActual->agenciau;

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

    public function tramite(){
        $usuarioActual = Auth::user();
        $agenciaU = $usuarioActual->agenciau;

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

    public function anulados(){
        $usuarioActual = Auth::user();
        $agenciaU = $usuarioActual->agenciau;

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

    public function bloqueados(){
        $usuarioActual = Auth::user();
        $agenciaU = $usuarioActual->agenciau;

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

    public function validarAutorizacion(Request $request, $id)
    {
        $usuarioActual = Auth::user();
        $nombre = $usuarioActual->name;
        $rol = $usuarioActual->agenciau;
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
            }

        //AUDITORIA
        $usuarioActual = Auth::user();
        $nombreauditoria = $usuarioActual->name;
        $rol = $usuarioActual->rol;
        date_default_timezone_set('America/Bogota');
        $fechaHoraActual = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        $agencia = $usuarioActual->agenciau;
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

    public function data1()
    {

        $usuarioActual = Auth::user();
        $agenciaU = $usuarioActual->agenciau;
        $user = DB::select("SELECT * FROM concepto_autorizaciones ORDER BY Letra ASC");

        return view('Gerencia/aprobar', ['user' => $user]);
    }

    public function data2()
    {

        $usuarioActual = Auth::user();
        $agenciaU = $usuarioActual->agenciau;
        $user = DB::select("SELECT * FROM concepto_autorizaciones ORDER BY Letra ASC");

        return view('Gerencia/coordinacion9', ['user' => $user]);
    }

    public function solicitudescoordinacion(Request $request)
    {
        $usuarioActual = Auth::user();
        $agenciaU = $usuarioActual->agenciau;

        $agencias = DB::select("SELECT NumAgencia FROM autorizaciones");

        $solicitudes = DB::select("
        SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte,B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, C.Letra, C.No, C.Concepto, C.Areas, D.FechaInsercion
        FROM persona A
        JOIN autorizaciones B ON B.ID_Persona = A.ID
        JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
        JOIN documentosintesis D ON A.ID = D.ID_Persona
        WHERE (B.Solicitud = 1 AND B.NumAgencia IN ('Jefatura')) && (B.Estado = 2) || (B.Estado = 2 && B.NumAgencia = 91) || (B.Estado = 2 && B.NumAgencia = 31) || (B.Estado = 2 && B.NumAgencia = 30) || (B.Estado = 2 && B.NumAgencia = 68)");


        return datatables()->of($solicitudes)->toJson();
    }


    public function validarAutorizacioncoordinacion9(Request $request, $id)
    {
        $usuarioActual = Auth::user();
        $nombre = $usuarioActual->name;
        $noCoordinacion = $usuarioActual->agenciau;
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

        //AUDITORIA
        $usuarioActual = Auth::user();
        $nombreauditoria = $usuarioActual->name;
        $rol = $usuarioActual->rol;
        date_default_timezone_set('America/Bogota');
        $fechaHoraActual = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        $agencia = $usuarioActual->agenciau;
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
        $usuarioActual = Auth::user();
        $agenciaU = $usuarioActual->agenciau;
        $user = DB::select("SELECT * FROM concepto_autorizaciones ORDER BY Letra ASC");
        $agencia = DB::select("SELECT DISTINCT NomAgencia FROM autorizaciones ORDER BY NomAgencia ASC");
        $solicitadopor = DB::select("SELECT DISTINCT SolicitadoPor FROM autorizaciones ORDER BY SolicitadoPor ASC");
        $validadopor = DB::select("SELECT DISTINCT ValidadoPor FROM autorizaciones ORDER BY ValidadoPor ASC");


        return view('Gerencia/filtrarconcepto', [
            'user' => $user,
            'agencia' => $agencia,
            'solicitadopor' => $solicitadopor,
            'validadopor' => $validadopor
        ]);
    }



        public function filtrarconcepto(Request $request)
    {
        $usuarioActual = Auth::user();
        $agenciaU = $usuarioActual->agenciau;

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
        $cargos = DB::select("SELECT DISTINCT id,agenciau,name FROM users WHERE agenciau != 'Asociacion Virtual' AND name != 'Jesus H BOLAÑOS'  ORDER BY name ASC");
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



        return view('Gerencia/otrabajo', ['cargos' => $cargos, 'gruposcreados' => $gruposcreados]);

    }

    public function otrabajodatatable(Request $request)
    {

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
                ->whereIn('id', $integrantesArray)
                ->select('name', 'agenciau')
                ->get();

            $nombresIntegrantes = $integrantesDetalles->map(function ($integrante) {
                return $integrante->name . ' - ' . $integrante->agenciau;
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
        $usuarioActual = Auth::user();
        $agenciaU = $usuarioActual->agenciau;

        $agencias = DB::select("SELECT NumAgencia FROM autorizaciones");

        $solicitudes = DB::select("SELECT * FROM users WHERE rol = 'Consultante'  AND activo = 1 ORDER BY agenciau ASC");




        return datatables()->of($solicitudes)->toJson();
    }

    public function coordinaciones(Request $request)
    {
        $usuarioActual = Auth::user();
        $agenciaU = $usuarioActual->agenciau;

        $agencias = DB::select("SELECT NumAgencia FROM autorizaciones");

        $solicitudes = DB::select("SELECT * FROM users WHERE rol = 'Coordinacion'  AND activo = 1 ORDER BY agenciau ASC");



        return datatables()->of($solicitudes)->toJson();
    }

    public function jefaturas(Request $request)
    {
        $usuarioActual = Auth::user();
        $agenciaU = $usuarioActual->agenciau;

        $agencias = DB::select("SELECT NumAgencia FROM autorizaciones");

        $solicitudes = DB::select("SELECT * FROM users WHERE rol = 'Jefatura'  AND activo = 1 ORDER BY agenciau ASC");



        return datatables()->of($solicitudes)->toJson();
    }

    public function agenciastabla(Request $request)
    {
        $solicitudes = DB::select("SELECT * FROM agencias WHERE activo = 1 ORDER BY NameAgencia ASC");



        return datatables()->of($solicitudes)->toJson();
    }


    public function cargaragencias(Request $request)
    {
        $cargos = DB::select("SELECT DISTINCT id,agenciau,name FROM users WHERE rol = 'Consultante' ORDER BY name ASC");
        $agencias = DB::select("SELECT * FROM agencias ORDER BY NumAgencia ASC");


        $jefaturas = DB::select("SELECT DISTINCT agenciau FROM users WHERE rol = 'Jefatura'");
        $codigos = DB::select("SELECT DISTINCT codigo FROM users WHERE rol = 'Jefatura'");
        $coordinaciones = DB::select("SELECT DISTINCT agenciau FROM users WHERE rol = 'Coordinacion'");
        return view('Gerencia/admin', ['cargos' => $cargos, 'agencias' => $agencias, 'jefaturas' => $jefaturas, 'coordinaciones' => $coordinaciones, 'codigos' => $codigos]);

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

            // Números en formato de cadenas para cada coordinación
            $selectedPeople1 = ['43', '76', '35', '34', '36', '37', '38', '40', '41', '87', '93', '96'];
            $selectedPeople2 = ['86', '33', '39', '46', '70', '77', '78', '80', '88', '92', '98'];
            $selectedPeople3 = ['73', '32', '42', '47', '81', '82', '83', '85', '90', '94'];
            $selectedPeople4 = ['44', '13', '45', '48', '49', '74', '75', '84', '89', '95', '97'];

            // Actualizar para "Coordinacion 1"
            DB::table('users')
                ->where('agenciau', 'Coordinacion 1')
                ->update([
                    'agencias_id' => json_encode($selectedPeople1)
                ]);

            // Actualizar para "Coordinacion 2"
            DB::table('users')
                ->where('agenciau', 'Coordinacion 2')
                ->update([
                    'agencias_id' => json_encode($selectedPeople2)
                ]);

            // Actualizar para "Coordinacion 3"
            DB::table('users')
                ->where('agenciau', 'Coordinacion 3')
                ->update([
                    'agencias_id' => json_encode($selectedPeople3)
                ]);

            // Actualizar para "Coordinacion 4"
            DB::table('users')
                ->where('agenciau', 'Coordinacion 4')
                ->update([
                    'agencias_id' => json_encode($selectedPeople4)
                ]);



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
            $consultaagencia = DB::table("agencias")->where("NameAgencia", $request->agencianombre)->count();
            $consultacentrocosto = DB::table("agencias")->where("NumAgencia", $request->centrocosto)->count();

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
        }



    }

    public function eliminarUsuario($id){

        $usuarioActual = Auth::user();
        $nombreauditoria = $usuarioActual->name;
        $rol = $usuarioActual->rol;
        date_default_timezone_set('America/Bogota');
        $fechaHoraActual = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        $agencia = $usuarioActual->agenciau;
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

        $usuarioRol = DB::select("SELECT agenciau, name from users WHERE id = ?",[$id]);


        if($existeAgencia>0){
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
            return back()->with("correcto", "<span class='fs-4'>Se eliminó satisfactoriamente el usuario <b>".$usuarioRol[0]->name."</b> <br>(<b>Rol:</b> <span class='badge bg-primary fw-bold'>".$usuarioRol[0]->agenciau."</span>).</span>");
        }



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

        $consultaRol = DB::select("SELECT * FROM users WHERE email = ?", [$correo]);



        if($agencianame != null || $centrocosto != null){
            $consultaagencia = DB::table("agencias")->where("NameAgencia", $agencianame)->count();
            $consultacentrocosto = DB::table("agencias")->where("NumAgencia", $centrocosto)->count();

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
