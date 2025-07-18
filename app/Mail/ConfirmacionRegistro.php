<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmacionRegistro extends Mailable
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
        return $this->view('emails.confirmacionInscripcion', [
                'nombre'  => $this->nombre,
                'correo'  => $this->correo,
                'dia' => $this->dia,
                'hora' => $this->hora,
                'logo' => public_path('imagenes/logotiny.png'),
            ])
            ->subject('Confirmacion de registro a la convocatoria para asignación de comedor 2025');
    }


}
