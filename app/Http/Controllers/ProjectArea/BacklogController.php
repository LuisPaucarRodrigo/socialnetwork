<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use App\Models\BacklogSite;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BacklogController extends Controller
{
    public function index() {
        return Inertia::render('ProjectArea/Backlog/Index');
    }


    public function autocomplete(Request $request) {
        $query = $request->input('query');
        $items = BacklogSite::where('site_name', 'like', "%{$query}%")->orWhere('site_id', 'like', "%{$query}%")->get();
        return response()->json($items);
    }
}
