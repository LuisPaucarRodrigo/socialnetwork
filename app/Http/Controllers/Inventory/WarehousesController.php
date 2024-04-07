<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Warehouse;
use App\Models\WarehousesHeader;
use App\Models\Header;
use App\Models\Purchase_order;
use App\Models\Inventory;
use App\Models\Entry;
use App\Models\NormalEntry;
use App\Models\PurchasesEntry;
use App\Models\ProjectEntry;
use App\Models\Purchase_product;
use Illuminate\Support\Facades\Auth;
use App\Providers\GlobalFunctionsServiceProvider;
use Carbon\Carbon;

class WarehousesController extends Controller
{
    //Warehouses
    public function showWarehouses()
    {
        $warehouses = Warehouse::paginate();
        return Inertia::render('Inventory/WarehouseManagement/Warehouses', [
            'warehouses' => $warehouses,
        ]);
    }

    public function showWarehouse(Warehouse $warehouse)
    {
        return Inertia::render('Inventory/WarehouseManagement/WarehouseInformation', [
            'warehouse' => $warehouse,
        ]);
    }

    public function showProducts(Warehouse $warehouse){
        $products = Inventory::where('warehouse_id', $warehouse->id)->with('entry', 'purchase_product')->paginate(10);
        return Inertia::render('Inventory/WarehouseManagement/Inventory', [
            'products' => $products,
            'warehouseId' => $warehouse->id
        ]);
    }

    public function createProducts(Warehouse $warehouse){
        $products = Inventory::where('warehouse_id', $warehouse->id)->with('entry')->paginate(10);
        $purchase_products = Purchase_product::where('type', 'Producto')->get();
        return Inertia::render('Inventory/WarehouseManagement/InventoryForm', [
            'products' => $products,
            'purchase_products' => $purchase_products,
            'warehouseId' => $warehouse->id
        ]);
    }

    public function showEntries(Warehouse $warehouse, Inventory $inventory)
    {
        $entries = Entry::where('inventory_id', $inventory->id)->with('inventory.purchase_product', 'normal_entry', 'purchase_entry')->paginate(10);
        return Inertia::render('Inventory/WarehouseManagement/Entries', [
            'entries' => $entries,
            'warehouseId' => $warehouse->id
        ]);
    }

    public function approvePurchaseOrders(Warehouse $warehouse){
        $purchase_orders = Purchase_order::where('state', 'Completada')->with('purchase_quote.purchase_quote_products.purchase_product')->paginate(5);
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

        $purchase_order = Purchase_order::with('purchase_quote.purchase_quote_products.purchase_product')->find($request->purchase_order_id);
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
                    'unitary_price' => $purchaseQuoteProduct->unitary_amount,
                    'entry_date' => $today,
                    'observations' => $request->observations
                ]);

                $purchase_entry = PurchasesEntry::create([
                    'entry_id' => $entry->id,
                    'purchase_quotes_product_id' => $purchaseQuoteProduct->id, 
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
                        'unitary_price' => $purchaseQuoteProduct->unitary_amount,
                        'entry_date' => $today,
                        'observations' => $request->observations
                    ]);
            
                    $purchase_entry = PurchasesEntry::create([
                        'entry_id' => $entry->id,
                        'purchase_quotes_product_id' => $purchaseQuoteProduct->id, 
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
                        'unitary_price' => $purchaseQuoteProduct->unitary_amount,
                        'entry_date' => $today,
                        'observations' => $request->observations
                    ]);
            
                    $purchase_entry = PurchasesEntry::create([
                        'entry_id' => $entry->id,
                        'purchase_quotes_product_id' => $purchaseQuoteProduct->id, 
                    ]);
                }
            }
        }

        $purchase_order->update([
            'state' => 'Completada/Aprobada'
        ]);
    }

    public function storeProducts(Request $request, Warehouse $warehouse){
        
        $request->validate = ([
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

            $normal_entry = NormalEntry::create([
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

            $normal_entry = NormalEntry::create([
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
        })->where('state', null)->with('entry.inventory.warehouse', 'entry.inventory.purchase_product', 'project')->paginate(10);

        return Inertia::render('Inventory/WarehouseManagement/Dispatches', [
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
}
