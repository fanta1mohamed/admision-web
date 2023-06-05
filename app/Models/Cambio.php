<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cambio extends Model
{
    use HasFactory;

    protected $table = 'cambios';

    protected $fillable = [
        'tabla',
        'campo',
        'valor_anterior',
        'valor_nuevo',
        'id_usuario',
        'id_registro'
    ];
}
