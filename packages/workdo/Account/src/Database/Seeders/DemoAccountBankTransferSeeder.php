<?php

namespace Workdo\Account\Database\Seeders;

use Workdo\Account\Models\BankTransfer;
use Illuminate\Database\Seeder;

class DemoAccountBankTransferSeeder extends Seeder
{
    public function run($userId): void
    {
        BankTransfer::where('created_by', $userId)->delete();

        // PT Bojeri — 4 inter-bank transfers (BCA=5, Mandiri=6, BNI=7)
        $transfers = [
            [
                'transfer_number'  => 'BT-2025-0001',
                'transfer_date'    => '2025-04-01',
                'from_account_id'  => 5,  // BCA PT Bojeri
                'to_account_id'    => 6,  // Mandiri PT Bojeri
                'transfer_amount'  => 100000000.00,
                'transfer_charges' => 15000.00,
                'reference_number' => 'OPS-APR-001',
                'description'      => 'Transfer dana operasional April 2025 dari BCA ke Mandiri untuk pembayaran vendor.',
                'status'           => 'completed',
            ],
            [
                'transfer_number'  => 'BT-2025-0002',
                'transfer_date'    => '2025-05-25',
                'from_account_id'  => 6,  // Mandiri PT Bojeri
                'to_account_id'    => 7,  // BNI PT Bojeri
                'transfer_amount'  => 75000000.00,
                'transfer_charges' => 10000.00,
                'reference_number' => 'PAYROLL-MEI-2025',
                'description'      => 'Transfer staging penggajian Mei 2025 dari Mandiri ke BNI.',
                'status'           => 'completed',
            ],
            [
                'transfer_number'  => 'BT-2025-0003',
                'transfer_date'    => '2025-06-30',
                'from_account_id'  => 5,  // BCA PT Bojeri
                'to_account_id'    => 7,  // BNI PT Bojeri
                'transfer_amount'  => 50000000.00,
                'transfer_charges' => 10000.00,
                'reference_number' => 'BUFFER-Q3-001',
                'description'      => 'Transfer cadangan modal kerja Q3 2025 ke rekening BNI.',
                'status'           => 'completed',
            ],
            [
                'transfer_number'  => 'BT-2025-0004',
                'transfer_date'    => '2025-07-10',
                'from_account_id'  => 6,  // Mandiri PT Bojeri
                'to_account_id'    => 5,  // BCA PT Bojeri
                'transfer_amount'  => 30000000.00,
                'transfer_charges' => 10000.00,
                'reference_number' => 'REBALANCE-JUL-001',
                'description'      => 'Rebalancing saldo — transfer dari Mandiri ke BCA Juli 2025.',
                'status'           => 'pending',
            ],
        ];

        foreach ($transfers as $data) {
            $data['creator_id'] = $userId;
            $data['created_by'] = $userId;
            BankTransfer::create($data);
        }
    }
}
