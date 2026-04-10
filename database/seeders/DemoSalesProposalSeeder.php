<?php

namespace Database\Seeders;

use App\Models\SalesProposal;
use App\Models\SalesProposalItem;
use Illuminate\Database\Seeder;

class DemoSalesProposalSeeder extends Seeder
{
    public function run($userId): void
    {
        SalesProposal::where('created_by', $userId)->delete();

        // PT Bojeri — 5 sales proposals to key customers
        $proposals = [
            [
                'proposal_number'       => 'PROP-2025-0001',
                'proposal_date'         => '2025-03-01',
                'due_date'              => '2025-03-31',
                'customer_id'           => 69, // Hotel Santika Jakarta
                'warehouse_id'          => 5,  // Gudang Showroom JKT
                'subtotal'              => 14800000.00,
                'tax_amount'            => 1628000.00,
                'discount_amount'       => 0.00,
                'total_amount'          => 16428000.00,
                'status'                => 'accepted',
                'converted_to_invoice'  => false,
                'payment_terms'         => 'NET 30',
                'notes'                 => 'Renovasi lobby hotel — sofa, kursi makan, dan lemari TV.',
                'items' => [
                    ['product_id' => 15, 'quantity' => 2,  'unit_price' => 4800000.00, 'tax_percentage' => 11.00, 'tax_amount' => 1056000.00, 'total_amount' => 10656000.00],
                    ['product_id' => 16, 'quantity' => 4,  'unit_price' =>  850000.00, 'tax_percentage' => 11.00, 'tax_amount' =>  374000.00, 'total_amount' =>  3774000.00],
                    ['product_id' => 14, 'quantity' => 1,  'unit_price' => 1800000.00, 'tax_percentage' => 11.00, 'tax_amount' =>  198000.00, 'total_amount' =>  1998000.00],
                ],
            ],
            [
                'proposal_number'       => 'PROP-2025-0002',
                'proposal_date'         => '2025-04-10',
                'due_date'              => '2025-05-10',
                'customer_id'           => 70, // PT Maju Bersama
                'warehouse_id'          => 5,
                'subtotal'              => 15650000.00,
                'tax_amount'            => 1721500.00,
                'discount_amount'       => 0.00,
                'total_amount'          => 17371500.00,
                'status'                => 'sent',
                'converted_to_invoice'  => false,
                'payment_terms'         => 'NET 30',
                'notes'                 => 'Pengadaan furnitur kantor — kursi ergonomis dan meja kerja L-Shape.',
                'items' => [
                    ['product_id' =>  8, 'quantity' => 5, 'unit_price' => 1750000.00, 'tax_percentage' => 11.00, 'tax_amount' =>  962500.00, 'total_amount' =>  10712500.00],
                    ['product_id' => 13, 'quantity' => 3, 'unit_price' => 2300000.00, 'tax_percentage' => 11.00, 'tax_amount' =>  759000.00, 'total_amount' =>   7659000.00],
                ],
            ],
            [
                'proposal_number'       => 'PROP-2025-0003',
                'proposal_date'         => '2025-05-01',
                'due_date'              => '2025-06-01',
                'customer_id'           => 71, // Café Kopi Nusantara
                'warehouse_id'          => 5,
                'subtotal'              => 39600000.00,
                'tax_amount'            => 4356000.00,
                'discount_amount'       => 0.00,
                'total_amount'          => 43956000.00,
                'status'                => 'sent',
                'converted_to_invoice'  => false,
                'payment_terms'         => 'NET 14',
                'notes'                 => 'Set meja & kursi makan jati untuk seluruh area café.',
                'items' => [
                    ['product_id' =>  9, 'quantity' =>  4, 'unit_price' => 6500000.00, 'tax_percentage' => 11.00, 'tax_amount' => 2860000.00, 'total_amount' => 28860000.00],
                    ['product_id' => 16, 'quantity' => 16, 'unit_price' =>  850000.00, 'tax_percentage' => 11.00, 'tax_amount' => 1496000.00, 'total_amount' => 15096000.00],
                ],
            ],
            [
                'proposal_number'       => 'PROP-2025-0004',
                'proposal_date'         => '2025-06-01',
                'due_date'              => '2025-07-01',
                'customer_id'           => 72, // PT Graha Properti
                'warehouse_id'          => 5,
                'subtotal'              => 117000000.00,
                'tax_amount'            => 12870000.00,
                'discount_amount'       => 0.00,
                'total_amount'          => 129870000.00,
                'status'                => 'draft',
                'converted_to_invoice'  => false,
                'payment_terms'         => 'NET 45',
                'notes'                 => 'Pengadaan furnitur apartemen — kabinet dapur dan lemari pakaian per unit.',
                'items' => [
                    ['product_id' =>  6, 'quantity' => 10, 'unit_price' => 8500000.00, 'tax_percentage' => 11.00, 'tax_amount' => 9350000.00, 'total_amount' =>  94350000.00],
                    ['product_id' =>  7, 'quantity' => 10, 'unit_price' => 3200000.00, 'tax_percentage' => 11.00, 'tax_amount' => 3520000.00, 'total_amount' =>  35520000.00],
                ],
            ],
            [
                'proposal_number'       => 'PROP-2025-0005',
                'proposal_date'         => '2025-07-01',
                'due_date'              => '2025-08-01',
                'customer_id'           => 73, // Rumah Sakit Medistra
                'warehouse_id'          => 5,
                'subtotal'              => 32400000.00,
                'tax_amount'            => 3564000.00,
                'discount_amount'       => 0.00,
                'total_amount'          => 35964000.00,
                'status'                => 'rejected',
                'converted_to_invoice'  => false,
                'payment_terms'         => 'NET 30',
                'notes'                 => 'Penawaran furnitur nurse station — anggaran tidak sesuai, ditolak klien.',
                'items' => [
                    ['product_id' => 13, 'quantity' => 8, 'unit_price' => 2300000.00, 'tax_percentage' => 11.00, 'tax_amount' => 2024000.00, 'total_amount' => 20424000.00],
                    ['product_id' =>  8, 'quantity' => 8, 'unit_price' => 1750000.00, 'tax_percentage' => 11.00, 'tax_amount' => 1540000.00, 'total_amount' => 15540000.00],
                ],
            ],
        ];

        foreach ($proposals as $data) {
            $items = $data['items'];
            unset($data['items']);

            $data['creator_id'] = $userId;
            $data['created_by'] = $userId;

            $proposal = SalesProposal::create($data);

            foreach ($items as $item) {
                $item['proposal_id']          = $proposal->id;
                $item['discount_percentage']  = 0.00;
                $item['discount_amount']      = 0.00;
                SalesProposalItem::create($item);
            }
        }
    }
}
