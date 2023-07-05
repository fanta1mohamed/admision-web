<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    protected $connection = 'mysql_secondary';

    protected $table = 'estudiante';

    protected $fillable = [
        'codigo',
        'dni'
    ];


}
