<?php

namespace App\Http\Controllers;
use App\Models\PagoBanco;
use Illuminate\Http\Request;

class PagoBancoController extends Controller
{
    public function getComprobantesDNI(Request $request){

        $res = PagoBanco::select(
          'comprobante.id', 'comprobante.codigo', 'comprobante.monto', 'nro_operacion','comprobante.ndoc_postulante', 
          'comprobante.fecha', 'postulante.nombres', 'postulante.primer_apellido', 'postulante.segundo_apellido',
          'comprobante.verificado'
        )
        ->join('postulante','postulante.nro_doc','comprobante.ndoc_postulante')
        ->where('comprobante.ndoc_postulante','=',$request->dni)
        ->where('comprobante.fecha','>','2023-07-01')
        ->get(); 
    
        $this->response['estado'] = true;
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);
    
      }
}
