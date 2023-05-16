<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proceso;
use App\Models\TipoProceso;
use App\Models\Preinscripcion;
use App\Models\Postulante;
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
    )
    ->where('postulante.nro_doc','=',$request->nro_doc)->get();

    $this->response['estado'] = true;
    $this->response['datos'] = $res;
    return response()->json($this->response, 200);
  }
  
  //END PASO 1 PRE INSCRIPCION 


}
