<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use App\Models\PreprojectQuoteService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ServicesLiquidation;

class ServicesLiquidationsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'preproject_quote_service_id'
        ]);

        ServicesLiquidation::create([
            'preproject_quote_service_id' => $request->preproject_quote_service_id,
            'observations' => $request->observations
        ]);

    }
}
