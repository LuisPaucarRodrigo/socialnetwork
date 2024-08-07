<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Backlog extends Model
{
    use HasFactory;
    protected $fillable = [
        'backlog_site_id',
        'activity_type',
        'task_id',
        'system',
        'subsystem',
        'element',
        'quantity',
        'event_date',
        'report_date',
        'event_element_description',
        'state',
        'commitment',
        'c_start_date',
        'c_end_date',
        'responsible',
        'criticity',
        'conproco_area',
        'origin_event',
        'report_by',
        'report',
        'email_send',
        'budget',
        'budget_required',
        'budget_2',
    ];

    public function backlog_site() {
        return $this->belongsTo(BacklogSite::class, 'backlog_site_id');
    }

}
