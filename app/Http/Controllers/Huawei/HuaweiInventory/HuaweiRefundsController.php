<?php

namespace App\Http\Controllers\Huawei\HuaweiInventory;

use App\Constants\HuaweiConstants;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Huawei\Utils\HuaweiUtils;
use App\Models\HuaweiEntryDetail;
use App\Models\HuaweiRefund;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HuaweiRefundsController extends Controller
{
    private static array $data;

    public function __construct()
    {
        self::$data = [
            'operators' => HuaweiConstants::getOperators(),
        ];
    }

    public function getRefunds($warehouse, $equipment = null)
    {
        if ($equipment) {
            $refunds = HuaweiRefund::with('huawei_entry_detail.huawei_equipment_serie.huawei_equipment', 'huawei_entry_detail.huawei_entry')
                ->whereHas('huawei_entry_detail', function ($query) {
                    $query->whereNull('huawei_material_id');
                })
                ->whereHas('huawei_entry_detail.huawei_equipment_serie.huawei_equipment', function ($query) use ($warehouse) {
                    $query->where('prefix', $warehouse);
                })
                ->paginate(10);
        } else {
            $refunds = HuaweiRefund::with('huawei_entry_detail.huawei_material', 'huawei_entry_detail.huawei_entry')
                ->whereHas('huawei_entry_detail', function ($query) {
                    $query->whereNull('huawei_equipment_serie_id');
                })
                ->whereHas('huawei_entry_detail.huawei_material', function ($query) use ($warehouse) {
                    $query->where('prefix', $warehouse);
                })
                ->paginate(10);
        }

        foreach ($refunds as $refund) {
            if ($equipment) {
                $refund->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name = HuaweiUtils::sanitizeText2($refund->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name);
            } else {
                $refund->huawei_entry_detail->huawei_material->name = HuaweiUtils::sanitizeText2($refund->huawei_entry_detail->huawei_material->name);
            }
        }

        return Inertia::render('Huawei/HuaweiInventory/Refunds/Refunds', [
            'refunds' => $refunds,
            'equipment' => $equipment,
            'warehouse' => $warehouse,
            'data' => self::$data
        ]);
    }

    public function searchRefunds($warehouse, $request, $equipment = null)
    {
        $searchTerm = strtolower($request);

        $query = HuaweiRefund::query();

        if ($equipment) {
            $query->whereHas('huawei_entry_detail.huawei_equipment_serie.huawei_equipment', function ($query) use ($warehouse) {
                $query->where('prefix', $warehouse);
            })
                ->whereHas('huawei_entry_detail', function ($query) {
                    $query->whereNull('huawei_material_id');
                });
            $query->where(function ($query) use ($searchTerm) {
                $query->whereHas('huawei_entry_detail.huawei_equipment_serie.huawei_equipment', function ($query) use ($searchTerm) {
                    $query->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"]);
                })
                    ->orWhereHas('huawei_entry_detail.huawei_equipment_serie', function ($query) use ($searchTerm) {
                        $query->whereRaw('LOWER(serie_number) LIKE ?', ["%{$searchTerm}%"]);
                    })
                    ->orWhereHas('huawei_entry_detail.huawei_entry', function ($query) use ($searchTerm) {
                        $query->whereRaw('LOWER(guide_number) LIKE ?', ["%{$searchTerm}%"]);
                    })
                    ->orWhereHas('huawei_entry_detail', function ($query) use ($searchTerm) {
                        $query->whereRaw('LOWER(order_number) LIKE ?', ["%{$searchTerm}%"]);
                    });
            });
        } else {
            $query->whereHas('huawei_entry_detail.huawei_material', function ($query) use ($warehouse) {
                $query->where('prefix', $warehouse);
            })
                ->whereHas('huawei_entry_detail', function ($query) {
                    $query->whereNull('huawei_equipment_serie_id');
                });
            $query->where(function ($query) use ($searchTerm) {
                $query->whereHas('huawei_entry_detail.huawei_material', function ($query) use ($searchTerm) {
                    $query->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"]);
                })
                    ->orWhereHas('huawei_entry_detail.huawei_entry', function ($query) use ($searchTerm) {
                        $query->whereRaw('LOWER(guide_number) LIKE ?', ["%{$searchTerm}%"]);
                    })
                    ->orWhereHas('huawei_entry_detail', function ($query) use ($searchTerm) {
                        $query->whereRaw('LOWER(order_number) LIKE ?', ["%{$searchTerm}%"]);
                    });
            });
        }

        $refunds = $query->with('huawei_entry_detail.huawei_equipment_serie.huawei_equipment', 'huawei_entry_detail.huawei_material', 'huawei_entry_detail.huawei_entry')->get();
        foreach ($refunds as $refund) {
            if ($equipment) {
                $refund->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name = HuaweiUtils::sanitizeText2($refund->huawei_entry_detail->huawei_equipment_serie->huawei_equipment->name);
            } else {
                $refund->huawei_entry_detail->huawei_material->name = HuaweiUtils::sanitizeText2($refund->huawei_entry_detail->huawei_material->name);
            }
        }

        return Inertia::render('Huawei/HuaweiInventory/Refunds/Refunds', [
            'refunds' => $refunds,
            'equipment' => $equipment,
            'search' => $request,
            'warehouse' => $warehouse,
            'data' => self::$data
        ]);
    }

    public function refund(Request $request, $equipment = null)
    {
        $huawei_entry_detail_founded = HuaweiEntryDetail::find($request->huawei_entry_detail_id);

        if ($huawei_entry_detail_founded->state !== 'Disponible') {
            abort(403, 'Acción no permitida');
        }

        $request->validate([
            'huawei_entry_detail_id' => 'required',
            'quantity' => 'nullable',
            'observation' => 'nullable'
        ]);

        // Encontrar el detalle de entrada por ID
        $entryDetail = HuaweiEntryDetail::findOrFail($request->huawei_entry_detail_id);

        // Obtener la cantidad disponible usando el campo calculado
        $availableQuantity = $entryDetail->available_quantity;

        // Si es equipo, la cantidad siempre es 1
        if ($equipment) {
            if ($availableQuantity < 1) {
                return redirect()->back()->withErrors(['error_exceed' => 'No hay suficiente cantidad disponible para la devolución.']);
            }
            HuaweiRefund::create([
                'huawei_entry_detail_id' => $request->huawei_entry_detail_id,
                'quantity' => 1,
                'observation' => $request->observation
            ]);
        } else {
            // Verificar si la cantidad solicitada es menor o igual a la cantidad disponible
            if ($request->quantity > $availableQuantity) {
                return redirect()->back()->withErrors(['error_exceed' => 'La cantidad solicitada excede la cantidad disponible.']);
            }
            HuaweiRefund::create([
                'huawei_entry_detail_id' => $request->huawei_entry_detail_id,
                'quantity' => $request->quantity,
                'observation' => $request->observation
            ]);
        }

        return redirect()->back();
    }

}