<?php
namespace App\Http\Controllers\Segundas;
use App\Http\Controllers\Controller;
use App\Models\Preinscripcion;
use Illuminate\Http\Request;

class PreinscripcionSegundasController extends Controller
{
    
    public function getPreinscripciones(Request $request) {

        $query_where = [];
        if (auth()->user()->programas != null) {
            $array = json_decode(auth()->user()->programas, true);
            if (!empty($array)) {
                $query_where[] = ['pre_inscripcion.id_programa', $array];
            }
        }

        if ($request->programa) {
            $query_where[] = ['pre_inscripcion.id_programa', '=', $request->programa];
        }
        $query_where[] = ['pre_inscripcion.id_proceso', '=', auth()->user()->id_proceso];

        $res = Preinscripcion::select(
            'pre_inscripcion.id as id', 'postulante.id as id_postulante', 'postulante.nro_doc AS dni',
            'postulante.nombres AS nombres',
            'postulante.primer_apellido AS paterno', 'postulante.segundo_apellido AS materno',
            'programa.nombre as programa', 'pre_inscripcion.id_programa as id_programa',
            'modalidad.id as id_modalidad', 'modalidad.nombre as modalidad', 'procesos.nombre AS proceso',
            'pre_inscripcion.created_at as fecha', 'postulante.sexo',
            'inscripciones.estado',
            'pre_inscripcion.observacion'
        )
        ->join('postulante','pre_inscripcion.id_postulante', 'postulante.id')
        ->leftJoin('inscripciones', function($join) {
            $join->on('inscripciones.id_postulante', '=', 'postulante.id')
                ->where('inscripciones.id_proceso', '=', auth()->user()->id_proceso);
        })
        ->join('programa','pre_inscripcion.id_programa', 'programa.id')
        ->join('modalidad','pre_inscripcion.id_modalidad', 'modalidad.id')
        ->join('procesos','pre_inscripcion.id_proceso', 'procesos.id')
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
                ->orWhere('modalidad.nombre', 'LIKE', '%' . $request->term . '%')
                ->orWhere('postulante.nro_doc', 'LIKE', '%' . $request->term . '%')
                ->orWhere('postulante.nombres', 'LIKE', '%' . $request->term . '%')
                ->orWhere('postulante.primer_apellido', 'LIKE', '%' . $request->term . '%')
                ->orWhere('postulante.segundo_apellido', 'LIKE', '%' . $request->term . '%')
                ->orWhere('modalidad.nombre', 'LIKE', '%' . $request->term . '%');
        })
        ->paginate($request->paginashoja);

        $this->response['estado'] = true;
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);
    }

    public function Actualizar(Request $request){

        $preinscripcion = Preinscripcion::find($request->id);

        if( $preinscripcion->id_programa != $request->id_programa) {
            $preinscripcion->observacion = "$preinscripcion->observacion - Cambio de programa de $preinscripcion->id_programa a $request->id_programa ";
            $preinscripcion->id_programa = $request->id_programa;
        }
        if ( $preinscripcion->id_modalidad != $request->id_modalidad ) {
            $preinscripcion->observacion = "$preinscripcion->observacion, Cambio de modalidad de $preinscripcion->id_modalidad a $request->id_modalidad";
            $preinscripcion->id_modalidad = $request->id_modalidad;
        }
        if($request->observacion != ''){
            $preinscripcion->observacion = "$preinscripcion->observacion, ( $request->observacion )";
        }
        $preinscripcion->save();
        // $this->pdfsolicitud(auth()->user()->id_proceso,$request->dni);

        $this->response['titulo'] = '!REGISTRO ACTUALIZADO!';
        $this->response['mensaje'] = '';
        $this->response['estado'] = true;
        return response()->json($this->response, 200);
        
    }

    


}
