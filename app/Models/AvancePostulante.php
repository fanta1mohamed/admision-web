<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvancePostulante extends Model
{
    use HasFactory;

    protected $table = 'avance_postulante';

    protected $fillable = [

        'dni_postulante',
        'id_proceso',
        'id_usuario',
        'avance',
    ];

    protected $casts = [
        'avance' => 'json',
    ];
}
