<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class Todos extends Controller
{
    public function otrabajodatatable(Request $request)
    {
        $usuarioActual = Auth::user();
        $rol = $usuarioActual->rol;
        $name = $usuarioActual->name;
        $id = $usuarioActual->id;

        //ESTABLECER NOTIFICACIONES EN 0 PORQUE YA REVISO
        DB::table('users')->where('name', $name)->update(['notificaciones' => 0]);


        $grupos = DB::table('grupos_otrabajo')
            ->where(function($query) use ($id) {
                $query->whereJsonContains('integrantes', (string)$id)
                      ->orWhereJsonContains('integrantes', $id);
            })
        ->get();

        $nombreGrupos = $grupos->pluck('nombregrupo');

        $solicitudes = DB::table('ordentrabajo')
        ->whereIn('asignado', $nombreGrupos)
        ->orWhere('asignado', $name)
        ->get();

        return datatables()->of($solicitudes)->toJson();

    }

    public function celularpendiente(Request $request){
        $usuarioActual = Auth::user();
        $rol = $usuarioActual->rol;
        $name = $usuarioActual->name;
        $id = $usuarioActual->id;

        DB::table('users')->where('id', $id)
        ->update([
            'celular' => $request->numeroCelular
        ]);

        return back()->with("correcto", "<span class='fs-4'>No Celular (".$request->numeroCelular.") registrado correctamente!</span>");
    }
}
