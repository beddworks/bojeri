<?php

namespace Workdo\ProductService\Database\Seeders;

use Workdo\ProductService\Models\ProductServiceUnit;
use Illuminate\Database\Seeder;

class DemoUnitSeeder extends Seeder
{
    public function run($userId): void
    {
        if (!empty($userId)) {
            // PT Bojeri — units for furniture products & services
            $units = [
                'Unit',     // individual furniture piece
                'Set',      // furniture sets (dining set, kitchen set)
                'Proyek',   // project-based custom orders
                'Trip',     // delivery trip
                'Meter',    // fabric / material by length
                'Lembar',   // panel / plywood sheets
                'Batang',   // metal rod / wood rod
                'Jam',      // hourly service
                'Hari',     // daily service
            ];

            foreach ($units as $unit) {
                ProductServiceUnit::updateOrCreate(
                    ['unit_name' => $unit, 'created_by' => $userId],
                    ['creator_id' => $userId]
                );
            }
        }
    }
}