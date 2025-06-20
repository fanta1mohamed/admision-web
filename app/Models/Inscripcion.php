<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    use HasFactory;

    protected $table = 'inscripciones';

    protected $fillable = [
        'codigo',
        'id_postulante',
        'id_proceso',
        'id_programa',
        'id_modalidad',
        'id_usuario',
        'id_pre_inscripcion',
        'id_examen_vocacional',
        'estado',
    ];
}
