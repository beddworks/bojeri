<?php

namespace Workdo\Account\Database\Seeders;

use Illuminate\Database\Seeder;
use Workdo\Account\Models\Vendor;
use App\Models\User;

class DemoVendorDatabaseSeeder extends Seeder
{
    public function run($userId = null)
    {
        if (!$userId) {
            return;
        }

        if (Vendor::where('created_by', $userId)->exists()) {
            return;
        }

        // PT Bojeri — 5 vendors / suppliers (Section 10 of bojeri_seeder.md)
        $vendors = [
            [
                'email'        => 'sales@kayujatiindah.co.id',
                'company_name' => 'CV Kayu Jati Indah',
                'pic_name'     => 'Pak Sutarja',
                'pic_mobile'   => '+62291-5566-112',
                'tax_number'   => '10.111.222.3-504.000',
                'payment_terms'=> 'Net 30',
                'address'      => 'Jl. Raya Jepara No.5, Jepara 59401',
                'city'         => 'Jepara',
                'state'        => 'Jawa Tengah',
                'zip'          => '59401',
                'notes'        => 'Pemasok kayu jati solid berkualitas tinggi',
            ],
            [
                'email'        => 'order@kainsofa.co.id',
                'company_name' => 'PT Kain Sofa Makmur',
                'pic_name'     => 'Ibu Neneng',
                'pic_mobile'   => '+62222-4455-678',
                'tax_number'   => '11.222.333.4-424.000',
                'payment_terms'=> 'Net 14',
                'address'      => 'Jl. Industri Tekstil No.8, Bandung 40234',
                'city'         => 'Bandung',
                'state'        => 'Jawa Barat',
                'zip'          => '40234',
                'notes'        => 'Pemasok kain pelapis sofa dan upholstery premium',
            ],
            [
                'email'        => 'info@besilestari.co.id',
                'company_name' => 'Toko Besi Lestari',
                'pic_name'     => 'Pak Haryono',
                'pic_mobile'   => '+62315-3344-890',
                'tax_number'   => '12.333.444.5-609.000',
                'payment_terms'=> 'COD',
                'address'      => 'Jl. Pasar Besi No.3, Surabaya 60177',
                'city'         => 'Surabaya',
                'state'        => 'Jawa Timur',
                'zip'          => '60177',
                'notes'        => 'Pemasok besi, sekrup, engsel, dan hardware furnitur',
            ],
            [
                'email'        => 'order@rotannusantara.co.id',
                'company_name' => 'CV Rotan Nusantara',
                'pic_name'     => 'Pak Tarmidi',
                'pic_mobile'   => '+62231-7788-345',
                'tax_number'   => '13.444.555.6-331.000',
                'payment_terms'=> 'Net 14',
                'address'      => 'Jl. Kerajinan No.12, Cirebon 45131',
                'city'         => 'Cirebon',
                'state'        => 'Jawa Barat',
                'zip'          => '45131',
                'notes'        => 'Pemasok rotan dan bambu untuk furnitur anyaman',
            ],
            [
                'email'        => 'sales@catprima.co.id',
                'company_name' => 'PT Cat & Finishing Prima',
                'pic_name'     => 'Pak Beni',
                'pic_mobile'   => '+6221-5990-2233',
                'tax_number'   => '14.555.666.7-036.000',
                'payment_terms'=> 'Net 7',
                'address'      => 'Jl. Kimia Raya No.7, Tangerang 15710',
                'city'         => 'Tangerang',
                'state'        => 'Banten',
                'zip'          => '15710',
                'notes'        => 'Pemasok cat, politur, dan bahan finishing furnitur',
            ],
        ];

        foreach ($vendors as $data) {
            $vendorUser = User::where('email', $data['email'])
                ->where('created_by', $userId)
                ->first();

            if (!$vendorUser) {
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

            Vendor::updateOrCreate(
                ['user_id' => $vendorUser->id, 'created_by' => $userId],
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
