<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest\CreateProjectRequest;
use App\Models\ComponentOrMaterial;
use App\Models\NetworkEquipment;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Project;
use App\Models\BudgetUpdate;
use App\Models\ProjectComponentOrMaterial;
use App\Models\ProjectNetworkEquipment;
use App\Models\ProjectProduct;
use App\Models\Resource;
use App\Models\ProjectResource;
use App\Models\Purchasing_request;
use App\Models\Purchase_quote;
use App\Models\ResourceHistorial;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProjectManagementController extends Controller
{
    public function index()
    {
        return Inertia::render('ProjectArea/ProjectManagement/Project', [
            'projects' => Project::paginate(),
        ]);
    }

    public function project_create(Request $request, $project_id = null)
    {
        if ($project_id) {
            $project = Project::with('employees')->find($project_id);
            return Inertia::render('ProjectArea/ProjectManagement/CreateProject', [
                'employees' => Employee::select('id', 'name', 'lastname')->get(),
                'project' => $project,
            ]);
        }
        if ($request->query('start_date')) {
            $sd = $request->query('start_date');
            $mes = date('m', strtotime($sd));
            $anio = date('Y', strtotime($sd));
            Project::whereMonth('start_date', $mes)
                ->whereYear('start_date', $anio)
                ->count();
        }
        return Inertia::render('ProjectArea/ProjectManagement/CreateProject', [
            'employees' => Employee::select('id', 'name', 'lastname')->get(),
        ]);
    }

    public function project_store(CreateProjectRequest $request)
    {
        $data = $request->validated();

        if ($request->id) {
            $project = Project::find($request->id);
            $project->update($data);
        } else {
            $project = Project::create($data);
            $employees = $request->input('employees');
            foreach ($employees as $employee) {
                $empId = $employee['employee'];
                $project->employees()->attach($empId['id'], ['charge' => $employee['charge']]);
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
        $project->employees()->attach($request->input('employee.id'), ['charge' => $request->input('charge')]);
        return redirect()->back();
    }

    public function project_destroy($project_id)
    {
        $project = Project::find($project_id);
        $project->delete();
        return redirect()->back();
    }

    public function project_resources($project_id)
    {
        $project = Project::with(['resources', 'components_or_materials', 'resource_historials.resource'])->find($project_id);
        $resources = Resource::all();
        $resourcesDisponibles = $resources->filter(function ($resource) {
            return $resource->state === 'Disponible';
        });

        $components_or_materials = ComponentOrMaterial::all()->where('state', 'Disponible');

        return Inertia::render('ProjectArea/ProjectManagement/ResourcesAssignment', [
            'project' => $project,
            'resources' => $resourcesDisponibles,
            'components_or_materials' => $components_or_materials,
        ]);
    }

    public function project_resources_store(Request $request)
    {
        $resource = Resource::find($request->resource_id);
        if ($resource->leftover < $request->quantity) {
            return response()->json(['error' => 'Cantidad excedida, recarga la página'], 500);
        }
        $data = $request->all();
        ProjectResource::create($data);
        $data['type'] = 'Asignamiento';
        ResourceHistorial::create($data);
        return redirect()->back();
    }

    public function project_resources_delete($resource_id)
    {
        $resource = ProjectResource::find($resource_id);
        $resource->delete();
    }

    public function project_componentmaterial_store(Request $request)
    {
        $component_or_material = ComponentOrMaterial::find($request->component_or_material_id);
        if ($component_or_material->leftover < $request->quantity) {
            return response()->json(['error' => 'Cantidad excedida, recarga la página'], 500);
        }
        if ($component_or_material->leftover == $request->quantity) {
            $component_or_material->update(['state' => 'Ocupado']);
        }
        ProjectComponentOrMaterial::create($request->all());
        return redirect()->back();
    }

    public function project_resources_return(Request $request, $id)
    {
        $data = $request->all();
        $data['type'] = 'Devolución';
        $project_resource = ProjectResource::find($id);
        if ($project_resource->quantity < $request->quantity) {
            return response()->json(['error' => 'Cantidad excedida, recarga la página'], 500);
        } else if ($project_resource->quantity == $request->quantity) {
            $project_resource->delete();
            ResourceHistorial::create($data);
        } else {
            $left = $project_resource->quantity - $request->quantity;
            $project_resource->update(['quantity' => $left]);
            ResourceHistorial::create($data);
        }
        return redirect()->back();
    }

    public function project_componentmaterial_delete($component_or_material_id)
    {
        $componente_or_material = ProjectComponentOrMaterial::find($component_or_material_id);
        $com = ComponentOrMaterial::find($componente_or_material->component_or_material_id);
        $com->update(['state' => 'Disponible']);
        $componente_or_material->delete();
        return redirect()->back();
    }

    public function project_purchases_request_index($project_id)
    {
        $purchases = Purchasing_request::where('project_id', $project_id)->paginate();
        return Inertia::render('ProjectArea/ProjectManagement/PurchaseRequest', [
            'purchases' => $purchases,
            'project_id' => $project_id,
            'project' => Project::find($project_id),
        ]);
    }

    public function project_purchases_request_create($project_id, $purchase_id = null)
    {
        if ($purchase_id) {
            $purchase_request = Purchasing_request::find($purchase_id);
            return Inertia::render('ProjectArea/ProjectManagement/CreatePurchaseRequest', [
                'project_id' => $project_id,
                'purchase_request' => $purchase_request
            ]);
        }
        
        return Inertia::render('ProjectArea/ProjectManagement/CreatePurchaseRequest', [
            'project_id' => $project_id,
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
        if ($request->id) {
            $purchase_request = Purchasing_request::find($request->id);
            $purchase_request->update($data);
        } else {
            Purchasing_request::create($data);
        }
    }

    public function project_expenses(Project $project_id)
    {
        $projectProducts = $project_id->projectProducts()->with('product.productHeaders')->get();

        $productArray = [];
        $contentArray = [];

        foreach ($projectProducts as $projectProduct) {
            // Accede a la relación 'product' de cada ProjectProduct
            $product = $projectProduct->product;

            // Agrega el objeto product al array
            $productArray[] = $product;

            // Accede a la relación 'productHeaders' de cada Product
            $productHeaders = $product->productHeaders;

            // Filtra los elementos de 'productHeaders' cuyo 'header_id' sea 29
            $filteredHeaders = $productHeaders->where('header_id', 29);

            // Agrega el contenido de 'content' al array
            $contentArray[] = $filteredHeaders->pluck('content')->toArray();
        }

        // $productArray ahora contiene todos los objetos de productos asociados al proyecto
        // $contentArray contiene los contenidos de 'ProductsHeader' con 'header_id' igual a 29
        dd($productArray, $contentArray);


        $last_update = BudgetUpdate::where('project_id', $project_id->id)
            ->with('project')
            ->with('user')
            ->orderByDesc('id')
            ->first();

        $current_budget = $last_update ? $last_update->new_budget : $project_id->initial_budget;

        $expenses = Purchasing_request::with([
            'purchase_quotes' => function ($query) {
                $query->whereHas('purchase_order', function ($subQuery) {
                    $subQuery->where('state', 'Completada');
                })->with('purchase_order');
            }
        ])
            ->where([
                ['project_id', $project_id->id],
                ['state', 'Aceptado'],
            ])
            ->whereHas('purchase_quotes.purchase_order', function ($query) {
                $query->where('state', 'Completada');
            });
        $total_expenses = $expenses->get()->sum(function ($expense) {
            return $expense->purchase_quotes[0]['amount'];
        });

        $total_expenses += $project_id->additionalCosts->sum('amount');

        $remaining_budget = $current_budget - $total_expenses;

        return Inertia::render('ProjectArea/ProjectManagement/ProjectExpenses', [
            'current_budget' => $current_budget,
            'remaining_budget' => $remaining_budget,
            'project' => $project_id,
            'expenses' => $expenses->paginate(),
            'additionalCosts' => $project_id->additionalCosts,
            'products' => $productArray
        ]);
    }

    public function project_product_index($project_id)
    {
        $assigned_products = ProjectProduct::where('project_id', $project_id)
            ->with([
                'product',
                'output_project_product' => function ($query) {
                    $query->whereDoesntHave('liquidation');
                },
            ])

            ->paginate(10);

        $warehouses = Warehouse::all();

        return Inertia::render('ProjectArea/ProjectManagement/ProjectProducts', [
            'assigned_products' => $assigned_products,
            'warehouses' => $warehouses,
            'project_id' => $project_id
        ]);
    }


    public function warehouse_products($warehouse_id)
    {
        $warehouse_products = Product::where('warehouse_id', $warehouse_id)
            ->get()
            ->filter(function ($product) {
                return $product->state === 'Disponible';
            });;
        return response()->json($warehouse_products);
    }

    public function project_product_store(Request $request)
    {
        $data = $request->validate([
            'project_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
            'observation' => 'required',
        ]);
        ProjectProduct::create($data);
        return redirect()->back();
    }
    public function project_product_update(ProjectProduct $project_product){
        $output_quantity = $project_product->total_output_project_product;
        if ($output_quantity != 0){
            $project_product->update([
                'quantity' => $output_quantity
            ]);
        }
        return redirect()->back();
    }

    public function warehouse_products_delete(ProjectProduct $assigned){
        $assigned->delete();
        return redirect()->back();
    }
}
