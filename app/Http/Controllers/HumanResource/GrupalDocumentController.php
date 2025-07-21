<?php

namespace App\Http\Controllers\HumanResource;

use App\Constants\DocumentConstants;
use App\Helpers\FileHandler;
use App\Http\Controllers\Controller;
use App\Models\GrupalDocument;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GrupalDocumentController extends Controller
{
    public function index()
    {
        $grupal_documents = GrupalDocument::orderBy('date', 'desc')->paginate(20);
        return Inertia::render('HumanResource/DocumentSpreedSheet/GrupalDocuments', [
            'grupal_documents' => $grupal_documents,
            'types' => DocumentConstants::grupalDouments()
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required',
            'archive' => 'required|mimes:pdf,xlsx,xls',
            'date' => 'required',
            'observation' => 'nullable',
        ]);
        if ($request->hasFile('archive')) {
            $data['archive'] = $this->file_store($request->file('archive'), 'documents/documents/grupal/');
        }
        $item = GrupalDocument::create($data);
        return response()->json($item, 200);
    }

    public function update(Request $request, $gd_id)
    {
        $data = $request->validate([
            'type' => 'required',
            'archive' => [
                'nullable',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->hasFile($attribute)) {
                        $file = $request->file($attribute);
                        $extension = $file->getClientOriginalExtension();
                        if (!in_array($extension, ['pdf', 'xls', 'xlsx'])) {
                            $fail('El archivo debe ser un PDF o un archivo de Excel (xls, xlsx).');
                        }
                    }
                },
            ],
            'date' => 'required',
            'observation' => 'nullable',
        ]);
        $grupal_document = GrupalDocument::findOrFail($gd_id);
        if ($request->hasFile('archive')) {
            $filename = $grupal_document->archive;
            if ($filename) {
                $this->file_delete($filename, 'documents/documents/grupal/');
            }
            $data['archive'] = $this->file_store($request->file('archive'), 'documents/documents/grupal/');
        }
        $grupal_document->update($data);
        return response()->json($grupal_document, 200);
    }

    public function destroy($gd_id)
    {
        $grupal_document = GrupalDocument::findOrFail($gd_id);
        $grupal_document->archive && $this->file_delete($grupal_document->archive, 'documents/documents/grupal/');
        $grupal_document->delete();
        return response()->json(['msg' => 'success'], 200);
    }

    public function download($gd_id)
    {
        $grupal_document = GrupalDocument::findOrFail($gd_id);
        return FileHandler::downloadFile('documents/documents/grupal/', $grupal_document->archive);
    }



    // return response()->download($zipFilePath)->deleteFileAfterSend(true);


    public function file_store($file, $path)
    {
        $name = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path($path), $name);
        return $name;
    }

    public function file_delete($filename, $path)
    {
        $file_path = $path . $filename;
        $path = public_path($file_path);
        if (file_exists($path))
            unlink($path);
    }
}
