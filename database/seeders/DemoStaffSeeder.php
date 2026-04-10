<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DemoStaffSeeder extends Seeder
{
    public function run($userId)
    {
        if (User::where('created_by', $userId)->whereIn('type', ['staff', 'client', 'vendor'])->count() > 5) {
            return;
        }

        $faker = Faker::create();

        // PT Bojeri employees (39 staff) + 7 clients (customers) + 5 vendors (suppliers)
        $users = [
            // ── Head Office – Jakarta (HQ) ──
            ['name' => 'Sari Dewi',         'email' => 'sari.dewi@bojeri.com',         'type' => 'staff',  'role' => 'staff',  'avatar' => 'girls1.png'],
            ['name' => 'Reza Firmansyah',   'email' => 'reza.f@bojeri.com',            'type' => 'staff',  'role' => 'staff',  'avatar' => 'boys1.png'],
            ['name' => 'Putri Handayani',   'email' => 'putri.h@bojeri.com',           'type' => 'staff',  'role' => 'staff',  'avatar' => 'girls2.png'],
            ['name' => 'Dimas Ardianto',    'email' => 'dimas.a@bojeri.com',           'type' => 'staff',  'role' => 'staff',  'avatar' => 'boys2.png'],
            ['name' => 'Yuni Rahayu',       'email' => 'yuni.rahayu@bojeri.com',       'type' => 'staff',  'role' => 'staff',  'avatar' => 'girls3.png'],
            ['name' => 'Rina Marlina',      'email' => 'rina.marlina@bojeri.com',      'type' => 'staff',  'role' => 'staff',  'avatar' => 'girls4.png'],
            ['name' => 'Bagas Nugroho',     'email' => 'bagas.n@bojeri.com',           'type' => 'staff',  'role' => 'staff',  'avatar' => 'boys3.png'],
            ['name' => 'Dewi Susanti',      'email' => 'dewi.susanti@bojeri.com',      'type' => 'staff',  'role' => 'staff',  'avatar' => 'girls5.png'],
            ['name' => 'Fajar Kurniawan',   'email' => 'fajar.k@bojeri.com',           'type' => 'staff',  'role' => 'staff',  'avatar' => 'boys4.png'],
            ['name' => 'Agus Purnomo',      'email' => 'agus.purnomo@bojeri.com',      'type' => 'staff',  'role' => 'staff',  'avatar' => 'boys5.png'],
            ['name' => 'Hendra Saputra',    'email' => 'hendra.s@bojeri.com',          'type' => 'staff',  'role' => 'staff',  'avatar' => 'boys6.png'],
            ['name' => 'Bambang Triyono',   'email' => 'bambang.t@bojeri.com',         'type' => 'staff',  'role' => 'staff',  'avatar' => 'boys7.png'],
            ['name' => 'Sukiman Wibowo',    'email' => 'sukiman.w@bojeri.com',         'type' => 'staff',  'role' => 'staff',  'avatar' => 'boys8.png'],
            ['name' => 'Rudi Hartono',      'email' => 'rudi.hartono@bojeri.com',      'type' => 'staff',  'role' => 'staff',  'avatar' => 'boys9.png'],
            ['name' => 'Wahyu Setiawan',    'email' => 'wahyu.s@bojeri.com',           'type' => 'staff',  'role' => 'staff',  'avatar' => 'boys10.png'],
            ['name' => 'Eko Prasetyo',      'email' => 'eko.prasetyo@bojeri.com',      'type' => 'staff',  'role' => 'staff',  'avatar' => 'boys11.png'],
            ['name' => 'Andi Wijaya',       'email' => 'andi.wijaya@bojeri.com',       'type' => 'staff',  'role' => 'staff',  'avatar' => 'boys12.png'],
            ['name' => 'Lestari Ningrum',   'email' => 'lestari.n@bojeri.com',         'type' => 'staff',  'role' => 'staff',  'avatar' => 'girls6.png'],
            ['name' => 'Mega Wulandari',    'email' => 'mega.w@bojeri.com',            'type' => 'staff',  'role' => 'staff',  'avatar' => 'girls7.png'],
            ['name' => 'Taufik Hidayat',    'email' => 'taufik.h@bojeri.com',          'type' => 'staff',  'role' => 'staff',  'avatar' => 'boys13.png'],
            ['name' => 'Novita Sari',       'email' => 'novita.sari@bojeri.com',       'type' => 'staff',  'role' => 'staff',  'avatar' => 'girls8.png'],
            ['name' => 'Irfan Maulana',     'email' => 'irfan.m@bojeri.com',           'type' => 'staff',  'role' => 'staff',  'avatar' => 'boys14.png'],
            // ── North Branch – Bandung (NBD) ──
            ['name' => 'Asep Gunawan',      'email' => 'asep.g@bojeri.com',            'type' => 'staff',  'role' => 'staff',  'avatar' => 'boys15.png'],
            ['name' => 'Ningsih Rahayu',    'email' => 'ningsih.r@bojeri.com',         'type' => 'staff',  'role' => 'staff',  'avatar' => 'girls9.png'],
            ['name' => 'Dedi Kurniawan',    'email' => 'dedi.k@bojeri.com',            'type' => 'staff',  'role' => 'staff',  'avatar' => 'boys16.png'],
            ['name' => 'Endang Supriyadi',  'email' => 'endang.s@bojeri.com',          'type' => 'staff',  'role' => 'staff',  'avatar' => 'boys17.png'],
            ['name' => 'Ujang Rohmat',      'email' => 'ujang.r@bojeri.com',           'type' => 'staff',  'role' => 'staff',  'avatar' => 'boys18.png'],
            ['name' => 'Dadang Permana',    'email' => 'dadang.p@bojeri.com',          'type' => 'staff',  'role' => 'staff',  'avatar' => 'boys19.png'],
            ['name' => 'Yayan Suryadi',     'email' => 'yayan.s@bojeri.com',           'type' => 'staff',  'role' => 'staff',  'avatar' => 'boys20.png'],
            ['name' => 'Nana Suherman',     'email' => 'nana.sh@bojeri.com',           'type' => 'staff',  'role' => 'staff',  'avatar' => 'boys21.png'],
            // ── South Branch – Surabaya (SBY) ──
            ['name' => 'Slamet Riyadi',     'email' => 'slamet.r@bojeri.com',          'type' => 'staff',  'role' => 'staff',  'avatar' => 'boys22.png'],
            ['name' => 'Ratna Kumalasari',  'email' => 'ratna.k@bojeri.com',           'type' => 'staff',  'role' => 'staff',  'avatar' => 'girls10.png'],
            ['name' => 'Joko Santoso',      'email' => 'joko.santoso@bojeri.com',      'type' => 'staff',  'role' => 'staff',  'avatar' => 'boys23.png'],
            ['name' => 'Mulyono Hadikusumo','email' => 'mulyono.h@bojeri.com',         'type' => 'staff',  'role' => 'staff',  'avatar' => 'boys24.png'],
            ['name' => 'Paijo Winarno',     'email' => 'paijo.w@bojeri.com',           'type' => 'staff',  'role' => 'staff',  'avatar' => 'boys25.png'],
            ['name' => 'Sutrisno Adi',      'email' => 'sutrisno.a@bojeri.com',        'type' => 'staff',  'role' => 'staff',  'avatar' => 'boys1.png'],
            ['name' => 'Hariyanto Putra',   'email' => 'hariyanto.p@bojeri.com',       'type' => 'staff',  'role' => 'staff',  'avatar' => 'boys2.png'],
            ['name' => 'Supardi Laksono',   'email' => 'supardi.l@bojeri.com',         'type' => 'staff',  'role' => 'staff',  'avatar' => 'boys3.png'],
            // ── Clients (customers) ──
            ['name' => 'Hotel Santika Jakarta',    'email' => 'bintoro@hotelsantika.co.id',   'type' => 'client', 'role' => 'client', 'avatar' => 'girls11.png'],
            ['name' => 'PT Maju Bersama',          'email' => 'sekar@majubersama.co.id',      'type' => 'client', 'role' => 'client', 'avatar' => 'girls12.png'],
            ['name' => 'Café Kopi Nusantara',      'email' => 'haris@kopinusantara.co.id',    'type' => 'client', 'role' => 'client', 'avatar' => 'girls13.png'],
            ['name' => 'PT Graha Properti',        'email' => 'hendro@grahaproperti.co.id',   'type' => 'client', 'role' => 'client', 'avatar' => 'girls14.png'],
            ['name' => 'RS Medistra',              'email' => 'tania@rsmedistra.co.id',       'type' => 'client', 'role' => 'client', 'avatar' => 'girls15.png'],
            ['name' => 'Developer Perumahan Asri', 'email' => 'rizal@perumahanasri.co.id',   'type' => 'client', 'role' => 'client', 'avatar' => 'girls16.png'],
            ['name' => 'Restoran Padang Emas',     'email' => 'yanti@padangemas.co.id',       'type' => 'client', 'role' => 'client', 'avatar' => 'girls17.png'],
            // ── Vendors (suppliers) ──
            ['name' => 'CV Kayu Jati Indah',       'email' => 'sales@kayujatiindah.co.id',    'type' => 'vendor', 'role' => 'vendor', 'avatar' => 'boys4.png'],
            ['name' => 'PT Kain Sofa Makmur',      'email' => 'order@kainsofa.co.id',         'type' => 'vendor', 'role' => 'vendor', 'avatar' => 'boys5.png'],
            ['name' => 'Toko Besi Lestari',        'email' => 'info@besilestari.co.id',       'type' => 'vendor', 'role' => 'vendor', 'avatar' => 'boys6.png'],
            ['name' => 'CV Rotan Nusantara',       'email' => 'order@rotannusantara.co.id',   'type' => 'vendor', 'role' => 'vendor', 'avatar' => 'boys7.png'],
            ['name' => 'PT Cat & Finishing Prima', 'email' => 'sales@catprima.co.id',         'type' => 'vendor', 'role' => 'vendor', 'avatar' => 'boys8.png'],
        ];

        foreach ($users as $index => $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'avatar' => $userData['avatar'],
                'email_verified_at' => now(),
                'password' => Hash::make('1234'),
                'mobile_no' => '+62' . $faker->numerify('8##########'),
                'type' => $userData['type'],
                'creator_id' => $userId,
                'created_by' => $userId,
            ]);

            $user->assignRole($userData['role']);
        }
    }
}
