<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\ParticipanteSimulacro;
use App\Models\Ide;
use App\Models\Resp;
use App\Models\Simulacro;
use App\Models\Postulante;
use App\Models\ArchivoSimulacro;
use App\Models\Documento;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use DB;

class ResultadosController extends Controller
{

    public function SubirResultado(Request $request){
        $data = $request->data; // No es necesario usar all()
        DB::table('resultados_simulacro')->insert($data);
        return response()->json(['message' => 'Datos insertados con éxito']);
    }

    public function SubirParticipantes(Request $request){
        $data = $request->data;
        $idSimulacro = $request->proceso;

        foreach ($data as &$row) {
            $row['id_simulacro'] = $idSimulacro;
        }

        DB::table('participantes_simulacro_externo')->insert($data);
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


    public function cargaArchivoIde(Request $request,$proceso,$area)
    {
        try {
            $archivo = $request->file('file');
            $extension = $archivo->getClientOriginalExtension();

            if (!in_array($extension, ['txt', 'dat'])) { return response()->json(['error' => 'El archivo debe ser de tipo txt o dat'], 400); }

            $tipo = $archivo->getClientOriginalExtension();
            $nombreArchivo = $archivo->getClientOriginalName();
            $areanombre = "";
            if($area == 1){$archivo->move(storage_path('app/calificar/'.$proceso.'/ides/biomedicas/'), $nombreArchivo); $areanombre = "biomedicas"; }
            if($area == 2){$archivo->move(storage_path('app/calificar/'.$proceso.'/ides/ingenierias/'), $nombreArchivo); $areanombre = "ingenierias";}
            if($area == 3){$archivo->move(storage_path('app/calificar/'.$proceso.'/ides/sociales/'), $nombreArchivo); $areanombre = "sociales";}
            
            $archivo = ArchivoSimulacro::create([
                'nombre' => $nombreArchivo ,
                'tipo' => $extension,
                'id_simulacro' => $proceso,
                'fecha' =>date('Y-m-d'),
                'area' => $area,
                'categoria' => 'ide',
                'url' => "app/calificar/$proceso/ides/$areanombre/$nombreArchivo"
            ]);

            $this->subirIdeBD(storage_path($archivo->url), $archivo->id);

            $respuesta = [ 'message' => 'Archivo subido', 'archivo' => [ 'nombre' => $nombreArchivo ],];

            return response()->json($respuesta, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function cargaArchivoRes(Request $request,$proceso,$ar)
    {
        $area = substr($ar,0,1);
        $aula = substr($ar,1,3);
        try {
            $archivo = $request->file('file');
            $extension = $archivo->getClientOriginalExtension();

            if (!in_array($extension, ['txt', 'dat'])) { return response()->json(['error' => 'El archivo debe ser de tipo txt o dat'], 400); }

            $tipo = $archivo->getClientOriginalExtension();
            $nombreArchivo = $archivo->getClientOriginalName();
            $areanombre = "";
            if($area == 1){$archivo->move(storage_path('app/calificar/'.$proceso.'/resp/biomedicas/'), $nombreArchivo); $areanombre = "biomedicas"; }
            if($area == 2){$archivo->move(storage_path('app/calificar/'.$proceso.'/resp/ingenierias/'), $nombreArchivo); $areanombre = "ingenierias";}
            if($area == 3){$archivo->move(storage_path('app/calificar/'.$proceso.'/resp/sociales/'), $nombreArchivo); $areanombre = "sociales";}
            
            $archivo = ArchivoSimulacro::create([
                'nombre' => $nombreArchivo ,
                'tipo' => $extension,
                'id_simulacro' => $proceso,
                'fecha' =>date('Y-m-d'),
                'area' => $area,
                'categoria' => 'respuesta',
                'url' => "app/calificar/$proceso/resp/$areanombre/$nombreArchivo"
            ]);

            $this->subirResBD(storage_path($archivo->url), $archivo->id, $aula);

            $respuesta = [ 'message' => 'Archivo subido', 'archivo' => [ 'nombre' => $nombreArchivo ],];

            return response()->json($respuesta, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function cargaArchivoPat(Request $request,$proceso,$ar)
    {
        $area = substr($ar,0,1);
        $aula = substr($ar,1,3);

        try {
            $archivo = $request->file('file');
            $extension = $archivo->getClientOriginalExtension();

            if (!in_array($extension, ['txt', 'dat'])) { return response()->json(['error' => 'El archivo debe ser de tipo txt o dat'], 400); }

            $tipo = $archivo->getClientOriginalExtension();
            $nombreArchivo = $archivo->getClientOriginalName();
            $areanombre = "";
            if($area == 1){$archivo->move(storage_path('app/calificar/'.$proceso.'/patron/biomedicas/'), $nombreArchivo); $areanombre = "biomedicas"; }
            if($area == 2){$archivo->move(storage_path('app/calificar/'.$proceso.'/patron/ingenierias/'), $nombreArchivo); $areanombre = "ingenierias";}
            if($area == 3){$archivo->move(storage_path('app/calificar/'.$proceso.'/patron/sociales/'), $nombreArchivo); $areanombre = "sociales";}
            
            $archivo = ArchivoSimulacro::create([
                'nombre' => $nombreArchivo ,
                'tipo' => $extension,
                'id_simulacro' => $proceso,
                'fecha' =>date('Y-m-d'),
                'area' => $area,
                'categoria' => 'patron',
                'url' => "app/calificar/$proceso/patron/$areanombre/$nombreArchivo"
            ]);

            $this->subirPatBD(storage_path($archivo->url), $archivo->id, $aula);

            $respuesta = [ 'message' => 'Archivo subido', 'archivo' => [ 'nombre' => $nombreArchivo ],];

            return response()->json($respuesta, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }



    public function leerIde($area)
    {
        if($area == 1){ $ponderaciones = DB::select("SELECT * FROM ponderacion WHERE area = 'biomedicas'"); }
        if($area == 2){ $ponderaciones = DB::select("SELECT * FROM ponderacion WHERE area = 'ingenierias'"); }
        if($area == 3){ $ponderaciones = DB::select("SELECT * FROM ponderacion WHERE area = 'sociales'"); }

        // $respFile = file(storage_path('app/calificar/resp101.dat'), FILE_IGNORE_NEW_LINES);

        if($area == 1){ $carpetaide = storage_path("app/calificar/ides/biomedicas"); }
        if($area == 2){ $carpetaide = storage_path("app/calificar/ides/ingenierias"); }
        if($area == 3){ $carpetaide = storage_path("app/calificar/ides/sociales"); }

        $calificaride = glob($carpetaide . '/*'); // Obtiene la lista de calificar en la carpeta

        $ideFile = [];

        foreach ($calificaride as $archivo) {
            // Verifica si es un archivo (no un directorio)
            if (is_file($archivo)) {
                // Lee el contenido del archivo y lo agrega al array
                $contenido = file($archivo, FILE_IGNORE_NEW_LINES);
                $ideFile = array_merge($ideFile, $contenido);
            }
        }

        $ides = $ideFile;

        if($area == 1){ $carpeta = storage_path("app/calificar/res/biomedicas"); }
        if($area == 2){ $carpeta = storage_path("app/calificar/res/ingenierias"); }
        if($area == 3){ $carpeta = storage_path("app/calificar/res/sociales"); }
#        $carpeta = storage_path('app/calificar/res');
        $calificar = glob($carpeta . '/*'); // Obtiene la lista de calificar en la carpeta

        $respFile = [];

        foreach ($calificar as $archivo) {
            // Verifica si es un archivo (no un directorio)
            if (is_file($archivo)) {
                // Lee el contenido del archivo y lo agrega al array
                $contenido = file($archivo, FILE_IGNORE_NEW_LINES);
                $respFile = array_merge($respFile, $contenido);
            }
        }

        // if($area == 1){ $patronFile = file(storage_path("app/calificar/patrones/biomedicas/biomedicas.dat"), FILE_IGNORE_NEW_LINES); }
        // if($area == 2){ $patronFile = file(storage_path("app/calificar/patrones/ingenierias/ingenierias.dat"), FILE_IGNORE_NEW_LINES); }
        // if($area == 3){ $patronFile = file(storage_path("app/calificar/patrones/sociales/sociales.dat"), FILE_IGNORE_NEW_LINES); }
        $patronFile = file(storage_path('app/calificar/patrones/biomedicas/biomedicas.dat'), FILE_IGNORE_NEW_LINES);

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

        DB::table('puntajes_simulacro')->insert($comparaciones);

        return response()->json(['comparaciones' => $comparaciones]);

    }

    public function cargarIdeBD( $area )
    {

        if($area == 1){ $carpetaide = storage_path("app/calificar/ides/biomedicas/"); }
        if($area == 2){ $carpetaide = storage_path("app/calificar/ides/ingenierias/"); }
        if($area == 3){ $carpetaide = storage_path("app/calificar/ides/sociales/"); }

        $calificaride = glob($carpetaide . '/*'); // Obtiene la lista de calificar en la carpeta

        $ideFile = [];

        foreach ($calificaride as $archivo) {
            // Verifica si es un archivo (no un directorio)
            if (is_file($archivo)) {
                // Lee el contenido del archivo y lo agrega al array
                $contenido = file($archivo, FILE_IGNORE_NEW_LINES);
                $ideFile = array_merge($ideFile, $contenido);
            }
        }

        $ides = $ideFile;

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

        if($area == 1){ $carpetaide = storage_path("app/calificar/res/biomedicas/"); }
        if($area == 2){ $carpetaide = storage_path("app/calificar/res/ingenierias/"); }
        if($area == 3){ $carpetaide = storage_path("app/calificar/res/sociales/"); }

        $calificaride = glob($carpetaide . '/*'); // Obtiene la lista de calificar en la carpeta

        $ideFile = [];

        foreach ($calificaride as $archivo) {
            // Verifica si es un archivo (no un directorio)
            if (is_file($archivo)) {
                // Lee el contenido del archivo y lo agrega al array
                $contenido = file($archivo, FILE_IGNORE_NEW_LINES);
                $ideFile = array_merge($ideFile, $contenido);
            }
        }

        $ides = $ideFile;

        // $rutaArchivo = storage_path('app/calificar/ides/id101.dat');
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

    
    //ARCHIVOS
    public function getArchivosIde(Request $request){

        $res = ArchivoSimulacro::select(DB::raw('COUNT(*) AS registros'), 'archivos_simulacro.*')
            ->join('ides','ides.id_archivo','archivos_simulacro.id')
            ->where('id_simulacro','=', $request->proceso)
            ->where(function ($query) use ($request) {
                return $query
                    ->orWhere('archivos_simulacro.nombre', 'LIKE', '%' . $request->term . '%')
                    ->orWhere('archivos_simulacro.tipo', 'LIKE', '%' . $request->term . '%');
            })->groupBy('archivos_simulacro.id')
            ->orderBy('archivos_simulacro.id', 'DESC')
            ->paginate(10);
      
          $this->response['estado'] = true;
          $this->response['datos'] = $res;
          return response()->json($this->response, 200);            
    }

    //ARCHIVOS
    public function getArchivosRes(Request $request){
        $res = ArchivoSimulacro::select(DB::raw('COUNT(*) AS registros'), 'archivos_simulacro.*')
            ->join('res','res.id_archivo','archivos_simulacro.id')
            ->where('id_simulacro','=', $request->proceso)
            ->where('categoria','=', 'respuesta')
            ->where(function ($query) use ($request) {
                return $query
                    ->orWhere('archivos_simulacro.nombre', 'LIKE', '%' . $request->term . '%')
                    ->orWhere('archivos_simulacro.tipo', 'LIKE', '%' . $request->term . '%');
            })->groupBy('archivos_simulacro.id')
            ->orderBy('archivos_simulacro.id', 'DESC')
            ->paginate(10);
        
            $this->response['estado'] = true;
            $this->response['datos'] = $res;
            return response()->json($this->response, 200);            
    }


    public function getArchivosPat(Request $request){
        $res = ArchivoSimulacro::select(DB::raw('COUNT(*) AS registros'), 'archivos_simulacro.*')
            ->join('res','res.id_archivo','archivos_simulacro.id')
            ->where('id_simulacro','=', $request->proceso)
            ->where('archivos_simulacro.categoria','=','patron')
            ->where(function ($query) use ($request) {
                return $query
                    ->orWhere('archivos_simulacro.nombre', 'LIKE', '%' . $request->term . '%')
                    ->orWhere('archivos_simulacro.tipo', 'LIKE', '%' . $request->term . '%');
            })->groupBy('archivos_simulacro.id')
            ->orderBy('archivos_simulacro.id', 'DESC')
            ->paginate(10);
        
            $this->response['estado'] = true;
            $this->response['datos'] = $res;
            return response()->json($this->response, 200);            
    }

    
    public function getIdes(Request $request){

        $res = Ide::select(
            'ides.id','ides.camp1','ides.camp2','ides.camp3','ides.camp4','ides.litho','ides.dni', 'ides.aula', 'ides.tipo',
            'archivos_simulacro.id  as id_archivo', 'archivos_simulacro.url',
            'participantes_simulacro_externo.dni as dnip',
            'archivos_simulacro.nombre AS name_archivo',
            \DB::raw('LENGTH(TRIM(ides.dni)) AS len_doc'),
            \DB::raw('(ides.dni REGEXP \'^[0-9]+$\' ) AS vdni'),
            \DB::raw('(ides.aula REGEXP \'^[0-9]+$\' ) AS vaula')
            )
            ->join('archivos_simulacro','archivos_simulacro.id','ides.id_archivo') 
            ->leftJoin('participantes_simulacro_externo', 'ides.dni', '=', 'participantes_simulacro_externo.dni')
            ->where('archivos_simulacro.id_simulacro','=', $request->proceso)
            ->where(function ($query) use ($request) {
                return $query
                    ->orWhere('ides.litho', 'LIKE', '%' . $request->term . '%')
                    ->orWhere('ides.dni', 'LIKE', '%' . $request->term . '%');
            })->orderBy('ides.id', 'ASC')
            ->paginate(2000);
      
        $this->response['estado'] = true;
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);            
    }

    public function getRes(Request $request){

        $res = Resp::select(
            'res.id',
            'res.n_lectura',
            'res.litho as res_litho',
            'ides.litho as ide_litho',
            'res.tipo as tipo',
            'res.aula as aula',
            'ides.tipo as id_tipo',
            'ides.dni',
            'res.respuestas',
            \DB::raw('LENGTH(TRIM(ides.dni)) AS len_doc'),
            \DB::raw('(ides.dni REGEXP \'^[0-9]+$\' ) AS vdni'),
            \DB::raw('if(res.tipo = ides.tipo, 1, 0) as c_tipo'),
            \DB::raw('if(res.aula = ides.aula, 1, 0) as c_aula'),
            'ides.id as id_ides'
            )
            ->join('archivos_simulacro','archivos_simulacro.id','res.id_archivo') 
            ->leftJoin('ides', 'res.litho', '=', 'ides.litho')
            ->where('archivos_simulacro.id_simulacro','=', $request->proceso)
            ->where('archivos_simulacro.categoria','=', 'respuesta')
            ->where(function ($query) use ($request) {
                return $query
                    ->orWhere('res.litho', 'LIKE', '%' . $request->term . '%')
                    ->orWhere('ides.dni', 'LIKE', '%' . $request->term . '%');
            })->orderBy('res.id', 'ASC')
            ->paginate(1600);
      
        $this->response['estado'] = true;
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);            
    }

    public function getPat(Request $request){

        $res = Resp::select(
            'res.id',
            'res.n_lectura',
            'res.litho as res_litho',
            'res.tipo as tipo',
            'res.aula as aula',
            'res.respuestas',
            DB::raw("if(archivos_simulacro.area = 1,'Biomédicas',if(archivos_simulacro.area = 2,'Ingenierías','Sociales')) as area")
            )
            ->join('archivos_simulacro','archivos_simulacro.id','res.id_archivo') 
            ->where('archivos_simulacro.id_simulacro','=', $request->proceso)
            ->where('archivos_simulacro.categoria','=', 'patron')
            ->where(function ($query) use ($request) {
                return $query
                    ->orWhere('res.litho', 'LIKE', '%' . $request->term . '%');
            })->orderBy('res.id', 'ASC')
            ->paginate(500);
      
        $this->response['estado'] = true;
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);            
    }


    public function getParticipantesSimulacro(Request $request){
      
        $res = DB::table('participantes_simulacro_externo')
        ->select('participantes_simulacro_externo.*', DB::raw('if(ides.id is null, 0, ides.id) as id_ide'))
        ->leftjoin('ides','ides.dni','participantes_simulacro_externo.dni')
        ->where('id_simulacro', '=', $request->proceso)
        ->where(function ($query) use ($request) {
            return $query
                ->orWhere('nombres', 'LIKE', '%' . $request->term . '%')
                ->orWhere('paterno', 'LIKE', '%' . $request->term . '%');
        })
        ->orderBy('paterno', 'DESC')
        ->paginate(200);
      
          $this->response['estado'] = true;
          $this->response['datos'] = $res;
          return response()->json($this->response, 200);            
    }



    public function eliminarArchivo($id)
    {
        
        $archivo = ArchivoSimulacro::find($id);
    
        if (!$archivo) {
            $this->response['titulo'] = 'ERROR';
            $this->response['mensaje'] = 'Archivo no encontrado.';
            $this->response['estado'] = false;
            return response()->json($this->response, 404);
        }
    
        $archivoNombre = $archivo->nombre;
        $filePath = storage_path($archivo->url);
    
        if (File::exists($filePath)) {
            File::delete($filePath);
    
            if (File::exists($filePath)) {
                $this->response['titulo'] = 'ERROR';
                $this->response['mensaje'] = 'No se pudo eliminar el archivo físico.';
                $this->response['estado'] = false;
                return response()->json($this->response, 500);
            }
        }
    
        $archivo->delete();
    
        $this->response['titulo'] = '¡REGISTRO ELIMINADO!';
        $this->response['mensaje'] = 'Archivo ' . $archivoNombre . ' eliminado correctamente.';
        $this->response['estado'] = true;
        $this->response['datos'] = $archivo;
        return response()->json($this->response, 200);
    }


    public function subirIdeBD($archivo, $id)
    {
        $ides = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
        $datosParaInsercion = [];
    
        foreach ($ides as $linea) {
            $campo1 = substr($linea, 0, 21);
            $campo2 = substr($linea, 3, 6);
            $campo3 = substr($linea, 24, 5);
            $campo4 = substr($linea, 38, 1);
            $campo5 = substr($linea, 40);
    
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
                    'id_archivo' => $id
                ];
            }
        }
    
        Ide::insert($datosParaInsercion);
    }

    public function subirResBD($archivo, $id, $aula)
    { 
        $ides = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    

        $datosParaInsercion = [];
    
        foreach ($ides as $linea) {
            $c1 = substr($linea, 0, 3);
            $lectura = substr($linea, 3, 6);
            $c3 = substr($linea, 9, 3);
            $c4 = substr($linea, 12, 5);
            $c5 = substr($linea, 17,4);
            $c6 = substr($linea, 24,4);
            $c7 = trim(substr($linea, 29,5));
            $c8 = trim(substr($linea, 38,1));    
            $litho = substr($linea, 40, 6);
            $tipo = substr($linea, 46, 1);
            $respuestas = substr($linea, 47, 60);
    
            if (strlen($c1) > 1) {
                $datosParaInsercion[] = [
                    'c1' => $c1,
                    'n_lectura' => $lectura,
                    'c3' => $c3,
                    'c4' => $c4,
                    'c5' => $c5,
                    'c6' => $c6,
                    'c7' => $c7,
                    'c8' => $c8,
                    'litho' => $litho,
                    'tipo' => $tipo,
                    'respuestas' => $respuestas,
                    'id_archivo' => $id,
                    'aula' => $aula
                ];
            }
        }
    
        Resp::insert($datosParaInsercion);
    }

    public function subirPatBD($archivo, $id, $aula)
    { 
        $ides = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
        $datosParaInsercion = [];
    
        foreach ($ides as $linea) {
            $c1 = substr($linea, 0, 3);
            $lectura = substr($linea, 3, 6);
            $c3 = substr($linea, 9, 3);
            $c4 = substr($linea, 12, 5);
            $c5 = substr($linea, 17,4);
            $c6 = substr($linea, 24,4);
            $c7 = trim(substr($linea, 29,5));
            $c8 = trim(substr($linea, 38,1));    
            $litho = substr($linea, 40, 6);
            $tipo = substr($linea, 46, 1);
            $respuestas = substr($linea, 47, 60);
    
            if (strlen($c1) > 1) {
                $datosParaInsercion[] = [
                    'c1' => $c1,
                    'n_lectura' => $lectura,
                    'c3' => $c3,
                    'c4' => $c4,
                    'c5' => $c5,
                    'c6' => $c6,
                    'c7' => $c7,
                    'c8' => $c8,
                    'litho' => $litho,
                    'tipo' => $tipo,
                    'respuestas' => $respuestas,
                    'id_archivo' => $id,
                    'aula' => $aula
                ];
            }
        }
    
        Resp::insert($datosParaInsercion);
    }


    public function getFichaRespuesta($id){
        $res = Resp::select(
            'res.id',
            'res.n_lectura',
            'res.litho as res_litho',
            'ides.litho as ide_litho',
            'res.tipo as res_tipo',
            'ides.tipo as ide_tipo',
            'res.respuestas',
            'ides.id as id_ides',
            'ides.aula as id_aula',
            'res.aula as res_aula',
            'ides.dni',
            'pex.nombres',
            'pex.paterno',
            'pex.materno'
        )
            ->leftJoin('ides', 'res.litho', '=', 'ides.litho')
            ->leftJoin('participantes_simulacro_externo as pex', 'pex.dni', '=', 'ides.dni')
            ->where('res.id', $id)
            ->first(); 

        $this->response['estado'] = true;
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);

    }



    public function Calificar($area)
    {
        $ponderaciones = DB::select("SELECT * FROM ponderacion WHERE id_ponderacion_simulacro = $area");

        $patrones = DB::select("SELECT i.*, asim.categoria AS ide_tipe FROM res i
        JOIN archivos_simulacro asim ON asim.id = i.id_archivo
        JOIN simulacro sim ON sim.id = asim.id_simulacro
        WHERE sim.id = 5 AND asim.area = $area
        AND asim.categoria = 'patron'");

        $respuestas = DB::select("SELECT i.*, r.tipo AS ide_tipe FROM res i
        JOIN archivos_simulacro asim ON asim.id = i.id_archivo
        JOIN simulacro sim ON sim.id = asim.id_simulacro
        LEFT JOIN ides r ON r.litho = i.litho
        WHERE sim.id = 5 AND asim.area = $area
        AND asim.categoria = 'respuesta'");

        $comparaciones = [];

        foreach ($respuestas as $line ){

            $comparacionActual = "";
            $puntaje = 0;

            $correctas = "";

            if($line->tipo == 'P'){ $correctas = $patrones[0]->respuestas; }
            if($line->tipo == 'Q'){ $correctas = $patrones[1]->respuestas; }
            if($line->tipo == 'R'){ $correctas = $patrones[2]->respuestas; }
            if($line->tipo == 'S'){ $correctas = $patrones[3]->respuestas; }
            if($line->tipo == 'T'){ $correctas = $patrones[4]->respuestas; }
            
            for ($i = 0; $i < 60; $i++) {
                if(strlen($line->respuestas) == 60 && strlen($correctas) == 60 ){
                    $caracterResp = $line->respuestas[$i];
                    $caracterPatron = $correctas[$i];
                    $puntuacion = 0;

                    if($caracterResp === " "){
                        $puntuacion = ($ponderaciones[$i]->ponderacion * 2);
                    }else {
                        if($caracterResp === $correctas[$i]){
                            $puntuacion = ($ponderaciones[$i]->ponderacion * 10);
                        }
                        else{ $puntuacion = 0; }
                    }
                    $comparacionActual = $comparacionActual.$caracterResp;
                    $puntaje = $puntaje + $puntuacion;
                }
        
            }

            $resp = Resp::find($line->id);
            $resp->puntaje = round($puntaje,3);
            $resp->save();

            $comparaciones[] = [
                'id'=> $line->id,
                'respuestas' => $comparacionActual,
                'puntaje' => round($puntaje,3)
            ];

        }



        //DB::table('puntajes_simulacro')->insert($comparaciones);

        return response()->json(['comparaciones' => $comparaciones]);

    }


    public function PdfErroresCalifacion($sim) {

        $proceso = Simulacro::find($sim);

        $errores = Ide::select(
            'archivos_simulacro.nombre AS archivo',
            'ides.camp2 AS lectura',
            'ides.litho',
            'ides.dni', 
            'ides.tipo', 
            'ides.aula', 
            'participantes_simulacro_externo.dni AS dnip',
            \DB::raw('LENGTH(TRIM(ides.dni)) AS len_doc'),
            \DB::raw('(ides.dni REGEXP \'^[0-9]+$\' ) AS vdni'),
            \DB::raw('(ides.aula REGEXP \'^[0-9]+$\' ) AS vaula'),
            \DB::raw('(ides.litho REGEXP \'^[0-9]+$\' ) AS vlitho')
        )
        ->join('archivos_simulacro', 'archivos_simulacro.id', 'ides.id_archivo')
        ->LEFTJOIN('participantes_simulacro_externo', 'ides.dni', 'participantes_simulacro_externo.dni')
        ->WHERE('archivos_simulacro.id_simulacro' ,'=', $sim)
        ->where(function ($query) {
            $query->whereNull('ides.dni')
                ->orWhereNull('ides.tipo')
                ->orWhereNull('ides.aula')
                ->orWhereNull('participantes_simulacro_externo.dni')
                ->orWhere(\DB::raw('LENGTH(TRIM(ides.dni))'), '!=', 8)
                ->orWhere(\DB::raw('(ides.dni REGEXP \'^[0-9]+$\' )'), '=', 0)
                ->orWhere(\DB::raw('(ides.litho REGEXP \'^[0-9]+$\' )'), '=', 0)
                ->orWhere(\DB::raw('(ides.aula REGEXP \'^[0-9]+$\' )'), '=', 0);
        })
        ->get();

        $duplicados_dni = Ide::select(
            'archivos_simulacro.nombre AS archivo',
            'ides.camp2 AS lectura',
            'ides.litho',
            'ides.dni',     
            'participantes_simulacro_externo.dni AS dnip'
        )
        ->join('archivos_simulacro', 'archivos_simulacro.id', '=', 'ides.id_archivo')
        ->leftJoin('participantes_simulacro_externo', 'ides.dni', '=', 'participantes_simulacro_externo.dni')
        ->where('archivos_simulacro.id_simulacro', '=', $sim)
        ->whereIn('ides.dni', function ($query) use ($sim) {
            $query->select('dni')
                ->from('ides')
                ->whereIn('id_archivo', function ($innerQuery) use ($sim) {
                    $innerQuery->select('id')
                        ->from('archivos_simulacro')
                        ->where('id_simulacro', '=', $sim);
                })
                ->groupBy('dni')
                ->havingRaw('COUNT(*) > 1');
        })
        ->get();

        $pdf = Pdf::loadView('Calificacion.errores', compact('errores','duplicados_dni','proceso'));
        $pdf->getDomPDF()->set_option("isPhpEnabled", true);
        $pdf->getDomPDF()->set_option("isHtml5ParserEnabled", true);
        $pdf->setPaper('A4', 'portrait');
        $output = $pdf->output();

        $rutaCarpeta = storage_path("/app/calificar/$sim/");
        file_put_contents(storage_path("/app/calificar/$sim/").'reporte.pdf', $output);

        
        $rutaArchivo = "calificar/{$sim}/reporte.pdf";

        if (Storage::exists($rutaArchivo)) {
            return response()->stream(
                function () use ($rutaArchivo) {
                    $stream = Storage::readStream($rutaArchivo);
                    fpassthru($stream);
                    if (is_resource($stream)) {
                        fclose($stream);
                    }
                },
                200,
                [
                    'Content-Type' => Storage::mimeType($rutaArchivo),
                    'Content-Disposition' => 'attachment; filename=reporte.pdf',
                ]
            );
        }
        

    }


    public function generarReportePrograma()
    {
        $rutaFuente = storage_path('app/fonts/Arialnl.ttf');

        $estudiantesPorPrograma = DB::select("SELECT dni, paterno, materno, nombres, puntaje, programa, apto AS ingreso 
        FROM puntajes
        WHERE programa IS NOT NULL
        ORDER BY programa, puntaje DESC LIMIT 200");
    
        $programaEstudiantes = [];
    
        // Recorre los estudiantes y agrúpalos por programa
        foreach ($estudiantesPorPrograma as $estudiante) {
            $programaActual = $estudiante->programa;
    
            if (!isset($programaEstudiantes[$programaActual])) {
                $programaEstudiantes[$programaActual] = [];
            }
    
            // Agrega el estudiante al programa actual
            $programaEstudiantes[$programaActual][] = [
                'dni' => $estudiante->dni,
                'paterno' => $estudiante->paterno,
                'materno' => $estudiante->materno,
                'nombres' => $estudiante->nombres,
                'puntaje' => $estudiante->puntaje,
                'ingreso' => $estudiante->ingreso,
            ];
        }
    
        foreach ($programaEstudiantes as $programa => $estudiantes) {
            // Cargar la vista 'Calificacion.puntajes' con los datos de los estudiantes
            $pdf = PDF::loadView('Calificacion.puntajes', compact('estudiantes','programa'));
            $pdf->getDomPDF()->set_option("isPhpEnabled", true);
            $pdf->getDomPDF()->set_option("isHtml5ParserEnabled", true);
            $pdf->setPaper('A4', 'portrait');
        
            // Guardar el PDF en el almacenamiento de Laravel
            $rutaCarpeta = storage_path("/app/");
            $nombreArchivo = "reporte_{$programa}.pdf";
            $rutaCompleta = $rutaCarpeta . $nombreArchivo;
        
            $pdf->save($rutaCompleta);
    
        }

    }


    public function cargaArchivoPDF(Request $request,$dni,$codigo,$tipo)
    {
        try {
            $archivo = $request->file('file');
            $extension = $archivo->getClientOriginalExtension();

            if (!in_array($extension, ['pdf'])) { 
                return response()->json(['error' => 'El archivo debe ser de tipo pdf'], 400); }

            $tipoA = $archivo->getClientOriginalExtension();
            $nombreArchivo = $archivo->getClientOriginalName();

            $post = Postulante::where('nro_doc', $dni)->first();
            
            if( $tipo == print($tipo)){ 
                $archivo->move(public_path('documentos/8/inscripciones/certificados/'), $dni.$codigo.'.pdf');
                $datosDocumento = [
                    'codigo' => $codigo,
                    'nombre' => 'Cert. est presentado',
                    'id_postulante' => $post->id,
                    'id_tipo_documento' => 1,
                    'estado' => 1,
                    'url' => 'documentos/8/inscripciones/certificados/'.$dni.$codigo.'.pdf',
                    'numero' => '1',
                    'observacion' => 'certificado-biometrico'
                ];

                $postulante = Documento::create($datosDocumento);

            } else { 
                $archivo->move(public_path('documentos/8/inscripciones/dnis/'), $codigo.$dni.'.pdf');
                $datosDocumento = [
                    'codigo' => $codigo,
                    'nombre' => 'DNI',
                    'id_postulante' => $post->id,
                    'id_tipo_documento' => 1,
                    'estado' => 1,
                    'url' => 'documentos/8/inscripciones/certificados/'.$dni.$codigo.'.pdf',
                    'numero' => 1,
                    'observacion' => 'certificado-biometrico'
                ];

                $postulante = Documento::create($datosDocumento);
            }
            $respuesta = [ 'message' => 'Archivo subido', 'estado'=>true, 'archivo' => [ 'nombre' => $nombreArchivo ],];

            return response()->json($respuesta, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }


}
