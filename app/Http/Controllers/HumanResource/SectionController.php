<?php

namespace App\Http\Controllers\HumanResource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Section;
use App\Models\SubSection;

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

    public function destroySubSection(SubSection $subSection)
    {
        $subSection->delete();
        return to_route('sections.subSections');
    }


}
