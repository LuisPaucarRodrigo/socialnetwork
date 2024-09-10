<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectEntryLiquidation extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_entry_id',
        'liquidated_quantity',
        'refund_quantity',
        'liquidations',
        'observations'
    ];

    protected $appends = [
        'type'
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

    public function getTypeAttribute()
    {
        if($this->refund()->first()){
            return 'Devoluciones';
        }else if ($this->retrieval_entry()->first()){
            return 'Recuperos';
        }else{
            return '-';
        }
    }
}
