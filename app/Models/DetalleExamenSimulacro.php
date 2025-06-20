<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleExamenSimulacro extends Model
{
    use HasFactory;

    protected $table = 'detalle_examen_simulacro';

    protected $fillable = [
        'aula',
        'id_examen_simulacro',
        'dni_postulante',
        'id_participante_simulacro',
        'n_pregunta',
        'respuesta',
        'nota',
        'id_usuario'
    ];
}
