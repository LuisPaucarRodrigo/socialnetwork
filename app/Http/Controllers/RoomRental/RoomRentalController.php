<?php

namespace App\Http\Controllers\RoomRental;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomRental\RoomRentalDocumentRequest;
use App\Http\Requests\RoomRental\RoomRentalRequest;

use App\Models\Provider;
use App\Models\Room;
use App\Models\RoomDocument;
use App\Models\CostLine;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;


class RoomRentalController extends Controller
{
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

        $providers = Provider::whereHas('category', function($query) {
            $query->where('name', 'like', '%alquiler%');
        })->whereHas('segments', function($query) {
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
            ->where(function ($query) use ($search) {
                $query->where('rental_type', 'like', "%$search%")
                    ->orWhere('address', 'like', "%$search%")
                    ->orWhere('observations', 'like', "%$search%");
            })
            ->whereHas('provider', function($query) use ($search){
                $query->where('company_name', 'like', "%$search%")
                    ->where('contact_name', 'like', "%$search%")
                    ->where('zone', 'like', "%$search%");
            })
            ->orderBy('created_at', 'desc');
        $hasPermissions = $this->notHaveManagerPermission();

        if ($hasPermissions) {
            $user = Auth::user();
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


    public function alarms()
    {

        $rooms = Room::with('provider')->get()
            ->each->append('documents_to_expire')
            ->filter(function($room){
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
            if ($request->hasFile('photo')) {
                $data['photo'] = $this->saveImage($request->file('photo'), 'image/room/', 'room');
            }
            $car = Room::create($data);
            $car->load(['provider', 'room_documents', 'costline']);
            return response()->json($car, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function saveImage($photo, $path, $name): String
    {
        try {
            $imagename = uniqid() . '_' . time() . '_' . $name . $photo->getClientOriginalName();
            $photo->move(public_path($path), $imagename);
            return $imagename;
        } catch (Exception $e) {
            abort(500, 'something went wrong');
        }
    }

    public function update(RoomRentalRequest $request, Room $car)
    {
        $data = $request->validated();
        if ($request->hasFile('photo')) {
            $fileName = $car->photo;
            if ($fileName) {
                $file_path = "image/room/$fileName";
                if (file_exists(public_path($file_path))) {
                    unlink(public_path($file_path));
                }
            }
            $data['photo'] = $this->saveImage($request->file('photo'), 'image/room/', 'room');
        }
        $car->update($data);
        $car->load(['provider', 'room_documents', 'costline',]);
        return response()->json($car, 200);
    }

    public function showImage(Room $car)
    {
        $fileName = $car->photo;
        $file_path = "image/room/$fileName";
        if (file_exists(public_path($file_path))) {
            ob_end_clean();
            return response()->file(public_path($file_path));
        }
        abort(404, 'Archivo no encontrada');
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
        $file_path = "documents/roomrental/room_documents/$fileName";
        if (file_exists(public_path($file_path))) {
            ob_end_clean();
            return response()->file(public_path($file_path));
        }
        abort(404, 'Archivo no encontrada');
    }

    public function storeDocument(RoomRentalDocumentRequest $request)
    {
        $data = $request->validated();
        try {
            $archives = ['archive'];
            foreach ($archives as $archive) {
                if ($request->hasFile($archive)) {
                    $document = $request->file($archive);
                    $data[$archive] = uniqid() . '_' . time() . '_' . $document->getClientOriginalName();
                    $document->move(public_path('documents/roomrental/room_documents/'), $data[$archive]);
                }
            }
            $carDocument = RoomDocument::create($data);
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
        $archives = ['archive', 'technical_review', 'soat', 'insurance', 'rental_contract'];
        $hasPermissions = $this->notHaveManagerPermission();

        if (!$hasPermissions) {
            foreach ($archives as $archive) {
                if ($request->hasFile($archive)) {
                    $fileName = $carDocument->$archive;
                    if ($fileName) {
                        $file_path = "documents/roomrental/room_documents/$fileName";
                        if (file_exists(public_path($file_path))) {
                            unlink(public_path($file_path));
                        }
                    }
                    $document = $request->file($archive);
                    $data[$archive] = uniqid() . '_' . time() . '_' . $document->getClientOriginalName();
                    $document->move(public_path('documents/roomrental/room_documents/'), $data[$archive]);
                }
            }
            $carDocument->update($data);
            $car = Room::find($carDocument->room_id);
            $car->load(['provider', 'costline', 'room_documents' => function ($query) {
                $query->orderBy('expiration_date', 'desc');
            }]);
            return response()->json($car, 200);
        } else {
            foreach ($archives as $archive) {
                if ($request->hasFile($archive)) {
                    $document = $request->file($archive);
                    $data[$archive] = uniqid() . '_' . time() . '_' . $document->getClientOriginalName();
                    $document->move(public_path('documents/roomrental/room_documents/'), $data[$archive]);
                }
            }

            $data['room_document_id'] = $carDocument->id;
            return response()->json([], 200);
        }
    }

    public function destroyDocument(RoomDocument $carDocument)
    {
        $room = Room::find($carDocument->room_id);
        $carDocument->delete();
        $archives = ['archive'];
        foreach ($archives as $archive) {
            $fileName = $carDocument->$archive;
            $file_path = "documents/roomrental/room_documents/$fileName";
            if (file_exists(public_path($file_path))) {
                unlink(public_path($file_path));
            }
        }
        $room->load(['provider', 'costline', 'room_documents' => function ($query) {
            $query->orderBy('expiration_date', 'desc');
        }]);
        return response()->json($room, 200);
    }





}
