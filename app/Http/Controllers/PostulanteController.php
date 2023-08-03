<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proceso;
use App\Models\TipoProceso;
use App\Models\Preinscripcion;
use App\Models\Postulante;
use App\Models\Paso;
use App\Models\Cambio;
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
    ->where('postulante.nro_doc','=',$request->nro_doc)
    ->where('postulante.ubigeo_nacimiento','=',$request->ubigeo)
    ->get(); 

    $this->response['estado'] = true;
    $this->response['datos'] = $res;
    return response()->json($this->response, 200);
  
  }



  public function saveDniPostulante(Request $request) {

      $postulante = Postulante::create([
        'tipo_doc' => $request->tipo_doc,
        'nro_doc' => $request->nro_doc,
        'ubigeo_nacimiento' => $request->ubigeo_nacimiento,
        'primer_apellido' => $request->paterno, 
        'segundo_apellido' => $request->materno,
        'nombres' => $request->nombres,
        'ubigeo_residencia' => $request->ubigeo_nacimiento
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

      // $validator = $request->validate([
      //   'ubigeo_residencia' => 'required',
      //   'direccion' => 'required', 
      // ]);

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


  //REVISOR
  public function actualizarDatos(Request $request) {


      $registroActual = Postulante::find($request->id);
      $cambios = [];

      $fillable = ['primer_apellido', 'segundo_apellido', 'apellido_casada', 'nombres', 'sexo', 'fec_nacimiento'];

      foreach ($fillable as $campo) {
          if ($registroActual->$campo != $request->$campo) {
              $cambios[$campo] = [
                  'anterior' => $registroActual->$campo,
                  'nuevo' => $request->$campo,
              ];
              $registroActual->$campo = $request->$campo;
          }
      }
  
      foreach ($cambios as $campo => $valores) {
        if($valores['nuevo'] != null){
          $this->saveCambios('usuarios',$campo, $valores['anterior'], $valores['nuevo'], $request->id);          
        }
      }

      $registroActual->save();

      $this->response['tipo'] = 'success';
      $this->response['titulo'] = 'REGISTRO ACTUALIZADO';
      $this->response['estado'] = true;
      return response()->json($this->response, 200);
  }


  public function actualizarDatosIngresante(Request $request) {

    $registroActual = Postulante::find($request->id);
    $registroActual->nombres = $request->nombres;
    $registroActual->primer_apellido = $request->paterno; 
    $registroActual->segundo_apellido = $request->materno; 
    $registroActual->sexo = $request->sexo;
    $registroActual->tipo_doc = $request->tipo_doc; 
    $registroActual->fec_nacimiento = $request->fec_nacimiento;
    $registroActual->save();

    $this->response['tipo'] = 'success';
    $this->response['titulo'] = 'REGISTRO ACTUALIZADO';
    $this->response['estado'] = true;
    return response()->json($this->response, 200);
}

  private function saveCambios($tabla, $campo, $anterior, $nuevo, $id ){
    $cambio = Cambio::create([
      'tabla' => $tabla,
      'campo' => $campo,
      'valor_anterior' => $anterior,
      'valor_nuevo' => $nuevo,
      'id_usuario' => auth()->id(), 
      'id_registro' => $id
    ]);
  } 


  //CRUD


  public function getPostulantesAdmin(Request $request)
  {
    $query_where = [];

    $res = Postulante::select(
      'id', 'nro_doc', 'primer_apellido', 'segundo_apellido', 'apellido_casada', 'nombres', 'sexo', 'fec_nacimiento',
      'ubigeo_nacimiento', 'ubigeo_residencia', 'celular', 'email', 'estado_civil','direccion','anio_egreso', 
      'correo_institucional', 'cod_orcid', 'observaciones', 'id_colegio',
    )
    ->where($query_where)
    ->where(function ($query) use ($request) {
        return $query
            ->orWhere('nro_doc', 'LIKE', '%' . $request->term . '%')
            ->orWhere('primer_apellido', 'LIKE', '%' . $request->term . '%')
            ->orWhere('segundo_apellido', 'LIKE', '%' . $request->term . '%')
            ->orWhere('nombres', 'LIKE', '%' . $request->term . '%');
    })
    ->paginate(20);

    $this->response['estado'] = true;
    $this->response['datos'] = $res;
    return response()->json($this->response, 200);
  }

  

public function savePostulanteAdmin(Request $request ) {
  
      $modalidad = null;
      if (!$request->id) {
        $postulante = Postulante::create([
          'primer_apellido' => $request->primer_apellido, 
          'segundo_apellido' => $request->segundo_apellido,
          'apellido_casada' => $request->apellido_casada,
          'nombres' => $request->nombres,
          'sexo' => $request->sexo,
          'fec_nacimiento' => $request->fec_nacimiento,
          'ubigeo_nacimiento' => $request->ubigeo_nacimiento,
          'ubigeo_residencia' => $request->ubigeo_residencia,
          'celular' => $request->celular,
          'email' => $request->correo,
          'estado_civil' => $request->estado_civil, 
          'direccion' => $request->direccion,
          'anio_egreso' => $request->egreso,
          'nro_doc' => $request->nro_doc,
          'observaciones' => $request->observaciones,
          'id_colegio' => $request->colegio,
        ]);
        $this->response['tipo'] = 'success';
        $this->response['titulo'] = 'REGISTRO NUEVO';
        $this->response['mensaje'] = 'Proceso '.$postulante->nombre.' creado con exito';
        $this->response['estado'] = true;
        $this->response['datos'] = $postulante;
    } else {
        $temp = Postulante::find($request->id);
        $postulante = Postulante::find($request->id);

        $postulante->primer_apellido = $request->primer_apellido; 
        $postulante->segundo_apellido = $request->segundo_apellido;
        $postulante->apellido_casada = $request->apellido_casada;
        $postulante->nombres = $request->nombres;
        $postulante->sexo = $request->sexo;
        $postulante->fec_nacimiento = $request->fec_nacimiento;
        $postulante->ubigeo_nacimiento = $request->ubigeo_nacimiento;
        $postulante->ubigeo_residencia = $request->ubigeo_residencia;
        $postulante->celular = $request->celular;
        $postulante->email = $request->correo;
        $postulante->estado_civil = $request->estado_civil; 
        $postulante->direccion = $request->direccion;
        $postulante->anio_egreso = $request->egreso;
        $postulante->nro_doc = $request->nro_doc;
        $postulante->observaciones = $request->observaciones;
        $postulante->id_colegio = $request->colegio;
        $postulante->id_usuario = auth()->id();
        $postulante->save();

        if( $temp == $postulante ) {
          $this->response['estado'] = false;
        }else 
        {
          $this->response['tipo'] = 'info';
          $this->response['titulo'] = '!REGISTRO ACTUALIZADO!';
          $this->response['mensaje'] = 'Datos del '.$postulante->nombres.' actualizados';
          $this->response['estado'] = true;
          $this->response['datos'] = $postulante;
        }

      }

  return response()->json($this->response, 200);
}

  public function deleteModalidad($id){
    $modalidad = Modalidad::find($id);
    $p = $modalidad;
    $modalidad->delete();

    $this->response['titulo'] = '!REGISTRO ELIMINADO!';
    $this->response['mensaje'] = 'MODALIDAD '.$p->nombre.' ELIMINADA CON EXITO';
    $this->response['estado'] = true;
    $this->response['datos'] = $p;
    return response()->json($this->response, 200);
  }

  public function participa($dni){
    $existeRegistro = DB::table('puntajes')
      ->where('dni', $dni)
      ->where('apto', 'SI')
      ->exists();    
    $sancionados = DB::table('sancionados')
      ->where('dni', $dni)
      ->exists();
    if($existeRegistro || $sancionados ){
      $this->response['estado'] = 0;
      return response()->json($this->response, 200);
    }else {
      $this->response['estado'] = 1;
      return response()->json($this->response, 200);
    }
  }

}
