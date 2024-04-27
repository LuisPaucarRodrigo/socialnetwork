<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use App\Models\PreprojectQuoteService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ServicesLiquidation;
use App\Models\ResourceEntry;

class ServicesLiquidationsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'preproject_quote_service_id'
        ]);

        $preproject_quote_service = PreprojectQuoteService::find($request->preproject_quote_service_id)->load('resource_entry');
        $resource_entry = ResourceEntry::find($preproject_quote_service->resource_entry_id);

        ServicesLiquidation::create([
            'preproject_quote_service_id' => $request->preproject_quote_service_id,
            'observations' => $request->observations,
        ]);

        if($request->state){
            $preproject_quote_service->update([
                'state' => $request->state,
                'final_price' => $preproject_quote_service->resource_entry->current_price
            ]);

            if($resource_entry){
                $resource_entry->update([
                    'condition'=> 'Consumido',
                ]);
            }

        }else{
            $preproject_quote_service->update([
                'state' => $request->state,
            ]);

            if($resource_entry){
                $resource_entry->update([
                    'condition'=> 'Disponible',
                ]);
            }
        }
    }
}
