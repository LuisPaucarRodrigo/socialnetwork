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
use Inertia\Inertia;
use Illuminate\Http\Request;

class ChecklistsController extends Controller
{

    public function index() {
        return Inertia::render('ProjectArea/Checklist/Index');
    }

    public function car_index(){
        $checklistscar = ChecklistCar::all();
        return response()->json($checklistscar, 200);
    }
    public function car_store (ChecklistCarRequest $request){
        $data = $request->validated();

        $data['front'] = $this->storeBase64Image($data['front'], 'checklistcar');
        $data['leftSide'] = $this->storeBase64Image($data['leftSide'], 'checklistcar');
        $data['rightSide'] = $this->storeBase64Image($data['rightSide'], 'checklistcar');
        $data['interior'] = $this->storeBase64Image($data['interior'], 'checklistcar');
        $data['rearLeftTire'] = $this->storeBase64Image($data['rearLeftTire'], 'checklistcar');
        $data['rearRightTire'] = $this->storeBase64Image($data['rearRightTire'], 'checklistcar');
        $data['frontRightTire'] = $this->storeBase64Image($data['frontRightTire'], 'checklistcar');
        $data['frontLeftTire'] = $this->storeBase64Image($data['frontLeftTire'], 'checklistcar');
        $data['user_id'] = Auth::user()->id;

        ChecklistCar::create($data);
        return response()->json([], 201);
    }



    public function toolkit_store (ChecklistToolkitRequest $request){
        $data = $request->validated();
        if ($data['badTools']){
            $data['badTools'] = $this->storeBase64Image($data['badTools'], 'checklisttoolkit');
        }
        $data['user_id'] = Auth::user()->id;
        ChecklistToolkit::create($data);
        return response()->json([], 201);
    }

    public function dailytoolkit_store (ChecklistDailytoolkitRequest $request){
        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;
        ChecklistDailytoolkit::create($data);
        return response()->json([], 201);
    }

    public function epp_store (ChecklistEppRequest $request){
        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;
        ChecklistEpp::create($data);
        return response()->json([], 201);
    }


    private function storeBase64Image($photo, $path){
        try{
            $image = str_replace('data:image/png;base64,', '', $photo);
            $image = str_replace(' ', '+', $image);
            $imageContent = base64_decode($image);
            $imagename = time() . '.png';
            file_put_contents(public_path('image/checklist/'.$path.'/') . $imagename, $imageContent);
            return $imagename;
        }catch(Exception $e){
            abort(500, 'something went wrong');
        }
    }

   
}
