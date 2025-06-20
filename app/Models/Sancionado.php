<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sancionado extends Model
{
    use HasFactory;

    protected $table = 'sancionados';

    protected $fillable = ['dni','paterno','materno','nombres','motivo','observacion','id_proceso'];

}
