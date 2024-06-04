<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Models\Documento;
use App\Models\Paso;
use App\Models\Cambio;
use Illuminate\Support\Str;

class DocumentoController extends Controller {

  public function getCertificadosRevision(Request $request)
  {
    $query_where = [];
    
    $res = Documento::select(
      'documento.id as id', 'documento.codigo as cod', 'tipo_documento.nombre as tipo_certificado',
      'documento.verificado', 'documento.url',
      'documento.observacion as tipo',
      'postulante.nro_doc as dni', 'postulante.primer_apellido AS paterno', 
      'postulante.segundo_apellido AS materno', 'postulante.nombres'
    )
    ->leftjoin('tipo_documento','documento.id_tipo_documento','tipo_documento.id')
    ->leftjoin('postulante','postulante.id','documento.id_postulante')
    // ->join('pre_inscripcion','pre_inscripcion.id_postulante', 'postulante.id')
    ->where($query_where)
    ->where('tipo_documento.id','=', 1)
    ->where(function ($query) use ($request) {
      return $query
          ->orWhere('postulante.nro_doc', 'LIKE', '%' . $request->term . '%')
          ->orWhere('postulante.nombres', 'LIKE', '%' . $request->term . '%')
          ->orWhere('postulante.primer_apellido', 'LIKE', '%' . $request->term . '%')
          ->orWhere('postulante.segundo_apellido', 'LIKE', '%' . $request->term . '%')
          ->orWhere('tipo_documento.nombre', 'LIKE', '%' . $request->term . '%');
  })->paginate($request->paginasize);

    $this->response['estado'] = true;
    $this->response['datos'] = $res;
    return response()->json($this->response, 200);
  }

  public function cambiarEstado( Request $request) {
    $documento = Documento::find($request->id);
    $copy = $documento;
    $this->cambio('documentos','verificado',$copy->verificado, $request->estado, $documento->id);
    $documento->verificado = $request->estado;
    $documento->id_usuario = auth()->id();
    $documento->save();

    $this->response['titulo'] = '!CERTIFICADO ACTUALIZADO!';
    $this->response['mensaje'] = 'Estado Cambiado con exito';
    $this->response['estado'] = true;
    $this->response['datos'] = $documento;
    return response()->json($this->response, 200);
  }
  
  public function getDocumentosAdmin(Request $request)
  {
    $query_where = [];
    $res = Documento::select( 'documento.id','documento.codigo','documento.nombre','postulante.nro_doc as dni',
    DB::raw('CONCAT(postulante.nombres," ",postulante.primer_apellido," ",postulante.segundo_apellido) as postulante'),
    'documento.estado', 'documento.verificado', 'tipo_documento.nombre as tipo', 'documento.observacion')
    ->join('postulante','postulante.id','documento.id_postulante')
    ->leftjoin('tipo_documento','documento.id_tipo_documento','tipo_documento.id')
    ->where($query_where)
    ->where(function ($query) use ($request) {
        return $query
          ->orWhere('postulante.nro_doc', 'LIKE', '%' . $request->term . '%')
          ->orWhere('postulante.nombres', 'LIKE', '%' . $request->term . '%')
          ->orWhere('documento.codigo', 'LIKE', '%' . $request->term . '%')
          ->orWhere('documento.nombre', 'LIKE', '%' . $request->term . '%');
    })
    ->paginate(20);

    $this->response['estado'] = true;
    $this->response['datos'] = $res;
    return response()->json($this->response, 200);
  }

  public function saveDocumentoAdmin(Request $request ) {

      $documento = null;
      if (!$request->id) {
          $documento = Documento::create([
              'codigo' => $request->codigo,
              'nombre' => $request->nombre,
              'id_usuario' => auth()->id()
          ]);
          $this->response['titulo'] = 'REGISTRO NUEVO';
          $this->response['mensaje'] = 'Documento '.$documento->nombre.' CREADA CON EXITO';
          $this->response['estado'] = true;
          $this->response['datos'] = $documento;
      } else {

          $documento = Documento::find($request->id);
          $documento->nombre = $request->nombre;
          $documento->codigo = $request->codigo;
          $documento->observacion = $request->observacion;
          $documento->id_usuario = auth()->id();
          $documento->save();

          $this->response['titulo'] = '!REGISTRO MODIFICADO!';
          $this->response['mensaje'] = 'DOCUMENTO '.$documento->nombre.' MODIFICADO';
          $this->response['estado'] = true;
          $this->response['datos'] = $documento;
      }

    return response()->json($this->response, 200);
  }


  private function cambio($tabla, $campo, $anterior, $nuevo, $id_registro){
    $cambio = Cambio::create([
      'tabla' => $tabla,
      'campo' => $campo,
      'valor_anterior' => $anterior,
      'valor_nuevo' => $nuevo,
      'id_registro' => $id_registro,
      'id_usuario' => auth()->id()
    ]);
  }

  public function getCodigoDNI($dni){

    $fechas = ['2024-03-25', '2024-03-26', '2024-03-27', '2024-03-28','2024-04-01','2024-04-02'];

    $res = Documento::select('documento.codigo as label', 'documento.codigo as value')
        ->join('postulante', 'postulante.id', '=', 'documento.id_postulante')
        ->whereIn(DB::raw('DATE(documento.created_at)'), $fechas) // Usar whereIn para verificar si la fecha está en el array
        ->where('postulante.nro_doc', $dni)
        ->distinct()
        ->get();
      
    if(count($res) == 0 ){
      $this->response['estado'] = false;
    }else{
      $this->response['estado'] = true;
      $this->response['datos'] = $res;
    }
    
    return response()->json($this->response, 200);

  }


}
