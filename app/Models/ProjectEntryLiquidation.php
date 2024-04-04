<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectEntryLiquidation extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_entry_id',
        'liquidated_quantify',
        'refund_quantify'
    ];

    //Relations
    public function project_entry()
    {
        return $this->belongsTo(ProjectEntry::class, 'project_entry_id');
    }

    public function refund()
    {
        return $this->hasMany(Refund::class);
    }

    public function retrieval_entry()
    {
        return $this->hasMany(RetrievalEntry::class);
    }
}
