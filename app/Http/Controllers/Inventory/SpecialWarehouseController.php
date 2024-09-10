<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Requests\SpecialInventoryRequest\SpecialInventoryOutputRequest;
use App\Http\Requests\SpecialInventoryRequest\SpecialInventoryRequest;
use App\Models\ProjectEntry;
use App\Models\ProjectEntryOutput;
use App\Models\Purchase_product;
use App\Models\Refund;
use App\Models\SpecialInventory;
use App\Models\Warehouse;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SpecialWarehouseController extends Controller
{

    //PRODUCTOS
    public function special_products_index($warehouse_id)
    {
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

    public function special_products_create($warehouse_id, $special_inventory_id = null)
    {
        return Inertia::render('Inventory/WarehouseManagement/SpecialWarehouses/ProductsCreate', [
            "products" => Purchase_product::where('type', 'Producto')->get(),
            "warehouse_id" => $warehouse_id,
            "special_product" => SpecialInventory::with('purchase_product')->find($special_inventory_id),
        ]);
    }

    public function special_products_store(SpecialInventoryRequest $request, $special_inventory_id = null)
    {
        $data = $request->validated();
        $siToStore = SpecialInventory::find($special_inventory_id);
        $siToStore ? $siToStore->update($data)
            : SpecialInventory::create($data);
        return redirect()->back();
    }

    public function special_products_destroy($special_inventory_id)
    {
        SpecialInventory::find($special_inventory_id)->delete();
        return redirect()->back();
    }


    //DESPACHOS
    public function special_dispatch_index($warehouse_id)
    {
        $warehouse = Warehouse::find($warehouse_id);
        
        $disToApToCom = ProjectEntry::with(
                'special_inventory.purchase_product',
                'project_entry_outputs',
                'project')
            ->whereHas(
                'special_inventory',
                function ($query) use ($warehouse_id) {
                    $query->where('warehouse_id', $warehouse_id);
                }
            )
            ->where('state', null)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return Inertia::render('Inventory/WarehouseManagement/SpecialWarehouses/DispatchIndex', [
            "warehouse" => $warehouse,
            "disToApToCom" => $disToApToCom,
        ]);
    }

    public function special_dispatch_approved ($warehouse_id) {
        $warehouse = Warehouse::find($warehouse_id);
        $disToApToCom = ProjectEntry::with(
                'special_inventory.purchase_product',
                'project_entry_outputs',
                'project')
            ->whereHas(
                'special_inventory',
                function ($query) use ($warehouse_id) {
                    $query->where('warehouse_id', $warehouse_id);
                }
            )
            ->where('state', true)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return Inertia::render('Inventory/WarehouseManagement/SpecialWarehouses/DispatchApproved', [
            "warehouse" => $warehouse,
            "disToApToCom" => $disToApToCom,
        ]);
    }
    public function special_dispatch_rejected ($warehouse_id) {
        $warehouse = Warehouse::find($warehouse_id);
        $disToApToCom = ProjectEntry::with(
                'special_inventory.purchase_product',
                'project')
            ->whereHas(
                'special_inventory',
                function ($query) use ($warehouse_id) {
                    $query->where('warehouse_id', $warehouse_id );
                }
            )
            ->where('state', false)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return Inertia::render('Inventory/WarehouseManagement/SpecialWarehouses/DispatchRejected', [
            "warehouse" => $warehouse,
            "disToApToCom" => $disToApToCom,
        ]);
    }


    

    public function special_dispatch_accept_decline(Request $request, $project_entry_id)
    {
        $data = $request->validate([
            "state" => "required|boolean"
        ]);
        $projectEntry = ProjectEntry::find($project_entry_id);
		$projectEntry->update($data);
        ProjectEntryOutput::create([
            "project_entry_id" => $projectEntry->id,
            "quantity" => $projectEntry->quantity,
        ]);
        return redirect()->back();
    }

    public function special_dispatch_output_store(SpecialInventoryOutputRequest $request)
    {
        $data = $request->validated();
        ProjectEntryOutput::create($data);
        return redirect()->back();
    }
    public function special_dispatch_output_destroy($project_entry_output_id)
    {
        ProjectEntryOutput::find($project_entry_output_id)->delete();
        return redirect()->back();
    }


    //DEVOLUCIONES
    public function special_refund_index($warehouse_id)
    {
        $warehouse = Warehouse::find($warehouse_id);
        $refunds = Refund::with(
            "project_entry_liquidation.project_entry.special_inventory.purchase_product"
        )
            ->where('warehouse_id', $warehouse_id)
            ->where(function ($query) {
                $query->where('state', null)
                    ->orWhere('state', false);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return Inertia::render('Inventory/WarehouseManagement/SpecialWarehouses/RefundsIndex', [
            "warehouse" => $warehouse,
            "refunds" => $refunds,
        ]);
    }
    public function special_refund_historial($warehouse_id)
    {
        $warehouse = Warehouse::find($warehouse_id);
        $refunds = Refund::with(
            "project_entry_liquidation.project_entry.special_inventory.purchase_product"
        )
            ->where('warehouse_id', $warehouse_id)
            ->where('state', true)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return Inertia::render('Inventory/WarehouseManagement/SpecialWarehouses/RefundsHistorial', [
            "warehouse" => $warehouse,
            "refunds" => $refunds,
        ]);
    }

    public function special_refund_accept_decline(Request $request, $refund_id)
    {
        $data = $request->validate([
            "state" => "required|boolean"
        ]);
        Refund::find($refund_id)->update($data);
        return redirect()->back();
    }
}
