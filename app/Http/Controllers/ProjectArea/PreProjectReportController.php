<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use App\Models\Customervisit;
use App\Models\Imagespreproject;
use Illuminate\Http\Request;

class PreProjectReportController extends Controller
{
    public function index()
    {
        $customers = Customervisit::all();
        return response()->json($customers);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric',
            'description' => 'required|string',
            'image' => 'required|mimes:jpg,jpeg'
        ]);
        $imageUrl = null;
        try {
            if ($request->hasFile('image')) {
                $customervisit = $request->file('image');
                $imageUrl = time() . '._' . $customervisit->getClientOriginalName();
                $customervisit->move(public_path('/image/customervisit/'), $imageUrl);
            }
            Imagespreproject::create([
                'description' => $request->description,
                'image' => $imageUrl,
                'customervisit_id' => $request->id
            ]);
            return response()->json([
                'response' => 1
            ]);
        } catch (\Exception $e) {
            if ($imageUrl && file_exists(public_path('/image/customervisit/' . $imageUrl))) {
                unlink(public_path('/image/customervisit/' . $imageUrl));
            }
            return response()->json([
                'response' => 0
            ]);
        }
    }
}
