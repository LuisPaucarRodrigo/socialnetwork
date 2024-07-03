<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiAdditionalCost extends Model
{
    use HasFactory;

    protected $table = 'huawei_additional_costs';

    protected $fillable = [
        'expense_type',
        'ruc',
        'zone',
        'type_doc',
        'doc_number',
        'doc_date',
        'amount',
        'description',
        'huawei_project_id'
    ];

    public function huawei_project ()
    {
        return $this->belongsTo(HuaweiProject::class, 'huawei_project_id');
    }
}
