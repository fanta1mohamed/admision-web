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

    public function biometrico(Request $request){

        $re = DB::select("SELECT
            postulante.id AS id_postulante,
            procesos.id AS id_proceso, procesos.nombre AS proceso,
            modalidad.id AS id_modalidad, modalidad.nombre AS modalidad,
            resultados.puesto, resultados.puntaje, resultados.programa AS programa,
            resultados.fecha,
            postulante.nro_doc AS dni, postulante.primer_apellido AS paterno,
            postulante.segundo_apellido AS materno, postulante.nombres
            FROM resultados
            JOIN postulante ON resultados.dni_postulante =  postulante.nro_doc
            JOIN modalidad ON resultados.modalidad = modalidad.id
            JOIN procesos ON resultados.id_proceso = procesos.id 
            WHERE resultados.apto = 'SI'
            AND resultados.dni_postulante = ".$request->dni."
            AND resultados.id_proceso = ". auth()->user()->id_proceso.";");

        $this->pdf($re[0]);
        $this->pdfbiometrico($re[0]);
        $this->UnirPDF($request->dni);

        $pdf = new Fpdi();
        
        $files = [
            public_path('/documentos/cepre2023-II/'.$request->dni.'/').'constancia-ingreso-1.pdf',
            public_path('/documentos/cepre2023-II/'.$request->dni.'/').'control-biometrico-1.pdf'
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

                $nuevoCodigo = '';

                $ultimoRegistro = Estudiante::orderBy('codigo', 'desc')->first();

                if ($ultimoRegistro) {
                    $ultimoCodigo = $ultimoRegistro->codigo;
                    $numero = (int) substr($ultimoCodigo, 2);
                    $nuevoCodigo = '23' . str_pad($numero + 1, 4, '0', STR_PAD_LEFT);
                } else {
                    $nuevoCodigo = '230001'; // Si no hay registros anteriores, iniciar con un valor predeterminado
                }

                $biometric = ControlBiometrico::create([
                    'id_proceso' => $re[0]->id_proceso,
                    'id_postulante' => $re[0]->id_postulante,
                    'codigo_ingreso' => $nuevoCodigo,
                    'estado' => 1,
                    'id_usuario' => auth()->id()
                ]);


                $estudiante = Estudiante::on('mysql_secondary')->create([
                    'dni' => $request->dni,
                    'codigo' => $nuevoCodigo
                ]);

                $avancePostulante = AvancePostulante::where('dni_postulante', $request->dni)->first();
                $avancePostulante->avance = 5;  
                $avancePostulante->save();
               
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
