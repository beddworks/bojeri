<?php

namespace Workdo\Account\Database\Seeders;

use Workdo\Account\Models\ExpenseCategories;
use Illuminate\Database\Seeder;
use Workdo\Account\Models\ChartOfAccount;

class DemoExpenseCategoriesSeeder extends Seeder
{
    public function run($userId): void
    {
        if (ExpenseCategories::where('created_by', $userId)->exists()) {
            return;
        }

        // PT Bojeri — expense categories mapped to Bojeri CoA
        $expenseCategories = [
            ['category_name' => 'Biaya Sewa Gudang',           'category_code' => 'EXP-001', 'description' => 'Biaya sewa gudang produksi dan gudang regional',              'gl_code' => '5-2002'],
            ['category_name' => 'Biaya Listrik & Air',         'category_code' => 'EXP-002', 'description' => 'Tagihan utilitas listrik dan air seluruh lokasi',             'gl_code' => '5-2003'],
            ['category_name' => 'Biaya Transport & Pengiriman','category_code' => 'EXP-003', 'description' => 'Ongkos kirim furnitur ke pelanggan dan antar lokasi',         'gl_code' => '5-2004'],
            ['category_name' => 'Biaya Pemasaran',             'category_code' => 'EXP-004', 'description' => 'Iklan, pameran furniture, dan materi promosi',                 'gl_code' => '5-2005'],
            ['category_name' => 'Biaya BPJS',                  'category_code' => 'EXP-005', 'description' => 'Iuran BPJS Ketenagakerjaan dan BPJS Kesehatan perusahaan',     'gl_code' => '5-3001'],
        ];

        foreach ($expenseCategories as $category) {
            $glId = ChartOfAccount::where('created_by', $userId)
                ->where('account_code', $category['gl_code'])
                ->value('id');

            ExpenseCategories::updateOrCreate(
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
