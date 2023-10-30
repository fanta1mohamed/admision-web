<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colegio extends Model
{
    use HasFactory;

    protected $table = 'colegios';

    protected $fillable = [
        'cod_modular',
        'cod_local',
        'nombre',
        'nivel',
        'forma',
        'sexo',
        'id_gestion',
        'gestion',
        'cod_sector',
        'sector',
        'telefono',
        'correo',
        'direccion',
        'zona',
        'ubigeo_inei',
        'ubigeo',
        'drep',
        'ugel',
        'funciona',
        'observacion'
    ];
}




