<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\HuaweiAnexe1;
use App\Models\HuaweiAnexe2;
use App\Models\HuaweiProduct;
use App\Models\HuaweiProductLoad;
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















    public function store (Request $request) {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);
        $file = $request->file('file');
        $importData = Excel::toArray([], $file);

        $huawei_pl = HuaweiProductLoad::create([
            'name' => 'carga nice',
            'path' => 'un path',
        ]);


        foreach ($importData[0] as $row) {

            $an_sanitize = $this->sanitize_text($row[1]);

            $annex1 = HuaweiAnexe1::where('name', $an_sanitize)->get();
            $annex2 = HuaweiAnexe2::where('name', $an_sanitize)->get();
            if ($annex1->count() > 1 || $annex2->count() > 1 ){
                abort(403, "Error: Más de un nombre coincide");
            }
            if ($annex1->count() + $annex2->count() > 0 ){
                abort(403, "Error: No hubo coincidencia de nombre");
            }
            $rpta_annex =  $annex1->count() > 0 ? $annex1->first() : $annex2->first();

            $zone = $row[4];

            $price_guide = $annex1->count() > 0 ? PriceGuide1::where('bidding_area', $zone)->where('ha1_id',$rpta_annex->id )->get() : PriceGuide2::where('bidding_area', $zone)->where('ha1_id',$rpta_annex->id )->get();
            

            HuaweiProduct::create([
                'hpl_id' => $huawei_pl->id,
                'name' => $row[0],
                'anexe_name' => $row[1],
                'anexe_unit' => $row[2],
                'quantity' => $row[3],
                'pg1_id' => $annex1->count() > 0 ? $price_guide->id: null,
                'pg2_id' => $annex2->count() > 0 ? $price_guide->id: null,
            ]);
        }

        return redirect()->back();


    }




    function sanitize_text($text) {
        $text = strtolower($text);
        $text = preg_replace('/["\'(),\s]/', '', $text);
        return $text;
    }
    
}
