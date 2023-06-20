<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proceso;
use App\Models\TipoProceso;
use App\Models\Preinscripcion;
use App\Models\Postulante;
use App\Models\Paso;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PostulanteController extends Controller
{

  //PASO 1 - PRE INSCRIPCIÓN

  public function getPostulanteXDni(Request $request)
  {
    $res = Postulante::select(
      'postulante.id','postulante.primer_apellido', 'postulante.segundo_apellido', 'postulante.nombres',
      'postulante.email AS correo', 'postulante.celular', 'postulante.fec_nacimiento',
      'postulante.ubigeo_nacimiento as ubigeo', 'postulante.ubigeo_residencia', 'postulante.direccion',
      'departamento.nombre as departamento', 'departamento.codigo as dep', 
      'provincia.nombre as provincia','provincia.codigo as prov', 
      'distritos.nombre as distrito', 'distritos.codigo as dist' 
    )
    ->leftjoin('ubigeo','postulante.ubigeo_residencia','ubigeo.ubigeo')
    ->leftjoin('departamento','ubigeo.id_departamento','departamento.id') 
    ->leftjoin('provincia','ubigeo.id_provincia','provincia.id') 
    ->leftjoin('distritos','distritos.id','ubigeo.id_distrito') 
    ->where('postulante.nro_doc','=',$request->nro_doc)->get(); 

    $this->response['estado'] = true;
    $this->response['datos'] = $res;
    return response()->json($this->response, 200);
  
  }

  public function saveDniPostulante(Request $request) {
    $postulante = Postulante::create([
        'tipo_doc' => $request->tipo_doc,
        'nro_doc' => $request->nro_doc,
        'ubigeo_nacimiento' => $request->ubigeo_nacimiento,
    ]);
    $this->response['tipo'] = 'success';
    $this->response['titulo'] = 'REGISTRO NUEVO';
    $this->response['estado'] = true;
    $this->response['datos'] = $postulante;
    return response()->json($this->response, 200);
  }

  public function savePostulante(Request $request) {
    $solo_unapellido = 1;
    if($request->segundo_apellido === null){
      $solo_unapellido = 0;
    }

    $postulante = null;
    if (!$request->id) {
        $postulante = Postulante::create([
            'tipo_doc' => $request->tipo_doc,
            'nro_doc' => $request->nro_doc,
            'ubigeo_nacimiento' => $request->ubigeo_nacimiento,
            'sexo' => $request->sexo,
            'estado_civil' => $request->estado_civil, 
            'primer_apellido' => $request->primer_apellido, 
            'segundo_apellido' => $request->segundo_apellido,
            'solo_un_apellido' => $solo_unapellido,
            'nombres' => $request->nombres,
            'email' => $request->correo,
            'celular' => $request->celular,
            'fec_nacimiento' => $request->fec_nacimiento,
        ]);
        $this->response['tipo'] = 'success';
        $this->response['titulo'] = 'REGISTRO NUEVO';
        $this->response['mensaje'] = 'Proceso '.$postulante->nombre.' creado con exito';
        $this->response['estado'] = true;
        $this->response['datos'] = $postulante;
    } else {
        $temp = Postulante::find($request->id);
        $postulante = Postulante::find($request->id);
        $postulante->tipo_doc = $request->tipo_doc;
        $postulante->nro_doc = $request->nro_doc;
        $postulante->ubigeo_nacimiento = $request->ubigeo_nacimiento;
        $postulante->sexo = $request->sexo;
        $postulante->estado_civil = $request->estado_civil; 
        $postulante->primer_apellido = $request->primer_apellido; 
        $postulante->segundo_apellido = $request->segundo_apellido;
        $postulante->solo_un_apellido = $solo_unapellido;
        $postulante->nombres = $request->nombres;
        $postulante->email = $request->correo;
        $postulante->celular = $request->celular;
        $postulante->fec_nacimiento = $request->fec_nacimiento;
        $postulante->id_usuario = auth()->id();
        $postulante->save();

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


    private function savePasos($nom, $num, $avan, $pos, $pro) {

      $pasos = null;
      $pasos = Paso::create([
          'nombre' => $nom,
          'nro' => $num,
          'avance' => $avan, 
          'postulante' => $pos,
          'proceso' => $pro
      ]);
      $this->response['tipo'] = 'success';
      $this->response['titulo'] = 'PASO REGISTRADO';
      $this->response['mensaje'] = 'Proceso '.$pasos->nombre.' creado con exito';
      $this->response['estado'] = true;
      $this->response['datos'] = $pasos;
      
      return response()->json($this->response, 200);
    }
  
  //END PASO 1 PRE INSCRIPCION 


}
