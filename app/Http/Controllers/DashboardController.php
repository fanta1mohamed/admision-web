<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Preinscripcion;
use App\Models\Inscripcion; 
use App\Models\Users;
use DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

  // $inscripcionesPorGenero = DB::table('pre_inscripcion')
  // ->join('postulante', 'pre_inscripcion.id_postulante', '=', 'postulante.id')
  // ->select('postulante.sexo', DB::raw('COUNT(*) as cantidad'))
  // ->groupBy('postulante.sexo')
  // ->get();
  #Pre inscritos 
  public function preinscritos(){

    $preinscritos = DB::table('pre_inscripcion')
    ->where('id_proceso','=',auth()->user()->id_proceso)
    ->count();

    $lastRegistro = Preinscripcion::selectRaw('COUNT(*) as count, DATE(created_at) as date')
    ->whereNotNull('created_at')
    ->where('id_proceso','=',auth()->user()->id_proceso)
    ->groupBy(DB::raw('DATE(created_at)'))
    ->orderByDesc(DB::raw('DATE(created_at)'))
    ->first();

    $this->response['fecha'] = $lastRegistro;
    $this->response['preinscritos'] = $preinscritos;
    $this->response['estado'] = true;
    return response()->json($this->response, 200);
  }

  public function inscritos(){
    $inscritos = DB::table('inscripciones')
    ->where('estado','=',0)
    ->where('id_proceso','=',auth()->user()->id_proceso)
    ->count();

    $lastRegistro = Inscripcion::selectRaw('COUNT(*) as count, DATE(created_at) as date')
    ->whereNotNull('created_at')
    ->where('id_proceso','=',auth()->user()->id_proceso)
    ->groupBy(DB::raw('DATE(created_at)'))
    ->orderByDesc(DB::raw('DATE(created_at)'))
    ->first();

    $this->response['fecha'] = $lastRegistro;
    $this->response['inscritos'] = $inscritos;
    $this->response['estado'] = true;
    return response()->json($this->response, 200);
  }

  public function mInscriptores(){

    $minscriptores = Inscripcion::selectRaw('COUNT(*) as cant, users.name, users.paterno, users.materno')
    ->join('users','inscripciones.id_usuario','users.id')
    ->where('inscripciones.estado','=',0)
    ->where('inscripciones.id_proceso','=',auth()->user()->id_proceso)
    ->groupBy(DB::raw('users.id'))
    ->orderByDesc(DB::raw('cant'))
    ->limit(4)
    ->get();

    $this->response['inscriptores'] = $minscriptores;
    $this->response['estado'] = true;
    return response()->json($this->response, 200);

  }


  public function mInscriptoresDia(Request $request){

    $minscriptoresD = Inscripcion::selectRaw('COUNT(*) as cant, users.name, users.paterno, users.materno')
    ->join('users','inscripciones.id_usuario','users.id')
    ->where('inscripciones.estado','=',0)
    ->where(DB::raw('date(inscripciones.created_at)'),'=',$request->fecha)
    ->where('inscripciones.id_proceso','=',auth()->user()->id_proceso)
    ->groupBy(DB::raw('users.id'))
    ->orderByDesc(DB::raw('cant'))
    ->limit(6)
    ->get();

    $this->response['inscriptores'] = $minscriptoresD;
    $this->response['estado'] = true;
    return response()->json($this->response, 200);

  }


  //Reportes varios

  public function reporteInscritosGenero(Request $request){
    
    $resultados = Inscripcion::join('postulante', 'postulante.id', '=', 'inscripciones.id_postulante')
    ->selectRaw('COUNT(*) AS cant, postulante.sexo')
    ->where('inscripciones.estado', '=', 0)
    ->where('inscripciones.id_proceso', '=', 5)
    ->groupBy('postulante.sexo')
    ->orderByDesc('cant')
    ->get();

    $this->response['datos'] = $resultados;
    $this->response['estado'] = true;
    return response()->json($this->response, 200);
  }


  public function reporteInscritosEdad(Request $request){
    
    $resultados = Inscripcion::join('postulante', 'postulante.id', '=', 'inscripciones.id_postulante')
    ->select(
        DB::raw('COUNT(*) AS cantidad'),
        DB::raw('TIMESTAMPDIFF(YEAR, postulante.fec_nacimiento, CURDATE()) AS edad')
    )
    ->where('inscripciones.estado', '=', 0)
    ->where('inscripciones.id_proceso', '=', 5)
    ->groupBy('edad')
    ->orderByDesc('cantidad','edad')
    ->limit(7)
    ->get();

    $this->response['datos'] = $resultados;
    $this->response['estado'] = true;
    return response()->json($this->response, 200);
  }


  public function reporteInscritosResidencia(Request $request){
    
    $resultados = Inscripcion::join('postulante', 'postulante.id', '=', 'inscripciones.id_postulante')
    ->join('ubigeo', 'postulante.ubigeo_residencia', '=', 'ubigeo.ubigeo')
    ->join('departamento', 'ubigeo.id_departamento', '=', 'departamento.id')
    ->join('provincia', 'ubigeo.id_provincia', '=', 'provincia.id')
    ->join('distritos', 'ubigeo.id_distrito', '=', 'distritos.id')
    ->select(
        DB::raw('COUNT(*) AS cant'),
        'departamento.nombre AS dep',
        'provincia.nombre AS prov',
        'distritos.nombre AS dist'
    )
    ->where('inscripciones.estado', '=', 0)
    ->where('inscripciones.id_proceso', '=', 5)
    ->groupBy('dep', 'prov', 'dist')
    #->orderByDesc('cant')
    ->orderByDesc('cant')
     ->limit(6)
    ->get();

    $this->response['datos'] = $resultados;
    $this->response['estado'] = true;
    return response()->json($this->response, 200);
  }


  public function reporteInscritosProcedencia(Request $request){
    
    $resultados = Inscripcion::join('postulante', 'postulante.id', '=', 'inscripciones.id_postulante')
    ->join('ubigeo', 'postulante.ubigeo_nacimiento', '=', 'ubigeo.ubigeo')
    ->join('departamento', 'ubigeo.id_departamento', '=', 'departamento.id')
    ->join('provincia', 'ubigeo.id_provincia', '=', 'provincia.id')
    ->join('distritos', 'ubigeo.id_distrito', '=', 'distritos.id')
    ->select(
        DB::raw('COUNT(*) AS cant'),
        'departamento.nombre AS dep',
        'provincia.nombre AS prov',
        'distritos.nombre AS dist'
    )
    ->where('inscripciones.estado', '=', 0)
    ->where('inscripciones.id_proceso', '=', 5)
    ->groupBy('dep', 'prov', 'dist')
    ->orderByDesc('cant')
    ->limit(8)
    ->get();

    $this->response['datos'] = $resultados;
    $this->response['estado'] = true;
    return response()->json($this->response, 200);
  }

  public function reporteInscritosEgreso(Request $request){
    
    $resultados = Inscripcion::join('postulante', 'postulante.id', '=', 'inscripciones.id_postulante')
    ->select(
        DB::raw('COUNT(*) AS cant'),
        'postulante.anio_egreso AS egreso'
    )
    ->where('inscripciones.estado', '=', 0)
    ->where('inscripciones.id_proceso', '=', 5)
    ->groupBy('egreso')
    ->orderByDesc('cant')
    ->limit(7)
    ->get();

    $this->response['datos'] = $resultados;
    $this->response['estado'] = true;
    return response()->json($this->response, 200);
  }

  public function reporteInscritosDiscapacidad(Request $request){
    
    $resultados = Inscripcion::join('postulante', 'postulante.id', '=', 'inscripciones.id_postulante')
    ->select(
        DB::raw('COUNT(*) AS cant'),
        'postulante.discapacidad AS discapacidad'
    )
    ->where('inscripciones.estado', '=', 0)
    ->where('inscripciones.id_proceso', '=', 5)
    ->groupBy('discapacidad')
    ->orderByDesc('cant')
    ->get();

    $this->response['datos'] = $resultados;
    $this->response['estado'] = true;
    return response()->json($this->response, 200);
  }

  public function reporteInscritosTipoDocumento(Request $request){
    
    $resultados = Inscripcion::join('postulante', 'postulante.id', '=', 'inscripciones.id_postulante')
    ->join('tipo_documento_identidad', 'postulante.tipo_doc', '=', 'tipo_documento_identidad.id')
    ->select(
        DB::raw('COUNT(*) AS cant'),
        'tipo_documento_identidad.nombre AS tipo_doc'
    )
    ->where('inscripciones.estado', '=', 0)
    ->where('inscripciones.id_proceso', '=', 5)
    ->groupBy('tipo_documento_identidad.nombre')
    ->orderByDesc('cant')
    ->get();

    $this->response['datos'] = $resultados;
    $this->response['estado'] = true;
    return response()->json($this->response, 200);
  }

  public function reporteInscritosColegio(Request $request){
    
    $resultados = Inscripcion::join('postulante', 'postulante.id', '=', 'inscripciones.id_postulante')
    ->join('colegios', 'colegios.id', '=', 'postulante.id_colegio')
    ->select(
        DB::raw('COUNT(*) AS cant'),
        'colegios.nombre AS cole',
        'colegios.cod_modular'
    )
    ->where('inscripciones.estado', '=', 0)
    ->where('inscripciones.id_proceso', '=', 5)
    ->groupBy('colegios.cod_modular', 'colegios.nombre')
    ->orderByDesc('cant')
    ->Limit(7)
    ->get();

    $this->response['datos'] = $resultados;
    $this->response['estado'] = true;
    return response()->json($this->response, 200);
  }

  public function reporteInscritosProcedenciaColegio(Request $request){
    
    $resultados = Inscripcion::join('postulante', 'postulante.id', '=', 'inscripciones.id_postulante')
    ->join('colegios', 'colegios.id', '=', 'postulante.id_colegio')
    ->join('ubigeo', 'colegios.ubigeo', '=', 'ubigeo.ubigeo')
    ->join('departamento', 'ubigeo.id_departamento', '=', 'departamento.id')
    ->join('provincia', 'ubigeo.id_provincia', '=', 'provincia.id')
    ->join('distritos', 'ubigeo.id_distrito', '=', 'distritos.id')
    ->select(
        DB::raw('COUNT(*) AS cant'),
        'departamento.nombre AS dep',
        'provincia.nombre AS prov',
        'distritos.nombre AS dist'
    )
    ->where('inscripciones.estado', '=', 0)
    ->where('inscripciones.id_proceso', '=', 5)
    ->groupBy('dep', 'prov', 'dist')
    ->orderByDesc('cant')
    ->Limit(7)
    ->get();

    $this->response['datos'] = $resultados;
    $this->response['estado'] = true;
    return response()->json($this->response, 200);
  }

  public function reporteInscritosTipoColegio(Request $request){
    
    $resultados = Inscripcion::join('postulante', 'postulante.id', '=', 'inscripciones.id_postulante')
    ->join('colegios', 'colegios.id', '=', 'postulante.id_colegio')
    ->select(
        DB::raw('COUNT(*) AS cant'),
        'colegios.gestion AS tipo_colegio'
    )
    ->where('inscripciones.estado', '=', 0)
    ->where('inscripciones.id_proceso', '=', 5)
    ->groupBy('colegios.gestion')
    ->orderByDesc('cant')
    ->get();

    $this->response['datos'] = $resultados;
    $this->response['estado'] = true;
    return response()->json($this->response, 200);
  }



}
