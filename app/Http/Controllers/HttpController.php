<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class HttpController extends Controller
{
    public function sunat_ruc() {
        $token = 'apis-token-7518.LyKvUJVQj1RPzGRuqba9YEeYdDTFF-yX';
        $number = '10766648814';
        $client = new Client(['base_uri' => 'https://api.apis.net.pe', 'verify' => false]);
        $parameters = [
            'http_errors' => false,
            'connect_timeout' => 5,
            'headers' => [
                'Authorization' => 'Bearer '.$token,
                'Referer' => 'https://apis.net.pe/api-consulta-ruc',
                'User-Agent' => 'laravel/guzzle',
                'Accept' => 'application/json',
            ],
            'query' => ['numero' => $number]
        ];
        // Para usar la versión 1 de la api, cambiar a /v1/ruc
        $res = $client->request('GET', '/v2/sunat/ruc', $parameters);
        $response = json_decode($res->getBody()->getContents(), true);
        var_dump($response);
    }
}
