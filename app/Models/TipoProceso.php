<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoProceso extends Model
{
    use HasFactory;

    protected $table = 'tipo_proceso';

    protected $fillable = [
        'nombre'
    ];
}
