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
                if($etapa == 'inscripcion'){ 
                     $rutaCarpeta = public_path('documentos/9/inscripciones/huellas/'); 
                } else {  $rutaCarpeta = public_path('documentos/9/control_biometrico/huellas/'); 
                }

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


    public function uploadcepre(Request $request) {
        try {
            $dni = $request->input('dni');
            $etapa = $request->input('etapa');
            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');

                $rutaCarpeta = "";
                if($etapa == 'inscripcion'){ 
                     $rutaCarpeta = public_path('documentos/10/inscripciones/huellas/'); 
                } else {  $rutaCarpeta = public_path('documentos/10/control_biometrico/huellas/'); 
                }

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


    public function uploadAzangaro(Request $request) {
        try {
            $dni = $request->input('dni');
            $etapa = $request->input('etapa');
            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');

                $rutaCarpeta = "";
                if($etapa == 'inscripcion'){ 
                     $rutaCarpeta = public_path('documentos/12/inscripciones/huellas/'); 
                } else {  $rutaCarpeta = public_path('documentos/12/control_biometrico/huellas/'); 
                }

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


    public function uploadJuli(Request $request) {
        try {
            $dni = $request->input('dni');
            $etapa = $request->input('etapa');
            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');

                $rutaCarpeta = "";
                if($etapa == 'inscripcion'){ 
                     $rutaCarpeta = public_path('documentos/11/inscripciones/huellas/'); 
                } else {  $rutaCarpeta = public_path('documentos/11/control_biometrico/huellas/'); 
                }

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


    // dni
    // id_proceso
    // etapa
    // Face
    // LFinger
    // RFinger
    public function uploadFotos(Request $request) {
        try {
            $id_proceso = $request->input('id_proceso');
            $dni = $request->input('dni');
            $etapa = $request->input('etapa');

            if ($request->hasFile('Face')) {
                $foto = $request->file('Face');
                $h_izquierda = $request->file('LFinger');
                $h_derecha = $request->file('RFinger');

                $rutaCarpeta = public_path("documentos/$id_proceso/$etapa/huellas/"); 
                $rutaCarpetaFoto = public_path("documentos/$id_proceso/$etapa/fotos/"); 

                if (!file_exists($rutaCarpeta)) {
                    if (!mkdir($rutaCarpeta, 0777, true)) {
                        return response()->json(['error' => 'No se pudo crear la carpeta para guardar la imagen'], 500);
                    }
                }
                if (!file_exists($rutaCarpetaFoto)) {
                    if (!mkdir($rutaCarpeta, 0777, true)) {
                        return response()->json(['error' => 'No se pudo crear la carpeta para guardar la imagen'], 500);
                    }
                }

                $fotoName = $dni;
                $hIzqName = $dni+"x";
                $hDerName = $dni;
                $foto->move($rutaCarpetaFoto, $fotoName);
                $h_izquierda->move($rutaCarpetaFoto, $hIzqName);
                $h_derecha->move($rutaCarpetaFoto, $hDerName);
    
                return response()->json(['foto_path' => $rutaCarpetaFoto+$dni+".jpg" ]);
            } else {
                return response()->json(['error' => 'No se proporcionó ningún archivo de imagen'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al cargar la imagen: ' . $e->getMessage()], 500);
        }
    }





}
