<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Postulante;
use App\Models\Inscripcion;
use App\Models\AvancePostulante;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

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
        postulante.id as id_postulante, postulante.nro_doc AS dni, postulante.nombres, 
        postulante.primer_apellido, postulante.segundo_apellido, postulante.sexo, postulante.fec_nacimiento,
        programa.id AS id_programa, programa.nombre as programa, programa.codigo as cod_programa,
        colegios.id AS id_colegio, colegios.nombre AS colegio,
        modalidad.id AS id_modalidad, modalidad.nombre as modalidad,
        procesos.nombre AS proceso, procesos.id as id_proceso,
        departamento.nombre AS departamento, provincia.nombre AS provincia, distritos.nombre AS distrito
        FROM pre_inscripcion
        JOIN postulante ON pre_inscripcion.id_postulante = postulante.id
        JOIN colegios ON postulante.id_colegio = colegios.id
        JOIN programa ON programa.id = pre_inscripcion.id_programa
        JOIN modalidad ON modalidad.id = pre_inscripcion.id_modalidad
        JOIN procesos ON procesos.id = pre_inscripcion.id_proceso
        JOIN ubigeo ON postulante.ubigeo_residencia = ubigeo.ubigeo
        JOIN departamento ON ubigeo.id_departamento = departamento.id
        JOIN provincia ON ubigeo.id_provincia = provincia.id
        JOIN distritos ON ubigeo.id_distrito = distritos.id
        WHERE postulante.nro_doc = ' . $dni.' AND postulante.estado = 1');
        if(count($res) > 0 ){
            $this->response['estado'] = true;
            $this->response['datos'] = $res[0];
            return response()->json($this->response, 200);
        }
        else{
            $this->response['estado'] = true;
            $this->response['datos'] = null;
            return response()->json($this->response, 200);
        }

    }

    public function getApoderados($dni){
        $res = DB::select('SELECT apoderado.nro_documento, apoderado.paterno, 
        apoderado.materno, apoderado.nombres, tipo_apoderado.nombre AS parentesco
        FROM apoderado
        JOIN postulante ON postulante.id = apoderado.id_postulante
        JOIN tipo_apoderado ON tipo_apoderado.id = apoderado.tipo_apoderado
        WHERE postulante.nro_doc = ' . $dni);
        
        $this->response['estado'] = true;
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);
    }

    public function getVouchers($dni){
        $res = DB::select('SELECT nro_operacion AS operacion, fecha, hora, codigo, monto FROM comprobante
        WHERE estado = 1 AND ndoc_postulante = ' . $dni);

        $this->response['estado'] = true;
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);
    }

    public function getDocumentos($dni){
        $res = DB::select('SELECT 
        documento.codigo, documento.nombre, 
        documento.url, documento.estado, documento.verificado, 
        tipo_documento.nombre  AS tipo  
        FROM documento
        left JOIN tipo_documento ON tipo_documento.id = documento.id_tipo_documento        
        JOIN postulante ON documento.id_postulante = postulante.id
        WHERE postulante.nro_doc  = ' . $dni);
        $this->response['estado'] = true;
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);
    }

    public function getPreinscipciones($dni){
        $res = DB::select('SELECT 
        pre_inscripcion.estado AS estado,
        programa.nombre AS programa, 
        procesos.nombre AS proceso,
        modalidad.nombre AS modalidad
        FROM pre_inscripcion
        JOIN programa ON programa.id = pre_inscripcion.id_programa
        JOIN procesos ON procesos.id = pre_inscripcion.id_proceso
        JOIN modalidad ON modalidad.id = pre_inscripcion.id_modalidad
        JOIN postulante ON postulante.id = pre_inscripcion.id_postulante    
        WHERE postulante.nro_doc = ' . $dni);
        
        $this->response['estado'] = true;
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);
    }

    public function getInscripciones($dni){
        
        $res = DB::select('SELECT 
        inscripciones.estado AS estado,
        programa.nombre AS programa, 
        procesos.nombre AS proceso,
        modalidad.nombre AS modalidad
        FROM inscripciones
        JOIN programa ON programa.id = inscripciones.id_programa
        JOIN procesos ON procesos.id = inscripciones.id_proceso
        JOIN modalidad ON modalidad.id = inscripciones.id_modalidad
        JOIN postulante ON postulante.id = inscripciones.id_postulante    
        WHERE postulante.nro_doc = ' . $dni);
        
        $this->response['estado'] = true;
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);
    }
     


    public function Inscribir(Request $request){
     
        $prog = $request['postulante']['cod_programa'];

        $res = DB::select("SELECT COALESCE(LPAD(MAX(CAST(SUBSTRING(codigo, 6) AS UNSIGNED)) + 1, 4, '0'), '0001') AS siguiente
        FROM inscripciones
        WHERE codigo LIKE '23".$prog."%'");

        $inscripcion = Inscripcion::create([
            'codigo'=>'23'.$request['postulante']['cod_programa'].$res[0]->siguiente,
            'id_postulante'=> $request['postulante']['id'],
            'id_proceso'=> $request['postulante']['id_proceso'],
            'id_programa' => $request['postulante']['id_programa'],
            'id_proceso' => $request['postulante']['id_proceso'],
            'id_modalidad' => $request['postulante']['id_modalidad'],
            'estado' => 0,
            'id_usuario' => auth()->id() 
        ]);

        // $avancePostulante = AvancePostulante::where('dni_postulante', $request['postulante']['dni_temp'])->first();
        // $avancePostulante->avance = 3;
        // $avancePostulante->save();

        $this->pdfInscripcion($request['postulante']['dni_temp']);

    
        $this->response['estado'] = true;
        $this->response['datos'] = $request['postulante']['dni_temp'];
        return response()->json($this->response, 200);

        // return redirect('http://admision-web.test/admin/pdf-inscripción/70757838');
         
    }


    public function pdfInscripcion($dni) {

        $datos = DB::select('SELECT 
        postulante.nro_doc AS dni, 
        inscripciones.codigo as codigo,
        postulante.nombres AS nombre, 
        postulante.primer_apellido AS paterno,
        postulante.segundo_apellido AS materno,
        programa.nombre AS programa,
        modalidad.nombre AS modalidad,
        procesos.nombre AS proceso
        FROM inscripciones
        JOIN postulante ON inscripciones.id_postulante = postulante.id
        JOIN programa ON inscripciones.id_programa = programa.id
        JOIN modalidad ON inscripciones.id_modalidad = modalidad.id 
        JOIN procesos ON inscripciones.id_proceso = procesos.id
        WHERE postulante.nro_doc = '.$dni.';');        
        $data = $datos[0];
        $pdf = Pdf::loadView('inscripcion.inscripcion', compact('data'));
        $pdf->setPaper('A4', 'portrait');
        $output = $pdf->output();

        $rutaCarpeta = public_path('/documentos/cepre2023-II/'.$dni);
        file_put_contents(public_path('/documentos/cepre2023-II/'.$dni.'/').'inscripcion-1.pdf', $output);

        return $pdf->download();

    }

}
