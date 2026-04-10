<?php

namespace Workdo\Account\Database\Seeders;

use Workdo\Account\Models\RevenueCategories;
use Illuminate\Database\Seeder;
use Workdo\Account\Models\ChartOfAccount;


class DemoRevenueCategoriesSeeder extends Seeder
{
    public function run($userId): void
    {
        if (RevenueCategories::where('created_by', $userId)->exists()) {
            return;
        }

        // PT Bojeri — revenue categories mapped to Bojeri CoA
        $revenueCategories = [
            ['category_name' => 'Pendapatan Penjualan Furnitur', 'category_code' => 'REV-001', 'description' => 'Penjualan sofa, meja, lemari, dan produk furnitur lainnya', 'gl_code' => '4-1001'],
            ['category_name' => 'Pendapatan Jasa',               'category_code' => 'REV-002', 'description' => 'Jasa pemasangan, custom order, dan pengiriman furnitur',  'gl_code' => '4-1002'],
        ];

        foreach ($revenueCategories as $category) {
            $glId = ChartOfAccount::where('created_by', $userId)
                ->where('account_code', $category['gl_code'])
                ->value('id');

            RevenueCategories::updateOrCreate(
                ['category_code' => $category['category_code'], 'created_by' => $userId],
                [
                    'category_name' => $category['category_name'],
                    'description'   => $category['description'],
                    'is_active'     => true,
                    'gl_account_id' => $glId,
                    'creator_id'    => $userId,
                ]
            );
        }
    }
}
