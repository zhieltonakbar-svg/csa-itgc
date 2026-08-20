<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\ItCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ---------------------------------------------------------------
        // Users
        // ---------------------------------------------------------------
        // ---------------------------------------------------------------
        // Users — 4 roles
        // ---------------------------------------------------------------
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'Admin User', 'password' => Hash::make('password123'), 'role' => 'admin']
        );

        User::updateOrCreate(
            ['email' => 'officer@gmail.com'],
            ['name' => 'Officer (Creator)', 'password' => Hash::make('password123'), 'role' => 'creator']
        );

        User::updateOrCreate(
            ['email' => 'manager@gmail.com'],
            ['name' => 'Manager (Reviewer)', 'password' => Hash::make('password123'), 'role' => 'reviewer']
        );

        User::updateOrCreate(
            ['email' => 'seniormanager@gmail.com'],
            ['name' => 'Senior Manager (Approver)', 'password' => Hash::make('password123'), 'role' => 'approver']
        );

        // ---------------------------------------------------------------
        // IT Categories master data (sourced from Excel master data)
        // ---------------------------------------------------------------
        $categories = [
            [
                'name'        => 'Access to Programs & Data',
                'icon'        => 'bi-shield-lock',
                'description' => 'Assess and evaluate controls related to user access, authorization, and access to IT programs and data.',
            ],
            [
                'name'        => 'Program Change',
                'icon'        => 'bi-gear',
                'description' => 'Assess and evaluate controls for managing, testing, approving, and implementing changes to IT programs.',
            ],
            [
                'name'        => 'Computer Operations',
                'icon'        => 'bi-pc-display',
                'description' => 'Assess and evaluate controls related to IT operations, system monitoring, backups, and operational activities.',
            ],
            [
                'name'        => 'Program Development',
                'icon'        => 'bi-code-square',
                'description' => 'Assess and evaluate controls related to the development, testing, approval, and implementation of IT applications.',
            ],
        ];

        foreach ($categories as $cat) {
            ItCategory::firstOrCreate(['name' => $cat['name']], $cat);
        }

        // ---------------------------------------------------------------
        // Applications master data (sourced from Excel master data)
        // ---------------------------------------------------------------

        // SAP S/4HANA — 4 IT Categories
        $sap = Application::firstOrCreate(
            ['name' => 'SAP S/4HANA'],
            ['description' => 'SAP S/4HANA ERP System', 'is_active' => true]
        );

        // Statuses sourced from the Excel template screenshot:
        //   Access to Programs & Data → Partial Completed
        //   Program Change            → Completed
        //   Computer Operations       → Not Completed
        //   Program Development       → Not Completed
        $sap->itCategories()->syncWithoutDetaching([
            ItCategory::where('name', 'Access to Programs & Data')->first()->id => ['completion_status' => 'partial'],
            ItCategory::where('name', 'Program Change')->first()->id             => ['completion_status' => 'complete'],
            ItCategory::where('name', 'Computer Operations')->first()->id        => ['completion_status' => 'not_complete'],
            ItCategory::where('name', 'Program Development')->first()->id        => ['completion_status' => 'not_complete'],
        ]);

        // SalesForce — 2 IT Categories
        $sf = Application::firstOrCreate(
            ['name' => 'SalesForce'],
            ['description' => 'Salesforce CRM System', 'is_active' => true]
        );

        $sf->itCategories()->syncWithoutDetaching([
            ItCategory::where('name', 'Access to Programs & Data')->first()->id => ['completion_status' => 'not_complete'],
            ItCategory::where('name', 'Program Change')->first()->id             => ['completion_status' => 'partial'],
        ]);
        // ---------------------------------------------------------------
        // (Data Control ditiadakan agar user bisa upload sendiri)
        // ---------------------------------------------------------------
    }
}
