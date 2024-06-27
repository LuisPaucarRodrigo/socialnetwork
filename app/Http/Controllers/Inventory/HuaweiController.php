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
use Maatwebsite\Excel\HeadingRowImport;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response;

class HuaweiController extends Controller
{
    public function show ()
    {
        $loads = HuaweiProductLoad::orderByDesc('created_at')->paginate(10);

        return Inertia::render('Inventory/Huawei/Loads', [
            'loads' => $loads,
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
            // Validar que el archivo es un Excel
            $data = $request->validate([
                'file' => 'required|mimes:xls,xlsx',
                'zone' => 'required'
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
            $endCell = 'D' . $sheet->getHighestRow();
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
                        $price_guide1 = PriceGuide1::where('ha1_id', $huaweiAnexe1->id)->where('bidding_area', $request->zone)->first();
                        HuaweiProduct::create([
                            'hpl_id' => $hpl->id,
                            'name' => $item->A,
                            'anexe_name' => $item->B,
                            'anexe_unit' => $item->C,
                            'quantity' => $item->D,
                            'pg1_id' => $price_guide1 ? $price_guide1->id : null,
                            'zone' => $request->zone
                        ]);
                    }else{
                        $huaweiAnexe2 = HuaweiAnexe2::where('name', $sanitizedB)->first();
                        if ($huaweiAnexe2){
                            $price_guide2 = PriceGuide2::where('ha2_id', $huaweiAnexe2->id)->where('bidding_area', $request->zone)->first();
                            HuaweiProduct::create([
                                'hpl_id' => $hpl->id,
                                'name' => $item->A,
                                'anexe_name' => $item->B,
                                'anexe_unit' => $item->C,
                                'quantity' => $item->D,
                                'pg2_id' => $price_guide2 ? $price_guide2->id : null,
                                'zone' => $request->zone
                            ]);
                        }else{
                            HuaweiProduct::create([
                                'hpl_id' => $hpl->id,
                                'name' => $item->A,
                                'anexe_name' => $item->B,
                                'anexe_unit' => $item->C,
                                'quantity' => $item->D,
                                'zone' => $request->zone
                            ]);
                        }
                    }
                }
            }
    }

    public function renderByLoad($loadId, $noPg = null)
    {
        $huawei_products = HuaweiProduct::where('hpl_id', $loadId)
                            ->with('price_guide1', 'price_guide2')
                            ->paginate(5);
        $allHuaweiProducts = HuaweiProduct::where('hpl_id', $loadId)
                            ->with('price_guide1', 'price_guide2')
                            ->get();
        $total = 0;

        foreach ($allHuaweiProducts as $product) {
            if ($product->price_guide1) {
                $total += $product->price_guide1->unit_price * $product->quantity;
            } elseif ($product->price_guide2) {
                $total += $product->price_guide2->unit_price * $product->quantity;
            }
        }

        if ($noPg) {
            $huawei_products = HuaweiProduct::where('hpl_id', $loadId)
                                ->where(function ($query) {
                                    $query->whereNull('pg1_id')
                                        ->whereNull('pg2_id');
                                })
                                ->orderBy('created_at', 'desc') // Opcional: Ordenar por fecha de creación descendente
                                ->paginate(5);
        }

        return Inertia::render('Inventory/Huawei/HuaweiProducts', [
            'huawei_products' => $huawei_products,
            'loadId' => $loadId,
            'noPg' => $noPg,
            'total' => $total
        ]);
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

    public function searchSimilarities(HuaweiProduct $huawei_product)
    {
        // Obtener el nombre del anexo del HuaweiProduct
        $anexeName = $huawei_product->anexe_name;

        // Definir el umbral de similitud
        $similarityThreshold = 70; // Por ejemplo, 70%

        // Inicializar variables para almacenar los resultados
        $result1 = null;
        $result2 = null;

        // Buscar en HuaweiAnexe1
        $huaweiAnexe1Records = HuaweiAnexe1::all();
        foreach ($huaweiAnexe1Records as $record) {
            similar_text($record->original_name, $anexeName, $percent);
            if ($percent >= $similarityThreshold) {
                $result1 = $record;
                break;
            }
        }

        // Buscar en HuaweiAnexe2
        $huaweiAnexe2Records = HuaweiAnexe2::all();
        foreach ($huaweiAnexe2Records as $record) {
            similar_text($record->original_name, $anexeName, $percent);
            if ($percent >= $similarityThreshold) {
                $result2 = $record;
                break;
            }
        }

        // Preparar la respuesta JSON
        $response = [
            'result1' => $result1,
            'result2' => $result2,
        ];

        return response()->json($response);
    }

    public function associate($loadId, HuaweiProduct $huawei_product, Request $request)
    {
        if ($request->anexe1) {
            // Buscar el HuaweiAnexe1 por su ID y cargar la relación price_guide_1
            $anexe1 = HuaweiAnexe1::where('id', $request->anexe1)->first();

            if ($anexe1) {
                // Verificar si el HuaweiAnexe1 tiene asociado un price_guide_1
                $priceGuide1 = PriceGuide1::where('ha1_id', $anexe1->id)->where('bidding_area', $huawei_product->zone)->first();

                if ($priceGuide1) {
                    // Asociar el HuaweiProduct con el price_guide_1 encontrado
                    $huawei_product->pg1_id = $priceGuide1->id;
                    $huawei_product->save();
                }
            }
        } elseif ($request->anexe2) {
            // Buscar el HuaweiAnexe2 por su ID y cargar la relación price_guide_2
            $anexe2 = HuaweiAnexe2::where('id', $request->anexe2)->first();

            if ($anexe2) {
                // Verificar si el HuaweiAnexe2 tiene asociado un price_guide_2
                $priceGuide2 = PriceGuide2::where('ha2_id', $anexe2->id)->where('bidding_area', $huawei_product->zone)->first();

                if ($priceGuide2) {
                    // Asociar el HuaweiProduct con el price_guide_2 encontrado
                    $huawei_product->pg2_id = $priceGuide2->id;
                    $huawei_product->save();
                }
            }
        }
    }

    public function exportHuaweiProducts($loadId, Request $request)
    {
        // Obtener los datos a exportar
        $products = HuaweiProduct::where('hpl_id', $loadId)->with('price_guide1', 'price_guide2')->get();
        $total = 0;

        foreach ($products as $product) {
            if ($product->price_guide1) {
                $total += $product->price_guide1->unit_price * $product->quantity;
            } elseif ($product->price_guide2) {
                $total += $product->price_guide2->unit_price * $product->quantity;
            }
        }
        // Verificar si los datos no están vacíos
        if ($products->isEmpty()) {
            return response()->json(['error' => 'No hay datos para exportar'], 404);
        }

        // Nombre del archivo
        $fileName = 'huawei_products.xlsx';

        // Descargar usando una clase de exportación anónima
        return Excel::download(new class($products, $total) implements \Maatwebsite\Excel\Concerns\FromView {
            private $products;
            private $total;

            public function __construct($products, $total)
            {
                $this->products = $products;
                $this->total = $total;
            }

            public function view(): \Illuminate\Contracts\View\View
            {
                return view('pdf.HuaweiCost', [
                    'products' => $this->products,
                    'total' => $this->total
                ]);
            }
        }, $fileName);
    }

}
