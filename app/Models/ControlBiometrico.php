<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlBiometrico extends Model
{
    use HasFactory;

    protected $table = 'control_biometrico';
    protected $fillable = [
        'id_proceso',
        'id_postulante',
        'codigo_ingreso',
        'estado',
        'id_usuario',
        'correo_institucional'
    ];
}
