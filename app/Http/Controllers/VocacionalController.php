<?php

namespace App\Http\Controllers;
use App\Models\DetalleExamenVocacional;
use Illuminate\Http\Request;
use DB;

class VocacionalController extends Controller
{
    public function participantesVocacional(Request $request)
    {

      $query_where = [];
  
      $res = DetalleExamenVocacional::selectRaw('COUNT(*) AS cant, postulante.nro_doc, UPPER(CONCAT(postulante.nombres," ",postulante.primer_apellido," ",postulante.segundo_apellido)) AS nombres')
        ->join('postulante', 'postulante.id', '=', 'detalle_examen_vocacional.id_postulante')
        ->where($query_where)
        ->where(function ($query) use ($request) {
            return $query
                ->orWhere('nro_doc', 'LIKE', '%' . $request->term . '%')
                ->orWhere('nombres', 'LIKE', '%' . $request->term . '%')
                ->orWhere(DB::raw("CONCAT(postulante.nombres, ' ', postulante.primer_apellido, ' ', postulante.segundo_apellido)"), 'LIKE', '%' . $request->term . '%')
                ->orWhere(DB::raw("CONCAT(postulante.primer_apellido, ' ', postulante.segundo_apellido, ' ', postulante.nombres)"), 'LIKE', '%' . $request->term . '%');
        })
        ->groupBy('detalle_examen_vocacional.id_postulante')
        ->paginate(20);


        $this->response['estado'] = true;
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);

    }
}
