<?php

namespace App\Http\Controllers\DocumentManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentManagementRequest\FolderCreateRequest;
use App\Models\Archive;
use App\Models\Area;
use App\Models\Folder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FolderController extends Controller
{
    protected $main_directory;
    public function __construct() {
        $this->main_directory = 'CCIP';
    }



    public function folder_index($folder_id = null){
        $path = Folder::find($folder_id) ? Folder::find($folder_id)->path
                                         : '';
        $areas = Folder::find($folder_id) ? Folder::with('areas')->find($folder_id)->areas
                                         : Area::all();
        $real_path = $this->main_directory.$path;
        $publicPath = public_path($real_path);
        $folderStructure = $this->scanFolder($publicPath);
       
        return Inertia::render('Document Management/Folder', [
            'folders'=>$folderStructure,
            'currentPath'=>$real_path,
            'areas'=> $areas
        ]);
    }


    public function folder_store (FolderCreateRequest $request) {
        $data = $request->validated();
        $data['path'] = $this->createFolder($data['currentPath'], $data['name']);
        $folder = Folder::create($data);
        $folder->areas()->sync($data['areas']);
        return redirect()->back();
    }


    public function createFolder($path, $name) {
        $publicPath = public_path($path. '/' . $name);
        if (!file_exists($publicPath)) {
            mkdir($publicPath, 0777, true);
            return $path. '/' . $name;
        } else {
            return abort(403, 'Carpeta ya existente');
        }
    }



    private function scanFolder($folderPath) {
        $folderStructure = [];
        $publicPosition = strpos($folderPath, 'public');
    
        if ($publicPosition === false) {
            return abort(404, 'Directorio no válido');
        }
    
        $publicPath = substr($folderPath, $publicPosition + strlen('public'));
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
