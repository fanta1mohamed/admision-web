<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoSegunda extends Model
{
    protected $table = 'documentos_segundas';
    
    protected $fillable = [
        'nombre',
        'estado',
        'id_tipo',
        'url',
        'observacion',
        'fecha',
        'id_proceso',
        'id_usuario'
    ];

}
