<?php

namespace App\Http\Controllers\Huawei\Projects\ProjectExpenses;

use App\Exports\HuaweiMonthlyExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Huawei\Utils\HuaweiUtils;
use App\Models\HuaweiMonthlyExpense;
use App\Models\HuaweiProject;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class HuaweiProjectExpensesController extends Controller
{
        private static array $data;

        public function __construct()
        {
            self::$data = HuaweiUtils::getData();
        }

        public function getCostSummary(HuaweiProject $huawei_project)
        {
            $prevExpense = HuaweiMonthlyExpense::with([
                'general_expense',
            ])
                ->where(function ($query) {
                    $query->where('is_accepted', 1)
                        ->orWhere('is_accepted', null);
                })
                ->where('huawei_project_id', $huawei_project->id)
                ->orderBy('created_at', 'desc')
                ->get();

            foreach ($prevExpense as $exp) {
                $exp->setAppends(['real_state', 'type']);
            }

            $acExpensesAmounts = $prevExpense->filter(function ($cost) {
                return $cost->type === 'Variable';
            })->groupBy('expense_type')->map(function ($group) {
                return [
                    'expense_type' => $group->first()->expense_type,
                    'total_amount' => $group->sum('amount'),
                ];
            })->values()->toArray();

            $scExpensesAmounts = $prevExpense->filter(function ($cost) {
                return $cost->type === 'Fijo';
            })->groupBy('expense_type')->map(function ($group) {
                return [
                    'expense_type' => $group->first()->expense_type,
                    'total_amount' => $group->sum('amount'),
                ];
            })->values()->toArray();

            return Inertia::render('Huawei/Projects/ProjectExpenses/CostSummary', [
                'expenses' => $prevExpense,
                'acExpensesAmounts' => $acExpensesAmounts,
                'scExpensesAmounts' => $scExpensesAmounts,
                'huawei_project' => $huawei_project->makeHidden([
                    'huawei_additional_costs',
                    'huawei_project_earnings',
                    'huawei_project_employees',
                    'huawei_project_real_earnings',
                    'huawei_project_resources',
                    'huawei_static_costs'
                ])
            ]);
        }

        public function getExpensesByZone($huawei_project_id, $expenseType)
        {
            $prevExpense = HuaweiMonthlyExpense::with([
                'general_expense',
            ])
                ->where(function ($query) {
                    $query->where('is_accepted', 1)
                        ->orWhere('is_accepted', null);
                })
                ->where('huawei_project_id', $huawei_project_id)
                ->where('expense_type', $expenseType)
                ->orderBy('created_at', 'desc')
                ->get();

            $groupedExpenses = $prevExpense->groupBy('zone')->map(function ($group) {
                return [
                    'zone' => $group->first()->zone,
                    'total_amount' => $group->sum('amount'),
                ];
            })->values()->toArray();

            return response()->json($groupedExpenses, 200);
        }

        public function getAdditionalCosts(HuaweiProject $huawei_project, $mode = null)
        {
            $expenses = HuaweiMonthlyExpense::query()
                ->whereNotNull('huawei_project_id')
                ->where('huawei_project_id', $huawei_project->id)
                ->orderBy('expense_date', 'desc')
                ->get()
                ->filter(fn($expense) => $mode ? $expense->type === 'Fijo' : $expense->type === 'Variable');

            return Inertia::render('Huawei/Projects/ProjectExpenses/AdditionalCosts', [
                'expense' => $expenses->values(),
                'project' => $huawei_project,
                'data' => self::$data,
                'mode' => $mode
            ]);
        }

        public function showImage(HuaweiMonthlyExpense $expense)
        {
            $imageToShow = $expense->image;
            $path = public_path("documents/huawei/monthly_expenses/$imageToShow");
            if (file_exists($path)) {
                ob_end_clean();
                return response()->file($path);
            }
            abort(403, 'Imagen no encontrada');
        }

        public function search_costs($huawei_project_id, Request $request, $mode = null)
        {
            $expensesQuery = HuaweiMonthlyExpense::where('huawei_project_id', $huawei_project_id)->orderBy('expense_date', 'desc');
            $summary = [
                'expense_types' => count($mode ? self::$data['static_expense_types'] : self::$data['variable_expense_types']),
                'employees' => count(self::$data['employees']),
                'cdp_types' => count(self::$data['cdp_types']),
            ];

            if (count($request->selectedExpenseTypes) < $summary['expense_types']) {
                $expensesQuery->whereIn('expense_type', $request->selectedExpenseTypes);
            }
            if (count($request->selectedCDPTypes) < $summary['cdp_types']) {
                $expensesQuery->whereIn('cdp_type', $request->selectedCDPTypes);
            }
            if (count($request->selectedEmployees) < $summary['employees']) {
                $expensesQuery->whereIn('employee', $request->selectedEmployees);
            }

            if ($request->exStartDate) {
                $expensesQuery->where('expense_date', '>=', $request->exStartDate);
            }
            if ($request->exEndDate) {
                $expensesQuery->where('expense_date', '<=', $request->exEndDate);
            }
            if ($request->exNoDate) {
                $expensesQuery->whereNull('expense_date');
            }
            if ($request->opStartDate) {
                $expensesQuery->where('ec_expense_date', '>=', $request->opStartDate);
            }
            if ($request->opEndDate) {
                $expensesQuery->where('ec_expense_date', '<=', $request->opEndDate);
            }
            if ($request->opNoDate) {
                $expensesQuery->whereNull('ec_expense_date');
            }
            $expenses = $expensesQuery->orderBy('expense_date', 'desc')
                ->get()
                ->filter(fn($expense) => $mode ? $expense->type === 'Fijo' : $expense->type === 'Variable');

            if (count($request->selectedStates) < 4) {
                $selectedStates = $request->selectedStates;

                $filteredStates = array_filter($selectedStates, function ($state) {
                    return in_array($state, ["Aceptado", "Rechazado", "Pendiente", "Aceptado-Validado"]);
                });

                $expenses = $expenses->filter(function ($expense) use ($filteredStates) {
                    return in_array($expense->real_state, $filteredStates);
                });
            }

            return response()->json(["expenses" => $expenses->values()->toArray()], 200);
        }


        public function searchAdditionalCosts(HuaweiProject $huawei_project, $request, $mode = null)
        {
            $searchTerm = strtolower($request);
            $expensesQuery = HuaweiMonthlyExpense::query()
                ->where('huawei_project_id', $huawei_project->id)
                ->where(function ($query) use ($searchTerm) {
                    $query->whereRaw('LOWER(expense_type) LIKE ?', ["%{$searchTerm}%"])
                        ->orWhereRaw('LOWER(employee) LIKE ?', ["%{$searchTerm}%"])
                        ->orWhereRaw('LOWER(cdp_type) LIKE ?', ["%{$searchTerm}%"])
                        ->orWhereRaw('LOWER(doc_number) LIKE ?', ["%{$searchTerm}%"])
                        ->orWhereRaw('LOWER(op_number) LIKE ?', ["%{$searchTerm}%"])
                        ->orWhereRaw('LOWER(ruc) LIKE ?', ["%{$searchTerm}%"])
                        ->orWhereRaw('LOWER(description) LIKE ?', ["%{$searchTerm}%"])
                        ->orWhereRaw('LOWER(ec_op_number) LIKE ?', ["%{$searchTerm}%"]);
                });

            $expenses = $expensesQuery->orderBy('expense_date', 'desc')
                ->get()
                ->filter(fn($expense) => $mode ? $expense->type === 'Fijo' : $expense->type === 'Variable');

            return Inertia::render('Huawei/Projects/ProjectExpenses/AdditionalCosts', [
                'expense' => $expenses->values(),
                'project' => $huawei_project,
                'data' => self::$data,
                'search' => $request,
                'mode' => $mode
            ]);
        }

        public function exportAdditionalCosts(HuaweiProject $huawei_project_id, $mode = null)
        {
            $fileName = 'Gastos ' . ($mode ? 'Fijos' : 'Variables') . ' del Proyecto - ' . $huawei_project_id->name . '.xlsx';

            return Excel::download(new HuaweiMonthlyExport($mode, $huawei_project_id->id), $fileName);
        }


}