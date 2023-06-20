<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueColumns implements Rule
{
    protected $table;
    protected $columns;

    public function __construct($table, ...$columns)
    {
        $this->table = $table;
        $this->columns = $columns;
    }

    public function passes($attribute, $value)
    {
        [$column1, $column2] = $value;
    
        return !!DB::table($this->table)
        ->where($column1, '=', $value[0])
        ->where($column2, '=', $value[1])
        ->exists();
    }

    public function message()
    {
        return 'Los valores de las columnas ya existen en la tabla.';
    }
}
