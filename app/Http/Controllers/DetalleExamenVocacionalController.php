<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetalleExamenVocacional;
use App\Models\Paso;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\File;
use setasign\Fpdi\Fpdi;
use App\Models\Proceso;
use App\Models\TipoProceso;
use App\Models\Preinscripcion;
use App\Models\AvancePostulante;
use App\Models\Documento;

class DetalleExamenVocacionalController extends Controller
{

    public function saveVocacional(Request $request ) {

        foreach($request->respuestas as $respuesta){
            if($respuesta == null ){
                echo "salto";
            }
            else{
                $this->saveDetalleVocacional($request->id_examen, $respuesta['ideP'], $request->id_postulante, $respuesta['ide']); 
            }
        }
        $avancePostulante = AvancePostulante::where('dni_postulante', $request->dni)->first();
        $avancePostulante->avance = 2;
        $avancePostulante->save();

        $this->savePasos($request->name, $request->nro, $request->avance, $request->id_postulante, $request->proceso);
        $this->pdfvocacional($request->dni);


        $this->response['tipo'] = 'success';
        $this->response['titulo'] = 'PASO REGISTRADO';
        $this->response['estado'] = true;

        return response()->json($this->response, 200);

    }

    public function saveDetalleVocacional($id_examen, $id_pregunta, $id_postulante, $id_respuesta) {
    
        $detalle_vocacional = DetalleExamenVocacional::create([
            'id_examen_vocacional' => $id_examen,
            'id_pregunta' => $id_pregunta,
            'id_postulante' => $id_postulante,
            'id_respuesta' => $id_respuesta,
            'fecha' => date('Y-m-d'),
            'id_usuario'=> auth()->id()
        ]);    
    }

    public function saveRespuesta(Request $request) {
    
        $detalle_vocacional = DetalleExamenVocacional::create([
            'id_examen_vocacional' => $request->id_vocacional,
            'id_pregunta' => $request->pregunta,
            'nro' => $request->nro,
            'id_postulante' => $request->postulante,
            'id_respuesta' => $request->respuesta,
            'fecha' => date('Y-m-d')
        ]);    

        $this->response['tipo'] = 'success';
        $this->response['nro'] = $request->nro;
        $this->response['estado'] = true;
        return response()->json($this->response, 200);
    }

    private function savePasos($nom, $num, $avan, $pos, $pro) {

        $pasos = null;
        $pasos = Paso::create([
            'nombre' => $nom,
            'nro' => $num,
            'avance' => $avan, 
            'postulante' => $pos,
            'proceso' => $pro
        ]);

        
    }

    public function pdfvocacional($dni) {
     
        $res = Preinscripcion::select(
            'postulante.id as idP',
            'postulante.nro_doc', 'postulante.primer_apellido', 'postulante.segundo_apellido',
            'postulante.nombres', 'programa.nombre AS programa', 'procesos.nombre AS proceso')
        ->join ('postulante','postulante.id','pre_inscripcion.id_postulante')
        ->join ('procesos','procesos.id','pre_inscripcion.id_proceso')
        ->join ('programa','programa.id','pre_inscripcion.id_programa')
        ->where('postulante.nro_doc','=', $dni)
        ->get();
        
        $data = $res[0];
        $pdf = Pdf::loadView('vocacional.constanciavocacional', compact('data'));
        $pdf->setPaper('A4', 'portrait');
        $output = $pdf->output();


        $rutaCarpeta = public_path('/documentos/cepre2023-II/'.$res[0]->nro_doc);

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
            'url' => 'documentos/cepre2023-II/'.$res[0]->nro_doc.'/'.'constancia vocacional-1.pdf',
            'fecha' => date('Y-m-d')
        ]);

        file_put_contents(public_path('/documentos/cepre2023-II/'.$res[0]->nro_doc.'/').'constancia vocacional-1.pdf', $output);
        return $pdf->stream();
    }



  
}