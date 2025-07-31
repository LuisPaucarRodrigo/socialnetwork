<?php

namespace App\Http\Controllers\Contractors;

use App\Constants\DocumentConstants;
use App\Http\Controllers\Controller;
use App\Models\Contractors\ContractorGrupalDocument;
use Inertia\Inertia;

class ContractorGrupalDocumentController extends Controller
{
    public function index()
    {
        $grupal_documents = ContractorGrupalDocument::orderBy('date', 'desc')->paginate(20);
        return Inertia::render('HumanResource/DocumentSpreedSheet/GrupalDocuments', [
            'grupal_documents' => $grupal_documents,
            'types' => DocumentConstants::grupalDouments()
        ]);
    }

    public function download($gd_id)
    {
        $grupal_document = ContractorGrupalDocument::findOrFail($gd_id);
        $fileName = $grupal_document->archive;
        $filePath = "documents/documents/grupal/$fileName";
        $path = public_path($filePath);
        if (file_exists($path)) {
            ob_end_clean();
            return response()->download($path, $fileName);
        }
        abort(404, 'Documento no encontrado');
    }
}
