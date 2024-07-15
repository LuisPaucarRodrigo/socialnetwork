<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ProjectDocumentController extends Controller
{
    protected $main_directory;

    public function __construct()
    {
        $this->main_directory = 'Projects';
    }
    public function project_doc_index($path, $project_id = null)
    {
        $currentPath = $project_id ? $this->main_directory . '/' . $path
        : $path;
        $publicPath = public_path($currentPath);
        $folders_archives = $this->scanFolder($publicPath);
        return Inertia::render('ProjectArea/ProjectDocument/ProjectFolder', [
            'folders_archives'=> $folders_archives,
            'currentPath' => $currentPath
        ]);
    }


    public function project_doc_store($path)
    {
        // $currentPath = $project_id ? $this->main_directory . '/' . $path
        // : $path;
        // $publicPath = public_path($currentPath);
        // $folders_archives = $this->scanFolder($publicPath);
        // return Inertia::render('ProjectArea/ProjectDocument/ProjectFolder', [
        //     'folders_archives'=> $folders_archives,
        //     'currentPath' => $currentPath
        // ]);
    }


    private function scanFolder($folderPath)
    {
        $folderStructure = [];
        $publicPosition = strpos($folderPath, 'public');
        if ($publicPosition === false) {
            return abort(403, 'Directorio no válido');
        }
        $publicPath = substr($folderPath, $publicPosition + strlen('public/'));
        $contents = scandir($folderPath);
        foreach ($contents as $item) {
            if ($item[0] !== '.') {
                $itemPath = $folderPath . '/' . $item;
                if (is_dir($itemPath)) {
                    $folderStructure[] = [
                        'name' => $item,
                        'type' => 'folder',
                        'path' => $publicPath . '/' . $item,
                        'size' => null,
                    ];
                } else {
                    $folderStructure[] = [
                        'name' => $item,
                        'type' => 'archive',
                        'path' => $publicPath . '/' . $item,
                        'size' => filesize($itemPath),
                    ];
                }
            }
        }
        return $folderStructure;
    }
}
