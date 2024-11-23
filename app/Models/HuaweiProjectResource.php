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
        'output_date',
        'quantity'
    ];

    protected $appends = [
        'liquidated_quantity',
        'state'
    ];

    public function huawei_project ()
    {
        return $this->belongsTo(HuaweiProject::class, 'huawei_project_id');
    }

    public function huawei_entry_detail ()
    {
        return $this->belongsTo(HuaweiEntryDetail::class, 'huawei_entry_detail_id');
    }

    public function huawei_project_liquidation ()
    {
        return $this->hasOne(HuaweiProjectLiquidation::class, 'huawei_project_resource_id');
    }

    public function getLiquidatedQuantityAttribute ()
    {
        if ($this->huawei_project_liquidation){
            return $this->huawei_project_liquidation->liquidated_quantity;
        }else{
            return null;
        }
    }

    public function getStateAttribute ()
    {
        if ($this->huawei_project_liquidation){
            return 'Liquidado';
        }
        if ($this->quantity == 0){
            return 'Devuelto';
        }else{
            return 'En proyecto';
        }
    }
}
