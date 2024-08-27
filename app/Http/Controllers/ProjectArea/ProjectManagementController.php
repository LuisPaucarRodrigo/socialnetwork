<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest\CreateProjectRequest;
use App\Http\Requests\PurchaseRequest\UpdatePurchaseRequest;
use App\Models\Employee;
use App\Models\Project;
use App\Models\BudgetUpdate;
use App\Models\Entry;
use App\Models\Inventory;
use App\Models\PreprojectQuoteService;
use App\Models\Purchasing_request;
use App\Models\Purchase_quote;
use App\Models\PreprojectEntry;
use App\Models\ResourceEntry;
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

class ProjectManagementController extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('get')) {
            return Inertia::render('ProjectArea/ProjectManagement/Project', [
                'projects' => Project::join('preprojects', 'projects.preproject_id', '=', 'preprojects.id')
                    ->select('projects.*', 'preprojects.date as preproject_date')
                    ->orderBy('preproject_date', 'desc')->where('projects.status', null)->paginate(),
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
                    ->orderBy('preprojects.date', 'desc')->where('projects.status', true)->paginate(),
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
                ->orderBy('preprojects.date', 'desc')
                ->paginate(12);

            return response()->json([
                'projects' => $projects
            ]);
        }
    }


    public function project_create($project_id = null)
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
            ]);
        }
        return Inertia::render('ProjectArea/ProjectManagement/CreateProject', [
            'employees' => Employee::select('id', 'name', 'lastname')->get(),
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
            $this->createFolder($project->code.'_'.$project->id);
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
        $project = Project::find($project_id);
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
            ->with('project')
            ->with('user')
            ->orderByDesc('id')
            ->first();
        $current_budget = $last_update ? $last_update->new_budget : $project_id->initial_budget;

        $additionalCosts = $project_id->additionalCosts->sum('real_amount');
        $acArr = $project_id->additionalCosts()
            ->select('expense_type', DB::raw('SUM(amount/(1+igv/100)) as total_amount'))
            ->groupBy('expense_type')
            ->get();
        $acExpensesAmounts = $acArr->map(function($cost) {
            return [
                'expense_type' => $cost->expense_type,
                'total_amount' => $cost->total_amount,
            ];
        })->toArray();

        $staticCosts = $project_id->staticCosts->sum('real_amount');
        $scArr = $project_id->staticCosts()
            ->select('expense_type', DB::raw('SUM(amount/(1+igv/100)) as total_amount'))
            ->groupBy('expense_type')
            ->get();
        $scExpensesAmounts = $scArr->map(function($cost) {
            return [
                'expense_type' => $cost->expense_type,
                'total_amount' => $cost->total_amount,
            ];
        })->toArray();


        return Inertia::render('ProjectArea/ProjectManagement/ProjectExpenses', [
            'current_budget' => $current_budget,
            'project' => $project_id,
            'additionalCosts' => $additionalCosts,
            'acExpensesAmounts' => $acExpensesAmounts,
            'scExpensesAmounts' => $scExpensesAmounts,
            'staticCosts' => $staticCosts,
        ]);
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
            $products = $products->filter(function($item){
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

        // dd($request->all());

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



    public function createFolder($name){
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
