<?php

namespace Workdo\Account\Database\Seeders;

use Workdo\Account\Models\Revenue;
use Workdo\Account\Models\RevenueCategories;
use Workdo\Account\Models\BankAccount;
use Workdo\Account\Models\ChartOfAccount;
use Illuminate\Database\Seeder;

class DemoRevenueSeeder extends Seeder
{
    public function run($userId): void
    {
        if (Revenue::where('created_by', $userId)->exists()) {
            return;
        }

        // PT Bojeri — sample revenues based on Section 19 sample invoices (IDR)
        $revenues = [
            ['revenue_date' => '2025-01-05', 'amount' => 48000000,  'description' => 'Penjualan Sofa Lobby Hotel Santika Jakarta (10 unit + jasa pasang)', 'reference_number' => 'INV-2025-001', 'status' => 'posted',  'category_code' => 'REV-001', 'coa_code' => '4-1001'],
            ['revenue_date' => '2025-01-10', 'amount' => 12500000,  'description' => 'Penjualan Kursi Makan & Meja Café Kopi Nusantara',                    'reference_number' => 'INV-2025-002', 'status' => 'approved','category_code' => 'REV-001', 'coa_code' => '4-1001'],
            ['revenue_date' => '2025-01-15', 'amount' => 7200000,   'description' => 'Penjualan Kursi Kantor Ergonomis × 12 unit — PT Maju Bersama',        'reference_number' => 'INV-2025-003', 'status' => 'draft','category_code' => 'REV-001', 'coa_code' => '4-1001'],
            ['revenue_date' => '2025-01-28', 'amount' => 3750000,   'description' => 'Jasa Pemasangan & Pengiriman Furnitur — Hotel Santika Jakarta',       'reference_number' => 'INV-2025-004', 'status' => 'posted',  'category_code' => 'REV-002', 'coa_code' => '4-1002'],
            ['revenue_date' => '2025-02-05', 'amount' => 64000000,  'description' => 'Penjualan Bedroom Package 20 unit — Developer Perumahan Asri',        'reference_number' => 'INV-2025-005', 'status' => 'posted',  'category_code' => 'REV-001', 'coa_code' => '4-1001'],
            ['revenue_date' => '2025-02-18', 'amount' => 34000000,  'description' => 'Dining Set Restoran Padang Emas (meja 5 set + kursi 30 bj)',          'reference_number' => 'INV-2025-006', 'status' => 'posted',  'category_code' => 'REV-001', 'coa_code' => '4-1001'],
            ['revenue_date' => '2025-02-28', 'amount' => 5500000,   'description' => 'Jasa Custom Order & Desain Furnitur — PT Graha Properti',            'reference_number' => 'INV-2025-007', 'status' => 'draft','category_code' => 'REV-002', 'coa_code' => '4-1002'],
        ];

        $bankAccountId = BankAccount::where('created_by', $userId)
            ->where('is_active', true)
            ->value('id');

        foreach ($revenues as $revenue) {
            $catId = RevenueCategories::where('created_by', $userId)
                ->where('category_code', $revenue['category_code'])
                ->value('id');
            $coaId = ChartOfAccount::where('created_by', $userId)
                ->where('account_code', $revenue['coa_code'])
                ->value('id');

            Revenue::updateOrCreate(
                ['reference_number' => $revenue['reference_number'], 'created_by' => $userId],
                [
                    'revenue_date'       => $revenue['revenue_date'],
                    'amount'             => $revenue['amount'],
                    'description'        => $revenue['description'],
                    'status'             => $revenue['status'],
                    'category_id'        => $catId,
                    'bank_account_id'    => $bankAccountId,
                    'chart_of_account_id'=> $coaId,
                    'approved_by'        => null,
                    'creator_id'         => $userId,
                ]
            );
        }
    }
}
