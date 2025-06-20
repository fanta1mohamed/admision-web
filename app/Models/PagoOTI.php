<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoOTI extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $connection = 'mysql_secondary';

    protected $table = 'banco_pagos_admision';





}
