<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExpirarEstadosFinDeMes extends Command
{
    protected $signature = 'estados:expirar-fin-mes';
    protected $description = 'Expira autorizaciones cuyo último estado es uno de los definidos, el último día del mes a las 6 pm';

    public function handle()
    {
        // Zona horaria
        $ahora = Carbon::now('America/Bogota');

        // Lista de estados válidos
        $estadosValidos = [
            'TRÁMITE', 'REMITIDO', 'VALIDADO', 'ACLARAR',
            'ENCARGARSE', 'PROCEDER', 'SOLUCIONAR', 'QUE PASO',
            'RECIBIDO', 'STAND BY', 'DESBLOQUEADO', 'REMITIDO', 'STAND BY'
        ];

        // Consultar todas las autorizaciones a evaluar
        $autorizaciones = DB::table('historialestado')
            ->select('ID_Autorizacion')
            ->distinct()
            ->get();

        foreach ($autorizaciones as $aut) {

            // Buscar el último estado de esa autorización
            $ultimoEstado = DB::table('historialestado')
                ->where('ID_Autorizacion', $aut->ID_Autorizacion)
                ->orderBy('ID', 'DESC')
                ->first();

            // Si no existe registro → continuar
            if (!$ultimoEstado) continue;

            // Si NO está en los estados válidos, continuar
            if (!in_array($ultimoEstado->Estado, $estadosValidos)) continue;

            // 👉 Si el último estado es TRÁMITE o REMITIDO → actualizarlo a DONE
            if (in_array($ultimoEstado->Estado, ['TRÁMITE', 'REMITIDO'])) {

                DB::table('historialestado')
                    ->where('ID', $ultimoEstado->ID)
                    ->update([
                        'Estado' => 'DONE',
                    ]);

                // Cambiarlo también en memoria
                $ultimoEstado->Estado = 'DONE';
            }

            // Registrar fecha
            $fechadeSolicitud = Carbon::now('America/Bogota');
            Carbon::setLocale('es');
            $fechaString = $fechadeSolicitud->translatedFormat('F d Y - H:i:s');

            // Fecha de corte (último día del mes)
            $fechaCorte = Carbon::now('America/Bogota')->endOfMonth();
            $fechaCorteString = $fechaCorte->translatedFormat('F d Y');

            // Mensaje
            $observacion = "Su solicitud o reporte ha superado la fecha de corte establecida ($fechaCorteString) y ha sido marcada automáticamente como VENCIDA.";

            // Insertar en historialestado (nuevo registro VENCIDO)
            DB::table('historialestado')->insert([
                'NumArea' => '00',
                'NomArea' => 'COOPSERP WEB',
                'Observaciones' => $observacion,
                'Estado' => 'VENCIDO',
                'Nombre' => 'SOFTWARE AUTORIZACIONES',
                'Fecha' => $fechadeSolicitud,
                'FechaString' => $fechaString,
                'ID_User' => null,
                'ID_Autorizacion' => $aut->ID_Autorizacion
            ]);
        }
        Log::info('CRON ejecutado: estados:expirar-fin-mes - ' . now());

        $this->info("Proceso de expiración completado.");
    }
}
