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
use App\Models\Pago;
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

  public function getInscritosSimulacro(Request $request){
      $columnas = [
        'inscripcion_simulacro.id as id_inscripcion',
        'participantes_simulacro.id AS id_participante',
        'participantes_simulacro.nro_doc', 'participantes_simulacro.paterno', 'participantes_simulacro.materno',
        'participantes_simulacro.nombres', 'participantes_simulacro.sexo','programa.nombre as programa',
        'colegios.nombre as colegio',
        DB::raw('CONCAT(departamento.nombre, "/", provincia.nombre, "/", distritos.nombre) as lugar')
      ];

      $res = InscripcionSimulacro::select($columnas)
          ->join('participantes_simulacro','participantes_simulacro.id','inscripcion_simulacro.id_estudiante')
          ->join('programa','inscripcion_simulacro.id_programa','programa.id')
          ->join('colegios','colegios.id','participantes_simulacro.id_colegio')
          ->join('ubigeo', 'ubigeo.ubigeo', '=', 'colegios.ubigeo')
          ->join('departamento', 'ubigeo.id_departamento', '=', 'departamento.id')
          ->join('provincia', 'ubigeo.id_provincia', '=', 'provincia.id')
          ->join('distritos', 'ubigeo.id_distrito', '=', 'distritos.id')
          ->where(function ($query) use ($request) {
              return $query
              ->orWhere('participantes_simulacro.nombres','%' . $request->term . '%')
              ->orWhere('participantes_simulacro.paterno','%' . $request->term . '%')
              ->orWhere('participantes_simulacro.materno','%' . $request->term . '%')
              ->orWhere('participantes_simulacro.nro_doc','%' . $request->term . '%')
              ->orWhere('colegios.nombre', 'LIKE', '%' . $request->term . '%')
              ->orWhere('programa.nombre', 'LIKE', '%' . $request->term . '%')
              ->orWhere(DB::raw('CONCAT(participantes_simulacro.nombres," ", participantes_simulacro.paterno," ", participantes_simulacro.materno)'),'LIKE','%'.$request->term . '%')
              ->orWhere(DB::raw('CONCAT(departamento.nombre, "/", provincia.nombre, "/", distritos.nombre)'),'LIKE','%'.$request->term . '%');
          })
          // ->paginate(10);
          ->paginate($request->paginashoja);

      $this->response['estado'] = true;
      $this->response['datos'] = $res;
      return response()->json($this->response, 200);
  }


  public function getParticipantesSimulacro(Request $request)
  {
      $columnas = [
          'ps.id AS id_participante', 'ps.tipo_doc',
          'ps.nro_doc', 'ps.paterno', 'ps.materno',
          'ps.nombres', 'ps.sexo',
          'ps.grado_instruccion as instruccion', 'ps.celular',
          'ps.correo', 'ps.fec_nacimiento as fec_nac','ps.ubi_residencia as ubigeo',
          DB::raw('date_format(ps.fec_nacimiento, "%d/%m/%Y") as fec_nacimiento'),
          DB::raw('CONCAT(d.nombre, "/", p.nombre, "/", di.nombre) as lugar'),
          DB::raw('CONCAT(dc.nombre, "/", pc.nombre, "/", dic.nombre) as lugarcolegio'),
          'colegios.nombre as colegio','colegios.id as idcolegio', 'colegios.ubigeo as ubigeocolegio'
      ];
  
      $res = ParticipanteSimulacro::from('participantes_simulacro as ps')
          ->select($columnas)
          ->join('ubigeo as u', 'u.ubigeo', '=', 'ps.ubi_residencia')
          ->join('departamento as d', 'u.id_departamento', '=', 'd.id')
          ->join('provincia as p', 'u.id_provincia', '=', 'p.id')
          ->join('distritos as di', 'u.id_distrito', '=', 'di.id')
          ->join('colegios', 'colegios.id', '=', 'ps.id_colegio')
          ->join('ubigeo as uc', 'uc.ubigeo', '=', 'colegios.ubigeo')
          ->join('departamento as dc', 'uc.id_departamento', '=', 'dc.id')
          ->join('provincia as pc', 'uc.id_provincia', '=', 'pc.id')
          ->join('distritos as dic', 'uc.id_distrito', '=', 'dic.id')
          ->where(function ($query) use ($request) {
              return $query
                  ->orWhere('ps.nombres', 'LIKE', '%' . $request->term . '%')
                  ->orWhere('ps.paterno', 'LIKE', '%' . $request->term . '%')
                  ->orWhere('ps.materno', 'LIKE', '%' . $request->term . '%')
                  ->orWhere('ps.nro_doc', 'LIKE', '%' . $request->term . '%')
                  ->orWhere(DB::raw('CONCAT(ps.nombres," ", ps.paterno," ", ps.materno)'), 'LIKE', '%' . $request->term . '%')
                  ->orWhere(DB::raw('CONCAT(d.nombre, "/", p.nombre, "/", di.nombre)'), 'LIKE', '%' . $request->term . '%');
          })
          ->paginate($request->paginashoja);
  
      $this->response['estado'] = true;
      $this->response['datos'] = $res;
      return response()->json($this->response, 200);
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


  // public function getParticipantesSimulacro(Request $request)
  // {  
  //   $res = DetalleExamenSimulacro::select(
  //     'examen_simulacro.area',
  //     'participantes_simulacro.nro_doc as dni', 
  //     'participantes_simulacro.nombres', 'participantes_simulacro.primer_apellido', 
  //     'participantes_simulacro.segundo_apellido', 
  //     DB::raw('SUM(nota) AS nota'))
  //     ->leftjoin('participantes_simulacro','detalle_examen_simulacro.dni_postulante','participantes_simulacro.nro_doc')  
  //     ->leftjoin('examen_simulacro','detalle_examen_simulacro.id_examen_simulacro','examen_simulacro.id')
  //     ->where('detalle_examen_simulacro.id_examen_simulacro','=',$request->examen)
  //     ->where(function ($query) use ($request) {
  //         return $query
  //             ->orWhere('participantes_simulacro.nro_doc', 'LIKE', '%' . $request->term . '%')
  //             ->orWhere('participantes_simulacro.nombres', 'LIKE', '%' . $request->term . '%')
  //             ->orWhere('participantes_simulacro.primer_apellido', 'LIKE', '%' . $request->term . '%');
  //     })->orderBy('nota', 'DESC')
  //     ->groupBy('participantes_simulacro.id', 'participantes_simulacro.id','examen_simulacro.id')
  //     ->paginate(100);

  //   $this->response['estado'] = true;
  //   $this->response['datos'] = $res;
  //   return response()->json($this->response, 200);
  // }


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

      $datos = DB::select("
        SELECT 
        programa.nombre AS programa,
        participantes_simulacro.nro_doc,
        participantes_simulacro.paterno,
        participantes_simulacro.materno,
        participantes_simulacro.nombres
        FROM inscripcion_simulacro
        JOIN programa ON inscripcion_simulacro.id_programa = programa.id
        JOIN participantes_simulacro ON inscripcion_simulacro.id_estudiante = participantes_simulacro.id
        WHERE participantes_simulacro.nro_doc = $dni
      ");

      if (count($datos) === 0) {
          return "No registrado";
      }else {
        $data = $datos[0];
        $pdf = Pdf::loadView('simulacro.inscripcion', ['fondo' => $this->fondo,'datos'=>$data]);        
        $pdf->setPaper('A4', 'portrait');
        $output = $pdf->output();

        file_put_contents(public_path('/documentos/simulacro2023/').$dni.'.pdf', $output);
        return $pdf->stream();
      }
      // return $pdf->download();

  }

  public function Inscrito($dni){

    $exists = InscripcionSimulacro::join('participantes_simulacro', 'inscripcion_simulacro.id_estudiante', '=', 'participantes_simulacro.id')
    ->where('participantes_simulacro.nro_doc', $dni)
    ->exists();

    if ($exists) {
      $this->response['mensaje'] = 'El postulante existe';
      $this->response['estado'] = true;
    } else {
      $this->response['mensaje'] = 'El postulante no existe';
      $this->response['estado'] = false;
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
        $this->response['mensaje'] = 'Los datos del colegio de '.$postulante->nombres.' se actualizó.';
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

          $pago = Pago::find($request->id_pago);
          $pago->id_inscripcion = $inscripcion->id;
          $pago->status = 0;
          $pago->save();

  
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

    public function updateParticipante(Request $request){
        $participante = ParticipanteSimulacro::find($request->id);
        $temp = $participante->getAttributes();

        $participante->nro_doc = $request->nro_doc;
        $participante->nombres = $request->nombres;
        $participante->paterno = $request->paterno;
        $participante->materno = $request->materno;
        $participante->sexo = $request->sexo;
        $participante->fec_nacimiento = substr($request->fec_nac, 0, 10);
        $participante->celular = $request->celular;
        $participante->correo = $request->correo;
        $participante->ubi_residencia = $request->ubigeo_residencia;
        $participante->grado_instruccion = $request->grado;
        $participante->id_colegio = $request->id_colegio;
        $participante->tipo_doc = $request->tipo_doc;

        if ($temp != $participante->getAttributes()) {
          $participante->save();
          $this->response['tipo'] = 'info';
          $this->response['titulo'] = '!REGISTRO MODIFICADO!';
          $this->response['estado'] = true;

        }else {
          $this->response['estado'] = false;
        }

        return response()->json($this->response, 200);
    }


    public function postulantesRegistrados() {

      $nPar = ParticipanteSimulacro::count();
      $ultReg = ParticipanteSimulacro::selectRaw('COUNT(*) as count, DATE(created_at) as fecha')
      ->groupByRaw('DATE(created_at)')
      ->orderByDesc('fecha')
      ->first();

      $this->response['particpantes'] = $nPar ;
      $this->response['ultimoparticipante'] = $ultReg;
      $this->response['estado'] = true;

      return response()->json($this->response, 200);

    }

    public function postulantesInscritos() {

      $nIns = InscripcionSimulacro::count();
      $ultReg = InscripcionSimulacro::selectRaw('COUNT(*) as count, DATE(created_at) as fecha')
      ->groupByRaw('DATE(created_at)')
      ->orderByDesc('fecha')
      ->first();

      $this->response['inscritos'] = $nIns;
      $this->response['ultimoinscrito'] = $ultReg;
      $this->response['estado'] = true;

      return response()->json($this->response, 200);

    }

    public function pagosRegistrados() {

      $nPagos = Pago::count();
      $ultReg = Pago::selectRaw('COUNT(*) as count, DATE(created_at) as fecha')
      ->groupByRaw('DATE(created_at)')
      ->orderByDesc('fecha')
      ->first();

      $this->response['n_pagos'] = $nPagos;
      $this->response['ultimoregistro'] = $ultReg;
      $this->response['estado'] = true;

      return response()->json($this->response, 200);

    }

    public function postulantexPrograma(){
        $programasConCantidad = InscripcionSimulacro::selectRaw('COUNT(*) as cantidad, programa.nombre as programa, programa.area as area')
        ->join('programa', 'inscripcion_simulacro.id_programa', '=', 'programa.id')
        ->groupBy('programa.nombre','programa.area')
        ->orderByDesc('cantidad')
        ->get();

        $this->response['datos'] = $programasConCantidad;
        $this->response['estado'] = true;
        return response()->json($this->response, 200);
    }


    //REPORTES

    public function reporteInscritosGenero(){
    
      $resultados = InscripcionSimulacro::join('participantes_simulacro', 'participantes_simulacro.id', '=', 'inscripcion_simulacro.id_estudiante')
        ->selectRaw('COUNT(*) AS cant, participantes_simulacro.sexo')
        ->where('inscripcion_simulacro.estado', '=', 1)
        ->where('inscripcion_simulacro.id_simulacro', '=', 3)
        ->groupBy('participantes_simulacro.sexo')
        ->orderByDesc('cant')
        ->get();
  
      $this->response['datos'] = $resultados;
      $this->response['estado'] = true;
      return response()->json($this->response, 200);
    }

    public function reporteInscritosAreas(){
    
      $resultados = InscripcionSimulacro::join('programa', 'inscripcion_simulacro.id_programa', '=', 'programa.id')
        ->selectRaw('COUNT(*) AS cant, programa.area as areas')
        ->where('inscripcion_simulacro.estado', '=', 1)
        ->where('inscripcion_simulacro.id_simulacro', '=', 3)
        ->groupBy('programa.area')
        ->get();
  
      $this->response['datos'] = $resultados;
      $this->response['estado'] = true;
      return response()->json($this->response, 200);
    }




}
