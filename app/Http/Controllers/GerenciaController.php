<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class GerenciaController extends Controller
{
    public function solicitudes(Request $request)
    {
        $usuarioActual = Auth::user();
        $agenciaU = $usuarioActual->agenciau;

        $agencias = DB::select("SELECT NumAgencia FROM autorizaciones");

        $solicitudes = DB::select("SELECT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos,
        B.ID AS IDAutorizacion, B.Observaciones, B.DocumentoSoporte, B.Fecha, B.CodigoAutorizacion, B.NumAgencia,
        B.NomAgencia, B.Cedula, B.Detalle, B.Estado, B.Solicitud, B.SolicitadoPor,
        B.Validacion, B.ValidadoPor, B.Aprobacion, B.AprobadoPor, C.Letra, C.No,
        C.Concepto, C.Areas
        FROM persona A
        JOIN autorizaciones B ON B.ID_Persona = A.ID
        JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
        WHERE B.Estado = 1 OR B.Estado = 4 OR B.Estado = 5 AND B.Validacion = 1");

        return datatables()->of($solicitudes)->toJson();
    }

    public function validarAutorizacion(Request $request, $id)
    {
        $usuarioActual = Auth::user();
        $nombre = $usuarioActual->name;
        $rol = $usuarioActual->agenciau;
        $estadoautorizacion = $request->Estado;


        if ($estadoautorizacion == '4' || $estadoautorizacion == '5') {
            $update = DB::table('autorizaciones')
                ->where('ID', $id)
                ->update([
                    'Estado' => $request->input('Estado'),
                    'AprobadoPor' => $nombre . ' ' . $rol
                ]);
        }



        return response()->json(['success' => true]);
    }


}
