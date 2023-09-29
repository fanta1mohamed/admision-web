<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dataversion extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'data_version';
    protected $fillable = ['registro_id', 'tabla', 'usuario_id', 'fecha', 'datos'];

    
}
