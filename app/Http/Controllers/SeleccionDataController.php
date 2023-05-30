<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departamento;
use App\Models\Provincia;
use App\Models\Distrito;
use App\Models\Pais;
use App\Models\Paso;
use App\Models\Filial;
use App\Models\Comprobante;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SeleccionDataController extends Controller
{

  protected $provincia;


  public function __construct()
  {
    $this->provincia = new Provincia();
  } 

  public function getPaises(Request $request) {
    $res = Pais::select(
      'distritos.codigo as key', 'distritos.nombre as value' 
    )
      ->join('ubigeo','distritos.id','ubigeo.id_distrito')
      ->join('departamento','departamento.id','ubigeo.id_departamento')
      ->join('provincia','provincia.id','ubigeo.id_provincia')
      ->where('departamento.codigo','=',$request->departamento)
      ->where('provincia.codigo','=',$request->provincia)
      ->where(function ($query) use ($request) {
        return $query
            ->orWhere('distritos.codigo', 'LIKE', '%' . $request->term . '%')
              ->orWhere('distritos.nombre', 'LIKE', '%' . $request->term . '%');
      })->orderBy('distritos.nombre', 'ASC')->get();

    $this->response['estado'] = true;
    $this->response['datos'] = $res;
    return response()->json($this->response, 200);
  }

  public function getDistritosCodigo(Request $request)
  {

    $res = Distrito::select(
      'distritos.codigo as key', 'distritos.nombre as value' 
    )
      ->join('ubigeo','distritos.id','ubigeo.id_distrito')
      ->join('departamento','departamento.id','ubigeo.id_departamento')
      ->join('provincia','provincia.id','ubigeo.id_provincia')
      ->where('departamento.codigo','=',$request->departamento)
      ->where('provincia.codigo','=',$request->provincia)
      ->where(function ($query) use ($request) {
        return $query
            ->orWhere('distritos.codigo', 'LIKE', '%' . $request->term . '%')
              ->orWhere('distritos.nombre', 'LIKE', '%' . $request->term . '%');
      })->orderBy('distritos.nombre', 'ASC')->get();

    $this->response['estado'] = true;
    $this->response['datos'] = $res;
    return response()->json($this->response, 200);
  }

  public function getProvinciasCodigo(Request $request)
  {
    $res = Provincia::select(
      'provincia.codigo as key', 'provincia.nombre as value' 
    )
      ->join('ubigeo','provincia.id','ubigeo.id_provincia')
      ->join('departamento','departamento.id','ubigeo.id_departamento')
      ->where('departamento.codigo','=',$request->departamento)
      ->where(function ($query) use ($request) {
        return $query
            ->orWhere('provincia.codigo', 'LIKE', '%' . $request->term . '%')
              ->orWhere('provincia.nombre', 'LIKE', '%' . $request->term . '%');
      })->orderBy('provincia.nombre', 'ASC')->get();

    $this->response['estado'] = true;
    $this->response['datos'] = $res;
    return response()->json($this->response, 200);
  }
  

  public function getDepartamentoCodigo(Request $request)
  {
    $res = Departamento::select(
      'codigo as key', 'nombre as value' 
    )
      ->where(function ($query) use ($request) {  
        return $query
            ->orWhere('departamento.codigo', 'LIKE', '%' . $request->term . '%')
              ->orWhere('departamento.nombre', 'LIKE', '%' . $request->term . '%');
      })->orderBy('departamento.nombre', 'ASC')
      ->paginate(25);

    $this->response['estado'] = true;
    $this->response['datos'] = $res;
    return response()->json($this->response, 200);
  }
  
  public function getDepartamento(Request $request)
  {
    $query_where = [];
    $res = Departamento::select(
        'id as key', 'nombre as value' 
    )
      ->where($query_where)
      ->where(function ($query) use ($request) {
        return $query
            ->orWhere('departamento.codigo', 'LIKE', '%' . $request->term . '%')
              ->orWhere('departamento.nombre', 'LIKE', '%' . $request->term . '%');
      })->orderBy('departamento.nombre', 'ASC')
      ->paginate(25);

    $this->response['estado'] = true;
    $this->response['datos'] = $res;
    return response()->json($this->response, 200);
  }

  public function getProvincias(Request $request)
  {
    $query_where = [];
    $res = Provincia::select(
        'id as key', 'nombre as value' 
    )
      ->where($query_where)
      ->where(function ($query) use ($request) {
        return $query
            ->orWhere('departamento.codigo', 'LIKE', '%' . $request->term . '%')
              ->orWhere('departamento.nombre', 'LIKE', '%' . $request->term . '%');
      })->orderBy('departamento.nombre', 'ASC')
      ->paginate(25);

    $this->response['estado'] = true;
    $this->response['datos'] = $res;
    return response()->json($this->response, 200);
  }

  public function getProvinciasPorDepartamento($departamento)
  {
      $res = $this->provincia->getProvinciasPorDepartamento($departamento)->map(function ($item) {
          return [
              'value' => $item->id,
              'label' => $item->nombre,
          ];
      });
      $this->response['estado'] = true;
      $this->response['datos'] = $res;
      return response()->json($this->response, 200);
  }

  public function getFacultades()
  {
      $res = DB::select('select id as value, facultad as label from facultad');
      $this->response['estado'] = true;
      $this->response['datos'] = $res;
      return response()->json($this->response, 200);
  }

  public function getSedes(Request $request){

    $query_where = [];
    $res = Filial::select(
        'id as value', 'nombre as label' 
    )
      ->where($query_where)
      ->where('estado','=',1)
      ->where(function ($query) use ($request) {
        return $query
            ->orWhere('filial.codigo', 'LIKE', '%' . $request->term . '%')
              ->orWhere('filial.nombre', 'LIKE', '%' . $request->term . '%');
      })->orderBy('filial.id', 'ASC')
      ->paginate(10);

    $this->response['estado'] = true;
    $this->response['datos'] = $res;
    return response()->json($this->response, 200);
  }

  public function getComprobanteByDni(Request $request){

    $monto = null; $res = null;

    $res = DB::select('SELECT sum(monto) as sum_monto, ndoc_postulante from comprobante
    WHERE codigo = "26" AND estado = 1 AND ndoc_postulante = '.$request->dni
    .' GROUP BY ndoc_postulante ');

    $monto = DB::select('SELECT MIN(monto) as monto FROM proceso_monto_comprobante
    WHERE id_proceso = 31 AND cod_comprobante = "26"');

    if($res !== []) {

      if($res[0]->sum_monto >= $monto[0]->monto ) {

        $this->response['voucher'] = true;
        $this->response['mensaje'] = "PUEDE CONTINUAR SU POSTULACIÓN";
        $this->response['datos'] = $res[0];
        return response()->json($this->response, 200);

      }else {
        $this->response['voucher'] = false;
        $this->response['mensaje'] = "EL MONTO PAGADO NO ES EL CORRECTO";
        return response()->json($this->response, 200);
      }
    } else {
      $this->response['mensaje'] = "NO TENEMOS VOUCHERS REGISTRADOS PARA ESTE PROCESO A SU NOMBRE";
      $this->response['voucher'] = false;
      return response()->json($this->response, 200);
    }

  }


  public function getPasos(Request $request)
  {
    $res = Paso::select(
      'id', 'nombre', 'nro','avance', 'proceso', 'postulante' 
    )
    ->where('paso.postulante','=',$request->postulante)
    ->where('paso.proceso','=',$request->proceso)
    ->orderby('id','desc')
    ->limit(1)
    ->get(); 

    $this->response['estado'] = true;
    $this->response['datos'] = $res;
    return response()->json($this->response, 200);
  
  }

}
