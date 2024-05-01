<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\CicsaSection;
use App\Models\CicsaSubSection;
use Carbon\Carbon;

class CicsaSectionController extends Controller
{

    //Sections
    public function showSections()
    {
        $sections = CicsaSection::all();
        return Inertia::render('ProjectArea/AlarmManagement/CicsaSections', [
            'sections' => $sections
        ]);
    }

    public function storeSection(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        CicsaSection::create([
            'name' => $request->name,
        ]);
    }

    public function destroySection(CicsaSection $section)
    {
        $section->delete();
        return to_route('sections.cicsaSections');
    }

    //SubSections
    public function showSubSections()
    {
        $subSections = CicsaSubSection::with('cicsa_section')->paginate();
        $sections = CicsaSection::all();
        return Inertia::render('ProjectArea/AlarmManagement/CicsaSubSections', [
            'subSections' => $subSections,
            'sections' => $sections
        ]);
    }

    public function showSubSection(CicsaSubSection $subSection)
    {
        $subSection->load('cicsa_section');
        return Inertia::render('ProjectArea/AlarmManagement/CicsaSubSectionInformation', [
            'subSection' => $subSection,
        ]);
    }

    public function storeSubSection(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        CicsaSubSection::create([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'requirements' => $request->requirements,
            'cicsa_section_id' => $request->cicsa_section_id,
        ]);
    }

    public function updateSubSection(Request $request, CicsaSubSection $subSection)
    {
        $request->validate([
            'name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',

        ]);

        // Actualizar los campos de la subsección con los datos del formulario
        $subSection->update([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'requirements' => $request->requirements,
            'cicsa_section_id' => $request->cicsa_section_id,
        ]);
    }


    public function destroySubSection(CicsaSubSection $subSection)
    {
        $subSection->delete();
        return to_route('sections.cicsaSubSections');
    }

    public function doTask()
    {
        // Obtener la fecha actual ajustada por el desfase
        $currentDate = Carbon::now();
        $currentDateUpdate = $currentDate->subHours(5);

        // Obtener todos los SubSection que están a punto de vencerse en los próximos 3 días y los que ya vencieron
        $subSections = CicsaSubSection::where('end_date', '<=', $currentDateUpdate->copy()->addDays(3)) // Ajustado para considerar los próximos 3 días y fechas pasadas
            ->get(); // Obtener una colección de resultados
            $subSections7 = CicsaSubSection::where('end_date', '>=', $currentDateUpdate->copy()->addDays(3)) // Ajustado para considerar los próximos 4 días
            ->where('end_date', '<=', $currentDateUpdate->copy()->addDays(7)) // Ajustado para considerar los próximos 7 días
            ->get(); // Obtener una colección de resultados

        return response()->json([
            'subSections' => $subSections,
            'subSections7' => $subSections7,
        ]);
    }

}
