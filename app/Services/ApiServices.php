<?php

namespace App\Services;

use App\Models\PreprojectTitle;
use App\Models\User;
use Exception;

class ApiServices 
{
    public function findUser($id) : Object{
        $user = User::select('id', 'name', 'dni', 'email')->find($id);
        return $user ;
    }

    public function storeBase64Image($photo, $path, $name) :String
    {
        try {
            $image = str_replace('data:image/png;base64,', '', $photo);
            $image = str_replace('', '+', $image);
            $imageContent = base64_decode($image);
            $imagename = time() . $name . '.png';
            file_put_contents(public_path($path) . "/" . $imagename, $imageContent);
            return $imagename;
        } catch (Exception $e) {
            abort(500, 'something went wrong');
        }
    }

    public function preprojectTitle($id)
    {
        $preprojectTitle = PreprojectTitle::with(['preprojectCodes.code' => function ($query) {
            $query->select('id', 'code');
        }, 'preprojectCodes' => function ($query) {
            $query->select('id', 'preproject_title_id', 'code_id', 'status');
        }])
            ->whereNotNull('state')->where('preproject_id', $id)
            ->select('id', 'type');
        return $preprojectTitle;
    }
    
}