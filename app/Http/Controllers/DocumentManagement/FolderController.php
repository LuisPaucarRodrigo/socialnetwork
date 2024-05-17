<?php

namespace App\Http\Controllers\DocumentManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentManagementRequest\FolderCreateRequest;
use App\Models\Archive;
use App\Models\Area;
use App\Models\Folder;
use App\Models\FolderArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

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

    public function folder_permissions($folder_id)
    {
        $permissions = FolderArea::with('area')->where('folder_id', $folder_id)->get();
        $folder = Folder::find($folder_id);
        return Inertia::render('DocumentManagement/FolderPermissions', [
            'permissions' => $permissions,
            'folder' => $folder
        ]);
    }


    public function see_dowload_permission(Request $request, $folder_area_id) {
        $data = $request->validate(['state'=>'required|boolean']);
        $this->sd_recursive($folder_area_id, $data['state']);
        return redirect()->back();
    }

    public function sd_recursive($folder_area_id, $state) {
        $currentPermission = FolderArea::find($folder_area_id);
        if ($state) {
            $currentPermission->update(['state'=>$state]);
        } else {
            $currentPermission->update(['see_download'=>$state, 'create'=>$state]);
        }
        $underFolder = Folder::with('folder_areas')
            ->whereHas('folder_areas', function($query) use ($currentPermission){
                $query->where('area_id', $currentPermission->area_id);
            })
            ->where('upper_folder_id', $currentPermission->folder_id)
            ->get();
        foreach($underFolder as $currentFolder){
            $underPermission = FolderArea::where('folder_id', $currentFolder->id)
                ->where('area_id', $currentPermission->area_id)->first();
            $this->sd_recursive($underPermission->id, $state);
        }
    }



























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

    public function deleteFolder($path){
        $folderPath = public_path($path);
        if (file_exists($folderPath) && is_dir($folderPath)) {
            $this->deleteDirectoryRecursively($folderPath);
        } else {
            return abort(404, 'Carpeta no encontrada');
        }
    }

    private function deleteDirectoryRecursively($dir){
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->deleteDirectoryRecursively("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }
}
