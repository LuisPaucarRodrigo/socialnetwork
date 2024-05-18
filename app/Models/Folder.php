<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;
    protected $table = 'folders';
    protected $fillable = [
        "name",
        "path",
        "type",
        "archive_type",
        "state",
        "user_id",
    ];

    protected $appends = [
        'availability',
        'last_owner'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function areas() {
        return $this->belongsToMany(Area::class, 'folder_area');
    }

    public function folder_areas () {
        return $this->hasMany(FolderArea::class, 'folder_id');
    }

    public function archives () {
        return $this->hasMany(Archive::class, 'folder_id');
    }

    public function getAvailabilityAttribute() {
        // Obtener el último archivo (el que tiene el máximo id)
        $lastArchive = $this->archives()->orderByDesc('id')->first();

        // Si no hay ningún archivo, devolver 1
        if (!$lastArchive) {
            return 1;
        }

        if ($lastArchive->type == 'stable'){
            return 1;
        }

        // Obtener los archive_users del último archivo
        $archiveUsers = $lastArchive->archive_users;

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

        return null; // Retorna null si no se cumple ninguna condición específica
    }  
    
    public function getLastOwnerAttribute()
    {
        $lastArchive = $this->archives()->orderByDesc('id')->first();
        if ($lastArchive) {
            return $lastArchive->user_id;
        }else{
            return null;
        }
        
    }
}
