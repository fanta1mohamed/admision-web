<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apoderado extends Model
{
    use HasFactory;

    protected $table = 'apoderado';

    protected $fillable = [
        'tipo_doc',
        'nro_documento',
        'paterno',
        'materno',
        'nombres',
        'tipo_apoderado',
        'observacion',
        'id_postulante',
        'id_usuario'
    ];
}
