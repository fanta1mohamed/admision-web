<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;

class ResCepreController extends Controller
{
    public function obtenerInformacionEstudiante($dni)
        {
            $estudiante = DB::table('resultados_sim')
                ->select('id', 'dni', 'nombres', 'puntaje', 'programa', 'created_at', 'updated_at', 'fecha')
                ->where('dni', $dni)
                ->first();

            return response()->json($estudiante);
        }
}
