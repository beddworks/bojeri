<?php

namespace Workdo\ProductService\Database\Seeders;

use Workdo\ProductService\Models\ProductServiceTax;
use Illuminate\Database\Seeder;

class DemoTaxSeeder extends Seeder
{
    public function run($userId): void
    {
        if (!empty($userId)) {
            // PT Bojeri — Indonesian VAT (PPN)
            $taxes = [
                ['tax_name' => 'PPN', 'rate' => 11.00],
            ];

            foreach ($taxes as $tax) {
                ProductServiceTax::updateOrCreate(
                    ['tax_name' => $tax['tax_name'], 'created_by' => $userId],
                    ['rate' => $tax['rate'], 'creator_id' => $userId]
                );
            }
        }
    }
}
