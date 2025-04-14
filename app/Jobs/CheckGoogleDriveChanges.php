<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Google\Client;
use Google\Service\Drive;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\GoogleDriveEvent;


class CheckGoogleDriveChanges implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        foreach (User::whereNotNull('google_drive_token')->get() as $user) {
            $client = new Client();
            $client->setAccessToken($user->google_drive_token);
            $service = new Drive($client);

            // Obtener cambios desde el último token guardado
            $changes = $service->changes->listChanges($user->google_drive_page_token);

            foreach ($changes->getChanges() as $change) {
                $file = $change->getFile();

                if ($file && $file->getMimeType() === 'application/vnd.google-apps.folder') {
                    // Es una carpeta nueva
                    GoogleDriveEvent::create([
                        'user_id' => $user->id,
                        'event_type' => 'folder_created',
                        'file_id' => $file->getId(),
                        'file_name' => $file->getName(),
                    ]);
                } elseif ($file) {
                    // Es un archivo nuevo
                    GoogleDriveEvent::create([
                        'user_id' => $user->id,
                        'event_type' => 'file_uploaded',
                        'file_id' => $file->getId(),
                        'file_name' => $file->getName(),
                    ]);
                }
            }

            // Actualizar el token de cambios
            $user->update(['google_drive_page_token' => $changes->getNewStartPageToken()]);
        }
    }
}
