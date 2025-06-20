<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CarrerasPrevias;

class CarrerasPreviasController extends Controller
{
    public function getCarrerasPrevias(Request $request)
    {
      $query_where = [];
     
     // array_push($query_where, ['filial.cod_dep', '=', 'provincia.cod_dep']);

      $res = CarrerasPrevias::select(
            'carreras_previas.id',
            'carreras_previas.codigo',
            'carreras_previas.cod_car',
            'carreras_previas.nombre as programa',
            'carreras_previas.fecha',
            'carreras_previas.condicion',
            'carreras_previas.dni_postulante',
            'postulante.primer_apellido as paterno',
            'postulante.segundo_apellido as materno',
            'postulante.nombres',
        )
        ->join('postulante','postulante.nro_doc','carreras_previas.dni_postulante')
        ->where($query_where)
        ->where(function ($query) use ($request) {
            return $query
                ->orWhere('carreras_previas.codigo', 'LIKE', '%' . $request->term . '%')
                ->orWhere('carreras_previas.nombre', 'LIKE', '%' . $request->term . '%')
                ->orWhere('carreras_previas.dni_postulante', 'LIKE', '%' . $request->term . '%');
        })->orderBy('carreras_previas.id', 'DESC')
        ->paginate(50);
  
      $this->response['estado'] = true;
      $this->response['datos'] = $res;
      return response()->json($this->response, 200);
    }


    public function save(Request $request ) {

        $programa = null;
        if (!$request->id) {
            $programa = CarrerasPrevias::create([
                'codigo' => $request->codigo,
                'cod_car' => $request->carrera['cod_car'],
                'nombre' => $request->carrera['nombre'],
                'condicion' => $request->condicion,
                'dni_postulante' => $request->dni_postulante
            ]);
            $this->response['titulo'] = 'REGISTRO NUEVO';
            $this->response['mensaje'] = 'Creada con exito';
            $this->response['estado'] = true;
            $this->response['datos'] = $programa;
        } else {

            $programa = Programa::find($request->id);
            Dataversion::create([ 'registro_id' => $programa->id, 'tabla' => $programa->getTable(),  'usuario_id' => auth()->id(), 'fecha' => now(), 'datos' => $programa->toJson() ]);
            $programa->nombre = $request->nombre;
            $programa->codigo = $request->codigo;
            $programa->estado = $request->estado;
            $programa->id_facultad = $request->id_facultad;
            $programa->area = $request->area;
            $programa->estado = $request->estado;
            $programa->id_usuario = auth()->id();
            $programa->save();

            $this->response['titulo'] = '!REGISTRO MODIFICADO!';
            $this->response['mensaje'] = 'Filial '.$programa->nombre.' modificado con exito';
            $this->response['estado'] = true;
            $this->response['datos'] = $programa;
        }

    return response()->json($this->response, 200);
  }


  public function delete($id){
    $programa = CarrerasPrevias::find($id);
    $programa->delete();

    $this->response['titulo'] = '!REGISTRO ELIMINADO!';
    $this->response['mensaje'] = 'Eliminado con exito';
    $this->response['estado'] = true;
    return response()->json($this->response, 200);
  }



}
