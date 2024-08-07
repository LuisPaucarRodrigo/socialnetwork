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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use ZipArchive;


class FolderController extends Controller
{
    protected $main_directory;

    public function __construct()
    {
        $this->main_directory = 'CCIP';
    }


    public function folder_index(Request $request, $folder_id = null){
        $folder = Folder::with('folder_areas')->find($folder_id);
        if ($this->checkUserSeeDownload($folder_id)) {
            abort(403, 'Not authorized or folder not activated');
        }
        $path = $folder ? $folder->path
            : $this->main_directory;

        //search functionality
        if($request->input('search')){

            $FoundedFolders = $this->searchInFolder($request->input('search'), $folder_id);
            return Inertia::render('DocumentManagement/FolderSearch', [
                'folders' => $FoundedFolders,
                'currentPath' => $path,
                'folder' => $folder,
                'searchTerm' => $request->input('search')
            ]);
        }
        ///////////////////////

        $areas = $folder ? $folder->areas: Area::all();
        $areas = $areas->filter(function($item){
            return $item->name !== 'Gerencia' && $item->name !== 'Calidad';
        });
        $publicPath = public_path($path);
        $folderStructure = $this->scanFolder($publicPath);
        return Inertia::render('DocumentManagement/Folder', [
            'folders' => $folderStructure,
            'currentPath' => $path,
            'areas' => $areas,
            'folder' => $folder
        ]);
    }



    public function folder_store(FolderCreateRequest $request) {
        $data = $request->validated();
        if(isset($data['upper_folder_id']) && $this->checkUserCreate($data['upper_folder_id'])){
            abort(403, 'No está autorizado');
        }
        $data['path'] = $this->createFolder($data['currentPath'], $data['name']);
        $folder = Folder::create($data);
        $folder->areas()->sync($data['areas']);
        return redirect()->back();
    }




    //Folder Download
    public function folder_download($folder_id){
        $user = Auth::user();
        $folder = Folder::find($folder_id);
        try {
            return $this->downloadZip($folder->path, $folder->name, $user);
        } catch (e) {
            return abort(403, 'No está autorizado');
        }
    }




    public function folder_delete($folder_id){
        $this->checkAdminAccess();
        $folder = Folder::findOrFail($folder_id);
        $publicDir = public_path($folder->path);
        if (!file_exists($publicDir)) {
            return response()->json(['error' => 'La carpeta no existe'], 404);
        }
        $this->deleteDirectory($publicDir);
        Folder::findOrFail($folder_id)->delete();
        return redirect()->back();
    }


    //Folder Permissions

    public function folder_permissions($folder_id) {
        $this->checkAdminAccess();
        $permissions = FolderArea::with('area')->where('folder_id', $folder_id)->get();
        $folder = Folder::find($folder_id);
        $upperFolder = Folder::with('areas')->find($folder->upper_folder_id);
        $upperFoldreAreas = $upperFolder ? $upperFolder->areas
            : Area::all();
        return Inertia::render('DocumentManagement/FolderPermissions', [
            'permissions' => $permissions,
            'folder' => $folder,
            'upper_folder_areas' => $upperFoldreAreas
        ]);
    }

    public function folder_permission_add(Request $request){
        $this->checkAdminAccess();
        $data = $request->validate([
            'folder_id' => 'required',
            'area_id' => 'required',
        ]);
        FolderArea::create($data);
        return redirect()->back();
    }


    public function folder_permission_remove($folder_area_id){
        $this->checkAdminAccess();
        $this->underDeletePermission($folder_area_id);
        return redirect()->back();
    }


    public function see_dowload_permission(FolderPermissionsRequest $request, $folder_area_id){
        $this->checkAdminAccess();
        $data = $request->validated();
        $currentPermission = FolderArea::with('area')->find($folder_area_id);
        //gerencia calidad avoid
        if (in_array($currentPermission?->area?->name, ['Gerencia', 'Calidad'])) {
            return abort(403, 'Área no permitida para cambio de permisos de ver/descargar');
        }
        $permissionCallback = function ($permission, $state) {
            $this->updateSdPermission($permission, $state);
        };
        if ($data['state']) {
            $actualFolder = Folder::find($currentPermission->folder_id);
            $upperFolder = Folder::find($actualFolder->upper_folder_id);
            if ($upperFolder) {
                $upperPermission = FolderArea::where('folder_id', $upperFolder->id)
                    ->where('area_id', $currentPermission->area_id)
                    ->first();
                $this->upperPermissionRecursive(
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
                        $this->underPermissionRecursive(
                            $underPermission->id,
                            $data['state'],
                            $permissionCallback,
                            'see_download'
                        );
                    }
                }
            }
        } else {
            $this->underPermissionRecursive(
                $folder_area_id,
                $data['state'],
                $permissionCallback,
                'see_download'
            );
        }
        return redirect()->back();
    }


    public function create_permission(FolderPermissionsRequest $request, $folder_area_id) {
        $this->checkAdminAccess();
        $data = $request->validated();
        $currentPermission = FolderArea::find($folder_area_id);
        $permissionCallback = function ($permission, $state) {
            $this->updateCreatePermission($permission, $state);
        };
        if ($data['state']) {
            $permissionCallback1 = function ($permission, $state) {
                $this->updateSdPermission($permission, $state);
            };
            $actualFolder = Folder::find($currentPermission->folder_id);
            $upperFolder = Folder::find($actualFolder->upper_folder_id);
            if ($upperFolder) {
                $upperPermission = FolderArea::where('folder_id', $upperFolder->id)
                    ->where('area_id', $currentPermission->area_id)
                    ->first();
                $this->upperPermissionRecursive(
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
                        $this->underPermissionRecursive(
                            $underPermission->id,
                            $data['state'],
                            $permissionCallback,
                            'see_download'
                        );
                    }
                }
            }
        } else {
            $this->underPermissionRecursive(
                $folder_area_id,
                $data['state'],
                $permissionCallback,
                'see_download'
            );
        }
        return redirect()->back();
    }










    //Folder Validation Functions

    public function folder_validation(){
        $this->checkAdminAccess();
        $folder = Folder::with('user', 'areas')->where('state', false)->orderBy('created_at', 'desc')->paginate(15);
        return Inertia::render('DocumentManagement/FolderValidation', [
            'folders' => $folder
        ]);
    }

    public function folder_check($folder_id){
        $this->checkAdminAccess();
        $folder = Folder::find($folder_id);
        if ($folder) {
            $folder->update(['state' => true]);
        } else {
            abort(403, 'Carpeta no encontrada');
        }
    }

    public function folder_invalidate($folder_id){
        $this->checkAdminAccess();
        $folder = Folder::find($folder_id);
        $this->deleteFolder($folder->path);
        $folder->delete();
        return redirect()->back();
    }


    //----------------------- Helpers -------------------//


    // Permissions Helpers
    public function checkAdminAccess() {
        $user = Auth::user();
        if ($user->role_id !== 1){
            abort(403, "No está autorizado");
        }
    }

    public function checkUserSeeDownload($folder_id){
        $user = Auth::user();
        $folder = Folder::find($folder_id);
        if( $folder?->type === 'Archivos' &&
            $folder?->type !== 'Carpeta' ) {return true;}
        if($folder && !$folder->state) {return true;}
        if ($user->role_id === 1 || $folder_id === null) { return false;}
        $folder_permission = FolderArea::where('folder_id', $folder_id)
            ->where('area_id', $user->area_id)
            ->first();
        if ($folder_permission?->see_download) {return false;}
        return true;
    }


    public function checkUserCreate($folder_id){
        $user = Auth::user();
        $folder = Folder::find($folder_id);
        if($folder && !$folder->state) {return true;}
        if ($user->role_id === 1) {return false;}
        if ($folder_id === null) {return true;}
        $folder_permission = FolderArea::where('folder_id', $folder_id)
            ->where('area_id', $user->area_id)
            ->first();
        if ($folder_permission?->create) {return false;}
        return true;
    }


    // DownloadHelpers

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
        ob_end_clean();
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
                $currentFolder = Folder::where('name', $dirName)->where('path', $dirPath)->first();
                $currentPermission = FolderArea::where('folder_id', $currentFolder->id)->where('area_id', $user->area_id)->first();
                if ($currentFolder?->state && ($user->role_id === 1 || $currentPermission?->see_download)) {
                    $zip->addEmptyDir($relativePath);
                } else {
                    continue;
                }
            } else if (is_file($file)) {
                $zip->addFile($file, $relativePath);
            }
        }
    }


    // Deletion Helpers

    private function deleteDirectory($dir){
        if (!file_exists($dir)) {
            return true;
        }
        if (!is_dir($dir)) {
            return unlink($dir);
        }
        $items = new \FilesystemIterator($dir, \FilesystemIterator::SKIP_DOTS);
        foreach ($items as $item) {
            $itemPath = $item->getRealPath();
            if ($item->isDir()) {
                $this->deleteDirectory($itemPath);
            } else {
                unlink($itemPath);
            }
        }
        return rmdir($dir);
    }


    // Store Helpers

    public function createFolder($path, $name){
        $publicPath = public_path($path . '/' . $name);
        if (!file_exists($publicPath)) {
            mkdir($publicPath, 0777, true);
            return $path . '/' . $name;
        } else {
            return abort(403, 'Carpeta ya existente');
        }
    }


    // Index Helpers

    private function scanFolder($folderPath){
        $folderStructure = [];
        $publicPosition = strpos($folderPath, 'public');
        if ($publicPosition === false) {
            return abort(404, 'Directorio no válido');
        }
        $publicPath = substr($folderPath, $publicPosition + strlen('public/'));
        $contents = scandir($folderPath);
        foreach ($contents as $item) {
            if ($item[0] !== '.') {
                $itemPath = $folderPath . '/' . $item;
                if (is_dir($itemPath)) {
                    $folderStructure[] = [
                        'name' => $item,
                        'path' => $publicPath . '/' . $item,
                        'size' => null,
                        'item_db' => Folder::with('user', 'folder_areas')->where('name', $item)->where('path', $publicPath . '/' . $item)->first()
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


    // Validation Helpers
    public function deleteFolder($path) {
        $folderPath = public_path($path);
        if (file_exists($folderPath) && is_dir($folderPath)) {
            $this->deleteDirectoryRecursively($folderPath);
        } else {
            return abort(404, 'Carpeta no encontrada');
        }
    }

    private function deleteDirectoryRecursively($dir) {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->deleteDirectoryRecursively("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }


    // Folder Permissions Helpers

    public function underDeletePermission($folder_area_id){
        $currentPermission = FolderArea::with('area')->find($folder_area_id);
        //gerencia calidad avoid
        if (in_array($currentPermission?->area?->name, ['Gerencia', 'Calidad'])) {
            return abort(403, 'No es posible remover este área');
        }
        $underFolders = Folder::with('folder_areas')
            ->whereHas('folder_areas', function ($query) use ($currentPermission) {
                $query->where('area_id', $currentPermission->area_id);
            })
            ->where('upper_folder_id', $currentPermission->folder_id)
            ->get();
        foreach ($underFolders as $underItem) {
            $underPermission = FolderArea::where('folder_id', $underItem->id)->where('area_id', $currentPermission->area_id)->first();
            if ($underPermission) {
                $this->underDeletePermission($underPermission->id);
            }
        }
        $currentPermission->delete();
    }


    public function underPermissionRecursive($folder_area_id, $state, callable $permissionCallback, $permissionString) {
        $currentPermission = FolderArea::find($folder_area_id);
        if ($this->stopDownRecursion($currentPermission, $state, $permissionString)) {
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
                $this->underPermissionRecursive($underPermission->id, $state, $permissionCallback, $permissionString);
            }
        }
    }



    public function upperPermissionRecursive($folder_area_id, $state, callable $permissionCallback, $permissionString) {
        $currentPermission = FolderArea::find($folder_area_id);
        if ($this->stopUpRecursion($currentPermission, $state, $permissionString)) {
            return;
        }
        $permissionCallback($currentPermission, $state);
        $actualFolder = Folder::find($currentPermission->folder_id);
        $upperFolder = Folder::find($actualFolder->upper_folder_id);
        if ($upperFolder) {
            $upperPermission = FolderArea::where('folder_id', $upperFolder->id)
                ->where('area_id', $currentPermission->area_id)
                ->first();
            $this->upperPermissionRecursive($upperPermission->id, $state, $permissionCallback, $permissionString);
        }
    }



    public function updateSdPermission($permission, $state){
        if ($state) {
            $permission->update(['see_download' => $state]);
        } else {
            $permission->update(['see_download' => $state, 'create' => $state]);
        }
    }


    public function updateCreatePermission($permission, $state){
        if ($state) {
            $permission->update(['see_download' => $state, 'create' => $state]);
        } else {
            $permission->update(['create' => $state]);
        }
    }

    public function stopDownRecursion($currentPermission, $state, $permissionString){
        if ($permissionString === 'see_download') {
            return !$state && !$currentPermission->see_download;
        } elseif ($permissionString === 'create') {
            return !$state && !$currentPermission->create;
        }
        return false;
    }


    public function stopUpRecursion($currentPermission, $state, $permissionString){
        if ($permissionString === 'see_download') {
            return $state && $currentPermission->see_download;
        } elseif ($permissionString === 'create') {
            return $state && $currentPermission->create;
        }
        return false;
    }


    public function searchInFolder($search, $folder_id) {
        $searchTerm = str_replace([' ', '-', '_'], '', $search);
        $currentFolder = Folder::find($folder_id);
        $result = $this->recursiveFolderSearch($currentFolder?->id, $searchTerm);
        return $result;
    }


    public function recursiveFolderSearch($upper_folder_id, $searchTerm){
        $result = [];
        $originalChildFolders = Folder::where('upper_folder_id', $upper_folder_id)
            ->get();

        $childFolders = Folder::with('user', 'folder_areas')->where('upper_folder_id', $upper_folder_id)
            ->where(function ($query) use ($searchTerm) {
                $query->whereRaw("REPLACE(REPLACE(REPLACE(name, ' ', ''), '-', ''), '_', '') LIKE ?", ["%$searchTerm%"])
                      ->orWhereRaw("REPLACE(REPLACE(REPLACE(path, ' ', ''), '-', ''), '_', '') LIKE ?", ["%$searchTerm%"]);
            })
            ->get();
        $childFoldersArray =  $childFolders->toArray();
        $result = array_merge($result, $childFoldersArray);
        foreach($originalChildFolders as $item){
            $itemCollectionArray = $this->recursiveFolderSearch($item->id, $searchTerm);
            $result = array_merge($result, $itemCollectionArray);
        }
        return $result;
    }
}
