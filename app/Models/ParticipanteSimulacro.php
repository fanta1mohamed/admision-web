<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipanteSimulacro extends Model
{
    use HasFactory;

    protected $table = 'participantes_simulacro';

    protected $fillable = [
        'nro_doc',
        'primer_apellido',
        'segundo_apellido',
        'nombres'
    ];


}
