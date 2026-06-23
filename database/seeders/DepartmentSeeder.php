<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $globalDepartments = [
            'Admin',
            'DOPS',
            'HR',
            'Project Engineers',
        ];

        foreach ($globalDepartments as $department) {
            Department::updateOrCreate(
                [
                    'hotel_id' => null,
                    'name' => $department,
                ],
                []
            );
        }
    }
}