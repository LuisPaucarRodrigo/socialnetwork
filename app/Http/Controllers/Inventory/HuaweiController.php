<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\HuaweiProduct;
use App\Models\HuaweiProductLoad;
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















    public function store (Request $request) {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);
        $file = $request->file('file');
        $importData = Excel::toArray([], $file);

        foreach ($importData[0] as $row) {

            $an_sanitize = $this->sanitize_text($row[0]);

            // $annex1 = 
            // if ($an_sanitize === '') {

            // } else {
                
            // }

            HuaweiProduct::create([
                'nombre' => $row[0],
                'apellido' => $row[1],
            ]);
        }

        return back()->with('success', 'Empleados importados con éxito.');


    }




    function sanitize_text($text) {
        $text = strtolower($text);
        $text = preg_replace('/["\'(),\s]/', '', $text);
        return $text;
    }
    
}
