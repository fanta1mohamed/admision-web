<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sancionado;

class SancionadoController extends Controller
{
    public function getSancionado($dni, $proceso) {

        $sancionado = Sancionado::where('dni', $dni)->where('id_proceso', $proceso)->first();

        if($sancionado){ 
            $this->response['estado'] = true;
            $this->response['datos'] = $sancionado;
            return response()->json($this->response, 200);
          } else {
            $this->response['estado'] = false;
            return response()->json($this->response, 201);
          }

    }

}
