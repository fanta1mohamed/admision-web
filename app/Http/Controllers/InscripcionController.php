<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Postulante;
use Illuminate\Support\Facades\DB;

class InscripcionController extends Controller
{
    public function index()
    {
        return Inertia::render('Inscripciones/impresion');
        
    }

    public function getPostulantes(Request $request)
    {
        $query_where = [];
        $res = Postulante::select( 
            'postulante.nro_doc as value', 
            DB::raw("CONCAT( postulante.nombres,' ',postulante.primer_apellido, postulante.primer_apellido) as label")
        )

            ->where($query_where)
            ->where(function ($query) use ($request) {
                return $query
                    ->orWhere('postulante.nro_doc', 'LIKE', '%' . $request->term . '%')
                    ->orWhere('postulante.nombres', 'LIKE', '%' . $request->term . '%')
                    ->orWhere('postulante.primer_apellido', 'LIKE', '%' . $request->term . '%');
            })->orderBy('postulante.id', 'DESC')
            ->paginate(10);

        $this->response['estado'] = true;
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);
    }

    public function getPostulanteByDni($dni){
        
        $res = DB::select('SELECT 
        postulante.id as id_postulante, postulante.nro_doc AS dni, postulante.nombres, postulante.primer_apellido, postulante.segundo_apellido, postulante.sexo, postulante.fec_nacimiento,
        programa.id AS id_programa, programa.nombre,
        colegios.id AS id_colegio, colegios.nombre AS colegio,
        modalidad.id AS id_modalidad, modalidad.nombre as modalidad
        FROM pre_inscripcion
        JOIN postulante ON pre_inscripcion.id_postulante = postulante.id
        JOIN colegios ON postulante.id_colegio = colegios.id
        JOIN programa ON programa.id = pre_inscripcion.id_programa
        JOIN modalidad ON modalidad.id = pre_inscripcion.id_modalidad
        WHERE postulante.nro_doc = ' . $dni);
        
        $this->response['estado'] = true;
        $this->response['datos'] = $res[0];
        return response()->json($this->response, 200);
    }

    public function getApoderados($dni){
        $res = DB::select('SELECT 
        apoderados.nro_documento, apoderados.paterno, apoderados.materno, apoderados.nombres
        FROM apoderados
        JOIN postulante ON postulante.id = apoderados.id_postulante
        WHERE postulante.nro_doc = ' . $dni);
        
        $this->response['estado'] = true;
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);
    }

    public function getVouchers($dni){
        $res = DB::select('SELECT nro_operacion AS operacion, fecha, hora, codigo, monto FROM comprobantes
        WHERE estado = 1 AND ndoc_postulante = ' . $dni);
        
        $this->response['estado'] = true;
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);
    }

    public function getDocumentos($dni){
        $res = DB::select('SELECT * FROM documento
        WHERE ndoc_postulante = ' . $dni);
        
        $this->response['estado'] = true;
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);
    }

    public function getPreinscipciones($dni){
        $res = DB::select('SELECT 
        pre_inscripcion.id, pre_inscripcion.id_postulante, pre_inscripcion.id_programa,
        pre_inscripcion.id_proceso, pre_inscripcion.id_modalidad, pre_inscripcion.estado,
        pre_inscripcion.codigo_seguridad 
        FROM pre_inscripcion
        JOIN postulante ON postulante.id = pre_inscripcion.id_postulante
        WHERE postulante.nro_doc = ' . $dni);
        
        $this->response['estado'] = true;
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);
    }

    public function getInscripciones($dni){
        $res = DB::select('SELECT 
        inscripciones.id, inscripciones.id_postulante, inscripciones.id_programa,
        inscripciones.id_proceso, inscripciones.id_modalidad, inscripciones.estado
        FROM inscripciones
        JOIN postulante ON postulante.id = inscripciones.id_postulante
        WHERE postulante.nro_doc = ' . $dni);
        
        $this->response['estado'] = true;
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);
    }



}
