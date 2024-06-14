<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use App\Models\Customers_contact;
use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectPintController extends Controller
{

    public function pint_create_project()
    {
        $ids = [3, 4];
        $contacts_cicsa = Customers_contact::where('customer_id', 1)->get();
        $services = Service::whereIn('id', $ids)->get();
        return Inertia::render(
            'ProjectArea/PreProject/CreateProjectPint', [
                'contacts_cicsa' => $contacts_cicsa,
                'services' => $services
            ]
        );
    }
}
