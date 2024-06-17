<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\HuaweiAnexe1;
use App\Models\HuaweiAnexe2;
use App\Models\HuaweiProduct;
use App\Models\HuaweiProductLoad;
use App\Imports\HuaweiProductsImport;
use App\Models\PriceGuide1;
use App\Models\PriceGuide2;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;

class HuaweiController extends Controller
{
    public function show ()
    {
        return Inertia::render('Inventory/Huawei/Loads', [
            'loads' => HuaweiProductLoad::paginate(10)
        ]);
    }















    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);

        $file = $request->file('file');

        $huawei_pl = HuaweiProductLoad::create([
            'name' => 'carga nice',
            'path' => 'un path',
        ]);

        // Usa la clase de importación
        Excel::import(new HuaweiProductsImport($huawei_pl), $file);

        return redirect()->back();
    }



    function sanitize_text($text) {
        $text = strtolower($text);
        $text = preg_replace('/["\'(),\s]/', '', $text);
        return $text;
    }
    
}
