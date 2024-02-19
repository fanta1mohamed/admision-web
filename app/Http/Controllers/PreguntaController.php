<?php

namespace App\Http\Controllers;

use App\Models\DetalleExamenVocacional;
use App\Models\Pregunta;
use App\Models\Respuesta;
use App\Models\Inscripcion;
use App\Models\ExamenVocacional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PreguntaController extends Controller
{
   
    public function getPreguntasPrograma(Request $request)
    {
        $programa = $request->id_programa;
        $preguntas = [];

        $res = DB::select(
            'SELECT preguntas.id AS id_pregunta, preguntas.url, 
            preguntas.pregunta, respuestas.respuesta, 
            respuestas.id AS id_respuesta, respuestas.valor
            FROM examen_vocacional 
            join preguntas ON examen_vocacional.id = preguntas.id_examen_vocacional
            JOIN respuestas ON respuestas.id_pregunta = preguntas.id
            WHERE examen_vocacional.id = '.$programa.';');

        $alternativas = [];
        $temp = $res[0]->id_pregunta;
        $cont = 0;
        $preguntas[0]['id'] = $temp; 
        $preguntas[0]['pregunta'] = $res[0]->pregunta;
        $preguntas[0]['url'] = $res[0]->url;

        // $alternativas[0] = $res[0]->respuesta; 
        foreach ($res as $key => $registro) {
            if($temp !== $registro->id_pregunta ){
                $preguntas[$cont]['respuestas'] = $alternativas;  
                $temp = $registro->id_pregunta;
                $cont++;
                $preguntas[$cont]['id_pregunta'] = $registro->id_pregunta; 
                $preguntas[$cont]['pregunta'] = $registro->pregunta;
                $preguntas[$cont]['url'] = $registro->url;
                $alternativas = [];
                $item = new Respuesta();
                $item['respuesta'] = $registro->respuesta;
                $item['valor'] = $registro->valor;
                $item['ide'] = $registro->id_respuesta;
                $item['ideP'] = $registro->id_pregunta;
                array_push($alternativas,$item);
                // $alternativas[$key] = $registro->respuesta;
            }
            else{
                $item = new Respuesta();
                $item['respuesta'] = $registro->respuesta;
                $item['valor'] = $registro->valor;
                $item['ide'] = $registro->id_respuesta;
                $item['ideP'] =$registro->id_pregunta;
                array_push($alternativas,$item);
                $preguntas[$cont]['respuestas'] = $alternativas;  
            }
        }
        $this->response['estado'] = true;
        $this->response['datos'] = $preguntas;
        return response()->json($this->response, 200);
    }


    public function getPreguntasPerfiles(Request $request)
    {
        $programa = $request->id_programa;
        $preguntas = [];

        $ids = DB::table('preguntas')
        ->select('id')
        ->where(function ($query) {
            $query->whereBetween('id', [431, 435])
                  ->orWhereBetween('id', [436, 440])
                  ->orWhereBetween('id', [441, 447])
                  ->orWhereBetween('id', [448, 452]);
        })
        ->inRandomOrder()
        ->limit(10)
        ->pluck('id');

        $res = DB::table('examen_vocacional')
            ->join('preguntas', 'examen_vocacional.id', '=', 'preguntas.id_examen_vocacional')
            ->join('respuestas', 'respuestas.id_pregunta', '=', 'preguntas.id')
            ->select('preguntas.id AS id_pregunta', 'preguntas.url', 'preguntas.pregunta', 'respuestas.respuesta', 'respuestas.id AS id_respuesta', 'respuestas.valor')
            ->where('examen_vocacional.id', 44)
            ->whereIn('preguntas.id', $ids)
            ->get();

        $alternativas = [];
        $temp = $res[0]->id_pregunta;
        $cont = 0;
        $preguntas[0]['id'] = $temp; 
        $preguntas[0]['pregunta'] = $res[0]->pregunta;
        $preguntas[0]['url'] = $res[0]->url;

        // $alternativas[0] = $res[0]->respuesta; 
        foreach ($res as $key => $registro) {
            if($temp !== $registro->id_pregunta ){
                $preguntas[$cont]['respuestas'] = $alternativas;  
                $temp = $registro->id_pregunta;
                $cont++;
                $preguntas[$cont]['id_pregunta'] = $registro->id_pregunta; 
                $preguntas[$cont]['pregunta'] = $registro->pregunta;
                $preguntas[$cont]['url'] = $registro->url;
                $alternativas = [];
                $item = new Respuesta();
                $item['respuesta'] = $registro->respuesta;
                $item['ide'] = $registro->id_respuesta;
                $item['ideP'] = $registro->id_pregunta;
                array_push($alternativas,$item);
                // $alternativas[$key] = $registro->respuesta;
            }
            else{
                $item = new Respuesta();
                $item['respuesta'] = $registro->respuesta;
                $item['ide'] = $registro->id_respuesta;
                $item['ideP'] =$registro->id_pregunta;
                array_push($alternativas,$item);
                $preguntas[$cont]['respuestas'] = $alternativas;  
            }
        }
        $this->response['estado'] = true;
        $this->response['datos'] = $preguntas;
        return response()->json($this->response, 200);
    }
   
    public function getDatosExamen(Request $request)
    {
        $res = DB::select(
            'SELECT examen_vocacional.id as id_vocacional,   programa.nombre, postulante.*  FROM pre_inscripcion
            JOIN postulante ON postulante.id = pre_inscripcion.id_postulante
            JOIN programa ON pre_inscripcion.id_programa  = programa.id
            JOIN examen_vocacional ON pre_inscripcion.id_programa = examen_vocacional.programa
            WHERE id_postulante = '.$request->id_postulante.';');

        $this->response['estado'] = true;
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);
    }

    public function getDatosExamen2(Request $request)
    {
        $examen = DB::select( 'SELECT COUNT(*) AS vocacional FROM avance_postulante WHERE dni_postulante = '.$request->dni.' AND avance = 2 AND id_proceso = 7');


        if($examen[0]->vocacional == 0) {

            $res = Inscripcion::select('examen_vocacional.id as id_vocacional', 'programa.nombre', 'postulante.*')
            ->join('postulante', 'postulante.id', '=', 'inscripciones.id_postulante')
            ->join('programa', 'inscripciones.id_programa', '=', 'programa.id')
            ->join('examen_vocacional', 'inscripciones.id_programa', '=', 'examen_vocacional.programa')
            ->where('postulante.nro_doc', $request->dni)
            ->where('inscripciones.id_proceso', 7)
            ->where('inscripciones.codigo', $request->codigo)
            ->get();
     
             $this->response['estado'] = true;
             $this->response['datos'] = $res;
             return response()->json($this->response, 200);
        }else {
            $this->response['estado'] = false;
            $this->response['mensaje'] = "Ya rindió el examen vocacional";
            return response()->json($this->response, 200);
        }

    }

    

    public function getResultado(){

       $res = DB::select(
        'SELECT 
        postulante.nro_doc AS dni, postulante.nombres, postulante.primer_apellido,
        postulante.segundo_apellido,
        modalidad.nombre AS modalidad, modalidad.codigo AS mod_cod, 
        programa.nombre AS programa, programa.codigo AS pro_cod,
        SUM(respuestas.valor) AS nota
        FROM detalle_examen_vocacional
        JOIN respuestas ON respuestas.id = detalle_examen_vocacional.id_respuesta
        JOIN postulante ON postulante.id = detalle_examen_vocacional.id_postulante
        JOIN inscripciones ON postulante.id = inscripciones.id_postulante
        JOIN programa ON programa.id = inscripciones.id_programa
        JOIN modalidad ON modalidad.id = inscripciones.id_modalidad
        GROUP BY postulante.nro_doc, modalidad.id, programa.id, inscripciones.id'
       );

       $this->response['estado'] = true;
       $this->response['datos'] = $res;
       return response()->json($this->response, 200);
    }




    public function guardarExamen(Request $request){

        // $this->response['nota'] = $request->respuestas;
        // $this->response['estado'] = true;
        // return response()->json($this->response, 200);



        $nota = 0;
       //$respuestas = [];
       //array_push($respuestas,$request->res1,$request->res2,$request->res3,$request->res4);
       //array_push($respuestas,$request->res5,$request->res6,$request->res7,$request->res8);
       //array_push($respuestas,$request->res9,$request->res10);

        foreach($request->respuestas as $res ) {
            if($res != null){
                $nota = $nota + $res['valor'];
            }
        }

        try {
            $trans = DB::transaction(function () use ($request,$nota) {

                foreach($request->respuestas as $res ) {
                    if($res != null){
                        $respuesta = DetalleExamenVocacional::create([ 
                            'id_respuesta' => $res['ide'],
                            'id_pregunta' => $res['ideP'],
                            'codigo_pre' => $request->codigo,
                            // 'dni' => null,   s
                            // 'id_examen' => , 
                        ]);
                    }
                }

                $this->response['nota'] = $nota;
                $this->response['estado'] = true;
                return response()->json($this->response, 200);

            });
        } catch (\Throwable $th) {
            $this->response['mensaje'] = 'Ocurrió un error, vuelva a intentarlo. ' .  $th->getMessage();
            $this->response['estado'] = false;
            return response()->json($this->response, 200);
        }

    }

    private function existe($id, $array) {
        $cont = 0;
        foreach ($array as $key => $item) {
           if($id  === $item->id){
            $cont = $key;
           }
        }
        return $cont;
    }

    //EXAMEN VOCACIONAL 2

    public function getpreguntas2(Request $request){

        $pos = $request->postulante;
        $cod = substr($request->codigo, 4, 2);

        $area = $this->obtenerAreaPorCodigo($cod);
       
        $res = DB::table('preguntas')
        ->select('preguntas.id AS id_pregunta', 'preguntas.pregunta')
        ->join('examen_vocacional', 'examen_vocacional.id', '=', 'preguntas.id_examen_vocacional')
        ->where('examen_vocacional.area', $area)
        ->inRandomOrder()
        ->limit(10)
        ->get();
        
        $preguntas = collect();

        $perfiles = ['perfil3','perfil4','perfil9','perfil10','perfil11'];


        foreach ($perfiles as $perfil) {
            $preguntasDeRango = DB::table('preguntas')
                ->select('preguntas.id as id_pregunta','pregunta')
                ->where('observacion', $perfil)
                ->inRandomOrder()
                ->limit(2)
                ->get();

            $preguntas = $preguntas->merge($preguntasDeRango);
        }

        $preguntas = $preguntas->shuffle()->take(12);


        $combinados = collect($res)->merge($preguntas);


        $this->response['estado'] = true;
        $this->response['datos'] = $combinados;
        return response()->json($this->response, 200);
    }


    public function getAlternativas2(Request $request){
        $res = DB::select('SELECT id, respuesta FROM respuestas
        WHERE id_pregunta = ' . $request->id_pregunta);
        
        $this->response['estado'] = true;
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);
    }

    public function getPreguntasPerfiles2(){

        $preguntas = collect();
        $rangos = [[431, 435], [436, 440], [441, 447], [448, 452], [453,457]];
        
        foreach ($rangos as $rango) {
            $preguntasDeRango = DB::table('preguntas')
                ->select('preguntas.id as id_pregunta','pregunta')
                ->whereBetween('id', $rango)
                ->inRandomOrder()
                ->limit(2)
                ->get();
        
            $preguntas = $preguntas->merge($preguntasDeRango);
        }
        
        $preguntas = $preguntas->shuffle()->take(12);
        

        return $preguntas;

    }


    private function obtenerAreaPorCodigo($codigo) {
        $programas = [
            [ 'cod'=> '08', 'area'=> 'biomedicas' ],
            [ 'cod'=> '09', 'area'=> 'biomedicas' ],
            [ 'cod'=> '10', 'area'=> 'biomedicas' ],
            [ 'cod'=> '23', 'area'=> 'biomedicas' ],
            [ 'cod'=> '38', 'area'=> 'biomedicas' ],
            [ 'cod'=> '39', 'area'=> 'biomedicas' ],
            [ 'cod'=> '40', 'area'=> 'biomedicas' ],
            [ 'cod'=> '41', 'area'=> 'biomedicas' ],
            [ 'cod'=> '03', 'area'=> 'ingenierias' ],
            [ 'cod'=> '13', 'area'=> 'ingenierias' ],
            [ 'cod'=> '14', 'area'=> 'ingenierias' ],
            [ 'cod'=> '24', 'area'=> 'ingenierias' ],
            [ 'cod'=> '25', 'area'=> 'ingenierias' ],
            [ 'cod'=> '26', 'area'=> 'ingenierias' ],
            [ 'cod'=> '27', 'area'=> 'ingenierias' ],
            [ 'cod'=> '28', 'area'=> 'ingenierias' ],
            [ 'cod'=> '29', 'area'=> 'ingenierias' ],
            [ 'cod'=> '30', 'area'=> 'ingenierias' ],
            [ 'cod'=> '31', 'area'=> 'ingenierias' ],
            [ 'cod'=> '32', 'area'=> 'ingenierias' ],
            [ 'cod'=> '33', 'area'=> 'ingenierias' ],
            [ 'cod'=> '34', 'area'=> 'ingenierias' ],
            [ 'cod'=> '35', 'area'=> 'ingenierias' ],
            [ 'cod'=> '36', 'area'=> 'ingenierias' ],
            [ 'cod'=> '37', 'area'=> 'ingenierias' ],
            [ 'cod'=> '01', 'area'=> 'sociales' ],
            [ 'cod'=> '02', 'area'=> 'sociales' ],
            [ 'cod'=> '04', 'area'=> 'sociales' ],
            [ 'cod'=> '05', 'area'=> 'sociales' ],
            [ 'cod'=> '06', 'area'=> 'sociales' ],
            [ 'cod'=> '07', 'area'=> 'sociales' ],
            [ 'cod'=> '11', 'area'=> 'sociales' ],
            [ 'cod'=> '12', 'area'=> 'sociales' ],
            [ 'cod'=> '15', 'area'=> 'sociales' ],
            [ 'cod'=> '16', 'area'=> 'sociales' ],
            [ 'cod'=> '17', 'area'=> 'sociales' ],
            [ 'cod'=> '18', 'area'=> 'sociales' ],
            [ 'cod'=> '19', 'area'=> 'sociales' ],
            [ 'cod'=> '20', 'area'=> 'sociales' ],
            [ 'cod'=> '21', 'area'=> 'sociales' ],
            [ 'cod'=> '22', 'area'=> 'sociales' ],
            [ 'cod'=> '42', 'area'=> 'sociales' ],
            [ 'cod'=> '43', 'area'=> 'sociales' ],
            [ 'cod'=> '44', 'area'=> 'sociales' ],
            [ 'cod'=> '45', 'area'=> 'sociales' ]
        ];
    
        foreach ($programas as $programa) {
            if ($programa['cod'] === $codigo) {
                return $programa['area'];
            }
        }
    
        return 'sin area';
    }




}
