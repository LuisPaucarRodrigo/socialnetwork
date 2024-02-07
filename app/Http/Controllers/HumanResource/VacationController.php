<?php

namespace App\Http\Controllers\HumanResource;

use App\Http\Controllers\Controller;
use App\Http\Requests\VacationRequest\CreateVacationRequest;
use App\Http\Requests\VacationRequest\UpdateVacationRequest;
use App\Models\Vacation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Employee;
use BaconQrCode\Renderer\Path\Move;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

use function PHPUnit\Framework\fileExists;

class VacationController extends Controller
{

    public function index()
    {
        $vacations = Vacation::with('employee')->paginate();
        return Inertia::render('HumanResource/ManagementVacation/Vacation', [
            'vacations' => $vacations,
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

    public function reviewed(Request $request, $vacation)
    {
        $request->validate([
            'start_date_accepted' => 'nullable|date',
            'end_date_accepted' => 'nullable|date|after:start_date_accepted'
        ]);

        $reviewed = Vacation::find($vacation);
        $reviewed->update([
            'start_date_accepted' => $request->start_date_accepted,
            'end_date_accepted' => $request->end_date_accepted,
            'status' => 'Aceptado'
        ]);
        return to_route('management.vacation');
    }

    public function decline($id)
    {
        $vacation = Vacation::find($id);
        $vacation->update([
            'status' => 'Rechazado'
        ]);
        return to_route('management.vacation');
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
}
