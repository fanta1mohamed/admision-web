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
      'documento.id as id', 'documento.codigo as cod', 'tipo_documento.nombre as tipo',
      'documento.verificado', 'documento.url',
      'postulante.nro_doc as dni', 'postulante.primer_apellido AS paterno', 
      'postulante.segundo_apellido AS materno', 'postulante.nombres'
    )
    ->join('tipo_documento','documento.id_tipo_documento','tipo_documento.id')
    ->join('postulante','postulante.id','documento.id_postulante')
    // ->join('pre_inscripcion','pre_inscripcion.id_postulante', 'postulante.id')
    ->where($query_where)
    ->where('tipo_documento.id','=', 1)
    ->where(function ($query) use ($request) {
      return $query
          ->orWhere('postulante.nro_doc', 'LIKE', '%' . $request->term . '%')
          ->orWhere('postulante.nombres', 'LIKE', '%' . $request->term . '%')
          ->orWhere('postulante.primer_apellido', 'LIKE', '%' . $request->term . '%');
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


}
