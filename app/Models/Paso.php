<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paso extends Model
{
    use HasFactory;

    protected $table = 'paso';

    protected $fillable = [
        'nombre',
        'nro',
        'avance',
        'proceso',
        'postulante'
    ];
}
