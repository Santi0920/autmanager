<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class DirectorController extends Controller
{

    public function data1()
    {

        $usuarioActual = Auth::user();
        $agenciaU = $usuarioActual->agenciau;
        $user = DB::select("SELECT * FROM concepto_autorizaciones");

        return view('Director/solicitudes', ['user' => $user]);
    }


    public function solicitarAutorizacion(Request $request)
    {

        $existingPerson = DB::select('SELECT * FROM autorizaciones WHERE Cedula = ?', [$request->cedula]);
        $tipoautorizacion = $request->tautorizacion;
        if ($tipoautorizacion == '11A' || $tipoautorizacion == '11D') {
            if (empty($existingPerson)) {
                return back()->with("incorrecto", "¡PERSONA NO EXISTE EN DATACRÉDITO!");
            }
        } else {



            $No = substr($tipoautorizacion, 0, 2);
            $letra = substr($tipoautorizacion, 2, 3);
            $cedula = $request->cedula;
            $detalle = $request->detalle;

            $fechadeSolicitud = Carbon::now('America/Bogota');
            $fechadeSolicitudUtc = $fechadeSolicitud->setTimezone('UTC');
            Carbon::setLocale('es');
            $fechaStringfechadeSolicitud = $fechadeSolicitud->translatedFormat('F d Y-H:i:s');


            $usuarioActual = Auth::user();
            $agenciaU = $usuarioActual->agenciau;
            $nombreU = $usuarioActual->name;
            $existingID = DB::select('SELECT ID FROM Persona WHERE Cedula = ?', [$request->cedula]);
            if ($tipoautorizacion == '11A' || $tipoautorizacion == '11D') {
                $idpersona = $existingID[0]->ID;
            } else {
                $idpersona = 7323;
            }

            $existingConcepto = DB::select('SELECT ID FROM concepto_autorizaciones WHERE No = ? AND Letra = ?', [$No, $letra]);
            $idconcepto = $existingConcepto[0]->ID;

            $existeAgencia = DB::select('SELECT * FROM agencias WHERE NameAgencia = ?', [$agenciaU]);
            $numAgencia = $existeAgencia[0]->NumAgencia;

            $id_insertado = DB::table('autorizaciones')->insertGetId([
                'Fecha' => $fechaStringfechadeSolicitud,
                'CodigoAutorizacion' => $tipoautorizacion,
                'NumAgencia' => $numAgencia,
                'NomAgencia' => $agenciaU,
                'Cedula' => $cedula,
                'Detalle' => $detalle,
                'Estado' => 2,
                'Solicitud' => 1,
                'SolicitadoPor' => $nombreU,
                'ID_Persona' => $idpersona,
                'ID_Concepto' => $idconcepto
            ]);

            // Verificar si se subió un archivo
            if (!$request->hasFile('SoporteScore')) {
                return back()->withErrors(['message' => 'No se subió ningún archivo.']);
            }

            $file = $request->file('SoporteScore');
            $filename = $file->getClientOriginalName();

            // Verificar si el archivo es PDF
            if ($file->getClientOriginalExtension() !== 'pdf') {
                return back()->withErrors(['message' => 'Solo se pueden subir archivos PDF.']);
            }

            // Contar archivos existentes y crear nuevo nombre de archivo
            $existingFilesCount = DB::table('autorizaciones')
                ->where('Cedula', $cedula)
                ->where('DocumentoSoporte', 'like', 'Soporte-' . $cedula . '%')
                ->count();
            if ($existingFilesCount == 0) {
                // Si no hay archivos existentes, guardarlo como el primero
                $newFilename = 'Soporte-' . $cedula . '.pdf';
            } else {
                // Si hay archivos existentes, generar un nombre de archivo único
                $newFilename = 'Soporte-' . $cedula . '-' . ($existingFilesCount + 1) . '.pdf';
            }

            // Subir el archivo
            $dir = 'Storage/files/soporteautorizaciones/';
            if (!$file->move($dir, $newFilename)) {
                return back()->withErrors(['message' => 'Error al subir el archivo.']);
            }

            // Actualizar base de datos
            $actualizacion = DB::table("autorizaciones")
                ->where('ID', $id_insertado)
                ->update(['DocumentoSoporte' => $newFilename]);



            return back()->with("correcto", "<span class='fs-4'>La autorización No. <span class='badge bg-primary fw-bold'>" . $id_insertado . "</span> está en trámite.</span>");




        }


    }

    public function solicitudes(Request $request)
    {
        $usuarioActual = Auth::user();
        $agenciaU = $usuarioActual->agenciau;
        $solicitudes = DB::select("SELECT DISTINCT A.ID AS IDPersona, A.Score, A.CuentaAsociada, A.Nombre, A.Apellidos, B.ID AS IDAutorizacion, B.DocumentoSoporte,B.Fecha, B.CodigoAutorizacion, B.NomAgencia, B.NumAgencia, B.Cedula, B.Detalle, B.Observaciones, B.Estado, B.Solicitud, B.SolicitadoPor, B.Validacion, B.ValidadoPor, B.Aprobacion, B.AprobadoPor, C.Letra, C.No, C.Concepto, C.Areas
        FROM persona A
        JOIN autorizaciones B ON B.ID_Persona = A.ID
        JOIN concepto_autorizaciones C ON B.ID_Concepto = C.ID
        WHERE B.NomAgencia = '$agenciaU'");
        return datatables()->of($solicitudes)->toJson();
    }

    public function actualizardetalle(Request $request, $id)
    {


        $file = $request->file('Soporte');


        $update = DB::table('autorizaciones')
            ->where('ID', $id)
            ->update([
                'Detalle' => $request->input('Detalle'),
                'Estado' => 2,
                'DocumentoSoporte' => 'putica'
            ]);

        return response()->json(['success' => 'Usuario actualizado correctamente']);
    }


}
