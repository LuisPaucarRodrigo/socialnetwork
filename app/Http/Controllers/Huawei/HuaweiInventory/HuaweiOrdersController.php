<?php

namespace App\Http\Controllers\Huawei\HuaweiInventory;

use App\Http\Controllers\Controller;
use App\Models\HuaweiEntry;
use App\Models\HuaweiEntryDetail;
use App\Models\HuaweiEquipment;
use App\Models\HuaweiEquipmentSerie;
use App\Models\HuaweiMaterial;
use App\Models\HuaweiPendingOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Throwable;

class HuaweiOrdersController extends Controller
{
    public function getPendingOrders()
    {
        $pending_orders = HuaweiPendingOrder::where('status', 0)->with([
            'huawei_entry_details' => function ($query) {
                $query->select('id', 'quantity', 'unit_price', 'huawei_order_id', 'huawei_material_id', 'huawei_equipment_serie_id', 'observation') // Ajusta los campos que necesitas
                    ->with([
                        'huawei_material' => function ($subQuery) {
                            $subQuery->select('id', 'name', 'claro_code', 'unit');
                        },
                        'huawei_equipment_serie.huawei_equipment' => function ($subQuery) {
                            $subQuery->select('id', 'name', 'claro_code');
                        }
                    ]);
            }
        ])->paginate(20);

        $pending_orders->getCollection()->transform(function ($pendingOrder) {
            $pendingOrder->huawei_entry_details->each(function ($entryDetail) {
                $entryDetail->makeHidden(['state', 'refund_quantity', 'project_quantity', 'available_quantity', 'assigned_site', 'instalation_state', 'antiquation_state', 'huawei_project_resources', 'huawei_pending_order']); // Oculta los campos deseados
            });

            return $pendingOrder;
        });

        $order_numbers = $pending_orders->pluck('order_number')->toArray();

        return Inertia::render('Huawei/HuaweiInventory/Orders/PendingOrders', [
            'pending_orders' => $pending_orders,
            'order_numbers' => $order_numbers
        ]);
    }

    public function ordersSearchAdvance(Request $request)
    {
        $pending_orders = HuaweiPendingOrder::where('status', 0)->with([
            'huawei_entry_details' => function ($query) {
                $query->select('id', 'quantity', 'unit_price', 'huawei_order_id', 'huawei_material_id', 'huawei_equipment_serie_id', 'observation') // Ajusta los campos que necesitas
                    ->with([
                        'huawei_material' => function ($subQuery) {
                            $subQuery->select('id', 'name', 'claro_code', 'unit');
                        },
                        'huawei_equipment_serie.huawei_equipment' => function ($subQuery) {
                            $subQuery->select('id', 'name', 'claro_code');
                        }
                    ]);
            }
        ]);

        $order_numbers = $pending_orders->get()->pluck('order_number')->filter()->unique()->count();
        $requestOrders = count($request->selectedOrderNumbers);

        if ($requestOrders < $order_numbers) {
            $pending_orders->whereIn('order_number', $request->selectedOrderNumbers);
        }

        $finalOrders = $pending_orders->get()->each(function ($pendingOrder) {
            $pendingOrder->huawei_entry_details->each(function ($entryDetail) {
                $entryDetail->makeHidden([
                    'state',
                    'refund_quantity',
                    'project_quantity',
                    'available_quantity',
                    'assigned_site',
                    'instalation_state',
                    'antiquation_state',
                    'huawei_project_resources',
                    'huawei_pending_order'
                ]);
            });
        });

        return response()->json(['orders' => $finalOrders], 200);
    }

    public function orderAssignGuide(HuaweiPendingOrder $order, Request $request)
    {
        $data = $request->validate([
            'guide_number' => 'required',
            'entry_date' => 'required',
            'archive' => 'required',
            'observation' => 'required'
        ]);

        if ($request->hasFile('archive')) {
            $file = $request->file('archive');
            $data['archive'] = time() . '_' . $request->guide_number . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('documents/huawei/guides/'), $data['archive']);
        }

        $entry = HuaweiEntry::create($data);

        foreach ($order->huawei_entry_details as $entryDetail) {
            $entryDetail->update([
                'huawei_entry_id' => $entry->id
            ]);
        }

        $order->update([
            'status' => true
        ]);

        $pending_orders = HuaweiPendingOrder::where('status', 0)->with([
            'huawei_entry_details' => function ($query) {
                $query->select('id', 'quantity', 'unit_price', 'huawei_order_id', 'huawei_material_id', 'huawei_equipment_serie_id', 'observation') // Ajusta los campos que necesitas
                    ->with([
                        'huawei_material' => function ($subQuery) {
                            $subQuery->select('id', 'name', 'claro_code', 'unit');
                        },
                        'huawei_equipment_serie.huawei_equipment' => function ($subQuery) {
                            $subQuery->select('id', 'name', 'claro_code');
                        }
                    ]);
            }
        ]);

        $finalOrders = $pending_orders->get()->each(function ($pendingOrder) {
            $pendingOrder->huawei_entry_details->each(function ($entryDetail) {
                $entryDetail->makeHidden([
                    'state',
                    'refund_quantity',
                    'project_quantity',
                    'available_quantity',
                    'assigned_site',
                    'instalation_state',
                    'antiquation_state',
                    'huawei_project_resources',
                    'huawei_pending_order'
                ]);
            });
        });

        return response()->json(['orders' => $finalOrders], 200);
    }

    public function fetchPendingOrders()
    {
        $pending_orders = HuaweiPendingOrder::where('status', 0)->get();
        return response()->json(['orders' => $pending_orders]);
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'order_number' => 'required',
            'order_date' => 'required',
            'materials' => 'nullable|array',
            'equipments' => 'nullable|array',
            'warehouse' => 'required',
            'observation' => 'nullable'
        ]);

        if (empty($request->materials) && empty($request->equipments)) {
            return back()->withErrors(['error_empty' => 'Debe agregar al menos un material o equipo.'])->withInput();
        }

        DB::beginTransaction();

        $huawei_pending_order = HuaweiPendingOrder::create([
            'order_number' => $request->order_number,
            'order_date' => $request->order_date,
            'observation' => $request->observation
        ]);

        $warehouse = $request->warehouse;

        try {
            // Manejar materiales
            if ($request->materials) {
                foreach ($request->materials as $material) {
                    if (!$material['order_number'] && !$request->order_number) {
                        DB::rollBack();
                        abort(403, 'Acción no permitida.');
                    }
                    if (!$material['order_date'] && !$request->order_date) {
                        DB::rollBack();
                        abort(403, 'Acción no permitida.');
                    }
                    if (isset($material['material_id']) && $material['material_id']) {
                        HuaweiEntryDetail::create([
                            'huawei_order_id' => $huawei_pending_order->id,
                            'huawei_material_id' => $material['material_id'],
                            'quantity' => $material['quantity'],
                            'unit_price' => $material['unit_price'],
                            'observation' => $material['observation'],
                            'order_number' => $material['order_number'],
                            'order_date' => $material['order_date'],
                        ]);
                    } else {
                        $new_material = HuaweiMaterial::create([
                            'name' => '(' . $warehouse . ') ' . $material['name'],
                            'claro_code' => $material['claro_code'],
                            'unit' => $material['unit'],
                            'prefix' => $warehouse
                        ]);

                        HuaweiEntryDetail::create([
                            'huawei_order_id' => $huawei_pending_order->id,
                            'huawei_material_id' => $new_material->id,
                            'quantity' => $material['quantity'],
                            'unit_price' => $material['unit_price'],
                            'observation' => $material['observation'],
                            'order_number' => $material['order_number'],
                            'order_date' => $material['order_date'],
                        ]);
                    }
                }
            }

            // Manejar equipos
            if ($request->equipments) {
                foreach ($request->equipments as $equipment) {
                    if (isset($equipment['equipment_id']) && $equipment['equipment_id']) {
                        foreach ($equipment['series'] as $serie) {
                            $existing_serie = HuaweiEquipmentSerie::where('huawei_equipment_id', $equipment['equipment_id'])
                                ->where('serie_number', $serie)
                                ->first();

                            if ($existing_serie) {
                                DB::rollBack();
                                return response()->json(['error' => 'Ocurrió un error durante la inserción de datos o se encontraron duplicados'], 500);
                            }

                            if (!$serie['order_number'] && !$request->order_number) {
                                DB::rollBack();
                                abort(403, 'Acción no permitida.');
                            }

                            if (!$serie['order_date'] && !$request->order_date) {
                                DB::rollBack();
                                abort(403, 'Acción no permitida.');
                            }

                            $new_serie = HuaweiEquipmentSerie::create([
                                'huawei_equipment_id' => $equipment['equipment_id'], // Asegúrate de usar 'huawei_equipment_id' aquí
                                'serie_number' => $serie['serie']
                            ]);

                            $huawei_entry_detail = HuaweiEntryDetail::create([
                                'huawei_order_id' => $huawei_pending_order->id,
                                'huawei_equipment_serie_id' => $new_serie->id,
                                'quantity' => 1,
                                'unit_price' => $serie['unit_price'],
                                'assigned_diu' => $serie['assigned_diu'],
                                'observation' => $serie['observation'],
                                'order_number' => $serie['order_number'],
                                'order_date' => $serie['order_date']
                            ]);
                        }
                    } else {
                        // Crear nuevo equipo
                        $new_equipment = HuaweiEquipment::create([
                            'name' => '(' . $warehouse . ') ' . $equipment['name'],
                            'claro_code' => $equipment['claro_code'],
                            'model_id' => $equipment['brand_model'],
                            'prefix' => $warehouse,
                            'unit' => 'Unidad'
                        ]);

                        foreach ($equipment['series'] as $serie) {
                            if (!$serie['order_number'] && !$request->order_number) {
                                DB::rollBack();
                                abort(403, 'Acción no permitida.');
                            }

                            $new_serie = HuaweiEquipmentSerie::create([
                                'huawei_equipment_id' => $new_equipment->id, // Usar el ID del nuevo equipo creado
                                'serie_number' => $serie['serie']
                            ]);

                            $huawei_entry_detail = HuaweiEntryDetail::create([
                                'huawei_order_id' => $huawei_pending_order->id,
                                'huawei_equipment_serie_id' => $new_serie->id,
                                'quantity' => 1,
                                'unit_price' => $serie['unit_price'],
                                'assigned_diu' => $serie['assigned_diu'],
                                'observation' => $serie['observation'],
                                'order_number' => $serie['order_number'],
                                'order_date' => $serie['order_date']
                            ]);
                        }
                    }
                }
            }

            DB::commit(); // Confirmar la transacción si todo va bien

        } catch (Throwable $e) {
            DB::rollBack(); // Revertir transacción en caso de error
            return response()->json(['error' => $e], 500);
        }
    }
}
