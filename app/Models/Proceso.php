<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Proceso extends Model
{
    use HasFactory;

    protected $table = 'procesos';

    protected $fillable = [
        'nombre',
        'id_sede_filial',
        'id_tipo_proceso',
        'id_modalidad_proceso',
        'anio',
        'estado',
        'nro_convocatoria',
        'id_usuario',
        'observaciones'
    ];
}
