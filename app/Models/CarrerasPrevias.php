<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarrerasPrevias extends Model
{
    use HasFactory;

    protected $table = "carreras_previas";

    protected $fillable = ['codigo', 'cod_car','nombre', 'fecha','condicion', 'dni_postulante' ];

}   