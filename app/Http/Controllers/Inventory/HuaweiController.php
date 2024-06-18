<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\HuaweiAnexe1;
use App\Models\HuaweiAnexe2;
use App\Models\HuaweiProduct;
use App\Models\HuaweiProductLoad;
use App\Models\PriceGuide1;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class HuaweiController extends Controller
{
    public function show ()
    {
        return Inertia::render('Inventory/Huawei/Loads', [
            'loads' => HuaweiProductLoad::paginate(10)
        ]);
    }















    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required',
    //     ]);

    //     $file = $request->file('file');

    //     $huawei_pl = HuaweiProductLoad::create([
    //         'name' => 'carga nice',
    //         'path' => 'un path',
    //     ]);

    //     // Usa la clase de importación
    //     Excel::import(new HuaweiProductsImport($huawei_pl), $file);

    //     return redirect()->back();
    // }
    public function import(Request $request)
    {
        try {
            // Validar que el archivo es un Excel
            $data = $request->validate([
                'file' => 'required|mimes:xls,xlsx'
            ]);

            // Manejar la carga del archivo
            $document = $request->file('file');
            $documentName = time() . '_' . $document->getClientOriginalName();
            $document->move(public_path('uploads/huawei/'), $documentName);

            $filePath = "uploads/huawei/$documentName";
            $path = public_path($filePath);

            // Leer el archivo Excel
            $spreadsheet = IOFactory::load($path);

            // Obtener la primera hoja
            /** @var Worksheet $sheet */
            $sheet = $spreadsheet->getSheet(0);

            // Definir el rango de lectura: A1:D hasta la última fila
            $startCell = 'A1';
            $endCell = 'E' . $sheet->getHighestRow();
            $range = "$startCell:$endCell";

            // Leer el rango especificado
            $data = $sheet->rangeToArray($range, null, true, true, true);

            // Array para almacenar los objetos
            $rowsAsObjects = [];

            // Recorrer las filas y convertir a objetos
            foreach ($data as $index => $row) {
                // Saltar la primera fila si es el encabezado
                if ($index == 0) continue;

                $rowObject = (object)[
                    'A' => $row['A'],
                    'B' => $row['B'],
                    'C' => $row['C'],
                    'D' => $row['D'],
                    'E' => $row['E'],
                ];

                $rowsAsObjects[] = $rowObject;
            }

            if ($rowsAsObjects) {
                $hpl = HuaweiProductLoad::create([
                    'name' => $documentName,
                    'path' => $path
                ]);

                foreach ($rowsAsObjects as $item){
                    $sanitizedB = $this->sanitize_text($item->B);
                    $huaweiAnexe1 = HuaweiAnexe1::where('name', $sanitizedB)->first();
                    if ($huaweiAnexe1){
                        $price_guide1 = PriceGuide1::where('ha1_id', $huaweiAnexe1->id)->where('bidding_area', $item->E);
                        HuaweiProduct::create([
                            'hpl_id' => $hpl->id,
                            'name' => $item->A,
                            'anexe_name' => $item->B,
                            'anexe_unit' => $item->C,
                            'quantity' => $item->D,
                            'pg1_id' => $price_guide1->id
                        ]);
                    }else{
                        $huaweiAnexe2 = HuaweiAnexe2::where('name', $sanitizedB)->first();
                        if ($huaweiAnexe2){
                            $price_guide2 = PriceGuide1::where('ha1_id', $huaweiAnexe1->id)->where('bidding_area', $item->E);
                            HuaweiProduct::create([
                                'hpl_id' => $hpl->id,
                                'name' => $item->A,
                                'anexe_name' => $item->B,
                                'anexe_unit' => $item->C,
                                'quantity' => $item->D,
                                'pg2_id' => $price_guide2->id
                            ]);
                        }else{
                            abort(403, 'error');
                        }
                    }
                }
            }

        } catch (\Exception $e) {
            // Manejo de excepciones para capturar errores
            dd('Error al procesar el archivo: ' . $e->getMessage());
        }
    }

    // Función para limpiar texto
    function sanitize_text($text)
    {
        if (is_array($text)) {
            $text = implode(' ', $text); // Convierte el array en una cadena
        }

        if (is_string($text)) {
            $text = strtolower($text);
            $text = preg_replace('/["\'(),\s]/', '', $text);
        } else {
            $text = ''; // Maneja el caso donde no es ni array ni string
        }

        return $text;
    }

}
