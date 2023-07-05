<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $connection = 'mysql_secondary';

    protected $table = 'estudiante';

    protected $fillable = [
        'num_mat',
        'cod_car',
        'paterno',
        'materno',
        'nombres',
        'tip_doc',
        'num_doc',
        'fch_nac',
        'sexo',
        'ubigeo',
        'mod_ing',
        'est_civ',
        'fch_ing',
        'direc',
        'email',
        'con_est',
        'celular',
        'cod_esp',
        'puntaje',
        'puesto_escuela',
        'puesto_general',
        'ano_ing',
        'per_ing'
    ];




}
