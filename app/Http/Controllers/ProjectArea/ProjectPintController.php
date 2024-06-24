<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\PreprojectRequest\ProjectPintCreateRequest;
use App\Models\Customers_contact;
use App\Models\Employee;
use App\Models\Preproject;
use App\Models\PreProjectQuote;
use App\Models\PreprojectQuoteService;
use App\Models\Project;
use App\Models\SpecialInventory;
use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectPintController extends Controller
{

    public function pint_create_project()
    {
        $ids = [1,2, 3, 4, 5, 6];
        $pintEmployees = [15, 10, 8, 25];
        $contacts_cicsa = Customers_contact::where('customer_id', 1)->get();
        $employees = Employee::whereIn('id', $pintEmployees)->get();
        $services = Service::whereIn('id', $ids)->get();
        return Inertia::render(
            'ProjectArea/PreProject/CreateProjectPint', [
                'contacts_cicsa' => $contacts_cicsa,
                'services' => $services,
                'employees' => $employees
            ]
        );
    }

    public function pint_store_project(ProjectPintCreateRequest $request){
        $data = $request->validated();
        $projectConstants = new ProjectConstants();
        $template = $projectConstants->generateTemplate($data);

        // dd($template);
        
        //Preproject 
        $preproject = Preproject::create($template['preproject']);

        //contacts
        $contactIds = collect($template['preproject_contacts'])->pluck('id');
        $preproject->contacts()->sync($contactIds);

        //quote
        $quote = new PreProjectQuote($template['preproject_quote']);
        $preproject->quote()->save($quote);

        $quote ->services()->sync($template['quote_services']);

        //Project
        $template['project']['preproject_id'] = $preproject->id;
        $project = Project::create($template['project']);
        $project->employees()->sync($template['project_employees']);
        return redirect()->back();
    }


    public function sameCPEProducts (Request $request) {
        $specialProducts = SpecialInventory::with('purchase_product')->where('cpe',$request->cpe )->where('warehouse_id', 1)->get();
        return response()->json($specialProducts,200);
    }

}
