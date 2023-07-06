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
    DB::beginTransaction();

    try {
    
            $proceso = 0;
            $p_name = 'cepre2023-II';
            if($request->modalidad == 9) { $proceso = 4; }
            if($request->modalidad == 8 || $request->modalidad == 7){ $proceso = 5; }

            $pre = Preinscripcion::create([
                'id_postulante'=> $request->id_postulante,
                'id_programa' => $request->programa,
                'id_proceso' => $proceso,
                'id_modalidad' => $request->modalidad,
                'estado' => 1,
                'codigo_seguridad' => date('Y')
            ]);

            $pre = AvancePostulante::create([
                'dni_postulante'=> $request->dni,
                'id_proceso' => $proceso,
                'avance' => 1,
            ]);

            try{
                if($request->hasFile('img')){
                    $file = $request->file('img');
                    $file_name =$file->getClientoriginalName();
                    $rutaCarpeta = public_path('/documentos/'.$p_name.'/'.$request->dni);
                    if (!File::exists($rutaCarpeta)) {
                        File::makeDirectory($rutaCarpeta, 0755, true, true);
                    }

                    $file->move(public_path('/documentos/'.$p_name.'/'.$request->dni), 'certificado-1.pdf');

                    //2023 
                    $doc = Documento::create([
                        'codigo' => $request->codigo_certificado,
                        'nombre' => $file_name,
                        'id_postulante' => $request->id_postulante,
                        'id_tipo_documento' => 1,
                        'estado' => 1,
                        'url' => 'documentos/'.$p_name.'/'.$request->dni.'/certificado-1.pdf',
                        'fecha' => date('Y-m-d'),
                        'observacion' => $request->tipo_certificado
                    ]);
                    return response()->json(['menssje'=>'file upload success'], 200);
                }
            }catch(\Exception $e){
                return response()->json([
                    'message'=>$e->getMessage()
                ]);
            }


        } 
        catch (\Exception $e) {
            // En caso de error, deshacer la transacción
            DB::rollBack();
        
            echo "Error en la transacción: " . $e->getMessage();
        }


        $this->pdfsolicitud($request->dni);

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

        $res = Preinscripcion::select(
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
          ->join ('ubigeo', 'ubigeo.ubigeo', '=','colegios.ubigeo')
          ->join ('distritos', 'distritos.id', '=','ubigeo.id_distrito')
          ->where('postulante.nro_doc','=', $dni)->get();

        $pos = DB::select('SELECT tipo_documento_identidad.nombre AS tipo_doc, postulante.direccion, distritos.nombre AS distrito_residencia FROM postulante
        JOIN ubigeo ON postulante.ubigeo_residencia = ubigeo.ubigeo
        JOIN distritos ON ubigeo.id_distrito = distritos.id
        JOIN tipo_documento_identidad ON tipo_documento_identidad.id = postulante.tipo_doc
        WHERE postulante.nro_doc = ' .$dni);

        $name = "cepre2023-II";
        // if($res[0]->modalidad == 8 || $res[0]->modalidad == 7 ){$name = "general2023-II"; }

        $data = $res[0];
        $dataP = $pos[0]; 
        setlocale(LC_TIME, 'es_ES.utf8'); // Establece la configuración regional en español
        // $date = strftime('%d de %B del %Y');
        $date = Carbon::now()->locale('es')->isoFormat('DD [de] MMMM [del] YYYY');
        //$date = date('d \d\e F \d\e\l Y');
        $pdf = Pdf::loadView('solicitud.solicitud', compact('data','date','dataP'));
        $pdf->setPaper('A4', 'portrait');
        $output = $pdf->output();


        $rutaCarpeta = public_path('/documentos/'.$name.'/'.$res[0]->dni);

        if (!File::exists($rutaCarpeta)) {
            File::makeDirectory($rutaCarpeta, 0755, true, true);
        }

        // $doc = Documento::create([
        //     'codigo' => '23-2-SOL-'.$res[0]->dni.'-1', 
        //     'nombre' => 'SOLICITUD DE POSTULACIÓN',
        //     'numero' => 1,
        //     'id_postulante' => $res[0]->idP,
        //     'id_tipo_documento' => 6,
        //     'estado' => 1,
        //     'url' => 'documentos/'.$name.'/'.$res[0]->dni.'/'.'solicitud-1.pdf',
        //     'fecha' => date('Y-m-d')
        // ]);

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
