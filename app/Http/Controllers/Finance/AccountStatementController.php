<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountStatementController extends Controller
{
    public function index () {
        // $accountStatuses = A
        return Inertia::render('Finance/AccountStatement/AccountStatement');
    }
}
