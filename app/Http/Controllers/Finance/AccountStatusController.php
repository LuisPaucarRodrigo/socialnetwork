<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountStatusController extends Controller
{
    public function index () {
        return Inertia::render('Finance/AccountStatus/AccountStatus');
    }
}
