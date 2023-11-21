<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParticipanteSimulacro;
use DB;
use App\Models\Ide;


class ResultadosController extends Controller
{
    

    public function SubirResultado(Request $request){
        $data = $request->data; // No es necesario usar all()
        DB::table('resultados_simulacro')->insert($data);
        return response()->json(['message' => 'Datos insertados con éxito']);
    }

    public function getResultados(Request $request){

        $resultado = ParticipanteSimulacro::select('participantes_simulacro.nro_doc', 'resultados_simulacro.puntaje', 'resultados_simulacro.puesto_programa', 'resultados_simulacro.fecha', 'programa.area')
        ->join('resultados_simulacro', 'participantes_simulacro.nro_doc', '=', 'resultados_simulacro.dni')
        ->join('inscripcion_simulacro', 'inscripcion_simulacro.id_estudiante', '=', 'participantes_simulacro.id')
        ->join('programa', 'inscripcion_simulacro.id_programa', '=', 'programa.id')
        ->where('participantes_simulacro.nro_doc', $request->dni)
        ->where('resultados_simulacro.fecha', '2023-11-18')
        ->first();

        DB::table('revision_puntaje')->insert([
            "dni"=>$request->dni
        ]);
        

        $this->response['datos'] = $resultado;
        $this->response['estado'] = true;  
        return response()->json($this->response, 200);

    }

    public function getExamenBio(){
        $archivo = public_path('/resultados/biomedicas.pdf');
        return response()->download($archivo);
    }
    public function getExamenIng(){
        $archivo = public_path('/resultados/ingenierias.pdf');
        return response()->download($archivo);
    }
    public function getExamenSoc(){
        $archivo = public_path('/resultados/sociales.pdf');
        return response()->download($archivo);
    }




    public function cargaArchivo(Request $request)
    {
        try {
            // $request->validate([
            //     'file' => 'required|file|mimes:dat,pdf,txt|max:10240',
            // ]);
    
            $archivo = $request->file('file');
            $extension = $archivo->getClientOriginalExtension();
    
            // Validar la extensión nuevamente
            if (!in_array($extension, ['pdf', 'txt', 'dat'])) {
                return response()->json(['error' => 'El archivo debe ser de tipo pdf, txt o dat'], 400);
            }
    
            // Manejar cada archivo individualmente
            // $nombreArchivo = uniqid() . '_' . $archivo->getClientOriginalName();
            $nombreArchivo = $archivo->getClientOriginalName();

            // Mover el archivo a la carpeta 'archivos_dat' usando el método move
            $archivo->move(storage_path('app/archivos'), $nombreArchivo);
    
            // Obtener la ruta completa del archivo almacenado
            $rutaCompleta = storage_path('app/archivos/' . $nombreArchivo);
    
            // Realizar cualquier procesamiento adicional aquí
    
            $respuesta = [
                'message' => 'Archivo subido y procesado exitosamente',
                'archivo' => [
                    'nombre' => $nombreArchivo,
                    'ruta' => $rutaCompleta,
                ],
            ];
    
            return response()->json($respuesta, 200);
        } catch (\Exception $e) {
            // Manejar el error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function leerIde($area)
    {
        if($area == 1){ $ponderaciones = DB::select("SELECT * FROM ponderacion WHERE area = 'biomedicas'"); }
        if($area == 2){ $ponderaciones = DB::select("SELECT * FROM ponderacion WHERE area = 'ingenierias'"); }
        if($area == 3){ $ponderaciones = DB::select("SELECT * FROM ponderacion WHERE area = 'sociales'"); }
        

        // $respFile = file(storage_path('app/archivos/resp101.dat'), FILE_IGNORE_NEW_LINES);

        if($area == 1){ $carpetaide = storage_path("app/archivos/ides/biomedicas"); }
        if($area == 2){ $carpetaide = storage_path("app/archivos/ides/ingenierias"); }
        if($area == 3){ $carpetaide = storage_path("app/archivos/ides/sociales"); }

        $archivoside = glob($carpetaide . '/*'); // Obtiene la lista de archivos en la carpeta

        $ideFile = [];

        foreach ($archivoside as $archivo) {
            // Verifica si es un archivo (no un directorio)
            if (is_file($archivo)) {
                // Lee el contenido del archivo y lo agrega al array
                $contenido = file($archivo, FILE_IGNORE_NEW_LINES);
                $ideFile = array_merge($ideFile, $contenido);
            }
        }

        $ides = $ideFile;

        if($area == 1){ $carpeta = storage_path("app/archivos/res/biomedicas"); }
        if($area == 2){ $carpeta = storage_path("app/archivos/res/ingenierias"); }
        if($area == 3){ $carpeta = storage_path("app/archivos/res/sociales"); }
#        $carpeta = storage_path('app/archivos/res');
        $archivos = glob($carpeta . '/*'); // Obtiene la lista de archivos en la carpeta

        $respFile = [];

        foreach ($archivos as $archivo) {
            // Verifica si es un archivo (no un directorio)
            if (is_file($archivo)) {
                // Lee el contenido del archivo y lo agrega al array
                $contenido = file($archivo, FILE_IGNORE_NEW_LINES);
                $respFile = array_merge($respFile, $contenido);
            }
        }

        if($area == 1){ $patronFile = file(storage_path("app/archivos/patrones/biomedicas/biomedicas.dat"), FILE_IGNORE_NEW_LINES); }
        if($area == 2){ $patronFile = file(storage_path("app/archivos/patrones/ingenierias/ingenierias.dat"), FILE_IGNORE_NEW_LINES); }
        if($area == 3){ $patronFile = file(storage_path("app/archivos/patrones/sociales/sociales.dat"), FILE_IGNORE_NEW_LINES); }
        #$patronFile = file(storage_path('app/archivos/patron_biomedicas.dat'), FILE_IGNORE_NEW_LINES);

        $comparaciones = [];

        $tipoPruebaMap = [ 'P' => 0, 'Q' => 1, 'R' => 2, 'S' => 3, 'T' => 4,];

        foreach ($respFile as $lineaResp) {

                if (strlen($lineaResp) > 46 && isset($tipoPruebaMap[$lineaResp[46]])) {
                    $tipoPrueba = $lineaResp[46];
                    $filaPatron = $tipoPruebaMap[$tipoPrueba];
        
                    // Inicializar el array para la comparación actual
                    $comparacionActual = "";
        
                    $puntaje = 0;
                    for ($i = 0; $i < 60; $i++) {
                        $caracterResp = $lineaResp[$i + 47];
                        $caracterPatron = $patronFile[$filaPatron][$i + 47];

                        $puntuacion = 0; 
                        if($caracterResp === " "){
                            $puntuacion = ($ponderaciones[$i]->ponderacion * 2);
                        }else {
                            if($caracterResp === $caracterPatron){
                                $puntuacion = ($ponderaciones[$i]->ponderacion * 10);
                            }
                            else{
                                $puntuacion = 0;
                            }
                        }
                        $comparacionActual = $comparacionActual.$caracterResp;

                        // $comparacionActual[] = [
                        //     'caracter_resp' => $caracterResp,
                        //     'caracter_patron' => $caracterPatron,
                        //     'coincide' => ($caracterResp === $caracterPatron) ? 1 : 0,
                        //     'ponderacion' => $ponderaciones[$i]->ponderacion,
                        //     'puntos' => $puntuacion,
                        // ];

                        $puntaje = $puntaje + $puntuacion;
                    }
                    $k = 0;
                    foreach ($ides as $elemento) {
                        if (strpos($elemento, substr($lineaResp,39,7)) !== false){
                            $k = $elemento;
                        }
                    }
        
                    $comparaciones[] = [
                        'respuestas' => $comparacionActual, 
                        'puntaje' => round($puntaje,3), 
                        'litho' => substr(substr($k,39,7),1,6),
                        'tipo' => substr($k,46,1),
                        'dni' => substr($k,47,8),
                        'aula' => substr($k,55,3) 
                    ];

                }

        }
        
        // DB::table('puntajes_simulacro')->insert($comparaciones);

        return response()->json(['comparaciones' => $comparaciones]);

    }

    public function cargarIdeBD( $area )
    {

        if($area == 1){ $carpetaide = storage_path("app/archivos/ides/biomedicas/"); }
        if($area == 2){ $carpetaide = storage_path("app/archivos/ides/ingenierias/"); }
        if($area == 3){ $carpetaide = storage_path("app/archivos/ides/sociales/"); }

        $archivoside = glob($carpetaide . '/*'); // Obtiene la lista de archivos en la carpeta

        $ideFile = [];

        foreach ($archivoside as $archivo) {
            // Verifica si es un archivo (no un directorio)
            if (is_file($archivo)) {
                // Lee el contenido del archivo y lo agrega al array
                $contenido = file($archivo, FILE_IGNORE_NEW_LINES);
                $ideFile = array_merge($ideFile, $contenido);
            }
        }

        $ides = $ideFile;

        // $rutaArchivo = storage_path('app/archivos/ides/id101.dat');
        // $datos = file($rutaArchivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        // // Prepara los datos para la inserción
        $datosParaInsercion = [];

        foreach ($ides as $linea) {
            $campo1 = substr($linea, 0, 21);
            $campo2 = substr(substr($linea, 21 , 8),3,5);
            $campo3 = substr(substr($linea, 26, 9),3,5);
            $campo4 = substr($linea, 38, 1);
            $campo5 = substr($linea, 40);

            // Descomposición de campo5
            $litho = substr($campo5, 0, 6);
            $tipo = substr($campo5, 6, 1);
            $dni = substr($campo5, 7, 8);
            $aula = substr($campo5, 15, 3);

            if (strlen($campo1) > 1) {
                $datosParaInsercion[] = [
                    'camp1' => $campo1,
                    'camp2' => $campo2,
                    'camp3' => $campo3,
                    'camp4' => $campo4,
                    'litho' => $litho,
                    'tipo' => $tipo,
                    'dni' => $dni,
                    'aula' => $aula,
                ];

            }

        }

        // Inserta en lote utilizando Eloquent
        Ide::insert($datosParaInsercion);

        return 'Datos insertados en lote correctamente.';
    
    }

    public function cargarResBD($area)
    {

        if($area == 1){ $carpetaide = storage_path("app/archivos/res/biomedicas/"); }
        if($area == 2){ $carpetaide = storage_path("app/archivos/res/ingenierias/"); }
        if($area == 3){ $carpetaide = storage_path("app/archivos/res/sociales/"); }

        $archivoside = glob($carpetaide . '/*'); // Obtiene la lista de archivos en la carpeta

        $ideFile = [];

        foreach ($archivoside as $archivo) {
            // Verifica si es un archivo (no un directorio)
            if (is_file($archivo)) {
                // Lee el contenido del archivo y lo agrega al array
                $contenido = file($archivo, FILE_IGNORE_NEW_LINES);
                $ideFile = array_merge($ideFile, $contenido);
            }
        }

        $ides = $ideFile;

        // $rutaArchivo = storage_path('app/archivos/ides/id101.dat');
        // $datos = file($rutaArchivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        // // Prepara los datos para la inserción
        $datosParaInsercion = [];

        foreach ($ides as $linea) {
            $campo1 = substr($linea, 0, 21);
            $campo2 = substr(substr($linea, 21 , 8),3,5);
            $campo3 = substr(substr($linea, 26, 9),3,5);
            $campo4 = substr($linea, 38, 1);
            $campo5 = substr($linea, 40);

            // Descomposición de campo5
            $litho = substr($campo5, 0, 6);
            $tipo = substr($campo5, 6, 1);
            $dni = substr($campo5, 7, 8);
            $aula = substr($campo5, 15, 3);

            if (strlen($campo1) > 1) {
                $datosParaInsercion[] = [
                    'camp1' => $campo1,
                    'camp2' => $campo2,
                    'camp3' => $campo3,
                    'camp4' => $campo4,
                    'litho' => $litho,
                    'tipo' => $tipo,
                    'dni' => $dni,
                    'aula' => $aula,
                ];
            }

        }

        // Inserta en lote utilizando Eloquent
        Ide::insert($datosParaInsercion);

        return 'Datos insertados en lote correctamente.';
    
    }





    


        
}
