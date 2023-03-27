<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SeederTablePermisos extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permisios = [
            'ver-rol',
            'crear-rol',
            'editar-rol',
            'borrar-rol',
            'ver-blog',
            'crear-blog',
            'editar-blog',
            'borrar-blog',
        ];
        foreach($permisios as $permiso){
            Permission::create(['name'=>$permiso]);
        }
    }
}
 