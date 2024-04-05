<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectEntry extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id',
        'entry_id',
        'special_inventory_id',
        'quantify',
        'observation',
        'unitary_price',
        'state'
    ];

    //Relations
    public function project ()
    {
        return $this->belongsTo(Project::class,'project_id');
    } 

    public function special_inventory ()
    {
        return $this->belongsTo(SpecialInventory::class,'special_inventory_id');
    }

    public function entry ()
    {
        return $this->belongsTo(Entry::class,'entry_id');
    }

    public function project_entry_outout ()
    {
        return $this->hasMany(ProjectEntryOutput::class);
    }

    public function project_entry_liquidation ()
    {
        return $this->hasMany(ProjectEntryLiquidation::class);
    }

    public function dispath ()
    {
        return $this->hasMany(Dispatch::class);
    }
}
