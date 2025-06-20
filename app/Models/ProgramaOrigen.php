<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramaOrigen extends Model
{
    use HasFactory;

    protected $table = 'programa_origen';
    protected $fillable = ['dni', 'codigo','cod_programa','programa','id_proceso'];

}
