<?php 

namespace App\Http\Controllers\FleetCar;

use App\Http\Controllers\Controller;
use App\Http\Requests\FleetCar\FleetCarDocumentRequest;
use App\Models\Car;
use App\Models\CarDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Requests\FleetCar\FleetCarRequest;

class FleetCarController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cars = Car::where('user_id', $user->id)->get();
        return Inertia::render('FleetCar/Index', [
            'cars' => $cars
        ]);
    }

    public function indexAdmin ()
    {
        $cars = Car::all();
        return Inertia::render('FleetCar/Index', [
            'cars' => $cars
        ]);
    }

    public function store(FleetCarRequest $request)
    {
        $data = $request->validated();
        $car = Car::create($data);
        return response()->json($car);
    }

    public function update(FleetCarRequest $request, Car $car)
    {
        $data = $request->validated();
        $car->update($data);
        return response()->json($car);
    }

    public function destroy(Car $car)
    {
        $car->delete();
        return response()->json(true);
    }

    //documents
    public function storeDocument(FleetCarDocumentRequest $request, Car $car)
    {
        $data = $request->validated();
        $data['car_id'] = $car->id;
        $carDocument = CarDocument::create($data);
        return response()->json($carDocument);
    }

    public function updateDocument(FleetCarDocumentRequest $request, CarDocument $carDocument)
    {
        $data = $request->validated();
        $carDocument->update($data);
        return response()->json($carDocument);
    }

    public function destroyDocument(CarDocument $carDocument)
    {
        $carDocument->delete();
        return response()->json(true);
    }
}