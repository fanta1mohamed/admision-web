<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetalleExamenVocacional;
use App\Models\Paso;
use Inertia\Inertia;

class DetalleExamenVocacionalController extends Controller
{

    public function saveVocacional(Request $request ) {

        foreach($request->respuestas as $respuesta){
            if($respuesta == null ){
                echo "salto";
            }
            else{
                $this->saveDetalleVocacional($request->id_examen, $respuesta['ideP'], $request->id_postulante, $respuesta['ide']); 
            }

        }
        $this->savePasos($request->name, $request->nro, $request->avance, $request->id_postulante, $request->proceso);

        $this->response['tipo'] = 'success';
        $this->response['titulo'] = 'PASO REGISTRADO';
        $this->response['estado'] = true;

        return response()->json($this->response, 200);

    }

    public function saveDetalleVocacional($id_examen, $id_pregunta, $id_postulante, $id_respuesta) {
    
        $detalle_vocacional = DetalleExamenVocacional::create([
            'id_examen_vocacional' => $id_examen,
            'id_pregunta' => $id_pregunta,
            'id_postulante' => $id_postulante,
            'id_respuesta' => $id_respuesta,
            'fecha' => date('Y-m-d')
        ]);    
    }

    private function savePasos($nom, $num, $avan, $pos, $pro) {

        $pasos = null;
        $pasos = Paso::create([
            'nombre' => $nom,
            'nro' => $num,
            'avance' => $avan, 
            'postulante' => $pos,
            'proceso' => $pro
        ]);
      }



  
}
