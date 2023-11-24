<?php

namespace App\Http\Controllers;

use App\Models\Carpeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarpetaController extends Controller
{
 

    public function crearCarpeta(Request $request)
    {
        // Validar los datos del formulario, si es necesario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'carpeta_padre_id' => 'nullable|exists:carpetas,id',
        ]);
        $carpetaPadre = Carpeta::find($request->input('carpeta_padre_id'));
        $url = $carpetaPadre ? $carpetaPadre->url . '/' . $request->input('carpeta_padre_id') : null;
    
        $carpeta = Carpeta::create([
            'nombre' => $request->input('nombre'),
            'carpeta_padre_id' => $request->input('carpeta_padre_id'),
            'url' => $url,
        ]);

        $directorio = 'carpetas/' . $url."/". $carpeta->id;
        Storage::disk('local')->makeDirectory($directorio);

    }

    public function verContenidoCarpeta($carpetaId)
    {
        $carpeta = Carpeta::findOrFail($carpetaId);

        $carpetas = Carpeta::where('carpeta_padre_id', $carpeta->id)->get();

        $rutaCarpeta = $this->obtenerRutaCarpeta($carpeta);
    
        $archivos = Storage::disk('local')->allFiles("carpetas/".$rutaCarpeta);

        $this->response['estado'] = true;
        $this->response['carpeta'] = $carpeta;
        $this->response['carpetas'] = $carpetas;
        $this->response['archivos'] = $archivos;
        $this->response['ruta'] = $rutaCarpeta;
        return response()->json($this->response, 200);

    }

    protected function obtenerRutaCarpeta($carpeta)
    {
        $urls = explode('/', trim($carpeta->url, '/'));
        $urls[] = $carpeta->id;

        return implode('/', $urls);
    }

}
