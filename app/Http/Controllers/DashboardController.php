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


}
