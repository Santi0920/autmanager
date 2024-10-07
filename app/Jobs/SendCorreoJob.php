<?php

namespace App\Jobs;

use App\Mail\CorreoInfo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendCorreoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;
    public $name;
    public $id_insertado;
    public $fecha;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $name, $id_insertado, $fecha)
    {
        $this->email = $email;
        $this->name = $name;
        $this->id_insertado = $id_insertado;
        $this->fecha = $fecha;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        Mail::to($this->email)->send(new CorreoInfo($this->name, $this->id_insertado, $this->fecha));

    }
    
}
