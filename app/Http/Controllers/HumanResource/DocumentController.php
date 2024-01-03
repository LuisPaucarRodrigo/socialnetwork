<?php

namespace App\Http\Controllers\HumanResource;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentSection;
use App\Models\Vacation;
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

        return to_route('documents.sections');
    }

    public function destroySection(DocumentSection $section)
    {
        $section->delete();
        return to_route('documents.sections');
    }

    public function index()
    {
        $documents = Document::with('section')->paginate();
        $sections = DocumentSection::all();
        return Inertia::render('HumanResource/Documents/Document', [
            'documents' => $documents,
            'sections' => $sections
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'document' => 'required|mimes:pdf,doc,docx|max:2048',
            'section_id' => 'required',
        ]);

        $document = $request->file('document');
        $documentName = time() . '_' . $document->getClientOriginalName(); // Generar un nombre único para el archivo

        $document->storeAs('public/documents/HumanResource/', $documentName); // Guardar el archivo en public/documents/HumanResource

        // Crear el documento en la base de datos
        Document::create([
            'section_id' => $request->section_id,
            'title' => $documentName,
        ]);

        return to_route('documents.index');
    }

    public function show(Vacation $vacation)
    {
        return response()->json($vacation->load('employee'));
    }

    public function destroy(Document $document)
    {
        $fileName = $document->title;

        $filePath = storage_path("app/public/documents/HumanResource/$fileName");
        if (file_exists($filePath)) {
 
            Storage::delete("public/documents/HumanResource/$fileName");
            $document->delete();
        } else {
            dd("El archivo no existe en la ruta: $filePath");
        }
        
        return redirect()->route('documents.index');
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
