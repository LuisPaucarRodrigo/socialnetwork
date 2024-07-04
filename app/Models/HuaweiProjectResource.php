<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiProjectResource extends Model
{
    use HasFactory;

    protected $table = 'huawei_project_resources';

    protected $fillable = [
        'huawei_project_id',
        'huawei_entry_detail_id',
        'quantity'
    ];

    public function huawei_project ()
    {
        return $this->belongsTo(HuaweiProject::class, 'huawei_project_id');
    }

    public function huawei_entry_detail ()
    {
        return $this->belongsTo(HuaweiEntryDetail::class, 'huawei_entry_detail_id');
    }
}
