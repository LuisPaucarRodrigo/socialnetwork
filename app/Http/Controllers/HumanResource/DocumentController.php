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

        $section = DocumentSection::create([
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

    public function index_info_additional(){
        //
    }

    public function edit_info_additional(Vacation $vacation)
    {
        //
    }


    /**
     * Show the form for creating a new resource.
     */
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
    public function show(Vacation $vacation)
    {
        return response()->json($vacation->load('employee'));
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
    public function destroy(Document $document)
    {
        // Obtén el nombre del archivo desde el modelo (con extensión)
        $fileName = $document->title;

        // Construye la ruta completa del archivo
        $filePath = storage_path("app/public/documents/HumanResource/$fileName");

        // Verifica si el archivo existe antes de intentar eliminarlo
        if (file_exists($filePath)) {
            // Elimina el archivo de almacenamiento
            Storage::delete("public/documents/HumanResource/$fileName");

            // Elimina el registro de la base de datos
            $document->delete();
        } else {
            // Agrega un dd para verificar si el archivo no existe
            dd("El archivo no existe en la ruta: $filePath");
        }

        // Redirige a la ruta de documentos
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
