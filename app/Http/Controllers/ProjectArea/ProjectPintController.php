<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\PreprojectRequest\ProjectPextCreateRequest;
use App\Http\Requests\PreprojectRequest\ProjectPintCreateRequest;
use App\Models\CicsaAssignation;
use Illuminate\Support\Facades\DB;
use App\Models\CostCenter;
use App\Models\CostLine;
use App\Models\CostLineCenterEmployee;
use App\Models\Customers_contact;
use App\Models\Preproject;
use App\Models\PreProjectQuote;
use App\Models\Project;
use App\Models\SpecialInventory;
use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Exception;

class ProjectPintController extends Controller
{
    public function pint_create_project($type)
    {
        $ids = [3, 4, 5, 6, 7];
        $contacts_cicsa = Customers_contact::where('customer_id', 1)->get();
        $cost_centers = CostCenter::whereIn('id', [1, 2])->get();
        $services = Service::whereIn('id', $ids)->get();
        return Inertia::render(
            'ProjectArea/PreProject/CreateProjectPint',
            [
                'contacts_cicsa' => $contacts_cicsa,
                'cost_centers' => $cost_centers,
                'services' => $services,
                'type' => $type,
            ]
        );
    }

    public function pext_create_project($type)
    {
        $text = "Mantto";
        // $ids = [8];
        $contacts_cicsa = Customers_contact::where('customer_id', 2)->get();
        $cost_line = CostLine::where('name', 'Pext')->with(['cost_center' => function ($query) use ($text) {
            $query->where('name', 'like', "%$text%");
        }])->first();
        // $services = Service::whereIn('id', $ids)->get();
        return Inertia::render(
            'ProjectArea/PreProject/CreateProjectPext',
            [
                'contacts_cicsa' => $contacts_cicsa,
                'cost_centers' => $cost_line->cost_center,
                // 'services' => $services,
                'type' => $type,
            ]
        );
    }

    public function getEmployees($cc_id)
    {
        $costLineEmployee = CostLineCenterEmployee::with('employee.contract')->where('cost_center_id', $cc_id)->get();
        $employees = $costLineEmployee->map(function ($item) {
            $item->charge = $item->employee?->contract?->personal_segment;
            $item->name = $item->employee?->name;
            $item->id = $item->employee?->id;
            $item->lastname = $item->employee?->lastname;
            return $item;
        });
        return response()->json($employees);
    }


    public function pint_store_project(ProjectPintCreateRequest $request)
    {
        $templateArray = [
            1 => 'Mantenimiento',
            2 => 'Combustible'
        ];
        $data = $request->validated();
        $projectConstants = new ProjectConstants();
        $data['template'] = $templateArray[$data['cost_center_id']];

        $template = $projectConstants->generateTemplate($data);

        DB::beginTransaction();
        try {
            //Preproject 
            $preproject = Preproject::create($template['preproject']);

            //contacts
            $contactIds = collect($template['preproject_contacts'])->pluck('id');
            $preproject->contacts()->sync($contactIds);

            //quote
            $quote = new PreProjectQuote($template['preproject_quote']);
            $preproject->quote()->save($quote);

            $quote->services()->sync($template['quote_services']);

            //Project
            $template['project']['preproject_id'] = $preproject->id;
            $project = Project::create($template['project']);
            $project->employees()->sync($template['project_employees']);
            $this->createFolder($project->code . '_' . $project->id);

            //

            //ProjectFolder
            DB::commit();
            return redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function pext_store_project(ProjectPextCreateRequest $request)
    {
        $data = $request->validated();
        $projectConstants = new ProjectConstantsPext();

        $template = $projectConstants->generateTemplate($data);

        $currentMonth = now()->month;
        $currentYear = now()->year;

        $listCost = [4, 5];
        $existingPreproject = Preproject::where('cost_line_id', 2)
            ->whereIn('cost_center_id', $listCost)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->first();

        if ($existingPreproject) {
            return response()->json('Ya existe un proyecto creado en este mes y año.', 422);
        }

        DB::beginTransaction();
        try {
            //Preproject 
            $preproject = Preproject::create($template['preproject']);
            $preproject->load('customer');

            //contacts
            $contactIds = collect($template['preproject_contacts'])->pluck('id');
            $preproject->contacts()->sync($contactIds);

            //quote
            $quote = new PreProjectQuote($template['preproject_quote']);
            $preproject->quote()->save($quote);

            $quote->services()->sync($template['quote_services']);

            //Project
            $template['project']['preproject_id'] = $preproject->id;
            $project = Project::create($template['project']);
            $project->employees()->sync($template['project_employees']);

            //ProjectFolder
            $this->createFolder($project->code . '_' . $project->id);

            //CicsaAssignation
            CicsaAssignation::create([
                "assignation_date" => $preproject->date,
                "project_name" => $project->description,
                "customer" => $preproject->customer->business_name,
                "project_code" => $preproject->code,
                "cpe" => $preproject->cpe,
                "zone" => 'Arequipa',
                "zone2" => null,
                "manager" => 'Valery Joana Montalvan Huillca',
                "user_name" => 'Valery Joana Montalvan Huillca',
                "user_id" => 9,
                "project_id" => $project->id,
            ]);

            DB::commit();
            return redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }


    public function createFolder($name)
    {
        $path = 'Projects';
        $storagePath = storage_path('app/' . $path . '/' . $name);
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0777, true);
            return $path . '/' . $name;
        } else {
            return abort(403, 'Carpeta ya existente');
        }
    }


    public function sameCPEProducts(Request $request)
    {
        $specialProducts = SpecialInventory::with('purchase_product')->where('cpe', $request->cpe)->where('warehouse_id', 1)->get();
        return response()->json($specialProducts, 200);
    }

    public function sameCPEProductsPext(Request $request)
    {
        $specialProducts = SpecialInventory::with('purchase_product')->where('cpe', $request->cpe)->where('warehouse_id', 2)->get();
        return response()->json($specialProducts, 200);
    }
}
