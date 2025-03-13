<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ControlBiometrico;
use App\Models\Estudiante;
use App\Models\CarrerasPrevias;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Models\RegistroEstudiante;
use App\Models\Postulante;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class IngresoController extends Controller {

    public function getDatosIngreso($dni){
        $res = DB::select("SELECT postulante.id,
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
            WHERE postulante.nro_doc = $dni");

        $this->response['estado'] = true;
        $this->response['datos'] = $res[0];
        return response()->json($this->response, 200);
    }

    public function getDatosIngresoGeneral($dni){
        $res = DB::select("SELECT
            postulante.id as id,
            postulante.nro_doc,
            postulante.nombres,
            postulante.primer_apellido,
            postulante.segundo_apellido,
            postulante.tipo_doc,
            postulante.sexo, 
            postulante.fec_nacimiento,
            programa.nombre AS programa,
            procesos.nombre AS proceso,
            modalidad.nombre AS modalidad,
            resultados.puntaje,
            resultados.puesto,
            resultados.puesto_general,
            resultados.fecha as fecha
        FROM resultados
        JOIN postulante ON postulante.nro_doc = resultados.dni_postulante
        JOIN inscripciones ON postulante.id = inscripciones.id_postulante
        JOIN programa ON programa.id = inscripciones.id_programa
        JOIN modalidad ON modalidad.id = inscripciones.id_modalidad
        JOIN procesos ON procesos.id = inscripciones.id_proceso AND resultados.id_proceso = inscripciones.id_proceso
        WHERE inscripciones.id_proceso = ".auth()->user()->id_proceso."
        AND postulante.nro_doc = $dni ");

        $fotoUrl = url("/documentos/" . auth()->user()->id_proceso . "/control_biometrico/fotos/" . $dni . ".jpg") . '?v=' . time();
        $huellaDerecha = url("/documentos/" . auth()->user()->id_proceso . "/control_biometrico/huellas/" . $dni . ".jpg") . '?v=' . time();
        $huellaIzquierda = url("/documentos/" . auth()->user()->id_proceso . "/control_biometrico/huellas/" . $dni . "x.jpg") . '?v=' . time();

        $doc_dni = url("/documentos/" . auth()->user()->id_proceso . "/biometrico/dnis/" . $dni . ".pdf") . '?v=' . time();
        $doc_certificado = url("/documentos/" . auth()->user()->id_proceso . "/biometrico/certificados/" . $dni . ".pdf") . '?v=' . time();

        $this->response['estado'] = true;
        $this->response['foto'] = $fotoUrl;
        $this->response['hDerecha'] = $huellaDerecha;
        $this->response['hIzquierda'] = $huellaIzquierda;
        $this->response['doc_dni'] = $doc_dni;
        $this->response['doc_certificado'] = $doc_certificado;
        $this->response['datos'] = $res[0];
        return response()->json($this->response, 200);
    }

    public function biometrico(Request $request){

        $re = DB::select("SELECT procesos.anio, procesos.ciclo_oti, programa.programa_oti,
        postulante.primer_apellido AS paterno, postulante.segundo_apellido AS materno, postulante.nombres, 
        tipo_documento_identidad.documento_oti AS tipo_doc_oti, postulante.nro_doc AS dni, users.name, 
        users.paterno as upaterno, postulante.fec_nacimiento, postulante.sexo, postulante.ubigeo_residencia,
        postulante.direccion, postulante.estado_civil, resultados.fecha, postulante.email,
        postulante.celular, programa.cod_esp, modalidad.modalidad_oti, resultados.puntaje, resultados.puesto,
        resultados.puesto_general, postulante.id AS id_postulante, procesos.id AS id_proceso, procesos.nombre AS proceso, modalidad.id AS id_modalidad, modalidad.nombre AS modalidad,
        programa.nombre AS programa, programa.id as id_programa
        FROM resultados 
        JOIN postulante ON resultados.dni_postulante = postulante.nro_doc
        JOIN inscripciones ON inscripciones.id_postulante = postulante.id
        JOIN modalidad ON inscripciones.id_modalidad = modalidad.id
        JOIN procesos ON resultados.id_proceso = procesos.id
        LEFT JOIN users ON users.id = inscripciones.id_usuario
        JOIN programa ON programa.id = inscripciones.id_programa
        JOIN tipo_documento_identidad ON postulante.tipo_doc = tipo_documento_identidad.id
        WHERE resultados.apto = 'SI' 
        AND inscripciones.estado = 0
        AND resultados.dni_postulante = ".$request->dni."
        AND resultados.id_proceso = ".auth()->user()->id_proceso."
        AND inscripciones.id_proceso = ".auth()->user()->id_proceso.";");


        try {
            DB::transaction(function () use ($request, $re) {

                $ingreso = 1; $i_admision = 0;
                if($request->n_carrera == 1 ){ $ingreso = 2; $i_admision = 1; }

                $prefijo = $re[0]->id_programa == '38' ? '25' : '25';
            
                $database2 = 'mysql_secondary';
                $rs = DB::connection($database2)->select("SELECT CONCAT('$prefijo', LPAD(IFNULL(MAX(CAST(SUBSTRING(e.num_mat, 3) AS UNSIGNED)) + 1, 1), 4, '0')) AS siguiente 
                FROM unapnet.estudiante e 
                WHERE LEFT(e.num_mat, 2) = '$prefijo';");

                $nuevoCodigo = $rs[0]->siguiente;

                $biometric = ControlBiometrico::create([
                    'id_proceso' => auth()->user()->id_proceso,
                    'id_postulante' => $re[0]->id_postulante,
                    'codigo_ingreso' => $nuevoCodigo,
                    'estado' => 1,
                    'segunda_carrera' => $i_admision,
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
                    'num_car' => $ingreso,
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

                $this->pdfbiometrico2($request->dni);

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

    public function registrar_biometrico($dni)
    {
        try {

            $postulante = Postulante::select([
                'procesos.anio', 'procesos.ciclo_oti',
                'programa.programa_oti',
                'postulante.primer_apellido AS paterno',
                'postulante.segundo_apellido AS materno', 'postulante.nombres',
                'tipo_documento_identidad.documento_oti AS tipo_doc_oti',
                'postulante.nro_doc AS dni',
                'users.name', 'users.paterno as upaterno',
                'postulante.fec_nacimiento',
                'postulante.sexo',
                'postulante.ubigeo_residencia',
                'postulante.direccion',
                'postulante.estado_civil',
                'resultados.fecha',
                'postulante.email',
                'postulante.celular',
                'programa.cod_esp',
                'modalidad.modalidad_oti',
                'resultados.puntaje',
                'resultados.puesto',
                'resultados.puesto_general',
                'postulante.id AS id_postulante',
                'procesos.id AS id_proceso', 'procesos.nombre AS proceso',
                'modalidad.id AS id_modalidad', 'modalidad.nombre AS modalidad',
                'programa.nombre AS programa', 'programa.id',
                'control_biometrico.codigo_ingreso as codigo'
            ])
            ->join('resultados', 'resultados.dni_postulante', '=', 'postulante.nro_doc')
            ->join('inscripciones', 'inscripciones.id_postulante', '=', 'postulante.id')
            ->join('modalidad', 'inscripciones.id_modalidad', '=', 'modalidad.id')
            ->join('procesos', 'resultados.id_proceso', '=', 'procesos.id')
            ->leftJoin('users', 'users.id', '=', 'inscripciones.id_usuario')
            ->join('programa', 'programa.id', '=', 'inscripciones.id_programa')
            ->join('tipo_documento_identidad', 'postulante.tipo_doc', '=', 'tipo_documento_identidad.id')
            ->join('control_biometrico', function($join) {
                $join->on('control_biometrico.id_postulante', '=', 'postulante.id')
                     ->on('control_biometrico.id_proceso', '=', DB::raw(auth()->user()->id_proceso));
            })
            ->where([
                ['resultados.apto', '=', 'SI'],
                ['inscripciones.estado', '=', 0],
                ['resultados.dni_postulante', '=', $dni],
                ['resultados.id_proceso', '=', auth()->user()->id_proceso],
                ['inscripciones.id_proceso', '=', auth()->user()->id_proceso]
            ])
            ->first();
    
            // Si no se encuentra el postulante, retornar error
            if (!$postulante) {
                return response()->json(['error' => 'No se encontró el postulante o no cumple los requisitos.'], 404);
            }
    
            DB::transaction(function () use ($postulante) {
                $estadoCivilMap = [
                    1 => 2,
                    2 => 1,
                    3 => 3,
                    4 => 6
                ];
    
                $estudiante = Estudiante::on('mysql_secondary')->create([
                    'num_mat' => $postulante->codigo,
                    'cod_car' => $postulante->programa_oti,
                    'paterno' => $postulante->paterno,
                    'materno' => $postulante->materno,
                    'nombres' => $postulante->nombres,
                    'tip_doc' => $postulante->tipo_doc_oti,
                    'num_doc' => $postulante->dni,
                    'num_car' => 1, // Ingreso
                    'fch_nac' => $postulante->fec_nacimiento,
                    'sexo' => $postulante->sexo,
                    'ubigeo' => $postulante->ubigeo_residencia,
                    'mod_ing' => $postulante->modalidad_oti,
                    'est_civ' => $estadoCivilMap[$postulante->estado_civil] ?? $postulante->estado_civil,
                    'fch_ing' => $postulante->fecha,
                    'direc' => $postulante->direccion,
                    'email' => $postulante->email,
                    'con_est' => 5,
                    'celular' => $postulante->celular,
                    'cod_esp' => $postulante->cod_esp,
                    'puntaje' => $postulante->puntaje,
                    'puesto_escuela' => $postulante->puesto,
                    'puesto_general' => $postulante->puesto_general,
                    'ano_ing' => $postulante->anio,
                    'per_ing' => $postulante->ciclo_oti
                ]);
            });
    
            return response()->json(['mensaje' => 'Registro biométrico realizado con éxito'], 200);
    
        } catch (\Exception $e) {
            \Log::error('Error en la transacción: ' . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error en la transacción: ' . $e->getMessage()], 500);
        }
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

        $rutaCarpeta = public_path('/documentos/'.auth()->user()->id_proceso.'/control_biometrico/constancias/');
        if (!File::exists($rutaCarpeta)) {
            File::makeDirectory($rutaCarpeta, 0755, true, true);
        }
        file_put_contents($rutaCarpeta . $dni . '.pdf', $output);   
        return $pdf->stream();
    }

    public function pdfbiometrico2($dni){

        $datos = DB::select(
            "SELECT procesos.nombre as proceso, postulante.primer_apellido AS paterno,
            postulante.segundo_apellido AS materno, postulante.nombres, tipo_documento_identidad.nombre,
            postulante.nro_doc AS dni, postulante.fec_nacimiento AS fec_nacimiento,
            users.name, users.paterno as upaterno, modalidad.nombre as modalidad,
            resultados.fecha, resultados.puntaje, resultados.puesto,
            resultados.puesto_general, control_biometrico.codigo_ingreso AS cod_ingreso,
            control_biometrico.segunda_carrera AS segunda_carrera,
            programa.nombre AS programa
            FROM resultados
            JOIN postulante ON resultados.dni_postulante =  postulante.nro_doc
            JOIN inscripciones ON inscripciones.id_postulante = postulante.id
            JOIN modalidad ON inscripciones.id_modalidad = modalidad.id
            JOIN procesos ON resultados.id_proceso = procesos.id
            join users on users.id = inscripciones.id_usuario
            JOIN programa ON programa.id = inscripciones.id_programa
            JOIN control_biometrico ON control_biometrico.id_postulante = postulante.id
            LEFT JOIN tipo_documento_identidad ON postulante.tipo_doc = tipo_documento_identidad.id
            WHERE resultados.apto = 'SI' AND inscripciones.estado = 0 AND control_biometrico.id_proceso = "
            . auth()->user()->id_proceso ." AND resultados.dni_postulante = " .$dni. " AND resultados.id_proceso =".
            auth()->user()->id_proceso ." AND inscripciones.id_proceso = ". auth()->user()->id_proceso);

        $data = $datos[0];
        $hinsI = public_path('documentos/'.auth()->user()->id_proceso.'/inscripciones/huellas/').$dni.'x.jpg';
        $hinsD = public_path('documentos/'.auth()->user()->id_proceso.'/inscripciones/huellas/').$dni.'.jpg';
        $hexaI = public_path('documentos/'.auth()->user()->id_proceso.'/examen/huellas/').$dni.'.jpg';
        $hexaD = public_path('documentos/'.auth()->user()->id_proceso.'/examen/huellas/').$dni.'x.jpg';
        $hbioI = public_path('documentos/'.auth()->user()->id_proceso.'/control_biometrico/huellas/').$dni.'.jpg';
        $hbioD = public_path('documentos/'.auth()->user()->id_proceso.'/control_biometrico/huellas/').$dni.'x.jpg';
        $fins = public_path('documentos/'.auth()->user()->id_proceso.'/inscripciones/fotos/').$dni.'.jpg';
        $fbio = public_path('documentos/'.auth()->user()->id_proceso.'/control_biometrico/fotos/').$dni.'.jpg';

        setlocale(LC_TIME, 'es_ES.utf8');
        $fecha = $data->fecha;
        $date = \Carbon\Carbon::createFromFormat('Y-m-d', $fecha)->locale('es')->isoFormat('DD [de] MMMM [del] YYYY');

        $fimp =  Carbon::now()->locale('es')->isoFormat('DD [de] MMMM [del] YYYY');

        $fec_nac = $datos[0]->fec_nacimiento;
        $fnac = \Carbon\Carbon::createFromFormat('Y-m-d', $fec_nac)->locale('es')->isoFormat('DD [de] MMMM [del] YYYY');

        $pdf = Pdf::loadView('ingreso.datosbiometricos', compact('data','hinsI','hinsD','hexaI','hexaD','hbioI','hbioD','fins','fbio','date', 'fimp','fnac'));
        $pdf->setPaper('A4', 'portrait');
        $output = $pdf->output();

        $userIdProceso = auth()->user()->id_proceso;
        $documentoDir = public_path('/documentos/' . $userIdProceso . '/control_biometrico/constancias/');
        $filePath = $documentoDir . $dni . '.pdf';
    
        if (!file_exists($documentoDir)) {
            mkdir($documentoDir, 0755, true);
        }
    
        file_put_contents($filePath, $output);
        return $pdf->stream();

    }
    
    public function getCodigo($dni){
        $res = DB::table('temporal')
        ->where('dni', $dni)
        ->first();

        $this->response['estado'] = true;
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);
    }

    public function getEstudianteOTI(){
        
        foreach ($ingresantes as $dni) {

            $response = Http::post('https://service2.unap.edu.pe/TieneCarrerasPrevias/', [
                'doc_' => $dni,
                'nom_' => 'sdfasdf',
                'app_' => 'ssdfasd',
                'apm_' => 'sdfs'
            ], [
                'headers' => ['Content-Type' => 'application/json']
            ]);
    
            $data = $response->json();

            foreach ($data as $estudiante) {
                RegistroEstudiante::create([
                    'dni' => $dni,
                    'nombre' => $estudiante['name'],
                    'codigo' => $estudiante['code'],
                    'ciclo' => $estudiante['cycle'],
                    'id_programa' => $estudiante['careerId'],
                    'programa' => $estudiante['career'],
                    'ultimo_ciclo' => $estudiante['lastCycle'],
                    'condicion' => $estudiante['cond1tion'],
                ]);
            }
        }

    }

    public function carrerasPrevias($dni){
        $res = CarrerasPrevias::select('*')->where('dni_postulante',$dni)->get();

        $this->response['estado'] = true;
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);
    }




    public function crearCorreo(Request $request)
    {
        $url = "https://service6.unap.edu.pe/api/crear-correo";
        $secretKey = "unap@2025";
        $data = [
            "apellido_paterno" => $request->apellido_paterno,
            "apellido_materno" => $request->apellido_materno,
            "nombres" => $request->nombres,
            "dni" => $request->dni,
            "celular" => $request->celular,
            "correo_secundario" => $request->correo_secundario,
            "facultad" => $request->facultad,
            "escuela" => $request->escuela,
            "numero_ingresos" => $request->numero_ingresos
        ];
        $jsonData = json_encode($data);
        $signature = hash_hmac('sha256', $jsonData, $secretKey);
        $response = Http::withHeaders([
            'X-Signature' => $signature,
            'Content-Type' => 'application/json'
        ])->post($url, $data);
        return response()->json($response->json(), $response->status());

    }



}