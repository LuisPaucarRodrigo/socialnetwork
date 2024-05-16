<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Archive extends Model
{
    use HasFactory;
    protected $table = 'archives';
    protected $fillable = [
        "name",
        "path",
        "user_id",
        "version",
        "folder_id",
    ];

    protected $appends= [
        'size',
        'users_active'
    ];

    public function users () {
        return $this->belongsToMany(User::class, 'archive_user');
    }

    public function archive_users() {
        return $this->hasMany(ArchiveUser::class, 'archive_id');
    }

    public function folder() 
    {
        return $this->belongsTo(Folder::class,'folder_id');
    }

    public function user ()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function getSizeAttribute()
    {
        // Obtiene la ruta completa del archivo
        $filePath = public_path($this->path);

        // Verifica si el archivo existe en esa ruta
        if (file_exists($filePath)) {
            // Obtiene el tamaño del archivo en bytes
            $fileSizeInBytes = filesize($filePath);
            
            // Convierte el tamaño del archivo a kilobytes y redondea el resultado a 2 decimales
            $fileSizeInKB = round($fileSizeInBytes / 1024, 2);
            
            // Retorna el tamaño del archivo en kilobytes
            return $fileSizeInKB;
        }

        // Retorna null si el archivo no existe
        return null;
    }

    public function getUsersActiveAttribute()
    {
        $statusUsers = $this->archive_users()->where('status', true)->pluck('user_id');
        $users = User::whereIn('id', $statusUsers)->get();
        return $users;
    }
}
