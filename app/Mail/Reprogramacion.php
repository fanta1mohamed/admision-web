<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Reprogramacion extends Mailable
{
    use Queueable, SerializesModels;

    public $nombre;
    public $correo;
    public $dia;
    public $hora;

    public function __construct($nombre, $correo, $dia, $hora)
    {
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->dia = $dia;
        $this->hora = $hora;

    }

    public function build()
    {
        return $this->view('emails.reprogramacionCita', [
                'nombre'  => $this->nombre,
                'correo'  => $this->correo,
                'dia' => $this->dia,
                'hora' => $this->hora,
                'logo' => public_path('imagenes/logotiny.png'),
            ])
            ->subject('Reprogramación de cita para entrevista - convocatoria de comedor 2025');
    }


}
