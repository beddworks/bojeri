<?php

namespace Workdo\Hrm\Database\Seeders;

use Illuminate\Database\Seeder;
use Workdo\Hrm\Models\Branch;

class DemoBranchSeeder extends Seeder
{
    public function run($userId)
    {

        if (Branch::where('created_by', $userId)->exists()) {
            return; // Skip seeding if data already exists
        }
        // PT Bojeri — 3 branches
        $branches = [
            ['branch_name' => 'Head Office',    'created_by' => $userId, 'creator_id' => $userId],
            ['branch_name' => 'North Branch',   'created_by' => $userId, 'creator_id' => $userId],
            ['branch_name' => 'South Branch',   'created_by' => $userId, 'creator_id' => $userId],
        ];

        foreach ($branches as $branch) {
            Branch::updateOrCreate(
                ['branch_name' => $branch['branch_name'], 'created_by' => $userId],
                ['creator_id' => $userId]
            );
        }
    }
}