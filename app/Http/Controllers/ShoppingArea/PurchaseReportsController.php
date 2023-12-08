<?php

namespace App\Http\Controllers\ShoppingArea;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PurchaseReportsController extends Controller
{
    public function index()
    {
        return Inertia::render('ShoppingArea/PurchasingReports');
    }
}
