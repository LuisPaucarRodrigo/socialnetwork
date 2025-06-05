<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use App\Models\MonthProject;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MonthProjectController extends Controller
{
    public function index()
    {
        return Inertia::render('ProjectArea/ProjectManagement/Administrative/MonthProject', [
            'month_projects' => MonthProject::orderBy('created_at', 'desc')->paginate(12)
        ]);
    }
    public function store(Request $request, $mp_id = null)
    {
        $rgToStore = MonthProject::find($mp_id);

        $data = $request->validate([
            'date' => 'required|string|size:7|unique:month_projects,date,' . ($mp_id ?? 'NULL') . ',id',
        ]);
        if ($rgToStore) {
            $rgToStore->update($data);
        } else {
            $rgToStore = MonthProject::create($data);
        }
        return response()->json($rgToStore);
    }

    public function destroy($mp_id)
    {
        $rgToStore = MonthProject::findOrFail($mp_id);
        $rgToStore->delete();
        return response()->json(['msg'=> 'success']);
    }

}
