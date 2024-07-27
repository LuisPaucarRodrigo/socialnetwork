<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backlog\BacklogRequest;
use App\Models\Backlog;
use App\Models\BacklogSite;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BacklogController extends Controller
{
    public function index() {
        return Inertia::render('ProjectArea/Backlog/Index', 
            ['inertiaBacklogs' => Backlog::with('backlog_site')->orderBy('created_at', 'desc')->paginate(20)]
        );
    }


    public function autocomplete(Request $request) {
        $query = $request->input('query');
        $items = BacklogSite::where('site_name', 'like', "%{$query}%")->orWhere('site_id', 'like', "%{$query}%")->get();
        return response()->json($items);
    }



    public function store(BacklogRequest $request) {
        $originalData = $request->all();
        if (empty($originalData)) {
            return response()->json(['message' => 'No data provided'], 400);
        }
        $data = $request->validated();
        $back_res = Backlog::updateOrCreate(['id' => $request->id], $data);
        $back_res->load('backlog_site');
        return response()->json(['backlog_res'=> $back_res], 200);
    }


    public function destroy ($backlog_id) {
        $backlog = Backlog::find($backlog_id);
        $backlog->delete();
        return response()->json(['message'=>'success'], 200);
    }
}
