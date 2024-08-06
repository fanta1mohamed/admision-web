<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentosBiometrico extends Model
{
    use HasFactory;

    protected $table = 'documentos_biometrico';
    
    protected $fillable = [
        'observacion',
        'id_tipo',
        'id_usuario',
        'url',
        'estado',
    ];
}
