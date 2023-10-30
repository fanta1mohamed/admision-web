<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $table = "pagos_simulacro";

    protected $fillable = [
        'authorizationCode',
        'cardOwner',
        'dni',
        'confirmedDate',
        'description',
        'email',
        'fullname',
        'originalDate',
        'status',
        'total',
        'totalForProvider',
        'type',
        'codigo'
    ];
}
