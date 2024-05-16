<?php

namespace App\Http\Controllers\DocumentGestion;

use App\Http\Controllers\Controller;
use App\Models\Archive;
use App\Models\Area;
use App\Models\ArchiveUser;
use App\Models\User;
use App\Models\Folder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class ArchivesController extends Controller
{
    protected $main_directory;

    public function __construct()
    {
        $this->main_directory = 'nueva carpeta';
    }

    public function show(Folder $folder) {

        $folder->load('areas');
        $archives = Archive::where('folder_id', $folder->id)->with('user')->paginate(10);
    
        $areaIds = $folder->areas->pluck('id')->toArray();
        $users = User::whereIn('area_id', $areaIds)->get();

        return Inertia::render('DocumentGestion/ArchivesGestion/Archives', [
            'archives' => $archives,
            'folder' => $folder,
            'users' => $users
        ]);
    }

    public function create(Request $request)
    {
        $folder = Folder::find($request->folder_id);

        $request->validate([
            'archive' => 'required|mimes:' . $folder->archive_type,
            'folder_id' => 'required',
            'user_id' => 'required'
        ]);

        $lastArchive = Archive::where('folder_id', $request->folder_id)->orderBy('id', 'desc')->first();
        
        if ($lastArchive) {
            $newVersion = $lastArchive->version + 0.01;
        } else {
            $newVersion = 0.01;
        }

        $archive = Archive::create([
            'name' => $folder->name . '-' . $newVersion,
            'folder_id' => $request->folder_id,
            'user_id' => $request->user_id,
            'path' => $folder->path,
            'version' => $newVersion,
        ]);

        ArchiveUser::create([
            'archive_id' => $archive->id,
            'user_id' => $archive->user_id
        ]);

        if ($request->hasFile('archive')) {
            $document = $request->file('archive');
            $extension = $document->getClientOriginalExtension();
            $documentName = $archive->name . '.' . $extension;
            $path = $document->move(public_path($folder->path), $documentName);
 
            $archive->path = $folder->path . '/' . $documentName;
            $archive->save();
        }
    }

    public function destroy($folder, $archive)
    {
        $findArchive = Archive::where('id', $archive)->with('folder')->first();
        if ($findArchive) {
            $filePath = $findArchive->path;
            $path = public_path($filePath);
            if (file_exists($path)) {
                unlink($path);
                $findArchive->delete();
            } else {
                dd("El archivo no existe en la ruta: $filePath");
            }
            return Redirect::route('archives.show', ['folder' => $folder]);
        }
        abort(404, 'Archivo no encontrado');
    }

    public function downloadArchive($folder, $archive)
    {
        // Utiliza first() en lugar de get() para obtener solo un modelo Archive
        $findArchive = Archive::where('id', $archive)->with('folder')->first();
        if ($findArchive) {
            // Accede a la propiedad path en el modelo Archive específico
            $filePath = $findArchive->path;
            $path = public_path($filePath);
            
            if (file_exists($path)) {
                // Utiliza el nombre y el tipo de archivo para generar el nombre del archivo a descargar
                $fileName = $findArchive->name . '.' . $findArchive->folder->archive_type;
                return response()->download($path, $fileName);
            }
            
            abort(404, 'Documento no encontrado');
        }
        
        abort(404, 'Archivo no encontrado');
    }


    public function assignUsers(Folder $folder, Archive $archive, Request $request)
    {
        $request->validate([
            'archive_id' => 'required',
        ]);
    
        $archiveId = $request->input('archive_id');
        $selectedUsers = $request->input('users');
    
        // Obtener los registros existentes en la tabla 'archive_user' para este archivo
        $existingRecords = ArchiveUser::where('archive_id', $archiveId)->get();
    
        // Iterar sobre los usuarios seleccionados
        foreach ($selectedUsers as $userId) {
            // Verificar si ya existe un registro para este usuario y archivo
            $existingRecord = $existingRecords->firstWhere('user_id', $userId);
            if ($existingRecord) {
                // Actualizar el estado del registro existente a true
                $existingRecord->status = true;
                $existingRecord->save();
            } else {
                // Si no existe un registro, crear uno nuevo con estado true
                ArchiveUser::create([
                    'archive_id' => $archiveId,
                    'user_id' => $userId,
                ]);
            }
        }
    
        // Actualizar el estado de los registros existentes que no están en la solicitud a false
        $existingRecords->each(function ($record) use ($selectedUsers) {
            if (!in_array($record->user_id, $selectedUsers)) {
                $record->status = false;
                $record->save();
            }
        });
    }    

}
