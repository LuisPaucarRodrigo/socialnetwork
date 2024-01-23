<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Warehouse;
use App\Models\WarehousesHeader;
use App\Models\Header;

class WarehousesController extends Controller
{
    
    //Headers
    public function showHeaders()
    {
        $headers = Header::all();
        return Inertia::render('Inventory/WarehouseManagement/Headers', [
            'headers' => $headers
        ]);
    }

    public function storeHeader(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        Header::create([
            'name' => $request->name,
        ]);
    }

    public function destroyHeader(Header $header)
    {
        $header->delete();
        return to_route('warehouses.headers');
    }

    //SubSections
    public function showWarehouses()
    {
        $warehouses = Warehouse::all();
        $headers = Header::all();
        $warehouse_headers = WarehousesHeader::with('header')->get();
        return Inertia::render('Inventory/WarehouseManagement/Warehouses', [
            'warehouses' => $warehouses,
            'headers' => $headers,
            'warehouse_headers' => $warehouse_headers
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
            'capacity' => 'required',
            'header_ids' => 'array',
        ]);

        $warehouse = Warehouse::create([
            'name' => $request->name,
            'location' => $request->location,
            'capacity' => $request->capacity,
        ]);
    
        // Asocia las cabeceras seleccionadas con el almacén
        $warehouse->headers()->attach($request->header_ids);
    }

    public function updateWarehouse(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'name' => 'required|string',
            'location' => 'required',
            'capacity' => 'required',
            'header_ids' => 'array',
        ]);

        $warehouse->update([
            'name' => $request->name,
            'location' => $request->location,
            'capacity' => $request->capacity,
        ]);

        // Sincroniza las cabeceras seleccionadas con el almacén
        $warehouse->headers()->sync($request->header_ids);
    }


    public function destroyWarehouse(Warehouse $warehouse)
    {
        $warehouse->delete();
        return to_route('warehouses.warehouses');
    }

}
