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



    public function folder_index($folder_id = null) {
        $path = Folder::find($folder_id) ? Folder::find($folder_id)->path
            : $this->main_directory;
        $areas = Folder::find($folder_id) ? Folder::with('areas')->find($folder_id)->areas
            : Area::all();

        $previousPath = $this->getPreviusPath($path);
        $previousId = $previousPath === $this->main_directory ? ''
            : Folder::where('path', $previousPath)->first()?->id;
        $publicPath = public_path($path);
        $folderStructure = $this->scanFolder($publicPath);

        return Inertia::render('DocumentManagement/Folder', [
            'folders' => $folderStructure,
            'currentPath' => $path,
            'areas' => $areas,
            'previousId' => $previousId
        ]);
    }

    public function folder_permissions($folder_id) {
        $permissions = FolderArea::with('area')->where('folder_id', $folder_id)->get();
        $folder = Folder::find($folder_id);
        return Inertia::render('DocumentManagement/FolderPermissions', [
            'permissions' => $permissions,
            'folder' => $folder
        ]);
    }





























    //Helpers

    function getPreviusPath($path){
        $segments = explode('/', $path);
        if (count($segments) > 1) {
            array_pop($segments);
        }
        return implode('/', $segments);
    }


    public function folder_store(FolderCreateRequest $request){
        $data = $request->validated();
        $data['path'] = $this->createFolder($data['currentPath'], $data['name']);
        $folder = Folder::create($data);
        $folder->areas()->sync($data['areas']);
        return redirect()->back();
    }


    public function createFolder($path, $name){
        $publicPath = public_path($path . '/' . $name);
        if (!file_exists($publicPath)) {
            mkdir($publicPath, 0777, true);
            return $path . '/' . $name;
        } else {
            return abort(403, 'Carpeta ya existente');
        }
    }


    private function scanFolder($folderPath){
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
                        'item_db' => Folder::where('name', $item)->where('path', $publicPath . '/' . $item)->first()
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
}
