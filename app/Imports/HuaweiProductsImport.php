<?php

namespace App\Imports;

use App\Models\HuaweiProduct;
use App\Models\HuaweiProductLoad;
use App\Models\HuaweiAnexe1;
use App\Models\HuaweiAnexe2;
use App\Models\PriceGuide1;
use App\Models\PriceGuide2;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class HuaweiProductsImport implements ToCollection
{
    public function __construct($huawei_pl)
    {
        $this->huawei_pl = $huawei_pl;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Tomar solo las primeras cinco columnas (A, B, C, D, E)
            $row = array_slice($row->toArray(), 0, 5);

             // Descomentar esta línea si quieres depurar y ver las filas

            $an_sanitize = $this->sanitize_text($row[1]);

            
            
            $annex1 = HuaweiAnexe1::where('name', $an_sanitize)->get();
            
            
            $annex2 = HuaweiAnexe2::where('name', $an_sanitize)->get();
            if ($annex1->count() > 1 || $annex2->count() > 1 ){
                abort(403, "Error: Más de un nombre coincide");
            }
            // if (($annex1->count() + $annex2->count()) === 0 ){
            //     abort(403, "Error: No hubo coincidencia de nombre");
            // }
            $rpta_annex =  $annex1->count() > 0 ? $annex1->first() : $annex2->first();

            $zone = $row[4];
            
            $price_guide = $annex1->count() > 0 ? PriceGuide1::where('bidding_area', $zone)->where('ha1_id',$rpta_annex?->id )->first() : PriceGuide2::where('bidding_area', $zone)->where('ha2_id',$rpta_annex?->id )->first();
            HuaweiProduct::create([
                'hpl_id' => $this->huawei_pl->id,
                'name' => $row[0],
                'anexe_name' => $row[1],
                'anexe_unit' => $row[2],
                'quantity' => $row[3],
                'pg1_id' => $annex1->count() > 0 ? $price_guide->id: null,
                'pg2_id' => $annex2->count() > 0 ? $price_guide->id: null,
            ]);
        }
    }

    private function sanitize_text($text) {
        $text = strtolower($text);
        $text = preg_replace('/["\'(),\s]/', '', $text);
        return $text;
    }
}
