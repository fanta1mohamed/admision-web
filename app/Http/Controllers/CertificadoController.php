<?php

namespace App\Http\Controllers;
use App\Models\Certificado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use DB;

class CertificadoController extends Controller
{
    public function save(Request $request)
    {
        // Validar la entrada
        $request->validate([
            'dni' => 'required|string',
            'id_proceso' => 'required|integer',
            'file' => 'nullable|file|mimes:jpg,png,pdf,doc,docx|max:4096',
            'tipo' => 'required|integer',
        ]);

        $dni = $request->dni;
        $id_proceso = $request->id_proceso;
        $rutaCarpeta = 'documentos/' . $id_proceso . '/biometrico/certificados/';

        // Asegúrate de que el directorio de destino exista
        if (!File::exists(public_path($rutaCarpeta))) {
            File::makeDirectory(public_path($rutaCarpeta), 0755, true);
        }

        if (!$request->id) {
            // Caso de crear un nuevo registro
            if ($request->hasFile('file')) {
                // Guardar el archivo en la ruta especificada
                $file = $request->file('file');
                $fileName = $file->getClientOriginalName();
                $filePath = $rutaCarpeta . $dni.'.pdf';
                $file->move(public_path($rutaCarpeta), $dni.'.pdf');
                $certificado = new Certificado();
                $certificado->observacion = $request->observacion;
                $certificado->id_tipo = $request->tipo;
                $certificado->url = $filePath;
                $certificado->save();

                return response()->json(['success' => 'File uploaded successfully'], 200);
            } else {
                return response()->json(['error' => 'No file found'], 400);
            }
        } else {
            // Caso de actualización de un registro existente
            $titulo = Titulo::find($request->id);

            if (!$titulo) {
                return response()->json(['error' => 'Title not found'], 404);
            }

            $titulo->denominacion = $request->descripcion;
            $titulo->institucion = $request->institucion;
            $titulo->fec_expedicion = $request->fec_expedicion;
            $titulo->reg_sunedu = $request->reg_sunedu;
            $titulo->id_tipo = $request->tipo;

            if ($request->hasFile('file')) {
                // Obtener el archivo subido
                $file = $request->file('file');
                $fileName = $file->getClientOriginalName();
                $filePath = $rutaCarpeta . $fileName;

                // Eliminar el archivo anterior si existe
                if (File::exists(public_path($titulo->url))) {
                    File::delete(public_path($titulo->url));
                }

                // Mover el nuevo archivo a la carpeta de destino
                $file->move(public_path($rutaCarpeta), $fileName);
                $titulo->url = $filePath;
            }

            $titulo->id_usuario = auth()->id();
            $titulo->save();

            return response()->json(['message' => 'Title updated successfully'], 200);
        }
    }


}
