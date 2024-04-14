<?php

namespace App\Http\Controllers\HumanResource;

use App\Http\Controllers\Controller;
use App\Http\Requests\VacationRequest\CreateVacationRequest;
use App\Http\Requests\VacationRequest\UpdateVacationRequest;
use App\Models\Vacation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class VacationController extends Controller
{

    public function index(Request $request, $review = false)
    {
        if ($review == false) {
            $vacations = Vacation::with('employee')->where('review_date', null);
        } else {
            $vacations = Vacation::with('employee')->where('review_date', '!=', null);
        }
        $searchTerm = strtolower($request->query('searchTerm'));
        if ($searchTerm !== '') {
            $vacations = $vacations->where(function ($query) use ($searchTerm) {
                $query->Where('type', 'like', '%' . $searchTerm . '%')
                    ->orWhere('status', 'like', '%' . $searchTerm . '%');
                $query->orWhereHas('employee', function ($subQuery) use ($searchTerm) {
                    $subQuery->where(DB::raw("CONCAT(name, ' ', lastname)"), 'like', '%' . $searchTerm . '%');
                });
            })->get();
        } else {
            $vacations = $vacations->paginate();
        }


        return Inertia::render('HumanResource/ManagementVacation/Vacation', [
            'vacations' => $vacations,
            'search' => $searchTerm,
            'boolean' => boolval($review)
        ]);
    }

    public function create()
    {
        return Inertia::render('HumanResource/ManagementVacation/CreateAndUpdate', [
            'employees' => Employee::all()
        ]);
    }

    public function store(CreateVacationRequest $request)
    {
        $documentName = null;
        if ($request->hasFile('doc_permission')) {
            $document = $request->file('doc_permission');
            $documentName = time() . '.' . $document->getClientOriginalName();
            $document->move(public_path('documents/permissions/'), $documentName);
        }

        Vacation::create([
            'employee_id' => $request->employee_id,
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_permissions' => $request->start_permissions,
            'end_permissions' => $request->end_permissions,
            'doc_permission' => $documentName,
            'reason' => $request->reason,
        ]);
    }

    public function edit($vacation)
    {
        return Inertia::render('HumanResource/ManagementVacation/CreateAndUpdate', [
            'vacation' => Vacation::with('employee')->find($vacation),
            'employees' => Employee::all()
        ]);
    }

    public function update(UpdateVacationRequest $request, $id)
    {
        $vacation = Vacation::find($id);
        $validateData = $request->validated();
        $vacation->update($validateData);
        return to_route('management.vacation');
    }

    public function review(Vacation $vacation)
    {

        return Inertia::render('HumanResource/ManagementVacation/Details', [
            'vacation' => $vacation,
            'details' => Vacation::with('employee')->find($vacation->id),
        ]);
    }

    public function details($vacation)
    {
        $details = Vacation::with('employee')->find($vacation);
        return Inertia::render('HumanResource/ManagementVacation/Details', [
            'details' => $details
        ]);
    }

    public function reviewed_and_decline(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric',
            'status' => 'required|in:Aceptado,Rechazado,Finalizado,Ausente',
            'coment' => 'sometimes|required|string'
        ]);
        $reviewed = Vacation::find($request->id);
        $dataToUpdate = [
            'review_date' => Carbon::now(),
            'status' => $request->status
        ];

        if ($request->has('coment')) {
            $dataToUpdate['coment'] = $request->coment;
        }

        $reviewed->update($dataToUpdate);
    }

    public function destroy(Vacation $vacation)
    {
        if ($vacation->type == "Permisos") {
            $filePath = "documents/permissions/$vacation->doc_permission";
            $path = public_path($filePath);
            if (file_exists($filePath)) {
                unlink($path);
            } else {
                abort(404, 'Documento no encontrado');
            }
        }
        $vacation->delete();
        return to_route('management.vacation');
    }

    public function download($filename)
    {
        $filePath = '/documents/permissions/' . $filename;
        $path = public_path($filePath);
        if (file_exists($path)) {
            return response()->download($path, $filename, [
                'Content-Type' => 'application/pdf',
            ]);
        }
        abort(404);
    }

    public function showDocument(Vacation $id)
    {
        $fileName = $id->doc_permission;
        $filePath = 'documents/permissions/' . $fileName;
        $path = public_path($filePath);
        if (file_exists($path)) {
            return response()->file($path);
        }

        abort(404, 'Documento no encontrado');
    }

    public function alarmPermissions()
    {
        $now = Carbon::now();
        $permissions = Vacation::where('type', 'Permisos')
            ->where('end_permissions', '<=', $now->copy()->addHours(1))
            ->where('review_date', '!=', null)
            ->where('status', 'Aceptado')
            ->get();
        return response()->json([
            'totalPermissions' => $permissions->count(),
            'permissions' => $permissions,
        ]);
    }

    public function alarmVacation()
    {
        $now = Carbon::now();
        $currentDateUpdate = $now->subHours(5);
        $vacation = Vacation::where('type', 'Vacaciones')
            ->where('end_date', '<=', $currentDateUpdate->copy()->addDays(3))
            ->where('review_date', '!=', null)
            ->where('status', 'Aceptado')
            ->get();
        return response()->json([
            'totalVacation' => $vacation->count(),
            'vacation' => $vacation,
        ]);
    }
}
