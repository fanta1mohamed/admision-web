<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Models\Modalidad;

class ModalidadController extends Controller
{
    public function index()
    {
        return Inertia::render('Modalidad/index');
        
    }  

    public function getModalidades(Request $request)
    {
      $query_where = [];

      $res = Modalidad::select(
        'modalidad.id',
        'modalidad.codigo',
        'modalidad.nombre',
      )
      ->where($query_where)
      ->where(function ($query) use ($request) {
          return $query
              ->orWhere('modalidad.codigo', 'LIKE', '%' . $request->term . '%')
              ->orWhere('modalidad.nombre', 'LIKE', '%' . $request->term . '%');
      })->orderBy('modalidad.id', 'DESC')
      ->paginate(10);
  
      $this->response['estado'] = true;
      $this->response['datos'] = $res;
      return response()->json($this->response, 200);
    }


    public function saveModalidad(Request $request ) {

        $modalidad = null;
        if (!$request->id) {
            $modalidad = Modalidad::create([
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'id_usuario' => auth()->id()
            ]);
            $this->response['titulo'] = 'REGISTRO NUEVO';
            $this->response['mensaje'] = 'MODALIDAD '.$modalidad->nombre.' CREADA CON EXITO';
            $this->response['estado'] = true;
            $this->response['datos'] = $modalidad;
        } else {

            $modalidad = Modalidad::find($request->id);
            $modalidad->nombre = $request->nombre;
            $modalidad->codigo = $request->codigo;
            $modalidad->id_usuario = auth()->id();
            $modalidad->save();

            $this->response['titulo'] = '!REGISTRO MODIFICADO!';
            $this->response['mensaje'] = 'MODALIDAD '.$modalidad->nombre.' MODIFICADA CON EXITO';
            $this->response['estado'] = true;
            $this->response['datos'] = $modalidad;
        }

    return response()->json($this->response, 200);
  }

  public function deleteModalidad($id){
    $modalidad = Modalidad::find($id);
    $p = $modalidad;
    $modalidad->delete();

    $this->response['titulo'] = '!REGISTRO ELIMINADO!';
    $this->response['mensaje'] = 'MODALIDAD '.$p->nombre.' ELIMINADA CON EXITO';
    $this->response['estado'] = true;
    $this->response['datos'] = $p;
    return response()->json($this->response, 200);
  }


  public function getSelectModalidades(Request $request) 
  {
    $res = Modalidad::select('id as dataIndex', 'nombre as title')
        ->where('estado', '=', 1)
        ->where(function ($query) use ($request) {
            return $query->orWhere('nombre', 'LIKE', '%' . $request->term . '%');
        })
        ->orderBy('nombre', 'ASC')
        ->paginate(20);
    $data = $res->toArray();

    array_unshift($data['data'], ['dataIndex' => 0, 'title' => 'Programa', 'width' => '300px']);

    $res->setCollection(collect($data['data']));

    $this->response['estado'] = true;
    $this->response['datos'] = $res;

    return response()->json($this->response, 200);

  }
    
}
