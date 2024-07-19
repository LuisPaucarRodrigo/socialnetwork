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
    public function project_doc_index(Request $request)
    {
        $project_id = $request->input('project_id');
        $path = $request->input('path');
        $previousPath = '';
        if (!$project_id) {
            $lastSlashPosition = strrpos($path, '/');
            if ($lastSlashPosition !== false) {
                $previousPath = substr($path, 0, $lastSlashPosition) !== $this->main_directory 
                    ? substr($path, 0, $lastSlashPosition)
                    : '';
            }
        }
        $currentPath = $project_id ? $this->main_directory . '/' . $path : $path;
        $folders_archives = $this->scanFolder(storage_path('app/' . $currentPath));
        return Inertia::render('ProjectArea/ProjectDocument/ProjectFolder', [
            'folders_archives' => $folders_archives,
            'currentPath' => $currentPath,
            'previousPath' => $previousPath,
        ]);
    }
    public function project_doc_store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'path' => 'required',
            'type' => 'required'
        ]);

        if ($data['type'] === 'Carpeta') {
            $this->createFolder($data['path'], $data['name']);
        }

        return redirect()->back();
    }
    private function scanFolder($folderPath)
    {
        $folderStructure = [];
        $contents = scandir($folderPath);

        foreach ($contents as $item) {
            if ($item[0] !== '.') {
                $itemPath = $folderPath . '/' . $item;
                if (is_dir($itemPath)) {
                    $folderStructure[] = [
                        'name' => $item,
                        'type' => 'folder',
                        'path' => str_replace(storage_path('app/'), '', $itemPath),
                        'size' => null,
                    ];
                } else {
                    $folderStructure[] = [
                        'name' => $item,
                        'type' => 'archive',
                        'path' => str_replace(storage_path('app/'), '', $itemPath),
                        'size' => round(filesize($itemPath) / 1024, 2) . " KB",
                    ];
                }
            }
        }

        return $folderStructure;
    }
    public function createFolder($path, $name)
    {
        $storagePath = storage_path('app/' . $path . '/' . $name);

        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0777, true);
            return $path . '/' . $name;
        } else {
            return abort(403, 'Carpeta ya existente');
        }
    }

}
