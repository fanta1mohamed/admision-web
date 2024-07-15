<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Proceso;
use App\Models\CarrerasPrevias;
use App\Models\Ingresante;
use App\Models\Postulante;
use App\Models\Apoderado;
use App\Models\Paso;
use App\Models\Cambio;
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
      'postulante.sexo', 'postulante.estado_civil',
      'departamento.nombre as departamento', 'departamento.codigo as dep', 
      'provincia.nombre as provincia','provincia.codigo as prov', 
      'distritos.nombre as distrito', 'distritos.codigo as dist' 
    )
    ->leftjoin('ubigeo','postulante.ubigeo_residencia','ubigeo.ubigeo')
    ->leftjoin('departamento','ubigeo.id_departamento','departamento.id') 
    ->leftjoin('provincia','ubigeo.id_provincia','provincia.id') 
    ->leftjoin('distritos','distritos.id','ubigeo.id_distrito') 
    ->where('postulante.nro_doc','=',$request->nro_doc)
    //->where('postulante.ubigeo_nacimiento','=',$request->ubigeo)
    ->get(); 

    if(count($res) > 0){
      $this->response['estado'] = true;
      $this->response['datos'] = $res;
      return response()->json($this->response, 200);
    }
    else{
      $this->response['estado'] = false;
      return response()->json($this->response, 200);
    }
  
  }


  public function getPostulanteXDni2(Request $request)
  {
    $res = Postulante::select(
      'postulante.id', 'postulante.tipo_doc','postulante.primer_apellido', 'postulante.segundo_apellido', 'postulante.nombres',
      'postulante.email AS correo', 'postulante.celular', 'postulante.fec_nacimiento',
      'postulante.ubigeo_nacimiento as ubigeo', 'postulante.ubigeo_residencia', 'postulante.direccion', 
      'postulante.sexo', 'postulante.estado_civil',
      'departamento.nombre as departamento', 'departamento.codigo as dep', 
      'provincia.nombre as provincia','provincia.codigo as prov', 
      'distritos.nombre as distrito', 'distritos.codigo as dist', 'postulante.id_pais' 
    )
    ->leftjoin('ubigeo','postulante.ubigeo_residencia','ubigeo.ubigeo')
    ->leftjoin('departamento','ubigeo.id_departamento','departamento.id') 
    ->leftjoin('provincia','ubigeo.id_provincia','provincia.id') 
    ->leftjoin('distritos','distritos.id','ubigeo.id_distrito') 
    ->where('postulante.nro_doc','=',$request->nro_doc)
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
      if($request->pais){
        $postulante->id_pais = $request->pais;
        $postulante->direccion = $request->direccion;
      }else{
        $postulante->ubigeo_residencia = $request->ubigeo_residencia;
        $postulante->direccion = $request->direccion;
      }
 
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

  
  public function getPostulantesBiometrico(Request $request)
  {
    // SELECT  
    //   pos.primer_apellido, pos.segundo_apellido,
    //   pos.nombres, pro.nombre AS programa,
    //   pro.area AS areas, mo.nombre AS modalidad 
    // FROM resultados res
    // JOIN postulante pos ON pos.nro_doc = res.dni_postulante
    // JOIN inscripciones ins ON ins.id_postulante = pos.id
    // AND ins.id_proceso = res.id_proceso AND ins.estado = 0
    // JOIN modalidad mo ON ins.id_modalidad = mo.id
    // JOIN programa pro ON ins.id_programa = pro.id
    // WHERE res.id_proceso = 7
    // ORDER BY pro.area, pro.nombre

      $query_where = [];
      $res = Ingresante::select( 
        'postulante.id as id_postulante', 'postulante.nro_doc as dni',
        'postulante.primer_apellido', 'postulante.segundo_apellido',
        'postulante.nombres', 'programa.nombre as programa',
        'programa.area', 'modalidad.nombre as modalidad',
        'control_biometrico.codigo_ingreso as codigo'
      )
      ->join('postulante','postulante.nro_doc','resultados.dni_postulante')
      ->join('inscripciones','inscripciones.id_postulante','postulante.id')
      ->join('modalidad','modalidad.id','inscripciones.id_modalidad')
      ->join('programa','programa.id','inscripciones.id_programa')
      ->leftjoin('control_biometrico','control_biometrico.id_postulante','postulante.id')
      ->where('inscripciones.id_proceso','=',auth()->user()->id_proceso)
      ->where('resultados.id_proceso','=',auth()->user()->id_proceso)
      ->where('inscripciones.estado','=',0)
      ->where($query_where)
      ->where(function ($query) use ($request) {
          return $query
            ->orWhere('postulante.nro_doc', 'LIKE', '%' . $request->term . '%')
            ->orWhere('postulante.nombres', 'LIKE', '%' . $request->term . '%')
            ->orWhere('postulante.primer_apellido', 'LIKE', '%' . $request->term . '%')
            ->orWhere(DB::raw("CONCAT(postulante.nombres, ' ', postulante.primer_apellido, ' ', postulante.segundo_apellido)"), 'LIKE', '%' . $request->term . '%')
            ->orWhere(DB::raw("CONCAT(postulante.primer_apellido, ' ', postulante.segundo_apellido, ' ', postulante.nombres)"), 'LIKE', '%' . $request->term . '%');
      })->orderBy('programa.area','ASC')
      ->orderBy('programa.nombre','ASC')
      ->paginate(20);

      $this->response['estado'] = true;
      $this->response['datos'] = $res;
      return response()->json($this->response, 200);
  }


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
      'id', 'tipo_doc', 'nro_doc', 'primer_apellido', 'segundo_apellido', 'apellido_casada', 'nombres', 'sexo', 'fec_nacimiento',
      'ubigeo_nacimiento', 'ubigeo_residencia', 'celular', 'email', 'estado_civil','direccion','anio_egreso', 
      'correo_institucional', 'cod_orcid', 'observaciones', 'id_colegio',
    )
    ->where($query_where)
    ->where(function ($query) use ($request) {
        return $query
            ->orWhere('nro_doc', 'LIKE', '%' . $request->term . '%')
            ->orWhere('email', 'LIKE', '%' . $request->term . '%')
            ->orWhere('celular', 'LIKE', '%' . $request->term . '%')
            ->orWhere(DB::raw("CONCAT(nombres, ' ', primer_apellido, ' ', segundo_apellido)"), 'LIKE', '%' . $request->term . '%')
            ->orWhere(DB::raw("CONCAT(primer_apellido, ' ', segundo_apellido, ' ', nombres)"), 'LIKE', '%' . $request->term . '%');
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
            'tipo_doc'=> $request->tipo_doc, 
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
          $postulante->tipo_doc = $request->tipo_doc; 
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


  public function getDataPrisma($dni)
  {
      $url = "https://erpprisma.com/rucdni/l_dni.php?dni=" . $dni;
      
      $response = Http::get($url);
      $data = explode("|", $response->body());

      if(count($data) >= 5){
          $resultado = [
            'dni' => $data[1],
            'nombre' => $data[2],
            'paterno' => $data[3],
            'materno' => $data[4],
        ];
        $this->response['datos'] = $resultado;
        $this->response['estado'] = true;
        return response()->json($this->response, 200);
      }
      else{
        $this->response['estado'] = false;
        return response()->json($this->response, 200);
      }

  }

  public function registrarCarreras(Request $request){

    $postulante = Postulante::where('nro_doc', $request->dni)->first();
    if($postulante){
      $postulante->carreras_previas = count($request->carreras);
      $postulante->save();
    }

    foreach ($request->carreras as $item) {
      $carrera = CarrerasPrevias::firstOrNew([
          'dni_postulante' => $request->dni,
          'cod_car' => $item['careerId']
      ], [
          'nombre' => $item['career'],
          'fecha' => now(),
          'codigo' => $item['code'],
          'condicion' => $item['cond1tion']
      ]);
  
      if (!$carrera->exists) {
          $carrera->save();
      }
  }

    $this->response['estado'] = true;
    return response()->json($this->response, 200);

  }

  public function verificarPadres (Request $request){
      $cantidad = Apoderado::join('postulante as pos', 'apoderado.id_postulante', '=', 'pos.id')
      ->whereIn('apoderado.nro_documento', [$request->dnipadre, $request->dnimadre])
      ->where('pos.nro_doc', $request->dni)
      ->count();
     
      // if($cantidad >= 1){ 
      //   return response()->json(['estado' => true]); 
      // }else { 
      //   return response()->json(['estado' => false]); 
      // }

      return true;
  
  }


  public function getCarrerasPrevias(Request $request)
  {
      $participante = $request->input('participante', null);
      $formState = $request->formState;
      $dni = $participante ? $participante['dni'] : ($formState ? $formState : null);
  
      if (!$dni) {
          return response()->json([
              'anteriores' => [],
              'loading' => false,
              'modalSancionado' => false,
              'confirmacion' => false,
              'message' => 'No se proporcionaron datos válidos'
          ], 400);
      }
  

      $existingRecords = DB::table('carreras_previas')->where('dni_postulante', $dni)->exists();
  

      if ($existingRecords) {
          return response()->json([
              'anteriores' => [],
              'loading' => false,
              'modalSancionado' => false,
              'confirmacion' => false,
              'message' => 'No tiene carreras previas'
          ]);
      }
  
      try {
          if ($participante !== null) {
              $payload = [
                  'doc_' => $participante['dni'],
                  'nom_' => $participante['nombre'],
                  'app_' => $participante['paterno'],
                  'apm_' => $participante['materno']
              ];
          } else {
              $payload = [
                  'doc_' => $formState,
                  'nom_' => 'DIRECCIÓN',
                  'app_' => 'ADMISIÓN',
                  'apm_' => 'UNAP'
              ];
          }
  
          $response = Http::withHeaders([
              'Content-Type' => 'application/json'
          ])->post('https://service2.unap.edu.pe/TieneCarrerasPrevias/', $payload);
  
          $data = $response->json();
  
          $isCountable = is_array($data) || $data instanceof Countable;
  

          $responseArray = [
              'anteriores' => $data,
              'loading' => false,
              'modalSancionado' => false,
              'confirmacion' => false,
              'message' => $isCountable && count($data) > 0 ? 'Tiene carreras previas' : 'No tiene carreras previas'
          ];
  
          return response()->json($responseArray);
      } catch (\Exception $e) {
          return response()->json([
              'anteriores' => [],
              'loading' => false,
              'modalSancionado' => false,
              'confirmacion' => false,
              'message' => 'Error en la solicitud: ' . $e->getMessage()
          ], 500);
      }

  }
  


}
