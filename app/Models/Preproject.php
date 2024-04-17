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
        'observation',
    ];

    protected $appends = [
        'has_photo_report',
        'is_appropriate',
        'has_quote',
        'state'
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
}
