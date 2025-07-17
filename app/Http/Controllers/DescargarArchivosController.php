<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ZipArchive;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;


class DescargarArchivosController extends Controller
{

    public function downloadZip()
    {
          $folder = public_path('documentos/' . auth()->user()->id_proceso.'/inscripciones');
          $zipFile = storage_path('app/documentos.zip');

          $zip = new ZipArchive;

          if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
              $files = File::allFiles($folder);

              foreach ($files as $file) {
                  $relativeName = str_replace($folder . '/', '', $file->getRealPath());
                  $zip->addFile($file->getRealPath(), $relativeName);
              }

              $zip->close();

              $response = new StreamedResponse(function () use ($zipFile) {
                  readfile($zipFile);
              });

              $filename = 'documentos_' . date('Ymd_His') . '.zip';

              $response->headers->set('Content-Type', 'application/zip');
              $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
              $response->headers->set('Content-Length', filesize($zipFile));

              // Eliminar archivo después de enviar
              $response->headers->set('X-Delete-File-After-Send', 'true');

              // Usar terminación del kernel para eliminar después
              app()->terminating(function () use ($zipFile) {
                  @unlink($zipFile);
              });

              return $response;
          }

          return response()->json(['error' => 'No se pudo crear el archivo ZIP.'], 500);

    }


}
