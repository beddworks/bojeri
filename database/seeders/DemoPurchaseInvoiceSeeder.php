<?php

namespace Database\Seeders;

use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceItem;
use Illuminate\Database\Seeder;

class DemoPurchaseInvoiceSeeder extends Seeder
{
    public function run($userId): void
    {
        PurchaseInvoice::where('created_by', $userId)->delete();

        // PT Bojeri — 5 purchase invoices from raw-material vendors
        $invoices = [
            [
                'invoice_number'     => 'PINV-2025-0001',
                'invoice_date'       => '2025-03-15',
                'due_date'           => '2025-04-15',
                'vendor_id'          => 76, // CV Kayu Jati Indah
                'warehouse_id'       => 4,  // Gudang Produksi
                'subtotal'           => 75000000.00,
                'tax_amount'         => 8250000.00,
                'discount_amount'    => 0.00,
                'total_amount'       => 83250000.00,
                'paid_amount'        => 0.00,
                'debit_note_applied' => 0.00,
                'balance_amount'     => 83250000.00,
                'status'             => 'posted',
                'payment_terms'      => 'NET 30',
                'notes'              => 'Pembelian bahan baku kayu jati batch Maret 2025.',
                'items' => [
                    ['product_id' =>  9, 'quantity' => 30, 'unit_price' => 2500000.00, 'tax_percentage' => 11.00, 'tax_amount' => 8250000.00, 'total_amount' => 83250000.00],
                ],
            ],
            [
                'invoice_number'     => 'PINV-2025-0002',
                'invoice_date'       => '2025-04-01',
                'due_date'           => '2025-04-30',
                'vendor_id'          => 77, // PT Kain Sofa Makmur
                'warehouse_id'       => 4,
                'subtotal'           => 40000000.00,
                'tax_amount'         => 4400000.00,
                'discount_amount'    => 0.00,
                'total_amount'       => 44400000.00,
                'paid_amount'        => 44400000.00,
                'debit_note_applied' => 0.00,
                'balance_amount'     => 0.00,
                'status'             => 'paid',
                'payment_terms'      => 'NET 30',
                'notes'              => 'Pembelian kain pelapis sofa April 2025 — lunas.',
                'items' => [
                    ['product_id' => 15, 'quantity' => 20, 'unit_price' => 2000000.00, 'tax_percentage' => 11.00, 'tax_amount' => 4400000.00, 'total_amount' => 44400000.00],
                ],
            ],
            [
                'invoice_number'     => 'PINV-2025-0003',
                'invoice_date'       => '2025-05-01',
                'due_date'           => '2025-05-31',
                'vendor_id'          => 78, // Toko Besi Lestari
                'warehouse_id'       => 4,
                'subtotal'           => 50000000.00,
                'tax_amount'         => 5500000.00,
                'discount_amount'    => 0.00,
                'total_amount'       => 55500000.00,
                'paid_amount'        => 25000000.00,
                'debit_note_applied' => 0.00,
                'balance_amount'     => 30500000.00,
                'status'             => 'partial',
                'payment_terms'      => 'NET 30',
                'notes'              => 'Pembelian rangka besi kursi kantor Mei 2025 — DP 25 juta.',
                'items' => [
                    ['product_id' =>  8, 'quantity' => 100, 'unit_price' => 500000.00, 'tax_percentage' => 11.00, 'tax_amount' => 5500000.00, 'total_amount' => 55500000.00],
                ],
            ],
            [
                'invoice_number'     => 'PINV-2025-0004',
                'invoice_date'       => '2025-06-01',
                'due_date'           => '2025-06-30',
                'vendor_id'          => 79, // CV Rotan Nusantara
                'warehouse_id'       => 4,
                'subtotal'           => 24000000.00,
                'tax_amount'         => 2640000.00,
                'discount_amount'    => 0.00,
                'total_amount'       => 26640000.00,
                'paid_amount'        => 0.00,
                'debit_note_applied' => 0.00,
                'balance_amount'     => 26640000.00,
                'status'             => 'posted',
                'payment_terms'      => 'NET 30',
                'notes'              => 'Pembelian bahan rotan untuk rak buku kuartal 3.',
                'items' => [
                    ['product_id' => 12, 'quantity' => 80, 'unit_price' => 300000.00, 'tax_percentage' => 11.00, 'tax_amount' => 2640000.00, 'total_amount' => 26640000.00],
                ],
            ],
            [
                'invoice_number'     => 'PINV-2025-0005',
                'invoice_date'       => '2025-07-01',
                'due_date'           => '2025-07-31',
                'vendor_id'          => 80, // PT Cat & Finishing Prima
                'warehouse_id'       => 4,
                'subtotal'           => 30000000.00,
                'tax_amount'         => 3300000.00,
                'discount_amount'    => 0.00,
                'total_amount'       => 33300000.00,
                'paid_amount'        => 0.00,
                'debit_note_applied' => 0.00,
                'balance_amount'     => 33300000.00,
                'status'             => 'draft',
                'payment_terms'      => 'NET 14',
                'notes'              => 'Pembelian cat dan bahan finishing furnitur Juli 2025.',
                'items' => [
                    ['product_id' => 10, 'quantity' => 200, 'unit_price' => 150000.00, 'tax_percentage' => 11.00, 'tax_amount' => 3300000.00, 'total_amount' => 33300000.00],
                ],
            ],
        ];

        foreach ($invoices as $data) {
            $items = $data['items'];
            unset($data['items']);

            $data['creator_id'] = $userId;
            $data['created_by'] = $userId;

            $invoice = PurchaseInvoice::create($data);

            foreach ($items as $item) {
                $item['invoice_id']          = $invoice->id;
                $item['discount_percentage'] = 0.00;
                $item['discount_amount']     = 0.00;
                PurchaseInvoiceItem::create($item);
            }
        }
    }
}
