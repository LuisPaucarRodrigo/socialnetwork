<?php

namespace App\Http\Controllers\HumanResource;

use App\Http\Controllers\Controller;
use App\Http\Requests\HumanResource\DocumentCreateRequest;
use App\Http\Requests\HumanResource\DocumentUpdateRequest;
use App\Models\Document;
use App\Models\DocumentRegister;
use App\Models\DocumentSection;
use App\Models\Employee;
use App\Models\ExternalEmployee;
use App\Models\Subdivision;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use ZipArchive;
use Carbon\Carbon;
use Exception;

class DocumentController extends Controller
{
    public function showSections()
    {
        $sections = DocumentSection::all();
        return Inertia::render('HumanResource/Documents/Sections', [
            'sections' => $sections->load('subdivisions')
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
        return response()->json(data: $section);
    }

    public function updateSection(DocumentSection $section, Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string'
        ]);

        $section->update($data);

        // Cargar la relación subdivisions
        $section->load('subdivisions');

        return response()->json($section);
    }


    public function destroySection(DocumentSection $section)
    {
        $section->delete();
        return response()->json([
            'message' => 'success',
        ]);
    }

    //Subdivisions
    public function storeSubdivision(DocumentSection $section, Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $sub = Subdivision::create([
            'name' => $request->name,
            'section_id' => $section->id,
        ]);
        return response()->json(data: $sub);
    }

    public function updateSubdivision($section, Subdivision $subdivision, Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string'
        ]);

        $subdivision->update($data);

        return response()->json([
            'id' => $subdivision->id,
            'name' => $subdivision->name,
            'section_id' => $subdivision->section_id,
        ]);
    }


    public function destroySubdivision($section, Subdivision $subdivision)
    {
        $subdivision->delete();
        return response()->json([
            'message' => 'success',
        ]);
    }

    public function dragandrop(Request $request)
    {
        $data = $request->validate([
            'subdivision_id' => 'required|integer',
            'section_id' => 'required|integer',
        ]);

        $subdivision = Subdivision::find($data['subdivision_id']);
        $subdivision->section_id = $data['section_id'];
        $subdivision->save();
        $subdivision->load('section');
        return response()->json([
            'id' => $subdivision->id,
            'name' => $subdivision->name,
            'section_id' => $subdivision->section_id,
        ]);
    }
    //Documents

    public function index()
    {
        return Inertia::render('HumanResource/Documents/Document', [
            'sections' => DocumentSection::with('subdivisions')->get(),
            'subdivisions' => Subdivision::all(),
            'employees' => Employee::whereHas('contract', function ($query) {
                $query->where('state', 'Active');
            })
                ->orderBy('name')
                ->get(),
            'e_employees' => ExternalEmployee::orderBy('name')->get(),
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

        $query = Document::with('subdivision.section', 'employee')
            ->where(function ($q) use ($data) {
                if (!empty($data['employees'])) {
                    $q->whereIn('employee_id', $data['employees']);
                }
                if (!empty($data['external_employees'])) {
                    $q->whereIn('e_employee_id', $data['external_employees']);
                }
                if (!empty($data['subdivisions'])) {
                    $q->whereIn('subdivision_id', $data['subdivisions']);
                }
            });
        $documents = $query->get();
        return response()->json($documents);
    }


    // public function filterDocument(Request $request)
    // {
    //     $searchTerm = strtolower($request->search);
    //     $query = Document::with('subdivision.section', 'employee');

    //     if (!empty($searchTerm)) {
    //         $query->where(function ($q) use ($searchTerm) {
    //             $q->whereRaw('LOWER(title) LIKE ?', ["%{$searchTerm}%"])
    //                 ->orWhereHas('employee', function ($q2) use ($searchTerm) {
    //                     $q2->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"])
    //                         ->orWhereRaw('LOWER(lastname) LIKE ?', ["%{$searchTerm}%"]);
    //                 });
    //         });
    //     }

    //     // Filtro por subdivisiones si vienen
    //     if (!empty($request->subdivisions)) {
    //         $query->whereIn('subdivision_id', $request->subdivisions);
    //     }

    //     return response()->json($query->get());
    // }



    public function create(DocumentCreateRequest $request)
    {
        $data = $request->validated();
        try {
            if ($request->hasFile('document')) {
                $document = $request->file('document');
                $employee_name = $request->employee_id ? Employee::where('id', $data['employee_id'])
                    ->selectRaw("CONCAT(name, ' ', lastname) as full_name")
                    ->first() : ExternalEmployee::where('id', $data['e_employee_id'])
                        ->selectRaw("CONCAT(name, ' ', lastname) as full_name")
                        ->first();
                $data['title'] = Subdivision::find($data['subdivision_id'])->name . ' - ' . $employee_name->full_name . '.' . $document->getClientOriginalExtension();
                $document->move(public_path('documents/documents/'), $data['title']);
            }
            $docItem = Document::create($data);
            $docReg = $docItem->employee_id
                ? DocumentRegister::where('subdivision_id', $docItem->subdivision_id)
                    ->where('employee_id', $docItem->employee_id)
                    ->first()
                : ($docItem->e_employee_id
                    ? DocumentRegister::where('subdivision_id', $docItem->subdivision_id)
                        ->where('e_employee_id', $docItem->e_employee_id)
                        ->first()
                    : null
                );

            if ($docReg) {
                $dataDocReg['document_id'] = $docItem->id;
                if ($docReg->exp_date === null) {
                    $dataDocReg['exp_date'] = $docItem->exp_date;
                }
                if (isset($data['exp_date']) && $docReg->exp_date) {
                    $newExpDate = Carbon::parse($data['exp_date']);
                    $pastExpDate = Carbon::parse($docReg->exp_date);
                    if ($newExpDate >= $pastExpDate) {
                        $dataDocReg['exp_date'] = $docItem->exp_date;
                    }
                }
                $docReg->update($dataDocReg);
            } else {
                DocumentRegister::create([
                    'subdivision_id' => $docItem->subdivision_id,
                    'document_id' => $docItem->id,
                    'employee_id' => $docItem->employee_id,
                    'e_employee_id' => $docItem->e_employee_id,
                    'exp_date' => $docItem->exp_date,
                    'state' => 'Completado',
                ]);
            }

            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Ocurrió un error: ' . $e->getMessage());

        }
    }

    public function update(DocumentUpdateRequest $request, $id)
    {
        $data = $request->validated();
        $docItem = Document::find($id);
        $fileName = $docItem->title;
        $filePath = "documents/documents/$fileName";
        $path = public_path($filePath);
        if (file_exists($path)) {
            unlink($path);
        }
        $documentName = null;
        if ($request->hasFile('document')) {
            $document = $request->file('document');
            $employee_name = $request->employee_id ? Employee::where('id', $data['employee_id'])
                ->selectRaw("CONCAT(name, ' ', lastname) as full_name")
                ->first() : ExternalEmployee::where('id', $data['e_employee_id'])
                    ->selectRaw("CONCAT(name, ' ', lastname) as full_name")
                    ->first();
            $data['title'] = Subdivision::find($data['subdivision_id'])->name . ' - ' . $employee_name->full_name . '.' . $document->getClientOriginalExtension();
            $document->move(public_path('documents/documents/'), $data['title']);
        }


        $docItem->update($data);
        $docReg = $docItem->employee_id
            ? DocumentRegister::where('subdivision_id', $docItem->subdivision_id)
                ->where('employee_id', $docItem->employee_id)
                ->first()
            : ($docItem->e_employee_id
                ? DocumentRegister::where('subdivision_id', $docItem->subdivision_id)
                    ->where('e_employee_id', $docItem->e_employee_id)
                    ->first()
                : null
            );
        if ($docReg) {
            $dataDocReg['document_id'] = $docItem->id;
            $dataDocReg['employee_id'] = $docItem->employee_id;
            $dataDocReg['e_employee_id'] = $docItem->e_employee_id;
            if ($docReg->exp_date === null) {
                $dataDocReg['exp_date'] = $docItem->exp_date;
            }
            if (isset($data['exp_date']) && $docReg->exp_date) {
                $newExpDate = Carbon::parse($data['exp_date']);
                $pastExpDate = Carbon::parse($docReg->exp_date);
                if ($newExpDate >= $pastExpDate) {
                    $dataDocReg['exp_date'] = $docItem->exp_date;
                }
            }
            $docReg->update($dataDocReg);
        } else {
            DocumentRegister::create([
                'subdivision_id' => $docItem->subdivision_id,
                'document_id' => $docItem->id,
                'employee_id' => $docItem->employee_id,
                'e_employee_id' => $docItem->e_employee_id,
                'exp_date' => $docItem->exp_date,
                'state' => 'Completado',
            ]);
        }

        return redirect()->back();
    }



    public function updateAllDocuments()
    {
        // $documents = Document::all();

        // foreach ($documents as $document) {
        //     $subdivision = Subdivision::find($document->subdivision_id);
        //     $subdivision_name = $subdivision->name;

        //     $employee_name = null;

        //     if (!empty($document->employee_id)) {
        //         $employee = Employee::where('id', $document->employee_id)
        //             ->selectRaw("CONCAT(name, ' ', lastname) as full_name")
        //             ->first();
        //         $employee_name = $employee->full_name ?? 'SIN NOMBRE';
        //     } elseif (!empty($document->e_employee_id)) {
        //         $external_employee = ExternalEmployee::where('id', $document->e_employee_id)
        //             ->selectRaw("CONCAT(name, ' ', lastname) as full_name")
        //             ->first();
        //         $employee_name = $external_employee->full_name ?? 'SIN NOMBRE';
        //     } else {
        //         $employee_name = 'SIN NOMBRE';
        //     }

        //     $old_file_path = public_path('documents/documents/' . $document->title);
        //     $file_extension = pathinfo($old_file_path, PATHINFO_EXTENSION) ?? 'pdf';

        //     $new_title = strtoupper($subdivision_name . ' - ' . $employee_name . '.' . $file_extension);
        //     $new_file_path = public_path('documents/documents/' . $new_title);

        //     if (File::exists($old_file_path)) {
        //         File::move($old_file_path, $new_file_path);
        //     }

        //     $document->update(['title' => $new_title]);
        // }

        return response()->json(['message' => 'Todos los documentos han sido actualizados correctamente.']);
    }









    public function destroy(Document $id)
    {
        $fileName = $id->title;
        $filePath = "documents/documents/$fileName";
        $path = public_path($filePath);
        if (file_exists($path)) {
            unlink($path);
        }

        $docReg = $id->employee_id
            ? DocumentRegister::where('subdivision_id', $id->subdivision_id)->where('employee_id', $id->employee_id)->first()
            : ($id->e_employee_id
                ? DocumentRegister::where('subdivision_id', $id->subdivision_id)->where('e_employee_id', $id->e_employee_id)->first()
                : null
            );
        if ($docReg) {
            $docReg->delete();
        }
        $id->delete();


        // } else {
        //     dd("El archivo no existe en la ruta: $filePath");
        // }
        return to_route('documents.index');
    }

    public function downloadDocument(Document $document)
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

    public function showDocument(Document $document)
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

        $documents = Document::with('subdivision.section')->whereIn('id', $request->ids)->get();

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
            $subdivision = Subdivision::with('documents')->findOrFail($subdivisionId);

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


    // public function deleteZip($section, $subdivisionId)
    // {
    //     // Construir la ruta completa al archivo ZIP
    //     $path = public_path('/documents/documents/subdivision_' . $subdivisionId . '_documents.zip');

    //     try {
    //         // Verificar si el archivo existe
    //         if (file_exists($path)) {
    //             // Intentar eliminar el archivo
    //             unlink($path);
    //         } else {
    //             // Si el archivo no existe, registrar un mensaje de advertencia
    //             Log::warning("El archivo ZIP no existe: {$path}");
    //             return response()->json(['status' => 'error', 'message' => 'El archivo ZIP no existe.'], 404);
    //         }
    //     } catch (\Exception $e) {
    //         // Capturar cualquier excepción y registrar un mensaje de error
    //         Log::error("Error al intentar eliminar el archivo ZIP: " . $e->getMessage());
    //         return response()->json(['status' => 'error', 'message' => 'Ocurrió un error al intentar eliminar el archivo ZIP.'], 500);
    //     }
    // }

    public function downloadSectionDocumentsZip($sectionId)
    {
        try {
            $section = DocumentSection::with('subdivisions.documents')->findOrFail($sectionId);

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


    // public function deleteSectionZip($sectionId)
    // {
    //     // Construir la ruta completa al archivo ZIP
    //     $zipFilePath = public_path("/documents/documents/section_{$sectionId}_documents.zip");

    //     try {
    //         // Verificar si el archivo existe
    //         if (file_exists($zipFilePath)) {
    //             // Intentar eliminar el archivo
    //             unlink($zipFilePath);
    //             return response()->json(['status' => 'success', 'message' => 'Archivo ZIP eliminado correctamente.']);
    //         } else {
    //             // Si el archivo no existe, registrar un mensaje de advertencia
    //             Log::warning("El archivo ZIP no existe: {$zipFilePath}");
    //             return response()->json(['status' => 'error', 'message' => 'El archivo ZIP no existe.'], 404);
    //         }
    //     } catch (\Exception $e) {
    //         // Capturar cualquier excepción y registrar un mensaje de error
    //         Log::error("Error al intentar eliminar el archivo ZIP: " . $e->getMessage());
    //         return response()->json(['status' => 'error', 'message' => 'Ocurrió un error al intentar eliminar el archivo ZIP.'], 500);
    //     }
    // }
}

