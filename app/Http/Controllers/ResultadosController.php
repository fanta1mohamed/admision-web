<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParticipanteSimulacro;
use DB;


class ResultadosController extends Controller
{
    

    public function SubirResultado(Request $request){
        $data = $request->data; // No es necesario usar all()
        DB::table('resultados_simulacro')->insert($data);
        return response()->json(['message' => 'Datos insertados con éxito']);
    }

    public function getResultados(Request $request){

        $resultado = ParticipanteSimulacro::select('participantes_simulacro.nro_doc', 'resultados_simulacro.puntaje', 'resultados_simulacro.puesto_programa', 'resultados_simulacro.fecha', 'programa.area')
        ->join('resultados_simulacro', 'participantes_simulacro.nro_doc', '=', 'resultados_simulacro.dni')
        ->join('inscripcion_simulacro', 'inscripcion_simulacro.id_estudiante', '=', 'participantes_simulacro.id')
        ->join('programa', 'inscripcion_simulacro.id_programa', '=', 'programa.id')
        ->where('participantes_simulacro.nro_doc', $request->dni)
        ->first();

        $this->response['datos'] = $resultado;
        $this->response['estado'] = true;  
        return response()->json($this->response, 200);

    }

    public function getExamenBio(){
        $archivo = public_path('/resultados/biomedicas.pdf');
        return response()->download($archivo);
    }
    public function getExamenIng(){
        $archivo = public_path('/resultados/ingenierias.pdf');
        return response()->download($archivo);
    }
    public function getExamenSoc(){
        $archivo = public_path('/resultados/sociales.pdf');
        return response()->download($archivo);
    }




}
