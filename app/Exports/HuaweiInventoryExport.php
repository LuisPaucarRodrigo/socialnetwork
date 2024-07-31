<?php

namespace App\Exports;

use App\Models\HuaweiEquipmentSerie;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class HuaweiInventoryExport implements FromView
{

    public function view():View
    {
        return view('Export/HuaweiInventoryExport', [
            'equipments' => HuaweiEquipmentSerie::with('huawei_equipment', 'huawei_entry_detail.huawei_entry', 'huawei_entry_detail.latest_huawei_project_resource.huawei_project.huawei_site')->get(),
        ]);
    }
}
