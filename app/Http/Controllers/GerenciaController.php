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
        WHERE (B.Estado = 1 AND B.Validacion = 1 AND B.Bloqueado = 0) OR (B.Estado = 6 AND B.Validacion = 1)");


        return datatables()->of($solicitudes)->toJson();
    }


    public function aprobados(){
        $usuarioActual = Auth::user();
        $agenciaU = $usuarioActual->agenciau;

        $agencias = DB::select("SELECT NumAgencia FROM autorizaciones");

        $solicitudes = DB::select("SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte, B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, C.Letra, C.No, C.Concepto, C.Areas, D.FechaInsercion
        FROM persona A
        JOIN autorizaciones B ON B.ID_Persona = A.ID
        JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
        JOIN documentosintesis D ON A.ID = D.ID_Persona
        WHERE B.Aprobacion = 1 AND B.Estado = 4
        ORDER BY A.ID ASC");



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
        WHERE B.Estado = 5
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
        WHERE (B.Solicitud = 1 AND B.NumAgencia IN ('Jefatura')) && (B.Estado = 2)");


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
        $gruposcreados = DB::select("SELECT DISTINCT * FROM grupos_otrabajo ORDER BY nombregrupo ASC");

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
            'asignado' => $request->nombreempleado,
            'estado' => $request->estadopolitica,
        ]);



        $query = DB::select('SELECT * FROM grupos_otrabajo WHERE nombregrupo = ?', [$request->nombreempleado]);

        if (!empty($query)) {
            $integrantes = json_decode($query[0]->integrantes, true);
            DB::table('users')
            ->whereIn('id', $integrantes)
            ->increment('notificaciones', 1);
            $idsString = implode(',', $integrantes);

            $correos = DB::select("SELECT id,email,name,celular FROM users WHERE id IN ($idsString)");

            $emails = array_map(function($user) {
                if ($user->celular !== null) {
                    return [
                        'id' => $user->id,
                        'email' => $user->email,
                        'name' => $user->name,
                        'celular' => $user->celular
                    ];
                }else{
                    return [
                        'id' => $user->id,
                        'email' => $user->email,
                        'name' => $user->name,
                        'celular' => 0
                    ];
                }
            }, $correos);



            foreach ($emails as $emailData) {

                SendCorreoJob::dispatch($emailData['email'], $emailData['name'], $id_insertado, $fechaStringfechadeSolicitud);
                // $url = 'https://aio2.sigmamovil.com/api/sms';
                // $nombrecompleto = $emailData['name'];
                // $bearerToken = '10827|FDDjj6eKpiYZxk68a1XJZ2xPxNxNZwMN6EEWe0Rz16607cfa';

                // $data = [
                //     "idSmsCategory" => 1,
                //     "name" => "".$id_insertado."otrabajo",
                //     "receiver" => [
                //         [
                //             "indicative" => 57,
                //             "phone" => $emailData['celular'],
                //             "message" => "Estimado(a) ".$nombrecompleto.", le informamos que ha sido asignado(a) a una nueva orden de trabajo por parte de la DIRECCIÓN GENERAL, identificada con el número ".$id_insertado.", con fecha ".$fechaStringfechadeSolicitud."."

                //         ]
                //     ],
                //     "dateNow" => 1,
                //     "type" => "lote",
                //     "track" => 0,
                //     "sendPush" => 0,
                //     "api" => 1,
                //     "notification" => 0,
                //     "email" => "email@email.com.co",
                //     "rne" => 0
                // ];

                // $response = Http::withToken($bearerToken)->post($url, $data);
            }
        } else{
            DB::table('users')->where('name', $request->nombreempleado)->increment('notificaciones', 1);
            $queryindividual = DB::select('SELECT * FROM users WHERE name = ?', [$request->nombreempleado]);
            $email = $queryindividual[0]->email;
            $nombrecompleto = $queryindividual[0]->name;
            Mail::to($email)->send(new CorreoInfo($nombrecompleto, $id_insertado, $fechaStringfechadeSolicitud));
            $querycelular = DB::select('SELECT celular FROM users WHERE name = ?', [$request->nombreempleado]);
            $celular = $querycelular[0]->celular;

            if(!empty($celular)){
                $url = 'https://aio2.sigmamovil.com/api/sms';

                $bearerToken = '10827|FDDjj6eKpiYZxk68a1XJZ2xPxNxNZwMN6EEWe0Rz16607cfa';

                $data = [
                    "idSmsCategory" => 1,
                    "name" => "".$id_insertado."otrabajo",
                    "receiver" => [
                        [
                            "indicative" => 57,
                            "phone" => $celular,
                            "message" => "Estimado(a) ".$nombrecompleto.", le informamos que ha sido asignado(a) a una nueva orden de trabajo por parte de la DIRECCIÓN GENERAL, identificada con el número ".$id_insertado.", con fecha ".$fechaStringfechadeSolicitud."."
                        ]
                    ],
                    "dateNow" => 1,
                    "type" => "lote",
                    "track" => 0,
                    "sendPush" => 0,
                    "api" => 1,
                    "notification" => 0,
                    "email" => "email@email.com.co",
                    "rne" => 0
                ];

                $response = Http::withToken($bearerToken)->post($url, $data);
            }
        }



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







}
