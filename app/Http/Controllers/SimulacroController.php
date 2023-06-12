<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proceso;
use App\Models\TipoProceso;
use App\Models\ParticipanteSimulacro;
use App\Models\DetalleExamenSimulacro;
use App\Models\Simulacro;
use App\Models\Postulante;
use App\Models\Paso;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SimulacroController extends Controller
{

  public function getSimulacros(Request $request)
  {
    $query_where = [];
  
    $res = Simulacro::select(
      'simulacro.id','simulacro.nombre','simulacro.estado', 'simulacro.fecha', 'simulacro.anio','simulacro.ubigeo')
      ->where($query_where)
      ->where(function ($query) use ($request) {
          return $query
              ->orWhere('simulacro.nombre', 'LIKE', '%' . $request->term . '%');
      })->orderBy('simulacro.id', 'DESC')
      ->paginate(10);

    $this->response['estado'] = true;
    $this->response['datos'] = $res;
    return response()->json($this->response, 200);
  }

  public function getParticipantes(Request $request)
  {  
    $res = ParticipanteSimulacro::select(
      'nro_doc as dni',
      'nombres','primer_apellido',
      'segundo_apellido'
      )   
      ->where(function ($query) use ($request) {
          return $query
              ->orWhere('nro_doc', 'LIKE', '%' . $request->term . '%')
              ->orWhere('nombres', 'LIKE', '%' . $request->term . '%')
              ->orWhere('primer_apellido', 'LIKE', '%' . $request->term . '%');
      })->orderBy('id', 'ASC')
      ->paginate(10);

    $this->response['estado'] = true;
    $this->response['datos'] = $res;
    return response()->json($this->response, 200);
  }


  public function getParticipantesSimulacro(Request $request)
  {  
    $res = DetalleExamenSimulacro::select(
      'examen_simulacro.area',
      'participantes_simulacro.nro_doc as dni', 
      'participantes_simulacro.nombres', 'participantes_simulacro.primer_apellido', 
      'participantes_simulacro.segundo_apellido', 
      DB::raw('SUM(nota) AS nota'))
      ->leftjoin('participantes_simulacro','detalle_examen_simulacro.dni_postulante','participantes_simulacro.nro_doc')  
      ->leftjoin('examen_simulacro','detalle_examen_simulacro.id_examen_simulacro','examen_simulacro.id')
      ->where('detalle_examen_simulacro.id_examen_simulacro','=',$request->examen)
      ->where(function ($query) use ($request) {
          return $query
              ->orWhere('participantes_simulacro.nro_doc', 'LIKE', '%' . $request->term . '%')
              ->orWhere('participantes_simulacro.nombres', 'LIKE', '%' . $request->term . '%')
              ->orWhere('participantes_simulacro.primer_apellido', 'LIKE', '%' . $request->term . '%');
      })->orderBy('nota', 'DESC')
      ->groupBy('participantes_simulacro.id', 'participantes_simulacro.id','examen_simulacro.id')
      ->paginate(100);

    $this->response['estado'] = true;
    $this->response['datos'] = $res;
    return response()->json($this->response, 200);
  }


  public function saveSimulacro(Request $request ) {

    $simulacro = null;
    if (!$request->id) {
        $simulacro = Simulacro::create([
            'nombre' => $request->nombre,
            'estado' => $request->estado,
            'anio' => $request->anio,
            'fecha' => date('Y-m-d'),
            'ubigeo' => $request->ubigeo,
            'id_usuario' => auth()->id(),
        ]);
        $this->response['tipo'] = 'success';
        $this->response['titulo'] = 'REGISTRO NUEVO';
        $this->response['mensaje'] = 'El simulacro '.$simulacro->nombre.' fue creado con exito';
        $this->response['estado'] = true;
        $this->response['datos'] = $simulacro;
    } else {
        $temp = Simulacro::find($request->id);
        $simulacro = Simulacro::find($request->id);
        $simulacro->nombre = $request->nombre;
        $simulacro->estado = $request->estado;
        $simulacro->anio = $request->anio;
        $simulacro->ubigeo = $request->ubigeo;
        $simulacro->save();

        if( $temp == $postulante ) {
          $this->response['estado'] = false;
        }else 
        {
          $this->response['tipo'] = 'info';
          $this->response['titulo'] = '!REGISTRO MODIFICADO!';
          $this->response['mensaje'] = 'Datos del '.$postulante->nombres.' actualizados';
          $this->response['estado'] = true;
          $this->response['datos'] = $postulante;
        }

      }

      return response()->json($this->response, 200);
    }

    public function saveResidencia(Request $request ) {

      $postulante = Postulante::find($request->id);
      $temp = $postulante;
      $postulante->ubigeo_residencia = $request->ubigeo_residencia;
      $postulante->direccion = $request->direccion;
      $postulante->save();

      if( $temp == $postulante ) {
        $this->response['estado'] = false;
      } else {
        $this->response['tipo'] = 'info';
        $this->response['titulo'] = '!REGISTRO ACTUALIZADO!';
        $this->response['mensaje'] = 'La residencia del '.$postulante->nombres.' se actualizó.';
        $this->response['estado'] = true;
        $this->response['datos'] = $postulante;
      }  
      return response()->json($this->response, 200);
    }

    
    public function saveColegio(Request $request ) {

      $postulante = Postulante::find($request->id);
      $temp = $postulante;
      $postulante->anio_egreso = $request->anio_egreso;
      $postulante->id_colegio = $request->colegio;
      $postulante->save();

      if( $temp == $postulante ) {
        $this->response['estado'] = false;
      } else {
        $this->response['tipo'] = 'info';
        $this->response['titulo'] = '!REGISTRO ACTUALIZADO!';
        $this->response['mensaje'] = 'Los dato del colegio de '.$postulante->nombres.' se actualizó.';
        $this->response['estado'] = true;
        $this->response['datos'] = $postulante;
      }

      if($request->actualizar == 'si'){
        $this->savePasos("Datos colegio registrados", 3, 48, $request->id, $request->proceso);
      }
  
        return response()->json($this->response, 200);
    }

    public function saveRespuestas(Request $request ) {

      DB::beginTransaction();

      try {

          $participante = ParticipanteSimulacro::create([
            'nro_doc' => $request->dni,
            'primer_apellido' => $request->paterno,
            'segundo_apellido' => $request->materno,
            'nombres' => $request->nombres
          ]);

          $res = DB::select('SELECT id_examen, nro_pregunta, respuesta, ponderacion from respuestas_simulacro 
          WHERE id_examen = '. $request->area);


          foreach($request->respuestas as $index => $item ) {
            if($item === null){
              $this->guardarRespuesta($request->area, $request->dni, $index, "-", 2 * $res[$index]->ponderacion);
            }else {

              if(count($item) === 0){
                $this->guardarRespuesta($request->area, $request->dni, $index, "-", 2 * $res[$index]->ponderacion);
              }
              if(count($item) > 1 ){
                $str = implode(', ', $item);
                $this->guardarRespuesta($request->area, $request->dni, $index, $str, 0);
              }
              if(count($item) === 1 ){
                if( $item[0] === $res[$index]->respuesta){
                  $this->guardarRespuesta($request->area, $request->dni, $index, $item[0],10 * $res[$index]->ponderacion );
                }else{
                  $this->guardarRespuesta($request->area, $request->dni, $index, $item[0],0);
                }          
              }
            }

          }

          DB::commit();
      } catch (\Exception $e) {
        DB::rollback();
        throw $e;
      }

      return $request;
    }

    public function guardarRespuesta($examen, $participante, $pregunta, $respuesta, $nota) {
      $doc = DetalleExamenSimulacro::create([
        'id_examen_simulacro' => $examen, 
        'dni_postulante' => $participante,
        'n_pregunta' => $pregunta+1,
        'respuesta' => $respuesta,
        'nota' => $nota,
        'id_usuario' => auth()->id()
      ]);
    

    }


  //END PASO 1 PRE INSCRIPCION 


}
