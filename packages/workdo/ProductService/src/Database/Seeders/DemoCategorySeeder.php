<?php

namespace Workdo\ProductService\Database\Seeders;

use Workdo\ProductService\Models\ProductServiceCategory;
use Illuminate\Database\Seeder;

class DemoCategorySeeder extends Seeder
{
    public function run($userId): void
    {
        if (!empty($userId)) {
            // PT Bojeri — furniture product & service categories
            $categories = [
                ['name' => 'Living Room',  'color' => '#3B82F6'],
                ['name' => 'Dining',       'color' => '#F59E0B'],
                ['name' => 'Office',       'color' => '#10B981'],
                ['name' => 'Bedroom',      'color' => '#8B5CF6'],
                ['name' => 'Kitchen',      'color' => '#F97316'],
                ['name' => 'Service',      'color' => '#06B6D4'],
            ];

            foreach ($categories as $category) {
                ProductServiceCategory::updateOrCreate(
                    ['name' => $category['name'], 'created_by' => $userId],
                    ['color' => $category['color'], 'creator_id' => $userId]
                );
            }
        }
    }
}
