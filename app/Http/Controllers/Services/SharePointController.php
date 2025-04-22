<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Inertia\Inertia;

class SharePointController extends Controller
{
    public function getAccessToken()
    {
        $client = new Client();

        // Solicitar el token de acceso usando las credenciales de cliente
        $response = $client->post('https://login.microsoftonline.com/' . env('MICROSOFT_TENANT_ID') . '/oauth2/v2.0/token', [
            'form_params' => [
                'client_id' => env('MICROSOFT_CLIENT_ID'),
                'client_secret' => env('MICROSOFT_CLIENT_SECRET'),
                'scope' => env('MICROSOFT_SCOPE'),
                'grant_type' => 'client_credentials',
            ]
        ]);

        $body = json_decode($response->getBody());
        return $body->access_token;
    }

    public function index()
    {
        $accessToken = $this->getAccessToken();
        $client = new Client();
        $siteId = 'ccip1985.sharepoint.com,acf3ab51-6a0a-4058-b226-16b62b3b9e83,59d75c2a-3c66-436b-b177-b50b81a17a48';
        $driveId = 'b!UavzrApqWECyJha2Kzuegypc11lmPGtDsXe1C4GhekjuGlgZcm4eQr45JFRCk8Nj';
        $response = $client->get("https://graph.microsoft.com/v1.0/sites/{$siteId}/drives/{$driveId}/root/children", [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ],
        ]);
        $content = json_decode($response->getBody(), true);
        return Inertia::render('SharePoint/index', [
            'siteId' => $siteId,
            'driveId' => $driveId,
            'accessToken' => $accessToken,
            'data' => $content
        ]);
    }
}
