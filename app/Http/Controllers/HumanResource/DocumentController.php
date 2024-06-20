<?php

namespace App\Http\Controllers\HumanResource;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentSection;
use App\Models\Subdivision;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use ZipArchive;

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

    public function updateSection (DocumentSection $section, Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string'
        ]);

        $section->update($data);
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

    public function updateSubdivision ($section, Subdivision $subdivision, Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string'
        ]);

        $subdivision->update($data);
    }

    public function destroySubdivision($section, Subdivision $subdivision)
    {
        $subdivision->delete();
        return to_route('documents.sections');
    }

    //Documents

    public function index(Request $request) {

        $documents = Document::all();
        $searchTerm = strtolower($request->query('searchTerm'));
        if ($searchTerm !== '') {
            $documents = $documents->where(function ($query) use ($searchTerm) {
                $query->where('title', 'like', '%' . $searchTerm . '%');
            });
        }else {
            $documents = $documents;
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
            'document' => 'required|mimes:pdf,doc,docx,ppt,pptx,xlsx,png,jpg,jpeg|max:2048',
            'subdivision_id' => 'required|numeric',
        ]);
        $documentName = null;
        if ($request->hasFile('document')) {
            $document = $request->file('document');
            $documentName = time() . '_' . $document->getClientOriginalName();
            $document->move(public_path('documents/documents/'), $documentName);
        }

        Document::create([
            'title' => $documentName,
            'subdivision_id' => $request->subdivision_id,
        ]);
    }

    public function update(Request $request, Document $id)
    {
        $request->validate([
            'document' => 'required|mimes:pdf,doc,docx,ppt,pptx,xlsx|max:2048',
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

    public function downloadSubdivisionDocumentsZip($section, $subdivisionId)
    {
        try {
            // Buscar la subdivisión por su ID con los documentos relacionados cargados
            $subdivision = Subdivision::with('documents')->findOrFail($subdivisionId);

            // Nombre del archivo ZIP temporal
            $zipFileName = "subdivision_{$subdivisionId}_documents.zip";

            // Ruta completa para el archivo ZIP temporal
            $zipFilePath = public_path("/documents/documents/{$zipFileName}");

            // Crear una instancia de ZipArchive
            $zip = new ZipArchive;

            // Intentar abrir el archivo ZIP para escritura
            if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                // Iterar sobre los documentos de la subdivisión y agregarlos al ZIP
                foreach ($subdivision->documents as $document) {
                    // Ruta completa del archivo de documento
                    $documentPath = public_path('/documents/documents/' . $document->title);

                    // Verificar si el archivo existe antes de agregarlo al ZIP
                    if (file_exists($documentPath)) {
                        // Agregar archivo al ZIP con un nombre relativo dentro del ZIP
                        $zip->addFile($documentPath, $document->title);
                    } else {
                        // Registrar un mensaje de advertencia si el archivo no existe
                        Log::warning("El archivo '{$document->title}' no existe en la ubicación especificada.");
                    }
                }

                // Cerrar el archivo ZIP
                $zip->close();

                // Descargar el archivo ZIP utilizando Laravel Response
                return response()->json('success');

            } else {
                // Si no se puede abrir el archivo ZIP para escritura
                Log::error('No se pudo abrir el archivo ZIP para escritura.');
                return response()->json(['error' => 'No se pudo abrir el archivo ZIP para escritura.'], 500);
            }
        } catch (\Exception $e) {
            // Capturar cualquier excepción que pueda ocurrir durante el proceso
            Log::error('Error al crear el archivo ZIP: ' . $e->getMessage());
            return response()->json(['error' => 'No se pudo crear el archivo ZIP.'], 500);
        }
    }

    public function deleteZip($section, $subdivisionId)
    {
        // Construir la ruta completa al archivo ZIP
        $path = public_path('/documents/documents/subdivision_' . $subdivisionId . '_documents.zip');

        try {
            // Verificar si el archivo existe
            if (file_exists($path)) {
                // Intentar eliminar el archivo
                unlink($path);
            } else {
                // Si el archivo no existe, registrar un mensaje de advertencia
                Log::warning("El archivo ZIP no existe: {$path}");
                return response()->json(['status' => 'error', 'message' => 'El archivo ZIP no existe.'], 404);
            }
        } catch (\Exception $e) {
            // Capturar cualquier excepción y registrar un mensaje de error
            Log::error("Error al intentar eliminar el archivo ZIP: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Ocurrió un error al intentar eliminar el archivo ZIP.'], 500);
        }
    }

    public function downloadSectionDocumentsZip($sectionId)
    {
        try {
            // Buscar la sección por su ID con todas las subdivisiones y documentos relacionados cargados
            $section = DocumentSection::with('subdivisions.documents')->findOrFail($sectionId);

            // Nombre del archivo ZIP temporal
            $zipFileName = "section_{$sectionId}_documents.zip";

            // Ruta completa para el archivo ZIP temporal
            $zipFilePath = public_path("/documents/documents/{$zipFileName}");

            // Crear una instancia de ZipArchive
            $zip = new ZipArchive;

            // Intentar abrir el archivo ZIP para escritura
            if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                foreach ($section->subdivisions as $subdivision) {
                    // Crear carpeta vacía para la subdivisión dentro del ZIP
                    $zip->addEmptyDir($subdivision->name);

                    foreach ($subdivision->documents as $document) {
                        // Ruta completa del archivo de documento
                        $documentPath = public_path('/documents/documents/' . $document->title);

                        // Verificar si el archivo existe antes de agregarlo al ZIP
                        if (file_exists($documentPath)) {
                            // Agregar archivo al ZIP con un nombre relativo dentro de la carpeta de la subdivisión
                            $zip->addFile($documentPath, "/{$subdivision->name}/{$document->title}");
                        } else {
                            // Registrar un mensaje de advertencia si el archivo no existe
                            Log::warning("El archivo '{$document->title}' no existe en la ubicación especificada.");
                        }
                    }
                }

                // Cerrar el archivo ZIP
                $zip->close();

            } else {
                // Si no se puede abrir el archivo ZIP para escritura
                Log::error('No se pudo abrir el archivo ZIP para escritura.');
                return response()->json(['error' => 'No se pudo abrir el archivo ZIP para escritura.'], 500);
            }
        } catch (\Exception $e) {
            // Capturar cualquier excepción que pueda ocurrir durante el proceso
            Log::error('Error al crear el archivo ZIP: ' . $e->getMessage());
            return response()->json(['error' => 'No se pudo crear el archivo ZIP.'], 500);
        }
    }

    public function deleteSectionZip($sectionId)
    {
        // Construir la ruta completa al archivo ZIP
        $zipFilePath = public_path("/documents/documents/section_{$sectionId}_documents.zip");

        try {
            // Verificar si el archivo existe
            if (file_exists($zipFilePath)) {
                // Intentar eliminar el archivo
                unlink($zipFilePath);
                return response()->json(['status' => 'success', 'message' => 'Archivo ZIP eliminado correctamente.']);
            } else {
                // Si el archivo no existe, registrar un mensaje de advertencia
                Log::warning("El archivo ZIP no existe: {$zipFilePath}");
                return response()->json(['status' => 'error', 'message' => 'El archivo ZIP no existe.'], 404);
            }
        } catch (\Exception $e) {
            // Capturar cualquier excepción y registrar un mensaje de error
            Log::error("Error al intentar eliminar el archivo ZIP: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Ocurrió un error al intentar eliminar el archivo ZIP.'], 500);
        }
    }
}

