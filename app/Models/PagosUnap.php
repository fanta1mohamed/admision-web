<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagosUnap extends Model
{
    use HasFactory;

    protected $connection = 'mysql_secondary';
    protected $table = 'banco_pagos_admision';
    public $timestamps = false;

    protected $fillable = [
        'cod_uni','num_mat','secuencia',
        'tip_doc','situacion','concepto',
        'tip_per','sede','num_doc',
        'imp_pag','tip_pag','for_pag',
        'fch_pag','hra_pag','cod_caj',
        'cod_age','num_che','cod_ban',
        'con_pag','fch_env','nom_cli',
        'cuenta','ano_aca','per_aca',
        'estado','fecha_usado'
    ];
    
}
