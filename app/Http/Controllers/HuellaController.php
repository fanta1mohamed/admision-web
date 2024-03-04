<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HuellaController extends Controller
{
    
    public function upload(Request $request) {
        try {
            $dni = $request->input('dni');
            $etapa = $request->input('etapa');
            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');

                $rutaCarpeta = "";
                if($etapa == 'inscripcion'){  $rutaCarpeta = public_path('documentos/8/inscripciones/huellas/'); }
                if($etapa == 'biometrico'){  $rutaCarpeta = public_path('documentos/8/biometrico/huellas/'); }

                if (!file_exists($rutaCarpeta)) {
                    if (!mkdir($rutaCarpeta, 0777, true)) {
                        return response()->json(['error' => 'No se pudo crear la carpeta para guardar la imagen'], 500);
                    }
                }

                $imageName = $dni;
                $imagen->move($rutaCarpeta, $imageName);
    
                return response()->json(['image_path' => $dni]);
            } else {
                return response()->json(['error' => 'No se proporcionó ningún archivo de imagen'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al cargar la imagen: ' . $e->getMessage()], 500);
        }
    }


}
