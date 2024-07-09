<?php

namespace App\Http\Controllers\Huawei;

use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HuaweiExport;

class ExportController extends Controller
{
    public function export()
    {
        return Excel::download(new HuaweiExport, 'template.xlsx');
    }
}

