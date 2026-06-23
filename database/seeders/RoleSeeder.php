<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
$roles = [

    // Platform
    'admin',

    // Executive Management
    'general-manager',
    'director-of-operations',
    'operations-manager',
    'director-of-sales',
    'financial-controller',
    'human-resources-manager',
    'it-manager',
    'project-engineer',

    // Hotel Management
    'manager',
    'assistant-manager',
    'duty-manager',
    'supervisor',

    // Front Office
    'front-office-manager',
    'receptionist',
    'night-auditor',
    'night-porter',
    'reservations',

    // Housekeeping
    'executive-housekeeper',
    'housekeeping-supervisor',
    'housekeeper',
    'linen-porter',
    'public-area-attendant',

    // Maintenance
    'chief-engineer',
    'maintenance-manager',
    'maintenance-technician',

    // Food & Beverage
    'food-and-beverage-manager',
    'restaurant-manager',
    'restaurant-supervisor',
    'restaurant-staff',
    'bartender',
    'bar-supervisor',

    // Kitchen
    'executive-chef',
    'head-chef',
    'sous-chef',
    'chef-de-partie',
    'commis-chef',
    'kitchen-supervisor',
    'kitchen-porter',

    // Sales & Finance
    'sales-executive',
    'finance',
    'accountant',

    // Other Operations
    'security',
    'spa-therapist',
    'pool-attendant',
];
        foreach ($roles as $role) {

            Role::updateOrCreate(
                ['name' => $role],
                []
            );

        }
    }
}