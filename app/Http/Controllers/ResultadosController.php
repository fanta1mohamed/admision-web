<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ResultadosController extends Controller
{
    

    public function SubirResultado(Request $request){
        $data = $request->data; // No es necesario usar all()
        DB::table('resultados_simulacro')->insert($data);
        return response()->json(['message' => 'Datos insertados con éxito']);
    }



}
