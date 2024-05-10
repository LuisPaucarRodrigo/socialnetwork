<?php

namespace App\Http\Controllers\HumanResource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Importa la clase Storage

class DocumentController extends Controller
{
    
    public function showTree()
    {
        // Obtiene todos los archivos dentro del directorio public
        $files = Storage::allFiles('public');

        // Obtiene todas las carpetas dentro del directorio public
        $directories = Storage::allDirectories('public');

        // Combina los archivos y las carpetas
        $tree = array_merge($files, $directories);

        // Muestra la estructura de carpetas y archivos
        dd($tree);
    }
}
