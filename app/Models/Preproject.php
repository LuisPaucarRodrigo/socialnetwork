<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Preproject extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'subcustomer_id',
        'code',
        'description',
        'date',
        'cpe',
        'observation',
        'status',
        'title_id',
        'cost_center_id',
        'cost_line_id',
    ];

    protected $appends = [
        'has_photo_report',
        'is_appropriate',
        'has_quote',
        'state',
        'total_amount_entry',
        'total_amount_entry_not_margin',
        'total_services_cost',
        'preproject_code_approve'
    ];

    //RELATIONS
    public function cost_center()
    {
        return $this->belongsTo(CostCenter::class, 'cost_center_id');
    }

    public function project()
    {
        return $this->hasOne(Project::class);
    }

    public function quote()
    {
        return $this->hasOne(PreProjectQuote::class, 'preproject_id');
    }

    public function contacts()
    {
        return $this->belongsToMany(Customers_contact::class, 'preprojects_contacts', 'preproject_id', 'customer_contact_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'preproject_user')->withTimestamps();
    }

    public function title()
    {
        return $this->belongsTo(Title::class, 'title_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function purchasing_request()
    {
        return $this->hasMany(Purchasing_request::class);
    }

    public function preproject_entries()
    {
        return $this->hasMany(PreprojectEntry::class);
    }

    // public function preprojectCodes()
    // {
    //     return $this->hasMany(PreprojectCode::class);
    // }

    public function preprojectTitles()
    {
        return $this->hasMany(PreprojectTitle::class);
    }

    public function codes()
    {
        return $this->belongsToMany(Code::class, 'preproject_codes')->withPivot('status');
    }


    // CALCULATED
    public function getVerificationsStagesAttribute()
    {
        return $this->preprojectTitles()->where('state', 1)->exists();
    }

    public function getStateAttribute()
    {
        foreach ($this->purchasing_request as $purchasingRequest) {
            if (is_null($purchasingRequest->project_id)) {
                return false;
            }
        }
        return true;
    }

    public function getHasPhotoReportAttribute()
    {
        return true;
    }

    public function getIsAppropriateAttribute()
    {
        $quote_status =  $this->quote()->first()?->state;
        $project = $this->project()->first();
        return $quote_status && ($project === null);
    }

    public function getHasQuoteAttribute()
    {
        return $this->quote()->first() ? true : false;
    }

    public function getTotalAmountEntryAttribute()
    {
        return $this->preproject_entries()->get()->sum(function ($item) {
            return $item->quantity * $item->unitary_price * (1 + $item->margin / 100);
        });
    }

    public function getTotalAmountEntryNotMarginAttribute()
    {
        return $this->preproject_entries()->get()->sum(function ($item) {
            return $item->quantity * $item->unitary_price;
        });
    }

    public function getTotalServicesCostAttribute()
    {
        return $this->quote()?->first()?->total_services_cost
            ? $this->quote()?->first()?->total_services_cost
            : 0;
    }

    public function getPreprojectCodeApproveAttribute()
    {
        // $preprojectTitles = $this->preprojectTitles;

        // $allStatusFilled = $preprojectTitles->preprojectCodes->every(function ($preprojectCode) {
        //     return !empty($preprojectCode->status);
        // });

        $preprojectTitles = $this->preprojectTitles;

        $allStatusFilled = $preprojectTitles->every(function ($preprojectTitle) {
            return $preprojectTitle->preprojectCodes->every(function ($preprojectCode) {
                return !empty($preprojectCode->status);
            });
        });

        return $allStatusFilled;
    }

    protected static function booted()
    {
        static::updating(function ($item) {
            try {
                $pr = Project::where('preproject_id', $item?->id)->first();
                if (!$pr) return;

                $ca = CicsaAssignation::where('project_id', 148)->first();
                $ca->update(['cpe' => '3333']);
            } catch (\Exception $e) {
                Log::info("Error in Preproject::updating: " . $e->getMessage());
            }
            // $pr = Project::where('preproject_id', $item?->id)->first();
            // $ca = CicsaAssignation::where('project_id', $pr->id)->first();
            // Log::info('cicsa: ' . $ca . 'cpe' . $item['cpe']);

            // if ($ca) $ca->update(['cpe' => '333']);
        });
    }
}
