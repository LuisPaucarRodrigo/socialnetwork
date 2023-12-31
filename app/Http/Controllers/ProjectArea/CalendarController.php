<?php

namespace App\Http\Controllers\ProjectArea;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Vacation;
use App\Models\Tasks;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::all();
        return Inertia::render('ProjectArea/Calendar/ProjectsCalendar', [
            'projects' => $projects,
        ]);
    }

    public function index_info_additional(){
        $vacations = Vacation::with('employee')->paginate();
        return Inertia::render('HumanResource/ManagementVacation/VacationInformation', [
            'vacations' => $vacations,
            'employees' => Employee::all()
        ]);
    }

    public function edit_info_additional(Vacation $vacation)
    {
        // Lógica para cargar la vista de información adicional para la edición
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request){
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        // Obtén el proyecto específico por ID
        $project = Project::with('tasks')->find($project->id);
        //dd($project);
        // Verifica si el proyecto fue encontrado
        if (!$project) {
            // Puedes manejar el caso en el que el proyecto no se encuentre, por ejemplo, redirigiendo o lanzando una excepción.
            // Aquí se lanza una excepción como ejemplo.
            throw new \Exception('Proyecto no encontrado');
        }

        // Renderiza la vista solo con el proyecto específico
        return Inertia::render('ProjectArea/Calendar/TasksCalendar', [
            'project' => $project,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vacation $vacacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vacation $vacation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vacation $vacation)
    {
        //
    }
}
