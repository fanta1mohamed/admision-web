<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Preinscripcion extends Model
{
    use HasFactory;

    protected $table = 'pre_inscripcion';

    protected $fillable = [
        'id_postulante',
        'id_programa',
        'id_proceso',
        'id_modalidad',
        'estado',
        'codigo_seguridad'
    ];
}
