<?php

namespace Workdo\Hrm\Database\Seeders;

use Illuminate\Database\Seeder;
use Workdo\Hrm\Models\Department;
use Workdo\Hrm\Models\Branch;

class DemoDepartmentSeeder extends Seeder
{
    public function run($userId): void
    {
        if (Department::where('created_by', $userId)->exists()) {
            return; // Skip seeding if data already exists
        }
        $branches = Branch::where('created_by', $userId)->get()->keyBy('branch_name');

        if ($branches->isEmpty()) {
            return;
        }

        // PT Bojeri department mapping per branch
        $branchDepartments = [
            'Head Office'   => ['Sales', 'Design', 'Production', 'Warehouse', 'Finance & HR', 'CRM'],
            'North Branch'  => ['Sales', 'Production', 'Warehouse'],
            'South Branch'  => ['Sales', 'Production', 'Warehouse'],
        ];

        foreach ($branchDepartments as $branchName => $departments) {
            $branch = $branches->get($branchName);
            if (!$branch) continue;

            foreach ($departments as $departmentName) {
                Department::updateOrCreate(
                    [
                        'department_name' => $departmentName,
                        'branch_id'       => $branch->id,
                        'created_by'      => $userId,
                    ],
                    ['creator_id' => $userId]
                );
            }
        }
    }
}