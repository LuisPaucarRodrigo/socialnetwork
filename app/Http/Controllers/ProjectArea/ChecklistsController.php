<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChecklistRequest\ChecklistCarRequest;
use App\Http\Requests\ChecklistRequest\ChecklistDailytoolkitRequest;
use App\Http\Requests\ChecklistRequest\ChecklistEppRequest;
use App\Http\Requests\ChecklistRequest\ChecklistToolkitRequest;
use App\Models\ChecklistCar;
use App\Models\ChecklistDailytoolkit;
use App\Models\ChecklistEpp;
use App\Models\ChecklistToolkit;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChecklistsController extends Controller
{

    public function car_index()
    {
        $checklistscar = ChecklistCar::all();
        return response()->json($checklistscar, 200);
    }
    public function car_store(ChecklistCarRequest $request)
    {
        $data = $request->validated();
        try {
            $data['front'] = $this->storeBase64Image($data['front'], 'checklistcar', 'front');
            $data['leftSide'] = $this->storeBase64Image($data['leftSide'], 'checklistcar', 'leftSide');
            $data['rightSide'] = $this->storeBase64Image($data['rightSide'], 'checklistcar', 'rightSide');
            $data['interior'] = $this->storeBase64Image($data['interior'], 'checklistcar', 'interior');
            $data['rearLeftTire'] = $this->storeBase64Image($data['rearLeftTire'], 'checklistcar', 'rearLeftTire');
            $data['rearRightTire'] = $this->storeBase64Image($data['rearRightTire'], 'checklistcar', 'rearRightTire');
            $data['frontRightTire'] = $this->storeBase64Image($data['frontRightTire'], 'checklistcar', 'frontRightTire');
            $data['frontLeftTire'] = $this->storeBase64Image($data['frontLeftTire'], 'checklistcar', 'frontLeftTire');
            $data['user_id'] = Auth::user()->id;

            ChecklistCar::create($data);
            return response()->json([], 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function toolkit_store(ChecklistToolkitRequest $request)
    {
        $data = $request->validated();
        try {
            if ($data['badTools']) {
                $data['badTools'] = $this->storeBase64Image($data['badTools'], 'checklisttoolkit', 'badTools');
            }
            $data['user_id'] = Auth::user()->id;
            ChecklistToolkit::create($data);
            return response()->json([], 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al procesar la solicitud',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function dailytoolkit_store(ChecklistDailytoolkitRequest $request)
    {
        $data = $request->validated();
        try {
            $data['user_id'] = Auth::user()->id;
            ChecklistDailytoolkit::create($data);
            return response()->json([], 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al procesar la solicitud',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function epp_store(ChecklistEppRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;
        ChecklistEpp::create($data);
        return response()->json([], 201);
    }

    private function storeBase64Image($photo, $path, $name)
    {
        try {
            $image = str_replace('data:image/png;base64,', '', $photo);
            $image = str_replace(' ', '+', $image);
            $imageContent = base64_decode($image);
            $imagename = time() . $name . '.png';
            file_put_contents(public_path('image/checklist/') . $path . "/" . $imagename, $imageContent);
            return $imagename;
        } catch (Exception $e) {
            abort(500, 'something went wrong');
        }
    }
}
