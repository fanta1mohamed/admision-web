<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PonderacionDetalle extends Model
{
    use HasFactory;

    protected $table="ponderacion";
    protected $fillable=['asignatura', 'numero', 'ponderacion', 'id_ponderacion_simulacro'];

}
