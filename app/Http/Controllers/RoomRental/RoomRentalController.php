<?php

namespace App\Http\Controllers\RoomRental;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomRental\RoomRentalChangelogRequest;
use App\Http\Requests\RoomRental\RoomRentalDocumentRequest;
use App\Http\Requests\RoomRental\RoomRentalRequest;

use App\Models\ApprovalRoomDocument;
use App\Models\Provider;
use App\Models\Room;
use App\Models\RoomChangelog;
use App\Models\RoomChangelogItem;
use App\Models\RoomDocument;


use App\Models\ChecklistCar;

use App\Models\CostLine;
use App\Models\User;
use Carbon\Carbon;
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
            'provider:id,company_name,contact_name,zone', 
            'costline:id,name', 
            'room_document.approvel_room_document:id,room_document_id', 
            'room_changelogs.room_changelog_items'
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
            'provider:id,company_name,contact_name,zone', 
            'costline:id,name', 
            'room_document.approvel_room_document:id,room_document_id', 
            'room_changelogs.room_changelog_items'
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

        $today = Carbon::now();
        $expirationThreshold = $today->copy()->addDays(7);

        $hasPermissions = $this->notHaveManagerPermission();
        $user = Auth::user();
        $carsQuery = !$hasPermissions ? Room::with('room_document') : Room::where('user_id', $user->id);
        $cars = $carsQuery->whereHas('room_document', function ($query) use ($expirationThreshold) {
            $query->where('technical_review_date', '<=', $expirationThreshold)
                ->orWhere('soat_date', '<=', $expirationThreshold)
                ->orWhere('insurance_date', '<=', $expirationThreshold)
                ->orWhere('rental_contract_date', '<=', $expirationThreshold);
        })

            ->get();

        return response()->json([
            'documentsCarToExpire' => $cars,
        ], 200);
    }

    public function getChangelogAlarms()
    {
        $user = Auth::user();
        $hasPermissions = $this->notHaveManagerPermission();
        $carsQuery = !$hasPermissions ? Room::query() : Room::where('user_id', $user->id);
        $cars = $carsQuery->whereHas('room_changelogs', function ($query) {
            $query->whereNull('is_accepted');
        })
            ->get();

        // if (!$hasPermissions) {
        //     $cars = Car::whereHas('car_changelogs', function ($query) {
        //         $query->whereNull('is_accepted');
        //     })
        //         ->get();
        // } else {
        //     $cars = Car::where('user_id', $user->id)
        //         ->whereHas('car_changelogs', function ($query) {
        //             $query->whereNull('is_accepted');
        //         })
        //         ->get();
        // }
        return response()->json([
            'carsToExpire' => $cars,
        ], 200);
    }

    public function specificAlarm($car_id)
    {
        $today = Carbon::now();
        $expirationThreshold = $today->copy()->addDays(7);
        $document = RoomDocument::where('room_id', $car_id)->first();
        $expiring = [];

        if ($document->technical_review_date && $document->technical_review_date <= $expirationThreshold) {
            $expiring['Revisión Técnica'] = $document->technical_review_date;
        }

        if ($document->soat_date && $document->soat_date <= $expirationThreshold) {
            $expiring['SOAT'] = $document->soat_date;
        }

        if ($document->insurance_date && $document->insurance_date <= $expirationThreshold) {
            $expiring['Seguro'] = $document->insurance_date;
        }

        if ($document->rental_contract_date && $document->rental_contract_date <= $expirationThreshold) {
            $expiring['Contrato'] = $document->rental_contract_date;
        }

        return response()->json($expiring, 200);
    }

    public function store(RoomRentalRequest $request)
    {
        $data = $request->validated();
        try {
            if ($request->hasFile('photo')) {
                $data['photo'] = $this->saveImage($request->file('photo'), 'image/room/', 'room');
            }
            $car = Room::create($data);
            $car->load(['provider', 'costline', 'room_changelogs.room_changelog_items', 'checklist']);
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
        $car->load(['provider', 'costline', 'room_changelogs.room_changelog_items', 'checklist']);
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
            $archives = ['ownership_card', 'technical_review', 'soat', 'insurance', 'rental_contract'];
            foreach ($archives as $archive) {
                if ($request->hasFile($archive)) {
                    $document = $request->file($archive);
                    $data[$archive] = uniqid() . '_' . time() . '_' . $document->getClientOriginalName();
                    $document->move(public_path('documents/roomrental/room_documents/'), $data[$archive]);
                }
            }
            $carDocument = RoomDocument::create($data);
            $carDocument->load("approvel_room_document");
            return response()->json($carDocument, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function updateDocument(RoomRentalDocumentRequest $request, RoomDocument $carDocument)
    {
        $data = $request->validated();
        $archives = ['ownership_card', 'technical_review', 'soat', 'insurance', 'rental_contract'];
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
            $carDocument->load("approvel_room_document");
            return response()->json($carDocument, 200);
        } else {
            foreach ($archives as $archive) {
                if ($request->hasFile($archive)) {
                    $document = $request->file($archive);
                    $data[$archive] = uniqid() . '_' . time() . '_' . $document->getClientOriginalName();
                    $document->move(public_path('documents/roomrental/room_documents/'), $data[$archive]);
                }
            }

            $data['car_document_id'] = $carDocument->id;
            ApprovalRoomDocument::create($data);
            return response()->json([], 200);
        }
    }

    public function destroyDocument(RoomDocument $carDocument)
    {
        $carDocument->delete();
        $archives = ['ownership_card', 'technical_review', 'soat', 'insurance', 'rental_contract'];
        foreach ($archives as $archive) {
            $fileName = $carDocument->$archive;
            $file_path = "documents/roomrental/room_documents/$fileName";
            if (file_exists(public_path($file_path))) {
                unlink(public_path($file_path));
            }
        }
        return response()->json(true);
    }

    //changelogs
    public function storeChangelog(RoomRentalChangelogRequest $request, Room $car)
    {
        $data = $request->validated();
        if ($request->hasFile('invoice')) {
            $document = $request->file('invoice');
            $data['invoice'] = uniqid() . '_' . time() . '_' . $document->getClientOriginalName();
            $document->move(public_path('documents/roomrental/invoices'), $data['invoice']);
        }
        $data['room_id'] = $car->id;
        $carChangelog = RoomChangelog::create($data);
        if (!empty($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {
                RoomChangelogItem::create([
                    'name' => $item,
                    'room_changelog_id' => $carChangelog->id
                ]);
            }
        }

        return response()->json($car->load(['user', 'costline', 'room_changelogs' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }, 'room_changelogs.room_changelog_items', 'checklist']));
    }

    public function updateChangelog(RoomRentalChangelogRequest $request, RoomChangelog $carChangelog)
    {
        $data = $request->validated();
        $fileName = $carChangelog->invoice;
        $file_path = "documents/roomrental/invoices/$fileName";
        if ($request->hasFile('invoice')) {
            if (file_exists(public_path($file_path))) {
                unlink(public_path($file_path));
            }
            $document = $request->file('invoice');
            $data['invoice'] = uniqid() . '_' . time() . '_' . $document->getClientOriginalName();
            $document->move(public_path('documents/roomrental/invoices'), $data['invoice']);
        }
        $carChangelog->update($data);

        if (!empty($data['items']) && is_array($data['items'])) {
            RoomChangelogItem::where('room_changelog_id', $carChangelog->id)->delete();
            foreach ($data['items'] as $item) {
                RoomChangelogItem::create([
                    'name' => $item,
                    'room_changelog_id' => $carChangelog->id
                ]);
            }
        }
        $car = Room::find($carChangelog->car_id);
        return response()->json($car->load(['user', 'costline', 'room_changelogs' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }, 'room_changelogs.room_changelog_items', 'checklist']));
    }

    public function destroyChangelog(RoomChangelog $carChangelog)
    {
        $car = Room::find($carChangelog->car_id);
        $fileName = $carChangelog->invoice;
        $file_path = "documents/roomrental/invoices/$fileName";
        if (file_exists(public_path($file_path))) {
            unlink(public_path($file_path));
        }
        $carChangelog->delete();
        return response()->json($car->load(['user', 'costline', 'room_changelogs' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }, 'room_changelogs.room_changelog_items', 'checklist']));
    }

    public function showChangelogInvoice(RoomChangelog $carChangelog)
    {
        $fileName = $carChangelog->invoice;
        $file_path = "documents/roomrental/invoices/$fileName";
        if (file_exists(public_path($file_path))) {
            ob_end_clean();
            return response()->file(public_path($file_path));
        }
        abort(404, 'Factura no encontrada');
    }

    //checklist
    public function showChecklist(Room $car)
    {
        $checklist = ChecklistCar::where('car_id', $car->id)->orderBy('created_at', 'desc')->paginate();
        return Inertia::render('RoomRental/checklist/CheckList', [
            'car' => $car->load('user'),
            'checklist' => $checklist
        ]);
    }
    public function sendChecklistImages(CheckListCar $checklist)
    {
        $basePath = 'image/checklist/checklistcar';
        $images = [];

        // Campos y sus traducciones
        $fields = [
            'maintenanceTools' => 'Foto Herraientas de Mantenimiento',
            'preventionTools' => 'Foto Herramientas de Prevencion',
            'imageSpareTire' => 'Foto Llanta de Repuesto',
            'front' => 'Foto Delantera',
            'leftSide' => 'Foto Lateral Izquierda',
            'rightSide' => 'Foto Lateral Derecha',
            'interior' => 'Foto Interior',
            'rearLeftTire' => 'Foto Llanta Trasera Izquierda',
            'rearRightTire' => 'Foto Llanta Trasera Derecha',
            'frontRightTire' => 'Foto Llanta Delantera Derecha',
            'frontLeftTire' => 'Foto Llanta Delantera Izquierda',
            'back' => 'Foto Trasera',
            'dashboard' => 'Foto de Tablero',
            'rearSeat' => 'Foto de Asiento Trasero'
        ];

        foreach ($fields as $field => $translatedName) {
            $imagePath = public_path($basePath . '/' . $checklist->$field);

            if (!empty($checklist->$field) && file_exists($imagePath)) {
                $absolutePath = asset($basePath . '/' . $checklist->$field);
                $images[] = [$translatedName => $absolutePath];
            }
        }

        return response()->json($images);
    }


    public function indexApprovelCarDocument()
    {
        $changes = ApprovalRoomDocument::with([
            'room_document:id,room_id',
            'room_document.room:id,plate'
        ])->get();
        return Inertia::render("RoomRental/approvals/IndexApprovals", [
            'change' => $changes
        ]);
    }

    public function approveChanges($id)
    {
        $archives = ['ownership_card', 'technical_review', 'soat', 'insurance', 'rental_contract'];
        try {
            $approve_car = ApprovalRoomDocument::find($id);
            if ($approve_car) {
                $car = RoomDocument::find($approve_car->car_document_id);
                foreach ($archives as $archive) {
                    if ($car->$archive && $car->$archive !== $approve_car->$archive) {
                        $fileName = $car->$archive;
                        $file_path = "documents/roomrental/room_documents/$fileName";
                        if (file_exists(public_path($file_path))) {
                            unlink(public_path($file_path));
                        }
                    }
                }
                $car->update($approve_car->toArray());

                $approve_car->delete();
                $pendingApprovals = ApprovalRoomDocument::where('room_document_id', $car->id)->get();

                foreach ($pendingApprovals as $approval) {
                    foreach ($archives as $item) {
                        if ($approval->$item) {
                            $file_path = "documents/roomrental/room_documents/{$approval->file_name}";
                            if (file_exists(public_path($file_path))) {
                                unlink(public_path($file_path));
                            }
                        }
                    }
                    $approval->delete();
                }
            }
            $approve_car->delete();
            return response()->json([], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteChanges($id)
    {
        $archives = ['ownership_card', 'technical_review', 'soat', 'insurance', 'rental_contract'];
        $changes = ApprovalRoomDocument::find($id);
        foreach ($archives as $item) {
            if ($changes->$item) {
                $file_path = "documents/roomrental/room_documents/{$changes->$item}";
                if (file_exists(public_path($file_path))) {
                    unlink(public_path($file_path));
                }
            }
        }
        $changes->delete();
        return response()->json([], 200);
    }

    public function showDocumentsApproval(ApprovalRoomDocument $approval_car, $fieldName)
    {
        $fileName = $approval_car->$fieldName;
        $file_path = "documents/roomrental/room_documents/$fileName";
        if (file_exists(public_path($file_path))) {
            ob_end_clean();
            return response()->file(public_path($file_path));
        }
        abort(404, 'Archivo no encontrada');
    }

    public function approveAlarms()
    {
        $approval = RoomDocument::whereHas('approvel_room_document')->get();
        return response()->json($approval, 200);
    }

    public function acceptOrDecline(RoomChangelog $changelog, $is_accepted)
    {
        $hasPermissions = $this->notHaveManagerPermission();

        if ($hasPermissions) {
            abort(403, 'Acción no permitida');
        }

        $changelog->update(['is_accepted' => $is_accepted]);
        $car = Room::with([
            'user',
            'costline',
            'room_changelogs' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'room_changelogs.room_changelog_items',
            'checklist'
        ])
            ->find($changelog->car_id);

        return response()->json($car);
    }

    public function checkListAlarms()
    {
        $user = Auth::user();
        $hasPermissions = $this->notHaveManagerPermission();
        $checkList = $hasPermissions
            ? Room::where('user_id', $user->id)
            : Room::query();

        $checkList = $checkList->with(['checklist' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])
            ->get();
        $checkList = $checkList->filter(function ($car) {
            $lastChecklist = $car->checklist->first();
            return !$lastChecklist || $lastChecklist->created_at < Carbon::now()->subDays(7);
        });

        $checkList->each(function ($car) {
            $lastChecklist = $car->checklist->first();
            $car->days = $lastChecklist
                ? Carbon::now()->diffInDays(Carbon::parse($lastChecklist->created_at)->addDays(7)) . ' R'
                : 'Sin checklist';
        });

        return response()->json($checkList->values(), 200);
    }
}
