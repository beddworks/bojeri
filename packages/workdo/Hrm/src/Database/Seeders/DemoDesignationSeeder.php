<?php

namespace Workdo\Hrm\Database\Seeders;

use Illuminate\Database\Seeder;
use Workdo\Hrm\Models\Branch;
use Workdo\Hrm\Models\Department;
use Workdo\Hrm\Models\Designation;

class DemoDesignationSeeder extends Seeder
{
    public function run($userId): void
    {
        if (Designation::where('created_by', $userId)->exists()) {
            return; // Skip seeding if data already exists
        }

        $branches = Branch::where('created_by', $userId)->get()->keyBy('branch_name');

        if ($branches->isEmpty()) {
            return;
        }

        // Helper: get department id by name + branch
        $getDept = function (string $deptName, int $branchId) use ($userId): ?int {
            return Department::where('created_by', $userId)
                ->where('branch_id', $branchId)
                ->where('department_name', $deptName)
                ->value('id');
        };

        // PT Bojeri designations — [designation_name, department, branch_key]
        // branch_key: 'HQ', 'NBD', 'SBY', 'ALL_BRANCHES', 'BRANCHES_ONLY'
        $designations = [
            // ── Head Office ──
            ['name' => 'Chief Executive Officer', 'dept' => 'Finance & HR', 'branches' => ['Head Office']],
            ['name' => 'Sales Manager',           'dept' => 'Sales',        'branches' => ['Head Office']],
            ['name' => 'Sales Executive',         'dept' => 'Sales',        'branches' => ['Head Office', 'North Branch', 'South Branch']],
            ['name' => 'Sales Representative',    'dept' => 'Sales',        'branches' => ['Head Office']],
            ['name' => 'POS Cashier',             'dept' => 'Sales',        'branches' => ['Head Office', 'North Branch', 'South Branch']],
            ['name' => 'Design Manager',          'dept' => 'Design',       'branches' => ['Head Office']],
            ['name' => 'Senior Designer',         'dept' => 'Design',       'branches' => ['Head Office']],
            ['name' => 'Junior Designer',         'dept' => 'Design',       'branches' => ['Head Office']],
            ['name' => '3D Render Artist',        'dept' => 'Design',       'branches' => ['Head Office']],
            ['name' => 'Production Manager',      'dept' => 'Production',   'branches' => ['Head Office']],
            ['name' => 'Production Supervisor',   'dept' => 'Production',   'branches' => ['Head Office']],
            ['name' => 'Floor Supervisor',        'dept' => 'Production',   'branches' => ['North Branch', 'South Branch']],
            ['name' => 'Craftsman',               'dept' => 'Production',   'branches' => ['Head Office', 'North Branch', 'South Branch']],
            ['name' => 'Warehouse Manager',       'dept' => 'Warehouse',    'branches' => ['Head Office']],
            ['name' => 'Inventory Controller',    'dept' => 'Warehouse',    'branches' => ['Head Office']],
            ['name' => 'Store Keeper',            'dept' => 'Warehouse',    'branches' => ['North Branch', 'South Branch']],
            ['name' => 'Logistics Staff',         'dept' => 'Warehouse',    'branches' => ['Head Office', 'North Branch', 'South Branch']],
            ['name' => 'Finance Manager',         'dept' => 'Finance & HR', 'branches' => ['Head Office']],
            ['name' => 'HR Manager',              'dept' => 'Finance & HR', 'branches' => ['Head Office']],
            ['name' => 'Accountant',              'dept' => 'Finance & HR', 'branches' => ['Head Office']],
            ['name' => 'Payroll Staff',           'dept' => 'Finance & HR', 'branches' => ['Head Office']],
            ['name' => 'Branch Sales Head',       'dept' => 'Sales',        'branches' => ['North Branch', 'South Branch']],
            ['name' => 'Branch Manager',          'dept' => 'Sales',        'branches' => ['North Branch', 'South Branch']],
            ['name' => 'CRM Manager',             'dept' => 'CRM',          'branches' => ['Head Office']],
            ['name' => 'Lead Specialist',         'dept' => 'CRM',          'branches' => ['Head Office']],
        ];

        foreach ($designations as $row) {
            foreach ($row['branches'] as $branchName) {
                $branch = $branches->get($branchName);
                if (!$branch) continue;

                $deptId = $getDept($row['dept'], $branch->id);
                if (!$deptId) continue;

                Designation::updateOrCreate(
                    [
                        'designation_name' => $row['name'],
                        'branch_id'        => $branch->id,
                        'department_id'    => $deptId,
                        'created_by'       => $userId,
                    ],
                    ['creator_id' => $userId]
                );
            }
        }
    }
}