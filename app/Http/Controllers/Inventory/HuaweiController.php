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
use Illuminate\Support\Facades\Validator;

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
            'file' => 'required',
        ]);

        $file = $request->file('file');

        $huawei_pl = HuaweiProductLoad::create([
            'name' => 'carga nice',
            'path' => 'un path',
        ]);

        Excel::import(new HuaweiProductsImport($huawei_pl), $file);

        return redirect()->back();
    }

    function sanitize_text($text) {
        $text = strtolower($text);
        $text = preg_replace('/["\'(),\s]/', '', $text);
        return $text;
    }

    public function import(Request $request)
    {
        // Validar que el archivo es un Excel
        $data = $request->validate([
            'file' => 'required'
        ]);

        $documentName = '';

        if ($request->hasFile('file')) {
            $document = $request->file('file');
            $documentName = time() . '_' . $document->getClientOriginalName();
            $document->move(public_path('uploads/huawei/'), $documentName);
        }

        $filePath = "uploads/huawei/$documentName";
        $path = public_path($filePath);

        // Cargar el archivo utilizando Maatwebsite Excel
        $data = Excel::toArray([], $path);
        // Iterar sobre cada fila
        foreach ($data as $row) {

            $service = $this->sanitize_text($row[0]);       // Columna 'A'
            $unit = $this->sanitize_text($row[1]);      // Columna 'B'
            $quantity = $this->sanitize_text($row[2]);

            dd($service, $unit, $quantity);// Columna 'C'

        }
    }

}
