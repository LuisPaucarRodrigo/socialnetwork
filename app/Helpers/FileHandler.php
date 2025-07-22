<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class FileHandler
{
    public static function generateFilename(string $baseName, string $kinds): string
    {
        return Str::slug($baseName, '_') . "_{$kinds}_" . uniqid('', true);
    }

    public static function storeFile($file, string $folder, string $filename)
    {
        return $file->move(public_path($folder), $filename);
    }

    public static function showFile(string $relativePath, string $filename)
    {
        $path = public_path($relativePath . $filename);

        if (file_exists($path)) {
            ob_end_clean();
            return response()->file($path);
        }

        abort(404, 'Archivo no encontrado');
    }

    public static function downloadFile(string $relativePath, string $downloadName)
    {
        $path = public_path($relativePath . $downloadName);

        if (file_exists($path)) {
            ob_end_clean();
            return response()->download($path, $downloadName);
        }

        abort(404, 'Archivo no encontrado');
    }

    public static function deleteFile(string $relativePath, string $filename): bool
    {
        $path = public_path($relativePath . $filename);

        if (file_exists($path)) {
            return unlink($path);
        }

        return false;
    }
}
