<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\PreprojectRequest\ProjectPintCreateRequest;
use App\Models\Customers_contact;
use App\Models\Preproject;
use App\Models\PreProjectQuote;
use App\Models\PreprojectQuoteService;
use App\Models\SpecialInventory;
use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectPintController extends Controller
{

    public function pint_create_project()
    {
        $ids = [3, 4];
        $contacts_cicsa = Customers_contact::where('customer_id', 1)->get();
        $services = Service::whereIn('id', $ids)->get();
        return Inertia::render(
            'ProjectArea/PreProject/CreateProjectPint', [
                'contacts_cicsa' => $contacts_cicsa,
                'services' => $services
            ]
        );
    }

    public function pint_store_project(ProjectPintCreateRequest $request){
        $data = $request->validated();
        $projectConstants = new ProjectConstants();
        $template = $projectConstants->generateTemplate($data);
        
        //Preproject 
        $preproject = Preproject::create($template['preproject']);

        //contacts
        $contactIds = collect($template['preproject_contacts'])->pluck('id');
        $preproject->contacts()->sync($contactIds);

        //quote
        $quote = new PreProjectQuote($template['preproject_quote']);
        $preproject->quote()->save($quote);


        foreach ($template['quote_services'] as $serviceData) {
            $serviceData['preproject_quote_id'] = $quote->id;
            PreprojectQuoteService::create($serviceData);
        }

        return redirect()->back();
    }


    public function sameCPEProducts (Request $request) {
        $specialProducts = SpecialInventory::where('cpe',$request->cpe )->get();
        return response()->json($specialProducts,200);
    }

}
