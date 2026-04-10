<?php

namespace Workdo\ProductService\Database\Seeders;

use Workdo\ProductService\Models\ProductServiceItem;
use Workdo\ProductService\Models\ProductServiceCategory;
use Workdo\ProductService\Models\ProductServiceTax;
use Workdo\ProductService\Models\ProductServiceUnit;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;
use Workdo\ProductService\Models\WarehouseStock;

class DemoProductServiceItemSeeder extends Seeder
{
    public function run($userId): void
    {
        if (!empty($userId)) {
            // Get only Item type categories
            $itemCategories = ProductServiceCategory::where('created_by', $userId)->pluck('id', 'name')->toArray();
            $taxes = ProductServiceTax::where('created_by', $userId)->pluck('id')->toArray();
            $units = ProductServiceUnit::where('created_by', $userId)->pluck('id')->toArray();
            $warehouses = Warehouse::where('created_by', $userId)->pluck('id')->toArray();

            if (empty($itemCategories) || empty($taxes) || empty($units)) {
                return;
            }

            // PT Bojeri — furniture products and services (Section 8)
            $categoryItems = [
                'Living Room' => [
                    ['name' => 'Sofa Minimalis 3-Seater', 'sku' => 'SF-001', 'type' => 'product', 'unit' => 'Unit', 'has_tax' => true, 'image' => true, 'images' => 3, 'description' => 'Sofa 3-seater minimalis berbahan kain premium, cocok untuk ruang tamu modern', 'sale_price' => 4800000, 'purchase_price' => 2800000],
                    ['name' => 'Sofa L-Shape Modern',     'sku' => 'SF-002', 'type' => 'product', 'unit' => 'Unit', 'has_tax' => true, 'image' => true, 'images' => 3, 'description' => 'Sofa L-shape modern dengan rangka kayu jati dan pelapis kain abu premium', 'sale_price' => 7200000, 'purchase_price' => 4200000],
                    ['name' => 'Rak Buku Rotan',          'sku' => 'RB-009', 'type' => 'product', 'unit' => 'Unit', 'has_tax' => true, 'image' => true, 'images' => 2, 'description' => 'Rak buku anyaman rotan natural, ringan dan tahan lama untuk dekorasi ruang tamu', 'sale_price' => 950000,  'purchase_price' => 500000],
                    ['name' => 'Lemari TV Minimalis',     'sku' => 'LT-010', 'type' => 'product', 'unit' => 'Unit', 'has_tax' => true, 'image' => true, 'images' => 2, 'description' => 'Lemari TV minimalis dengan rak serbaguna, finishing HPL warna putih glossy', 'sale_price' => 1800000, 'purchase_price' => 1000000],
                ],
                'Dining' => [
                    ['name' => 'Meja Makan Jati 6 Pax',  'sku' => 'MM-003', 'type' => 'product', 'unit' => 'Set',  'has_tax' => true, 'image' => true, 'images' => 3, 'description' => 'Meja makan kayu jati solid untuk 6 orang, finishing natural oil berkualitas tinggi', 'sale_price' => 6500000, 'purchase_price' => 3800000],
                    ['name' => 'Kursi Makan Minimalis',   'sku' => 'KM-004', 'type' => 'product', 'unit' => 'Unit', 'has_tax' => true, 'image' => true, 'images' => 2, 'description' => 'Kursi makan minimalis rangka kayu jati, dudukan busa padat bersarung kain premium', 'sale_price' => 850000,  'purchase_price' => 450000],
                ],
                'Office' => [
                    ['name' => 'Kursi Kantor Ergonomis',  'sku' => 'KK-005', 'type' => 'product', 'unit' => 'Unit', 'has_tax' => true, 'image' => true, 'images' => 3, 'description' => 'Kursi kantor ergonomis dengan sandaran punggung adjustable dan material mesh bernapas', 'sale_price' => 1750000, 'purchase_price' => 950000],
                    ['name' => 'Meja Kerja L-Shape',      'sku' => 'MK-006', 'type' => 'product', 'unit' => 'Unit', 'has_tax' => true, 'image' => true, 'images' => 2, 'description' => 'Meja kerja L-shape berbahan multiplex HPL dengan laci dan rak kabel tersembunyi', 'sale_price' => 2300000, 'purchase_price' => 1300000],
                ],
                'Bedroom' => [
                    ['name' => 'Lemari Pakaian 4 Pintu',  'sku' => 'LP-007', 'type' => 'product', 'unit' => 'Unit', 'has_tax' => true, 'image' => true, 'images' => 3, 'description' => 'Lemari pakaian 4 pintu slide material multiplex HPL putih dengan cermin terintegrasi', 'sale_price' => 3200000, 'purchase_price' => 1800000],
                    ['name' => 'Tempat Tidur Minimalis',  'sku' => 'TT-008', 'type' => 'product', 'unit' => 'Unit', 'has_tax' => true, 'image' => true, 'images' => 3, 'description' => 'Tempat tidur minimalis Queen 160×200 cm, rangka kayu solid finishing duco putih', 'sale_price' => 5100000, 'purchase_price' => 3000000],
                    ['name' => 'Meja Rias Jati',          'sku' => 'MR-011', 'type' => 'product', 'unit' => 'Unit', 'has_tax' => true, 'image' => true, 'images' => 2, 'description' => 'Meja rias kayu jati dengan cermin oval dan 3 laci penyimpanan, finishing natural oil', 'sale_price' => 2100000, 'purchase_price' => 1200000],
                ],
                'Kitchen' => [
                    ['name' => 'Kabinet Dapur Set',       'sku' => 'KS-012', 'type' => 'product', 'unit' => 'Set',  'has_tax' => true, 'image' => true, 'images' => 4, 'description' => 'Kitchen set custom ukuran 3 meter, material multiplex HPL motif kayu, termasuk sink hole', 'sale_price' => 8500000, 'purchase_price' => 5000000],
                ],
                'Service' => [
                    ['name' => 'Jasa Pasang & Instalasi', 'sku' => 'SVC-01', 'type' => 'service', 'unit' => 'Unit',   'has_tax' => false, 'image' => false, 'images' => 0, 'description' => 'Jasa pemasangan dan instalasi furnitur di lokasi pelanggan oleh tim teknisi berpengalaman', 'sale_price' => 250000, 'purchase_price' => 0],
                    ['name' => 'Jasa Custom Order',       'sku' => 'SVC-02', 'type' => 'service', 'unit' => 'Proyek', 'has_tax' => false, 'image' => false, 'images' => 0, 'description' => 'Layanan desain dan produksi furnitur custom sesuai spesifikasi dan kebutuhan klien', 'sale_price' => 500000, 'purchase_price' => 0],
                    ['name' => 'Jasa Pengiriman',         'sku' => 'SVC-03', 'type' => 'service', 'unit' => 'Trip',   'has_tax' => false, 'image' => false, 'images' => 0, 'description' => 'Layanan pengiriman furnitur ke lokasi pelanggan dengan armada kendaraan aman', 'sale_price' => 200000, 'purchase_price' => 0],
                ],
            ];

            $items = [];
            foreach ($categoryItems as $categoryName => $categoryProducts) {
                if (isset($itemCategories[$categoryName])) {
                    foreach ($categoryProducts as $product) {
                        $product['category_name'] = $categoryName;
                        $items[] = $product;
                    }
                }
            }

            if (!empty($items)) {
                $items = collect($items)->shuffle()->values()->toArray(); // random select from array
            }

            if (!empty($warehouses)) {
                foreach ($items as $itemData) {
                    // Generate item name based image paths
                    $itemName = strtolower(str_replace([' ', '-'], '_', $itemData['name']));
                    $imagePath = "{$itemName}_image.png";

                    // Use predefined image count from categoryItems
                    $imageCount = $itemData['images'];
                    $imagesPaths = [];
                    if ($itemData['image'] && $imageCount > 0) {
                        for ($i = 1; $i <= $imageCount; $i++) {
                            $imagesPaths[] = "{$itemName}_images_{$i}.png";
                        }
                    }

                    // Handle tax assignment - 5 items without tax, rest with tax
                    $selectedTaxes = null;
                    if ($itemData['has_tax']) {
                        $taxCount = rand(1, min(3, count($taxes)));
                        $randomTaxes = array_slice($taxes, 0, $taxCount);
                        $selectedTaxes = array_map('intval', $randomTaxes);
                    }

                    // Get unit ID by name
                    $selectedUnit = null;
                    if (isset($itemData['unit'])) {
                        $unitId = ProductServiceUnit::where('created_by', $userId)
                            ->where('unit_name', $itemData['unit'])
                            ->value('id');
                        $selectedUnit = $unitId ?: null;
                    }

                    $item = ProductServiceItem::create([
                        'name' => $itemData['name'],
                        'sku' => $itemData['sku'],
                        'type' => $itemData['type'],
                        'description' => $itemData['description'] ?? null,
                        'sale_price' => $itemData['sale_price'],
                        'purchase_price' => $itemData['purchase_price'],
                        'tax_ids' => $selectedTaxes,
                        'category_id' => $itemCategories[$itemData['category_name']],
                        'unit' => $selectedUnit,
                        'image' => $itemData['image'] ? $imagePath : null,
                        'images' => !empty($imagesPaths) ? json_encode($imagesPaths) : null,
                        'is_active' => 1,
                        'creator_id' => $userId,
                        'created_by' => $userId,
                    ]);

                    // Only create warehouse stock for products and parts, not services
                    if ($itemData['type'] !== 'service') {
                        $warehouseCount = rand(1, min(3, count($warehouses)));
                        $selectedWarehouses = array_rand($warehouses, $warehouseCount);
                        if (!is_array($selectedWarehouses)) {
                            $selectedWarehouses = [$selectedWarehouses];
                        }

                        foreach ($selectedWarehouses as $warehouseIndex) {
                            WarehouseStock::create([
                                'product_id' => $item->id,
                                'warehouse_id' => $warehouses[$warehouseIndex],
                                'quantity' => rand(10, 150),
                            ]);
                        }
                    }
                }
            }
        }
    }
}
