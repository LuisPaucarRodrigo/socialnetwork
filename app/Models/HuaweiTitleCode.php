<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiTitleCode extends Model
{
    use HasFactory;

    protected $table = 'huawei_title_codes';

    protected $fillable = [
        'huawei_title_id',
        'huawei_code_id'
    ];

    public function huawei_title ()
    {
        return $this->belongsTo(HuaweiTitle::class, 'huawei_title_id');
    }

    public function huawei_code ()
    {
        return $this->belongsTo(HuaweiCode::class, 'huawei_code_id');
    }
}
