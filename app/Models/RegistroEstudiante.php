<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroEstudiante extends Model
{
    use HasFactory;

    protected $table = 'cb_2023_1';

    protected $fillable = [
        'dni',
        'nombre',
        'codigo',
        'ciclo',
        'id_programa',
        'programa', 
        'ultimo_ciclo',
        'condicion'
    ];




}
