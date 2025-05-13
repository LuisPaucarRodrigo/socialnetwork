<?php

namespace App\Http\Controllers;

use App\Enums\Permissions\DocumentsPermissions;
use App\Enums\Permissions\HumanResourcesPermissions;
use App\Enums\Permissions\InventoryPermissions;
use App\Enums\Permissions\ProjectPermissions;
use App\Enums\Permissions\PurchasingPermissions;
use App\Enums\Permissions\UserRolePermissions;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Permission;
use App\Models\Project;
use App\Models\CicsaAssignation;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Exception;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function allFine()
    {
        // $data = Project::with('cicsa_assignation', 'preproject.customer')
        //      ->where('cost_center_id', 2)
        //      ->whereIn('id', [372, 425, 452])
        //      ->get();

        // DB::beginTransaction();
        // try {
        //     foreach ($data as $project) {
        //         $preproject = $project->preproject;
        //         CicsaAssignation::create([
        //             "assignation_date" => $preproject->date,
        //             "project_name" => $project->description,
        //             "customer" => $preproject->customer->business_name,
        //             "project_code" => $preproject->code,
        //             "cpe" => $preproject->cpe,
        //             "zone" => 'Arequipa',
        //             "zone2" => null,
        //             "manager" => 'Nikol Sheyla Rondón Neyra',
        //             "user_name" => 'Nikol Sheyla Rondón Neyra',
        //             "user_id" => 6,
        //             "project_id" => $project->id,
        //         ]);
        //     }
        //     DB::commit();
        //     return response()->json('all fine');
        // } catch (Exception $e) {
        //     DB::rollBack();
        //     return $e->getMessage();
        // }

    }

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
