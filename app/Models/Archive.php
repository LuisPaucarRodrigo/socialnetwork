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
        "comment"
    ];

    protected $appends= [
        'size',
        'users_active',
        'users_available',
        'type',
        'disponibility',
        'observation_state',
        'extension'
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
        // Obtener los user_ids de los usuarios activos
        $statusUsers = $this->archive_users()->where('status', true)->pluck('user_id');
    
        // Excluir el user_id del propietario
        $statusUsers = $statusUsers->filter(function ($userId) {
            return $userId != $this->user_id;
        });
    
        // Obtener los usuarios que coinciden con los user_ids filtrados
        $users = User::whereIn('id', $statusUsers)->get();
    
        return $users;
    }
    public function getUsersAvailableAttribute()
    {
        $areaIds = $this->folder->areas->pluck('id')->toArray();
        $users = User::whereIn('area_id', $areaIds)->get();
        $filteredUsers = $users->filter(function ($user) {
            return $user->id != $this->user_id;
        });
    
        return $filteredUsers;
    }

    public function getTypeAttribute()
    {
        if (fmod($this->version, 1) > 0) {
            return 'beta';
        }
    
        // Si la parte decimal de la versión es 0, es una versión estable
        return 'stable';
    }

    public function getDisponibilityAttribute() {
        // Obtener los archive_user con status true
        $activeUsers = $this->archive_users()->where('status', true)->get();
        
        // Si no hay usuarios activos, retorna false
        if ($activeUsers->isEmpty()) {
            return false;
        }

        // Verificar que todos los usuarios activos tienen el state 'Aprobado'
        foreach ($activeUsers as $user) {
            if ($user->state !== 'Aprobado') {
                return false;
            }
        }

        return true;
    }

    public function getObservationStateAttribute()
    {
        // Obtener los archive_users del último archivo
        $archiveUsers = $this->archive_users;

        // Si el último archivo no tiene archive_users, devolver 4
        if ($archiveUsers->isEmpty()) {
            return 4;
        }

        // Inicializar variables de control
        $hasDesestimado = false;
        $hasPendiente = false;
        $allObservado = true;
        $allAprobado = true;
        $noPendienteNoDesestimado = true;

        foreach ($archiveUsers as $archiveUser) {
            if ($archiveUser->state === 'Desestimado') {
                $hasDesestimado = true;
                $noPendienteNoDesestimado = false;
            }
            if ($archiveUser->state === 'Pendiente') {
                $hasPendiente = true;
                $noPendienteNoDesestimado = false;
            }
            if ($archiveUser->state !== 'Observado') {
                $allObservado = false;
            }
            if ($archiveUser->state !== 'Aprobado') {
                $allAprobado = false;
            }
        }

        if ($hasDesestimado) {
            return 1;
        } elseif ($hasPendiente) {
            return 2;
        } elseif ($allObservado) {
            return 3;
        } elseif ($allAprobado) {
            return 5;
        } elseif ($noPendienteNoDesestimado) {
            return 3;
        }

        return null;
    }

    public function getExtensionAttribute()
    {
        // Obtiene el nombre del archivo completo
        $fileName = $this->path;
        
        // Utiliza la función pathinfo para obtener la información del archivo
        $fileInfo = pathinfo($fileName);
        
        // Retorna solo la extensión del archivo
        return $fileInfo['extension'] ?? null;
    }

}
