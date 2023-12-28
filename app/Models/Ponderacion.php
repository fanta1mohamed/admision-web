<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ponderacion extends Model
{
    use HasFactory;

    protected $table="ponderacion_simulacro";

    protected $fillable=['nombre', 'area'];
}
