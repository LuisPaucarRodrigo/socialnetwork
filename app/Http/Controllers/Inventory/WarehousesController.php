<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Warehouse;
use App\Models\WarehousesHeader;
use App\Models\Header;
use App\Models\Inventory;
use App\Models\ProjectEntry;
use App\Models\RetrievalEntry;
use Illuminate\Support\Facades\Auth;
use App\Providers\GlobalFunctionsServiceProvider;

class WarehousesController extends Controller
{
    //Warehouses
    public function showWarehouses()
    {
        $warehouses = Warehouse::paginate();
        $headers = Header::all();
        $warehouse_headers = WarehousesHeader::with('header')->get();
        return Inertia::render('Inventory/WarehouseManagement/Warehouses', [
            'warehouses' => $warehouses,
            'headers' => $headers,
            'warehouse_headers' => $warehouse_headers,
        ]);
    }

    public function showWarehouse(Warehouse $warehouse)
    {
        $warehouse->load('headers');
        return Inertia::render('Inventory/WarehouseManagement/WarehouseInformation', [
            'warehouse' => $warehouse,
        ]);
    }

    public function storeWarehouse(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'location' => 'required',
            'manager' => 'required',
            'header_ids' => 'array',
        ]);

        $warehouse = Warehouse::create([
            'name' => $request->name,
            'location' => $request->location,
            'manager' => $request->manager,
        ]);

        // Asocia las cabeceras seleccionadas con el almacén
        $warehouse->headers()->attach($request->header_ids);
    }

    public function destroyWarehouse(Warehouse $warehouse)
    {
        $warehouse->delete();
        return to_route('warehouses.warehouses');
    }


    public function showWarehouseHeader(Warehouse $warehouse)
    {
        $warehouse->load(['headers', 'warehouseHeaders']);
        return Inertia::render('Inventory/WarehouseManagement/WarehouseHeader', [
            'warehouse' => $warehouse,
        ]);
    }

    public function storeWarehouseHeader(Warehouse $warehouse, Request $request)
    {
        $request->validate([
            'contentIds' => 'required|array',
        ]);

        // Obtenemos las cabeceras asociadas al almacén
        $warehouseHeaders = WarehousesHeader::where('warehouse_id', $warehouse->id)->get();

        // Iteramos sobre cada almacén y actualizamos el contenido según lo proporcionado en el request
        foreach ($warehouseHeaders as $warehouseHeader) {
            // Obtenemos el id de la cabecera actual
            $headerId = $warehouseHeader->header_id;

            // Verificamos si existe el id de la cabecera en el request
            if (isset($request->contentIds[$headerId])) {
                // Actualizamos el contenido
                $warehouseHeader->update([
                    'content' => $request->contentIds[$headerId],
                ]);
            }
        }
    }

    //RETRIEVALENTRY

    public function retrievalEntryIndex($boolean = false)
    {
        if ($boolean) {
            $retrieval = RetrievalEntry::with('project_entry_liquidation.project_entry.project.preproject', 'entry.inventory.purchase_product.name')
                ->where('state' != true)
                ->paginate();
        } else {
            $retrieval = RetrievalEntry::with('project_entry_liquidation.project_entry.project.preproject', 'entry.inventory.purchase_product.name')
                ->where('state' == true)
                ->paginate();
        }

        return Inertia::render('Inventory/WarehouseManagement/RetrievalEntry', [
            'retrievalEntry' => $retrieval,
            'boolean' => boolval($boolean)
        ]);
    }

    public function retrievalEntry(RetrievalEntry $retrieval)
    {
        $retrieval->update([
            'state' => true
        ]);
    }

    //RETRIEVALPRODUCT

    public function retrievalProduct()
    {
        $retrievalProducts = Inventory::where('warehouse_id', 4)->with('entry.retrieval_entry');
        return Inertia::render('Inventory/WarehouseManagement/RetrievalProduct', [
            'retrievalProducts' => $retrievalProducts
        ]);
    }

    //RETRIEVALDISPATH
    public function retrievalDispatch()
    {
        $retrievalDispatch = ProjectEntry::with('project.preproject')
            ->orderBy('created_at', 'desc')
            ->paginate();
        return Inertia::render('Inventory/WarehouseManagement/RetrievalDispatch', [
            'retrievalDispatch' => $retrievalDispatch
        ]);
    }
}
