<?php

namespace App\Http\Controllers\RoomRental;

use App\Helpers\FileHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoomRental\RoomRentalDocumentRequest;
use App\Http\Requests\RoomRental\RoomRentalRequest;

use App\Models\Provider;
use App\Models\Room;
use App\Models\RoomDocument;
use App\Models\CostLine;
use App\Models\RoomImage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RoomRentalController extends Controller
{
    private string $pathRoom = 'image/room/';
    private string $pathRoomDocument = 'documents/roomrental/room_documents/';
    public function notHaveManagerPermission(): bool
    {
        $user = Auth::user();

        $userFunctionalities = $user->role->functionalities;

        $hasPermissions = $userFunctionalities->contains('key_name', 'room_actions') ||
            $userFunctionalities->contains('key_name', 'see_room_unit');

        return $hasPermissions;
    }

    public function index($id = null)
    {
        $cars = Room::with([
            'provider:id,company_name,contact_name,zone,phone1,phone2',
            'costline:id,name',
            'room_documents' => function ($query) {
                $query->orderBy('expiration_date', 'desc');
            },
        ])->orderBy('created_at', 'desc');

        $providers = Provider::whereHas('category', function ($query) {
            $query->where('name', 'like', '%alquiler%');
        })->whereHas('segments', function ($query) {
            $query->where('name', 'like', '%cuart%');
        })->get();


        $hasPermissions = $this->notHaveManagerPermission();
        if ($hasPermissions) {
            $user = Auth::user();
            $cars->where('user_id', $user->id);
        }
        $cars = $cars->paginate(20);
        return Inertia::render('RoomRental/index/Index', [
            'car' => $cars,
            'costLine' => CostLine::all(),
            'id' => $id,
            'providers' => $providers
        ]);
    }

    public function search(Request $request)
    {
        $cost_line = $request->cost_line;
        $search = $request->search;

        $cars = Room::with([
            'provider:id,company_name,contact_name,zone,phone1,phone2',
            'costline:id,name',
            'room_documents' => function ($query) {
                $query->orderBy('expiration_date', 'desc');
            },
        ])
            ->where(function ($e) use ($search) {
                $e->where(function ($query) use ($search) {
                    $query->where('rental_type', 'like', "%$search%")
                        ->orWhere('address', 'like', "%$search%")
                        ->orWhere('observations', 'like', "%$search%");
                })
                    ->orWhereHas('provider', function ($query) use ($search) {
                        $query->where('company_name', 'like', "%$search%")
                            ->orWhere('contact_name', 'like', "%$search%")
                            ->orWhere('zone', 'like', "%$search%");
                    });
            })
            ->orderBy('created_at', 'desc');
        $hasPermissions = $this->notHaveManagerPermission();

        if ($hasPermissions) {
            $user = Auth::user();
            $cars->where('user_id', $user->id);
        }
        if (count($cost_line) < 5) {
            $cars->whereHas('costline', function ($query) use ($cost_line) {
                $query->whereIn('name', $cost_line);
            });
        }
        $cars = $cars->get();
        return response()->json($cars, 200);
    }


    public function alarms()
    {
        $rooms = Room::with('provider')->get()
            ->each->append('documents_to_expire')
            ->filter(function ($room) {
                return $room->documents_to_expire;
            });

        return response()->json([
            'documentsCarToExpire' => $rooms,
        ], 200);
    }


    public function store(RoomRentalRequest $request)
    {
        $data = $request->validated();
        try {
            $room = Room::create($data);
            $room->load(['provider', 'room_documents', 'costline', 'room_images']);
            return response()->json($room, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function update(RoomRentalRequest $request, Room $car)
    {
        $data = $request->validated();
        $car->update($data);
        $car->load(['provider', 'room_documents', 'costline']);
        return response()->json($car, 200);
    }

    public function storeImages(Request $request, Room $room)
    {
        $validateData = $request->validate([
            'images' => 'required|array',
            'images.*.image' => 'required|file',
        ]);
        foreach ($validateData['images'] as $image) {
            $file = $image['image'];
            $filename = FileHandler::generateFilename($room->address, 'Foto de sitio');
            RoomImage::create([
                'room_id' => $room->id,
                'photo' => $filename
            ]);
            FileHandler::storeFile($file, $this->pathRoom, $filename);
        }
        return response()->json([], 200);
    }

    public function getImages($room_id)
    {
        $images = RoomImage::where('room_id', $room_id)->get();
        return response()->json($images, 200);
    }

    public function deleteImage(RoomImage $image)
    {
        $image->delete();
        return response()->json([], 200);
    }

    public function showImage(RoomImage $image)
    {
        $fileName = $image->photo;
        return FileHandler::showFile($this->pathRoom, $fileName);
    }

    public function destroy(Room $car)
    {
        $car->delete();
        return response()->json(true);
    }

    //documents
    public function showDocuments(RoomDocument $car_document, $fieldName)
    {
        $fileName = $car_document->$fieldName;
        return FileHandler::showFile($this->pathRoomDocument, $fileName);
    }

    public function storeDocument(RoomRentalDocumentRequest $request)
    {
        $data = $request->validated();
        try {
            $data['archive'] = FileHandler::generateFilename('', 'Contrato de Alquiler');
            $carDocument = RoomDocument::create($data);
            FileHandler::storeFile($request->file('archive'), $this->pathRoomDocument, $data['archive']);
            $car = Room::find($carDocument->room_id);
            $car->load(['provider', 'costline', 'room_documents' => function ($query) {
                $query->orderBy('expiration_date', 'desc');
            }]);
            return response()->json($car, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function updateDocument(RoomRentalDocumentRequest $request, RoomDocument $carDocument)
    {
        $data = $request->validated();
        if ($request->hasFile('archive')) {
            $data['archive'] = FileHandler::generateFilename('', 'Contrato de Alquiler');
        }
        $carDocument->update($data);
        if ($request->hasFile('archive')) {
            FileHandler::storeFile($request->file('archive'), $this->pathRoomDocument, $data['archive']);
        }
        $car = Room::find($carDocument->room_id);
        $car->load(['provider', 'costline', 'room_documents' => function ($query) {
            $query->orderBy('expiration_date', 'desc');
        }]);
        return response()->json($car, 200);
    }

    public function destroyDocument(RoomDocument $carDocument)
    {
        $room = Room::find($carDocument->room_id);
        $carDocument->delete();
        $room->load(['provider', 'costline', 'room_documents' => function ($query) {
            $query->orderBy('expiration_date', 'desc');
        }]);
        return response()->json($room, 200);
    }
}
