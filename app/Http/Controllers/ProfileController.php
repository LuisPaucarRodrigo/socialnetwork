<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\AdditionalCost;
use App\Models\PayrollDetailExpense;
use App\Models\GeneralExpense;
use App\Models\Contract;
use App\Models\PextProjectExpense;
use App\Models\StaticCost;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    // public function allFine(){
    //     $aditionalCostsData = PextProjectExpense::with('project')->where('general_expense_id', null)->get();
    //     foreach($aditionalCostsData as $item){
    //         $ge = GeneralExpense::create([
    //             'zone' => $item->zone,
    //             'expense_type' => $item->expense_type,
    //             'location' => $item?->project?->description ?? 'Sin descripción',
    //             'amount' => $item->amount,
    //             'operation_number' => $item->operation_number,
    //             'operation_date' => $item->operation_date,
    //             'account_statement_id' => $item->account_statement_id,
    //         ]);
    //         $item->update(['general_expense_id'=> $ge->id]);
    //     }
        
    //     return response()->json('siuuu');
    // }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
