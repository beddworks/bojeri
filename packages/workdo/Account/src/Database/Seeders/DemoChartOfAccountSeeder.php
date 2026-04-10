<?php

namespace Workdo\Account\Database\Seeders;

use Workdo\Account\Models\ChartOfAccount;
use Illuminate\Database\Seeder;
use Workdo\Account\Models\AccountType;


class DemoChartOfAccountSeeder extends Seeder
{
    public function run($userId): void
    {
        if (ChartOfAccount::where('created_by', $userId)
            ->where('account_code', '1-1001')
            ->exists()) {
            return;
        }

        // Resolve account type IDs by code (created by AccountUtility::defaultdata)
        $typeIds = AccountType::where('created_by', $userId)
            ->pluck('id', 'code')
            ->toArray();

        // PT Bojeri — Chart of Accounts (Section 12 of bojeri_seeder.md)
        $accounts = [
            // Assets — Current
            ['code' => 'CA',   'account_code' => '1-1001', 'account_name' => 'Kas & Bank',                   'normal_balance' => '0', 'opening' => 312500000, 'current' => 312500000, 'description' => 'Kas tunai dan rekening bank operasional PT Bojeri'],
            ['code' => 'CA',   'account_code' => '1-1200', 'account_name' => 'Piutang Usaha',                'normal_balance' => '0', 'opening' => 125000000, 'current' => 143500000, 'description' => 'Tagihan kepada pelanggan yang belum dibayar'],
            ['code' => 'CA',   'account_code' => '1-1400', 'account_name' => 'Persediaan Bahan Baku',        'normal_balance' => '0', 'opening' => 45000000,  'current' => 38000000,  'description' => 'Stok kayu jati, kain sofa, rotan, dan material produksi'],
            ['code' => 'CA',   'account_code' => '1-1500', 'account_name' => 'Persediaan Barang Jadi',       'normal_balance' => '0', 'opening' => 72000000,  'current' => 88500000,  'description' => 'Furnitur jadi siap jual di gudang dan showroom'],
            // Assets — Fixed
            ['code' => 'FA',   'account_code' => '1-2001', 'account_name' => 'Peralatan Produksi',           'normal_balance' => '0', 'opening' => 250000000, 'current' => 250000000, 'description' => 'Mesin gergaji, CNC router, amplas, dan alat produksi'],
            ['code' => 'FA',   'account_code' => '1-2100', 'account_name' => 'Akumulasi Penyusutan',         'normal_balance' => '1', 'opening' => 62500000,  'current' => 68750000,  'description' => 'Akumulasi penyusutan peralatan produksi'],
            // Liabilities — Current
            ['code' => 'CL',   'account_code' => '2-1001', 'account_name' => 'Utang Usaha',                  'normal_balance' => '1', 'opening' => 34700000,  'current' => 28500000,  'description' => 'Kewajiban pembayaran kepada pemasok bahan baku'],
            ['code' => 'CL',   'account_code' => '2-1100', 'account_name' => 'Utang Gaji',                   'normal_balance' => '1', 'opening' => 0,          'current' => 185000000, 'description' => 'Gaji karyawan yang belum dibayarkan'],
            ['code' => 'CL',   'account_code' => '2-1200', 'account_name' => 'Utang PPN',                    'normal_balance' => '1', 'opening' => 0,          'current' => 15730000,  'description' => 'PPN 11% terutang atas penjualan periode berjalan'],
            // Equity
            ['code' => 'SC',   'account_code' => '3-1001', 'account_name' => 'Modal Disetor',                'normal_balance' => '1', 'opening' => 500000000, 'current' => 500000000, 'description' => 'Modal awal yang disetor pemegang saham PT Bojeri'],
            ['code' => 'RE',   'account_code' => '3-1100', 'account_name' => 'Laba Ditahan',                 'normal_balance' => '1', 'opening' => 248800000, 'current' => 248800000, 'description' => 'Akumulasi laba bersih yang ditahan dalam perusahaan'],
            // Revenue
            ['code' => 'SR',   'account_code' => '4-1001', 'account_name' => 'Pendapatan Penjualan',         'normal_balance' => '1', 'opening' => 0,          'current' => 285000000, 'description' => 'Pendapatan dari penjualan furnitur kepada pelanggan'],
            ['code' => 'SR',   'account_code' => '4-1002', 'account_name' => 'Pendapatan Jasa',              'normal_balance' => '1', 'opening' => 0,          'current' => 12500000,  'description' => 'Pendapatan dari jasa pemasangan, custom order, dan pengiriman'],
            // Expenses — COGS
            ['code' => 'COGS', 'account_code' => '5-1001', 'account_name' => 'Harga Pokok Penjualan',        'normal_balance' => '0', 'opening' => 0,          'current' => 168000000, 'description' => 'Biaya bahan baku dan produksi langsung atas barang terjual'],
            // Expenses — Operating
            ['code' => 'OE',   'account_code' => '5-2001', 'account_name' => 'Biaya Gaji Karyawan',          'normal_balance' => '0', 'opening' => 0,          'current' => 185000000, 'description' => 'Total gaji bruto seluruh karyawan PT Bojeri'],
            ['code' => 'OE',   'account_code' => '5-2002', 'account_name' => 'Biaya Sewa Gudang',            'normal_balance' => '0', 'opening' => 0,          'current' => 18000000,  'description' => 'Biaya sewa gudang produksi Tangerang dan gudang regional'],
            ['code' => 'OE',   'account_code' => '5-2003', 'account_name' => 'Biaya Listrik & Air',          'normal_balance' => '0', 'opening' => 0,          'current' => 7500000,   'description' => 'Tagihan listrik dan air seluruh lokasi operasional'],
            ['code' => 'OE',   'account_code' => '5-2004', 'account_name' => 'Biaya Transport & Pengiriman', 'normal_balance' => '0', 'opening' => 0,          'current' => 5200000,   'description' => 'Ongkos pengiriman furnitur ke pelanggan dan antar-gudang'],
            ['code' => 'OE',   'account_code' => '5-2005', 'account_name' => 'Biaya Pemasaran',              'normal_balance' => '0', 'opening' => 0,          'current' => 8500000,   'description' => 'Iklan Instagram, pameran, dan materi promosi'],
            // Expenses — HR
            ['code' => 'OE',   'account_code' => '5-3001', 'account_name' => 'Biaya BPJS Ketenagakerjaan',  'normal_balance' => '0', 'opening' => 0,          'current' => 5800000,   'description' => 'Iuran BPJS Ketenagakerjaan bagian perusahaan (2% dari gaji)'],
            ['code' => 'OE',   'account_code' => '5-3002', 'account_name' => 'Biaya BPJS Kesehatan',        'normal_balance' => '0', 'opening' => 0,          'current' => 2900000,   'description' => 'Iuran BPJS Kesehatan bagian perusahaan (1% dari gaji)'],
        ];

        foreach ($accounts as $row) {
            $typeCode = $row['code'];
            ChartOfAccount::updateOrCreate(
                ['account_code' => $row['account_code'], 'created_by' => $userId],
                [
                    'account_name'     => $row['account_name'],
                    'normal_balance'   => $row['normal_balance'],
                    'opening_balance'  => $row['opening'],
                    'current_balance'  => $row['current'],
                    'is_active'        => true,
                    'is_system_account'=> false,
                    'description'      => $row['description'],
                    'account_type_id'  => $typeIds[$typeCode] ?? null,
                    'creator_id'       => $userId,
                    'created_by'       => $userId,
                ]
            );
        }
    }
}