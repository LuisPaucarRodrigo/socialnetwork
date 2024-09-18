<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DocumentRegister extends Model
{
    use HasFactory;
    protected $fillable = [
        'subdivision_id',
        'document_id',
        'employee_id',
        'e_employee_id',
        'exp_date',
        'state',
        'observations',
    ];

    protected $appends = [
        'sync_status',
        'display'
    ];
    
    public function document () {
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function getSyncStatusAttribute () {
        if($this->state==='No Corresponde'){return true;}
        if ($this->document && $this->exp_date){
            return $this->exp_date === $this->document->exp_date;
        }
        if ($this->document===null){
            return false;
        }
        return null;
    }

    public function getDisplayAttribute () {
        if (is_null($this->exp_date)) {
            return false;
        }
        $expDate = Carbon::parse($this->exp_date);
        $now = Carbon::now();
        return $expDate->lte($now->addWeek());
    }
}
