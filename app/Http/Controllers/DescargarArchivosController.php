<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\GenerateZipForDownload;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use App\Models\User;

class DescargarArchivosController extends Controller
{
    public function prepareDownload()
    {
        $userIdProceso = auth()->user()->id_proceso;
        $notificationUserId = auth()->id();

        // Despachar el job a la cola
        GenerateZipForDownload::dispatch($userIdProceso, $notificationUserId);

        return response()->json([
            'message' => 'Estamos preparando tu archivo. Te notificaremos cuando esté listo para descargar.',
            'status_url' => route('download.status')
        ]);
    }

    public function downloadStatus()
    {
        return response()->json([
            'status' => 'processing',
            'message' => 'El archivo aún se está preparando'
        ]);
    }

    public function downloadPreparedZip($filename)
    {
        $filePath = storage_path('app/zips/' . $filename);

        // Verificar que el archivo pertenece al usuario
        $userIdProceso = auth()->user()->id_proceso;
        if (!str_contains($filename, 'documentos_' . $userIdProceso)) {
            abort(403, 'No tienes permiso para descargar este archivo');
        }

        if (!file_exists($filePath)) {
            abort(404, 'El archivo no existe o ha expirado');
        }

        return response()->download($filePath, $filename, [
            'Content-Type' => 'application/zip',
        ])->deleteFileAfterSend(true);
    }
}
