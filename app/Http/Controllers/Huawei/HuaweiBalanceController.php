<?php

namespace App\Http\Controllers\Huawei;

use App\Exports\HuaweiGeneralCostsExport;
use App\Exports\HuaweiGeneralEarningsExport;
use App\Http\Controllers\Controller;
use App\Models\HuaweiBalanceCost;
use App\Models\HuaweiBalanceEarning;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class HuaweiBalanceController extends Controller
{
    protected $total;

    public function __construct()
    {
        $total_main = HuaweiBalanceEarning::whereNotNull('deposit_date')
        ->sum('main_amount');
        $total_detraction = HuaweiBalanceEarning::whereNotNull('detraction_date')
            ->sum('detraction_amount');
        $this->total = $total_main + $total_detraction;
    }

    public function getGeneralSummary ()
    {
        $total_variable_costs = HuaweiBalanceCost::where('type', 1)->sum('amount');
        $total_static_costs = HuaweiBalanceCost::where('type', 0)->sum('amount');
        $total_earnings = HuaweiBalanceEarning::whereNotNull('deposit_date')->sum('amount');
        $total_main_earnings = HuaweiBalanceEarning::whereNotNull('deposit_date')->sum('main_amount');
        $total_detraction_earnings = HuaweiBalanceEarning::whereNotNull('detraction_date')->sum('detraction_amount');
        return Inertia::render('Huawei/GeneralBalance', [
            'total_variable_costs' => $total_variable_costs,
            'total_static_costs' => $total_static_costs,
            'total_earnings' => $total_earnings,
            'total_main_earnings' => $total_main_earnings,
            'total_detraction' => $total_detraction_earnings
        ]);
    }

    public function getSummary ()
    {
        $additionalCosts = HuaweiBalanceCost::where('type', 1)->sum('amount');
        $acArr = HuaweiBalanceCost::where('type', 1)
            ->select('expense_type', DB::raw('SUM(amount) as total_amount'))
            ->groupBy('expense_type')
            ->get();
        $acExpensesAmounts = $acArr->map(function($cost){
            return [
                'expense_type' => $cost->expense_type,
                'total_amount' => $cost->total_amount
            ];
        })->toArray();


        $staticCosts = HuaweiBalanceCost::where('type', 0)->sum('amount');
        $scArr = HuaweiBalanceCost::where('type', 0)
            ->select('expense_type', DB::raw('SUM(amount) as total_amount'))
            ->groupBy('expense_type')
            ->get();
        $scExpensesAmounts = $scArr->map(function($cost) {
            return [
                'expense_type' => $cost->expense_type,
                'total_amount' => $cost->total_amount,
            ];
        })->toArray();

        return Inertia::render('Huawei/GeneralCostSummary', [
            'additionalCosts' => $additionalCosts,
            'acExpensesAmounts' => $acExpensesAmounts,
            'scExpensesAmounts' => $scExpensesAmounts,
            'staticCosts' => $staticCosts,
        ]);
    }

    //costs

    public function getCosts($type = null)
    {
        if ($type){
            $costs = HuaweiBalanceCost::where('type', 1)->orderBy('created_at', 'desc')->paginate(10);
        }else{
            $costs = HuaweiBalanceCost::where('type', 0)->orderBy('created_at', 'desc')->paginate(10);
        }

        return Inertia::render('Huawei/BalanceCosts', [
            'costs' => $costs,
            'type' => $type
        ]);
    }

    public function searchCosts ($request, $type = null)
    {
        $searchTerm = strtolower($request);
        $query = HuaweiBalanceCost::query();
        if ($type){
            $query->where('type', 1)->where(function ($query) use ($searchTerm) {
                $query->where('expense_type', 'like', '%' . $searchTerm . '%')
                      ->orWhere('zone', 'like', '%' . $searchTerm . '%');
            });
        }else{
            $query->where('type', 0)->where(function ($query) use ($searchTerm) {
                $query->where('expense_type', 'like', '%' . $searchTerm . '%')
                      ->orWhere('zone', 'like', '%' . $searchTerm . '%');
            });
        }
        $filteredCosts = $query->get();

        return Inertia::render('Huawei/BalanceCosts', [
            'costs' => $filteredCosts,
            'search' => $request,
            'type' => $type
        ]);
    }

    public function storeCosts (Request $request, $type = null)
    {
        $data = $request->validate([
            'expense_type' => 'required',
            'zone' => 'required',
            'cost_date' => 'required',
            'amount' => 'required'
        ]);

        $data['type'] = $type ? 1 : 0;

        HuaweiBalanceCost::create($data);

        return redirect()->back();
    }

    public function updateCost (HuaweiBalanceCost $huawei_balance_cost, Request $request)
    {
        $data = $request->validate([
            'expense_type' => 'required',
            'zone' => 'required',
            'cost_date' => 'required',
            'amount' => 'required'
        ]);

        $huawei_balance_cost->update($data);

        return redirect()->back();
    }

    public function deleteCost (HuaweiBalanceCost $huawei_balance_cost)
    {
        $huawei_balance_cost->delete();

        return redirect()->back();
    }

    public function search_costs (Request $request, $type = null)
    {
        $result = $type ? HuaweiBalanceCost::where('type', 1) : HuaweiBalanceCost::where('type', 0);
        $count = $type ? 6 : 7;
        if (count($request->selectedExpenseTypes) < $count) {
            $result = $result->whereIn('expense_type', $request->selectedExpenseTypes);
        }

        if ($request->search) {
            $searchTerms = $request->input('search');
            $result = $result->where(function($query) use ($searchTerms){
                $query->where('cost_date', 'like', "%$searchTerms%")
                ->orWhere('amount', 'like', "%$searchTerms%");
            });
        }
        $result = $result->get();
        return response()->json($result, 200);
    }

    public function importCosts(Request $request)
    {
        // Validar que el archivo es un Excel
        $data = $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);

        // Manejar la carga del archivo
        $document = $request->file('file');

        // Leer el archivo Excel directamente desde el stream
        $spreadsheet = IOFactory::load($document->getRealPath());

        // Obtener la primera hoja
        /** @var Worksheet $sheet */
        $sheet = $spreadsheet->getSheet(0);

        // Definir el rango de lectura: A1 hasta la última fila en la columna D
        $startCell = 'A1';
        $endCell = 'D' . $sheet->getHighestRow();
        $range = "$startCell:$endCell";

        // Leer el rango especificado
        $data = $sheet->rangeToArray($range, null, true, true, true);

        // Array para almacenar los objetos
        $rowsAsObjects = [];

        // Recorrer las filas y convertir a objetos

        foreach ($data as $index => $row) {

            $rowObject = (object)[
                'zone' => $this->sanitizeText($row['A'], false),
                'cost_date' => $this->sanitizeDate($row['B']),
                'amount' => $this->sanitizeNumber($row['C']),
                'expense_type' => $this->sanitizeText($row['D'], true)
            ];

            $rowsAsObjects[] = $rowObject;
        }

            DB::beginTransaction();

            foreach ($rowsAsObjects as $item) {
                if (in_array(trim($item->expense_type), ['Planilla', 'Transporte', 'Fletes', 'Alimentacion', 'Consumibles', 'Hospedaje', 'Movilidad'], true)) {
                    // Insert into HuaweiStaticCost
                    HuaweiBalanceCost::create([
                        'zone' => $item->zone,
                        'cost_date' => $item->cost_date,
                        'amount' => $item->amount,
                        'expense_type' => $item->expense_type,
                        'type' => 0
                    ]);
                } elseif (in_array(trim($item->expense_type), ['Cochera', 'Combustible', 'Epps', 'Herramientas', 'Materiales', 'Otros'])) {
                    HuaweiBalanceCost::create([
                        'zone' => $item->zone,
                        'cost_date' => $item->cost_date,
                        'amount' => $item->amount,
                        'expense_type' => $item->expense_type,
                        'type' => 1
                    ]);
                }else{
                    DB::rollBack();
                    return back()->withErrors(['excel_error' => 'Error en los datos del Excel a importar.'])->withInput();
                }
            }

            DB::commit();

        return redirect()->back();
    }

    public function exportCosts ()
    {
        return Excel::download(new HuaweiGeneralCostsExport(), 'Gastos Generales.xlsx');
    }

    //earnings

    public function getEarnings ()
    {
        return Inertia::render('Huawei/BalanceEarnings', [
            'earnings' => HuaweiBalanceEarning::orderBy('created_at', 'desc')->paginate(10),
            'total' => $this->total
        ]);
    }

    public function searchEarnings ($request)
    {
        $searchTerm = strtolower($request);
        $query = HuaweiBalanceEarning::query();

            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(invoice_number) LIKE ? ', ["%{$searchTerm}%"])
                  ->orWhereRaw('LOWER(main_op_number) LIKE ? ', ["%{$searchTerm}%"])
                  ->orWhereRaw('LOWER(detraction_op_number) LIKE ? ', ["%{$searchTerm}%"]);
            });

        $earnings = $query->orderBy('created_at', 'desc')->get();

        return Inertia::render('Huawei/BalanceEarnings', [
            'earnings' => $earnings,
            'search' => $request,
            'total' => $this->total
        ]);
    }

    public function storeEarnings (Request $request)
    {
        $data = $request->validate([
            'invoice_number' => ['required', Rule::unique('huawei_balance_earnings', 'invoice_number')],
            'amount' => 'required',
            'invoice_date' => 'required',
            'deposit_date' => 'nullable',
            'main_amount' => 'nullable',
            'main_op_number' => 'nullable',
            'detraction_amount' => 'nullable',
            'detraction_date' => 'nullable',
            'detraction_op_number' => 'nullable'
        ]);

        HuaweiBalanceEarning::create($data);

        return redirect()->back();
    }

    public function updateEarning (HuaweiBalanceEarning $huawei_balance_earning, Request $request)
    {
        $data = $request->validate([
            'invoice_number' => ['required', Rule::unique('huawei_balance_earnings', 'invoice_number')->ignore($huawei_balance_earning->id)],
            'amount' => 'required',
            'invoice_date' => 'required',
            'deposit_date' => 'nullable',
            'main_amount' => 'nullable',
            'main_op_number' => 'nullable',
            'detraction_amount' => 'nullable',
            'detraction_date' => 'nullable',
            'detraction_op_number' => 'nullable'
        ]);

        $huawei_balance_earning->update($data);

        return redirect()->back();
    }

    public function deleteEarning (HuaweiBalanceEarning $huawei_balance_earning)
    {
        $huawei_balance_earning->delete();

        return redirect()->back();
    }

    public function verifyImportEarnings (Request $request)
    {
        $data = $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);

        // Manejar la carga del archivo
        $document = $request->file('file');

        // Leer el archivo Excel directamente desde el stream
        $spreadsheet = IOFactory::load($document->getRealPath());

        // Obtener la primera hoja
        /** @var Worksheet $sheet */
        $sheet = $spreadsheet->getSheet(0);

        // Definir el rango de lectura: A1 hasta la última fila en la columna D
        $startCell = 'A1';
        $endCell = 'A' . $sheet->getHighestRow();
        $range = "$startCell:$endCell";

        // Leer el rango especificado
        $data = $sheet->rangeToArray($range, null, true, true, true);

        // Array para almacenar los objetos
        $rowsAsObjects = [];

        // Recorrer las filas y convertir a objetos

        foreach ($data as $index => $row) {

            $rowObject = (object)[
                'invoice_number' => $row['A'],
            ];

            $rowsAsObjects[] = $rowObject;
        }

        foreach ($rowsAsObjects as $item) {
            $found_earning = HuaweiBalanceEarning::where('invoice_number', $item->invoice_number)->first();
            if ($found_earning){
                return response()->json([
                    'message' => 'found'
                ]);
            }
        }

        return response()->json([
            'message' => 'notfound'
        ]);
    }

    public function importEarnings(Request $request)
    {
        $data = $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);

        // Manejar la carga del archivo
        $document = $request->file('file');

        // Leer el archivo Excel directamente desde el stream
        $spreadsheet = IOFactory::load($document->getRealPath());

        // Obtener la primera hoja
        /** @var Worksheet $sheet */
        $sheet = $spreadsheet->getSheet(0);

        // Definir el rango de lectura: A1 hasta la última fila en la columna I
        $startCell = 'A1';
        $endCell = 'I' . $sheet->getHighestRow();
        $range = "$startCell:$endCell";

        // Leer el rango especificado
        $data = $sheet->rangeToArray($range, null, true, true, true);

        // Array para almacenar los objetos agrupados por 'invoice_number'
        $groupedData = [];

        // Recorrer las filas y agrupar los datos
        foreach ($data as $index => $row) {
            $invoice_number = $row['A'];

            // Si no existe aún en groupedData, lo agregamos
            if (!isset($groupedData[$invoice_number])) {
                $groupedData[$invoice_number] = (object) [
                    'invoice_number' => $row['A'],
                    'amount' => $this->sanitizeNumber($row['B']),
                    'invoice_date' => $this->sanitizeDate($row['C']),
                    'deposit_date' => !empty($row['D']) ? $this->sanitizeDate($row['D']) : null,
                    'main_op_number' => !empty($row['E']) ? $row['E'] : null,
                    'main_amount' => !empty($row['F']) ? $this->sanitizeNumber($row['F']) : null,
                    'detraction_date' => !empty($row['G']) ? $this->sanitizeDate($row['G']) : null,
                    'detraction_amount' => !empty($row['H']) ? $this->sanitizeNumber($row['H']) : null,
                    'detraction_op_number' => !empty($row['I']) ? $row['I'] : null,
                ];
            } else {
                // Si ya existe, sumamos los valores de amount, main_amount y detraction_amount
                $groupedData[$invoice_number]->amount += $this->sanitizeNumber($row['B']);
                $groupedData[$invoice_number]->main_amount += !empty($row['F']) ? $this->sanitizeNumber($row['F']) : 0;
                $groupedData[$invoice_number]->detraction_amount += !empty($row['H']) ? $this->sanitizeNumber($row['H']) : 0;
            }
        }

        // Convertimos el array asociativo a un array plano con los objetos únicos por invoice_number
        $rowsAsObjects = array_values($groupedData);

        // Empezar la transacción
        DB::beginTransaction();

        try {
            foreach ($rowsAsObjects as $item) {
                $found_earning = HuaweiBalanceEarning::where('invoice_number', $item->invoice_number)->first();

                if ($found_earning) {
                    // Si el registro ya existe, actualizamos
                    $found_earning->update([
                        'amount' => $item->amount,
                        'invoice_date' => $item->invoice_date,
                        'deposit_date' => $item->deposit_date,
                        'main_amount' => $item->main_amount,
                        'main_op_number' => $item->main_op_number,
                        'detraction_amount' => $item->detraction_amount,
                        'detraction_op_number' => $item->detraction_op_number,
                        'detraction_date' => $item->detraction_date,
                    ]);
                } else {
                    // Si no existe, creamos un nuevo registro
                    HuaweiBalanceEarning::create([
                        'invoice_number' => $item->invoice_number,
                        'amount' => $item->amount,
                        'invoice_date' => $item->invoice_date,
                        'deposit_date' => $item->deposit_date,
                        'main_amount' => $item->main_amount,
                        'main_op_number' => $item->main_op_number,
                        'detraction_amount' => $item->detraction_amount,
                        'detraction_op_number' => $item->detraction_op_number,
                        'detraction_date' => $item->detraction_date,
                    ]);
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['earning_error' => 'Hubo un problema al procesar los registros.'])->withInput();
        }

        DB::commit();

        return redirect()->back();
    }


    public function exportEarnings ()
    {
        return Excel::download(new HuaweiGeneralEarningsExport(), 'Ingresos Generales.xlsx');
    }

    //private functions

    private function sanitizeDate($date)
    {
        // Definir los formatos de fecha esperados
        $formats = [
            'd / m / Y', // Ejemplo: 01 / 01 / 2024
            'd/m/Y',     // Ejemplo: 01/01/2024
            'Y-m-d',     // Ejemplo: 2024-01-01
            'd-m-Y',     // Ejemplo: 01-01-2024
            'd.m.Y',     // Ejemplo: 01.01.2024
        ];

        // Intentar analizar la fecha con los formatos definidos
        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $date)->format('Y-m-d');
            } catch (\Exception $e) {
                // Continúa al siguiente formato si falla
                continue;
            }
        }

        // Si ninguno de los formatos funcionó, intentar un parseo general
        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            // En caso de error, puedes manejar el error o retornar un valor por defecto
            return null; // o cualquier valor por defecto que prefieras
        }
    }


    private function sanitizeText($text, $mode)
    {
        if ($mode) {
            // Modo 1: Convertir a primera letra en mayúscula y el resto en minúsculas
            return ucwords(strtolower($text));
        } else {
            // Modo 2: Eliminar espacios en blanco, convertir a mayúsculas y eliminar tildes
            $text = strtoupper($text);

            // Reemplazar tildes
            $text = str_replace(
                ['Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ'],
                ['A', 'E', 'I', 'O', 'U', 'N'],
                $text
            );

            // Eliminar todos los espacios
            return preg_replace('/\s+/', '', $text);
        }
    }

    private function sanitizeNumber($text)
    {
        // Remover todos los caracteres que no sean dígitos o puntos
        $sanitized = preg_replace('/[^0-9.]/', '', $text);

        // Si hay más de un punto, remover todos menos el último
        if (substr_count($sanitized, '.') > 1) {
            $parts = explode('.', $sanitized);
            $sanitized = implode('', array_slice($parts, 0, -1)) . '.' . end($parts);
        }

        return $sanitized;
    }

}

