<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InscripcionSimulacro extends Model
{
    use HasFactory;

    protected $table = 'inscripcion_simulacro';

    protected $fillable = [
        'id_estudiante',
        'id_simulacro',
        'id_usuario',
        'id_programa',
        'estado',
        'fecha',
        'terminos',
        'observacion'

    ];
}
