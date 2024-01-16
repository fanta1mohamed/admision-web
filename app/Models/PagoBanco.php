<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoBanco extends Model
{
    use HasFactory;

    protected $table = 'banco_pagos';
    protected $fillable = [
        'cod_uni', 'num_mat', 'secuencia', 'tip_doc', 'situacion', 'concepto', 'tip_per',
        'sede', 'num_doc', 'imp_pag', 'tip_pag', 'for_pag', 'fch_pag', 'hra_pag',
        'cod_age', 'cod_caj', 'num_che', 'cod_ban', 'con_pag', 'fch_env', 'nom_cli', 
        'cuenta ', 'ano_aca', 'per_aca', 'estado', 'fecha_usado', 'id_usuario', 'id_proceso'
    ];
    
}
