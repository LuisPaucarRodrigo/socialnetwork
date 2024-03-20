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
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

use function PHPUnit\Framework\fileExists;

class VacationController extends Controller
{

    public function index(Request $request)
    {
        $vacations = Vacation::with('employee');
        $searchTerm = strtolower($request->query('searchTerm'));
        if ($searchTerm !== '') {
            $vacations = $vacations->where(function ($query) use ($searchTerm) {
                if (preg_match('/^(0?[1-9]|[12][0-9]|3[01])\/(0?[1-9]|1[0-2])(?:\/\d{4})?(?: \d{2}:\d{2})?$/', $searchTerm)) {
                    $searchDate = Carbon::createFromFormat('d/m/Y H:i', $searchTerm)->format('Y-m-d H:i');
        
                    $query->where('start_date', 'like', '%' . $searchDate . '%')
                          ->orWhere('end_date', 'like', '%' . $searchDate . '%')
                          ->orWhere('start_permissions', 'like', '%' . $searchDate . '%')
                          ->orWhere('end_permissions', 'like', '%' . $searchDate . '%')
                          ->orWhere('review_date', 'like', '%' . $searchDate . '%');
                    $query->orWhereHas('employee', function ($subQuery) use ($searchTerm) { 
                            $subQuery->where('name', 'like', '%' . $searchTerm . '%')
                                ->orWhere('lastname', 'like', '%' . $searchTerm . '%');
                        });
                } else {
                    $query->where('start_date', 'like', '%' . $searchTerm . '%')
                          ->orWhere('end_date', 'like', '%' . $searchTerm . '%')
                          ->orWhere('start_permissions', 'like', '%' . $searchTerm . '%')
                          ->orWhere('end_permissions', 'like', '%' . $searchTerm . '%')
                          ->orWhere('review_date', 'like', '%' . $searchTerm . '%')
                          ->orWhere('type', 'like', '%' . $searchTerm . '%')
                          ->orWhere('status', 'like', '%' . $searchTerm . '%')
                          ;
                    $query->orWhereHas('employee', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('name', 'like', '%' . $searchTerm . '%')
                                 ->orWhere('lastname', 'like', '%' . $searchTerm . '%');
                    });
                }
            })->get();
        } else {
            $vacations = $vacations->paginate();
        }


        return Inertia::render('HumanResource/ManagementVacation/Vacation', [
            'vacations' => $vacations,
            'search' => $searchTerm,
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

    public function reviewed($id)
    {

        $reviewed = Vacation::find($id);
        $reviewed->update([
            'review_date' => Carbon::now(),
            'status' => 'Aceptado'
        ]);
        return to_route('management.vacation');
    }

    public function decline($id)
    {
        $vacation = Vacation::find($id);
        $vacation->update([
            'review_date' => Carbon::now(),
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
