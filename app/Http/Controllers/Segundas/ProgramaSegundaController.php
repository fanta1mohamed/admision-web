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

    
    
    


}
