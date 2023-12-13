<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaskManagementController extends Controller
{
    public function index()
    {
        return Inertia::render('ProjectArea/TasksManagement/index');
    }

    public function new()
    {
        return Inertia::render('ProjectArea/TasksManagement/newTask');
    }
    public function edit()
    {
        return Inertia::render('ProjectArea/TasksManagement/editTask');
    }
}
