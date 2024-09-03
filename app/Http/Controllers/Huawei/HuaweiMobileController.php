<?php

namespace App\Http\Controllers\Huawei;

use App\Http\Controllers\Controller;
use App\Models\HuaweiCode;
use App\Models\HuaweiProject;
use App\Models\HuaweiProjectCode;
use App\Models\HuaweiProjectImage;
use App\Models\HuaweiProjectStage;
use App\Models\HuaweiTitle;
use App\Models\HuaweiTitleCode;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HuaweiMobileController extends Controller
{
    //codes
    public function getCodes ()
    {
        return Inertia::render('Huawei/HuaweiCodes', [
            'codes' => HuaweiCode::paginate(15)
        ]);
    }

    public function storeCode (Request $request)
    {
        $data = $request->validate([
            'code' => 'required',
            'description' => 'nullable'
        ]);

        HuaweiCode::create($data);

        return redirect()->back();
    }

    public function updateCode (HuaweiCode $huawei_code, Request $request)
    {
        $data = $request->validate([
            'code' => 'required',
            'description' => 'nullable'
        ]);

        $huawei_code->update($data);

        return redirect()->back();
    }

    public function deleteCode (HuaweiCode $huawei_code)
    {
        $huawei_code->delete();

        return redirect()->back();
    }

    //titles
    public function getTitles ()
    {
        return Inertia::render('Huawei/HuaweiTitles', [
            'titles' => HuaweiTitle::with('huawei_codes')->paginate(15),
            'codes' => HuaweiCode::all()
        ]);
    }

    public function storeTitle (Request $request)
    {
        $request->validate([
            'name' => 'required',
            'codes' => 'required|array'
        ]);

        $huawei_title = HuaweiTitle::create([
            'name' => $request->name
        ]);

        $huawei_title->huawei_codes()->attach($request->codes);

        return redirect()->back();
    }

    public function updateTitle (HuaweiTitle $huawei_title, Request $request)
    {
        $request->validate([
            'name' => 'required',
            'codes' => 'required|array'
        ]);

        $huawei_title->update([
            'name' => $request->name
        ]);

        $huawei_title->huawei_codes()->sync($request->codes);

        return redirect()->back();
    }

    public function deleteTitle (HuaweiTitle $huawei_title)
    {
        $huawei_title->delete();

        return redirect()->back();
    }

    //stages

    public function getProjectStages (HuaweiProject $huawei_project)
    {
        return Inertia::render('Huawei/ProjectStages', [
            'stages' => HuaweiProjectStage::where('huawei_project_id', $huawei_project->id)->get(),
            'titles' => HuaweiTitle::with('huawei_codes')->get(),
            'huawei_project' => $huawei_project
        ]);
    }

    public function filterProjectStages(HuaweiProject $huawei_project, HuaweiProjectStage $stage)
    {
        $codes = HuaweiProjectCode::where('huawei_project_stage_id', $stage->id)
                    ->with(['huawei_code', 'huawei_project_images'])
                    ->get()
                    ->map(function ($code) {
                        // Iteramos sobre cada código para modificar las imágenes
                        $code->huawei_project_images->transform(function ($image) {
                            $image->image = asset('documents/huawei/photoreports/' . $image->image);
                            return $image;
                        });
                        return $code;
                    });

        return Inertia::render('Huawei/ProjectStages', [
            'stages' => HuaweiProjectStage::where('huawei_project_id', $huawei_project->id)->get(),
            'codes' => $codes,
            'titles' => HuaweiTitle::with('huawei_codes')->get(),
            'huawei_project' => $huawei_project,
            'selectedStage' => $stage->id
        ]);
    }

    public function viewImage (HuaweiProjectImage $image)
    {
        $fileName = $image->image;
        $filePath = '/documents/huawei/photoreports/' . $fileName;
        $path = public_path($filePath);
        if (file_exists($path)) {
            ob_end_clean();
            return response()->file($path);
        }
        abort(404, 'Documento no encontrado');
    }

    public function downloadImage (HuaweiProjectImage $image)
    {
        $fileName = $image->image;
        $filePath = '/documents/huawei/photoreports/' . $fileName;
        $path = public_path($filePath);
        if (file_exists($path)) {
            ob_end_clean();
            return response()->download($path, $fileName);
        }
        abort(404, 'Documento no encontrado');
    }

    public function deleteImage (HuaweiProjectImage $image)
    {
        $fileName = $image->image;
        $filePath = "documents/huawei/photoreports/$fileName";
        $path = public_path($filePath);
        if (file_exists($path)) {
            unlink($path);
            $image->delete();
        } else {
            dd("El archivo no existe en la ruta: $filePath");
        }
        return redirect()->back();
    }

    public function approveOrReject (HuaweiProjectImage $image, Request $request)
    {
        if ($image->state !== null){
            abort(403, 'Acción no permitida.');
        }

        $data = $request->validate([
            'state' => 'required',
            'observation' => [
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->state == false && empty($value)) {
                        $fail('El campo observación es obligatorio.');
                    }
                },
            ]
        ]);

        $image->update($data);

        return redirect()->back();
    }

    public function approveCode (HuaweiProjectCode $code)
    {
        $images = HuaweiProjectImage::where('huawei_project_code_id', $code->id)
            ->where('state', '!=', true)
            ->orWhereNull('state')
            ->get();

        foreach ($images as $item){
            $fileName = $item->image;
            $filePath = "documents/huawei/photoreports/$fileName";
            $path = public_path($filePath);
            if (file_exists($path)) {
                unlink($path);
                $item->delete();
            } else {
                dd("El archivo no existe en la ruta: $filePath");
            }
        }

        $code->update([
            'status' => 1
        ]);

        return redirect()->back();
    }

    public function addStage (HuaweiProject $huawei_project, Request $request)
    {
        if (!$huawei_project->status){
            abort(403, 'Acción no permitida.');
        }

        $request->validate([
            'description' => 'required',
            'title' => 'required'
        ]);

        $title = HuaweiTitle::find($request->title);
        $codes = $title->huawei_codes;

        $huawei_project_stage = HuaweiProjectStage::create([
            'description' => $request->description,
            'huawei_project_id' => $huawei_project->id
        ]);

        foreach ($codes as $item){
            HuaweiProjectCode::create([
                'huawei_project_stage_id' => $huawei_project_stage->id,
                'huawei_code_id' => $item->id
            ]);
        }

        return redirect()->back();
    }
}
