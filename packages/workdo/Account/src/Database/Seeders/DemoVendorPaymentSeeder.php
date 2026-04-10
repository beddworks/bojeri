<?php

namespace Workdo\Account\Database\Seeders;

use Workdo\Account\Models\VendorPayment;
use Illuminate\Database\Seeder;

class DemoVendorPaymentSeeder extends Seeder
{
    public function run($userId): void
    {
        VendorPayment::where('created_by', $userId)->delete();

        // PT Bojeri — 5 vendor payments to raw-material suppliers
        $payments = [
            [
                'payment_number'   => 'VPAY-2025-0001',
                'payment_date'     => '2025-04-15',
                'vendor_id'        => 76, // CV Kayu Jati Indah
                'bank_account_id'  => 6,  // Mandiri PT Bojeri
                'reference_number' => 'REF/PINV-2025-0001',
                'payment_amount'   => 83250000.00,
                'status'           => 'cleared',
                'notes'            => 'Pelunasan pembelian kayu jati batch Maret 2025.',
            ],
            [
                'payment_number'   => 'VPAY-2025-0002',
                'payment_date'     => '2025-04-30',
                'vendor_id'        => 77, // PT Kain Sofa Makmur
                'bank_account_id'  => 5,  // BCA PT Bojeri
                'reference_number' => 'REF/PINV-2025-0002',
                'payment_amount'   => 44400000.00,
                'status'           => 'cleared',
                'notes'            => 'Pembayaran penuh kain pelapis sofa April 2025.',
            ],
            [
                'payment_number'   => 'VPAY-2025-0003',
                'payment_date'     => '2025-05-15',
                'vendor_id'        => 78, // Toko Besi Lestari
                'bank_account_id'  => 6,  // Mandiri PT Bojeri
                'reference_number' => 'REF/PINV-2025-0003/DP',
                'payment_amount'   => 25000000.00,
                'status'           => 'cleared',
                'notes'            => 'DP 50% pembelian rangka besi kursi kantor Mei 2025.',
            ],
            [
                'payment_number'   => 'VPAY-2025-0004',
                'payment_date'     => '2025-07-05',
                'vendor_id'        => 79, // CV Rotan Nusantara
                'bank_account_id'  => 5,  // BCA PT Bojeri
                'reference_number' => 'REF/PINV-2025-0004',
                'payment_amount'   => 26640000.00,
                'status'           => 'pending',
                'notes'            => 'Pembayaran bahan rotan rak buku — dalam proses kliring.',
            ],
            [
                'payment_number'   => 'VPAY-2025-0005',
                'payment_date'     => '2025-07-15',
                'vendor_id'        => 80, // PT Cat & Finishing Prima
                'bank_account_id'  => 7,  // BNI PT Bojeri
                'reference_number' => null,
                'payment_amount'   => 33300000.00,
                'status'           => 'pending',
                'notes'            => 'Pembayaran cat & bahan finishing Juli 2025 — menunggu approval.',
            ],
        ];

        foreach ($payments as $data) {
            $data['creator_id'] = $userId;
            $data['created_by'] = $userId;
            VendorPayment::create($data);
        }
    }
}
