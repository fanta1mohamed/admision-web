<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrativo extends Model
{
    use HasFactory;

    protected $table = 'administrativos';

    protected $fillable = [
        'tipo_doc', 'nro_doc', 'codigo', 'nombres', 'paterno', 'materno', 'sexo', 'fec_nac', 'condicion', 'dependencia', 'estado', 'id_usuario'
    ];

    public static $rules = [
        'nro_doc' => 'required|unique:administrativos',
        'codigo' => 'unique:administrativos',
    ];

    public static $customMessages = [
        'nro_doc.required' => 'El campo nro de documento es obligatorio.',
        'nro_doc.unique' => 'El nro de documento ingresado_doca existe |en nuestros registros.',
        'codigo.unique' => 'El código ingresado ya existe en nuestros registros.',
        'nombres.required' => 'El campo nombres es obligatorio.',
        'apellidos.required' => 'El campo apellidos es obligatorio.',
        'sexo.required' => 'El campo sexo es obligatorio.',
        'condicion.required' => 'El campo condición es obligatorio.',
        'fec_nac.required' => 'El campo fecha de nacimiento es obligatorio.',
        'fec_nac.date' => 'El campo fecha de nacimiento debe ser una fecha válida.',
        'dependencia.required' => 'El campo dependencia es obligatorio.',
    ];

}