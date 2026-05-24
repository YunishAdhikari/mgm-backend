<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::insert([
            ['name' => 'admin'],
            ['name' => 'manager'],
            ['name' => 'Assistant Manger'],
            ['name' => 'Duty Manager'],
            ['name' => 'supervisor'],
            ['name' => 'Housekeeper'],
            ['name' => 'chef'],
            ['name' => 'Kitchen Porter'],
            ['name' => 'Staff'],
            ['name' => 'Linen Porter'],
            ['name' => 'Public Area'],
            ['name' => 'Head chef'],
        ]);
    }
}
