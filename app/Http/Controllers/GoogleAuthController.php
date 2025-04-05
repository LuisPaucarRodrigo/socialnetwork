<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Drive;
use Illuminate\Support\Facades\Auth;


class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        $client = new Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $client->addScope(Drive::DRIVE_METADATA_READONLY);
        $client->setAccessType('offline');

        return redirect()->to($client->createAuthUrl());
    }

    public function handleGoogleCallback()
    {
        $client = new Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));

        $token = $client->fetchAccessTokenWithAuthCode(request('code'));
        Auth::user()->update([
            'google_drive_token' => $token['access_token'],
            'google_drive_refresh_token' => $token['refresh_token'],
        ]);

        return redirect(route('users.index'));
    }
}
