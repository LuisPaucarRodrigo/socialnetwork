<?php

namespace App\Http\Controllers\HumanResource;

use App\Http\Controllers\Controller;
use App\Models\FormationProgram;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FormationDevelopment extends Controller
{
    public function index() {
        return Inertia::render('HumanResource/FormationDevelopments/FormationPrograms', [
            'formationPrograms' => FormationProgram::paginate()
        ]);
    }

    public function create() {
        return Inertia::render('HumanResource/FormationDevelopments/Create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name'=> 'required',
            'type'=> 'required',
            'description' => 'required'
        ]);
        FormationProgram::create($data);
        return redirect()->route('management.employees.formation_development');
    }
}
