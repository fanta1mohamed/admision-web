<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Models\Colegio;

class ColegioController extends Controller
{

    public function getUbigeoColegio(Request $request)
    {
      $res = Colegio::select(
        'colegios.id', 'colegios.nombre as colegio', 'colegios.direccion', 
        'postulante.anio_egreso as egreso',
        'departamento.nombre as departamento', 'departamento.codigo as dep',
        'provincia.nombre as provincia', 'provincia.codigo as prov', 
        'distritos.nombre as distrito', 'distritos.codigo as dist'
      )
      ->join('postulante','postulante.id_colegio','colegios.id')
      ->join('ubigeo','colegios.ubigeo','ubigeo.ubigeo')
      ->join('departamento','ubigeo.id_departamento','departamento.id')
      ->join('provincia','ubigeo.id_provincia','provincia.id')
      ->join('distritos','distritos.id','ubigeo.id_distrito')
      ->where('postulante.id','=', $request->id_postulante)
      ->get(10);
  
      $this->response['estado'] = true;
      $this->response['datos'] = $res;
      return response()->json($this->response, 200);
    }

    public function getColegiosDistrito(Request $request) {

      $res = Colegio::select( 'colegios.id as value', 'colegios.nombre as label' )
        ->where('colegios.ubigeo','=',$request->ubigeo_cole)
        ->orderBy('colegios.nombre', 'ASC')->get();
  
      $this->response['estado'] = true;
      $this->response['datos'] = $res;
      return response()->json($this->response, 200);
    }









    
}
