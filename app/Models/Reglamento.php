<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reglamento extends Model
{
  protected $table = 'reglamento';

  protected $fillable = [
      'nombre',
      'version',
      'url',
      'estado',
      'inicio_vigencia',
      'fin_vigencia',
      'id_usuario'
  ];

}
