<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Administrativo;
use App\Models\Dataversion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdministrativoController extends Controller
{

    public function getAdministrativos(Request $request) {

        $query_where = [];

        // if ($request->programa) array_push($query_where,[DB::raw('pre_inscripcion.id_programa'), '=', $request->programa]); 
        // array_push($query_where,[DB::raw('pre_inscripcion.id_proceso'), '=', 5]);

        $res = Administrativo::select(
            'administrativos.id as id', 
            'administrativos.codigo as codigo',
            'administrativos.nro_doc AS dni',
            'administrativos.nombres AS nombres',
            'administrativos.paterno', 'administrativos.materno', 
            'administrativos.dependencia as dependencia',
            'modalidad_empleo.nombre as tipo_empleo',
            'administrativos.condicion',
            'administrativos.sexo',
            'administrativos.estado',
            'administrativos.observacion',
            'dependencia.nombre as dependencia_name'
        )
        ->join('modalidad_empleo','modalidad_empleo.id','administrativos.condicion')
        ->join('dependencia','dependencia.id','administrativos.dependencia')
//        ->where($query_where)
        ->where(function ($query) use ($request) {
            return $query
              ->orWhere('administrativos.nro_doc', 'LIKE', '%' . $request->term . '%')
              ->orWhere('administrativos.paterno', 'LIKE', '%' . $request->term . '%')
              ->orWhere('dependencia.nombre', 'LIKE', '%' . $request->term . '%')
              ->orWhere('modalidad_empleo.nombre', 'LIKE', '%' . $request->term . '%')
              ->orWhere('administrativos.materno', 'LIKE', '%' . $request->term . '%');
        })
        ->paginate($request->paginashoja);

        $this->response['estado'] = true;
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);

    }
    
    public function saveAdministrativo(Request $request ) {

        $fecha = null;
        if($request->fec_nac){ $fecha = substr($request->fec_nac,0,10);}

        $docente = null;
        if (!$request->id) {
            $validator = Validator::make($request->all(), Administrativo::$rules, Administrativo::$customMessages);
            if ($validator->fails()) { return response()->json(['errors' => $validator->errors()], 202); }

            $docente = Administrativo::create([
                'tipo_doc' => $request->tipo_doc,
                'nro_doc' => $request->nro_doc,
                'codigo' => $request->codigo,
                'nombres' => $request->nombres,
                'paterno' => $request->paterno,
                'materno' => $request->materno, 
                'sexo' => $request->sexo,
                'fec_nac' => $fecha,
                'condicion' => $request->condicion,
                'dependencia' => $request->dependencia,
                'estado' => $request->estado,
                'id_usuario' => auth()->id()
            ]);
            $this->response['titulo'] = 'REGISTRO NUEVO';
            $this->response['mensaje'] = 'DOCENTE '.$docente->nombres.' CREADO CON EXITO';
            $this->response['estado'] = true;
            $this->response['datos'] = $docente;
        } else {

            $docente = Administrativo::find($request->id);
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
            $docente->dependencia = $request->dependencia;
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
        $docente = Administrativo::find($request->id);
        $p = $docente;
        $docente->delete();
    
        $this->response['titulo'] = '!REGISTRO ELIMINADO!';
        $this->response['mensaje'] = 'DOCENTE '.$p->nombres.' ELIMINADO CON EXITO';
        $this->response['estado'] = true;
        $this->response['datos'] = $p;
        return response()->json($this->response, 200);
    }

    public function actualizarSexo(Request $request ) {

        $docente = Administrativo::find($request->id_postulante);
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
