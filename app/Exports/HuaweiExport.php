<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class HuaweiExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Hoja1' => new class implements FromView {
                public function view(): View
                {
                    return view('Export.Sheet1');
                }
            },
            'Hoja2' => new class implements FromView {
                public function view(): View
                {
                    return view('Export.Sheet2');
                }
            },
        ];
    }

}
