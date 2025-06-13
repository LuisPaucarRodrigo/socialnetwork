<?php

namespace App\Http\Controllers\Huawei\Sites;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Constants\HuaweiConstants;
use App\Models\HuaweiSite;
use Inertia\Inertia;

class HuaweiSitesController extends Controller
{
    //Sites

    public function getSites()
    {
        return Inertia::render('Huawei/Sites/Sites', [
            'sites' => HuaweiSite::select('id', 'name', 'address', 'prefix', 'code', 'latitude', 'longitude')->orderBy('name')->paginate(10),
            'operators' => HuaweiConstants::getOperators()
        ]);
    }

    public function searchSites($request)
    {
        $searchTerm = strtolower($request);

        $query = HuaweiSite::query();

        $query->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"])
            ->orWhereRaw('LOWER(address) LIKE?', ["%{$searchTerm}%"])
            ->orWhereRaw('LOWER(prefix) LIKE?', ["%{$searchTerm}%"])
            ->orWhereRaw('LOWER(code) LIKE?', ["%{$searchTerm}%"]);

        return Inertia::render('Huawei/Sites/Sites', [
            'sites' => $query->select('id', 'name', 'address', 'prefix', 'code', 'latitude', 'longitude')->orderBy('name')->get(),
            'search' => $request,
            'operators' => HuaweiConstants::getOperators()
        ]);
    }

    public function storeSite(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'code' => 'required',
            'address' => 'nullable',
            'prefix' => 'required',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
        ]);

        HuaweiSite::create($data);

        return redirect()->back();
    }

    public function destroySite(HuaweiSite $site)
    {
        $site->delete();
        return redirect()->back();
    }

    public function updateSite(HuaweiSite $site, Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'code' => 'required',
            'address' => 'nullable',
            'prefix' => 'required',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
        ]);

        $site->update($data);

        return redirect()->back();
    }

    public function verifySiteName(Request $request, $update = null)
    {
        $term = strtolower($request->input('name'));

        $sites = HuaweiSite::all()->pluck('name')->toArray();

        $closeMatchName = null;

        foreach ($sites as $site) {
            $similarity = 0;
            similar_text($term, strtolower($site), $similarity);

            if ($similarity > 65) {
                $closeMatchName = $site;
                break;
            }
        }

        if ($update && $closeMatchName && $closeMatchName === HuaweiSite::find($update)->name) {
            return response()->json([
                'message' => 'notfound',
                'status' => 'none'
            ]);
        }

        if ($closeMatchName) {
            return response()->json([
                'message' => 'found',
                'status' => 'close',
                'name' => $closeMatchName
            ]);
        } else {
            return response()->json([
                'message' => 'notfound',
                'status' => 'none'
            ]);
        }
    }
}
