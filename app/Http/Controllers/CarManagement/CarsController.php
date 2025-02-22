<?php

namespace App\Http\Controllers\CarManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\FleetCar\FleetCarChangelogRequest;
use App\Http\Requests\FleetCar\FleetCarDocumentRequest;
use App\Http\Requests\FleetCar\FleetCarRequest;
use App\Models\ApprovalCarDocument;
use App\Models\Car;
use App\Models\CarChangelog;
use App\Models\CarChangelogItem;
use App\Models\CarDocument;
use App\Models\ChecklistCar;
use App\Models\CostLine;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CarsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cars = Car::with(['user', 'costline', 'car_document.approvel_car_document', 'car_changelogs' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }, 'car_changelogs.car_changelog_items', 'checklist']);
        $users = User::where('role_id', 12)
            ->get();
        if ($user->role_id !== 1) {
            $cars->where('user_id', $user->id);
        }

        $cars = $cars->orderby('created_at', 'desc')->get();
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
        $cars = Car::with(['user', 'costline', 'car_document.approvel_car_document','car_changelogs' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }, 'car_changelogs.car_changelog_items', 'checklist'])
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


    public function alarms()
    {
        $user = Auth::user();
        $today = Carbon::now();
        $expirationThreshold = $today->copy()->addDays(7);

        if ($user->role_id == 1) {
            $cars = Car::whereHas('car_document', function ($query) use ($expirationThreshold) {
                $query->where('technical_review_date', '<=', $expirationThreshold)
                    ->orWhere('soat_date', '<=', $expirationThreshold)
                    ->orWhere('insurance_date', '<=', $expirationThreshold);
            })
                ->orWhereHas('car_changelogs', function ($query) {
                    $query->whereNull('is_accepted');
                })
                ->get();
        } else {
            $cars = Car::where('user_id', $user->id)
                ->whereHas('car_document', function ($query) use ($expirationThreshold) {
                    $query->where('technical_review_date', '<=', $expirationThreshold)
                        ->orWhere('soat_date', '<=', $expirationThreshold)
                        ->orWhere('insurance_date', '<=', $expirationThreshold);
                })
                ->orWhereHas('car_changelogs', function ($query) {
                    $query->whereNull('is_accepted');
                })
                ->get();
        }


        return response()->json([
            'documentsCarToExpire' => $cars,
        ], 200);
    }

    public function store(FleetCarRequest $request)
    {
        $data = $request->validated();
        try {
            if ($request->hasFile('photo')) {
                $data['photo'] = $this->saveImage($request->file('photo'), 'image/car/', 'car');
            }
            $car = Car::create($data);
            $car->load(['user', 'costline', 'car_changelogs.car_changelog_items', 'checklist']);
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

    public function update(FleetCarRequest $request, Car $car)
    {
        $data = $request->validated();
        $data['year'] = intval($data['year']);
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
        $car->load(['user', 'costline', 'car_changelogs.car_changelog_items', 'checklist']);
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
                    $data[$archive] = uniqid() . '_' . time() . '_' . $document->getClientOriginalName();
                    $document->move(public_path('documents/fleetcar/car_documents/'), $data[$archive]);
                }
            }
            $carDocument = CarDocument::create($data);
            return response()->json($carDocument, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function updateDocument(FleetCarDocumentRequest $request, CarDocument $carDocument)
    {
        $data = $request->validated();
        $archives = ['ownership_card', 'technical_review', 'soat', 'insurance'];
        $user = Auth::user();
        if ($user->role_id == 1) {
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
                    $data[$archive] = uniqid() . '_' . time() . '_' . $document->getClientOriginalName();
                    $document->move(public_path('documents/fleetcar/car_documents/'), $data[$archive]);
                }
            }
            $carDocument->update($data);
            $carDocument->load("approvel_car_document");
            return response()->json($carDocument, 200);
        } else {
            foreach ($archives as $archive) {
                if ($request->hasFile($archive)) {
                    $document = $request->file($archive);
                    $data[$archive] = uniqid() . '_' . time() . '_' . $document->getClientOriginalName();
                    $document->move(public_path('documents/fleetcar/car_documents/'), $data[$archive]);
                }
            }
            $data['car_document_id'] = $carDocument->id;
            ApprovalCarDocument::create($data);
            return response()->json([], 200);
        }
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
            $data['invoice'] = uniqid() . '_' . time() . '_' . $document->getClientOriginalName();
            $document->move(public_path('documents/fleetcar/invoices'), $data['invoice']);
        }
        $data['car_id'] = $car->id;
        $carChangelog = CarChangelog::create($data);
        if (!empty($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {
                CarChangelogItem::create([
                    'name' => $item,
                    'car_changelog_id' => $carChangelog->id
                ]);
            }
        }

        return response()->json($car->load(['user', 'costline', 'car_changelogs' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }, 'car_changelogs.car_changelog_items', 'checklist']));
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
            $data['invoice'] = uniqid() . '_' . time() . '_' . $document->getClientOriginalName();
            $document->move(public_path('documents/fleetcar/invoices'), $data['invoice']);
        }
        $carChangelog->update($data);

        if (!empty($data['items']) && is_array($data['items'])) {
            CarChangelogItem::where('car_changelog_id', $carChangelog->id)->delete();
            foreach ($data['items'] as $item) {
                CarChangelogItem::create([
                    'name' => $item,
                    'car_changelog_id' => $carChangelog->id
                ]);
            }
        }
        $car = Car::find($carChangelog->car_id);
        return response()->json($car->load(['user', 'costline', 'car_changelogs' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }, 'car_changelogs.car_changelog_items', 'checklist']));
    }

    public function destroyChangelog(CarChangelog $carChangelog)
    {
        $car = Car::find($carChangelog->car_id);
        $fileName = $carChangelog->invoice;
        $file_path = "documents/fleetcar/invoices/$fileName";
        if (file_exists(public_path($file_path))) {
            unlink(public_path($file_path));
        }
        $carChangelog->delete();
        return response()->json($car->load(['user', 'costline', 'car_changelogs' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }, 'car_changelogs.car_changelog_items', 'checklist']));
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

    //checklist
    public function showChecklist(Car $car)
    {
        $checklist = ChecklistCar::where('car_id', $car->id)->orderBy('created_at', 'desc')->paginate();
        return Inertia::render('FleetCar/CheckList', [
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
        $changes = ApprovalCarDocument::with([
            'car_document:id,car_id',
            'car_document.car:id,plate'
        ])->get();
        return Inertia::render("FleetCar/IndexApprovals", [
            'change' => $changes
        ]);
    }

    public function approveChanges($id)
    {
        $archives = ['ownership_card', 'technical_review', 'soat', 'insurance'];
        try {
            $approve_car = ApprovalCarDocument::find($id);
            if ($approve_car) {
                $car = CarDocument::find($approve_car->car_document_id);
                foreach ($archives as $archive) {
                    if ($car->$archive && $car->$archive !== $approve_car->$archive) {
                        $fileName = $car->$archive;
                        $file_path = "documents/fleetcar/car_documents/$fileName";
                        if (file_exists(public_path($file_path))) {
                            unlink(public_path($file_path));
                        }
                    }
                }
                $car->update($approve_car->toArray());

                $approve_car->delete();
                $pendingApprovals = ApprovalCarDocument::where('car_document_id', $car->id)->get();

                foreach ($pendingApprovals as $approval) {
                    foreach ($archives as $item) {
                        if ($approval->$item) {
                            $file_path = "documents/fleetcar/car_documents/{$approval->file_name}";
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
        $archives = ['ownership_card', 'technical_review', 'soat', 'insurance'];
        $changes = ApprovalCarDocument::find($id);
        foreach ($archives as $item) {
            if ($changes->$item) {
                $file_path = "documents/fleetcar/car_documents/{$changes->$item}";
                if (file_exists(public_path($file_path))) {
                    unlink(public_path($file_path));
                }
            }
        }
        $changes->delete();
        return response()->json([], 200);
    }

    public function approveAlarms()
    {
        $approval = CarDocument::whereHas('approvel_car_document')->get();
        return response()->json($approval, 200);
    }
    public function acceptOrDecline(CarChangelog $changelog, $is_accepted)
    {
        $user = Auth::user();
        if ($user->role_id !== 1) {
            abort(403, 'Acción no permitida');
        }

        $car = Car::where('id', $changelog->car_id)
            ->with([
                'user',
                'costline',
                'car_changelogs' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                },
                'car_changelogs.car_changelog_items',
                'checklist'
            ])
            ->first();

        $changelog->update(['is_accepted' => $is_accepted]);

        return response()->json($car);
    }
}
