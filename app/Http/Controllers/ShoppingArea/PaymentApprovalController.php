<?php

namespace App\Http\Controllers\ShoppingArea;

use App\Constants\PextConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\ShoppingArea\PaymentApproval\PaymentApprovalRequest;
use App\Models\CostLine;
use App\Models\Provider;
use App\Models\ShoppingArea\PaymentApproval;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentApprovalController extends Controller
{
    //
    public function index()
    {
        $zones = PextConstants::getZone();
        $costLines = CostLine::all();
        $providers = Provider::all();
        $banks = ['BCP', 'INTERBANK', 'BBVA', 'SCOTIABANK'];
        return Inertia::render('ShoppingArea/PaymentApproval/index', [
            'costLines' => $costLines,
            'zones' => $zones,
            'providers' => $providers,
            'banks' => $banks
        ]);
    }

    public function getPaymentApproval()
    {
        $data = PaymentApproval::with('cost_line')->paginate(20);
        $data->getCollection()->each(function ($item) {
            $item->append('state');
        });
        return response()->json($data, 200);
    }

    public function searchPaymentApproval(Request $request)
    {
        $data = PaymentApproval::with('cost_line');
        $searchQuery = $request->searchQuery;
        $data->where(function ($e) use ($searchQuery) {
            $e->where('account_number', 'like', "%$searchQuery%")
                ->orWhere('ruc', 'like', "%$searchQuery%")
                ->orWhere('beneficiary', 'like', "%$searchQuery%")
                ->orWhere('description', 'like', "%$searchQuery%");
        });
        $zone = $request->selectedZone;
        $costLine = $request->selectedCostLine;
        $banks = $request->selectedBanks;
        if (count($zone) < 7) {
            $data->whereIn('zone', $zone);
        }
        if (count($banks) < 5) {
            $data->whereIn('bank', $banks);
        }
        if (count($costLine) < 8) {
            $data->whereHas('cost_line', function ($e) use ($costLine) {
                $e->whereIn('name', $costLine);
            });
        }
        $data = $data->get();
        $data->each(function ($item) {
            $item->append('state');
        });
        $state = $request->selectedState;
        if (count($state) < 3) {
            $data = $data->filter(function ($item) use ($state) {
                return in_array($item->state, $state);
            })->values();
        }
        return response()->json($data, 200);
    }

    public function store(PaymentApprovalRequest $request)
    {
        $validateData = $request->validated();
        $item = PaymentApproval::create($validateData)->fresh();
        $item->append('state');
        $item->load('cost_line');
        return response()->json($item, 200);
    }

    public function document(Request $request, $id)
    {
        $validateData = $request->validate([
            'document' => 'required|file'
        ]);

        $url = 'documents/shoppingArea/paymentApproval/';
        $validateData['document'] = $this->storeArchives($request->file('document'), $url);

        $payment = PaymentApproval::find($id);
        $payment->update([
            'document' => $validateData['document']
        ]);
        $payment->append('state');
        $payment->load('cost_line');

        return response()->json($payment, 200);
    }

    private function storeArchives($file, $url)
    {
        $imageUrl = time() . '._' . $file->getClientOriginalName();
        $file->move(public_path($url), $imageUrl);
        return $imageUrl;
    }

    public function download_document($id)
    {
        $payment = PaymentApproval::find($id);
        $filePath = 'documents/shoppingArea/paymentApproval/' . $payment->document;
        $path = public_path($filePath);
        if (file_exists($path)) {
            return response()->file($path);
        }
    }

    public function delete($id)
    {
        $payment = PaymentApproval::find($id);
        $payment->delete();
        return response()->json([], 200);
    }

    public function pending_payments()
    {
        $payment = PaymentApproval::whereNull('document')->get();
        return response()->json($payment, 200);
    }

    public function validate_payment(Request $request, $id)
    {
        $validateData = $request->validate([
            'is_validated' => 'required'
        ]);
        $payment = PaymentApproval::find($id);
        $payment->update($validateData);
        return response()->json($payment, 200);
    }
}
