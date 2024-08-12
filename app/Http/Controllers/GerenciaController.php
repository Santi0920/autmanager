<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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
        WHERE B.Aprobacion = 1 && B.Estado = 4
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
        WHERE (B.Aprobacion = 1 || B.Aprobacion = 0) && B.Estado = 5
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
        if($estadoautorizacion == '1'){
            $update = DB::table('autorizaciones')
            ->where('ID', $id)
            ->update([
                'Bloqueado' => $request->input('Estado'),
                'ObservacionesGer' => $request->input('Observaciones'),
                'FechaAprobacion' => $fechaStringfechadeSolicitud,
                'Aprobacion' => 0
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

            $total = $tramite + $validadocoordinadores + $rechazados + $aprobadogerencia;

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


            return view('Gerencia/estadisticas', ['decimalaprobados' => $decimalaprobados, 'decimalrechazados' => $decimalrechazados, 'decimalvalidados' => $decimalvalidados, 'porcentajetramite' => $porcentaje_tramite_con_decimales, 'tramite' => $tramite, 'validadocoordinadores' => $validadocoordinadores, 'rechazados' => $rechazados, 'aprobadogerencia' => $aprobadogerencia, 'total' => $total, 'nombresAgencia' => $nombresAgencia, 'year' => $year]);

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

        $total = $tramite + $validadocoordinadores + $rechazados + $aprobadogerencia;



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
        ]);

    }




}
