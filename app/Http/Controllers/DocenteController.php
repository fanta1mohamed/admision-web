<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Docente;
use App\Models\Dataversion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DocenteController extends Controller
{

    public function getDocentes(Request $request) {

        $query_where = [];

        // if ($request->programa) array_push($query_where,[DB::raw('pre_inscripcion.id_programa'), '=', $request->programa]); 
        // array_push($query_where,[DB::raw('pre_inscripcion.id_proceso'), '=', 5]);

        $res = Docente::select(
            'docentes.id as id', 
            'docentes.codigo as codigo',
            'docentes.nro_doc AS dni',
            'docentes.nombres AS nombres',
            'docentes.paterno', 'docentes.materno', 
            'docentes.escuela as escuela',
            'modalidad_empleo.nombre as tipo_empleo',
            'docentes.condicion',
            'docentes.sexo',
            'docentes.estado',
            'docentes.observacion',
            'escuela.nombre_corto as escuela_name'
        )
        ->join('modalidad_empleo','modalidad_empleo.id','docentes.condicion')
        ->join('escuela','escuela.id','docentes.escuela')
//        ->where($query_where)
        ->where(function ($query) use ($request) {
            return $query
              ->orWhere('docentes.nro_doc', 'LIKE', '%' . $request->term . '%')
              ->orWhere('docentes.paterno', 'LIKE', '%' . $request->term . '%')
              ->orWhere('docentes.escuela', 'LIKE', '%' . $request->term . '%')
              ->orWhere('escuela.nombre_corto', 'LIKE', '%' . $request->term . '%')
              ->orWhere('modalidad_empleo.nombre', 'LIKE', '%' . $request->term . '%')
              ->orWhere('docentes.materno', 'LIKE', '%' . $request->term . '%');
        })
        ->paginate($request->paginashoja);

        $this->response['estado'] = true;
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);

    }
    
    public function saveDocente(Request $request ) {

        $fecha = null;
        if($request->fec_nac){ $fecha = substr($request->fec_nac,0,10);}

        $docente = null;
        if (!$request->id) {
            $validator = Validator::make($request->all(), Docente::$rules, Docente::$customMessages);
            if ($validator->fails()) { return response()->json(['errors' => $validator->errors()], 202); }

            $docente = Docente::create([
                'tipo_doc' => $request->tipo_doc,
                'nro_doc' => $request->nro_doc,
                'codigo' => $request->codigo,
                'nombres' => $request->nombres,
                'paterno' => $request->paterno,
                'materno' => $request->materno, 
                'sexo' => $request->sexo,
                'fec_nac' => $fecha,
                'condicion' => $request->condicion,
                'escuela' => $request->escuela,
                'estado' => $request->estado,
                'id_usuario' => auth()->id()
            ]);
            $this->response['titulo'] = 'REGISTRO NUEVO';
            $this->response['mensaje'] = 'DOCENTE '.$docente->nombres.' CREADO CON EXITO';
            $this->response['estado'] = true;
            $this->response['datos'] = $docente;
        } else {

            $docente = Docente::find($request->id);
            Dataversion::create([
                'registro_id' => $docente->id,
                'tabla' => $docente->getTable(),
                'usuario_id' => auth()->id(),
                'fecha' => now(),
                'datos' => $docente->toJson()
            ]);
        
            // Actualiza las propiedades correctas en lugar de las inexistentes
            $docente->tipo_doc = $request->tipo_doc;
            $docente->nro_doc = $request->nro_doc;
            $docente->codigo = $request->codigo;
            $docente->nombres = $request->nombres;
            $docente->paterno = $request->paterno;
            $docente->materno = $request->materno;
            $docente->sexo = $request->sexo;
            $docente->fec_nac = $fecha;
            $docente->condicion = $request->condicion;
            $docente->escuela = $request->escuela;
            $docente->estado = $request->estado;
            $docente->observacion = $request->observacion;
            $docente->id_usuario = auth()->id();
            $docente->save();  
        
            $this->response['titulo'] = '¡REGISTRO MODIFICADO!';
            $this->response['mensaje'] = 'DOCENTE '.$docente->nombres.' modificado con exito';
            $this->response['estado'] = true;
            $this->response['datos'] = $docente;
        }                
        return response()->json($this->response, 200);
    }

    public function deleteDocente(Request $request){
        $docente = Docente::find($request->id);
        $p = $docente;
        $docente->delete();
    
        $this->response['titulo'] = '!REGISTRO ELIMINADO!';
        $this->response['mensaje'] = 'DOCENTE '.$p->nombres.' ELIMINADO CON EXITO';
        $this->response['estado'] = true;
        $this->response['datos'] = $p;
        return response()->json($this->response, 200);
    }

    public function actualizarSexo(Request $request ) {

        $docente = Docente::find($request->id_postulante);
        Dataversion::create([
            'registro_id' => $docente->id,
            'tabla' => $docente->getTable(),
            'usuario_id' => auth()->id(),
            'fecha' => now(),
            'datos' => $docente->toJson()
        ]);
        $docente->sexo = $request->sexo;
        $docente->save();

        $this->response['titulo'] = '!REGISTRO ACTUALIZADO!';
        $this->response['mensaje'] = '';
        $this->response['estado'] = true;
        return response()->json($this->response, 200);
    }



}
