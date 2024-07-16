<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Postulante;
use App\Models\Inscripcion;
use App\Models\Proceso;
use App\Models\AvancePostulante;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

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
        
        $sancionados = DB::table('sancionados')
            ->where('dni', $dni)
            ->where('id_proceso', auth()->user()->id_proceso)
            ->exists();

        if( $sancionados ){
            $this->response['estado'] = true;
            $this->response['mensaje'] = "El postulante está observado";
            return response()->json($this->response, 200);
        }
        else {
            $res = DB::select("SELECT 
            postulante.id as id_postulante, postulante.nro_doc AS dni, postulante.nombres, 
            postulante.primer_apellido, postulante.segundo_apellido, postulante.sexo, postulante.fec_nacimiento,
            programa.id AS id_programa, programa.nombre as programa, programa.codigo as cod_programa,
            colegios.id AS id_colegio, concat(colegios.gestion,".'" - "'.", colegios.nombre) AS colegio,
            colegios.id_gestion as id_gestion,
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
            WHERE postulante.nro_doc = $dni AND postulante.estado = 1
            AND pre_inscripcion.id_proceso = ". auth()->user()->id_proceso);
            if(count($res) > 0 ){
                $this->response['estado'] = true;
                $this->response['datos'] = $res[0];
                return response()->json($this->response, 200);
            }
            else{
                $this->response['estado'] = true;
                return response()->json($this->response, 200);
            }

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
        WHERE documento.id_tipo_documento IN (1,4)
        AND documento.codigo IS NOT null
        AND postulante.nro_doc  = ' . $dni);
        $this->response['estado'] = true;
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);
    }

    public function getPreinscipciones($dni){

        $res = DB::select("SELECT 
        pre_inscripcion.estado AS estado,
        programa.nombre AS programa, 
        procesos.nombre AS proceso,
        modalidad.nombre AS modalidad
        FROM pre_inscripcion
        JOIN programa ON programa.id = pre_inscripcion.id_programa
        JOIN procesos ON procesos.id = pre_inscripcion.id_proceso AND procesos.id = ".auth()->user()->id_proceso ." 
        JOIN modalidad ON modalidad.id = pre_inscripcion.id_modalidad
        JOIN postulante ON postulante.id = pre_inscripcion.id_postulante    
        WHERE postulante.nro_doc =  $dni AND pre_inscripcion.id_proceso = ". auth()->user()->id_proceso );
        
        $this->response['estado'] = true;
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);
    }

    public function getInscripciones($dni){
        
        $res = DB::select("SELECT 
        inscripciones.estado AS estado,
        programa.codigo as cod_programa,
        programa.nombre AS programa, 
        procesos.nombre AS proceso,
        modalidad.nombre AS modalidad
        FROM inscripciones
        JOIN programa ON programa.id = inscripciones.id_programa
        JOIN procesos ON procesos.id = inscripciones.id_proceso
        JOIN modalidad ON modalidad.id = inscripciones.id_modalidad
        JOIN postulante ON postulante.id = inscripciones.id_postulante    
        WHERE postulante.nro_doc = $dni AND inscripciones.id_proceso = ". auth()->user()->id_proceso);
        
        $this->response['estado'] = true;
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);
    }

    public function Inscribir(Request $request){
     
        $prog = $request['postulante']['cod_programa'];

        $res = $siguiente = Inscripcion::where('codigo', 'like', 'G224'.$prog.'%')
        ->max(\DB::raw('CAST(SUBSTRING(codigo, 7) AS UNSIGNED)')) + 1;
        $res = str_pad($res, 4, '0', STR_PAD_LEFT);

        $inscripcion = Inscripcion::create([
            'codigo' => 'G224' . $prog . $res,
            'id_postulante'=> $request['postulante']['id'],
            'id_proceso'=> auth()->user()->id_proceso,
            'id_programa' => $request['postulante']['id_programa'],
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


    public function Actualizar(Request $request){
     
        $inscripcion = Inscripcion::find($request->id);

        if( $inscripcion->id_programa != $request->id_programa) {
            $inscripcion->estado = 3;
            $inscripcion->observaciones = "Cambio de programa a $request->id_programa";
            $inscripcion->save();

            $id_programa = str_pad($request->id_programa, 2, '0', STR_PAD_LEFT);
            $res = $siguiente = Inscripcion::where('codigo', 'like', 'G224'.$id_programa.'%')
            ->max(\DB::raw('CAST(SUBSTRING(codigo, 7) AS UNSIGNED)')) + 1;
            $res = str_pad($res, 4, '0', STR_PAD_LEFT);


            $inscripcion = Inscripcion::create([
                'codigo' => 'G224' . $request->id_programa . $res,
                'id_postulante'=> $request->id_postulante,
                'id_proceso'=> auth()->user()->id_proceso,
                'id_programa' => $request->id_programa,
                'id_modalidad' => $request->id_modalidad, 
                'estado' => 0,
                'id_usuario' => auth()->id()
            ]);

        }
        if ( $inscripcion->id_modalidad != $request->id_modalidad ) {
            $inscripcion->id_modalidad = $request->id_modalidad;
            $inscripcion->observaciones = "$inscripcion->observaciones Cambio de modalidad a $request->id_modalidad";
            $inscripcion->save();
        }

        $this->pdfInscripcion($request->dni);

        $this->response['titulo'] = 'REGISTRO MODIFICADO';
        $this->response['mensaje'] = 'DATOS ACTUALIZADOS CORRECTAMENTE';
        $this->response['estado'] = true;
    }


    public function pdfInscripcion($dni) {

        $carreras_previas = DB::select("SELECT codigo, cod_car, nombre, condicion FROM carreras_previas
        WHERE dni_postulante = $dni");

        $datos = DB::select("SELECT 
        postulante.nro_doc AS dni, 
        inscripciones.codigo as codigo,
        postulante.nombres AS nombre, 
        postulante.primer_apellido AS paterno,
        postulante.segundo_apellido AS materno,
        programa.nombre AS programa,
        inscripciones.id_programa as id_programa,
        modalidad.nombre AS modalidad,
        procesos.nombre AS proceso,
        LPAD(codigo_sunedu,3,'0') AS cod_sunedu,
        inscripciones.created_at as fecha,
        users.name, users.paterno as upaterno
        FROM inscripciones
        JOIN postulante ON inscripciones.id_postulante = postulante.id
        JOIN programa ON inscripciones.id_programa = programa.id
        JOIN modalidad ON inscripciones.id_modalidad = modalidad.id 
        JOIN procesos ON inscripciones.id_proceso = procesos.id
        JOIN users on inscripciones.id_usuario = users.id
        WHERE postulante.nro_doc = $dni AND inscripciones.estado = 0 AND inscripciones.id_proceso = ". auth()->user()->id_proceso);


        $foto = public_path('/documentos/'.auth()->user()->id_proceso.'/inscripciones/fotos/' . $dni . '.jpg');
        $huellaIzquierda = public_path('/documentos/'.auth()->user()->id_proceso.'/inscripciones/huellas/' . $dni . '.jpg');
        $huellaDerecha = public_path('/documentos/'.auth()->user()->id_proceso.'/inscripciones/huellas/'. $dni . 'x.jpg');

        $data = $datos[0];
        $pdf = Pdf::loadView('inscripcion.inscripcion', compact('data','carreras_previas','foto','huellaIzquierda','huellaDerecha'));
        $pdf->setPaper('A4', 'portrait');
        $output = $pdf->output();

        $rutaCarpeta = public_path('/documentos/'.auth()->user()->id_proceso.'/inscripciones/constancias/');
        if (!File::exists($rutaCarpeta)) {
            File::makeDirectory($rutaCarpeta, 0755, true, true);
        }
        file_put_contents($rutaCarpeta . $dni . '.pdf', $output);
        return $pdf->stream();
        // return $pdf->download('constancia-inscripcion.pdf');

    }


    public function getInscripcionesAdmin(Request $request) {

        $query_where = [];

        if ($request->programa) array_push($query_where,[DB::raw('inscripciones.id_programa'), '=', $request->programa]); 
        array_push($query_where,[DB::raw('inscripciones.id_proceso'), '=', auth()->user()->id_proceso]);

        $res = Inscripcion::select(
            'inscripciones.id as id', 'postulante.id as id_postulante', 'postulante.nro_doc AS dni', 'inscripciones.codigo as codigo', 'postulante.nombres AS nombres', 
            'postulante.primer_apellido AS paterno', 'postulante.segundo_apellido AS materno', 'programa.nombre as programa', 'inscripciones.id_programa as id_programa',
            'modalidad.id as id_modalidad', 'modalidad.nombre as modalidad', 'procesos.nombre AS proceso', 'inscripciones.created_at as fecha', 'inscripciones.estado'
        )
        ->join('postulante','inscripciones.id_postulante', 'postulante.id')
        ->join('programa','inscripciones.id_programa', 'programa.id')
        ->join('modalidad','inscripciones.id_modalidad', 'modalidad.id')        
        ->join('procesos','inscripciones.id_proceso', 'procesos.id')
        ->where($query_where)
        ->where(function ($query) use ($request) {
            return $query
              ->orWhere('modalidad.nombre', 'LIKE', '%' . $request->term . '%')
              ->orWhere('postulante.nro_doc', 'LIKE', '%' . $request->term . '%')
              ->orWhere('postulante.nombres', 'LIKE', '%' . $request->term . '%')
              ->orWhere('postulante.primer_apellido', 'LIKE', '%' . $request->term . '%')
              ->orWhere('postulante.segundo_apellido', 'LIKE', '%' . $request->term . '%');
        })
        ->paginate($request->paginashoja);

        $this->response['estado'] = true;
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);

    }


}
