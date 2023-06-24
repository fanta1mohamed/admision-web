<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class FotoController extends Controller {

    public function guardarFotoInscripcion(Request $request)
    {
        if ($request->has('photo')) {
            $photoData = $request->input('photo');
            $photo = str_replace('data:image/png;base64,', '', $photoData);
            $photo = str_replace(' ', '+', $photo);
            $photoData = base64_decode($photo);
    
            // Genera un nombre único para la foto recortada
            $fileName = $request->dni . '.jpg';
            // Define la ruta donde se guardará la foto recortada
            $filePath = public_path('fotos/inscripcion/' . $fileName);
    
            // Guarda la foto recortada en el servidor
            file_put_contents($filePath, $photoData);
    
            // Realiza cualquier acción adicional después de guardar la foto recortada
            // ...
    
            return response()->json(['message' => 'Foto recortada guardada correctamente']);
        }
    
        return response()->json(['error' => 'No se proporcionó ninguna foto recortada'], 400);
    }

    public function guardarFotoBiometrico(Request $request)
    {
        if ($request->has('photo')) {
            $photoData = $request->input('photo');
            $photo = str_replace('data:image/png;base64,', '', $photoData);
            $photo = str_replace(' ', '+', $photo);
            $photoData = base64_decode($photo);
    
            // Genera un nombre único para la foto recortada
            $fileName = $request->dni . '.jpg';
            // Define la ruta donde se guardará la foto recortada
            $filePath = public_path('fotos/biometrico/' . $fileName);
    
            // Guarda la foto recortada en el servidor
            file_put_contents($filePath, $photoData);
    
            // Realiza cualquier acción adicional después de guardar la foto recortada
            // ...
    
            return response()->json(['message' => 'Foto recortada guardada correctamente']);
        }
    
        return response()->json(['error' => 'No se proporcionó ninguna foto recortada'], 400);
    }

}
