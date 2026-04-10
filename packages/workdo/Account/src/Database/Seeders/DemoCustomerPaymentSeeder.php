<?php

namespace Workdo\Account\Database\Seeders;

use Workdo\Account\Models\CustomerPayment;
use Illuminate\Database\Seeder;

class DemoCustomerPaymentSeeder extends Seeder
{
    public function run($userId): void
    {
        CustomerPayment::where('created_by', $userId)->delete();

        // PT Bojeri — 5 customer payments received from clients
        $payments = [
            [
                'payment_number'   => 'CPAY-2025-0001',
                'payment_date'     => '2025-04-28',
                'customer_id'      => 69, // Hotel Santika Jakarta
                'bank_account_id'  => 5,  // BCA PT Bojeri
                'reference_number' => 'REF/INV-2025-0001',
                'payment_amount'   => 16428000.00,
                'status'           => 'cleared',
                'notes'            => 'Pelunasan furnitur lobby Hotel Santika Jakarta.',
            ],
            [
                'payment_number'   => 'CPAY-2025-0002',
                'payment_date'     => '2025-05-15',
                'customer_id'      => 71, // Café Kopi Nusantara
                'bank_account_id'  => 5,  // BCA PT Bojeri
                'reference_number' => 'REF/INV-2025-0005',
                'payment_amount'   => 43956000.00,
                'status'           => 'cleared',
                'notes'            => 'Pembayaran penuh set furnitur café — meja dan kursi jati.',
            ],
            [
                'payment_number'   => 'CPAY-2025-0003',
                'payment_date'     => '2025-06-01',
                'customer_id'      => 74, // Developer Perumahan Asri
                'bank_account_id'  => 6,  // Mandiri PT Bojeri
                'reference_number' => 'REF/INV-2025-0003/DP',
                'payment_amount'   => 30000000.00,
                'status'           => 'cleared',
                'notes'            => 'DP 50% furnitur apartemen Developer Perumahan Asri.',
            ],
            [
                'payment_number'   => 'CPAY-2025-0004',
                'payment_date'     => '2025-07-10',
                'customer_id'      => 70, // PT Maju Bersama
                'bank_account_id'  => 5,  // BCA PT Bojeri
                'reference_number' => 'REF/INV-2025-0002',
                'payment_amount'   => 17371500.00,
                'status'           => 'pending',
                'notes'            => 'Pembayaran furnitur kantor PT Maju Bersama — dalam proses verifikasi.',
            ],
            [
                'payment_number'   => 'CPAY-2025-0005',
                'payment_date'     => '2025-07-20',
                'customer_id'      => 73, // Rumah Sakit Medistra
                'bank_account_id'  => 6,  // Mandiri PT Bojeri
                'reference_number' => null,
                'payment_amount'   => 12500000.00,
                'status'           => 'pending',
                'notes'            => 'Uang muka order furnitur nurse station RS Medistra.',
            ],
        ];

        foreach ($payments as $data) {
            $data['creator_id'] = $userId;
            $data['created_by'] = $userId;
            CustomerPayment::create($data);
        }
    }
}
