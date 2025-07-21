<?php

namespace App\Support\RouteDefinitions\Projects;

use App\Http\Controllers\ProjectArea\CustomersController;

class CustomersRoutes
{
    public static function all(): array
    {
        return [
            // Customers
            [
                'uri' => 'customers/post',
                'method' => 'post',
                'action' => [CustomersController::class, 'store'],
                'permission' => true,
                'name' => 'customers.store',
            ],
            [
                'uri' => 'customers/{customer}/update',
                'method' => 'put',
                'action' => [CustomersController::class, 'update'],
                'permission' => true,
                'name' => 'customers.update',
            ],
            [
                'uri' => 'customers/{customer}/destroy',
                'method' => 'delete',
                'action' => [CustomersController::class, 'destroy'],
                'permission' => true,
                'name' => 'customers.destroy',
            ],

            // Customers contacts
            [
                'uri' => 'customers/{customer}/contacts',
                'method' => 'get',
                'action' => [CustomersController::class, 'show_contacts'],
                'permission' => true,
                'name' => 'customers.contacts.index',
            ],
            [
                'uri' => 'customers/{customer}/contacts/post',
                'method' => 'post',
                'action' => [CustomersController::class, 'store_contact'],
                'permission' => true,
                'name' => 'customers.contacts.store',
            ],
            [
                'uri' => 'customers/{customer}/contacts/{customer_contact}/update',
                'method' => 'put',
                'action' => [CustomersController::class, 'update_contact'],
                'permission' => true,
                'name' => 'customers.contacts.update',
            ],
            [
                'uri' => 'customers/{customer}/contacts/{customer_contact}/destroy',
                'method' => 'delete',
                'action' => [CustomersController::class, 'destroy_contact'],
                'permission' => true,
                'name' => 'customers.contacts.destroy',
            ],
            [
                'uri' => '/customers',
                'method' => 'any',
                'action' => [CustomersController::class, 'index'],
                'permission' => true,
                'name' => 'customers.index',
            ],
            [
                'uri' => '/customers/search',
                'method' => 'get',
                'action' => [CustomersController::class, 'index'],
                'permission' => true,
                'name' => 'customers.search',
            ],
        ];
    }
}
