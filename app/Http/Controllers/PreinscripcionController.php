<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proceso;
use App\Models\TipoProceso;
use App\Modeñs\Preinscripcion;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;



class PreinscripcionController extends Controller
{
  public function index()
  {
      return Inertia::render('Preinscripcion/index');        
  }










  public function getProcesos(Request $request)
  {
    $query_where = [];
    $res = Proceso::select(
        'procesos.id', 'procesos.nombre','procesos.estado','procesos.anio',
        'filial.id as id_sede', 'filial.nombre as sede',
        'tipo_proceso.id as id_tipo', 'tipo_proceso.nombre as tipo',
        'modalidad_proceso.id as id_modalidad', 'modalidad_proceso.nombre as modalidad'
    )
      ->join ('filial', 'filial.id', '=','procesos.id_sede_filial')
      ->join ('tipo_proceso', 'tipo_proceso.id', '=','procesos.id_tipo_proceso')
      ->join ('modalidad_proceso', 'modalidad_proceso.id', '=','procesos.id_modalidad_proceso')
      ->where($query_where)
      ->where(function ($query) use ($request) {
          return $query
              ->orWhere('procesos.nombre', 'LIKE', '%' . $request->term . '%')
              ->orWhere('filial.nombre', 'LIKE', '%' . $request->term . '%')
              ->orWhere('modalidad_proceso.nombre', 'LIKE', '%' . $request->term . '%')
              ->orWhere('procesos.anio', 'LIKE', '%' . $request->term . '%');
      })->orderBy('procesos.id', 'DESC')
      ->paginate(10);

    $this->response['estado'] = true;
    $this->response['datos'] = $res;
    return response()->json($this->response, 200);
  }

  public function saveProceso(Request $request ) {

        $proceso = null;
        if (!$request->id) {
            $proceso = Proceso::create([
                'nombre' => $request->nombre,
                'id_tipo_proceso' => $request->tipo,
                'id_modalidad_proceso' => $request->modalidad,
                'anio' => $request->anio,
                'estado' => $request->estado,
                'id_sede_filial' => $request->sede,
                'id_usuario' => auth()->id()
            ]);
            $this->response['titulo'] = 'REGISTRO NUEVO';
            $this->response['mensaje'] = 'Proceso '.$proceso->nombre.' creado con exito';
            $this->response['estado'] = true;
            $this->response['datos'] = $proceso;
        } else {

            $proceso = Proceso::find($request->id);
            $proceso->nombre = $request->nombre;
            $proceso->id_tipo_proceso = $request->tipo;
            $proceso->id_modalidad_proceso = $request->modalidad;
            $proceso->anio = $request->anio;
            $proceso->estado = $request->estado;
            $proceso->id_sede_filial = $request->sede;
            $proceso->id_usuario = auth()->id();
            $proceso->save();

            $this->response['titulo'] = '!REGISTRO MODIFICADO!';
            $this->response['mensaje'] = 'Proceso '.$proceso->nombre.' modificado con exito';
            $this->response['estado'] = true;
            $this->response['datos'] = $proceso;
        }

    return response()->json($this->response, 200);
  }

  public function deleteProceso($id){
    $proceso = Proceso::find($id);
    $p = $proceso;
    $proceso->delete();

    $this->response['titulo'] = '!REGISTRO ELIMINADO!';
    $this->response['mensaje'] = 'Proceso '.$p->nombre.' eliminado con exito';
    $this->response['estado'] = true;
    $this->response['datos'] = $p;
    return response()->json($this->response, 200);
  }


}
