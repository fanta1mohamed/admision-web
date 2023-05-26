<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;
    protected $table = 'documento';
    protected $fillable = [
        'codigo',
        'nombre',
        'id_postulante',
        'id_tipo_documento',
        'estado',
        'url',
        'observacion'
    ];

}
