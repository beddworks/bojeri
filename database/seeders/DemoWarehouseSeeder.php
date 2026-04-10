<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class DemoWarehouseSeeder extends Seeder
{
    public function run($userId): void
    {
        if (Warehouse::where('created_by', $userId)->exists()) {
            return;
        }

        // PT Bojeri — 4 warehouses across 3 locations
        $warehouses = [
            [
                'name'     => 'Gudang Produksi',
                'address'  => 'Jl. Raya Industri No.12',
                'city'     => 'Tangerang',
                'zip_code' => '15710',
                'phone'    => '+62215551234',
                'email'    => 'gudang.produksi@bojeri.com',
            ],
            [
                'name'     => 'Gudang Showroom JKT',
                'address'  => 'Jl. Gatot Subroto No.55',
                'city'     => 'Jakarta Selatan',
                'zip_code' => '12950',
                'phone'    => '+62215551235',
                'email'    => 'showroom.jkt@bojeri.com',
            ],
            [
                'name'     => 'Gudang Bandung',
                'address'  => 'Jl. Asia Afrika No.10',
                'city'     => 'Bandung',
                'zip_code' => '40111',
                'phone'    => '+622225551236',
                'email'    => 'gudang.bandung@bojeri.com',
            ],
            [
                'name'     => 'Gudang Surabaya',
                'address'  => 'Jl. Basuki Rahmat No.22',
                'city'     => 'Surabaya',
                'zip_code' => '60271',
                'phone'    => '+623115551237',
                'email'    => 'gudang.surabaya@bojeri.com',
            ],
        ];

        foreach ($warehouses as $warehouse) {
            Warehouse::create(array_merge($warehouse, [
                'is_active' => true,
                'creator_id' => $userId,
                'created_by' => $userId,
            ]));
        }
    }
}
