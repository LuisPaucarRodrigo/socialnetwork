<?php

namespace App\Http\Controllers\ProjectArea\PRO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Code;
use App\Models\CodeImage;
use App\Models\ReportStage;
use App\Models\Title;
use Exception;
use Inertia\Inertia;

class ProController extends Controller
{
    public function showTitles()
    {
        return Inertia::render('ProjectArea/PreProject/PRO/Titles/Index', [
            'titles' => Title::with('codes')
                ->orderBy('created_at', 'desc')
                ->paginate(10),
            'codes' => Code::all(),
            'stages' => ReportStage::select('id', 'name')->get(),
        ]);
    }

    public function postTitle(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'type' => 'required',
            'code_id_array' => 'required|array'
        ]);

        $title = Title::create($data);

        $title->codes()->attach($data['code_id_array']);
        $title->load('codes');
        return response()->json($title, 200);
    }

    public function putTitle(Request $request, $title_id)
    {
        $data = $request->validate([
            'title' => 'required',
            'type' => 'required',
            'code_id_array' => 'required|array'
        ]);

        $title = Title::find($title_id);
        $title->update($data);

        $title->codes()->sync($data['code_id_array'], true);
        $title->load('codes');
        return response()->json($title, 200);
    }

    public function deleteTitle(Title $title)
    {
        $title->delete();
        return response()->json([], 200);
    }

    //Codes
    public function showCodes()
    {
        return Inertia::render('ProjectArea/PreProject/PRO/Codes/Index', [
            'code' => Code::orderBy('created_at', 'desc')->paginate(20)
        ]);
    }
    public function storeCode(Request $request)
    {
        $validateData = $request->validate([
            'code' => 'required|string',
            'description' => 'required|string',
        ]);

        $code = Code::create($validateData);
        return response()->json($code, 200);
    }
    public function updateCode(Request $request, Code $code)
    {
        $validateData = $request->validate([
            'code' => 'required',
            'description' => 'required|string',
        ]);

        $code->update($validateData);
        return response()->json($code, 200);
    }

    public function deleteCode(Code $code)
    {
        if ($code->preprojectCodes()->exists() || $code->code_images()->exists() || $code->titles()->exists() || $code->code_images()->exists()) {
            return response()->json(['message' => 'No se puede eliminar el código porque tiene relaciones con otras tablas'], 400);
        }
        $code->delete();
        return response()->noContent();
    }
    public function indexImages($code_id)
    {
        $imagesCode = CodeImage::where('code_id', $code_id)
            ->get();
        return response()->json($imagesCode, 200);
    }

    public function storeCodeImages(Request $request)
    {
        $validateData = $request->validate([
            'code_id' => 'required|numeric',
            'images' => 'required|array',
            'images.*.image' => 'required|image|mimes:jpeg,jpg,png|max:2048'
        ]);

        foreach ($validateData['images'] as $image) {
            $uploadedImage = $image['image'];
            $image['image'] = time() . '._' . $uploadedImage->getClientOriginalName();
            CodeImage::create([
                'code_id' => $validateData['code_id'],
                'image' => $image['image']
            ]);
            $uploadedImage->move(public_path('image/imageCode/'), $image['image']);
        }
        return response()->noContent();
    }

    public function show_code_image($image_id)
    {
        $codeImage = CodeImage::find($image_id);
        $fileName = $codeImage->image;
        $filePath = 'image/imageCode/' . $fileName;
        $path = public_path($filePath);
        if (file_exists($path)) {
            $url = url($filePath);
            return response()->json(['url' => $url]);
        }
        abort(404, 'Imagen no encontrada');
    }

    public function deleteCodeImages($image_id)
    {
        try {
            $codeImage = CodeImage::find($image_id);
            if ($codeImage) {
                // $filePath = "image/imageCode/{$codeImage->image}";
                // $path = public_path($filePath);

                // if (file_exists($path)) {
                //     unlink($path); 
                // }
                $codeImage->delete();

                return response()->json([], 200);
            } else {
                return response()->json(['error' => 'Imagen no encontrada'], 404);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
