<?php

namespace App\Http\Controllers\DocumentGestion;

use App\Http\Controllers\Controller;
use App\Models\Archive;
use App\Models\Area;
use App\Models\ArchiveUser;
use App\Models\FolderArea;
use App\Models\User;
use App\Models\Folder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\AssignUsersRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ArchivesController extends Controller
{
    protected $main_directory;

    public function __construct()
    {
        $this->main_directory = 'CCIP';

    }

    public function show(Folder $folder) {
        $user = Auth::user();
        $findFolder = FolderArea::where('area_id', $user->area_id)
            ->where('folder_id', $folder->id)->first();
        if ($findFolder?->see_download) {
            $folder->load('areas', 'folder_areas');
            $archives = Archive::where('folder_id', $folder->id)->with('user', 'archive_users.user')->orderByDesc('version')->paginate(10);
            $user = Auth::user();
            $userHasPermission = $folder->folder_areas->where('area_id', $user->area_id)->where('create', true)->isNotEmpty();

            return Inertia::render('DocumentGestion/ArchivesGestion/Archives', [
                'archives' => $archives,
                'folder' => $folder,
                'userHasPermission' => $userHasPermission
            ]);
        }else{
            abort(403, 'No autorizado');
        }

    }

    public function create($folder, Request $request)
    {
        $user = Auth::user();
        $findFolder = FolderArea::where('area_id', $user->area_id)
            ->where('folder_id', $folder)->first();
        if ($findFolder?->create) {
            $folder = Folder::find($request->folder_id);
            $availability = $folder->availability;

            $lastArchive = Archive::where('folder_id', $request->folder_id)->orderBy('id', 'desc')->first();
            $firstDueDate = null;
            if ($lastArchive) {
                // Obtener el primer archive_user y su due_date
                $firstArchiveUser = $lastArchive->archive_users()->orderBy('id', 'asc')->first();
                if ($firstArchiveUser) {
                    $firstDueDate = $firstArchiveUser->due_date;
                }
            }

            // Configurar las reglas de validación condicionalmente
            $validationRules = [
                'archive' => 'required|mimes:' . $folder->format_type['laravel'],
                'folder_id' => 'required',
                'comment' => 'required',
                'user_id' => 'required',
            ];

            if ($availability == 3) {
                $validationRules['new_date'] = ['required', 'date', function ($attribute, $value, $fail) use ($firstDueDate) {
                    if ($firstDueDate && $value < $firstDueDate) {
                        $fail('La nueva fecha debe ser mayor o igual que la anterior fecha de evaluación: ' . $firstDueDate);
                    }
                }];
            } else {
                $validationRules['new_date'] = 'nullable|date';
            }

            $request->validate($validationRules);

            $lastArchive = Archive::where('folder_id', $request->folder_id)->orderBy('id', 'desc')->first();

            if ($lastArchive) {
                $newVersion = $lastArchive->version + 0.01;
            } else {
                $newVersion = 0.01;
            }

            if ($lastArchive?->observation_state == 3){
                $archive = Archive::create([
                    'name' => $folder->name . '-' . $newVersion,
                    'folder_id' => $request->folder_id,
                    'user_id' => $request->user_id,
                    'path' => $folder->path,
                    'comment' => $request->comment,
                    'version' => $newVersion,
                ]);
                foreach($lastArchive->archive_users as $archive_user){
                    ArchiveUser::create([
                        'user_id' => $archive_user->user_id,
                        'due_date' => $request->new_date,
                        'status' => true,
                        'archive_id' => $archive->id,
                    ]);
                }
            }else{
                $archive = Archive::create([
                    'name' => $folder->name . '-' . $newVersion,
                    'folder_id' => $request->folder_id,
                    'user_id' => $request->user_id,
                    'path' => $folder->path,
                    'comment' => $request->comment,
                    'version' => $newVersion,
                ]);
            }

            if ($request->hasFile('archive')) {
                $document = $request->file('archive');
                $extension = $document->getClientOriginalExtension();
                $documentName = $archive->name . '.' . $extension;
                if(isset($findFolder->archive_type) && $findFolder->archive_type == 'Imágenes'){
                    $path = Storage::putFileAs('public/' . $folder->path, $document, $documentName);
                }else{
                    $path = $document->move(public_path($folder->path), $documentName);
                }
                $archive->path = $folder->path . '/' . $documentName;
                $archive->save();
            }
        } else {
            abort(403, 'No autorizado');
        }

    }

    public function destroy($folder, $archive)
    {
        $user = Auth::user();
        if($user->role_id == 1){
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
        }else{
            abort(403,'No autorizado');
        }

    }

    public function downloadArchive($folder, $archive)
    {
        $user = Auth::user();
        $findFolder = FolderArea::where('area_id', $user->area_id)
            ->where('folder_id', $folder)->first();

        if ($findFolder?->see_download) {
            $findArchive = Archive::where('id', $archive)->with('folder')->first();
            if ($findArchive) {
                // Accede a la propiedad path en el modelo Archive específico
                $filePath = $findArchive->path;
                $path = public_path($filePath);

                if (file_exists($path)) {
                    ob_end_clean();
                    return response()->download($path);
                }

                abort(404, 'Documento no encontrado');
            }

            abort(404, 'Archivo no encontrado');
        } else {
            abort(403,'No autorizado');
        }
    }

    public function assignUsers(Folder $folder, Archive $archive, AssignUsersRequest $request)
    {
        $user = Auth::user();

        if ($user->role_id == 1 || $archive->user_id == $user->id) {
            $validatedData = $request->validated(); // Accede a los datos validados

            $archiveId = $validatedData['archive_id'];
            $selectedUsers = $validatedData['users'];
            $due_date = $validatedData['due_date'];

            // Convertir la lista de usuarios en un array de ids para la verificación
            $selectedUserIds = array_column($selectedUsers, 'id');

            // Obtener los registros existentes en la tabla 'archive_user' para este archivo
            $existingRecords = ArchiveUser::where('archive_id', $archiveId)->get();

            // Iterar sobre los usuarios seleccionados
            foreach ($selectedUserIds as $userId) {
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
                        'due_date' => $due_date,
                        'user_id' => $userId,
                    ]);
                }
            }

            // Actualizar el estado de los registros existentes que no están en la solicitud a false
            $existingRecords->each(function ($record) use ($selectedUserIds) {
                if (!in_array($record->user_id, $selectedUserIds)) {
                    $record->status = false;
                    $record->save();
                }
            });
        } else {
            abort(403, 'No autorizado');
        }
    }

    public function observationsPerArchive($folder, Archive $archive)
    {
        $user = Auth::user();
        $findArchiveUser = ArchiveUser::where('archive_id', $archive->id)
            ->where('user_id', $user->id)
            ->first();

        if ($findArchiveUser || $user->role_id == 1) {
            $archiveUsers = ArchiveUser::where('archive_id', $archive->id)->whereNotNull('evaluation_date')->with('archive.user', 'user')->paginate(10);
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
        }else{
            abort(403, 'No autorizado');
        }
    }

    public function saveObservation(Request $request, $archive)
    {
        $user = Auth::user();
        $findArchiveUser = ArchiveUser::where('archive_id', $archive)
            ->where('user_id', $user->id)
            ->first();

        if ($findArchiveUser && $findArchiveUser->state !== 'Pendiente'){
            abort(403, 'Ya no está autorizado');
        }

        if ($findArchiveUser) {
            $request->validate([
                'state' => 'required',
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
            $archiveFounded = Archive::find($archive);
            if ($archiveFounded->observation_state == 5 && $observation->state == 'Aprobado') {
                $archiveFounded->load('folder');

                $archiveFounded->approve_status = false;
                $archiveFounded->save();

                //versionAndExtension
                $newVersion = floor($archiveFounded->version) + 1.0;
                $extension = pathinfo($archiveFounded->path, PATHINFO_EXTENSION);

                //name
                $nameParts = explode('-', $archiveFounded->name);
                $tempName = $nameParts[0];

                $newArchivePath = $archiveFounded->folder->path . '/' . $tempName . '-' . $newVersion . '.' . $extension;

                $newArchive = Archive::create([
                    'name' => $tempName . '-' . $newVersion,
                    'folder_id' => $archiveFounded->folder_id,
                    'user_id' => $archiveFounded->user_id,
                    'comment' => $archiveFounded->comment,
                    'path' => $newArchivePath,
                    'version' => $newVersion,
                ]);

                if (file_exists(public_path($archiveFounded->path))) {
                    copy(public_path($archiveFounded->path), public_path($newArchivePath));
                }
            }
            return redirect()->back();
        }else{
            abort(403, 'No autorizado');
        }

    }

    public function getAlarmPerUser()
    {
        $user = Auth::user();

        $currentDate = Carbon::now()->subHours(5);

        $pending = ArchiveUser::where('user_id', $user->id)
            ->where('state', 'Pendiente')
            ->with('archive.folder')
            ->get();

        $pendingAlarms = $pending->filter(function ($archiveUser) {
            return $archiveUser->archive->observation_state == 2;
        })->sortByDesc('created_at');


        $alarmsLessThan3Days = [];
        $alarmsBetween4And7Days = [];

        foreach ($pendingAlarms as $alarm) {
            $dueDate = Carbon::createFromFormat('Y-m-d', $alarm->due_date);

            $differenceInDays = $currentDate->diffInDays($dueDate, false);

            if ($differenceInDays <= 3) {
                $alarmsLessThan3Days[] = $alarm;
            } elseif ($differenceInDays >= 4 && $differenceInDays <= 7) {
                $alarmsBetween4And7Days[] = $alarm;
            }
        }

        $responseData = [
            'alarms3' => $alarmsLessThan3Days,
            'alarms7' => $alarmsBetween4And7Days,
        ];

        return response()->json($responseData);
    }

    public function getPDF(Archive $archive)
    {
        $user = Auth::user();
        $previousArchives = Archive::where('folder_id', $archive->folder_id)
            ->where('id', '<', $archive->id)
            ->get();

        $previous = $previousArchives->filter(function ($archive) {
            return $archive->type === 'stable';
        })->sortByDesc('created_at');

        $archive->load('users', 'user');
        $pdf = Pdf::loadView('pdf.ArchivePDF', compact('archive', 'user', 'previous'));
        return $pdf->stream();
    }


}
