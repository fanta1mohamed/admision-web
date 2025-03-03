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
        'slug',
        'id_sede_filial',
        'id_tipo_proceso',
        'ciclo',
        'id_modalidad_proceso',
        'anio',
        'estado',
        'fecha_examen',
        'fec_inicio',
        'fec_fin',
        'nro_convocatoria',
        'id_usuario',
        'observaciones',
        'url',
        'nivel'
    ];
}
