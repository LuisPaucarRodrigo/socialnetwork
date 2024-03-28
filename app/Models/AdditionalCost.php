<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalCost extends Model
{
    use HasFactory;
    protected $table = 'additional_costs';
    protected $fillable = [
        'expense_type',
        'ruc',
        'type_doc',
        'invoice_number',
        'invoice_date',
        'description',
        'amount',
        'project_id'
    ];

    public function project(){
        return $this->belongsTo(Project::class, 'project_id');
    }



    












}
