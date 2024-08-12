<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChecklistRequest\ChecklistCarRequest;
use App\Models\ChecklistCar;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ChecklistsController extends Controller
{
    public function cars_store (ChecklistCarRequest $request){
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

    public function toolkit_store (ChecklistCarRequest $request){

    }
    public function dailytoolkit_store (ChecklistCarRequest $request){

    }
    public function epp_store (ChecklistCarRequest $request){

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
