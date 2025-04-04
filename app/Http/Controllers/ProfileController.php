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
    public function allFine(){
        foreach (DocumentsPermissions::cases() as $status) {
            Permission::create(['name' => $status->value]);
        }
        foreach (HumanResourcesPermissions::cases() as $status) {
            Permission::create(['name' => $status->value]);
        }
        foreach (InventoryPermissions::cases() as $status) {
            Permission::create(['name' => $status->value]);
        }
        foreach (ProjectPermissions::cases() as $status) {
            Permission::create(['name' => $status->value]);
        }
        foreach (PurchasingPermissions::cases() as $status) {
            Permission::create(['name' => $status->value]);
        }
        foreach (UserRolePermissions::cases() as $status) {
            Permission::create(['name' => $status->value]);
        }
        
        return response()->json('siuu');
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
