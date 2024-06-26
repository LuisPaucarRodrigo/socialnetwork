<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiEntry extends Model
{
    use HasFactory;

    protected $table = 'huawei_entries';

    protected $fillable = [
        'guide_number',
        'entry_date'
    ];

    public function huawei_entry_details ()
    {
        return $this->hasMany(HuaweiEntryDetail::class, 'huawei_entry_id');
    }

}
