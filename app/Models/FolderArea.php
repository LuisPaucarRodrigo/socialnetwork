<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FolderArea extends Model
{
    use HasFactory;
    protected $table = 'folder_area';
    protected $fillable = [
        "folder_id",
        "area_id",
        "see_download",
        "create",
    ];

    public function areas() {
        return $this->belongsTo(Area::class, 'area_id');
    }
}
