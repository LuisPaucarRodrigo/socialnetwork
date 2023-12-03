<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Employee;
use App\Models\Pension;
use App\Models\Purchasing_request;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'luis',
            'dni' => '70969005',
            'email' => 'luis@gmail.com',
            'password' => Hash::make('12345678'),
            'platform' => 'web'
        ]);
        User::factory()->count(20)->create();
        Purchasing_request::factory()->count(10)->create();
        Provider::factory()->count(10)->create();
        $data = [
            [
                'type' => 'HABITAT',
                'values' => 0.0147,
            ],
            [
                'type' => 'INTEGRA',
                'values' => 0.0155,
            ],
            [
                'type' => 'PRIMA',
                'values' =>0.0160,
            ],
            [
                'type' => 'PROFUTURO',
                'values' => 0.0169,
            ],
            [
                'type' => 'HABITATMX',
                'values' => 0,           
            ],
            [
                'type' => 'INTEGRAMX',
                'values' => 0,       
            ],
            [
                'type' => 'PRIMAMX',
                'values' => 0,     
            ],
            [
                'type' => 'PROFUTUROMX',
                'values' => 0,  
            ],
        ];
        Pension::insert($data);
        // Employee::factory()->count(1000)->create();
    }
}
