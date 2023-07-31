<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proceso;
use App\Models\TipoProceso;
use App\Models\ControlBiometrico;
use App\Models\Preinscripcion;
use App\Models\Documento;
use App\Models\Estudiante;
use App\Models\AvancePostulante;
use App\Models\Paso;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\File;
use setasign\Fpdi\Fpdi;

class IngresoController extends Controller
{

    public function getDatosIngreso($dni){
        $res = DB::select("SELECT  
            postulante.id,
            postulante.nro_doc,
            postulante.nombres,
            postulante.primer_apellido,
            postulante.segundo_apellido,
            programa.nombre AS programa,
            procesos.nombre AS proceso,
            resultados.puntaje,
            resultados.puesto,
            resultados.puesto_general,
            resultados.fecha
            FROM resultados
            JOIN postulante ON postulante.nro_doc = resultados.dni_postulante
            JOIN inscripciones ON postulante.id = inscripciones.id_postulante
            JOIN programa ON programa.id = inscripciones.id_programa
            JOIN procesos ON procesos.id = inscripciones.id_proceso
            WHERE postulante.nro_doc = ".$dni);

        $this->response['estado'] = true;
        $this->response['datos'] = $res[0];
        return response()->json($this->response, 200);
    }

    public function biometrico(Request $request){

        $re = DB::select("SELECT
            procesos.anio, procesos.ciclo_oti,
            programa.programa_oti,
            postulante.primer_apellido AS paterno,
            postulante.segundo_apellido AS materno, postulante.nombres,
            tipo_documento_identidad.documento_oti AS tipo_doc_oti,
            postulante.nro_doc AS dni,
            users.name, users.paterno as upaterno,
            postulante.fec_nacimiento,
            postulante.sexo,
            postulante.ubigeo_residencia,
            postulante.direccion,
            postulante.estado_civil,
            resultados.fecha,
            postulante.email,
            postulante.celular,
            programa.cod_esp,
            modalidad.modalidad_oti,
            resultados.puntaje,
            resultados.puesto,
            resultados.puesto_general,
            postulante.id AS id_postulante,
            procesos.id AS id_proceso, procesos.nombre AS proceso,
            modalidad.id AS id_modalidad, modalidad.nombre AS modalidad,
            programa.nombre AS programa
            FROM resultados
            LEFT JOIN postulante ON resultados.dni_postulante =  postulante.nro_doc
            LEFT JOIN inscripciones ON inscripciones.id_postulante = postulante.id
            LEFT JOIN modalidad ON inscripciones.id_modalidad = modalidad.id
            LEFT JOIN procesos ON resultados.id_proceso = procesos.id 
            left join users on users.id = inscripciones.id_usuario
            LEFT JOIN programa ON programa.id = inscripciones.id_programa
            LEFT JOIN tipo_documento_identidad ON postulante.tipo_doc = tipo_documento_identidad.id
            WHERE resultados.apto = 'SI'
            AND resultados.dni_postulante = ".$request->dni." AND resultados.id_proceso = ". auth()->user()->id_proceso.";");

            $this->pdf($re[0]);
            //$this->pdfbiometrico($re[0]);
//          $this->UnirPDF($request->dni);

            $pdf = new Fpdi();
            
            $files = [
                public_path('/documentos/cepre2023-II/'.$request->dni.'/').'constancia-ingreso-1.pdf',
//                public_path('/documentos/cepre2023-II/'.$request->dni.'/').'control-biometrico-1.pdf'
            ];

            foreach ($files as $file) {
                $pageCount = $pdf->setSourceFile($file);
                for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                    $template = $pdf->importPage($pageNo);
                    $pdf->AddPage();
                    $pdf->useTemplate($template);
                }
            }

            $outputFilePath = public_path('/documentos/cepre2023-II'.'/'.$request->dni.'/control-biometrico-unido.pdf');
            $pdf->Output($outputFilePath, 'F');

            try {
                DB::transaction(function () use ($request, $re) {

                    $database2 = 'mysql_secondary';
                    $rs = DB::connection($database2)->select("SELECT CONCAT('23', (max(right(e.num_mat,LENGTH(TRIM(e.num_mat))-2)+0) + 1)) AS siguiente FROM unapnet.estudiante e WHERE left(e.num_mat,2) = '23' ;");
                    $nuevoCodigo = $rs[0]->siguiente;

                    $biometric = ControlBiometrico::create([
                        'id_proceso' => $re[0]->id_proceso,
                        'id_postulante' => $re[0]->id_postulante,
                        'codigo_ingreso' => $nuevoCodigo,
                        'estado' => 1,
                        'id_usuario' => auth()->id()
                    ]);

                    $e_civil = 1;
                    if($re[0]->estado_civil == 1 ) { $e_civil = 2;}
                    if($re[0]->estado_civil == 2 ) { $e_civil = 1;}
                    if($re[0]->estado_civil == 3 ) { $e_civil = 3;}
                    if($re[0]->estado_civil == 4 ) { $e_civil = 6;}

                    $estudiante = Estudiante::on('mysql_secondary')->create([
                        'num_mat' => $nuevoCodigo,
                        'cod_car' => $re[0]->programa_oti,
                        'paterno' => $re[0]->paterno, 
                        'materno' => $re[0]->materno,
                        'nombres' => $re[0]->nombres,
                        'tip_doc' => $re[0]->tipo_doc_oti,
                        'num_doc' => $re[0]->dni,
                        'fch_nac' => $re[0]->fec_nacimiento,
                        'sexo' => $re[0]->sexo,
                        'ubigeo' => $re[0]->ubigeo_residencia,
                        'mod_ing' => $re[0]->modalidad_oti,
                        'est_civ' => $e_civil,
                        'fch_ing' => $re[0]->fecha,
                        'direc' => $re[0]->direccion,
                        'email' => $re[0]->email,
                        'con_est' => 5,
                        'celular' => $re[0]->celular,
                        'cod_esp' => $re[0]->cod_esp,
                        'puntaje' => $re[0]->puntaje,
                        'puesto_escuela' => $re[0]->puesto,
                        'puesto_general' => $re[0]->puesto_general,
                        'ano_ing' => $re[0]->anio,
                        'per_ing' => $re[0]->ciclo_oti

                    ]);

                    // $avancePostulante->avance = 5;  
                    // $avancePostulante = AvancePostulante::where('dni_postulante', $request->dni)->first();
                    // $avancePostulante->save();
                
                });
            } catch (\Exception $e) {
                $errorMessage = $e->getMessage();
                // Registrar el error en un archivo de registro
                \Log::error('Error en la transacción: ' . $errorMessage);
                // Devolver una respuesta de error al usuario con el mensaje de error
                return response()->json(['error' => 'Ocurrió un error en la transacción: ' . $errorMessage], 500);
        
        
            }
        


        $this->response['estado'] = true;
        $this->response['datos'] = $request->dni;
        return response()->json($this->response, 200);
        // return response()->download($outputFilePath);
        // return response()->download($outputFilePath)->deleteFileAfterSend();
    } 

    public function pdf($datos){
        setlocale(LC_TIME, 'es_ES.utf8'); 
        $date = Carbon::now()->locale('es')->isoFormat('DD [de] MMMM [del] YYYY');
        $dateI = Carbon::createFromFormat('Y-m-d', $datos->fecha)->locale('es')->isoFormat('DD [de] MMMM [del] YYYY');
        #$dateI = "26 de Junio del 2034";
        $data = $datos;
        $pdf = Pdf::loadView('ingreso.constancia', compact('data','date','dateI'));
        $pdf->setPaper('A4', 'portrait');
        $output = $pdf->output();
        $rutaCarpeta = public_path('/documentos/cepre2023-II/'.$data->dni);
        if (!File::exists($rutaCarpeta)) { File::makeDirectory($rutaCarpeta, 0755, true, true); }
        file_put_contents(public_path('/documentos/cepre2023-II/'.$data->dni.'/').'constancia-ingreso-1.pdf', $output);
        return $pdf->stream();
    }

    public function pdfbiometrico($datos){

        $data = $datos->dni;
        $pdf = Pdf::loadView('ingreso.datosbiometricos', compact('data'));
        $pdf->setPaper('A4', 'portrait');
        $output = $pdf->output();
        $rutaCarpeta = public_path('/documentos/cepre2023-II/'.$datos->dni);
        if (!File::exists($rutaCarpeta)) {
            File::makeDirectory($rutaCarpeta, 0755, true, true);
        }
        file_put_contents(public_path('/documentos/cepre2023-II/'.$datos->dni.'/').'control-biometrico-1.pdf', $output);
        return $pdf->stream();
    }


    public function pdfbiometrico2($dni){

        $datos = DB::select(
            "SELECT
            procesos.nombre as proceso,
            postulante.primer_apellido AS paterno,
            postulante.segundo_apellido AS materno, postulante.nombres,
            tipo_documento_identidad.nombre,
            postulante.nro_doc AS dni,
            users.name, users.paterno as upaterno,
            modalidad.nombre as modalidad,
            resultados.fecha,
            resultados.puntaje,
            resultados.puesto,
            resultados.puesto_general,
            programa.nombre AS programa
            FROM resultados
            LEFT JOIN postulante ON resultados.dni_postulante =  postulante.nro_doc
            LEFT JOIN inscripciones ON inscripciones.id_postulante = postulante.id
            LEFT JOIN modalidad ON inscripciones.id_modalidad = modalidad.id
            LEFT JOIN procesos ON resultados.id_proceso = procesos.id 
            left join users on users.id = inscripciones.id_usuario
            LEFT JOIN programa ON programa.id = inscripciones.id_programa
            LEFT JOIN tipo_documento_identidad ON postulante.tipo_doc = tipo_documento_identidad.id
            WHERE resultados.apto = 'SI'
            AND resultados.dni_postulante = " .$dni. " AND resultados.id_proceso = 4"
        );

        $data = $datos[0];
        $hinsI = public_path('fotos/huella/').$dni.'.jpg';
        $hinsD = public_path('fotos/huella/').$dni.'x.jpg';
        $hexaI = public_path('hexamencepre/').$dni.'.jpg';
        $hexaD = public_path('hexamencepre/').$dni.'x.jpg';
        $hbioI = public_path('hbiometricocepre/').$dni.'.jpg';
        $hbioD = public_path('hbiometricocepre/').$dni.'x.jpg';
        $fins = public_path('fotos/inscripcion/').$dni.'.jpg';
        $fbio = public_path('fotos/biometrico/').$dni.'.jpg';

        setlocale(LC_TIME, 'es_ES.utf8');
        $fecha = $data->fecha;
        $date = \Carbon\Carbon::createFromFormat('Y-m-d', $fecha)->locale('es')->isoFormat('DD [de] MMMM [del] YYYY');

        $fec_imp = '2023-07-27';
        $fimp = \Carbon\Carbon::createFromFormat('Y-m-d', $fec_imp)->locale('es')->isoFormat('DD [de] MMMM [del] YYYY');
//        $fimp =  Carbon::now()->locale('es')->isoFormat('DD [de] MMMM [del] YYYY');

        $pdf = Pdf::loadView('ingreso.datosbiometricos', compact('data','hinsI','hinsD','hexaI','hexaD','hbioI','hbioD','fins','fbio','date', 'fimp'));
        $pdf->setPaper('A4', 'portrait');
        //  $output = $pdf->output();
        // $rutaCarpeta = public_path('/documentos/cepre2023-II/'.$datos->dni);
        // if (!File::exists($rutaCarpeta)) {
        //     File::makeDirectory($rutaCarpeta, 0755, true, true);
        // }
        // file_put_contents(public_path('/documentos/cepre2023-II/'.$datos->dni.'/').'control-biometrico-1.pdf', $output);
        return $pdf->stream();
    } 

    public function UnirPDF($dni){

        $pdf = new Fpdi();
        
        $files = [
            public_path('/documentos/cepre2023-II/'.$dni.'/').'constancia-ingreso-1.pdf',
            public_path('/documentos/cepre2023-II/'.$dni.'/').'control-biometrico-1.pdf'
        ];

        foreach ($files as $file) {
            $pageCount = $pdf->setSourceFile($file);
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $template = $pdf->importPage($pageNo);
                $pdf->AddPage();
                $pdf->useTemplate($template);
            }
        }

        $outputFilePath = public_path('/documentos/cepre2023-II/'.'/'.$dni.'control-biometrico-unido.pdf');
        $pdf->Output($outputFilePath, 'F');

        return response()->download($outputFilePath);
        // return response()->download($outputFilePath)->deleteFileAfterSend();

    }





}
