<?php

namespace App\Http\Controllers\ProjectArea;

use App\Helpers\FileHandler;
use App\Http\Controllers\Controller;
use App\Models\ChecklistCar;
use App\Models\ChecklistDailytoolkit;
use App\Models\ChecklistEpp;
use App\Models\ChecklistToolkit;
use Inertia\Inertia;

class ChecklistsController extends Controller
{

    public function index()
    {
        return Inertia::render('ProjectArea/Checklist/Index');
    }

    public function car_index()
    {
        $checklistcar = ChecklistCar::with('user')->orderBy('created_at', 'desc')->paginate(20);
        return Inertia::render(
            'ProjectArea/Checklist/ChecklistCar',
            ['checklists' => $checklistcar]
        );
    }

    public function car_photo($id, $photoProp)
    {
        $checklistcar = ChecklistCar::find($id);
        return FileHandler::showFile('/image/checklist/checklistcar/', $checklistcar->$photoProp);
    }

    public function dailytoolkit_index()
    {
        $checklistdailytoolkit = ChecklistDailytoolkit::with('user')->orderBy('created_at', 'desc')->paginate(20);
        return Inertia::render(
            'ProjectArea/Checklist/ChecklistDailytoolkit',
            ['checklists' => $checklistdailytoolkit]
        );
    }
    public function epp_index()
    {
        $checklistepp = ChecklistEpp::with('user')->orderBy('created_at', 'desc')->paginate(20);
        return Inertia::render(
            'ProjectArea/Checklist/ChecklistEpp',
            ['checklists' => $checklistepp]
        );
    }

    public function toolkit_index()
    {
        $checklisttoolkit = ChecklistToolkit::with('user')->orderBy('created_at', 'desc')->paginate(20);
        return Inertia::render(
            'ProjectArea/Checklist/ChecklistToolkit',
            ['checklists' => $checklisttoolkit]
        );
    }

    public function toolkit_photo($id, $photoProp)
    {
        $checklistcar = ChecklistToolkit::find($id);
        return FileHandler::showFile('/image/checklist/checklisttoolkit/', $checklistcar->$photoProp);
    }

    


    public function car_destroy($id)
    {
        $checklistCar = ChecklistCar::findOrFail($id);
        $checklistCar->maintenanceTools && $this->file_delete($checklistCar->maintenanceTools, 'image/checklist/checklistcar');
        $checklistCar->preventionTools && $this->file_delete($checklistCar->preventionTools, 'image/checklist/checklistcar');
        $checklistCar->imageSpareTire && $this->file_delete($checklistCar->imageSpareTire, 'image/checklist/checklistcar');

        $checklistCar->front && $this->file_delete($checklistCar->front, 'image/checklist/checklistcar');
        $checklistCar->leftSide && $this->file_delete($checklistCar->leftSide, 'image/checklist/checklistcar');
        $checklistCar->rightSide && $this->file_delete($checklistCar->rightSide, 'image/checklist/checklistcar');
        $checklistCar->interior && $this->file_delete($checklistCar->interior, 'image/checklist/checklistcar');
        $checklistCar->rearLeftTire && $this->file_delete($checklistCar->rearLeftTire, 'image/checklist/checklistcar');
        $checklistCar->rearRightTire && $this->file_delete($checklistCar->rearRightTire, 'image/checklist/checklistcar');
        $checklistCar->frontRightTire && $this->file_delete($checklistCar->frontRightTire, 'image/checklist/checklistcar');
        $checklistCar->frontLeftTire && $this->file_delete($checklistCar->frontLeftTire, 'image/checklist/checklistcar');

        $checklistCar->back && $this->file_delete($checklistCar->back, 'image/checklist/checklistcar');
        $checklistCar->dashboard && $this->file_delete($checklistCar->dashboard, 'image/checklist/checklistcar');
        $checklistCar->rearSeat && $this->file_delete($checklistCar->rearSeat, 'image/checklist/checklistcar');

        $checklistCar->delete();
        return redirect()->back();
    }

    public function toolkit_destroy($id)
    {
        $checklistToolkit = ChecklistToolkit::findOrFail($id);
        $checklistToolkit->badTools && $this->file_delete($checklistToolkit->badTools, 'image/checklist/checklisttoolkit');
        $checklistToolkit->goodTools && $this->file_delete($checklistToolkit->goodTools, 'image/checklist/checklisttoolkit');
        $checklistToolkit->delete();
        return redirect()->back();
    }


    public function dailytoolkit_destroy($cdt_id)
    {
        $checklistdailytoolkit = ChecklistDailytoolkit::find($cdt_id);
        $checklistdailytoolkit->delete();
        return redirect()->back();
    }

    public function epp_destroy($epp_id)
    {
        $checklistepp = ChecklistEpp::find($epp_id);
        $checklistepp->delete();
        return redirect()->back();
    }

    public function file_delete($filename, $path)
    {
        $file_path = $path . $filename;
        $path = public_path($file_path);
        if (file_exists($path))
            unlink($path);
    }    
}
