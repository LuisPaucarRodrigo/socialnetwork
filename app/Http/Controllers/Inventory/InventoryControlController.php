<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NetworkEquipment;
use App\Models\MobileDevice;
use App\Models\Provider;
use App\Models\ComponentOrMaterial;
use App\Http\Requests\Inventory\NetworkEquipmentRequest;
use App\Http\Requests\Inventory\MobileDeviceRequest;
use App\Http\Requests\Inventory\ComponentAndMaterialRequest;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\Inventory\NetworkEquipmentImport;

class InventoryControlController extends Controller
{
    public function index()
    {
        return Inertia::render('Inventory/InventoryControl/Index');
    }
    
    public function NetworkEquipment()
    {
        $networkEquipments = NetworkEquipment::all() ;
        return Inertia::render('Inventory/InventoryControl/NetworkEquipment/Index',[
            'network_equipments'=> $networkEquipments,
        ]);
    }
    public function NewNetworkEquipment()
    {
        $providers = Provider::all('company_name');
        return Inertia::render('Inventory/InventoryControl/NetworkEquipment/Information',[
            'providers'=> $providers,
            'title'=>'Nuevo Equipo de Red',
        ]);
    }
    public function CreateNetworkEquipment(NetworkEquipmentRequest $request)
    {
        $NetworkEquipment = NetworkEquipment::create($request->validated());
        return redirect()->route('inventory.NetworkEquipment.index');
    }
    public function EditNetworkEquipment($NeId)
    {
        $NetworkEquipment = NetworkEquipment::find($NeId);
        $providers = Provider::all('company_name');
        return Inertia::render('Inventory/InventoryControl/NetworkEquipment/Information',[
            'network_equipment'=> $NetworkEquipment,
            'providers'=> $providers,
            'title'=>'Editar Equipo de Red',
        ]);
    }
    public function UpdateNetworkEquipment(NetworkEquipmentRequest $request, $NeId)
    {
        $NetworkEquipment = NetworkEquipment::find($NeId);
        $NetworkEquipment->update($request->all());
        return redirect()->route('inventory.NetworkEquipment.index');
    }
    public function DeleteNetworkEquipment($NeId)
    {
        $NetworkEquipment = NetworkEquipment::find($NeId);
        $NetworkEquipment->delete();
        return back();
    }
    public function ImportNetworkEquipment(Request $request)
    {
        //dd($request);
        if ($request->has('importar')) {
            //dd($request->importar);
            try {
                Excel::import(new NetworkEquipmentImport, $request->file('importar'));
                dd("Correcto");
                return back()->with('success', 'Los datos se importaron correctamente.');
            } catch (\Exception $e) {
                dd("Error");
                return back()->with('error', 'Hubo un error al importar los datos. Por favor, verifica el archivo excel.');
            }
        } else {
            return back();
        }   
    }

    public function MobileDevices()
    {
        $MovileDevices = MobileDevice::all();
        return Inertia::render('Inventory/InventoryControl/MobileDevices/Index',[
            'mobile_devices'=> $MovileDevices,
        ]);
    }
    
    public function NewMobileDevices()
    {
        return Inertia::render('Inventory/InventoryControl/MobileDevices/Information',[
            'title'=>'Nuevo Dipositivo Movil',
        ]);
    }
    public function CreateMobileDevices(MobileDeviceRequest $request)
    {
        //dd($request->validate());
        $MovileDevice = MobileDevice::create($request->validated());
        return redirect()->route('inventory.MobileDevices.index');
    }
    public function EditMobileDevices($MdId)
    {
        $MobileDevice = MobileDevice::find($MdId);
        return Inertia::render('Inventory/InventoryControl/MobileDevices/Information',[
            'mobile_device' => $MobileDevice,
            'title'=>'Modificar Información del Dipositivo Movil',
        ]);
    }
    public function UpdateMobileDevices(MobileDeviceRequest $request, $MdId)
    {
        $MobileDevice = MobileDevice::find($MdId);
        $MobileDevice->update($request->all());
        return redirect()->route('inventory.MobileDevices.index');
    }
    public function DeleteMobileDevices($MdId)
    {
        $MovileDevice = MobileDevice::find($MdId);
        $MovileDevice->delete();
        return back();
    }

    public function ComponentsAndMaterials()
    {
        $ComponentsOrMaterials = ComponentOrMaterial::all();
        return Inertia::render('Inventory/InventoryControl/ComponentsAndMaterials/Index',[
            'components_or_materials' => $ComponentsOrMaterials
        ]);
    }
    public function NewComponentsAndMaterials()
    {
        return Inertia::render('Inventory/InventoryControl/ComponentsAndMaterials/Information',[
            'title'=>'Nuevo Componente o Material',
        ]);
    }
    public function CreateComponentsAndMaterials(ComponentAndMaterialRequest $request)
    {
        $ComponentAndMaterial = ComponentOrMaterial::create($request->validated());
        return redirect()->route('inventory.ComponentsAndMaterials.index');
    }
    public function EditComponentsAndMaterials($CmId)
    {
        $ComponentOrMaterial = ComponentOrMaterial::find($CmId);
        return Inertia::render('Inventory/InventoryControl/ComponentsAndMaterials/Information',[
            'title'=>'Editar Componente o Material',
            'component_or_material' => $ComponentOrMaterial
        ]);
    }
    public function UpdateComponentsAndMaterials(ComponentAndMaterialRequest $request, $CmId)
    {
        $ComponentOrMaterial = ComponentOrMaterial::find($CmId);
        $ComponentOrMaterial->update($request->all());
        return redirect()->route('inventory.ComponentsAndMaterials.index');
    }
    public function DeleteComponentsAndMaterials($CmId)
    {
        $ComponentOrMaterial = ComponentOrMaterial::find($CmId);
        $ComponentOrMaterial->delete();
        return back();
    }
}
