<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Notifications\ZipReadyNotification;
use App\Models\User;
use ZipArchive;

class GenerateZipForDownload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userIdProceso;
    protected $notificationUserId;

    public function __construct($userIdProceso, $notificationUserId)
    {
        $this->userIdProceso = $userIdProceso;
        $this->notificationUserId = $notificationUserId;
    }

    public function handle()
    {
        $folder = public_path('documentos/' . $this->userIdProceso.'/inscripciones/huellas');
        $filename = 'documentos_' . $this->userIdProceso . '.zip';
        $zipPath = storage_path('app/zips/' . $filename);

        // Crear directorio si no existe
        if (!File::exists(dirname($zipPath))) {
            File::makeDirectory(dirname($zipPath), 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $files = File::allFiles($folder);

            foreach ($files as $file) {
                $relativePath = ltrim(str_replace($folder, '', $file->getPathname()), '/\\');
                $zip->addFile($file->getRealPath(), $relativePath);
            }

            $zip->close();

            $user = User::find($this->notificationUserId);
            $user->notify(new ZipReadyNotification($filename));

            return true;
        }

        throw new \Exception('No se pudo crear el archivo ZIP');
    }
}
