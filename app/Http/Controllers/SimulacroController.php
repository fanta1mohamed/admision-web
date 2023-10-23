<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proceso;
use App\Models\TipoProceso;
use App\Models\ParticipanteSimulacro;
use App\Models\DetalleExamenSimulacro;
use App\Models\Simulacro;
use App\Models\InscripcionSimulacro;
use App\Models\Postulante;
use App\Models\Paso;
use Barryvdh\DomPDF\Facade\Pdf;
use TCPDF;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;


class SimulacroController extends Controller
{

  private $fondo;

  public function __construct()
  {
      $this->fondo = public_path('imagenes/simulacro/sim40.png');
  }

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

    public function pdfInscripcion($dni) {

      // $datos = DB::select("SELECT 
      // postulante.nro_doc AS dni, 
      // inscripciones.codigo as codigo,
      // postulante.nombres AS nombre, 
      // postulante.primer_apellido AS paterno,
      // postulante.segundo_apellido AS materno,
      // programa.nombre AS programa,
      // inscripciones.id_programa as id_programa,
      // modalidad.nombre AS modalidad,
      // procesos.nombre AS proceso,
      // inscripciones.created_at as fecha,
      // users.name, users.paterno as upaterno
      // FROM inscripciones
      // JOIN postulante ON inscripciones.id_postulante = postulante.id
      // JOIN programa ON inscripciones.id_programa = programa.id
      // JOIN modalidad ON inscripciones.id_modalidad = modalidad.id 
      // JOIN procesos ON inscripciones.id_proceso = procesos.id
      // JOIN users on inscripciones.id_usuario = users.id
      // WHERE postulante.nro_doc = $dni AND inscripciones.estado = 0");

      // $data = $datos[0];
      
      $pdf = Pdf::loadView('simulacro.inscripcion', ['fondo' => $this->fondo]);
      
      $pdf->setPaper('A4', 'portrait');
      $output = $pdf->output();
      
      return $pdf->stream();
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


    //SIMULACRO 2023
    public function saveParticipante(Request $request) {
      if ($request->fec_nac) {
          $fecha = substr($request->fec_nac, 0, 10);
      }
  
      try {
          DB::beginTransaction();
  
          $participante = ParticipanteSimulacro::create([
              'tipo_doc' => $request->tipo_doc,
              'nro_doc' => $request->nro_doc,
              'paterno' => $request->paterno,
              'materno' => $request->materno,
              'nombres' => $request->nombres,
              'sexo' => $request->sexo,
              'fec_nacimiento' => $fecha,
              'celular' => $request->celular,
              'correo' => $request->correo,
              'pais' => $request->pais,
              'ubi_residencia' => $request->ubigeo_residencia,
              'grado_instruccion' => $request->grado,
              'id_colegio' => $request->id_colegio
          ]);
  
          $inscripcion = InscripcionSimulacro::create([
              'id_estudiante' => $participante->id,
              'id_simulacro' => 3,
              'estado' => 1,
              'fecha' => date('Y-m-d'),
              'terminos' => $request->terminos,
              'id_programa' => $request->programa
          ]);
  
          DB::commit();
  
          $this->response['titulo'] = 'REGISTRO NUEVO';
          $this->response['mensaje'] = 'PARTICIPANTE ' . $participante->nombres . ' REGISTRADO';
          $this->response['estado'] = true;
          $this->response['datos'] = $participante;
      } catch (\Exception $e) {
          DB::rollback();
          // Manejar el error como desees, por ejemplo, registrar en un archivo de registro.
          $this->response['titulo'] = 'ERROR EN EL REGISTRO';
          $this->response['mensaje'] = 'No se pudo completar el registro: ' . $e->getMessage();
          $this->response['estado'] = false;
      }
  }


}
