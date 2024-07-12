<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Postulante;

class ValidacionController extends Controller {

    public function existeCelular(Request $request) {
        $postulante = Postulante::where('celular', $request->celular)->first();
        if (!$postulante) {return response()->json(false, 200); }   
        $nro_doc_correcto = $postulante->dni == $request->dni;
        return response()->json($nro_doc_correcto, 200);
    }

    public function existeCorreo(Request $request) {
        $postulante = Postulante::where('email', $request->email)->first();
        if (!$postulante) {return response()->json(false, 200); }   
        $nro_doc_correcto = $postulante->dni == $request->dni;
        return response()->json($nro_doc_correcto, 200);
    }

    
}
