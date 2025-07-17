<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipStream\ZipStream;
use ZipArchive;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;


class DescargarArchivosController extends Controller
{

  public function downloadZip($carpeta)
{
    $folder = public_path('documentos/' . auth()->user()->id_proceso);
    $filename = 'documentos_' . auth()->user()->id_proceso . '.zip';

    return new StreamedResponse(function () use ($folder) {
        $options = new \ZipStream\Option\Archive();
        $options->setSendHttpHeaders(true);

        $zip = new ZipStream(null, $options);
        $files = File::allFiles($folder);

        foreach ($files as $file) {
            $relativePath = ltrim(str_replace($folder, '', $file->getPathname()), '/\\');
            $zip->addFileFromPath($relativePath, $file->getRealPath());
        }

        $zip->finish();
    }, 200, [
        'Content-Type' => 'application/octet-stream',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ]);
}


}
