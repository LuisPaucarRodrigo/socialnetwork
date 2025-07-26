<?php

namespace App\Http\Controllers\ShoppingArea;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Segment;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MCSController extends Controller
{
    //Reenderizar vista
    public function index()
    {
        return Inertia::render('ShoppingArea/ProviderManagement/MCS/index');
    }

    //Obtener los datos para la vista
    public function getData()
    {
        $data = Category::with('segment')->paginate();
        return response()->json($data, 200);
    }

    //Buscar datos
    public function search(Request $request)
    {
        $search = $request->searchQuery;
        $data = Category::with('segment')
            ->where('name', 'like', "%$search%")
            ->orWhereHas('segment', function ($e) use ($search) {
                $e->where('name', 'like', "%$search%");
            });
        $data = $data->get();
        return response()->json($data, 200);
    }

    //Creacion de categorias
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'segment' => 'required|array',
            'segment.*.name' => 'required|string'
        ]);
        $category = Category::create($data);
        foreach ($data['segment'] as $item) {
            Segment::create([
                'category_id' => $category->id,
                'name' => $item['name']
            ]);
        }
        $category->load('segment');
        return response()->json($category, 200);
    }

    //Actualizar categorias
    public function edit(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'segment' => 'required|array',
            'segment.*.id' => 'nullable|numeric',
            'segment.*.name' => 'required|string'
        ]);
        $category->update(['name' => $data['name']]);
        $existingIds = $category->segment()->pluck('id')->toArray();
        $incomingIds = collect($data['segment'])->pluck('id')->filter()->toArray();

        $toDelete = array_diff($existingIds, $incomingIds);

        foreach ($toDelete as $id) {
            $segment = Segment::find($id);
            $segment->delete();
        }

        foreach ($data['segment'] as $seg) {
            if (isset($seg['id'])) {
                Segment::where('id', $seg['id'])->update(['name' => $seg['name']]);
            } else {
                $category->segment()->create(['name' => $seg['name']]);
            }
        }
        $category->load('segment');
        return response()->json($category, 200);
    }

    //Eliminar categorias
    public function delete(Category $item)
    {
        try {
            $item->delete();
            return response()->json([], 200);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return response()->json('No se puede eliminar: existen datos relacionados.', 422);
            }
            throw $e;
        }
    }

    //Crear segmentos
    public function segment_store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required',
            'name' => 'required'
        ]);
        $segment = Segment::create($data);
        return response()->json($segment, 200);
    }

    //Editar segmentos
    public function segment_edit(Request $request, Segment $segment)
    {
        $data = $request->validate([
            'name' => 'required'
        ]);
        $segment->update($data);
        return response()->json($segment, 200);
    }

    //Eliminar Segmentos
    public function segment_delete(Segment $item)
    {
        $item->delete();
        return response()->json([], 200);
    }
}
