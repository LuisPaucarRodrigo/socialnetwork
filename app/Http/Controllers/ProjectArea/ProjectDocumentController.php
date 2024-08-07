<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Exception;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Response;

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
        if ($request->type === 'Carpeta') {
            $data = $request->validate([
                'name' => ['required', 'regex:/^[a-zA-Z0-9 _-]+$/'],
                'path' => 'required',
                'type' => 'required'
            ]);
            $this->createFolder($data['path'], $data['name']);
        }
        if ($request->type === 'Archivo') {
            $data = $request->validate([
                'file' => 'required|file',
                'path' => 'required',
                'type' => 'required'
            ]);
            if ($request->hasfile('file')) {
                $this->createArchive($request->file('file'), $data['path']);
            }
        }
        return redirect()->back();
    }

    public function project_doc_delete(Request $request)
    {
        $path = $request->input('path');
        $type = $request->input('type');
        if ($type === 'folder') {
            $this->deleteFolder($path);
        }
        if ($type === 'archive') {
            $this->deleteArchive($path);
        }
        return redirect()->back();
    }


    private function scanFolder($folderPath)
    {
        $folders = [];
        $files = [];
        $contents = scandir($folderPath);
        foreach ($contents as $item) {
            if ($item[0] !== '.') {
                $itemPath = $folderPath . '/' . $item;
                if (is_dir($itemPath)) {
                    $folders[] = [
                        'name' => $item,
                        'type' => 'folder',
                        'path' => str_replace(storage_path('app/'), '', $itemPath),
                        'size' => null,
                    ];
                } else {
                    $files[] = [
                        'name' => $item,
                        'type' => 'archive',
                        'path' => str_replace(storage_path('app/'), '', $itemPath),
                        'size' => round(filesize($itemPath) / 1024, 2) . " KB",
                    ];
                }
            }
        }
        return array_merge($folders, $files);
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


    public function createArchive($file, $path)
    {
        $filename = $file->getClientOriginalName();
        $filePath = $file->storeAs($path, $filename);
    }

    public function deleteFolder($path)
    {
        if (Storage::exists($path)) {
            Storage::deleteDirectory($path);
        }
    }


    public function deleteArchive($path)
    {
        if (Storage::disk('local')->exists($path)) {
            Storage::disk('local')->delete($path);
        } else {
            return response()->json(['message' => 'Archivo no encontrado'], 404);
        }
    }






    public function project_doc_download(Request $request)
    {
        $path = $request->input('path');
        $storagePath = storage_path('app/' . $path);

        if (is_file($storagePath)) {
            ob_end_clean();
            return $this->downloadFile($path);
        } elseif (is_dir($storagePath)) {
            ob_end_clean();
            return $this->downloadFolderAsZip($path);
        } else {
            return abort(404, 'El recurso solicitado no existe.');
        }
    }

    private function downloadFile($path) {
        $filePath = storage_path('app/' . $path);

        if (!file_exists($filePath)) {
            return abort(404, 'El archivo no existe.');
        }
        return response()->download($filePath);
    }

    private function downloadFolderAsZip($folderPath)
    {
        if (count($this->scanFolder(storage_path('app/' . $folderPath))) > 0) {
            $zip = new ZipArchive();
            $zipFileName = storage_path('app/' . basename($folderPath) . '.zip');

            if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                return abort(500, 'No se pudo crear el archivo zip.');
            }

            $this->addFolderToZip(storage_path('app/' . $folderPath), $zip);


            $zip->close();
            return response()->download($zipFileName)->deleteFileAfterSend(true);
        } else {
            return abort(403, 'Empty folder >:(');
        }

    }

    private function addFolderToZip($folder, ZipArchive $zip, $parentFolder = '')
    {
        $files = scandir($folder);

        foreach ($files as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }

            $filePath = $folder . '/' . $file;
            $localPath = $parentFolder ? $parentFolder . '/' . $file : $file;

            if (is_dir($filePath)) {
                $zip->addEmptyDir($localPath);
                $this->addFolderToZip($filePath, $zip, $localPath);
            } else {
                $zip->addFile($filePath, $localPath);
            }
        }
    }




}
