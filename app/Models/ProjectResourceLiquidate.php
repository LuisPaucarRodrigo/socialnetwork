<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectResourceLiquidate extends Model
{
    use HasFactory;
    protected $table = "project_resource_liquidation";
    protected $fillable = [
        "project_resource_id",
        "liquidated_quantity",
        "refund_quantity",
        "observations",
    ];

    public function project_resource() {
        return $this->belongsTo(ProjectResource::class, "project_resource_id");
    }
}
