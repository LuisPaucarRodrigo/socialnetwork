<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Requests\SpecialInventoryRequest\SpecialInventoryRequest;
use App\Models\ProjectEntry;
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
        // $dispatchIncomplete = ProjectEntry::with('project_entry_outputs')
        //     ->whereHas(
        //         'special_inventory',
        //         function ($query) use ($warehouse_id) {
        //             $query->where('warehouse_id', $warehouse_id );
        //         }
        //     )
        //     // ->where(como pongo aqui el campo calculado de output state que debe ser true)
        //     ->paginate(15);

        $perPage = 15;
        $page = LengthAwarePaginator::resolveCurrentPage() ?: 1;
        $results = ProjectEntry::with('special_inventory.purchase_product', 'project')
            ->whereHas(
                'special_inventory',
                function ($query) use ($warehouse_id) {
                    $query->where('warehouse_id', $warehouse_id );
                }
            )
            ->where('state', null)
            ->orWhere('state', false)
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







}
