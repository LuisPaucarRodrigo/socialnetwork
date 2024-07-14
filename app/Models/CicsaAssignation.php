<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CicsaAssignation extends Model
{
    use HasFactory;

    protected $table = 'cicsa_assignations';

    protected $fillable = [
        'assignation_date',
        'project_name',
        'customer',
        'project_code',
        'cpe',
        'project_deadline',
        'user_name',
        'user_id'
    ];

    public function user ()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cicsa_feasibility ()
    {
        return $this->hasOne(CicsaFeasibility::class, 'cicsa_assignation_id');
    }

    public function cicsa_installation ()
    {
        return $this->hasOne(CicsaInstallation::class, 'cicsa_assignation_id');
    }

    public function cicsa_materials ()
    {
        return $this->hasOne(CicsaMaterial::class, 'cicsa_assignation_id');
    }

    public function cicsa_purchase_order ()
    {
        return $this->hasOne(CicsaPurchaseOrder::class, 'cicsa_assignation_id');
    }

    public function cicsa_purchase_order_validation ()
    {
        return $this->hasOne(CicsaPurchaseOrderValidation::class, 'cicsa_assignation_id');
    }

    public function cicsa_service_order ()
    {
        return $this->hasOne(CicsaServiceOrder::class, 'cicsa_assignation_id');
    }

    public function cicsa_charge_area ()
    {
        return $this->hasOne(CicsaChargeArea::class, 'cicsa_assignation_id');
    }
}
