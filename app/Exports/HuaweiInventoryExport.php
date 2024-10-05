<?php

namespace App\Exports;

use App\Models\HuaweiEquipmentSerie;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class HuaweiInventoryExport implements FromView
{

    public function view():View
    {
        $equipments = HuaweiEquipmentSerie::with('huawei_equipment', 'huawei_entry_detail.huawei_entry', 'huawei_entry_detail.latest_huawei_project_resource.huawei_project.huawei_site', 'huawei_entry_detail.latest_huawei_project_resource.huawei_project_liquidation')->get();

        foreach ($equipments as $equipment) {
            $equipment->huawei_equipment->name = $this->sanitizeText2($equipment->huawei_equipment->name);
        }

        return view('Export/HuaweiInventoryExport', [
            'equipments' => $equipments,
        ]);
    }

    private function sanitizeText2($text) {
        // Usa una expresión regular para eliminar el texto entre paréntesis al principio del texto
        return preg_replace('/^\(.*?\)\s*/', '', $text);
    }
}
