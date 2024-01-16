<?php

namespace App\Http\Controllers;
use App\Models\PagoBanco;
use Illuminate\Http\Request;
use DB;

class PagoBancoController extends Controller
{
  public function getComprobantesDNI(Request $request){
    $res = PagoBanco::select([
        'banco_pagos.secuencia',
        \DB::raw('SUBSTRING(banco_pagos.num_doc, 8, 8) AS dni'),
        \DB::raw('SUBSTRING(banco_pagos.concepto, 7, 2) AS concepto'),
        'banco_pagos.imp_pag', 'banco_pagos.fch_pag', 'banco_pagos.nom_cli', 
        'banco_pagos.estado', 'banco_pagos.fecha_usado', 'banco_pagos.id_usuario', 'banco_pagos.id_proceso',
        'procesos.nombre as proceso', 'procesos.id_modalidad_proceso'

    ])
    ->leftjoin('procesos','procesos.id','banco_pagos.id_proceso')
    ->where(\DB::raw('SUBSTRING(num_doc, 8, 8)'), '=', $request->dni)
    ->where('banco_pagos.fch_pag', '>', '2023-07-01')
    ->get(); 

    $this->response['estado'] = true;
    $this->response['datos'] = $res;
    return response()->json($this->response, 200);
  }

  public function verificarComprobanteProceso(Request $request){
;
    $comprobante = PagoBanco::where('fch_pag', $request['comp']['fch_pag'])
    ->where('secuencia', $request['comp']['secuencia'])
    ->first();

    if($comprobante->id_usuario == auth()->id()){
      if( $request['comp']['estado'] == 1 ){
        $comprobante->estado = 2;
        $comprobante->fecha_usado = null;
        $comprobante->id_proceso =  null;
        $comprobante->id_usuario = auth()->id();
      }else{
        if( $request['comp']['estado'] == 2 ){
          $comprobante->estado = 1;
          $comprobante->fecha_usado = date('Y-m-d H:s:m');
          $comprobante->id_proceso = auth()->user()->id_proceso;
          $comprobante->id_usuario = auth()->id();
        }
      }
      
      $this->response['type'] = 'success';
      $this->response['titulo'] = '!COMPROBANTE ACTUALIZADO!';
      $this->response['mensaje'] = '';
      $this->response['estado'] = true;
      $this->response['datos'] = $comprobante;
    }else{
      $this->response['type'] = 'error';
      $this->response['titulo'] = '!ERROR AL ACTUALIZAR!';
      $this->response['mensaje'] = 'OTRO USUARIO YA MODIFICÓ ESTE COMPROBANTE';
      $this->response['estado'] = true;
      $this->response['datos'] = $comprobante;
    }

    $comprobante->save();




    return response()->json($this->response, 200);
  }


}
