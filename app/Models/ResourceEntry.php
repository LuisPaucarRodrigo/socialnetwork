<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ResourceEntry extends Model
{
    use HasFactory;
    protected $fillable = [
        'cost_line_id',
        'condition',
        'entry_date',
        'serial_number',
        'referral_guide',
        'entry_price',
        'state',
        'description',
        'purchase_product_id'
    ];

    protected $appends = [
        'current_price',
    ];

    //CALCULATED
    public function getCurrentPriceAttribute(){
        $entryDate = Carbon::parse($this->entry_date);
        $yearsDifference = floor($entryDate->floatDiffInYears(Carbon::now()));
        $depreciationFactor = $this->purchase_product->resource_type->depreciation_value / 100;
        $currentPrice = number_format($this->entry_price * (1 - $yearsDifference * $depreciationFactor), 2);
        
        if($yearsDifference == 0){
            return $this->entry_price;
        }else if($currentPrice >= 0){
            return $currentPrice;
        }else{
            return 0;
        }
    }

    //RELATIONS
    public function purchase_product()
    {
        return $this->belongsTo(Purchase_product::class,'purchase_product_id');
    }

}
