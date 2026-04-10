<?php

namespace Database\Seeders;

use App\Models\SalesInvoice;
use App\Models\SalesInvoiceItem;
use Illuminate\Database\Seeder;

class DemoSalesInvoiceSeeder extends Seeder
{
    public function run($userId): void
    {
        SalesInvoice::where('created_by', $userId)->delete();

        // PT Bojeri — 6 sales invoices across key customers
        $invoices = [
            [
                'invoice_number'  => 'INV-2025-0001',
                'invoice_date'    => '2025-04-01',
                'due_date'        => '2025-04-30',
                'customer_id'     => 69, // Hotel Santika Jakarta
                'warehouse_id'    => 5,
                'subtotal'        => 14800000.00,
                'tax_amount'      => 1628000.00,
                'discount_amount' => 0.00,
                'total_amount'    => 16428000.00,
                'paid_amount'     => 16428000.00,
                'balance_amount'  => 0.00,
                'status'          => 'paid',
                'type'            => 'product',
                'payment_terms'   => 'NET 30',
                'notes'           => 'Pelunasan furnitur lobby Hotel Santika Jakarta.',
                'items' => [
                    ['product_id' => 15, 'quantity' => 2,  'unit_price' => 4800000.00, 'tax_percentage' => 11.00, 'tax_amount' => 1056000.00, 'total_amount' => 10656000.00],
                    ['product_id' => 16, 'quantity' => 4,  'unit_price' =>  850000.00, 'tax_percentage' => 11.00, 'tax_amount' =>  374000.00, 'total_amount' =>  3774000.00],
                    ['product_id' => 14, 'quantity' => 1,  'unit_price' => 1800000.00, 'tax_percentage' => 11.00, 'tax_amount' =>  198000.00, 'total_amount' =>  1998000.00],
                ],
            ],
            [
                'invoice_number'  => 'INV-2025-0002',
                'invoice_date'    => '2025-05-01',
                'due_date'        => '2025-05-31',
                'customer_id'     => 70, // PT Maju Bersama
                'warehouse_id'    => 5,
                'subtotal'        => 15650000.00,
                'tax_amount'      => 1721500.00,
                'discount_amount' => 0.00,
                'total_amount'    => 17371500.00,
                'paid_amount'     => 0.00,
                'balance_amount'  => 17371500.00,
                'status'          => 'posted',
                'type'            => 'product',
                'payment_terms'   => 'NET 30',
                'notes'           => 'Furnitur kantor PT Maju Bersama — kursi dan meja kerja.',
                'items' => [
                    ['product_id' =>  8, 'quantity' => 5, 'unit_price' => 1750000.00, 'tax_percentage' => 11.00, 'tax_amount' =>  962500.00, 'total_amount' =>  9712500.00],
                    ['product_id' => 13, 'quantity' => 3, 'unit_price' => 2300000.00, 'tax_percentage' => 11.00, 'tax_amount' =>  759000.00, 'total_amount' =>  7659000.00],
                ],
            ],
            [
                'invoice_number'  => 'INV-2025-0003',
                'invoice_date'    => '2025-05-15',
                'due_date'        => '2025-06-15',
                'customer_id'     => 74, // Developer Perumahan Asri
                'warehouse_id'    => 5,
                'subtotal'        => 55300000.00,
                'tax_amount'      => 6083000.00,
                'discount_amount' => 0.00,
                'total_amount'    => 61383000.00,
                'paid_amount'     => 30000000.00,
                'balance_amount'  => 31383000.00,
                'status'          => 'partial',
                'type'            => 'product',
                'payment_terms'   => 'NET 45',
                'notes'           => 'Pengadaan furnitur apartemen  — pembayaran sebagian (DP 30 juta).',
                'items' => [
                    ['product_id' =>  6, 'quantity' =>  5, 'unit_price' => 8500000.00, 'tax_percentage' => 11.00, 'tax_amount' => 4675000.00, 'total_amount' => 47175000.00],
                    ['product_id' =>  7, 'quantity' =>  4, 'unit_price' => 3200000.00, 'tax_percentage' => 11.00, 'tax_amount' => 1408000.00, 'total_amount' => 14208000.00],
                ],
            ],
            [
                'invoice_number'  => 'INV-2025-0004',
                'invoice_date'    => '2025-03-01',
                'due_date'        => '2025-03-31',
                'customer_id'     => 75, // Restoran Padang Emas
                'warehouse_id'    => 5,
                'subtotal'        => 36200000.00,
                'tax_amount'      => 3982000.00,
                'discount_amount' => 0.00,
                'total_amount'    => 40182000.00,
                'paid_amount'     => 0.00,
                'balance_amount'  => 40182000.00,
                'status'          => 'overdue',
                'type'            => 'product',
                'payment_terms'   => 'NET 30',
                'notes'           => 'Dining set Restoran Padang Emas — belum dibayar, jatuh tempo terlewat.',
                'items' => [
                    ['product_id' =>  9, 'quantity' =>  4, 'unit_price' => 6500000.00, 'tax_percentage' => 11.00, 'tax_amount' => 2860000.00, 'total_amount' => 28860000.00],
                    ['product_id' => 16, 'quantity' => 12, 'unit_price' =>  850000.00, 'tax_percentage' => 11.00, 'tax_amount' => 1122000.00, 'total_amount' => 11322000.00],
                ],
            ],
            [
                'invoice_number'  => 'INV-2025-0005',
                'invoice_date'    => '2025-04-15',
                'due_date'        => '2025-05-15',
                'customer_id'     => 71, // Café Kopi Nusantara
                'warehouse_id'    => 5,
                'subtotal'        => 39600000.00,
                'tax_amount'      => 4356000.00,
                'discount_amount' => 0.00,
                'total_amount'    => 43956000.00,
                'paid_amount'     => 43956000.00,
                'balance_amount'  => 0.00,
                'status'          => 'paid',
                'type'            => 'product',
                'payment_terms'   => 'NET 14',
                'notes'           => 'Set furnitur café — meja dan kursi makan jati, lunas.',
                'items' => [
                    ['product_id' =>  9, 'quantity' =>  4, 'unit_price' => 6500000.00, 'tax_percentage' => 11.00, 'tax_amount' => 2860000.00, 'total_amount' => 28860000.00],
                    ['product_id' => 16, 'quantity' => 16, 'unit_price' =>  850000.00, 'tax_percentage' => 11.00, 'tax_amount' => 1496000.00, 'total_amount' => 15096000.00],
                ],
            ],
            [
                'invoice_number'  => 'INV-2025-0006',
                'invoice_date'    => '2025-07-01',
                'due_date'        => '2025-07-31',
                'customer_id'     => 72, // PT Graha Properti
                'warehouse_id'    => 5,
                'subtotal'        => 14200000.00,
                'tax_amount'      => 1562000.00,
                'discount_amount' => 0.00,
                'total_amount'    => 15762000.00,
                'paid_amount'     => 0.00,
                'balance_amount'  => 15762000.00,
                'status'          => 'draft',
                'type'            => 'service',
                'payment_terms'   => 'NET 30',
                'notes'           => 'Jasa desain interior & pemasangan furnitur apartemen PT Graha Properti.',
                'items' => [
                    ['product_id' => 13, 'quantity' => 2, 'unit_price' => 2300000.00, 'tax_percentage' => 11.00, 'tax_amount' =>  506000.00, 'total_amount' =>  5106000.00],
                    ['product_id' =>  7, 'quantity' => 3, 'unit_price' => 3200000.00, 'tax_percentage' => 11.00, 'tax_amount' => 1056000.00, 'total_amount' => 10656000.00],
                ],
            ],
        ];

        foreach ($invoices as $data) {
            $items = $data['items'];
            unset($data['items']);

            $data['creator_id'] = $userId;
            $data['created_by'] = $userId;

            $invoice = SalesInvoice::create($data);

            foreach ($items as $item) {
                $item['invoice_id']           = $invoice->id;
                $item['discount_percentage']  = 0.00;
                $item['discount_amount']      = 0.00;
                SalesInvoiceItem::create($item);
            }
        }
    }
}
