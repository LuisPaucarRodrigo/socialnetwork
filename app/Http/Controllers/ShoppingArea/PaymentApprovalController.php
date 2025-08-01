<?php

namespace App\Http\Controllers\ShoppingArea;

use App\Constants\PextConstants;
use App\Helpers\FileHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\ShoppingArea\PaymentApproval\PaymentApprovalRequest;
use App\Models\CostLine;
use App\Models\Provider;
use App\Models\ShoppingArea\PaymentApproval;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PaymentApprovalController extends Controller
{
    //
    public function index()
    {
        $zones = PextConstants::getZone();
        $costLines = CostLine::all();
        $providers = Provider::all();
        $users = User::whereHas('payment_approval')->get(['id', 'name']);
        $banks = ['BCP', 'INTERBANK', 'BBVA', 'SCOTIABANK'];
        return Inertia::render('ShoppingArea/PaymentApproval/index', [
            'costLines' => $costLines,
            'zones' => $zones,
            'providers' => $providers,
            'banks' => $banks,
            'users' => $users
        ]);
    }

    public function getPaymentApproval()
    {
        $data = PaymentApproval::with('cost_line', 'user:id,name')->latest()->paginate(20);
        $data->getCollection()->each(function ($item) {
            $item->append('state');
        });
        return response()->json($data, 200);
    }

    public function searchPaymentApproval(Request $request)
    {
        $data = PaymentApproval::with('cost_line', 'user:id,name')->latest();
        $searchQuery = $request->searchQuery;
        $data->where(function ($e) use ($searchQuery) {
            $e->where('account_number', 'like', "%$searchQuery%")
                ->orWhere('ruc', 'like', "%$searchQuery%")
                ->orWhere('beneficiary', 'like', "%$searchQuery%")
                ->orWhere('description', 'like', "%$searchQuery%");
        });
        $zone = $request->selectedZone;
        $costLine = $request->selectedCostLine;
        $user = $request->selectedUser;
        $banks = $request->selectedBanks;
        if (count($user) < 8) {
            $data->whereHas('user', function ($e) use ($user) {
                $e->whereIn('name', $user);
            });
        }
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
        $name = $validateData['ruc'] . '_' . $validateData['beneficiary'];
        if ($request->document) {
            $validateData['document'] = FileHandler::generateFilename($name, 'Documento');
        }
        $validateData['user_id'] = Auth::user()->id;
        $item = PaymentApproval::create($validateData)->fresh();
        if ($request->document) {
            $url = 'documents/shoppingArea/paymentApproval/';
            FileHandler::storeFile($request->file('document'), $url, $validateData['document']);
        }
        $item->append('state');
        $item->load('cost_line', 'user');
        return response()->json($item, 200);
    }

    public function document(Request $request, $id)
    {
        $validateData = $request->validate([
            'proof_payment' => 'required|file'
        ]);
        $payment = PaymentApproval::with('cost_line')->find($id);
        $name = $payment->ruc . '_' . $payment->beneficiary;
        $validateData['proof_payment'] = FileHandler::generateFilename($name, 'Constancia de Pago');
        try {
            $payment->update([
                'proof_payment' => $validateData['proof_payment'],
                'is_accepted' => 1,
            ]);
            $url = 'documents/shoppingArea/paymentApproval/';
            FileHandler::storeFile($request->file('proof_payment'), $url, $validateData['proof_payment']);
            $payment->append('state');
            return response()->json($payment, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function rejected(Request $request, $id)
    {
        $validateData = $request->validate([
            'reason_rejection' => 'required',
            'is_validated' => 'required'
        ]);
        $payment = PaymentApproval::with('cost_line')->find($id);
        $payment->update($validateData);
        $payment->append('state');
        return response()->json($payment, 200);
    }

    public function rejected_vericom($id)
    {
        $payment = PaymentApproval::with('cost_line')->find($id);
        $payment->update([
            'is_accepted' => 0,
        ]);
        $payment->append('state');
        return response()->json($payment, 200);
    }

    public function download_document($id, $kind)
    {
        $payment = PaymentApproval::find($id);
        return FileHandler::showFile('documents/shoppingArea/paymentApproval/', $payment->$kind);
    }

    public function delete($id)
    {
        $payment = PaymentApproval::find($id);
        $payment->delete();
        return response()->json([], 200);
    }

    public function pending_payments()
    {
        $payment = PaymentApproval::whereNull('is_validated')
            ->get();
        return response()->json($payment, 200);
    }

    public function validate_payment(Request $request, $id)
    {
        $validateData = $request->validate([
            'is_validated' => 'required'
        ]);
        $payment = PaymentApproval::with('cost_line')->find($id);
        $payment->update($validateData);
        $payment->append('state');
        return response()->json($payment, 200);
    }
}
