<?php

namespace App\Http\Controllers\Contractors;

use App\Http\Controllers\Controller;
use App\Models\Contractors\ContractorCostLine;
use App\Models\Contractors\ContractorDocument;
use App\Models\Contractors\ContractorDocumentRegister;
use App\Models\Contractors\ContractorDocumentSection;
use App\Models\Contractors\ContractorEmployee;
use App\Models\Contractors\ContractorExternalEmployee;
use App\Models\Contractors\ContractorSubdivision;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use ZipArchive;
use Exception;

class ContractorDocumentController extends Controller
{
    public function showSections()
    {
        $sections = ContractorDocumentSection::all();
        return Inertia::render('HumanResource/Documents/Sections', [
            'sections' => $sections->load('subdivisions')
        ]);
    }

    //Documents

    public function index()
    {
        return Inertia::render('HumanResource/Documents/Document', [
            'sections' => ContractorDocumentSection::with('subdivisions')->get(),
            'subdivisions' => ContractorSubdivision::all(),
            'cost_lines' => ContractorCostLine::select('id', 'name')->get(),
            'employees' => ContractorEmployee::whereHas('contract', function ($query) {
                $query->where('state', 'Active');
            })
                ->orderBy('name')
                ->with('contract')
                ->get(),
            'e_employees' => ContractorExternalEmployee::orderBy('name')->get(),
        ]);
    }

    public function documentReport(Request $request)
    {
        $data = $request->validate([
            'employees' => 'nullable|array',
            'employees.*' => 'integer',
            'external_employees' => 'nullable|array',
            'external_employees.*' => 'integer',
            'subdivisions' => 'nullable|array',
            'subdivisions.*' => 'integer',
        ]);

        if (empty($data['employees']) && empty($data['external_employees']) && empty($data['subdivisions'])) {
            abort(403, 'Acción no permitida');
        }

        $query = ContractorDocument::with('subdivision.section')
            ->where(function ($q) use ($data) {
                $q->where(function ($subQ) use ($data) {
                    if (!empty($data['employees'])) {
                        $subQ->whereIn('employee_id', $data['employees']);
                    }
                    if (!empty($data['external_employees'])) {
                        $subQ->orWhereIn('e_employee_id', $data['external_employees']);
                    }
                });

                if (!empty($data['subdivisions'])) {
                    $q->whereIn('subdivision_id', $data['subdivisions']);
                }
            });
        $documents = $query->get();
        return response()->json($documents);
    }


    public function downloadDocument(ContractorDocument $document)
    {
        $fileName = $document->title;
        $filePath = "documents/documents/$fileName";
        $path = public_path($filePath);
        if (file_exists($path)) {
            ob_end_clean();
            return response()->download($path, $fileName);
        }
        abort(404, 'Documento no encontrado');
    }

    public function showDocument(ContractorDocument $document)
    {
        $fileName = $document->title;
        $filePath = '/documents/documents/' . $fileName;
        $path = public_path($filePath);
        if (file_exists($path)) {
            ob_end_clean();
            return response()->file($path);
        }
        abort(404, 'Documento no encontrado');
    }

    public function massiveZip(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        $documents = ContractorDocument::with('subdivision.section')->whereIn('id', $request->ids)->get();

        $zipFileName = "Documentos_Seccionados_" . now()->format('Ymd_His') . ".zip";
        $zipFilePath = public_path("/documents/documents/{$zipFileName}");

        if (file_exists($zipFilePath)) {
            unlink($zipFilePath);
        }

        $zip = new ZipArchive;

        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            Log::error("No se pudo crear el archivo ZIP.");
            return response()->json(['error' => 'No se pudo abrir el archivo ZIP.'], 500);
        }

        foreach ($documents as $doc) {
            $subdivision = $doc->subdivision;
            $section = $subdivision?->section;

            if (!$subdivision || !$section) {
                Log::warning("Documento sin relaciones válidas: ID {$doc->id}");
                continue;
            }

            $sectionName = preg_replace('/[\/\\\\?%*:|"<>]/', '-', $section->name);
            $subdivisionName = preg_replace('/[\/\\\\?%*:|"<>]/', '-', $subdivision->name);
            $fileName = preg_replace('/[\/\\\\?%*:|"<>]/', '-', $doc->title);

            $documentPath = public_path('/documents/documents/' . $doc->title);

            if (file_exists($documentPath)) {
                $zip->addFile($documentPath, "{$sectionName}/{$subdivisionName}/{$fileName}");
            } else {
                Log::warning("Archivo no encontrado: " . $documentPath);
            }
        }

        $zip->close();

        ob_end_clean();
        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }


    public function downloadSubdivisionDocumentsZip($section, $subdivisionId)
    {
        try {
            // Buscar la subdivisión por su ID con los documentos relacionados cargados
            $subdivision = ContractorSubdivision::with('documents')->findOrFail($subdivisionId);

            // Nombre del archivo ZIP temporal
            $zipFileName = "Subdivisión {$subdivision->name}.zip";

            // Ruta completa para el archivo ZIP temporal
            $zipFilePath = public_path("/documents/documents/{$zipFileName}");

            // Crear una instancia de ZipArchive
            $zip = new ZipArchive;

            // Intentar abrir el archivo ZIP para escritura
            if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                // Verificar si hay documentos
                if ($subdivision->documents && $subdivision->documents->count() > 0) {
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
                } else {
                    // Añadir un mensaje de texto vacío si no hay documentos
                    $zip->addFromString('empty.txt', 'No hay documentos disponibles para esta subdivisión.');
                }

                // Cerrar el archivo ZIP
                $zip->close();

                // Limpiar el búfer de salida
                ob_end_clean();
                // Descargar el archivo ZIP y eliminarlo después del envío
                return response()->download($zipFilePath)->deleteFileAfterSend(true);
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

    public function downloadSectionDocumentsZip($sectionId)
    {
        try {
            $section = ContractorDocumentSection::with('subdivisions.documents')->findOrFail($sectionId);

            // Nombre del archivo ZIP temporal
            $zipFileName = "Sección {$section->name}.zip";

            // Ruta completa para el archivo ZIP temporal
            $zipFilePath = public_path("/documents/documents/{$zipFileName}");

            // Crear una instancia de ZipArchive
            $zip = new ZipArchive;

            // Intentar abrir el archivo ZIP para escritura
            if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                // Verificar si hay subdivisiones
                if ($section->subdivisions && $section->subdivisions->count() > 0) {
                    foreach ($section->subdivisions as $subdivision) {
                        // Crear carpeta vacía para la subdivisión dentro del ZIP
                        $zip->addEmptyDir($subdivision->name);

                        if ($subdivision->documents && $subdivision->documents->count() > 0) {
                            foreach ($subdivision->documents as $document) {
                                // Ruta completa del archivo de documento
                                $documentPath = public_path('/documents/documents/' . $document->title);

                                // Verificar si el archivo existe antes de agregarlo al ZIP
                                if (file_exists($documentPath)) {
                                    // Agregar archivo al ZIP con un nombre relativo dentro de la carpeta de la subdivisión
                                    $zip->addFile($documentPath, "{$subdivision->name}/{$document->title}");
                                } else {
                                    // Registrar un mensaje de advertencia si el archivo no existe
                                    Log::warning("El archivo '{$document->title}' no existe en la ubicación especificada.");
                                }
                            }
                        } else {
                            // Añadir un archivo de texto vacío si no hay documentos en la subdivisión
                            $zip->addFromString("{$subdivision->name}/empty.txt", 'No hay documentos disponibles para esta subdivisión.');
                        }
                    }
                } else {
                    // Si no hay subdivisiones, añadir un archivo de texto vacío
                    $zip->addFromString('empty.txt', 'No hay subdivisiones disponibles en esta sección.');
                }

                // Cerrar el archivo ZIP
                $zip->close();
                ob_end_clean();
                return response()->download($zipFilePath)->deleteFileAfterSend(true);
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

    private function areTheSameDocItems($docItem, $data)
    {
        if ($docItem->subdivision_id !== (int)$data['subdivision_id']) return false;
        if ($docItem->e_employee_id !== (int)$data['e_employee_id']) return false;
        if ($docItem->employee_id !== (int)$data['employee_id']) return false;
        return true;
    }

    private function getDocumentRegister($docItem)
    {
        return ContractorDocumentRegister::where('subdivision_id', $docItem->subdivision_id)
            ->where('employee_id', $docItem->employee_id)
            ->where('e_employee_id', $docItem->e_employee_id)
            ->first();
    }

    private function file_store($data)
    {
        $document = $data['document'];
        $name = $this->getFileName($data, $document);
        $document->move(public_path('documents/documents/'), $name);
        return $name;
    }

    private function file_delete($docItem)
    {
        $fileName = $docItem->title;
        $filePath = "documents/documents/$fileName";
        $path = public_path($filePath);
        if (file_exists($path) && is_file($path)) {
            unlink($path);
        }
    }

    private function getFileName($data, $document)
    {
        $employee_name = $data['employee_id'] ? ContractorEmployee::where('id', $data['employee_id'])
            ->selectRaw("CONCAT(name, ' ', lastname) as full_name")
            ->first() : ContractorExternalEmployee::where('id', $data['e_employee_id'])
            ->selectRaw("CONCAT(name, ' ', lastname) as full_name")
            ->first();
        $name = ContractorSubdivision::find($data['subdivision_id'])->name . ' - ' . $employee_name->full_name . '.' . $document->getClientOriginalExtension();
        return $name;
    }
}
