<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Preinscripcion;
use App\Models\Inscripcion;  
use App\Models\Users;
use App\Models\Postulante;
use App\Models\Colegio;
use App\Models\ControlBiometrico;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use DB;

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

    $mins = Inscripcion::selectRaw('COUNT(*) as cant, users.name, users.paterno, users.materno')
    ->join('users','inscripciones.id_usuario','users.id')
    ->where('inscripciones.estado','=',0)
    ->where('inscripciones.id_proceso','=',auth()->user()->id_proceso)
    ->groupBy(DB::raw('users.id'))
    ->orderBy('cant','asc')
    ->limit(5)
    ->get()
    ->reverse()
    ->values();

    $mins = $mins->reverse();

    $this->response['inscriptores'] = $mins;
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


  public function showPostulante($dni) {

    $postulanteInfo = Postulante::select(
        'postulante.id AS id_postulante',
        'postulante.nombres',
        'postulante.email',
        'postulante.celular',
        'departamento.nombre AS departamento',
        'provincia.nombre AS provincia',
        'distritos.nombre AS distrito'
    )
    ->leftJoin('ubigeo', 'ubigeo.ubigeo', '=', 'postulante.ubigeo_residencia')
    ->leftJoin('departamento', 'departamento.id', '=', 'ubigeo.id_departamento')
    ->leftJoin('provincia', 'provincia.id', '=', 'ubigeo.id_provincia')
    ->leftJoin('distritos', 'distritos.id', '=', 'ubigeo.id_distrito')
    ->where('postulante.nro_doc', '=', $dni)
    ->first();

    $colegioInfo = Colegio::select( 'colegios.nombre AS colegio', 'distritos.nombre AS distrito' )
    ->join('postulante','postulante.id_colegio','=','colegios.id')
    ->leftJoin('ubigeo', 'ubigeo.ubigeo', '=', 'postulante.ubigeo_residencia')
    ->leftJoin('distritos', 'distritos.id', '=', 'ubigeo.id_distrito')
    ->where('postulante.nro_doc', '=', $dni)
    ->first();

    $procesos = Inscripcion::select('procesos.id AS id_proceso','procesos.nombre AS proceso','inscripciones.codigo')
    ->join('procesos', 'procesos.id', '=', 'inscripciones.id_proceso')
    ->where('inscripciones.id_postulante', '=', $postulanteInfo->id_postulante)
    ->orderBy('procesos.id', 'desc')
    ->get();

    $foto = "https://inscripciones.admision.unap.edu.pe/fotos/inscripcion/$dni.jpg";

    $countPreInscripcion = Preinscripcion::where('id_postulante', '=', $postulanteInfo->id_postulante)->count();
    $countInscripcion = Inscripcion::where('id_postulante', '=', $postulanteInfo->id_postulante)->count();
    $countControlBiometrico = ControlBiometrico::where('id_postulante', '=', $postulanteInfo->id_postulante)->count();

    //return Inertia::location('perfil-postulante');
    return Inertia::render('Admin/Postulante/Perfil',
      [
        'info' => $postulanteInfo, 
        'infoColegio' => $colegioInfo, 
        'preinscripciones'=>  $countPreInscripcion,
        'inscripciones' => $countInscripcion,
        'control_biometrico' => $countControlBiometrico,
        'foto' => $foto,
        'pro' => $procesos
      ]); 

  }

  // public function getInsPostulante(Request $request){

  //   $procesos = Inscripcion::select('procesos.id AS id_proceso','procesos.nombre AS proceso','inscripciones.codigo')
  //   ->join('procesos', 'procesos.id', '=', 'inscripciones.id_proceso')
  //   ->where('inscripciones.id', '=', $postulanteInfo)
  //   ->orderBy('procesos.id', 'desc')
  //   ->get();

  //   $this->response['datos'] = $procesos;
  //   $this->response['estado'] = true;
  //   return response()->json($this->response, 200);
  // }









}
