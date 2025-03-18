<?php

namespace App\Services;

use App\Models\Car;
use App\Models\Employee;
use App\Models\PextProjectExpense;
use App\Models\PreprojectTitle;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;

class ApiServices
{
    public function findUser($id): Object
    {
        $user = User::select('id', 'name', 'dni', 'email')->find($id);
        return $user;
    }

    public function storeBase64Image($photo, $path, $name): String
    {
        try {
            $image = str_replace('data:image/png;base64,', '', $photo);
            $image = str_replace('', '+', $image);
            $imageContent = base64_decode($image);
            $imagename = time() . $name . '.png';
            file_put_contents(public_path($path) . "/" . $imagename, $imageContent);
            return $imagename;
        } catch (Exception $e) {
            abort(500, 'something went wrong');
        }
    }

    public function preprojectTitle($id)
    {
        $preprojectTitle = PreprojectTitle::with([
            'preprojectCodes:id,preproject_title_id,code_id,status',
            'preprojectCodes.code:id,code',
        ])
            ->whereNotNull('state')->where('preproject_id', $id)
            ->select('id', 'type');

        return $preprojectTitle;
    }

    public function employeesCostLine($cost_line_id): Builder
    {
        $employees = Employee::select('id', 'name', 'lastname')
            ->whereHas('contract', function ($e) use ($cost_line_id) {
                $e->where('cost_line_id', $cost_line_id);
            });
        return $employees;
    }

    public function car($cost_line_id): Builder
    {
        $car = Car::select('id', 'plate')
            ->where('cost_line_id', $cost_line_id);
        return $car;
    }

    public function transformExpenseData($validateData): array
    {
        $docDate = Carbon::createFromFormat('d/m/Y', $validateData['doc_date']);
        $validateData['doc_date'] = $docDate->format('Y-m-d');
        $validateData['fixedOrAdditional'] = 0;

        if (($validateData['zone'] !== 'MDD') && $validateData['type_doc'] === 'Factura') {
            $validateData['igv'] = 18;
        }

        $user = Auth::user();
        $validateData['description'] = $user->name . ", " . $validateData['description'];

        if ($validateData['photo']) {
            $validateData['photo'] = $this->storeBase64Image(
                $validateData['photo'],
                'documents/expensesPext',
                'Gasto Pext'
            );
        }

        $validateData['user_id'] = $user->id;

        return $validateData;
    }

    public function getExpensesPext(): Builder
    {
        $user = Auth::user();
        $expensesPext = PextProjectExpense::where('user_id', $user->id)
            ->whereHas('project', function ($query) {
                $query->where('cost_line_id', 2);
            });
        return $expensesPext;
    }

    public function userPreproject($user): Builder
    {
        $user->preprojects()
            ->select('preprojects.id as preproject_id', 'preproject_user.id as pivot_id', 'code', 'description', 'date', 'observation', 'status')
            ->whereNull('status')
            ->whereHas('preprojectTitles', function ($query) {
                $query->where('state', 1);
            });
        return $user;
    }
}
