<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DepartmentSeeder::class,
            RoleSeeder::class,
        ]);

        $adminRole = Role::where('name', 'admin')->first();
        $adminDepartment = Department::where('name', 'admin')->first();

        User::updateOrCreate(
            ['email' => 'admin@mgm.com'],
            [
                'name' => 'Yunish',
                'password' => bcrypt('password'),
                'role_id' => $adminRole?->id,
                'department_id' => $adminDepartment?->id,
            ]
        );
    }
}