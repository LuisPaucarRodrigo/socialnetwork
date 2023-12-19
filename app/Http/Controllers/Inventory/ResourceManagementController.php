<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ResourceManagementController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Inventory/ResourceManagement/index');
    }
    public function new(Request $request)
    {
        return Inertia::render('Inventory/ResourceManagement/information',[
            'title'=>'Nuevo Recurso',
        ]);
    }
}
