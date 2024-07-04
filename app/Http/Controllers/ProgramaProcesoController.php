<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProgramaProceso;

class ProgramaProcesoController extends Controller
{
    public function saveProceso(Request $request ) {
    
        $proceso = null;
        if (!$request->id) {
            $proceso = ProgramaProceso::create([
                'id_modalidad' => $request->id_modalidad,
                'id_programa' => $request->id_programa,
                'estado' => $request->estado,
                'id_proceso' => auth()->user()->id_proceso,
                'id_usuario' => auth()->id()
            ]);
            $this->response['titulo'] = 'REGISTRO NUEVO';
            $this->response['mensaje'] = 'Proceso '.$proceso->nombre.' creado con exito';
            $this->response['estado'] = true;
            $this->response['datos'] = $proceso;
        
        }
        // } else {
    
        //     $proceso = Proceso::find($request->id);
        //     $proceso->nombre = $request->nombre;
        //     $proceso->slug = $request->slug;
        //     $proceso->id_tipo_proceso = $request->tipo;
        //     $proceso->ciclo = $request->ciclo;
        //     $proceso->id_modalidad_proceso = $request->modalidad;
        //     $proceso->anio = $request->anio;
        //     $proceso->estado = $request->estado;
        //     $proceso->fecha_examen = $request->fec_examen;
        //     $proceso->fec_inicio = $fec_inicio;
        //     $proceso->fec_fin = $fec_fin;
        //     $proceso->id_sede_filial = $request->sede;
        //     $proceso->nro_convocatoria = $request->convocatoria;
        //     $proceso->observaciones = $request->observacion;
        //     $proceso->id_usuario = auth()->id();
        //     $proceso->save();
    
        //     $this->response['titulo'] = '!REGISTRO MODIFICADO!';
        //     $this->response['mensaje'] = 'Proceso '.$proceso->nombre.' modificado con exito';
        //     $this->response['estado'] = true;
        //     $this->response['datos'] = $proceso;
        // }
    
        return response()->json($this->response, 200);
      }   
}
