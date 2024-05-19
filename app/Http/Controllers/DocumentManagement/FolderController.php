<?php

namespace App\Http\Controllers\DocumentManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentManagementRequest\FolderCreateRequest;
use App\Http\Requests\DocumentManagementRequest\FolderPermissionsRequest;
use App\Models\Archive;
use App\Models\Area;
use App\Models\Folder;
use App\Models\FolderArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use ZipArchive;

use Illuminate\Support\Facades\Response;

class FolderController extends Controller
{
    protected $main_directory;
    public function __construct()
    {
        $this->main_directory = 'CCIP';
    }



    public function folder_index($folder_id = null)
    {
        $folder = Folder::find($folder_id);
        $path = $folder ? Folder::find($folder_id)->path
            : $this->main_directory;
        $areas = $folder ? Folder::with('areas')->find($folder_id)->areas
            : Area::all();

        $publicPath = public_path($path);
        $folderStructure = $this->scanFolder($publicPath);

        return Inertia::render('DocumentManagement/Folder', [
            'folders' => $folderStructure,
            'currentPath' => $path,
            'areas' => $areas,
            'folder' => $folder

        ]);
    }

    public function folder_store(FolderCreateRequest $request)
    {
        $data = $request->validated();
        $data['path'] = $this->createFolder($data['currentPath'], $data['name']);
        $folder = Folder::create($data);
        $folder->areas()->sync($data['areas']);
        return redirect()->back();
    }


    //Folder Download

    public function folder_download($folder_id){
        $user = Auth::user();
        $folder = Folder::find($folder_id);
        try{
            return $this->downloadZip($folder->path, $folder->name, $user);
        }catch (e){
            return response()->json(['error' => 'No autorizado'], 403);
        }
        
    }

    public function downloadZip($path, $folder_name, $user){
        $publicDir = public_path($path);
        $zipFileName = $folder_name . '.zip';
        $zip = new ZipArchive;
        $zipFilePath = public_path($zipFileName);
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $zip->addEmptyDir($folder_name);
            $this->addFolderToZip($publicDir, $zip, strlen(public_path($path)) + 1, $folder_name, $user);
            $zip->close();
        } else {
            return response()->json(['error' => 'No se pudo crear el archivo ZIP'], 500);
        }
        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }


    private function addFolderToZip($folder, $zip, $baseLength, $rootFolderName, $user){
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($folder, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($files as $file) {
            $file = realpath($file);
            $relativePath = $rootFolderName . '/' . substr($file, $baseLength);
            $fullFolderPath = realpath($file);
            $publicPosition = strpos($fullFolderPath, 'public');
            $dirName = basename($file);
            $dirPath = substr($fullFolderPath, $publicPosition + strlen('public/'));
            if (is_dir($file)) {
                $currentFolder = Folder::where('name', $dirName )->where('path', $dirPath)->first();
                $currentPermission = FolderArea::where('folder_id', $currentFolder->id)->where('area_id', $user->area_id)->first();
                if ($user->role_id === 1 ||($currentFolder?->state && $currentPermission?->see_download)) {
                    $zip->addEmptyDir($relativePath);
                } else {
                    continue;
                }
            } else if (is_file($file)) {
                $zip->addFile($file, $relativePath);
            }
        }
    }








    //Folder Permissions

    public function folder_permissions($folder_id)
    {
        $permissions = FolderArea::with('area')->where('folder_id', $folder_id)->get();
        $folder = Folder::find($folder_id);
        $upperFolder = Folder::with('areas')->find($folder->upper_folder_id);
        Log::info('=== folder superior===');
        Log::info($upperFolder);
        $upperFoldreAreas = $upperFolder ? $upperFolder->areas
            : Area::all();
        return Inertia::render('DocumentManagement/FolderPermissions', [
            'permissions' => $permissions,
            'folder' => $folder,
            'upper_folder_areas' => $upperFoldreAreas
        ]);
    }

    public function folder_permission_add(Request $request)
    {
        $data = $request->validate([
            'folder_id' => 'required',
            'area_id' => 'required',
        ]);
        FolderArea::create($data);
        return redirect()->back();
    }


    public function folder_permission_remove($folder_area_id)
    {
        $this->under_delete_permission_recursive($folder_area_id);
        return redirect()->back();
    }

    public function under_delete_permission_recursive($folder_area_id)
    {
        $currentPermission = FolderArea::find($folder_area_id);
        $underFolders = Folder::with('folder_areas')
            ->whereHas('folder_areas', function ($query) use ($currentPermission) {
                $query->where('area_id', $currentPermission->area_id);
            })
            ->where('upper_folder_id', $currentPermission->folder_id)
            ->get();
        foreach ($underFolders as $underItem) {
            $underPermission = FolderArea::where('folder_id', $underItem->id)->where('area_id', $currentPermission->area_id)->first();
            if ($underPermission) {
                $this->under_delete_permission_recursive($underPermission->id);
            }
        }
        $currentPermission->delete();
    }



    public function see_dowload_permission(FolderPermissionsRequest $request, $folder_area_id)
    {
        $data = $request->validated();
        $currentPermission = FolderArea::find($folder_area_id);
        $permissionCallback = function ($permission, $state) {
            $this->down_update_sd_permission($permission, $state);
        };
        if ($data['state']) {
            $actualFolder = Folder::find($currentPermission->folder_id);
            $upperFolder = Folder::find($actualFolder->upper_folder_id);
            if ($upperFolder) {
                $upperPermission = FolderArea::where('folder_id', $upperFolder->id)
                    ->where('area_id', $currentPermission->area_id)
                    ->first();
                $this->upper_permission_recursive(
                    $upperPermission->id,
                    $data['state'],
                    $permissionCallback,
                    'see_download'
                );
            }
            $currentPermission->update(['see_download' => $data['state']]);
            if ($data['down_recursive']) {

                $underFolders = Folder::with('folder_areas')
                    ->whereHas('folder_areas', function ($query) use ($currentPermission) {
                        $query->where('area_id', $currentPermission->area_id);
                    })
                    ->where('upper_folder_id', $currentPermission->folder_id)
                    ->get();

                foreach ($underFolders as $currentFolder) {
                    $underPermission = FolderArea::where('folder_id', $currentFolder->id)
                        ->where('area_id', $currentPermission->area_id)
                        ->first();
                    if ($underPermission) {
                        $this->under_permission_recursive(
                            $underPermission->id,
                            $data['state'],
                            $permissionCallback,
                            'see_download'
                        );
                    }
                }
            }
        } else {
            $this->under_permission_recursive(
                $folder_area_id,
                $data['state'],
                $permissionCallback,
                'see_download'
            );
        }
        return redirect()->back();
    }


    public function create_permission(FolderPermissionsRequest $request, $folder_area_id)
    {
        $data = $request->validated();
        $currentPermission = FolderArea::find($folder_area_id);
        $permissionCallback = function ($permission, $state) {
            $this->down_update_create_permission($permission, $state);
        };
        if ($data['state']) {
            $permissionCallback1 = function ($permission, $state) {
                $this->down_update_sd_permission($permission, $state);
            };
            $actualFolder = Folder::find($currentPermission->folder_id);
            $upperFolder = Folder::find($actualFolder->upper_folder_id);
            if ($upperFolder) {
                $upperPermission = FolderArea::where('folder_id', $upperFolder->id)
                    ->where('area_id', $currentPermission->area_id)
                    ->first();
                $this->upper_permission_recursive(
                    $upperPermission->id,
                    $data['state'],
                    $permissionCallback1,
                    'see_download'
                );
            }
            $currentPermission->update(['see_download' => $data['state'], 'create' => $data['state']]);
            if ($data['down_recursive']) {

                $underFolders = Folder::with('folder_areas')
                    ->whereHas('folder_areas', function ($query) use ($currentPermission) {
                        $query->where('area_id', $currentPermission->area_id);
                    })
                    ->where('upper_folder_id', $currentPermission->folder_id)
                    ->get();

                foreach ($underFolders as $currentFolder) {
                    $underPermission = FolderArea::where('folder_id', $currentFolder->id)
                        ->where('area_id', $currentPermission->area_id)
                        ->first();
                    if ($underPermission) {
                        $this->under_permission_recursive(
                            $underPermission->id,
                            $data['state'],
                            $permissionCallback,
                            'see_download'
                        );
                    }
                }
            }
        } else {
            $this->under_permission_recursive(
                $folder_area_id,
                $data['state'],
                $permissionCallback,
                'see_download'
            );
        }
        return redirect()->back();
    }











    public function under_permission_recursive($folder_area_id, $state, callable $permissionCallback, $permissionString)
    {
        $currentPermission = FolderArea::find($folder_area_id);
        if ($this->stop_down_recursion($currentPermission, $state, $permissionString)) {
            return;
        }
        $permissionCallback($currentPermission, $state);
        $underFolders = Folder::with('folder_areas')
            ->whereHas('folder_areas', function ($query) use ($currentPermission) {
                $query->where('area_id', $currentPermission->area_id);
            })
            ->where('upper_folder_id', $currentPermission->folder_id)
            ->get();

        foreach ($underFolders as $currentFolder) {
            $underPermission = FolderArea::where('folder_id', $currentFolder->id)
                ->where('area_id', $currentPermission->area_id)
                ->first();

            if ($underPermission) {
                $this->under_permission_recursive($underPermission->id, $state, $permissionCallback, $permissionString);
            }
        }
    }





    public function upper_permission_recursive($folder_area_id, $state, callable $permissionCallback, $permissionString)
    {
        $currentPermission = FolderArea::find($folder_area_id);
        if ($this->stop_upp_recursion($currentPermission, $state, $permissionString)) {
            return;
        }
        $permissionCallback($currentPermission, $state);
        $actualFolder = Folder::find($currentPermission->folder_id);
        $upperFolder = Folder::find($actualFolder->upper_folder_id);
        if ($upperFolder) {
            $upperPermission = FolderArea::where('folder_id', $upperFolder->id)
                ->where('area_id', $currentPermission->area_id)
                ->first();
            $this->upper_permission_recursive($upperPermission->id, $state, $permissionCallback, $permissionString);
        }
    }



    public function down_update_sd_permission($permission, $state)
    {
        if ($state) {
            $permission->update(['see_download' => $state]);
        } else {
            $permission->update(['see_download' => $state, 'create' => $state]);
        }
    }

    public function down_update_create_permission($permission, $state)
    {
        if ($state) {
            $permission->update(['see_download' => $state, 'create' => $state]);
        } else {
            $permission->update(['create' => $state]);
        }
    }

    public function stop_down_recursion($currentPermission, $state, $permissionString)
    {
        if ($permissionString === 'see_download') {
            return !$state && !$currentPermission->see_download;
        } elseif ($permissionString === 'create') {
            return !$state && !$currentPermission->create;
        }
        return false;
    }


    public function stop_upp_recursion($currentPermission, $state, $permissionString)
    {
        if ($permissionString === 'see_download') {
            return $state && $currentPermission->see_download;
        } elseif ($permissionString === 'create') {
            return $state && $currentPermission->create;
        }
        return false;
    }




























    //Folder Validation Functions

    public function folder_validation()
    {
        $folder = Folder::with('user', 'areas')->where('state', false)->orderBy('created_at', 'desc')->paginate(15);
        return Inertia::render('DocumentManagement/FolderValidation', [
            'folders' => $folder
        ]);
    }

    public function folder_check($folder_id)
    {
        $folder = Folder::find($folder_id);
        if ($folder) {
            $folder->update(['state' => true]);
        } else {
            abort(403, 'Carpeta no encontrada');
        }
    }

    public function folder_invalidate($folder_id)
    {
        $folder = Folder::find($folder_id);
        $this->deleteFolder($folder->path);
        $folder->delete();
        return redirect()->back();
    }






























    //Helpers

    function getPreviusPath($path)
    {
        $segments = explode('/', $path);
        if (count($segments) > 1) {
            array_pop($segments);
        }
        return implode('/', $segments);
    }




    public function createFolder($path, $name)
    {
        $publicPath = public_path($path . '/' . $name);
        if (!file_exists($publicPath)) {
            mkdir($publicPath, 0777, true);
            return $path . '/' . $name;
        } else {
            return abort(403, 'Carpeta ya existente');
        }
    }


    private function scanFolder($folderPath)
    {
        $folderStructure = [];
        $publicPosition = strpos($folderPath, 'public');
        if ($publicPosition === false) {
            return abort(404, 'Directorio no válido');
        }
        $publicPath = substr($folderPath, $publicPosition + strlen('public/'));
        $contents = scandir($folderPath);
        foreach ($contents as $item) {
            if ($item != '.' && $item != '..') {
                $itemPath = $folderPath . '/' . $item;
                if (is_dir($itemPath)) {
                    $folderStructure[] = [
                        'name' => $item,
                        'path' => $publicPath . '/' . $item,
                        'size' => null,
                        'item_db' => Folder::with('user')->where('name', $item)->where('path', $publicPath . '/' . $item)->first()
                    ];
                } else {
                    $folderStructure[] = [
                        'name' => $item,
                        'path' => $publicPath . '/' . $item,
                        'size' => filesize($itemPath),
                        'item_db' => Archive::where('name', $item)->where('path', $publicPath . '/' . $item)->first()
                    ];
                }
            }
        }
        return $folderStructure;
    }


    //delete folder
    public function deleteFolder($path)
    {
        $folderPath = public_path($path);
        if (file_exists($folderPath) && is_dir($folderPath)) {
            $this->deleteDirectoryRecursively($folderPath);
        } else {
            return abort(404, 'Carpeta no encontrada');
        }
    }

    private function deleteDirectoryRecursively($dir)
    {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->deleteDirectoryRecursively("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }
}
