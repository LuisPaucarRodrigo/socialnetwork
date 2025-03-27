<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest\CreateProjectRequest;
use App\Models\AdditionalCost;
use App\Models\Employee;
use App\Models\Project;
use App\Models\BudgetUpdate;
use App\Models\Entry;
use App\Models\Inventory;
use App\Models\PreprojectQuoteService;
use App\Models\Purchasing_request;
use App\Models\ResourceEntry;
use App\Models\StaticCost;
use App\Models\Warehouse;
use App\Models\Preproject;
use App\Models\ProjectEntry;
use App\Models\Purchase_product;
use App\Models\SpecialInventory;
use App\Models\TypeProduct;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Constants\PintConstants;
use Carbon\Carbon;

class ProjectManagementController extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('get')) {
            $projectsData = Project::join('preprojects', 'projects.preproject_id', '=', 'preprojects.id')
                ->select('projects.*', 'preprojects.date as preproject_date')
                ->orderBy('preproject_date', 'desc')->where('projects.status', null)->where('projects.cost_line_id', 1)->paginate();
            $projectsData->getCollection()->each->setAppends([
                'name',
                'code',
                'remaining_budget',
                'current_budget',
                'is_liquidable',
            ]);

            return Inertia::render('ProjectArea/ProjectManagement/Project', [
                'projects' => $projectsData,
            ]);
        } elseif ($request->isMethod('post')) {
            $searchQuery = $request->input('searchQuery');
            $searchTerms = explode(' ', $searchQuery);

            $projects = Project::where(function ($query) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $query->where('projects.description', 'like', "%$term%");
                }
            })
                ->orWhereHas('preproject', function ($query) use ($searchTerms) {
                    foreach ($searchTerms as $term) {
                        $query->where('description', 'like', "%$term%");
                    }
                })
                ->join('preprojects', 'projects.preproject_id', '=', 'preprojects.id')
                ->select('projects.*', 'preprojects.date as preproject_date')
                ->whereNull('projects.status')
                ->where('projects.cost_line_id', 1)
                ->orderBy('preprojects.date', 'desc')
                ->paginate(12);

            return response()->json([
                'projects' => $projects
            ]);
        }
    }

    public function historial(Request $request)
    {
        if ($request->isMethod('get')) {
            return Inertia::render('ProjectArea/ProjectManagement/ProjectHistorial', [
                'projects' => Project::join('preprojects', 'projects.preproject_id', '=', 'preprojects.id')
                    ->select('projects.*', 'preprojects.date as preproject_date')
                    ->orderBy('preprojects.date', 'desc')->where('projects.status', true)->where('projects.cost_line_id',1)->paginate(),
                
            ]);
        } elseif ($request->isMethod('post')) {
            $searchQuery = $request->input('searchQuery');
            $searchTerms = explode(' ', $searchQuery);

            $projects = Project::where(function ($query) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $query->where('projects.description', 'like', "%$term%");
                }
            })
                ->orWhereHas('preproject', function ($query) use ($searchTerms) {
                    foreach ($searchTerms as $term) {
                        $query->where('description', 'like', "%$term%");
                    }
                })
                ->join('preprojects', 'projects.preproject_id', '=', 'preprojects.id')
                ->select('projects.*', 'preprojects.date as preproject_date')
                ->where('projects.status', true)
                ->where('projects.cost_line_id', 1)
                ->orderBy('preprojects.date', 'desc')
                ->paginate(12);

            return response()->json([
                'projects' => $projects
            ]);
        }
    }


    public function project_create($project_id = null, $type = null)
    {
        $preprojects = Preproject::all()->filter(function ($item) {
            return $item->is_appropriate === true;
        });

        if ($project_id) {
            $project = Project::with('employees', 'preproject.quote')->find($project_id);
            return Inertia::render('ProjectArea/ProjectManagement/CreateProject', [
                'employees' => Employee::select('id', 'name', 'lastname')->orderBy('name', 'asc')->get(),
                'project' => $project,
                'preprojects' => $preprojects,
                'type' => $type
            ]);
        }
        return Inertia::render('ProjectArea/ProjectManagement/CreateProject', [
            'employees' => Employee::select('id', 'name', 'lastname')->orderBy('name', 'asc')->get(),
            'preprojects' => $preprojects,
        ]);
    }

    public function project_store(CreateProjectRequest $request)
    {

        $data = $request->validated();
        if ($request->id) {
            $user = Auth::user();
            if ($user->role_id !== 1) {
                abort(403, "Solo admin puede editar");
            }
            $project = Project::find($request->id);
            $project->update($data);
        } else {
            $project = Project::create($data);
            $this->createFolder($project->code . '_' . $project->id);
            $preproject = Preproject::find($request->preproject_id);
            $preproject->update(['status' => true]);
            Purchasing_request::where('preproject_id', $request->preproject_id)
                ->update(['project_id' => $project->id, 'preproject_id' => null]);
            $employees = $request->input('employees');


            //Automatic assignation products from warehouse
            // $preproject_entries = PreprojectEntry::where('preproject_id', $data["preproject_id"])
            //     ->get();
            // foreach ($preproject_entries as $item) {
            //     ProjectEntry::create([
            //         'project_id' => $project->id,
            //         'entry_id' => $item->entry_id,
            //         'quantity' => $item->quantity,
            //         'unitary_price' => $item->unitary_price
            //     ]);
            // }

            //Assignation with CPE
            // if ($preproject->cpe) {
            //     $specialProducts = SpecialInventory::where('cpe', $preproject->cpe)->get();
            //     foreach ($specialProducts as $sPro) {
            //         ProjectEntry::create([
            //             'project_id' => $project->id,
            //             'special_inventory_id' => $sPro->id,
            //             'quantity' => $sPro->quantity,
            //         ]);
            //     }
            // }


            foreach ($employees as $employee) {
                $empId = $employee['employee'];
                $project->employees()->attach($empId['id'], ['charge' => $employee['charge']]);
            }

            $project->load('preproject.quote');
            $preproject_quote_services = PreprojectQuoteService::where('preproject_quote_id', $project->preproject->quote->id)->get();
            foreach ($preproject_quote_services as $item) {
                if ($item->resource_entry_id) {
                    ResourceEntry::find($item->resource_entry_id)->update([
                        'condition' => 'No disponible'
                    ]);
                }
            }
        }
    }


    public function project_delete_employee($pivot_id)
    {
        $tablaPivote = 'project_employee';
        DB::table($tablaPivote)->where('id', $pivot_id)->delete();
        return redirect()->back();
    }

    public function project_add_employee(Request $request, $project_id)
    {
        $project = Project::with('employees')->find($project_id);
        $request->validate([
            'employee.id' => [
                'required',
                function ($attribute, $value, $fail) use ($project) {
                    foreach ($project->employees as $emp) {
                        if ($emp->id == $value) {
                            $fail('El trabajador ya se encuentra en el proyecto');
                        }
                    }
                }
            ]
        ]);
        $employee = Employee::find($request->input('employee.id'));
        $project->employees()->attach($request->input('employee.id'), [
            'charge' => $request->input('charge'),
            'salary_per_day' => $employee->salaryPerDay($project->days),
        ]);
        return redirect()->back();
    }

    public function project_destroy($project_id)
    {
        $project = Project::find($project_id);
        Purchasing_request::where('project_id', $project->id)
            ->update(['preproject_id' => $project->preproject_id]);
        Preproject::find($project->preproject_id)?->update(['status' => null]);
        $project->delete();

        return redirect()->back();
    }

    public function project_resources($project_id)
    {
        $project = Project::with([
            'preproject.quote.preproject_quote_services.resource_entry',
            'preproject.quote.preproject_quote_services.service'
        ])->find($project_id);

        return Inertia::render('ProjectArea/ProjectManagement/ResourcesAssignment', [
            'project' => $project,
        ]);
    }

    public function project_purchases_request_index($project_id)
    {
        $purchases = Purchasing_request::with('project')->where('project_id', $project_id)->paginate();
        return Inertia::render('ProjectArea/ProjectManagement/PurchaseRequest', [
            'purchases' => $purchases,
            'project_id' => $project_id,
            'project' => Project::find($project_id),
        ]);
    }

    public function project_purchases_request_create($project_id)
    {
        return Inertia::render('ShoppingArea/PurchaseRequest/CreateAndUpdateRequest', [
            'allProducts' => Purchase_product::all(),
            'typeProduct' => TypeProduct::all(),
            'project' => Project::find($project_id),
        ]);
    }

    public function project_purchases_request_store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'product_description' => 'required',
            'due_date' => 'required',
            'project_id' => 'required',
        ]);

        $lastRequestId = Purchasing_request::latest()->first()->id ?? 0;
        $data['code'] = 'SC' . str_pad($lastRequestId + 1, 5, '0', STR_PAD_LEFT);

        if ($request->id) {
            if (Auth::user()->role_id !== 1)
                return response()->json(['error' => 'No tiene permisos'], 500);
            $purchase_request = Purchasing_request::find($request->id);
            $purchase_request->update($data);
        } else {
            Purchasing_request::create($data);
        }
    }

    public function project_purchases_request_details($id)
    {
        return Inertia::render('ShoppingArea/PurchaseRequest/PurchasingDetails', [
            'details' => Purchasing_request::with('project', 'products')->find($id),
            'boolean' => true
        ]);
    }

    public function project_purchases_request_edit($project_id = null, $id)
    {
        $purchase = Purchasing_request::with('products')->find($id);
        return Inertia::render('ShoppingArea/PurchaseRequest/CreateAndUpdateRequest', [
            'purchase' => $purchase,
            'allProducts' => Purchase_product::all(),
            'project' => Project::find($project_id),
        ]);
    }

    public function project_expenses(Project $project_id)
    {
        $last_update = BudgetUpdate::where('project_id', $project_id->id)
            ->orderByDesc('id')
            ->first();
        $current_budget = $last_update ? $last_update->new_budget : $project_id->initial_budget;

        $additionalCosts = $project_id->additionalCosts()->where('is_accepted', 1)->whereNotIn('expense_type', PintConstants::acExpensesThatDontCount())->get()->sum('real_amount');
        $additionalCostsIgv = $project_id->additionalCosts()->where('is_accepted', 1)->whereNotIn('expense_type', PintConstants::acExpensesThatDontCount())->get()->sum('amount');
        $acArr = $project_id->additionalCosts()
            ->select('expense_type', DB::raw('SUM(amount/(1+igv/100)) as total_amount'))
            ->groupBy('expense_type')
            ->orderBy('total_amount', 'desc')
            ->get();
        $acExpensesAmounts = $acArr->map(function ($cost) {
            return [
                'expense_type' => $cost->expense_type,
                'total_amount' => $cost->total_amount,
            ];
        })->toArray();

        $staticCosts = $project_id->staticCosts()->whereNotIn('expense_type', PintConstants::scExpensesThatDontCount())->get()
            ->sum('real_amount');
        $staticCostsIgv = $project_id->staticCosts()->whereNotIn('expense_type', PintConstants::scExpensesThatDontCount())->get()
            ->sum('amount');
        $scArr = $project_id->staticCosts()
            ->select('expense_type', DB::raw('SUM(amount/(1+igv/100)) as total_amount'))
            ->groupBy('expense_type')
            ->orderBy('total_amount', 'desc')
            ->get();


        $scExpensesAmounts = $scArr->map(function ($cost) {
            return [
                'expense_type' => $cost->expense_type,
                'total_amount' => $cost->total_amount,
            ];
        })->toArray();
        $project_id->setAppends([
            'remaining_budget',
            'total_services_cost',
            'total_products_cost',
            'total_employee_costs',
        ]);

        return Inertia::render('ProjectArea/ProjectManagement/ProjectExpenses', [
            'current_budget' => $current_budget,
            'project' => $project_id,
            'acExpensesAmounts' => $acExpensesAmounts,
            'scExpensesAmounts' => $scExpensesAmounts,
            'additionalCosts' => $additionalCosts,
            'staticCosts' => $staticCosts,
            'additionalCostsIgv' => $additionalCostsIgv,
            'staticCostsIgv' => $staticCostsIgv,
            'scExpensesThatDontCount' => PintConstants::scExpensesThatDontCount(),
            'acExpensesThatDontCount' => PintConstants::acExpensesThatDontCount(),
        ]);
    }


    public function projects_year_profit($project_id)
    {
        $currProject = Project::with('preproject')->find($project_id);
        if (!$currProject || !$currProject->preproject) {
            return response()->json(['error' => 'Proyecto no encontrado o sin preproyecto'], 404);
        }
        $currDate = Carbon::parse($currProject->preproject->date);
        $otherProjects = Project::with('preproject.quote')
            ->join('preprojects', 'projects.preproject_id', '=', 'preprojects.id')
            ->where('preprojects.date', '<=', $currDate)
            ->where('projects.cost_line_id', 1)
            ->where('projects.cost_center_id', 1)
            ->orderByDesc('preprojects.date')
            ->select('projects.*')
            ->limit(12)
            ->get();

        $months = [];
        $utilities = [];
        $names = [];

        foreach ($otherProjects as $itemProject) {
            if (!$itemProject->preproject || !$itemProject->preproject->date) {
                continue;
            }
            $monthName = Carbon::parse($itemProject->preproject->date)->translatedFormat('F Y');
            $income = optional($itemProject->preproject->quote)->total_amount ?? 0;
            $outcome = ($itemProject->additional_costs_total ?? 0) + ($itemProject->static_costs_total ?? 0);

            $names[] = $itemProject->description;
            $months[] = ucfirst($monthName);
            $utilities[] = round($income - $outcome, 2);
        }

        return response()->json([
            'months' => array_reverse($months),
            'utilities' => array_reverse($utilities),
            'names' => array_reverse($names),
        ]);
    }


    public function project_zone_expenses($project_id)
    {
        $currProject = Project::with('preproject')->find($project_id);
        $zones = PintConstants::allZones();
        $currDate = Carbon::parse($currProject->preproject->date);
        $prevDate = $currDate->copy()->subMonthNoOverflow();
        $acArray = [];
        $scArray = [];
        foreach ($zones as $zone) {
            $acAmount = AdditionalCost::where('zone', $zone)
                ->selectRaw('SUM(amount / (1 + igv / 100)) as total_amount')
                ->where('project_id', $project_id)
                ->whereNotIn('expense_type', PintConstants::acExpensesThatDontCount())
                ->value('total_amount') ?? 0;
            $scAmount = StaticCost::where('zone', $zone)
                ->selectRaw('SUM(amount / (1 + igv / 100)) as total_amount')
                ->where('project_id', $project_id)
                ->whereNotIn('expense_type', PintConstants::scExpensesThatDontCount())
                ->value('total_amount') ?? 0;
            $acArray[] = round($acAmount, 2);
            $scArray[] = round($scAmount, 2);
        }

        $prevProject = Project::with('preproject')
            ->where('cost_line_id', 1)
            ->where('cost_center_id', 1)
            ->whereHas('preproject', function ($query) use ($prevDate) {
                $query->whereMonth('date', $prevDate->month)
                    ->whereYear('date', $prevDate->year)
                    ->whereDay('date', 1);
            })
            ->first();
        $prevAcArray = [];
        $prevScArray = [];
        foreach ($zones as $zone) {
            $acAmount = AdditionalCost::where('zone', $zone)
                ->selectRaw('SUM(amount / (1 + igv / 100)) as total_amount')
                ->where('project_id', $prevProject->id)
                ->whereNotIn('expense_type', PintConstants::acExpensesThatDontCount())
                ->value('total_amount') ?? 0;
            $scAmount = StaticCost::where('zone', $zone)
                ->selectRaw('SUM(amount / (1 + igv / 100)) as total_amount')
                ->where('project_id', $prevProject->id)
                ->whereNotIn('expense_type', PintConstants::scExpensesThatDontCount())
                ->value('total_amount') ?? 0;
            $prevAcArray[] = round($acAmount, 2);
            $prevScArray[] = round($scAmount, 2);
        }

        $yearProjectsIds = Project::with('preproject')
            ->where('cost_line_id', 1)
            ->where('cost_center_id', 1)
            ->whereHas('preproject', function ($query) use ($currDate) {
                $query->whereYear('date', $currDate->year)
                    ->whereDay('date', 1);
            })
            ->pluck('id')->toArray();
        $yearAcArray = [];
        $yearScArray = [];
        foreach ($zones as $zone) {
            $acAmount = AdditionalCost::where('zone', $zone)
                ->selectRaw('SUM(amount / (1 + igv / 100)) as total_amount')
                ->whereIn('project_id', $yearProjectsIds)
                ->whereNotIn('expense_type', PintConstants::acExpensesThatDontCount())
                ->value('total_amount') ?? 0;
            $scAmount = StaticCost::where('zone', $zone)
                ->selectRaw('SUM(amount / (1 + igv / 100)) as total_amount')
                ->whereIn('project_id', $yearProjectsIds)
                ->whereNotIn('expense_type', PintConstants::scExpensesThatDontCount())
                ->value('total_amount') ?? 0;
            $yearAcArray[] = round($acAmount, 2);
            $yearScArray[] = round($scAmount, 2);
        }

        return response()->json([
            'zones' => $zones,
            'current' => [
                'additionals' => $acArray,
                'statics' => $scArray,
            ],
            'previous' => [
                'additionals' => $prevAcArray,
                'statics' => $prevScArray,
            ],
            'years' => [
                'additionals' => $yearAcArray,
                'statics' => $yearScArray,
            ],
        ]);
    }



    public function project_expense_details(Request $request)
    {
        $arrModels = [
            'additional' => 'additional_costs',
            'static' => 'static_costs'
        ];
        $table = $arrModels[$request->spMod];
        $data = \DB::table($table)
            ->select('zone as spentName', \DB::raw('ROUND(SUM(amount / (1 + igv / 100)), 2) as amount'))
            ->where('project_id', $request->project_id)
            ->where('expense_type', $request->expType)
            ->groupBy('zone')
            ->get();
        return response()->json($data, 200);
    }


    public function project_product_index(Project $project_id)
    {
        $assigned_products = ProjectEntry::where('project_id', $project_id->id)
            ->with(
                'entry.inventory.purchase_product',
                'entry.inventory.warehouse',
                'special_inventory.purchase_product',
                'special_inventory.warehouse'
            )
            ->paginate(10);
        $project_id->load('preproject');

        if ($project_id->preproject->customer_id == 1) {
            $warehouses = Warehouse::whereIn('id', [1, 3, 4])->get();
        } else if ($project_id->preproject->customer_id == 2) {
            $warehouses = Warehouse::whereIn('id', [2, 3, 4])->get();
        } else if ($project_id->preproject->customer_id == 3) {
            $warehouses = Warehouse::whereIn('id', [7, 3, 4])->get();
        } else {
            $warehouses = Warehouse::whereIn('id', [3, 4])->get();
        }


        return Inertia::render('ProjectArea/ProjectManagement/ProjectProducts', [
            'assigned_products' => $assigned_products,
            'warehouses' => $warehouses,
            'project_id' => $project_id->id,
            'project' => $project_id
        ]);
    }

    public function project_purchases_request_update_due_date(Request $request)
    {
        $request->validate([
            'due_date' => 'required|date',
            'purchase_id' => 'required|numeric'
        ]);
        $update_due_date = Purchasing_request::find($request->purchase_id);
        $update_due_date->update([
            'due_date' => $request->due_date
        ]);
    }

    public function warehouse_products(Project $project, Warehouse $warehouse)
    {
        if ($warehouse->category === 'Especial') {
            $project->load('preproject');
            $products = SpecialInventory::with('purchase_product')
                ->where('warehouse_id', $warehouse->id)
                ->where('cpe', $project->preproject->cpe)->get();
            $products = $products->filter(function ($item) {
                return $item->quantity_available > 0;
            })->values()->all();
            return response()->json(['products' => $products]);
        } else {
            $products = Inventory::with('entry', 'purchase_product')->where('warehouse_id', $warehouse->id)->get();
            return response()->json(['products' => $products]);
        }
    }

    public function inventory_products(Inventory $inventory)
    {
        if ($inventory->warehouse_id === 4) {
            $inventory = Entry::with(
                'retrieval_entry',
                'inventory.purchase_product',
            )
                ->whereHas('retrieval_entry', function ($query) {
                    $query->where('state', true);
                })
                ->where('inventory_id', $inventory->id)->get()
                ->filter(function ($item) {
                    return $item->quantity_available > 0;
                })->values()->all();
        } else {
            $inventory = Entry::with(
                'normal_entry',
                'purchase_entry',
                'inventory.purchase_product',
            )
                ->where('inventory_id', $inventory->id)->get()
                ->filter(function ($item) {
                    return $item->quantity_available > 0;
                })->values()->all();
        }

        return response()->json(['inventory' => $inventory]);
    }

    public function project_product_store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|numeric',
            'special_inventory_id' => 'nullable|numeric',
            'quantity' => 'required|numeric',
            'area' => 'required|string',
            'zone' => 'required|string',
            'entry_id' => 'nullable|numeric'
        ]);

        if ($request->special_inventory_id != null) {
            ProjectEntry::create([
                'project_id' => $request->project_id,
                'special_inventory_id' => $request->special_inventory_id,
                'area' => $request->area,
                'zone' => $request->zone,
                'quantity' => $request->quantity
            ]);
        } else {
            $entry = Entry::find($request->entry_id);
            ProjectEntry::create([
                'project_id' => $request->project_id,
                'entry_id' => $request->entry_id,
                'area' => $request->area,
                'zone' => $request->zone,
                'quantity' => $request->quantity,
                'unitary_price' => $entry->unitary_price
            ]);
        }

        return redirect()->back();
    }

    public function all_warehouse_products(Warehouse $warehouse)
    {
        if ($warehouse->category == 'Especial') {
            $products = SpecialInventory::all();
            return response()->json(['products' => $products]);
        } else {
            $products = Inventory::where('warehouse_id', $warehouse->id)->get();
            return response()->json(['products' => $products]);
        }
    }

    public function liquidate_project(Request $request)
    {
        Project::find($request->project_id)?->update(['status' => true]);
        return redirect()->back();
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
}
