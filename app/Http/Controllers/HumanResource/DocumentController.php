<?php

namespace App\Http\Controllers\HumanResource;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentSection;
use App\Models\Subdivision;
use App\Models\Vacation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    
    //Sections

    public function showSections()
    {
        $sections = DocumentSection::all();
        return Inertia::render('HumanResource/Documents/Sections', [
            'sections' => $sections
        ]);
    }

    public function storeSection(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        DocumentSection::create([
            'name' => $request->name,
        ]);
    }

    public function destroySection(DocumentSection $section)
    {
        $section->delete();
        return to_route('documents.sections');
    }

    //Subdivisions

    public function showSubdivisions(DocumentSection $section)
    {
        $subdivisions = Subdivision::where('section_id', $section->id)->get();
        return Inertia::render('HumanResource/Documents/Subdivisions', [
            'subdivisions' => $subdivisions,
            'section' => $section,
        ]);
    }

    public function storeSubdivision(DocumentSection $section, Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        Subdivision::create([
            'name' => $request->name,
            'section_id' => $section->id,
        ]);
    }

    public function destroySubdivision(DocumentSection $section, Subdivision $subdivision)
    {
        $subdivision->delete();
        return to_route('documents.sections');
    }

    //Documents 

    public function index()
    {
        $documents = Document::with('section')->paginate();
        $sections = DocumentSection::all();
        $subdivisions = Subdivision::all();
        return Inertia::render('HumanResource/Documents/Document', [
            'documents' => $documents,
            'sections' => $sections,
            'subdivisions' => $subdivisions,
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'document' => 'required|mimes:pdf|max:2048',
            'section_id' => 'required|numeric',
            'subdivision_id' => 'required|numeric',
        ]);

        $document = $request->file('document');
        $documentName = time() . '_' . $document->getClientOriginalName(); // Generar un nombre único para el archivo

        $document->storeAs('public/documents/HumanResource/', $documentName); // Guardar el archivo en public/documents/HumanResource

        Document::create([
            'section_id' => $request->section_id,
            'title' => $documentName,
            'subdivision_id' => $request->subdivision_id,
        ]);
    }

    public function show(Vacation $vacation)
    {
        return response()->json($vacation->load('employee'));
    }

    public function destroy(Document $id)
    {
        $fileName = $id->title;
        $filePath = storage_path("app/public/documents/HumanResource/$fileName");
        if (file_exists($filePath)) {
            Storage::delete("public/documents/HumanResource/$fileName");
            $id->delete();
        } else {
            dd("El archivo no existe en la ruta: $filePath");
        }
        return to_route('documents.index');
    }
    

    public function downloadDocument(Document $document)
    {
        $fileName = $document->title;
        $filePath = storage_path("app/public/documents/HumanResource/$fileName");

        if (!$fileName) {
            abort(404, 'Documento no encontrado');
        }

        return response()->download($filePath, $fileName);
    }

    public function showDocument(Document $document)
    {
        $fileName = $document->title;
        $filePath = storage_path("app/public/documents/HumanResource/$fileName");
        if (!file_exists($filePath)) {
            abort(404, 'Documento no encontrado');
        }

        return response()->file($filePath);
    }

}
