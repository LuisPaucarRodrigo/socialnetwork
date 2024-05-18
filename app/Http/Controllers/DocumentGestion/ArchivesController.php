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
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class ArchivesController extends Controller
{
    protected $main_directory;

    public function __construct()
    {
        $this->main_directory = 'nueva carpeta';
    }

    public function show(Folder $folder) {

        $folder->load('areas', 'folder_areas');
        $archives = Archive::where('folder_id', $folder->id)->with('user')->orderByDesc('version')->paginate(10);
        $user = Auth::user();
        $userHasPermission = $folder->folder_areas->where('area_id', $user->area_id)->where('create', true)->isNotEmpty();

        return Inertia::render('DocumentGestion/ArchivesGestion/Archives', [
            'archives' => $archives,
            'folder' => $folder,
            'userHasPermission' => $userHasPermission
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
                $archiveUsers = ArchiveUser::where('archive_id', $archive)->get();
                // Elimina los registros en la tabla `archive_user`
                if($archiveUsers){
                    foreach ($archiveUsers as $archiveUser) {
                        $archiveUser->delete();
                    }
                }
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

    public function observationsPerArchive($folder, Archive $archive)
    {
        $archiveUsers = ArchiveUser::where('archive_id', $archive->id)->whereNotNull('observation')->with('archive.user', 'user')->paginate(10);
        $user = Auth::user();
        $observation = ArchiveUser::where('archive_id', $archive->id)->where('user_id', $user->id)->first();
        if ($observation){
            if ($observation->observation){
                $canObservate = false;
            }else{
                $canObservate = true;
            }
        }else{
            $canObservate = false;
        }

        return Inertia::render('DocumentGestion/ArchivesGestion/Observations', [
            'archive' => $archive,
            'folder_id' => $folder,
            'canObservate' => $canObservate,
            'archiveUsers' => $archiveUsers,
        ]);
    }

    public function saveObservation(Request $request, $archive)
    {
        $request->validate([
            'state' => 'required',
            'observations' => 'required',
            'user_id' => 'required'
        ]);

        $observation = ArchiveUser::where('archive_id', $archive)->where('user_id', $request->user_id)->first();
        if ($observation) {
            $observation->update([
                'observation' => $request->observations,
                'state' => $request->state,
                'evaluation_date' => Carbon::now()
            ]);
        }
    }

    public function upgradeArchive(Archive $archive)
    {   
        $archive->load('folder');

        $archive->approve_status = false;
        $archive->save();

        //versionAndExtension
        $newVersion = floor($archive->version) + 1.0;
        $extension = pathinfo($archive->path, PATHINFO_EXTENSION);

        //name
        $nameParts = explode('-', $archive->name);
        $tempName = $nameParts[0];

        $newArchivePath = $archive->folder->path . '/' . $tempName . '-' . $newVersion . '.' . $extension;

        $newArchive = Archive::create([
            'name' => $tempName . '-' . $newVersion,
            'folder_id' => $archive->folder_id,
            'user_id' => $archive->user_id,
            'path' => $newArchivePath,
            'version' => $newVersion,
        ]);

        if (file_exists(public_path($archive->path))) {
            copy(public_path($archive->path), public_path($newArchivePath));
        }
    }

}
