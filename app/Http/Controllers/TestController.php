<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AvancePostulante;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;


class TestController extends Controller
{
    public function saveAvance(){
        $usuario = AvancePostulante::create([
            'dni_postulante'=> '70757838',
            'id_proceso' => 4,
            'id_usuario' => 1,
            'avance' => 1,
        ]);
    }

    public function getAvancePostulante(Request $request)
    {
        $res = DB::select(
            'SELECT dni_postulante, postulante.nombres, id_proceso, avance, avance_postulante.id_usuario, observacion 
            FROM avance_postulante 
            join postulante on postulante.nro_doc = avance_postulante.dni_postulante
            WHERE dni_postulante = '.$request->dni .' AND postulante.ubigeo_nacimiento = ' . $request->ubigeo
        );

        $this->response['estado'] = true;
        $this->response['datos'] = $res[0];
        return response()->json($this->response, 200);
    }

    public function getAvancePostulante2(Request $request)
    {
        $res = DB::select(
            'SELECT dni_postulante, id_proceso, avance, avance_postulante.id_usuario, observacion 
            FROM avance_postulante 
            WHERE dni_postulante = '.$request->dni
        );

        $this->response['estado'] = true;
        $this->response['datos'] = $res[0];
        return response()->json($this->response, 200);
    }


  
}