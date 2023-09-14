<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class Comprobante extends Model
{
    use HasFactory;

    protected $table = 'comprobante';

    protected $fillable = [
        'nro_operacion',
        'fecha',
        'hora',
        'codigo',
        'monto',
        'estado',
        'ndoc_postulante',
        'verificado',
        'id_usuario',
        'temp_fecha'
    ];

    // public function setNroOperacionAttribute($value)
    // {
    //     $this->attributes['nro_operacion'] = Hash::make($value);
    // }

    // public function getNroOperacionAttribute($value)
    // {
    //     return $value; // No necesitas desencriptar, ya que Hash no es reversible
    // }

    // public function setNroOperacionAttribute($value)
    // {
    //     $this->attributes['nro_operacion'] = Crypt::encrypt($value);
        
    // }
    
    // public function setNdocPostulanteAttribute($value)
    // {
    //     $this->attributes['ndoc_postulante'] = Crypt::encrypt($value);
    // }
    
    // // public function setMontoAttribute($value)
    // // {
    // //     $this->attributes['monto'] = Crypt::encrypt($value);
    // // }

    
    // public function getNroOperacionAttribute($value)
    // {
    //     return Crypt::decrypt($value);
    // }

    // public function getNdocPostulanteAttribute($value)
    // {
    //     return Crypt::decrypt($value);
    // }

    // public function getMontoAttribute($value)
    // {
    //     return Crypt::decrypt($value);
    // }










}
