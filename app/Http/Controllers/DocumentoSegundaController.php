<?php

namespace App\Http\Controllers;
use App\Models\DocumentoSegunda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use DB;

class DocumentoSegundaController extends Controller
{
    public function save(Request $request)
    {
        $request->validate([
            'dni' => 'required|string',
            #'file' => 'nullable|file|mimes:pdf|max:4096',
            'tipo' => 'required|integer'
        ]);
    
        $dni = $request->dni;
        $id_proceso = $request->id_proceso;
        $rutaCarpeta = 'documentos/' . $id_proceso . '/preinscripcion/'.$request->tipo.'/';
    
        if (!File::exists(public_path($rutaCarpeta))) {
            if (!File::makeDirectory(public_path($rutaCarpeta), 0755, true)) {
                return response()->json(['error' => 'Unable to create directory'], 500);
            }
        }
    
        if (!$request->has('id')) {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filePath = $rutaCarpeta . $dni . '.pdf';
                $file->move(public_path($rutaCarpeta), $dni . '.pdf');
    
                $dni = new DocumentoSegunda();
                $dni->observacion = $request->observacion;
                $dni->dni = $request->dni;
                $dni->id_tipo = $request->tipo;
                $dni->id_proceso = $request->id_proceso;
                $dni->url = $filePath;
                $dni->save();

                return response()->json(['message' => 'Registrado con exito'], 200);
             } else {
                return response()->json(['error' => 'No file found'], 400);
            }
        } else {
            $dni = DocumentoSegunda::find($request->id);
            if (!$dni) {
                return response()->json(['error' => 'Record not found'], 404);
            }
    
            $dni->observacion = $request->observacion;
            $dni->dni = $request->dni;
            $dni->id_tipo = $request->tipo;
    
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = $file->getClientOriginalName();
                $filePath = $rutaCarpeta . $fileName;
                $file->move(public_path($rutaCarpeta), $fileName);
                $dni->url = $filePath;
            }
    
            $dni->id_usuario = auth()->id();
            $saved = $dni->save();
    
            if ($saved) {
                return response()->json(['message' => 'Record updated successfully'], 200);
            } else {
                return response()->json(['error' => 'Failed to update the record'], 500);
            }
        }
    }

    public function getTitulos( Request $request){
        $res = DB::select("SELECT crt.id, crt.dni, crt.id_tipo, crt.observacion, crt.url, crt.estado FROM documentos_segundas crt
        WHERE dni = ". $request->dni . " AND crt.id_tipo IN (7)");
    
        $this->response['estado'] = !empty($res);
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);
    }

    public function getFotos( Request $request){
        $res = DB::select("SELECT crt.id, crt.dni, crt.id_tipo, crt.observacion, crt.url, crt.estado FROM documentos_segundas crt
        WHERE dni = ". $request->dni . " AND crt.id_tipo IN (8)");
    
        $this->response['estado'] = !empty($res);
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);
    }


    public function delete($id) {
         
        $file = DocumentoSegunda::find($id);

        if (!$file) {
            return response()->json(['error' => 'File not found'], 404);
        }
        if (Storage::disk('public')->exists($file->url)) {
            Storage::disk('public')->delete($file->url);
        }
        $file->delete();

        $this->response['estado'] = true;
        return response()->json($this->response, 200);
    
    }


    public function verificarDocumentos($dni, $proceso){
        $existing = \App\Models\DocumentoSegunda::where('dni', '70757831')
            ->where('id_proceso', 16)
            ->whereIn('id_tipo', [7, 8])
            ->distinct()
            ->pluck('id_tipo')
            ->toArray();

        $expected = [7, 8];
        $missing = array_diff($expected, $existing);

        if (empty($missing)) {
            return response()->json(['estado' => true]);
        } else {
            return response()->json([
                'estado' => false,
                'missing' => array_values($missing) 
            ]);
        }
    }

}
