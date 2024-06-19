<?php

namespace App\Http\Controllers;

use App\Services\WebScraper;

class ScraperController extends Controller
{
    protected $webScraper;

    public function __construct(WebScraper $webScraper)
    {
        $this->webScraper = $webScraper;
    }

    public function scrape()
    {
        $url = 'https://1at0-mx.teleows.com/portal-web/portal/homepage.html'; // URL estática de la página a scrapear

        $data = $this->webScraper->scrapeTable($url);

        return response()->json($data);
    }
}
