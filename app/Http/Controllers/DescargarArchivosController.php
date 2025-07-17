<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;


class DescargarArchivosController extends Controller
{

    public function downloadZip()
{
    $folder = public_path('documentos/' . auth()->user()->id_proceso);
    $filename = 'documentos_' . auth()->user()->id_proceso . '.zip';

    $response = new StreamedResponse(function () use ($folder) {
        $zip = new \ZipStream\ZipStream(null, [
            'outputName' => 'archivos.zip',
            'sendHttpHeaders' => false,
        ]);

        $files = File::allFiles($folder);

        foreach ($files as $file) {
            $relativeName = str_replace($folder . '/', '', $file->getRealPath());
            $zip->addFileFromPath($relativeName, $file->getRealPath());
        }

        $zip->finish();
    });

    $response->headers->set('Content-Type', 'application/octet-stream');
    $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

    return $response;
}


}
