<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carpeta extends Model
{
    use HasFactory;

    protected $table = 'carpetas';

    protected $fillable = [

        'nombre',
        'carpeta_padre_id',
        'url'
    ];

    public function carpetaPadre()
    {
        return $this->belongsTo(Carpeta::class, 'carpeta_padre_id');
    }

    public function carpetasHijas()
    {
        return $this->hasMany(Carpeta::class, 'carpeta_padre_id');
    }

    protected function obtenerRutaCarpeta($carpeta)
    {
        return $carpeta->url;
    }

}
