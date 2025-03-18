<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Contract;
use App\Models\CostLine;
use App\Models\Education;
use App\Models\Emergency;
use App\Models\Employee;
use App\Models\ExternalEmployee;
use App\Models\Family;
use App\Models\Health;
use App\Models\PayrollDetail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

use function PHPUnit\Framework\isEmpty;

class ManagementEmployeesServices
{
    public $pensionList = ['Habitat', 'Integra', 'Prima', 'Profuturo', 'HabitadMX', 'IntegraMX', 'PrimaMX', 'ProfuturoMX', 'ONP'];

    private function queryEmployeesCostLine($state): Builder
    {
        $query = Employee::with('contract.cost_line')->whereHas('contract', function ($query) use ($state) {
            $query->where('state', $state);
        });
        return $query;
    }

    public function getEmployees(): Object
    {
        $employees = $this->queryEmployeesCostLine('Active')->paginate();
        $employees = $this->mapProfileEmployee($employees);
        return $employees;
    }

    private function mapProfileEmployee($employees)
    {
        $employees->each(function ($employee) {
            $employee->cropped_image = url($employee->cropped_image ? '/image/profile/' . $employee->cropped_image : '/image/projectimage/DefaultUser.png');
        });
        return $employees;
    }

    public function getCostLine(): Object
    {
        $cost_line = CostLine::all();
        return $cost_line;
    }

    public function searchEmployees($state, $search, $cost_line): Object
    {
        $employees = $this->queryEmployeesCostLine($state);
        $employees = $this->queryFilterSearch($employees, $search, $cost_line);
        return $employees;
    }

    private function queryFilterSearch($employees, $searchTerm, $cost_line): Object
    {
        $employees = $employees->where(function ($query) use ($searchTerm) {
            $query->where('name', 'like', '%' . $searchTerm . '%')
                ->orWhere('lastname', 'like', '%' . $searchTerm . '%')
                ->orWhere('phone1', 'like', '%' . $searchTerm . '%')
                ->orWhere('dni', 'like', '%' . $searchTerm . '%');
        });
        $employees = $employees->whereHas('contract.cost_line', function ($item) use ($cost_line) {
            $item->whereIn('name', $cost_line);
        })->get();
        $employees = $this->mapProfileEmployee($employees);
        return $employees;
    }

    public function deleteEmployees($id)
    {
        $employee = Employee::find($id);
        $employee->delete();
    }

    public function firedEmployees($validateData, $id)
    {
        $contract = Contract::where('employee_id', $id)->first();
        $contract->update($validateData);
    }

    public function updatePayrollDetail($validateData, $id)
    {
        $payroll = PayrollDetail::where('employee_id', $id)
            ->latest('created_at')
            ->first();
        if ($payroll) {
            $payroll->update($validateData);
        }
    }

    public function relationEmployees($id)
    {
        $employee = Employee::with('contract.cost_line', 'education', 'address', 'emergency', 'family', 'health')->find($id);
        return $employee;
    }

    public function download($id)
    {
        $filename = $id->curriculum_vitae;
        $filePath = 'documents/curriculum_vitae/' . $filename;
        $path = public_path($filePath);

        if (file_exists($path)) {
            return response()->download($filePath, $filename);
        }
        abort(404);
    }

    public function reentry($request, $id)
    {
        $reentry = Contract::find($id);
        $reentry->update([
            'hire_date' => $request->reentry_date,
            'fired_date' => null,
            'days_taken' => 0,
            'state' => 'Active'
        ]);
    }

    public function happy_birthday()
    {
        $now = Carbon::now();
        $startDate = $now->copy();
        $endDate = $now->copy()->addDays(3);

        $dates = [];
        for ($date = $startDate; $date <= $endDate; $date->addDay()) {
            $dates[] = $date->format('m-d');
        }

        $employees = Employee::select('id', 'name', 'lastname', 'birthdate')->get();

        $data = $employees->filter(function ($employee) use ($dates) {
            return in_array(Carbon::parse($employee->birthdate)->format('m-d'), $dates);
        });
        return $data;
    }

    private function storeArchives($file, $url): ?String
    {
        if ($file) {
            $imageUrl = time() . '._' . $file->getClientOriginalName();
            $file->move(public_path($url), $imageUrl);
            return $imageUrl;
        } else {
            return null;
        }
    }

    public function updateOrCreateEmployee($request, $employee_id): String
    {
        $filteredFields = array_diff((new Employee())->getFillable(), ['cropped_image']);
        $validateData = $request->only($filteredFields);
        if ($request->hasFile('cropped_image')) {
            $url = 'image/profile/';
            $validateData['cropped_image'] = $this->storeArchives($request->file('cropped_image'), $url);
        }
        $employee = Employee::updateOrCreate(
            ['id' => $employee_id],
            $validateData
        );
        return $employee->id;
    }

    public function updateOrCreateContract($validateData, $employee_id)
    {
        Contract::updateOrCreate(
            ['employee_id' => $employee_id],
            $validateData
        );
    }

    public function updateOrCreateEducation($request, $employee_id)
    {
        $filteredFields = array_diff((new Education())->getFillable(), ['curriculum_vitae']);
        $validateData = $request->only($filteredFields);
        if ($request->hasFile('curriculum_vitae')) {
            $url = 'documents/curriculum_vitae/';
            $validateData['curriculum_vitae'] = $this->storeArchives($request->file('curriculum_vitae'), $url);
        }

        Education::updateOrCreate(
            ['employee_id' => $employee_id],
            $validateData
        );
    }

    public function updateOrCreateAddress($validateData, $employee_id)
    {
        Address::updateOrCreate(
            ['employee_id' => $employee_id],
            $validateData
        );
    }

    public function updateOrCreateEmergency($validateData, $employee_id)
    {
        foreach ($validateData as $emergency) {
            Emergency::create(
                [
                    'emergency_name' => $emergency['emergency_name'],
                    'emergency_lastname' => $emergency['emergency_lastname'],
                    'emergency_relations' => $emergency['emergency_relations'],
                    'emergency_phone' => $emergency['emergency_phone'],
                    'employee_id' => $employee_id,
                ]
            );
        }
    }

    public function updateOrCreateFamily($validateData, $employee_id)
    {
        foreach ($validateData as $dependent) {
            Family::create(
                [
                    'family_dni' => $dependent['family_dni'],
                    'family_education' => $dependent['family_education'],
                    'family_relation' => $dependent['family_relation'],
                    'family_name' => $dependent['family_name'],
                    'family_lastname' => $dependent['family_lastname'],
                    'employee_id' => $employee_id,
                ]
            );
        }
    }

    public function updateOrCreateHealth($validateData, $employee_id)
    {
        Health::updateOrCreate(
            ['employee_id' => $employee_id],
            $validateData
        );
    }

    public function getExternalEmployees()
    {
        $employees = ExternalEmployee::with('cost_line')->paginate();
        $employees = $this->mapProfileEmployee($employees);
        return $employees;
    }

    public function searchExternal($cost_line)
    {
        $employees = ExternalEmployee::with('cost_line')
            ->whereHas('cost_line', function ($item) use ($cost_line) {
                $item->whereIn('name', $cost_line);
            })
            ->get();
        $employees = $this->mapProfileEmployee($employees);
        return $employees;
    }

    public function storeOrUpdateExternalEmployees($validateData, $request, $external_id)
    {
        $validateData['cropped_image'] = $this->storeArchives($request->file('cropped_image'), 'image/profile/');
        $validateData['curriculum_vitae'] = $this->storeArchives($request->file('curriculum_vitae'), 'documents/curriculum_vitae/');
        ExternalEmployee::updateOrCreate(
            ['id' => $external_id],
            $validateData
        );
    }

    public function preview($fileName): BinaryFileResponse
    {
        $filePath = '/documents/curriculum_vitae/' . $fileName;
        $path = public_path($filePath);
        if (file_exists($path)) {
            ob_end_clean();
            return response()->file($path);
        }
        abort(404, 'Documento no encontrado');
    }
}
