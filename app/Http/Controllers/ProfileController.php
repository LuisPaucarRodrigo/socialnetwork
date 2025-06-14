<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Subdivision;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function allFine()
    {

        $subdivisions = Subdivision::whereIn('section_id', [1,4,5,6,7,8,9,10])->pluck('id');
        return response()->json($subdivisions);
        // $path = public_path('documents/documents');

        // if (!File::exists($path)) {
        //     return response()->json(['error' => 'Ruta no encontrada'], 404);
        // }
        // $files = File::files($path);
        // $fileNames = collect($files)->map(function ($file) {
        //     return $file->getFilename();
        // });
        // $orphans = [];
        // foreach ($fileNames as $name) {
        //     $reg = Document::where('title', $name)->first();
        //     if(!$reg)
        //     array_push($orphans, $name);
        // }

        // return response()->json($orphans);

        // $docregs = DocumentRegister::whereNull('subdivision_id')->get();

        // foreach($docregs as $item) {
        //     $docitem = Document::find($item->document_id);
        //     $residual = DocumentRegister::where('subdivision_id', $docitem->subdivision_id)
        //         ->where('employee_id', $docitem->employee_id)->whereNull('document_id')->first();
        //        if($residual) $residual->delete();
        //     $item->update([
        //         'subdivision_id' => $docitem->subdivision_id
        //     ]);
        // }
        // return response()->json('all fine');

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
