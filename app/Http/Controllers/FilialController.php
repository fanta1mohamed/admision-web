<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Models\Filial;
use App\Models\Dataversion;

class FilialController extends Controller
{
    public function index()
    {
        return Inertia::render('Filial/filial'); 
    }  

    public function getFiliales(Request $request)
    {
      $query_where = [];
     
     // array_push($query_where, ['filial.cod_dep', '=', 'provincia.cod_dep']);

      $res = Filial::select(
        'filial.id',
        'filial.codigo',
        'filial.nombre',
        'departamento.nombre AS departamento',
        'departamento.id AS id_dep',
        'provincia.nombre AS provincia',
        'provincia.id as id_prov',
        'filial.estado AS estado',
        'filial.efi'
      )
        ->join ('departamento', 'filial.id_dep', '=','departamento.id' )
        ->join ('provincia', 'filial.id_prov', '=','provincia.id')
        ->where($query_where)
        ->where(function ($query) use ($request) {
            return $query
                ->orWhere('filial.codigo', 'LIKE', '%' . $request->term . '%')
                ->orWhere('departamento.nombre', 'LIKE', '%' . $request->term . '%')
                ->orWhere('filial.nombre', 'LIKE', '%' . $request->term . '%');
        })->orderBy('filial.id', 'DESC')
        ->paginate(10);
  
      $this->response['estado'] = true;
      $this->response['datos'] = $res;
      return response()->json($this->response, 200);
    }


    public function saveFilial(Request $request ) {

        $filial = null;
        if (!$request->id) {
            $filial = Filial::create([
                'nombre' => $request->nombre,
                'codigo' => $request->codigo,
                'id_dep' => $request->id_dep,
                'id_prov' => $request->id_prov,
                'estado' => $request->estado,
                'id_usuario' => auth()->id()
            ]);
            $this->response['titulo'] = 'REGISTRO NUEVO';
            $this->response['mensaje'] = 'Filial '.$filial->nombre.' creada con exito';
            $this->response['estado'] = true;
            $this->response['datos'] = $filial;
        } else {

            $filial = Filial::find($request->id);
            Dataversion::create([ 'registro_id' => $filial->id, 'tabla' => $filial->getTable(),  'usuario_id' => auth()->id(), 'fecha' => now(), 'datos' => $filial->toJson() ]);

            $filial->nombre = $request->nombre;
            $filial->codigo = $request->codigo;
            $filial->id_dep = $request->id_dep;
            $filial->id_prov = $request->id_prov;
            $filial->estado = $request->estado;
            $filial->id_usuario = auth()->id();
            $filial->save();  

            $this->response['titulo'] = '!REGISTRO MODIFICADO!';
            $this->response['mensaje'] = 'Filial '.$filial->nombre.' modificado con exito';
            $this->response['estado'] = true;
            $this->response['datos'] = $filial;
        }

    return response()->json($this->response, 200);
  }


  public function deleteFilial($id){
    $filial = Filial::find($id);
    $p = $filial;
    $filial->delete();

    $this->response['titulo'] = '!REGISTRO ELIMINADO!';
    $this->response['mensaje'] = 'Filial '.$p->nombre.' eliminada con exito';
    $this->response['estado'] = true;
    $this->response['datos'] = $p;
    return response()->json($this->response, 200);
  }

    


}
