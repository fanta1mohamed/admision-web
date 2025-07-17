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

  public function downloadZip()
  {
      $folder = public_path('documentos/' . auth()->user()->id_proceso);
      $filename = 'documentos_' . auth()->user()->id_proceso . '.zip';

      // Verificar si la carpeta existe
      if (!File::exists($folder)) {
          abort(404, 'La carpeta no existe');
      }

      // Crear el archivo ZIP temporal
      $zipPath = storage_path('app/temp_zips/' . $filename);

      // Asegurarse de que el directorio temporal existe
      if (!File::exists(dirname($zipPath))) {
          File::makeDirectory(dirname($zipPath), 0755, true);
      }

      // Crear el archivo ZIP (esto puede tomar tiempo para 10GB)
      $zip = new ZipArchive();
      if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
          $files = File::allFiles($folder);

          foreach ($files as $file) {
              $relativePath = ltrim(str_replace($folder, '', $file->getPathname()), '/\\');
              $zip->addFile($file->getRealPath(), $relativePath);
          }

          $zip->close();
      } else {
          abort(500, 'No se pudo crear el archivo ZIP');
      }

      // Configurar headers para descarga chunked
      $headers = [
          'Content-Type' => 'application/zip',
          'Content-Disposition' => 'attachment; filename="' . $filename . '"',
          'Content-Length' => filesize($zipPath),
      ];

      // Usar respuesta chunked para archivos grandes
      $response = Response::stream(function () use ($zipPath) {
          $stream = fopen($zipPath, 'rb');
          while (!feof($stream)) {
              echo fread($stream, 1024 * 1024); // Leer en chunks de 1MB
              flush();
          }
          fclose($stream);

          // Eliminar el archivo temporal después de la descarga
          unlink($zipPath);
      }, 200, $headers);

      return $response;
  }


}
