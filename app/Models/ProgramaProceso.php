<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramaProceso extends Model
{
    use HasFactory;
    
    protected $table = 'programas_proceso';

    protected $fillable = [
        'id_modalidad',
        'id_proceso',
        'id_programa',
        'estado',
        'id_usuario'
    ];
}
