<?php

namespace App\Http\Controllers\HumanResource;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ScheduleController extends Controller
{

    public function index() {
        $currentDate = Carbon::now();
        $lastSchedule = Schedule::latest()->first();
        $file = null;

        if ($lastSchedule) {
            $lastScheduleDate = Carbon::createFromFormat('Y-m-d', $lastSchedule->date);
            if ($lastScheduleDate->month === $currentDate->month && $lastScheduleDate->year === $currentDate->year) {
                $file = $lastSchedule;
            }else{

            }
        }
        $schedules = Schedule::paginate(10);
        return Inertia::render('HumanResource/Schedules/Schedules', [
            'schedules' => $schedules,
            'file' => $file,
        ]);
    }

    public function latest()
    {
        $currentDate = Carbon::now();
        $lastSchedule = Schedule::latest()->first();

        if ($lastSchedule) {
            $lastScheduleDate = Carbon::createFromFormat('Y-m-d', $lastSchedule->date);
            if ($lastScheduleDate->month === $currentDate->month && $lastScheduleDate->year === $currentDate->year) {
                return response()->json(['hasSchedule' => true, 'schedule' => $lastSchedule]);
            } else {
                return response()->json(['hasSchedule' => false, 'message' => 'No schedule']);
            }
        } else {
            return response()->json(['hasSchedule' => false, 'message' => 'No schedule']);
        }
    }

    public function preview(Schedule $schedule)
    {
        $fileName = $schedule->schedule_title;
        $filePath = '/documents/schedules/' . $fileName;
        $path = public_path($filePath);
        if (file_exists($path)) {
            return response()->file($path);
        }
        abort(404, 'Documento no encontrado');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'document' => 'required|mimes:pdf|max:2048',
        ]);

        $currentDate = Carbon::now();
        $lastSchedule = Schedule::latest()->first();
        $monthName = $currentDate->locale('es')->translatedFormat('F'); // Obtiene el nombre del mes en español
        $year = $currentDate->year;
        $documentName = null;

        if ($request->schedule) {
            $editingSchedule = Schedule::find($request->schedule);
            $fileName = $editingSchedule->schedule_title;
            $filePath = "documents/schedules/$fileName";
            $path = public_path($filePath);
            if (file_exists($path)) {
                unlink($path);
                if ($request->hasFile('document')) {
                    $document = $request->file('document');
                    $documentName = time() . '_Horario_' . $monthName . '_' . $year . '.pdf';
                    $document->move(public_path('documents/schedules/'), $documentName);
                }
                $editingSchedule->update([
                    'schedule_title' => $documentName,
                    'date' => $currentDate->toDateString(),
                ]);
            }
            
        } else {
            if ($request->hasFile('document')) {
                $document = $request->file('document');
                $documentName = time() . '_Horario_' . $monthName . '_' . $year . '.pdf';
                $document->move(public_path('documents/schedules/'), $documentName);
            }
            Schedule::create([
                'schedule_title' => $documentName,
                'date' => $currentDate->toDateString(),
            ]);
        } 
    }  

    public function download(Schedule $schedule)
    {
        $fileName = $schedule->schedule_title;
        $filePath = "documents/schedules/$fileName";
        $path = public_path($filePath);
        if (file_exists($path)) {
            return response()->download($path, $fileName);
        }
        abort(404, 'Documento no encontrado');
    }
}
