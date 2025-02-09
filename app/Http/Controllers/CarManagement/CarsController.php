<?php

namespace App\Http\Controllers\CarManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\FleetCar\FleetCarChangelogRequest;
use App\Http\Requests\FleetCar\FleetCarDocumentRequest;
use App\Http\Requests\FleetCar\FleetCarRequest;
use App\Models\Car;
use App\Models\CarChangelog;
use App\Models\CarDocument;
use App\Models\CostLine;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CarsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cars = Car::with('user', 'costline', 'car_document');
        $users = User::where('role_id', 2)
            ->get();
        if ($user->role_id !== 1) {
            $cars->where('user_id', $user->id);
        }

        $cars = $cars->orderby('created_at','desc')->get();
        return Inertia::render('FleetCar/Index', [
            'car' => $cars,
            'costLine' => CostLine::all(),
            'users' => $users
        ]);
    }

    public function search(Request $request)
    {
        $user = Auth::user();
        $cost_line = $request->cost_line;
        $search = $request->search;
        $cars = Car::with('user', 'costline')
            ->where(function ($query) use ($search) {
                $query->where('plate', 'like', "%$search%")
                    ->orWhere('brand', 'like', "%$search%")
                    ->orWhere('model', 'like', "%$search%")
                    ->orWhere('type', 'like', "%$search%");
            });
        if ($user->role_id !== 1) {
            $cars->where('user_id', $user->id);
        }
        if (count($cost_line) < 3) {
            $cars->whereHas('costline', function ($query) use ($cost_line) {
                $query->whereIn('name', $cost_line);
            });
        }
        $cars = $cars->get();
        return response()->json($cars, 200);
    }

    public function indexAdmin()
    {
        $cars = Car::with(['car_document', 'car_changelogs.car_changelog_items'])->get();
        return Inertia::render('FleetCar/Index', [
            'cars' => $cars
        ]);
    }

    public function store(FleetCarRequest $request)
    {
        $data = $request->validated();
        try {
            if ($request->hasFile('photo')) {
                $data['photo'] = $this->saveImage($request->file('photo'), 'image/car/', 'car');
            }
            $car = Car::create($data);
            $car->load('user', 'costline');
            return response()->json($car, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function saveImage($photo, $path, $name): String
    {
        try {
            $imagename = time() . '_' . $name . $photo->getClientOriginalName();
            $photo->move(public_path($path), $imagename);
            return $imagename;
        } catch (Exception $e) {
            abort(500, 'something went wrong');
        }
    }

    public function update(FleetCarRequest $request, Car $car)
    {
        $data = $request->validated();
        if ($request->hasFile('photo')) {
            $fileName = $car->photo;
            if ($fileName) {
                $file_path = "image/car/$fileName";
                if (file_exists(public_path($file_path))) {
                    unlink(public_path($file_path));
                }
            }
            $data['photo'] = $this->saveImage($request->file('photo'), 'image/car/', 'car');
        }
        $car->update($data);
        $car->load('user', 'costline');
        return response()->json($car);
    }

    public function showImage(Car $car)
    {
        $fileName = $car->photo;
        $file_path = "image/car/$fileName";
        if (file_exists(public_path($file_path))) {
            ob_end_clean();
            return response()->file(public_path($file_path));
        }
        abort(404, 'Archivo no encontrada');
    }

    public function destroy(Car $car)
    {
        $car->delete();
        return response()->json(true);
    }

    //documents
    public function showDocuments(CarDocument $car_document, $fieldName)
    {
        $fileName = $car_document->$fieldName;
        $file_path = "documents/fleetcar/car_documents/$fileName";
        if (file_exists(public_path($file_path))) {
            ob_end_clean();
            return response()->file(public_path($file_path));
        }
        abort(404, 'Archivo no encontrada');
    }

    public function storeDocument(FleetCarDocumentRequest $request)
    {
        $data = $request->validated();
        try {
            $archives = ['ownership_card', 'technical_review', 'soat', 'insurance'];
            foreach ($archives as $archive) {
                if ($request->hasFile($archive)) {
                    $document = $request->file($archive);
                    $data[$archive] = time() . '_' . $document->getClientOriginalName();
                    $document->move(public_path('documents/fleetcar/car_documents/'), $data[$archive]);
                }
            }
            // $data['car_id'] = $car->id;
            $carDocument = CarDocument::create($data);
            return response()->json($carDocument);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function updateDocument(FleetCarDocumentRequest $request, CarDocument $carDocument)
    {
        $data = $request->validated();
        $archives = ['ownership_card', 'technical_review', 'soat', 'insurance'];
        foreach ($archives as $archive) {
            if ($request->hasFile($archive)) {
                $fileName = $carDocument->$archive;
                if ($fileName) {
                    $file_path = "documents/fleetcar/car_documents/$fileName";
                    if (file_exists(public_path($file_path))) {
                        unlink(public_path($file_path));
                    }
                }
                $document = $request->file($archive);
                $data[$archive] = time() . '_' . $document->getClientOriginalName();
                $document->move(public_path('documents/fleetcar/car_documents/'), $data[$archive]);
            }
        }
        $carDocument->update($data);
        return response()->json($carDocument);
    }

    public function destroyDocument(CarDocument $carDocument)
    {
        $carDocument->delete();
        $archives = ['ownership_card', 'technical_review', 'soat', 'insurance'];
        foreach ($archives as $archive) {
            $fileName = $carDocument->$archive;
            $file_path = "documents/fleetcar/car_documents/$fileName";
            if (file_exists(public_path($file_path))) {
                unlink(public_path($file_path));
            }
        }
        return response()->json(true);
    }

    //changelogs
    public function storeChangelog(FleetCarChangelogRequest $request, Car $car)
    {
        $data = $request->validated();
        if ($request->hasFile('invoice')) {
            $document = $request->file('invoice');
            $data['invoice'] = time() . '_' . $document->getClientOriginalName();
            $document->move(public_path('documents/fleetcar/invoices'), $data['invoice']);
        }
        $data['car_id'] = $car->id;
        $carChangelog = CarChangelog::create($data);
        if (!empty($data['items']) && is_array($data['items'])) {
            $carChangelog->car_changelog_items()->delete();
            $carChangelog->car_changelog_items()->createMany($data['items']);
        }

        return response()->json($carChangelog->load('car_changelog_items'));
    }

    public function updateChangelog(FleetCarChangelogRequest $request, CarChangelog $carChangelog)
    {
        $data = $request->validated();
        $fileName = $carChangelog->invoice;
        $file_path = "documents/fleetcar/invoices/$fileName";
        if ($request->hasFile('invoice')) {
            if (file_exists(public_path($file_path))) {
                unlink(public_path($file_path));
            }
            $document = $request->file('invoice');
            $data['invoice'] = time() . '_' . $document->getClientOriginalName();
            $document->move(public_path('documents/fleetcar/invoices'), $data['invoice']);
        }
        $carChangelog->update($data);
        if (!empty($data['items']) && is_array($data['items'])) {
            $carChangelog->car_changelog_items()->delete();
            $carChangelog->car_changelog_items()->createMany($data['items']);
        }

        return response()->json($carChangelog->load('car_changelog_items'));
    }

    public function destroyChangelog(CarChangelog $carChangelog)
    {
        $fileName = $carChangelog->invoice;
        $file_path = "documents/fleetcar/invoices/$fileName";
        if (file_exists(public_path($file_path))) {
            unlink(public_path($file_path));
        }
        $carChangelog->delete();
        return response()->json(true);
    }

    public function showChangelogInvoice(CarChangelog $carChangelog)
    {
        $fileName = $carChangelog->invoice;
        $file_path = "documents/fleetcar/invoices/$fileName";
        if (file_exists(public_path($file_path))) {
            ob_end_clean();
            return response()->file(public_path($file_path));
        }
        abort(404, 'Factura no encontrada');
    }
}
