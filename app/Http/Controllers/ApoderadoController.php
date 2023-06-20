<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Models\Apoderado;
use App\Models\Paso;
use Illuminate\Support\Str;

class ApoderadoController extends Controller {

  public function getApoderado(Request $request )
  {
    $v = [];
    $res = Apoderado::select(
      'id','tipo_doc', 'nro_documento', 'paterno', 'materno', 'nombres', 'tipo_apoderado'
    )
    ->where('apoderado.id_postulante','=', $request->id_postulante)
    ->where('apoderado.tipo_apoderado','=', $request->tipo)
    ->get();
    
    if(count($res) === 0) {
      $this->response['mensaje'] = "Apoderado no registrado";
      $this->response['estado'] = false;
      $this->response['datos'] = $res;
      return response()->json($this->response, 200);      
    }else {

      $this->response['mensaje'] = "tienes apoderado";
      $this->response['estado'] = true;
      $this->response['datos'] = $res;
      return response()->json($this->response, 200);
    }

  }



  public function saveApoderado(Request $request ) {

    $tipo_doc = 1;
    if( Str::length($request->dni) == 12 ) { $tipo_doc = 2; }

    $apoderado = null;
    if (!$request->id) {
        $apoderado = Apoderado::create([
            'tipo_doc' => $tipo_doc,
            'nro_documento' => $request->dni,
            'paterno' => $request->paterno, 
            'materno' => $request->materno,
            'nombres' => $request->nombres,
            'id_postulante' => $request->id_postulante,
            'tipo_apoderado' => $request->tipo_apoderado,
        ]);

        if($request->actualizar == 'si'){
          $this->savePasos($request->name, $request->nro, $request->avance, $request->id_postulante, $request->proceso);
        }

        $this->response['tipo'] = 'success';
        $this->response['titulo'] = 'REGISTRO NUEVO';
        $this->response['mensaje'] = 'Proceso '.$apoderado->nombre.' creado con exito';
        $this->response['estado'] = true;
        $this->response['datos'] = $apoderado;
    } else {
        $temp = Apoderado::find($request->id);
        $apoderado = Apoderado::find($request->id);
        $apoderado->tipo_doc = $tipo_doc;
        $apoderado->nro_documento = $request->dni;
        $apoderado->paterno = $request->paterno; 
        $apoderado->materno = $request->materno;
        $apoderado->nombres = $request->nombres;
        $apoderado->tipo_apoderado = $request->tipo_apoderado;
        $apoderado->id_usuario = auth()->id();
        $apoderado->save();

        if($request->actualizar == 'si'){
          $this->savePasos($request->name, $request->nro, $request->avance, $request->id_postulante, $request->proceso);
        }
        if( $temp == $apoderado ) {
          $this->response['estado'] = false;
        }else 
        {
          $this->response['tipo'] = 'info';
          $this->response['titulo'] = '!REGISTRO MODIFICADO!';
          $this->response['mensaje'] = 'Datos del '.$apoderado->nombres.' actualizados';
          $this->response['estado'] = true;
          $this->response['datos'] = $apoderado;
        }

      }

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
      $this->response['tipo'] = 'success';
      $this->response['titulo'] = 'PASO REGISTRADO';
      $this->response['mensaje'] = 'Proceso '.$pasos->nombre.' creado con exito';
      $this->response['estado'] = true;
      $this->response['datos'] = $pasos;
      
      return response()->json($this->response, 200);
    }



}
