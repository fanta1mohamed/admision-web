<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    use HasFactory;

    public function getProvinciasPorDepartamento($departamento)
    {
        return $this->where('id_dep', $departamento, )->get();
    }

    protected $table = "provincia";

    protected $fillable = [
        'codigo',
        'nombre',
        'id_dep',
        'estado',
        'id_usuario'
    ];



}
