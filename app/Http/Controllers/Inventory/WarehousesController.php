<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Warehouse;
use App\Models\Purchase_order;
use App\Models\Inventory;
use App\Models\Entry;
use App\Models\NormalEntry;
use App\Models\PurchasesEntry;
use App\Models\ProjectEntry;
use App\Models\Purchase_product;
use App\Models\ResourceEntry;
use App\Models\RetrievalEntry;
use App\Models\Service;
use Carbon\Carbon;

class WarehousesController extends Controller
{
    //Warehouses
    public function showWarehouses()
    {
        $special_warehouses = Warehouse::where('category', 'Especial')->get();
        $warehouses = Warehouse::where('category', 'Normal')->get();
        return Inertia::render('Inventory/WarehouseManagement/Warehouses', [
            'warehouses' => $warehouses,
            'special_warehouses' => $special_warehouses,
        ]);
    }

    public function showWarehouse(Warehouse $warehouse)
    {
        return Inertia::render('Inventory/WarehouseManagement/WarehouseInformation', [
            'warehouse' => $warehouse,
        ]);
    }

    public function showProducts(Warehouse $warehouse)
    {
        
        $products = Inventory::where('warehouse_id', $warehouse->id)->with('entry', 'purchase_product')->paginate(10);
        // dd($products);
        return Inertia::render('Inventory/WarehouseManagement/Inventory', [
            'products' => $products,
            'warehouseId' => $warehouse->id
        ]);
 
    }

    public function createProducts(Warehouse $warehouse)
    {
        $purchase_products = Purchase_product::where('type', 'Producto')->get();
        return Inertia::render('Inventory/WarehouseManagement/InventoryForm', [
            'purchase_products' => $purchase_products,
            'warehouseId' => $warehouse->id
        ]);
    }

    public function showEntries(Warehouse $warehouse, Inventory $inventory)
    {
        $entries = Entry::where('inventory_id', $inventory->id)
                            ->with(
                                'inventory.purchase_product', 
                                'normal_entry', 'purchase_entry'
                            )
                            ->paginate(10);
        return Inertia::render('Inventory/WarehouseManagement/Entries', [
            'entries' => $entries,
            'warehouseId' => $warehouse->id
        ]);
    }

    public function approvePurchaseOrders(Warehouse $warehouse)
    {
        $purchase_orders = Purchase_order::where('state', 'Completada')
            ->whereHas('purchase_quote.purchase_quote_products.purchase_product', function ($query) {
                $query->where('type', 'Producto');
            })
            ->with('purchase_quote.purchase_quote_products.purchase_product')
            ->paginate(5);
        return Inertia::render('Inventory/WarehouseManagement/ApprovePurchaseOrders', [
            'purchase_orders' => $purchase_orders,
            'warehouseId' => $warehouse->id
        ]);
    }

    public function approve(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'purchase_order_id' => 'required|numeric'
        ]);

        $purchase_order = Purchase_order::with('purchase_quote.purchase_quote_products.purchase_product', 'purchase_quote.purchasing_requests')->find($request->purchase_order_id);
        $warehouse = Warehouse::with('inventory')->find($warehouse->id);
        $today = Carbon::now();

        // Verificar si $warehouse->inventory es nulo o está vacío
        if ($warehouse->inventory->isEmpty()) {
            // Crear registros de inventario directamente
            foreach ($purchase_order->purchase_quote->purchase_quote_products as $purchaseQuoteProduct) {
                $inventory = Inventory::create([
                    'purchase_product_id' => $purchaseQuoteProduct->purchase_product->id,
                    'warehouse_id' => 3
                ]);

                $entry = Entry::create([
                    'inventory_id' => $inventory->id,
                    'type' => 'purchase',
                    'quantity' => $purchaseQuoteProduct->quantity,
                    'unitary_price' => $purchaseQuoteProduct->unitary_amount_no_igv,
                    'entry_date' => $today,
                    'observations' => $request->observations
                ]);

                PurchasesEntry::create([
                    'entry_id' => $entry->id,
                    'purchase_quotes_product_id' => $purchaseQuoteProduct->id,
                ]);

                ProjectEntry::create([
                    'project_id' => $purchase_order->purchase_quote->purchasing_requests->project_id,
                    'entry_id' => $entry->id,
                    'quantity' => $entry->quantity,
                    'unitary_price' => $entry->unitary_price,
                ]);
            }
        } else {
            // Si hay registros de inventario, procede con la lógica existente
            foreach ($purchase_order->purchase_quote->purchase_quote_products as $purchaseQuoteProduct) {
                // Verificar si hay un inventario existente con el mismo purchase_product_id
                $existingInventory = $warehouse->inventory->firstWhere('purchase_product_id', $purchaseQuoteProduct->purchase_product->id);

                if ($existingInventory) {
                    // Si se encuentra un inventario existente, crear un entry y un purchase_entry asociados a ese inventario
                    $entry = Entry::create([
                        'inventory_id' => $existingInventory->id,
                        'type' => 'purchase',
                        'quantity' => $purchaseQuoteProduct->quantity,
                        'unitary_price' => $purchaseQuoteProduct->unitary_amount_no_igv,
                        'entry_date' => $today,
                        'observations' => $request->observations
                    ]);

                    PurchasesEntry::create([
                        'entry_id' => $entry->id,
                        'purchase_quotes_product_id' => $purchaseQuoteProduct->id,
                    ]);

                    ProjectEntry::create([
                        'project_id' => $purchase_order->purchase_quote->purchasing_requests->project_id,
                        'entry_id' => $entry->id,
                        'quantity' => $entry->quantity,
                        'unitary_price' => $entry->unitary_price,
                    ]);

                } else {
                    // Si no se encuentra un inventario existente, crear uno nuevo y luego un entry y un purchase_entry asociados a ese inventario
                    $inventory = Inventory::create([
                        'purchase_product_id' => $purchaseQuoteProduct->purchase_product->id,
                        'warehouse_id' => 3
                    ]);

                    $entry = Entry::create([
                        'inventory_id' => $inventory->id,
                        'type' => 'purchase',
                        'quantity' => $purchaseQuoteProduct->quantity,
                        'unitary_price' => $purchaseQuoteProduct->unitary_amount_no_igv,
                        'entry_date' => $today,
                        'observations' => $request->observations
                    ]);

                    PurchasesEntry::create([
                        'entry_id' => $entry->id,
                        'purchase_quotes_product_id' => $purchaseQuoteProduct->id,
                    ]);

                    ProjectEntry::create([
                        'project_id' => $purchase_order->purchase_quote->purchasing_requests->project_id,
                        'entry_id' => $entry->id,
                        'quantity' => $entry->quantity,
                        'unitary_price' => $entry->unitary_price,
                    ]);
                }
            }
        }

        $purchase_order->update([
            'state' => 'Completada/Aprobada'
        ]);
    }


    public function storeProducts(Request $request, Warehouse $warehouse)
    {

        $request->validate([
            'quantity' => 'required|numeric',
            'unitary_price' => 'required|numeric',
            'entry_date' => 'required|date',
            'referral_guide' => 'required|string',
            'purchase_product_id' => 'required|numeric'
        ]);
        $found = null;
        foreach ($warehouse->inventory as $inventoryItem) {
            if ($inventoryItem->purchase_product_id == $request->purchase_product_id) {
                $found = $inventoryItem->id;
                break;
            }
        }
        if ($found) {
            $entry = Entry::create([
                'inventory_id' => $found,
                'type' => 'normal',
                'quantity' => $request->quantity,
                'unitary_price' => $request->unitary_price,
                'entry_date' => $request->entry_date,
                'observations' => $request->observations,
            ]);

            NormalEntry::create([
                'entry_id' => $entry->id,
                'purchase_product_id' => $request->purchase_product_id,
                'referral_guide' => $request->referral_guide
            ]);
        } else {

            $inventory = Inventory::create([
                'purchase_product_id' => $request->purchase_product_id,
                'warehouse_id' => $warehouse->id
            ]);

            $entry = Entry::create([
                'inventory_id' => $inventory->id,
                'type' => 'normal',
                'quantity' => $request->quantity,
                'unitary_price' => $request->unitary_price,
                'entry_date' => $request->entry_date,
                'observations' => $request->observations,
            ]);

            NormalEntry::create([
                'entry_id' => $entry->id,
                'purchase_product_id' => $request->purchase_product_id,
                'referral_guide' => $request->referral_guide
            ]);
        }
    }

    public function showDispatches(Warehouse $warehouse)
    {
        $project_entries = ProjectEntry::whereHas('entry.inventory', function ($query) use ($warehouse) {
                $query->where('warehouse_id', $warehouse->id);
            })
            ->where('state', null)
            ->orderBy('created_at', 'desc')
            ->with('entry.inventory.warehouse', 'entry.inventory.purchase_product', 'project', 'project_entry_outputs')->paginate(10);

        return Inertia::render('Inventory/WarehouseManagement/Dispatches', [
            'project_entries' => $project_entries,
            'warehouseId' => $warehouse->id
        ]);
    }


    public function showApprovedDispatches(Warehouse $warehouse)
    {
        $project_entries = ProjectEntry::whereHas('entry.inventory', function ($query) use ($warehouse) {
                $query->where('warehouse_id', $warehouse->id);
            })
            ->where('state', true)
            ->orderBy('created_at', 'desc')
            ->with('entry.inventory.warehouse', 'entry.inventory.purchase_product', 'project', 'project_entry_outputs')->paginate(10);

        return Inertia::render('Inventory/WarehouseManagement/DispatchesApproved', [
            'project_entries' => $project_entries,
            'warehouseId' => $warehouse->id
        ]);
    }

    public function showRejectedDispatches(Warehouse $warehouse)
    {
        $project_entries = ProjectEntry::whereHas('entry.inventory', function ($query) use ($warehouse) {
                $query->where('warehouse_id', $warehouse->id);
            })
            ->where('state', false)
            ->orderBy('created_at', 'desc')
            ->with('entry.inventory.warehouse', 'entry.inventory.purchase_product', 'project', 'project_entry_outputs')->paginate(10);

        return Inertia::render('Inventory/WarehouseManagement/DispatchesRejected', [
            'project_entries' => $project_entries,
            'warehouseId' => $warehouse->id
        ]);
    }



    public function acceptOrDeclineDispatch(Warehouse $warehouse, Request $request)
    {
        $request->validate([
            'state' => 'required|boolean',
            'project_entry_id' => 'required|numeric'
        ]);

        $project_entry = ProjectEntry::find($request->project_entry_id);
        $project_entry->update([
            'state' => $request->state
        ]);
    }

    //RETRIEVALENTRY
    public function retrieval_entry_index($boolean = false)
    {
        if ($boolean == false) {
            $retrieval = Entry::with('retrieval_entry.purchase_product')->whereHas('retrieval_entry', function ($query) {
                $query->where('state', null);
            })->paginate();
        } else {
            $retrieval = Entry::with('retrieval_entry.purchase_product')->whereHas('retrieval_entry', function ($query) {
                $query->where('state', true);
            })->paginate();
        }

        return Inertia::render('Inventory/WarehouseManagement/Retrieval/RetrievalEntry', [
            'retrievalEntry' => $retrieval,
            'boolean' => boolval($boolean)
        ]);
    }

    public function retrievalEntryApprove(Request $request)
    {
        $entry = Entry::with('retrieval_entry')->find($request->retrieval);
        $retrieval_entry = RetrievalEntry::where('entry_id', $entry->id);
        $warehouse = Warehouse::with('inventory')->find(4);
        $found = null;

        foreach ($warehouse->inventory as $inventoryItem) {
            if ($inventoryItem->purchase_product_id == $entry->retrieval_entry->purchase_product_id) {
                $found = $inventoryItem->id;
                break;
            }
        }
        $date = Carbon::now();
        if ($found) {
            $entry->update([
                'entry_date' => $date,
                'inventory_id' => $found
            ]);
            $retrieval_entry->update([
                'state' => 1
            ]);
        } else {

            $inventory = Inventory::create([
                'purchase_product_id' => $entry->retrieval_entry->purchase_product_id,
                'warehouse_id' => $warehouse->id
            ]);

            $entry->update([
                'entry_date' => $date,
                'inventory_id' => $inventory->id
            ]);
            $retrieval_entry->update([
                'state' => 1
            ]);
        }
    }

    //RETRIEVALPRODUCT
    public function retrievalProduct()
    {
        $retrievalProducts = Inventory::where('warehouse_id', 4)->with('entry.retrieval_entry', 'purchase_product')->paginate();
        return Inertia::render('Inventory/WarehouseManagement/Retrieval/RetrievalProduct', [
            'retrievalProducts' => $retrievalProducts
        ]);
    }

    public function retrievalProductShow($product)
    {
        $retrievalProductShow = Entry::where('inventory_id', $product)->with('inventory.purchase_product')->paginate();
        return Inertia::render('Inventory/WarehouseManagement/Retrieval/RetrievalProductShow', [
            'retrievalProductsShow' => $retrievalProductShow
        ]);
    }

    //RETRIEVALDISPATH
    public function retrievalDispatch()
    {
        $retrievalDispatch = ProjectEntry::with('project.preproject', 'entry.inventory.purchase_product', 'special_inventory.purchase_product')
            ->where('state', null)
            ->orderBy('created_at', 'desc')
            ->paginate();
        return Inertia::render('Inventory/WarehouseManagement/Retrieval/RetrievalDispatch', [
            'retrievalDispatch' => $retrievalDispatch
        ]);
    }

    public function retrievalDispatchApprove(Request $request)
    {
        $approve = ProjectEntry::find($request->dispatch);
        $approve->update([
            'state' => 1
        ]);
    }

    //RESOURCE
    public function resourcePurchaseOrders()
    {
        $purchase_orders_resource = Purchase_order::where('state', 'Completada')
            ->whereHas('purchase_quote.purchase_quote_products.purchase_product', function ($query) {
                $query->where('type', 'Activo');
            })
            ->with('purchase_quote.purchase_quote_products.purchase_product')
            ->paginate(5);
        return Inertia::render('Inventory/WarehouseManagement/Resource/Entry', [
            'purchase_orders_resource' => $purchase_orders_resource
        ]);
    }

    public function approveResourcePurchaseOrders(Request $request)
    {
        $request->validate([
            'purchase_order_id' => 'required|numeric'
        ]);
        $purchase_order = Purchase_order::with('purchase_quote.purchase_quote_products.purchase_product')->find($request->purchase_order_id);
        $today = Carbon::now();
        foreach ($purchase_order->purchase_quote->purchase_quote_products as $purchaseQuoteProduct) {
            for ($i = 0; $i < $purchaseQuoteProduct->quantity; $i++) {
                ResourceEntry::create([
                    'entry_date' => $today,
                    'referral_guide' => $purchase_order->remission_guide_number,
                    'entry_price' => $purchaseQuoteProduct->unitary_amount_no_igv,
                    'purchase_product_id' => $purchaseQuoteProduct->purchase_product->id,
                ]);
            }

            $purchase_order->update([
                'state' => 'Completada/Aprobada'
            ]);
        }
    }

    public function productResourcePurchaseOrders($boolean = false)
    {
        //dd($boolean);
        if ($boolean == false) {
            $resources = ResourceEntry::with('purchase_product')
                ->where('state', true)
                ->paginate(10);
        } else {
            $resources = ResourceEntry::with('purchase_product')
                ->where('state', null)
                ->paginate(10);
        }
        return Inertia::render('Inventory/WarehouseManagement/Resource/Index', [
            'resources' => $resources,
            'boolean' => boolval($boolean)
        ]);
    }

    public function serialNumberResourcePurchaseOrders(Request $request)
    {   
        $resource_id = ResourceEntry::find($request->resource_id);
        $resource_id->update([
            'serial_number' => $request->serial_number,
            'state'=>true,
            'condition'=>'Disponible'
        ]);
    }

    public function resource_create()
    {
        return Inertia::render('Inventory/WarehouseManagement/CreateResource',[
            'products' => Purchase_product::where('type','Activo')->get()
        ]);
    }

    public function resource_store(Request $request)
    {
        $data = $request->validate([
            'state' => 'required|numeric',
            'condition' => 'required|string',
            'entry_date' => 'required|date',
            'serial_number' => 'required|numeric',
            'referral_guide' => 'required|numeric',
            'entry_price' => 'required|numeric',
            'purchase_product_id' => 'required|numeric',
        ]);
        ResourceEntry::create($data);
    }

    //SERVICES
    public function service_index()
    {
        $services = Service::with('purchase_product')->paginate(10);
        $resources = Purchase_product::with('resource_type')->whereNotNull('resource_type_id')->get();
        return Inertia::render('Inventory/WarehouseManagement/Service/Index', [
            'services' => $services,
            'resources' => $resources
        ]);
    }

    public function service_store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'rent_price' => 'required|numeric',
            'description' => 'string|nullable',
            'purchase_product_id' => 'numeric|nullable'
        ]);

        Service::create($data);
    }
}
