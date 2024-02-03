<?php

namespace App\Http\Controllers\HumanResource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Section;
use App\Models\SubSection;
use App\Events\MyEvent;
use Carbon\Carbon;

class SectionController extends Controller
{
    
    //Sections
    public function showSections()
    {
        $sections = Section::all();
        return Inertia::render('HumanResource/AlarmManagement/Sections', [
            'sections' => $sections
        ]);
    }

    public function storeSection(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        Section::create([
            'name' => $request->name,
        ]);
    }

    public function destroySection(Section $section)
    {
        $section->delete();
        return to_route('sections.sections');
    }

    //SubSections
    public function showSubSections()
    {
        $subSections = SubSection::with('section')->paginate();
        $sections = Section::all();
        return Inertia::render('HumanResource/AlarmManagement/SubSections', [
            'subSections' => $subSections,
            'sections' => $sections
        ]);
    }

    public function showSubSection(SubSection $subSection)
    {
        $subSection->load('section');
        return Inertia::render('HumanResource/AlarmManagement/SubSectionInformation', [
            'subSection' => $subSection,
        ]);
    }

    public function storeSubSection(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        SubSection::create([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'requirements' => $request->requirements,
            'section_id' => $request->section_id,
        ]);
    }

    public function updateSubSection(Request $request, SubSection $subSection)
    {
        $request->validate([
            'name' => 'required|string',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        // Actualizar los campos de la subsección con los datos del formulario
        $subSection->update([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'requirements' => $request->requirements,
            'section_id' => $request->section_id,
        ]);
    }


    public function destroySubSection(SubSection $subSection)
    {
        $subSection->delete();
        return to_route('sections.subSections');
    }

    public function doTask()
    {
        // Obtener la fecha actual ajustada por el desfase
        $currentDate = Carbon::now();
        $currentDateUpdate = $currentDate->subHours(5);

        // Obtener todos los SubSection que están a punto de vencerse en los próximos 3 días y los que ya vencieron
        $subSections = SubSection::where('end_date', '<=', $currentDateUpdate->copy()->addDays(3)) // Ajustado para considerar los próximos 3 días y fechas pasadas
            ->get(); // Obtener una colección de resultados

        $totalSubSections = $subSections->count();

        return response()->json([
            'totalSubSections' => $totalSubSections,
            'subSections' => $subSections,
        ]);
    }


    public function doTask2()
    {
        // Obtener la fecha actual ajustada por el desfase
        $currentDate = Carbon::now();
        $currentDateUpdate = $currentDate->subHours(5);

        // Obtener todos los SubSection que están a punto de vencerse entre los próximos 4 y 7 días
        $subSections = SubSection::where('end_date', '>=', $currentDateUpdate->copy()->addDays(4)) // Ajustado para considerar los próximos 4 días
            ->where('end_date', '<=', $currentDateUpdate->copy()->addDays(7)) // Ajustado para considerar los próximos 7 días
            ->get(); // Obtener una colección de resultados

        $totalSubSections = $subSections->count();

        return response()->json([
            'totalSubSections' => $totalSubSections,
            'subSections' => $subSections,
        ]);
    }

}
