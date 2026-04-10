<?php

namespace Workdo\Account\Database\Seeders;

use Illuminate\Database\Seeder;
use Workdo\Account\Models\Customer;
use App\Models\User;

class DemoCustomerDatabaseSeeder extends Seeder
{
    public function run($userId = null)
    {
        if (!$userId) {
            return;
        }

        if (Customer::where('created_by', $userId)->exists()) {
            return;
        }

        // PT Bojeri — 7 customers (Section 9 of bojeri_seeder.md)
        $customers = [
            [
                'email'        => 'bintoro@hotelsantika.co.id',
                'company_name' => 'Hotel Santika Jakarta',
                'pic_name'     => 'Pak Bintoro',
                'pic_mobile'   => '+6221-2345-6789',
                'tax_number'   => '01.456.789.0-021.000',
                'payment_terms'=> 'Net 30',
                'address'      => 'Jl. KH Wahid Hasyim No.9, Jakarta Pusat 10340',
                'city'         => 'Jakarta',
                'state'        => 'DKI Jakarta',
                'zip'          => '10340',
                'notes'        => 'Klien hotel — proyek lobby & kamar',
            ],
            [
                'email'        => 'sekar@majubersama.co.id',
                'company_name' => 'PT Maju Bersama',
                'pic_name'     => 'Bu Sekar',
                'pic_mobile'   => '+6221-5700-1234',
                'tax_number'   => '02.567.890.1-021.000',
                'payment_terms'=> 'Net 14',
                'address'      => 'Jl. Sudirman Kav.21, Jakarta Selatan 12920',
                'city'         => 'Jakarta',
                'state'        => 'DKI Jakarta',
                'zip'          => '12920',
                'notes'        => 'Klien korporat — pengadaan furnitur kantor',
            ],
            [
                'email'        => 'haris@kopinusantara.co.id',
                'company_name' => 'Café Kopi Nusantara',
                'pic_name'     => 'Pak Haris',
                'pic_mobile'   => '+6221-7890-1234',
                'tax_number'   => '03.678.901.2-021.000',
                'payment_terms'=> 'COD',
                'address'      => 'Jl. Kemang Raya No.3, Jakarta Selatan 12730',
                'city'         => 'Jakarta',
                'state'        => 'DKI Jakarta',
                'zip'          => '12730',
                'notes'        => 'Kafe — meja dan kursi custom',
            ],
            [
                'email'        => 'hendro@grahaproperti.co.id',
                'company_name' => 'PT Graha Properti',
                'pic_name'     => 'Pak Hendro',
                'pic_mobile'   => '+6221-8901-2345',
                'tax_number'   => '04.789.012.3-021.000',
                'payment_terms'=> 'Net 30',
                'address'      => 'Jl. TB Simatupang No.88, Jakarta Selatan 12560',
                'city'         => 'Jakarta',
                'state'        => 'DKI Jakarta',
                'zip'          => '12560',
                'notes'        => 'Developer properti — pengadaan sofa lobby 50 unit',
            ],
            [
                'email'        => 'tania@rsmedistra.co.id',
                'company_name' => 'Rumah Sakit Medistra',
                'pic_name'     => 'Bu Tania',
                'pic_mobile'   => '+6221-5260-1234',
                'tax_number'   => '05.890.123.4-021.000',
                'payment_terms'=> 'Net 45',
                'address'      => 'Jl. Gatot Subroto Kav.59, Jakarta Selatan 12950',
                'city'         => 'Jakarta',
                'state'        => 'DKI Jakarta',
                'zip'          => '12950',
                'notes'        => 'Rumah sakit — furnitur nurse station & ruang tunggu',
            ],
            [
                'email'        => 'rizal@perumahanasri.co.id',
                'company_name' => 'Developer Perumahan Asri',
                'pic_name'     => 'Pak Rizal',
                'pic_mobile'   => '+6221-5435-6789',
                'tax_number'   => '06.901.234.5-036.000',
                'payment_terms'=> 'Net 30',
                'address'      => 'Jl. Raya Serpong No.5, Tangerang 15310',
                'city'         => 'Tangerang',
                'state'        => 'Banten',
                'zip'          => '15310',
                'notes'        => 'Developer perumahan — paket furnitur 100 unit bedroom',
            ],
            [
                'email'        => 'yanti@padangemas.co.id',
                'company_name' => 'Restoran Padang Emas',
                'pic_name'     => 'Bu Yanti',
                'pic_mobile'   => '+6221-7659-3456',
                'tax_number'   => '07.012.345.6-021.000',
                'payment_terms'=> 'COD',
                'address'      => 'Jl. Fatmawati No.18, Jakarta Selatan 12420',
                'city'         => 'Jakarta',
                'state'        => 'DKI Jakarta',
                'zip'          => '12420',
                'notes'        => 'Restoran — set meja makan dan kursi dining',
            ],
        ];

        foreach ($customers as $data) {
            $clientUser = User::where('email', $data['email'])
                ->where('created_by', $userId)
                ->first();

            if (!$clientUser) {
                continue;
            }

            $addr = [
                'name'          => $data['pic_name'],
                'address_line_1'=> $data['address'],
                'address_line_2'=> null,
                'city'          => $data['city'],
                'state'         => $data['state'],
                'country'       => 'Indonesia',
                'zip_code'      => $data['zip'],
            ];

            Customer::updateOrCreate(
                ['user_id' => $clientUser->id, 'created_by' => $userId],
                [
                    'company_name'          => $data['company_name'],
                    'contact_person_name'   => $data['pic_name'],
                    'contact_person_email'  => $data['email'],
                    'contact_person_mobile' => $data['pic_mobile'],
                    'tax_number'            => $data['tax_number'],
                    'payment_terms'         => $data['payment_terms'],
                    'billing_address'       => $addr,
                    'shipping_address'      => $addr,
                    'same_as_billing'       => true,
                    'notes'                 => $data['notes'],
                    'creator_id'            => $userId,
                ]
            );
        }
    }
}
