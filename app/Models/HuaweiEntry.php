<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiEntry extends Model
{
    use HasFactory;

    protected $table = 'huawei_entries';
    //buena paucar

    protected $fillable = [
        'guide_number',
        'entry_date',
        'observation',
        'archive',
    ];

    public function huawei_entry_details ()
    {
        return $this->hasMany(HuaweiEntryDetail::class, 'huawei_entry_id');
    }

}
