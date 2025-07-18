<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Comunicado extends Mailable
{
    use Queueable, SerializesModels;

    public $nombre;
    public $correo;


    public function __construct($nombre, $correo)
    {
        $this->nombre = $nombre;
        $this->correo = $correo;

    }

    public function build()
    {
        return $this->view('emails.comunicado', [
                'nombre'  => $this->nombre,
                'correo'  => $this->correo,
                'logo' => public_path('imagenes/logotiny.png'),
            ])
            ->subject('Comunicado - convocatoria de comedor 2025');
    }


}
