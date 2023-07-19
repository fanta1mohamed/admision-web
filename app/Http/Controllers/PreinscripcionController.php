<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proceso;
use App\Models\TipoProceso;
use App\Models\Preinscripcion;
use App\Models\Documento;
use App\Models\AvancePostulante;
use App\Models\Paso;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\File;
use setasign\Fpdi\Fpdi;

class PreinscripcionController extends Controller
{
  public function index()
  {
      return Inertia::render('Preinscripcion/index');        
  }

  public function getProcesos(Request $request)
  {   
    $proceso = 0;
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

  public function preinscribir(Request $request) 
  {
    try {
        DB::beginTransaction();
            
            $proceso = 0;
            if ($request->modalidad == 9) { $proceso = 4; } elseif ($request->modalidad == 8 || $request->modalidad == 7) { $proceso = 5; }

            $pre = Preinscripcion::create([
                'id_postulante'=> $request->id_postulante,
                'id_programa' => $request->programa,
                'id_proceso' => $proceso,
                'id_modalidad' => $request->modalidad,
                'estado' => 1
            ]);

            $doc = Documento::create([
                'codigo' => $request->codigo_certificado, 
                'nombre' => 'CERT. DE ESTUDIOS',
                'numero' => 1,
                'observacion' => $request->tipo_certificado,
                'id_postulante' => $request->id_postulante,
                'id_tipo_documento' => 1,
                'estado' => 1,
                'url' => '',
                'fecha' => date('Y-m-d')
            ]);

            $ava = AvancePostulante::create([
                'dni_postulante'=> $request->dni,
                'id_proceso' => $proceso,
                'avance' => 1,
            ]);

        
        DB::commit();
        return response()->json(['message' => 'Preinscripción exitosa'], 200);
    
    }
    catch (\Exception $e) {
        DB::rollBack();
    
        // Obtener información completa del error
        $errorMessage = $e->getMessage();
        $errorFile = $e->getFile();
        $errorLine = $e->getLine();
    
        // Crear una respuesta con los detalles del error
        $errorResponse = [
            'error' => $errorMessage,
            'file' => $errorFile,
            'line' => $errorLine
        ];
    
        // Devolver la respuesta con los detalles del error
        return response()->json($errorResponse, 500);
    }

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

  public function savePasos(Request $request) {
    $pasos = null;
    if (!$request->id) {
        $pasos = Paso::create([
            'nombre' => $request->nombre,
            'nro' => $request->nro,
            'avance' => $request->avance, 
            'anvance_general' => $request->avance_general,
            'postulante' => $request->postulante,
            'proceso' => $request->proceso,
        ]);

        $this->response['tipo'] = 'success';
        $this->response['titulo'] = 'PASO REGISTRADO';
        $this->response['mensaje'] = 'Proceso '.$pasos->nombre.' creado con exito';
        $this->response['estado'] = true;
        $this->response['datos'] = $pasos;
        
    } else {
          $pasos = Paso::find($request->id);
          $pasos->nombre = $request->nombre;
          $pasos->nro = $request->nro;
          $pasos->avance = $request->avance; 
          $pasos->avance_general = $request->avance_general;
          $pasos->postulante = $request->postulante;
          $pasos->proceso = $request->proceso;
          $pasos->save();
            $this->response['tipo'] = 'info';
            $this->response['titulo'] = 'PASO ACTUALIZADO';
            $this->response['mensaje'] = 'Datos del '.$pasos->nombre.' actualizados';
            $this->response['estado'] = true;
            $this->response['datos'] = $pasos;
          }
    
    }

    public function pdf(){

        $data = "";
        $pdf = Pdf::loadView('preinscripcion.pdf', compact('data'));
        
        return $pdf->stream();
        
    }

    public function pdfvocacional($dni) {
        $res = Preinscripcion::select(
            'postulante.id as idP',
            'postulante.nro_doc', 'postulante.primer_apellido', 'postulante.segundo_apellido',
            'postulante.nombres', 'programa.nombre AS programa', 'procesos.nombre AS proceso',
            'pre_inscripcion.id_proceso as id_proceso'
            )
        ->join ('postulante','postulante.id','pre_inscripcion.id_postulante')
        ->join ('procesos','procesos.id','pre_inscripcion.id_proceso')
        ->join ('programa','programa.id','pre_inscripcion.id_programa')
        ->where('postulante.nro_doc','=', $dni)
        ->get();
        
        $name = "cepre2023-II";
        // if($res[0]->id_proceso == 5 ){ $name = "general2023-II"; }
        $data = $res[0];
        $pdf = Pdf::loadView('vocacional.constanciavocacional', compact('data'));
        $pdf->setPaper('A4', 'portrait');
        $output = $pdf->output();


        $rutaCarpeta = public_path('/documentos/'.$name.'/'.$res[0]->nro_doc);

        if (!File::exists($rutaCarpeta)) {
            File::makeDirectory($rutaCarpeta, 0755, true, true);
        }

        $doc = Documento::create([
            'codigo' => '23-2-VOC-'.$res[0]->nro_doc.'-1', 
            'nombre' => 'CONSTANCIA VOCACIONAL',
            'numero' => 1,
            'id_postulante' => $res[0]->idP,
            'id_tipo_documento' => 7,
            'estado' => 1,
            'url' => 'documentos/'.$name.'/'.$res[0]->nro_doc.'/'.'constancia vocacional-1.pdf',
            'fecha' => date('Y-m-d')
        ]);

        file_put_contents(public_path('/documentos/'.$name.'/'.$res[0]->nro_doc.'/').'constancia vocacional-1.pdf', $output);
        return $pdf->stream();
    }

    public function pdfsolicitud($dni) {

        $preinscrito = DB::select("SELECT COUNT(*) AS cont FROM pre_inscripcion
        JOIN postulante ON postulante.id = pre_inscripcion.id_postulante
        WHERE postulante.nro_doc = ".$dni);
        
        $res = Preinscripcion::select(
            'tipo_documento_identidad.nombre AS tipo_doc',
            'postulante.direccion', 
            'postulante.id as idP',
            'postulante.nro_doc as dni', 
            'postulante.nombres', 'postulante.primer_apellido', 'postulante.segundo_apellido',
            'postulante.anio_egreso AS egreso',
            'colegios.nombre AS colegio',
            'modalidad.nombre as modalidad',
            'distritos.nombre AS distrito',
            'procesos.id as id_proceso',
            'procesos.nombre AS proceso',
            'programa.nombre AS programa' 
        )
          ->leftjoin ('postulante', 'postulante.id', '=','pre_inscripcion.id_postulante')
          ->join ('procesos', 'procesos.id', '=','pre_inscripcion.id_proceso')
          ->join ('programa', 'programa.id', '=','pre_inscripcion.id_programa')
          ->join ('modalidad', 'modalidad.id', '=','pre_inscripcion.id_modalidad')
          ->join ('colegios', 'colegios.id', '=','postulante.id_colegio')
          ->join ('ubigeo', 'ubigeo.ubigeo', '=','postulante.ubigeo_residencia')
          ->join ('distritos', 'distritos.id', '=','ubigeo.id_distrito')
          ->join ('tipo_documento_identidad','tipo_documento_identidad.id', '=', 'postulante.tipo_doc')
          ->where('postulante.nro_doc','=', $dni)->get();

        $name = "cepre2023-II";
//        $name = "general2023-II";

        $data = $res[0];
        setlocale(LC_TIME, 'es_ES.utf8'); 
        $date = Carbon::now()->locale('es')->isoFormat('DD [de] MMMM [del] YYYY');
        $pdf = Pdf::loadView('solicitud.solicitud', compact('data','date'));
        $pdf->setPaper('A4', 'portrait');
        $output = $pdf->output();
    
        $rutaCarpeta = public_path('/documentos/'.$name.'/'.$res[0]->dni);

        if (!File::exists($rutaCarpeta)) {
            File::makeDirectory($rutaCarpeta, 0755, true, true);
        }

        if($preinscrito[0]->cont == 0){

            $doc = Documento::create([
                'codigo' => '23-2-SOL-'.$res[0]->dni.'-1', 
                'nombre' => 'SOLICITUD DE POSTULACIÓN',
                'numero' => 1,
                'id_postulante' => $res[0]->idP,
                'id_tipo_documento' => 6,
                'estado' => 1,
                'url' => 'documentos/'.$name.'/'.$res[0]->dni.'/'.'solicitud-1.pdf',
                'fecha' => date('Y-m-d')
            ]);
        }

        file_put_contents(public_path('/documentos/'.$name.'/'.$res[0]->dni.'/').'solicitud-1.pdf', $output);
        return $pdf->download();
        
    }

    public function UnirPDF($dni){
        
        $pdf = new Fpdi();
        
        $files = [
            public_path('/documentos/cepre2023-II/'.$dni.'/').'solicitud-1.pdf',
        ];

        foreach ($files as $file) {
            $pageCount = $pdf->setSourceFile($file);
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $template = $pdf->importPage($pageNo);
                $pdf->AddPage();
                $pdf->useTemplate($template);
            }
        }

        $outputFilePath = public_path('/documentos'.'/'.$dni.'.pdf');
        $pdf->Output($outputFilePath, 'F');

        return response()->download($outputFilePath);
        // return response()->download($outputFilePath)->deleteFileAfterSend();

    }

}
