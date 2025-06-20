<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitoDetalle extends Model
{
    use HasFactory;

    protected $table = 'requisito_detalle';

    protected $fillable = [
        'requisitos',
        'dni_postulante',
        'id_usuario',
        'id_proceso'
    ];

    protected $casts = [
        'postulantes' => 'array',
    ];
}
