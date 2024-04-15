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
    public function solicitudes(Request $request)
    {
        $usuarioActual = Auth::user();
        $agenciaU = $usuarioActual->agenciau;

        $agencias = DB::select("SELECT NumAgencia FROM autorizaciones");

        if ($agenciaU = $usuarioActual->agenciau == "Coordinacion 1") {
            $solicitudes = DB::select("SELECT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos,
            B.ID AS IDAutorizacion, B.Observaciones, B.DocumentoSoporte, B.Fecha, B.CodigoAutorizacion, B.NumAgencia,
            B.NomAgencia, B.Cedula, B.Detalle, B.Estado, B.Solicitud, B.SolicitadoPor,
            B.Validacion, B.ValidadoPor, B.Aprobacion, B.AprobadoPor, C.Letra, C.No,
            C.Concepto, C.Areas
            FROM persona A
            JOIN autorizaciones B ON B.ID_Persona = A.ID
            JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
            WHERE B.Solicitud = 1 AND B.NumAgencia IN (34, 36, 37, 38, 40, 41, 87, 93, 96)");
        } else if ($agenciaU = $usuarioActual->agenciau == "Coordinacion 2") {
            $solicitudes = DB::select("
            SELECT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos,
            B.ID AS IDAutorizacion, B.Observaciones, B.DocumentoSoporte, B.Fecha, B.CodigoAutorizacion, B.NumAgencia,
            B.NomAgencia, B.Cedula, B.Detalle, B.Estado, B.Solicitud, B.SolicitadoPor,
            B.Validacion, B.ValidadoPor, B.Aprobacion, B.AprobadoPor, C.Letra, C.No,
            C.Concepto, C.Areas
            FROM persona A
            JOIN autorizaciones B ON B.ID_Persona = A.ID
            JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
            WHERE B.Solicitud = 1 AND B.NumAgencia IN (33, 39, 46, 70, 77, 78, 80, 88, 92, 98)");
        } else if ($agenciaU = $usuarioActual->agenciau == "Coordinacion 3") {
            $solicitudes = DB::select("
            SELECT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos,
            B.ID AS IDAutorizacion, B.Observaciones, B.DocumentoSoporte, B.Fecha, B.CodigoAutorizacion, B.NumAgencia,
            B.NomAgencia, B.Cedula, B.Detalle, B.Estado, B.Solicitud, B.SolicitadoPor,
            B.Validacion, B.ValidadoPor, B.Aprobacion, B.AprobadoPor, C.Letra, C.No,
            C.Concepto, C.Areas
            FROM persona A
            JOIN autorizaciones B ON B.ID_Persona = A.ID
            JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
            WHERE B.Solicitud = 1 AND B.NumAgencia IN (32, 42, 47, 81, 82, 83, 85, 90, 94)");
        } else if ($agenciaU = $usuarioActual->agenciau == "Coordinacion 4") {
            $solicitudes = DB::select("
            SELECT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos,
            B.ID AS IDAutorizacion, B.Observaciones, B.DocumentoSoporte, B.Fecha, B.CodigoAutorizacion, B.NumAgencia,
            B.NomAgencia, B.Cedula, B.Detalle, B.Estado, B.Solicitud, B.SolicitadoPor,
            B.Validacion, B.ValidadoPor, B.Aprobacion, B.AprobadoPor, C.Letra, C.No,
            C.Concepto, C.Areas
            FROM persona A
            JOIN autorizaciones B ON B.ID_Persona = A.ID
            JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
            WHERE B.Solicitud = 1 AND B.NumAgencia IN (44, 45, 48, 49, 74, 75, 84, 89, 91, 95, 97)");
        } else if ($agenciaU = $usuarioActual->agenciau == "Coordinacion 5") {
            $solicitudes = DB::select("
            SELECT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos,
                   B.ID AS IDAutorizacion, B.Observaciones, B.DocumentoSoporte, B.Fecha, B.CodigoAutorizacion, B.NumAgencia,
                   B.NomAgencia, B.Cedula, B.Detalle, B.Estado, B.Solicitud, B.SolicitadoPor,
                   B.Validacion, B.ValidadoPor, B.Aprobacion, B.AprobadoPor, C.Letra, C.No,
                   C.Concepto, C.Areas
            FROM persona A
            JOIN autorizaciones B ON B.ID_Persona = A.ID
            JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
            WHERE B.Solicitud = 1 AND B.NumAgencia IN (13, 30, 31, 43, 68, 73, 76, 86)");
        }
        return datatables()->of($solicitudes)->toJson();
    }


    public function validarAutorizacion(Request $request, $id)
    {
        $usuarioActual = Auth::user();
        $nombre = $usuarioActual->name;
        $noCoordinacion = $usuarioActual->agenciau;
        $estadoautorizacion = $request->Estado;


        if ($estadoautorizacion == '0' || $estadoautorizacion == '2' || $estadoautorizacion == '3') {
            $update = DB::table('autorizaciones')
                ->where('ID', $id)
                ->update([
                    'Observaciones' => $request->input('Observaciones'),
                    'Estado' => $request->input('Estado'),
                    'ValidadoPor' => $nombre. ' '.$noCoordinacion
                ]);
        } else if ($estadoautorizacion == '1') {
            $update = DB::table('autorizaciones')
                ->where('ID', $id)
                ->update([
                    'Observaciones' => $request->input('Observaciones'),
                    'Estado' => $request->input('Estado'),
                    'ValidadoPor' => $nombre . ' '.$noCoordinacion,
                    'Validacion' => 1,
                ]);
        }



        return response()->json(['success' => true]);
    }
}
