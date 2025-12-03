<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class Todos extends Controller
{
    public function otrabajodatatable(Request $request)
    {
        if (session('email') == null) {
            return redirect()->route('login');
        }

        $rol = session('rol');
        $name = session('name');
        $id = session('id');


        $selectedPeople = DB::table('grupos_otrabajo')
            ->whereJsonContains('integrantes', $id)
            ->get();


        $nombreGrupos = [];
        foreach ($selectedPeople as $grupos) {
            $nombreGrupos[] = $grupos->nombregrupo;
        }

        // ESTABLECER NOTIFICACIONES EN 0 PORQUE YA REVISO
        DB::table('users')->where('name', $name)->update(['notificaciones' => 0]);


        $solicitudes = DB::table('ordentrabajo')
            ->where(function ($query) use ($nombreGrupos) {
                foreach ($nombreGrupos as $grupo) {
                    $query->orWhereJsonContains('asignado', $grupo);
                }
            })
            ->orWhere(function ($query) use ($name) {
                $query->orWhereJsonContains('asignado', $name);
            })
            ->get();

        return datatables()->of($solicitudes)->toJson();
    }


    public function celularpendiente(Request $request){

        $rol = session('rol');
        $name = session('name');
        $id = session('id');

        DB::table('users')->where('id', $id)
        ->update([
            'celular' => $request->numeroCelular
        ]);

        return back()->with("correcto", "<span class='fs-4'>No Celular (".$request->numeroCelular.") registrado correctamente!</span>");
    }


    public function solicitudes(Request $request)
    {
        if (session('email') == null) {
            return redirect()->route('login');
        }

        if(session('rol') == 'Coordinacion'){
            $agenciaU = session('agenciau');
            $id = session('id');
            $agencias = DB::select("SELECT NumAgencia FROM autorizaciones");


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
                $solicitudes = DB::select(
                    "SELECT DISTINCT
                        A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos,
                        B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte, B.Fecha, B.CodigoAutorizacion,
                        B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona,
                        B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion,
                        B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor,
                        B.FechaAprobacion, B.ObservacionesGer, B.Bloqueado, B.ID_Concepto,
                        C.Letra, C.No, C.Concepto, C.Areas,
                        D.FechaInsercion
                    FROM persona A
                    JOIN autorizaciones B ON B.ID_Persona = A.ID
                    JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
                    JOIN documentosintesis D ON A.ID = D.ID_Persona
                    WHERE
                        B.ID > 6000
                        AND (
                            (B.Estado IN (0, 1, 2, 5, 6))
                            OR (B.Validacion = 1 AND B.AprobadoPor IS NULL)
                            OR (B.Estado = 5 OR B.Estado = 0)
                            OR B.Bloqueado = 1
                        )

                        AND B.NumAgencia IN (" . implode(',', array_fill(0, count($agenciasIdArray), '?')) . ", ?)
                    ",
                    array_merge($agenciasIdArray, [$coordinacionVariable])
                );
            } else {
                // Si no hay agencias en el array, solo usamos la variable de coordinación
                $solicitudes = DB::select(
                    "SELECT DISTINCT
                        A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos,
                        B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte, B.Fecha, B.CodigoAutorizacion,
                        B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona,
                        B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion,
                        B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor,
                        B.FechaAprobacion, B.ObservacionesGer, B.Bloqueado,
                        C.Letra, C.No, C.Concepto, C.Areas,
                        D.FechaInsercion
                    FROM persona A
                    JOIN autorizaciones B ON B.ID_Persona = A.ID
                    JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
                    JOIN documentosintesis D ON A.ID = D.ID_Persona
                    WHERE ((B.Estado = 2 OR B.Estado = 6) AND B.NumAgencia = ? AND A.ID > 6000)",
                    [$coordinacionVariable]
                );
            }
        }else if(session('rol') == 'Jefatura'){
            $agenciaU = session('agenciau');
            $solicitudes = DB::select("SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte,B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, B.Bloqueado, B.ID_Concepto, C.Letra, C.No, C.Concepto, C.Areas, D.FechaInsercion
            FROM persona A
            JOIN autorizaciones B ON B.ID_Persona = A.ID
            JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
            JOIN documentosintesis D ON A.ID = D.ID_Persona
            WHERE
            B.ID > 6000
            AND (B.Estado = 2 OR B.Estado = 0 OR B.Estado = 5)
            AND B.NomAgencia = '$agenciaU'");
        }else{
            $agenciaU = session('agenciau');
            $solicitudes = DB::select("SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte,B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, B.Bloqueado, B.ID_Concepto, C.Letra, C.No, C.Concepto, C.Areas, D.FechaInsercion
            FROM persona A
            JOIN autorizaciones B ON B.ID_Persona = A.ID
            JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
            JOIN documentosintesis D ON A.ID = D.ID_Persona
            WHERE
                B.ID > 6000
                AND (
                    B.Estado IN (0, 1, 2, 5)
                    OR B.Bloqueado = 1
                )
                AND B.NomAgencia = '$agenciaU'
            ");

        }

        return datatables()->of($solicitudes)->toJson();
    }

    public function aprobados(Request $request)
    {
        
        if(session('rol') == 'Coordinacion'){
            $agenciaU = session('agenciau');
            $id = session('id');
            $agencias = DB::select("SELECT NumAgencia FROM autorizaciones");


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
                $solicitudes = DB::select(
                    "SELECT DISTINCT
                        A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos,
                        B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte, B.Fecha, B.CodigoAutorizacion,
                        B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona,
                        B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion,
                        B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor,
                        B.FechaAprobacion, B.ObservacionesGer, B.Bloqueado, B.ID_Concepto,
                        C.Letra, C.No, C.Concepto, C.Areas,
                        D.FechaInsercion
                    FROM persona A
                    JOIN autorizaciones B ON B.ID_Persona = A.ID
                    JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
                    JOIN documentosintesis D ON A.ID = D.ID_Persona
                    WHERE (B.Estado = 4 AND B.Aprobacion = 1 AND B.NumAgencia IN (" . implode(',', array_fill(0, count($agenciasIdArray), '?')) . ", ?))",
                    array_merge($agenciasIdArray, [$coordinacionVariable])
                );
            } else {
                // Si no hay agencias en el array, solo usamos la variable de coordinación
                $solicitudes = DB::select(
                    "SELECT DISTINCT
                        A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos,
                        B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte, B.Fecha, B.CodigoAutorizacion,
                        B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona,
                        B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion,
                        B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor,
                        B.FechaAprobacion, B.ObservacionesGer, B.Bloqueado, B.ID_Concepto,
                        C.Letra, C.No, C.Concepto, C.Areas,
                        D.FechaInsercion
                    FROM persona A
                    JOIN autorizaciones B ON B.ID_Persona = A.ID
                    JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
                    JOIN documentosintesis D ON A.ID = D.ID_Persona
                    WHERE (B.Estado = 4 AND B.Aprobacion = 1 AND B.NumAgencia = ?)",
                    [$coordinacionVariable]
                );
            }
        }else if(session('rol') == 'Jefatura'){
            $agenciaU = session('agenciau');
            $solicitudes = DB::select("SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte,B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, B.Bloqueado, B.ID_Concepto, C.Letra, C.No, C.Concepto, C.Areas, D.FechaInsercion
            FROM persona A
            JOIN autorizaciones B ON B.ID_Persona = A.ID
            JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
            JOIN documentosintesis D ON A.ID = D.ID_Persona
            WHERE B.Estado = 4 AND B.NomAgencia = '$agenciaU'");
        }else{
            $agenciaU = session('agenciau');
            $solicitudes = DB::select("SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte,B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, B.Bloqueado, B.ID_Concepto, C.Letra, C.No, C.Concepto, C.Areas, D.FechaInsercion
            FROM persona A
            JOIN autorizaciones B ON B.ID_Persona = A.ID
            JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
            JOIN documentosintesis D ON A.ID = D.ID_Persona
            WHERE B.Estado = 4 AND B.Aprobacion = 1 AND B.NomAgencia = '$agenciaU'");
        }
        
        if (session('email') == null) {
            return redirect()->route('login');
        }

        return datatables()->of($solicitudes)->toJson();
    }

    public function anulados(Request $request)
    {

        if(session('rol') == 'Coordinacion'){
            $agenciaU = session('agenciau');
            $id = session('id');
            $agencias = DB::select("SELECT NumAgencia FROM autorizaciones");


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
                $solicitudes = DB::select(
                    "SELECT DISTINCT
                        A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos,
                        B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte, B.Fecha, B.CodigoAutorizacion,
                        B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona,
                        B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion,
                        B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor,
                        B.FechaAprobacion, B.ObservacionesGer, B.Bloqueado, B.ID_Concepto,
                        C.Letra, C.No, C.Concepto, C.Areas,
                        D.FechaInsercion
                    FROM persona A
                    JOIN autorizaciones B ON B.ID_Persona = A.ID
                    JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
                    JOIN documentosintesis D ON A.ID = D.ID_Persona
                    WHERE (B.Estado = 7 AND B.NumAgencia IN (" . implode(',', array_fill(0, count($agenciasIdArray), '?')) . ", ?))",
                    array_merge($agenciasIdArray, [$coordinacionVariable])
                );
            } else {
                // Si no hay agencias en el array, solo usamos la variable de coordinación
                $solicitudes = DB::select(
                    "SELECT DISTINCT
                        A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos,
                        B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte, B.Fecha, B.CodigoAutorizacion,
                        B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona,
                        B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion,
                        B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor,
                        B.FechaAprobacion, B.ObservacionesGer, B.Bloqueado, B.ID_Concepto,
                        C.Letra, C.No, C.Concepto, C.Areas,
                        D.FechaInsercion
                    FROM persona A
                    JOIN autorizaciones B ON B.ID_Persona = A.ID
                    JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
                    JOIN documentosintesis D ON A.ID = D.ID_Persona
                    WHERE (B.Estado = 7 AND B.NumAgencia = ?)",
                    [$coordinacionVariable]
                );
            }
        }else if(session('rol') == 'Jefatura'){
            $agenciaU = session('agenciau');
            $solicitudes = DB::select("SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte,B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, B.Bloqueado, B.ID_Concepto, C.Letra, C.No, C.Concepto, C.Areas, D.FechaInsercion
            FROM persona A
            JOIN autorizaciones B ON B.ID_Persona = A.ID
            JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
            JOIN documentosintesis D ON A.ID = D.ID_Persona
            WHERE B.Estado = 7 AND B.NomAgencia = '$agenciaU'");
        }else{
            $agenciaU = session('agenciau');
            $solicitudes = DB::select("SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte,B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, B.Bloqueado, B.ID_Concepto, C.Letra, C.No, C.Concepto, C.Areas, D.FechaInsercion
            FROM persona A
            JOIN autorizaciones B ON B.ID_Persona = A.ID
            JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
            JOIN documentosintesis D ON A.ID = D.ID_Persona
            WHERE B.Estado = 7 AND B.NomAgencia = '$agenciaU'");

        }


        if (session('email') == null) {
            return redirect()->route('login');
        }

        return datatables()->of($solicitudes)->toJson();
    }


    public function standby(Request $request)
    {


        if(session('rol') == 'Coordinacion'){
            $agenciaU = session('agenciau');
            $id = session('id');
            $agencias = DB::select("SELECT NumAgencia FROM autorizaciones");


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
                $solicitudes = DB::select(
                    "SELECT DISTINCT
                        A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos,
                        B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte, B.Fecha, B.CodigoAutorizacion,
                        B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona,
                        B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion,
                        B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor,
                        B.FechaAprobacion, B.ObservacionesGer, B.Bloqueado, B.ID_Concepto,
                        C.Letra, C.No, C.Concepto, C.Areas,
                        D.FechaInsercion
                    FROM persona A
                    JOIN autorizaciones B ON B.ID_Persona = A.ID
                    JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
                    JOIN documentosintesis D ON A.ID = D.ID_Persona
                    WHERE (B.Estado = 8 AND B.NumAgencia IN (" . implode(',', array_fill(0, count($agenciasIdArray), '?')) . ", ?))",
                    array_merge($agenciasIdArray, [$coordinacionVariable])
                );
            } else {
                // Si no hay agencias en el array, solo usamos la variable de coordinación
                $solicitudes = DB::select(
                    "SELECT DISTINCT
                        A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos,
                        B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte, B.Fecha, B.CodigoAutorizacion,
                        B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona,
                        B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion,
                        B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor,
                        B.FechaAprobacion, B.ObservacionesGer, B.Bloqueado, B.ID_Concepto,
                        C.Letra, C.No, C.Concepto, C.Areas,
                        D.FechaInsercion
                    FROM persona A
                    JOIN autorizaciones B ON B.ID_Persona = A.ID
                    JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
                    JOIN documentosintesis D ON A.ID = D.ID_Persona
                    WHERE (B.Estado = 8 AND B.NumAgencia = ?)",
                    [$coordinacionVariable]
                );
            }
        }else{
            $agenciaU = session('agenciau');
            $solicitudes = DB::select("SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.Convencion, B.DocumentoSoporte,B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.CuentaAsociado, B.EstadoCuenta, B.NombrePersona, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.FechaValidacion, B.Coordinacion, B.Aprobacion, B.AprobadoPor, B.FechaAprobacion, B.ObservacionesGer, B.Bloqueado, B.ID_Concepto, C.Letra, C.No, C.Concepto, C.Areas, D.FechaInsercion
            FROM persona A
            JOIN autorizaciones B ON B.ID_Persona = A.ID
            JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
            JOIN documentosintesis D ON A.ID = D.ID_Persona
            WHERE B.Estado = 8 AND B.NomAgencia = '$agenciaU'");
        }

        if (session('email') == null) {
            return redirect()->route('login');
        }

        return datatables()->of($solicitudes)->toJson();
    }

}
