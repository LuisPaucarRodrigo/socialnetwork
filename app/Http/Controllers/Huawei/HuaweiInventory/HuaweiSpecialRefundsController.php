<?php

namespace App\Http\Controllers\Huawei\HuaweiInventory;

use App\Http\Controllers\Controller;
use App\Models\HuaweiSpecialRefund;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HuaweiSpecialRefundsController extends Controller
{

    public function getSpecialRefunds()
    {
        return Inertia::render('Huawei/SpecialRefunds', [
            'refunds' => HuaweiSpecialRefund::orderBy('created_at', 'desc')->paginate(15)
        ]);
    }

    public function searchSpecialRefunds($request)
    {
        $searchTerm = strtolower($request);

        $refunds = HuaweiSpecialRefund::query()
            ->whereRaw('LOWER(description) LIKE ?', ["%{$searchTerm}%"])
            ->orWhereRaw('LOWER(diu) LIKE ?', ["%{$searchTerm}%"])
            ->orWhereRaw('LOWER(observation) LIKE ?', ["%{$searchTerm}%"])
            ->orWhereRaw('CAST(quantity AS CHAR) LIKE ?', ["%{$searchTerm}%"])
            ->get();

        return Inertia::render('Huawei/SpecialRefunds', [
            'refunds' => $refunds,
            'search' => $request
        ]);
    }

    public function storeSpecialRefund(Request $request)
    {
        $data = $request->validate([
            'description' => 'required',
            'diu' => 'required',
            'quantity' => 'required',
            'observation' => 'nullable'
        ]);

        HuaweiSpecialRefund::create($data);

        return redirect()->back();
    }

    public function updateSpecialRefund(HuaweiSpecialRefund $id, Request $request)
    {
        $data = $request->validate([
            'description' => 'required',
            'diu' => 'required',
            'quantity' => 'required',
            'observation' => 'required'
        ]);

        $id->update($data);
        return redirect()->back();
    }

    public function deleteSpecialRefund(HuaweiSpecialRefund $id)
    {
        $id->delete();

        return redirect()->back();
    }
}
