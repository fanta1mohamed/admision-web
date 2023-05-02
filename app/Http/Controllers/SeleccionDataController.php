<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departamento;
use App\Models\Provincia;
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

  public  function getProvinciasPorDepartamento($departamento)
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

    $query_where = [];
    $res = Comprobante::select('*')
      ->where($query_where)
      ->where('estado','=',1)
      ->where('ndoc_postulante','=',$request->dni)
      ->where('codigo','=',26)
      ->where(function ($query) use ($request) {
        return $query
            ->orWhere('comprobante.codigo', 'LIKE', '%' . $request->term . '%')
            ->orWhere('comprobante.fecha', 'LIKE', '%' . $request->term . '%');
      })->orderBy('comprobante.id', 'ASC')
      ->paginate(50);

    $this->response['estado'] = true;
    $this->response['datos'] = $res;
    return response()->json($this->response, 200);
  }




}
