<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DocumentSpreedSheetController extends Controller
{
    public function index () {
        return Inertia::render('HumanResource/DocumentSpreedSheet/Index');
    }
}
