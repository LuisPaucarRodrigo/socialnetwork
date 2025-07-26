<?php

namespace App\Http\Controllers\Huawei\Projects;

use App\Constants\HuaweiConstants;
use App\Exports\HuaweiProjectEarningsExport;
use App\Exports\HuaweiProjectRealEarningsExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Huawei\Utils\HuaweiUtils;
use App\Http\Requests\Huawei\HuaweiProjectRequest;
use App\Models\CostCenter;
use App\Models\HuaweiProject;
use App\Models\HuaweiProjectAssignation;
use App\Models\HuaweiProjectEmployee;
use App\Models\HuaweiProjectSchedule;
use App\Models\HuaweiSite;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\HuaweiProjectEarning;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\HuaweiPriceGuide;
use App\Models\HuaweiProjectRealEarning;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class HuaweiProjectController extends Controller
{

    private static array $data;

    public function __construct()
    {
        self::$data = HuaweiUtils::getData();
    }

    public function show($status, $prefix)
    {
        $state = null;
        switch ($status) {
            case '1':
                $state = 1;
                break;
            case '2':
                $state = null;
                break;
            case '3':
                $state = 0;
                break;
            default:
                abort(403, 'Acción no permitida');
        }

        $projects = HuaweiProject::where('status', $state)
            ->select('id', 'assigned_diu', 'description', 'zone', 'prefix', 'macro_project', 'assignation_date', 'huawei_site_id', 'status')
            ->where('prefix', $prefix)
            ->with('huawei_site') // Incluye la relación deseada
            ->orderBy('created_at', 'desc')
            ->get();

        $projects->transform(function ($project) {
            return $project->makeHidden([
                'huawei_additional_costs',    // Reemplaza con los nombres de los campos que deseas ocultar
                'huawei_project_earnings',
                'huawei_project_employees',  // Reemplaza con los nombres de las relaciones que deseas ocultar
                'huawei_project_resources',
                'huawei_static_costs',
                'additional_cost_total',
                'static_cost_total',
                'materials_in_project',
                'equipments_in_project',
                'materials_liquidated',
                'equipments_liquidated',
                'total_earnings',
                'total_real_earnings',
                'total_real_earnings_without_deposit',
                'total_project_cost',
                'total_employee_costs',
                'total_essalud_employee_cost'
            ])->setRelation('huawei_site', $project->huawei_site->makeHidden([
                'huawei_project',
            ]));
        });

        return Inertia::render('Huawei/Projects/Projects', [
            'projects' => $projects,
            'prefix' => $prefix,
            'status' => $status,
            'operators' => self::$data['operators'],

        ]);
    }

    public function searchProject($status, $prefix, $request)
    {
        $state = null;
        switch ($status) {
            case '1':
                $state = 1;
                break;
            case '2':
                $state = null;
                break;
            case '3':
                $state = 0;
                break;
            default:
                abort(403, 'Acción no permitida');
        }

        $searchTerm = strtolower($request);
        $projects = HuaweiProject::select('id', 'assigned_diu', 'description', 'zone', 'prefix', 'assignation_date', 'macro_project', 'huawei_site_id', 'status')->where('status', $state)->where('prefix', $prefix)->where(function ($query) use ($searchTerm) {
            $query->WhereRaw('LOWER(description) like ?', ['%' . $searchTerm . '%'])
                ->orWhereRaw('LOWER(zone) LIKE ?', ["%{$searchTerm}%"])
                ->orWhereRaw('LOWER(assigned_diu) LIKE ?', ["%{$searchTerm}%"])
                ->orWhereRaw('LOWER(macro_project) LIKE ?', ["%{$searchTerm}%"])
                ->orWhereHas('huawei_site', function ($query) use ($searchTerm) {
                    $query->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"]);
                });
        })->orderBy('created_at', 'desc')->get();

        $projects->transform(function ($project) {
            return $project->makeHidden([
                'huawei_additional_costs',    // Reemplaza con los nombres de los campos que deseas ocultar
                'huawei_project_earnings',
                'huawei_project_employees',  // Reemplaza con los nombres de las relaciones que deseas ocultar
                'huawei_project_resources',
                'huawei_static_costs',
                'additional_cost_total',
                'static_cost_total',
                'materials_in_project',
                'equipments_in_project',
                'materials_liquidated',
                'equipments_liquidated',
                'total_earnings',
                'total_real_earnings',
                'total_real_earnings_without_deposit',
                'total_project_cost',
                'total_employee_costs',
                'total_essalud_employee_cost'
            ])->setRelation('huawei_site', $project->huawei_site->makeHidden([
                'huawei_project',
            ]));
        });
        return Inertia::render('Huawei/Projects/Projects', [
            'projects' => $projects,
            'search' => $request,
            'prefix' => $prefix,
            'status' => $status,
            'operators' => self::$data['operators'],
        ]);
    }

    public function create()
    {
        $employees = HuaweiConstants::getEmployees();
        return Inertia::render('Huawei/Projects/ProjectForm', [
            'employees' => $employees,
            'cost_centers' => CostCenter::where('cost_line_id', 3)->get(),
            'price_guides' => HuaweiPriceGuide::all(),
            'operators' => self::$data['operators'],
            'macro_projects' => self::$data['macro_projects'],
        ]);
    }

    public function importBaseLines(Request $request, $zone)
    {
        $data = $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);

        $document = $request->file('file');

        $spreadsheet = IOFactory::load($document->getRealPath());

        /** @var Worksheet $sheet */
        $sheet = $spreadsheet->getSheet(0);

        $startCell = 'A1';
        $endCell = 'E' . $sheet->getHighestRow();
        $range = "$startCell:$endCell";

        $data = $sheet->rangeToArray($range, null, true, true, true);

        $rowsAsObjects = [];

        foreach ($data as $index => $row) {
            if ($index === 1)
                continue;
            $isEmptyRow = empty($row['A']) && empty($row['B']) && empty($row['C']) && empty($row['D']) &&
                empty($row['E']);

            if ($isEmptyRow) {
                break;
            }
            $rowObject = (object) [
                'code' => $row['A'],
                'quantity' => $row['B'],
                'evidence' => $row['C'],
                'goal' => $row['D'],
                'observation' => $row['E'],
            ];

            $rowsAsObjects[] = $rowObject;
        }
        $response = [];

        foreach ($rowsAsObjects as $item) {
            if (empty($item->code) || (!isset($item->quantity) && $item->quantity !== 0) || empty($item->evidence) || empty($item->goal)) {
                return response()->json(['error' => 'Llene todos los campos'], 400);
            }

            $price_guide = HuaweiPriceGuide::where('code', $item->code)->first();
            if (!$price_guide) {
                return response()->json(['error' => 'Uno de los códigos no existe en la guía de precios'], 400);
            }
            $objectTo = [
                'code' => $item->code,
                'description' => $price_guide->description,
                'unit' => $price_guide->unit,
                'unit_price' => $price_guide->$zone,
                'quantity' => $item->quantity,
                'total_price' => $price_guide->$zone * $item->quantity,
                'evidence' => $item->evidence,
                'goal' => $item->goal,
                'observation' => $item->observation,
            ];

            $response[] = $objectTo;
        }

        return response()->json(["lines" => $response], 200);
    }

    public function downloadTemplate()
    {
        $templatePath = public_path('documents/huawei/resources/Data Structure - Base Lines.xlsx');
        ob_end_clean();
        return response()->download($templatePath, 'Estructura de Datos - Líneas Base Huawei.xlsx');
    }


    public function toUpdate($huawei_project)
    {
        $huawei_project = HuaweiProject::select(
            'id',
            'huawei_site_id',
            'description',
            'assigned_diu',
            'assignation_date',
            'zone',
            'cost_center_id',
            'status',
            'prefix',
            'macro_project'
        )->where('id', $huawei_project)
            ->with([
                'huawei_site',
                'cost_center',
                'huawei_project_assignations.huawei_project_earnings',
                'huawei_project_schedules.huawei_project_employees',
            ])->first()
            ->makeHidden([
                'additional_cost_total',
                'static_cost_total',
                'materials_in_project',
                'equipments_in_project',
                'materials_liquidated',
                'equipments_liquidated',
                'total_earnings',
                'total_real_earnings',
                'total_real_earnings_without_deposit',
                'total_project_cost',
                'total_employee_costs',
                'total_essalud_employee_cost',
                'huawei_project_resources'
            ]);

        if (!$huawei_project->status) {
            abort(403, 'Acción no permitida');
        }
        $employees = HuaweiConstants::getEmployees();

        return Inertia::render('Huawei/Projects/ProjectForm', [
            'huawei_project' => $huawei_project,
            'huawei_sites' => HuaweiSite::orderBy('name')->get(),
            'employees' => $employees,
            'cost_centers' => CostCenter::where('cost_line_id', 3)->get(),
            'price_guides' => HuaweiPriceGuide::all(),
        ]);
    }

    public function liquidateProject(HuaweiProject $huawei_project)
    {
        if (!$huawei_project->status || !$huawei_project->pre_report) {
            abort(403, 'Acción no permitida');
        }

        if (!$huawei_project->state) {
            abort(403, 'Proyecto no apto para liquidación');
        }

        $huawei_project->update([
            'status' => false
        ]);

        return redirect()->back();
    }

    public function cancelProject(HuaweiProject $huawei_project)
    {
        if (!$huawei_project->status) {
            abort(403, 'Acción no permitida');
        }

        $huawei_project->update([
            'status' => null
        ]);

        return redirect()->back();
    }

    public function resumeProject(HuaweiProject $huawei_project)
    {
        if ($huawei_project->status !== null) {
            abort(403, 'Acción no permitida');
        }

        $huawei_project->update([
            'status' => 1
        ]);

        return redirect()->back();
    }

    public function projectBalance(HuaweiProject $huawei_project)
    {
        return Inertia::render('Huawei/Projects/ProjectExpenses/ProjectBalance', [
            'huawei_project' => $huawei_project
        ]);
    }

    public function fetchSites(Request $request)
    {
        $data = $request->validate([
            'prefix' => 'required'
        ]);

        $sites = HuaweiSite::where('prefix', $data['prefix'])->get();
        return response()->json($sites);
    }

    public function store(HuaweiProjectRequest $request)
    {
        $request->validated();
        DB::beginTransaction();

        try {
            $project = HuaweiProject::create([
                'assigned_diu' => $request->assigned_diu,
                'cost_center_id' => $request->cost_center_id,
                'macro_project' => $request->macro_project,
                'prefix' => $request->prefix,
                'huawei_site_id' => $request->huawei_site_id,
                'zone' => $request->zone,
                'assignation_date' => $request->assignation_date,
                'description' => $request->description,
                'status' => 1
            ]);

            foreach ($request->assignations as $assignation) {
                $new_assignation = HuaweiProjectAssignation::create([
                    'po' => $assignation['po'],
                    'assignation_date' => $assignation['assignation_date'],
                    'description' => $assignation['description'],
                    'huawei_project_id' => $project->id,
                ]);

                foreach ($assignation['base_lines'] as $line) {
                    $code = HuaweiPriceGuide::where('code', $line['code'])->first();
                    if ($code) {
                        if ($code->goal !== $line['goal'] || $code->evidence !== $line['evidence']) {
                            $code->update([
                                'goal' => $line['goal'] ?? $code->goal,
                                'evidence' => $line['evidence'] ?? $code->evidence,
                            ]);
                        }
                    }
                    HuaweiProjectEarning::create([
                        'code' => $line['code'],
                        'description' => $line['description'],
                        'unit' => $line['unit'],
                        'unit_price' => $line['unit_price'],
                        'quantity' => $line['quantity'],
                        'evidence' => $line['evidence'],
                        'goal' => $line['goal'],
                        'observation' => $line['observation'],
                        'huawei_pa_id' => $new_assignation->id
                    ]);
                }
            }

            foreach ($request->schedule as $activity) {
                $schedule = HuaweiProjectSchedule::create([
                    'activity' => $activity['activity'],
                    'days' => $activity['days'],
                    'start_date' => $activity['start_date'],
                    'end_date' => $activity['end_date'],
                    'huawei_project_id' => $project->id
                ]);
                foreach ($activity['employees'] as $employee) {
                    HuaweiProjectEmployee::create([
                        'huawei_project_schedule_id' => $schedule->id,
                        'employee' => $employee
                    ]);
                }
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::info($th);
            abort(403, 'Error al crear el proyecto');
        }

        DB::commit();

        return redirect()->back();
    }

    public function update(HuaweiProject $huawei_project, HuaweiProjectRequest $request)
    {
        if (!$huawei_project->status) {
            abort(403, 'Acción no permitida');
        }

        $data = $request->validated();
        Log::info($data);

        DB::beginTransaction();

        try {
            $huawei_project->update([
                'description' => $data['description'],
            ]);

            foreach ($data['assignations'] as $assignation) {
                if (isset($assignation['id']) && $assignation['id']) {
                    $huawei_project_assignation = HuaweiProjectAssignation::find($assignation['id']);
                    $huawei_project_assignation->update([
                        'po' => $assignation['po'],
                        'assignation_date' => $assignation['assignation_date'],
                        'description' => $assignation['description'],
                    ]);
                    foreach ($assignation['base_lines'] as $line) {
                        if (isset($line['id']) && $line['id']) {
                            $huawei_project_earning = HuaweiProjectEarning::find($line['id']);
                            $huawei_project_earning->update([
                                'quantity' => $line['quantity'],
                            ]);
                        } else {
                            $code = HuaweiPriceGuide::where('code', $line['code'])->first();
                            if ($code) {
                                if ($code->goal !== $line['goal'] || $code->evidence !== $line['evidence']) {
                                    $code->update([
                                        'goal' => $line['goal'] ?? $code->goal,
                                        'evidence' => $line['evidence'] ?? $code->evidence,
                                    ]);
                                }
                            }
                            $new_line = HuaweiProjectEarning::create([
                                'code' => $line['code'],
                                'description' => $line['description'],
                                'unit' => $line['unit'],
                                'unit_price' => $line['unit_price'],
                                'quantity' => $line['quantity'],
                                'evidence' => $line['evidence'],
                                'goal' => $line['goal'],
                                'observation' => $line['observation'],
                                'huawei_pa_id' => $huawei_project_assignation->id
                            ]);
                        }
                    }
                } else {
                    $new_assignation = HuaweiProjectAssignation::create([
                        'po' => $assignation['po'],
                        'assignation_date' => $assignation['assignation_date'],
                        'description' => $assignation['description'],
                        'huawei_project_id' => $huawei_project->id,
                    ]);

                    foreach ($assignation['base_lines'] as $line) {
                        $code = HuaweiPriceGuide::where('code', $line['code'])->first();
                        if ($code) {
                            if ($code->goal !== $line['goal'] || $code->evidence !== $line['evidence']) {
                                $code->update([
                                    'goal' => $line['goal'] ?? $code->goal,
                                    'evidence' => $line['evidence'] ?? $code->evidence,
                                ]);
                            }
                        }
                        HuaweiProjectEarning::create([
                            'code' => $line['code'],
                            'description' => $line['description'],
                            'unit' => $line['unit'],
                            'unit_price' => $line['unit_price'],
                            'quantity' => $line['quantity'],
                            'evidence' => $line['evidence'],
                            'goal' => $line['goal'],
                            'observation' => $line['observation'],
                            'huawei_pa_id' => $new_assignation->id
                        ]);
                    }
                }
            }

            $scheduleIdsFromRequest = collect($data['schedule'])
                ->pluck('id')
                ->filter()
                ->all();

            $currentSchedules = $huawei_project->huawei_project_schedules;
            $currentSchedules->each(function ($schedule) use ($scheduleIdsFromRequest) {
                if (!in_array($schedule->id, $scheduleIdsFromRequest)) {
                    $schedule->huawei_project_employees()->delete();
                    $schedule->delete();
                }
            });

            foreach ($data['schedule'] as $schedule) {
                if ($schedule['id']) {
                    $huawei_project_schedule = HuaweiProjectSchedule::find($schedule['id']);
                    $huawei_project_schedule->update([
                        'activity' => $schedule['activity'],
                        'days' => $schedule['days'],
                        'start_date' => $schedule['start_date'],
                        'end_date' => $schedule['end_date'],
                    ]);
                    $huawei_project_schedule->huawei_project_employees()->delete();
                    foreach ($schedule['employees'] as $employee) {
                        HuaweiProjectEmployee::create([
                            'huawei_project_schedule_id' => $huawei_project_schedule->id,
                            'employee' => (is_array($employee) && isset($employee['employee'])) ? $employee['employee'] : $employee
                        ]);
                    }
                } else {
                    $new_schedule = HuaweiProjectSchedule::create([
                        'activity' => $schedule['activity'],
                        'days' => $schedule['days'],
                        'start_date' => $schedule['start_date'],
                        'end_date' => $schedule['end_date'],
                        'huawei_project_id' => $huawei_project->id
                    ]);
                    foreach ($schedule['employees'] as $employee) {
                        HuaweiProjectEmployee::create([
                            'huawei_project_schedule_id' => $new_schedule->id,
                            'employee' => $employee
                        ]);
                    }
                }
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::info($data);
            Log::info($th);
            abort(403, 'Error al actualizar el proyecto');
        }
        DB::commit();
        return redirect()->back();
    }

    //Earnings

    public function getEarnings(HuaweiProject $huawei_project)
    {
        $earnings = HuaweiProjectEarning::where('huawei_project_id', $huawei_project->id)->orderBy('created_at', 'desc')->paginate(10);
        $total = HuaweiProjectEarning::where('huawei_project_id', $huawei_project->id)->get()->reduce(function ($carry, $item) {
            return $carry + ($item->unit_price * $item->quantity);
        }, 0);

        return Inertia::render('Huawei/Earnings/ProjectEarnings', [
            'earnings' => $earnings,
            'total' => $total,
            'huawei_project' => $huawei_project
        ]);
    }

    public function searchEarnings(HuaweiProject $huawei_project, $request)
    {
        $searchTerm = strtolower($request);
        $query = HuaweiProjectEarning::where('huawei_project_id', $huawei_project->id);

        $query->where(function ($q) use ($searchTerm) {
            $q->whereRaw('LOWER(description) LIKE ?', ["%{$searchTerm}%"])
                ->orWhereRaw('LOWER(code) LIKE ?', ["%{$searchTerm}%"])
                ->orWhereRaw('LOWER(unit_price) LIKE ?', ["%{$searchTerm}%"]);
        });

        $earnings = $query->orderBy('updated_at', 'desc')->get();
        $total = $earnings->reduce(function ($carry, $item) {
            return $carry + ($item->unit_price * $item->quantity);
        }, 0);

        return Inertia::render('Huawei/Earnings/ProjectEarnings', [
            'earnings' => $earnings,
            'huawei_project' => $huawei_project,
            'search' => $request,
            'total' => $total
        ]);
    }

    public function storeEarning(Request $request)
    {
        $found_project = HuaweiProject::find($request->huawei_project_id);

        if (!$found_project->status) {
            return abort(403, 'Acción no permitida');
        }

        $data = $request->validate([
            'description' => 'required',
            'quantity' => 'required',
            'unit_price' => 'required',
            'huawei_project_id' => 'required',
            'state' => [
                'required',
                'string',
                'in:Pendiente,En Proceso,Completado,Cancelado'
            ],
        ]);

        $count = HuaweiProjectEarning::where('huawei_project_id', $request->huawei_project_id)->count();
        $nextNumber = $count + 1;
        $data['code'] = sprintf('COE-%04d', $nextNumber);
        HuaweiProjectEarning::create($data);
        return redirect()->back();
    }

    public function updateEarningState(HuaweiProject $huawei_project, HuaweiProjectEarning $earning, Request $request)
    {
        if (!$huawei_project->status) {
            abort(403, 'Acción no permitida');
        }
        $data = $request->validate([
            'state' => 'required'
        ]);

        $earning->update($data);

        return redirect()->back();
    }

    public function updateEarning(HuaweiProjectEarning $huawei_project_earning, Request $request)
    {
        $found_project = HuaweiProject::find($request->huawei_project_id);

        if (!$found_project->status) {
            return abort(403, 'Acción no permitida');
        }

        $data = $request->validate([
            'description' => 'required',
            'quantity' => 'required',
            'unit_price' => 'required',
            'huawei_project_id' => 'required',
            'state' => [
                'required',
                'string',
                'in:Pendiente,En Proceso,Completado,Cancelado'
            ],
        ]);

        $huawei_project_earning->update($data);

        return redirect()->back();
    }

    public function deleteEarning(HuaweiProjectEarning $huawei_project_earning)
    {
        $found_project = HuaweiProject::find($huawei_project_earning->huawei_project_id);

        if (!$found_project->status) {
            return abort(403, 'Acción no permitida');
        }

        $huawei_project_earning->delete();
        return redirect()->back();
    }

    public function importEarnings($huawei_project, Request $request)
    {
        $found_project = HuaweiProject::find($huawei_project);

        if (!$found_project->status) {
            return abort(403, 'Acción no permitida');
        }

        // Validar que el archivo es un Excel
        $data = $request->validate([
            'file' => 'required|mimes:xls,xlsx',
            'zone' => 'required'
        ]);

        // Manejar la carga del archivo
        $document = $request->file('file');

        // Leer el archivo Excel directamente desde el stream
        $spreadsheet = IOFactory::load($document->getRealPath());

        // Obtener la primera hoja
        /** @var Worksheet $sheet */
        $sheet = $spreadsheet->getSheet(0);

        // Definir el rango de lectura: A1 hasta la última fila en la columna C
        $startCell = 'A1';
        $endCell = 'D' . $sheet->getHighestRow();
        $range = "$startCell:$endCell";

        // Leer el rango especificado
        $data = $sheet->rangeToArray($range, null, true, true, true);

        // Array para almacenar los objetos
        $rowsAsObjects = [];

        // Recorrer las filas y convertir a objetos
        foreach ($data as $index => $row) {

            $rowObject = (object) [
                'code' => $row['A'],
                'description' => $row['B'],
                'quantity' => $row['C'],
                'state' => $row['D'] ? $row['D'] : 'Pendiente'
            ];

            $rowsAsObjects[] = $rowObject;
        }


        foreach ($rowsAsObjects as $item) {
            $priceGuide = HuaweiPriceGuide::where('code', $item->code)->first();
            if ($priceGuide) {
                $unitPriceField = 'b' . $request->zone;
                $unitPrice = $priceGuide->$unitPriceField;

                HuaweiProjectEarning::create([
                    'code' => $item->code,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'unit_price' => $unitPrice,
                    'huawei_project_id' => $huawei_project,
                    'state' => $item->state
                ]);
            } else {
                HuaweiProjectEarning::create([
                    'code' => $item->code,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'state' => $item->state,
                    'huawei_project_id' => $huawei_project
                ]);
            }
        }

        return redirect()->back();
    }

    public function exportEarnings(HuaweiProject $huawei_project)
    {
        return Excel::download(new HuaweiProjectEarningsExport($huawei_project->id), 'Ingresos proyectados de ' . $huawei_project->assigned_diu . '.xlsx');
    }









    //real_earnings

    public function getRealEarnings(HuaweiProject $huawei_project)
    {
        $real_earnings = HuaweiProjectRealEarning::where('huawei_project_id', $huawei_project->id)->orderBy('created_at', 'desc')->paginate(10);
        $total = HuaweiProjectRealEarning::where('huawei_project_id', $huawei_project->id)
            ->whereNotNull('deposit_date')
            ->sum('amount');

        return Inertia::render('Huawei/Earnings/ProjectRealEarnings', [
            'real_earnings' => $real_earnings,
            'total' => $total,
            'huawei_project' => $huawei_project
        ]);
    }

    public function searchRealEarnings(HuaweiProject $huawei_project, $request)
    {
        $searchTerm = strtolower($request);
        $query = HuaweiProjectRealEarning::where('huawei_project_id', $huawei_project->id);

        $query->where(function ($q) use ($searchTerm) {
            $q->whereRaw('LOWER(invoice_number) LIKE ?', ["%{$searchTerm}%"])
                ->orWhereRaw('LOWER(main_op_number) LIKE ? ', ["%{$searchTerm}%"])
                ->orWhereRaw('LOWER(detraction_op_number) LIKE ? ', ["%{$searchTerm}%"]);
        });

        $earnings = $query->orderBy('created_at', 'desc')->get();
        $total = HuaweiProjectRealEarning::where('huawei_project_id', $huawei_project->id)
            ->whereNotNull('deposit_date')
            ->sum('amount');

        return Inertia::render('Huawei/Earnings/ProjectRealEarnings', [
            'real_earnings' => $earnings,
            'huawei_project' => $huawei_project,
            'search' => $request,
            'total' => $total
        ]);
    }

    public function storeRealEarning(Request $request)
    {
        $found_project = HuaweiProject::find($request->huawei_project_id);

        if (!$found_project->status) {
            return abort(403, 'Acción no permitida');
        }

        $data = $request->validate([
            'invoice_number' => 'required',
            'amount' => 'required',
            'invoice_date' => 'required',
            'deposit_date' => 'nullable',
            'main_amount' => 'nullable',
            'main_op_number' => 'nullable',
            'detraction_amount' => 'nullable',
            'detraction_date' => 'nullable',
            'detraction_op_number' => 'nullable'
        ]);

        $data['huawei_project_id'] = $request->huawei_project_id;
        HuaweiProjectRealEarning::create($data);
        return redirect()->back();
    }

    public function updateRealEarning(HuaweiProjectRealEarning $huawei_project_real_earning, Request $request)
    {
        $found_project = HuaweiProject::find($request->huawei_project_id);

        if (!$found_project->status) {
            return abort(403, 'Acción no permitida');
        }

        $data = $request->validate([
            'invoice_number' => 'required',
            'amount' => 'required',
            'invoice_date' => 'required',
            'deposit_date' => 'nullable',
            'main_amount' => 'nullable',
            'main_op_number' => 'nullable',
            'detraction_amount' => 'nullable',
            'detraction_date' => 'nullable',
            'detraction_op_number' => 'nullable'
        ]);

        $huawei_project_real_earning->update($data);

        return redirect()->back();
    }

    public function deleteRealEarning(HuaweiProjectRealEarning $huawei_project_real_earning)
    {
        $found_project = HuaweiProject::find($huawei_project_real_earning->huawei_project_id);

        if (!$found_project->status) {
            return abort(403, 'Acción no permitida');
        }

        $huawei_project_real_earning->delete();
        return redirect()->back();
    }

    public function exportRealEarnings(HuaweiProject $huawei_project)
    {
        return Excel::download(new HuaweiProjectRealEarningsExport($huawei_project->id), 'Ingresos reales de ' . $huawei_project->assigned_diu . '.xlsx');
    }

    public function importRealEarnings($huawei_project, Request $request)
    {
        $found_project = HuaweiProject::find($huawei_project);

        if (!$found_project->status) {
            return abort(403, 'Acción no permitida');
        }

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
        $endCell = 'I' . $sheet->getHighestRow();
        $range = "$startCell:$endCell";

        // Leer el rango especificado
        $data = $sheet->rangeToArray($range, null, true, true, true);

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
                $found_earning = HuaweiProjectRealEarning::where('invoice_number', $item->invoice_number)->where('huawei_project_id', $huawei_project)->first();

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
                    HuaweiProjectRealEarning::create([
                        'invoice_number' => $item->invoice_number,
                        'amount' => $item->amount,
                        'invoice_date' => $item->invoice_date,
                        'deposit_date' => $item->deposit_date,
                        'main_amount' => $item->main_amount,
                        'main_op_number' => $item->main_op_number,
                        'detraction_amount' => $item->detraction_amount,
                        'detraction_op_number' => $item->detraction_op_number,
                        'detraction_date' => $item->detraction_date,
                        'huawei_project_id' => $huawei_project
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

    public function verifyImportRealEarnings($huawei_project, Request $request)
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

            $rowObject = (object) [
                'invoice_number' => $row['A'],
            ];

            $rowsAsObjects[] = $rowObject;
        }

        foreach ($rowsAsObjects as $item) {
            $found_earning = HuaweiProjectRealEarning::where('invoice_number', $item->invoice_number)->where('huawei_project_id', $huawei_project)->first();
            if ($found_earning) {
                return response()->json([
                    'message' => 'found'
                ]);
            }
        }

        return response()->json([
            'message' => 'notfound'
        ]);
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
