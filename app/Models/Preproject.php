<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    protected $appends = [
        'has_photo_report',
        'is_appropriate',
        'has_quote',
        'state',
        'total_amount_entry',
        'total_amount_entry_not_margin',
        'total_services_cost'
    ];

    //RELATIONS
    public function project()
    {
        return $this->hasOne(Project::class);
    }

    public function imagepreproject()
    {
        return $this->HasMany(Imagespreproject::class);
    }

    public function quote()
    {
        return $this->hasOne(PreProjectQuote::class, 'preproject_id');
    }

    public function contacts()
    {
        return $this->belongsToMany(Customers_contact::class, 'preprojects_contacts', 'preproject_id', 'customer_contact_id');
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

    
    // CALCULATED
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
        return $this->preproject_entries()->get()->sum(function($item){
            return $item->quantity * $item->unitary_price * (1+ $item->margin/100);
        });
    }

    public function getTotalAmountEntryNotMarginAttribute()
    {
        return $this->preproject_entries()->get()->sum(function($item){
            return $item->quantity * $item->unitary_price;
        });
    }

    public function getTotalServicesCostAttribute() : float
    {
        return $this->quote()?->first()?->total_services_cost 
                    ? $this->quote()?->first()?->total_services_cost 
                    : number_format(0, 2) ;
    }
}
