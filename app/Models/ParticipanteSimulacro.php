<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipanteSimulacro extends Model
{
    use HasFactory;

    protected $table = 'participantes_simulacro';

    protected $fillable = [
        'tipo_doc',
        'nro_doc',
        'paterno',
        'materno',
        'nombres',
        'sexo',
        'fec_nacimiento',
        'celular',
        'correo',
        'pais',
        'ubi_residencia',
        'grado_instruccion',
        'id_colegio'
    ];


}
