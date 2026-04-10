<?php

namespace Workdo\Account\Database\Seeders;

use Workdo\Account\Models\Expense;
use Workdo\Account\Models\ExpenseCategories;
use Workdo\Account\Models\BankAccount;
use Workdo\Account\Models\ChartOfAccount;
use Illuminate\Database\Seeder;

class DemoExpenseSeeder extends Seeder
{
    public function run($userId): void
    {
        if (Expense::where('created_by', $userId)->exists()) {
            return;
        }

        // PT Bojeri — sample expenses (IDR, last 3 months)
        $expenses = [
            ['expense_date' => now()->subMonths(2)->startOfMonth()->addDays(2),  'amount' => 9000000,  'description' => 'Sewa Gudang Produksi Tangerang — Jan 2026',    'reference_number' => 'EXP-2026-001', 'status' => 'posted',  'category_code' => 'EXP-001', 'coa_code' => '5-2002'],
            ['expense_date' => now()->subMonths(2)->startOfMonth()->addDays(5),  'amount' => 3750000,  'description' => 'Tagihan Listrik & Air Jan 2026',              'reference_number' => 'EXP-2026-002', 'status' => 'posted',  'category_code' => 'EXP-002', 'coa_code' => '5-2003'],
            ['expense_date' => now()->subMonths(2)->startOfMonth()->addDays(8),  'amount' => 2600000,  'description' => 'Ongkos Kirim Furnitur Hotel Santika Jan 2026', 'reference_number' => 'EXP-2026-003', 'status' => 'posted',  'category_code' => 'EXP-003', 'coa_code' => '5-2004'],
            ['expense_date' => now()->subMonths(2)->startOfMonth()->addDays(10), 'amount' => 4250000,  'description' => 'Biaya Iklan Instagram & Pameran Jan 2026',    'reference_number' => 'EXP-2026-004', 'status' => 'posted',  'category_code' => 'EXP-004', 'coa_code' => '5-2005'],
            ['expense_date' => now()->subMonths(2)->startOfMonth()->addDays(15), 'amount' => 8700000,  'description' => 'Iuran BPJS Jan 2026',                         'reference_number' => 'EXP-2026-005', 'status' => 'posted',  'category_code' => 'EXP-005', 'coa_code' => '5-3001'],
            ['expense_date' => now()->subMonths(1)->startOfMonth()->addDays(2),  'amount' => 9000000,  'description' => 'Sewa Gudang Produksi Tangerang — Feb 2026',    'reference_number' => 'EXP-2026-006', 'status' => 'posted',  'category_code' => 'EXP-001', 'coa_code' => '5-2002'],
            ['expense_date' => now()->subMonths(1)->startOfMonth()->addDays(5),  'amount' => 3800000,  'description' => 'Tagihan Listrik & Air Feb 2026',              'reference_number' => 'EXP-2026-007', 'status' => 'posted',  'category_code' => 'EXP-002', 'coa_code' => '5-2003'],
            ['expense_date' => now()->subMonths(1)->startOfMonth()->addDays(8),  'amount' => 2650000,  'description' => 'Ongkos Kirim Furnitur Feb 2026',              'reference_number' => 'EXP-2026-008', 'status' => 'draft','category_code' => 'EXP-003', 'coa_code' => '5-2004'],
        ];

        $bankAccountId = BankAccount::where('created_by', $userId)
            ->where('is_active', true)
            ->value('id');

        foreach ($expenses as $expense) {
            $catId = ExpenseCategories::where('created_by', $userId)
                ->where('category_code', $expense['category_code'])
                ->value('id');
            $coaId = ChartOfAccount::where('created_by', $userId)
                ->where('account_code', $expense['coa_code'])
                ->value('id');

            Expense::updateOrCreate(
                ['reference_number' => $expense['reference_number'], 'created_by' => $userId],
                [
                    'expense_date'       => $expense['expense_date'],
                    'amount'             => $expense['amount'],
                    'description'        => $expense['description'],
                    'status'             => $expense['status'],
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
