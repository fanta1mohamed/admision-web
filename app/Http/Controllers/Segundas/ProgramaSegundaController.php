<?php
namespace App\Http\Controllers\Segundas;
use App\Http\Controllers\Controller;
use App\Models\Programa;
use Illuminate\Http\Request;

class ProgramaSegundaController extends Controller
{
    public function getSelectProgramas(Request $request)
    {
      $res = Programa::select( 'id as value', 'nombre_corto as label' )
      ->where('estado','=',1)
      ->where('nivel_academico','=','Segunda Especialidad')
      ->where(function ($query) use ($request) {
          return $query
              ->orWhere('codigo', 'LIKE', '%' . $request->term . '%')
              ->orWhere('nombre_corto', 'LIKE', '%' . $request->term . '%');
      })->orderBy('nombre', 'ASC')
      ->get(50);
  
      $this->response['estado'] = true;
      $this->response['datos'] = $res;
      return response()->json($this->response, 200);
    }

    public function getSelectProgramasAutorizados(Request $request)
    {
      if (auth()->user()->programas != null) {
          $array = json_decode(auth()->user()->programas, true);
          if (!empty($array)) {
              $query_where[] = ['programa.id', $array];
          }
      }
      $res = Programa::select( 'id as value', 'nombre_corto as label' )
      ->where('estado','=',1)
      ->where('nivel_academico','=','Segunda Especialidad')
      ->when(!empty($query_where), function ($query) use ($query_where) {
          foreach ($query_where as $condition) {
              if (is_array($condition[1])) {
                  $query->whereIn($condition[0], $condition[1]);
              } else {
                  $query->where($condition[0], $condition[1], $condition[2] ?? null);
              }
          }
      })
      ->where(function ($query) use ($request) {
          return $query
              ->orWhere('codigo', 'LIKE', '%' . $request->term . '%')
              ->orWhere('nombre_corto', 'LIKE', '%' . $request->term . '%');
      })->orderBy('nombre', 'ASC')
      ->get(50);
  
      $this->response['estado'] = true;
      $this->response['datos'] = $res;
      return response()->json($this->response, 200);
    }

    

    
    
    


}
