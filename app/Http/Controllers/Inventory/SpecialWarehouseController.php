<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Requests\SpecialInventoryRequest\SpecialInventoryOutputRequest;
use App\Http\Requests\SpecialInventoryRequest\SpecialInventoryRequest;
use App\Models\ProjectEntry;
use App\Models\ProjectEntryOutput;
use App\Models\Purchase_product;
use App\Models\SpecialInventory;
use App\Models\Warehouse;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SpecialWarehouseController extends Controller
{

    //PRODUCTOS
    public function special_products_index ($warehouse_id) {
        $warehouse = Warehouse::find($warehouse_id);
        $special_products = SpecialInventory::with('purchase_product')
            ->where('warehouse_id', $warehouse_id)
            ->orderBy('cpe', 'desc')
            ->orderBy('entry_date', 'desc')
            ->paginate(15);
        return Inertia::render('Inventory/WarehouseManagement/SpecialWarehouses/ProductsIndex', [
            "warehouse" => $warehouse,
            "special_products" => $special_products,
        ]);
    }

    public function special_products_create ($warehouse_id, $special_inventory_id=null ) {
        return Inertia::render('Inventory/WarehouseManagement/SpecialWarehouses/ProductsCreate', [
            "products" => Purchase_product::where('type', 'Producto')->get(),
            "warehouse_id" => $warehouse_id,
            "special_product" => SpecialInventory::find($special_inventory_id),
        ]);
    }

    public function special_products_store (SpecialInventoryRequest $request, $special_inventory_id=null) {
        $data = $request->validated();
        $siToStore = SpecialInventory::find($special_inventory_id);
        $siToStore ? $siToStore->update($data)
                   : SpecialInventory::create($data);
        return redirect()->back();
    }

    public function special_products_destroy ( $special_inventory_id) {
        SpecialInventory::find($special_inventory_id)->delete();
        return redirect()->back();
    }


    //DESPACHOS
    public function special_dispatch_index ($warehouse_id) {
        $warehouse = Warehouse::find($warehouse_id);
        $perPage = 15;
        $page = LengthAwarePaginator::resolveCurrentPage() ?: 1;
        $results = ProjectEntry::with(
                'special_inventory.purchase_product',
                'project_entry_outputs',
                'project')
            ->whereHas(
                'special_inventory',
                function ($query) use ($warehouse_id) {
                    $query->where('warehouse_id', $warehouse_id );
                }
            )
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(function ($projectEntry) {
                return $projectEntry->output_state === true;
            });
        $currentPageItems = $results->slice(($page - 1) * $perPage, $perPage);
        $disToApToCom = new LengthAwarePaginator(
            $currentPageItems,
            $results->count(),
            $perPage,
            $page,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return Inertia::render('Inventory/WarehouseManagement/SpecialWarehouses/DispatchIndex', [
            "warehouse" => $warehouse,
            "disToApToCom" => $disToApToCom,
        ]);
    }


    public function special_dispatch_historial ($warehouse_id) {
        $warehouse = Warehouse::find($warehouse_id);
        $perPage = 15;
        $page = LengthAwarePaginator::resolveCurrentPage() ?: 1;
        $results = ProjectEntry::with(
                'special_inventory.purchase_product',
                'project_entry_outputs',
                'project')
            ->whereHas(
                'special_inventory',
                function ($query) use ($warehouse_id) {
                    $query->where('warehouse_id', $warehouse_id );
                }
            )
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(function ($projectEntry) {
                return $projectEntry->output_state === false;
            });
        $currentPageItems = $results->slice(($page - 1) * $perPage, $perPage);
        $disToApToCom = new LengthAwarePaginator(
            $currentPageItems,
            $results->count(),
            $perPage,
            $page,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return Inertia::render('Inventory/WarehouseManagement/SpecialWarehouses/DispatchHistorial', [
            "warehouse" => $warehouse,
            "disToApToCom" => $disToApToCom,
        ]);
    }

    public function special_dispatch_accept_decline (Request $request, $project_entry_id) {
        $data = $request->validate([
            "state" => "required|boolean"
        ]);
        ProjectEntry::find($project_entry_id)->update($data);
        return redirect()->back();
    }

    public function special_dispatch_output_store (SpecialInventoryOutputRequest $request) {
        $data = $request->validated();
        ProjectEntryOutput::create($data);
        return redirect()->back();
    }
    public function special_dispatch_output_destroy ($project_entry_output_id) {
        ProjectEntryOutput::find($project_entry_output_id)->delete();
        return redirect()->back();
    }
}
