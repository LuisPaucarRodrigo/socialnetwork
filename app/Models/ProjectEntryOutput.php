<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectEntryOutput extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_entry_id',
        'quantity',
    ];

    //Relations
    public function project_entry(){
        return $this->belongsTo(ProjectEntry::class, 'project_entry_id');
    }
}
