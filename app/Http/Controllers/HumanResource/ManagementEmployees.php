<?php

namespace App\Http\Controllers\HumanResource;

use App\Http\Controllers\Controller;
use App\Http\Requests\HumanResource\CreateManagementEmployees;
use App\Http\Requests\HumanResource\FiredContractEmployees;
use App\Http\Requests\HumanResource\StoreOrUpdateEmployeesExternal;
use App\Http\Requests\HumanResource\UpdateManagementEmployees;
use App\Services\HumanResource\ManagementEmployeesServices;
use App\Models\Address;
use App\Models\Contract;
use App\Models\Education;
use App\Models\Emergency;
use App\Models\ExternalEmployee;
use App\Models\Family;
use App\Models\Health;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ManagementEmployees extends Controller
{
    protected $employeesServices;

    public function __construct(ManagementEmployeesServices $employeesServices)
    {
        $this->employeesServices = $employeesServices;
    }

    public function index()
    {
        $employees = $this->employeesServices->getEmployees();
        $cost_line = $this->employeesServices->getCostLine();
        return Inertia::render('HumanResource/ManagementEmployees/Employees', [
            'employee' => $employees,
            'costLine' => $cost_line
        ]);
    }

    public function search(Request $request)
    {
        $employees = $this->employeesServices->searchEmployees($request->state, $request->search, $request->cost_line);
        return response()->json($employees, 200);
    }

    public function create()
    {
        $pension = $this->employeesServices->pensionList;
        $costLines = $this->employeesServices->getCostLine();
        return Inertia::render('HumanResource/ManagementEmployees/EmployeesStoreAndUpdate', ['pensions' => $pension, 'costLines' => $costLines]);
    }

    public function store(CreateManagementEmployees $request)
    {

        $validateData = $request->validated();
        DB::beginTransaction();
        try {
            $employee_id = $this->employeesServices->updateOrCreateEmployee($request, null);
            $this->employeesServices->updateOrCreateContract($request->only((new Contract())->getFillable()), $employee_id);
            $this->employeesServices->updateOrCreateEducation($request, $employee_id);
            $this->employeesServices->updateOrCreateAddress($request->only((new Address())->getFillable()), $employee_id);
            if (isset($validateData['emergencyContacts'])) {
                Emergency::where('employee_id', $employee_id)->delete();
                $this->employeesServices->updateOrCreateEmergency($validateData['emergencyContacts'], $employee_id);
            }

            if (isset($validateData['familyDependents'])) {
                Family::where('employee_id', $employee_id)->delete();
                $this->employeesServices->updateOrCreateFamily($validateData['familyDependents'], $employee_id);
            }
            $this->employeesServices->updateOrCreateHealth($request->only((new Health())->getFillable()), $employee_id);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function edit($id)
    {
        $pension = $this->employeesServices->pensionList;
        $employeesedit = $this->employeesServices->relationEmployees($id);
        $costLines = $this->employeesServices->getCostLine();
        return Inertia::render('HumanResource/ManagementEmployees/EmployeesStoreAndUpdate', ['employees' => $employeesedit, 'pensions' => $pension, 'costLines' => $costLines]);
    }

    public function update(UpdateManagementEmployees $request, $id)
    {
        $validateData = $request->validated();
        DB::beginTransaction();
        try {
            $this->employeesServices->updateOrCreateEmployee($request, $id);
            $this->employeesServices->updateOrCreateContract($request->only((new Contract())->getFillable()), $id);
            $this->employeesServices->updateOrCreateEducation($request, $id);
            $this->employeesServices->updateOrCreateAddress($request->only((new Address())->getFillable()), $id);
            if (isset($validateData['emergencyContacts'])) {
                Emergency::where('employee_id', $id)->delete();
                $this->employeesServices->updateOrCreateEmergency($validateData['emergencyContacts'], $id);
            }

            if (isset($validateData['familyDependents'])) {
                Family::where('employee_id', $id)->delete();
                $this->employeesServices->updateOrCreateFamily($validateData['familyDependents'], $id);
            }
            $this->employeesServices->updateOrCreateHealth($request->only((new Health())->getFillable()), $id);
            DB::commit();
            return to_route('management.employees');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $this->employeesServices->deleteEmployees($id);
        return to_route('management.employees');
    }

    public function fired(FiredContractEmployees $request, $id)
    {
        $validateData = $request->validated();
        $this->employeesServices->firedEmployees($validateData, $id);
        $this->employeesServices->updatePayrollDetail($validateData, $id);
    }

    public function details($id)
    {
        $details = $this->employeesServices->relationEmployees($id);
        return Inertia::render('HumanResource/ManagementEmployees/EmployeesDetails', ['details' => $details]);
    }

    public function download(Education $id)
    {
        $this->employeesServices->relationEmployees($id);
    }

    public function reentry(Request $request, $id)
    {
        $this->employeesServices->reentry($request, $id);
    }

    public function happy_birthday()
    {
        $data = $this->employeesServices->happy_birthday();
        return response()->json([
            'happyBirthday' => $data->values()
        ]);
    }

    public function external_index()
    {
        $employees = $this->employeesServices->getExternalEmployees();
        $costLines = $this->employeesServices->getCostLine();
        return Inertia::render('HumanResource/ManagementEmployees/EmployeesExternal', [
            'employee' => $employees,
            'costLines' => $costLines
        ]);
    }

    public function external_search(Request $request)
    {
        $employees = $this->employeesServices->searchExternal($request->cost_line);
        return response()->json($employees, 200);
    }

    public function storeorupdate(StoreOrUpdateEmployeesExternal $request, $external_id = null)
    {
        $validateData = $request->validated();
        try {
            $this->employeesServices->storeOrUpdateExternalEmployees($validateData, $request, $external_id);
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function preview_curriculum_vitae(ExternalEmployee $external_preview_id)
    {
        $file = $this->employeesServices->preview($external_preview_id->curriculum_vitae);
        return $file;
    }
}
