<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoResultado extends Model
{
    use HasFactory;

    protected $table = 'documentos_resultado';
    
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
