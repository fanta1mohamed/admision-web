<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filial extends Model
{
    use HasFactory;

    protected $table = 'filial';

    protected $fillable = [
        'nombre',
        'codigo',
        'id_dep',
        'id_prov',
        'estado',
        'ubigeo',
        'direccion',
        'carpeta',
        'id_usuario'
    ];
}
