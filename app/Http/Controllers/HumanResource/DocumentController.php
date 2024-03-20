<?php

namespace App\Http\Controllers\HumanResource;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentSection;
use App\Models\Subdivision;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    
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

    public function index(Request $request) {

        $documents = Document::with('section');
        $searchTerm = strtolower($request->query('searchTerm'));
        if ($searchTerm !== '') {
            $documents = $documents->where(function ($query) use ($searchTerm) {
                $query->where('title', 'like', '%' . $searchTerm . '%');
            })->get();
        }else {
            $documents = $documents->get();
        }


        $sections = DocumentSection::all();
        $subdivisions = Subdivision::all();
        return Inertia::render('HumanResource/Documents/Document', [
            'documents' => $documents,
            'sections' => $sections,
            'subdivisions' => $subdivisions,
            'search' => $searchTerm,
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'document' => 'required|mimes:pdf,doc,docx,ppt,pptx,xlsx|max:2048',
            'section_id' => 'required|numeric',
            'subdivision_id' => 'required|numeric',
        ]);
        $documentName = null;
        if ($request->hasFile('document')) {
            $document = $request->file('document');
            $documentName = time() . '_' . $document->getClientOriginalName();
            $document->move(public_path('documents/documents/'), $documentName);
        }

        Document::create([
            'section_id' => $request->section_id,
            'title' => $documentName,
            'subdivision_id' => $request->subdivision_id,
        ]);
    }

    public function update(Request $request, Document $id)
    {
        $request->validate([
            'document' => 'required|mimes:pdf,doc,docx,ppt,pptx,xlsx|max:2048',
            'section_id' => 'required|numeric',
            'subdivision_id' => 'required|numeric',
        ]);

        $fileName = $id->title;
        $filePath = "documents/documents/$fileName";
        $path = public_path($filePath);
        if (file_exists($path)) {
            unlink($path);
            $documentName = null;
            if ($request->hasFile('document')) {
                $document = $request->file('document');
                $documentName = time() . '_' . $document->getClientOriginalName();
                $document->move(public_path('documents/documents/'), $documentName);
            }

            $id->update([
                'section_id' => $request->section_id,
                'title' => $documentName,
                'subdivision_id' => $request->subdivision_id,
            ]);
        } else {
            dd("El archivo no existe en la ruta: $filePath");
        }
    }

    public function destroy(Document $id)
    {
        $fileName = $id->title;
        $filePath = "documents/documents/$fileName";
        $path = public_path($filePath);
        if (file_exists($path)) {
            unlink($path);
            $id->delete();
        } else {
            dd("El archivo no existe en la ruta: $filePath");
        }
        return to_route('documents.index');
    }


    public function downloadDocument(Document $document)
    {
        $fileName = $document->title;
        $filePath = "documents/documents/$fileName";
        $path = public_path($filePath);
        if (file_exists($path)) {
            return response()->download($path, $fileName);
        }
        abort(404, 'Documento no encontrado');
    }

    public function showDocument(Document $document)
    {
        $fileName = $document->title;
        $filePath = '/documents/documents/' . $fileName;
        $path = public_path($filePath);
        if (file_exists($path)) {
            return response()->file($path);
        }
        abort(404, 'Documento no encontrado');
    }
}
