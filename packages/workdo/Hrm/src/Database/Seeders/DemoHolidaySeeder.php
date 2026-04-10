<?php

namespace Workdo\Hrm\Database\Seeders;

use Workdo\Hrm\Models\Holiday;
use Workdo\Hrm\Models\HolidayType;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DemoHolidaySeeder extends Seeder
{
    public function run($userId): void
    {
        if (!empty($userId)) {
            $holidayTypes = HolidayType::where('created_by', $userId)->pluck('id')->toArray();

            if (empty($holidayTypes)) {
                return;
            }

            Holiday::where('created_by', $userId)->delete();

            $holidayNames = [
                'Tahun Baru 2025',
                'Isra Miraj 1446 H',
                'Tahun Baru Imlek 2576 Kongzili',
                'Hari Raya Nyepi - Tahun Baru Saka 1947',
                'Wafat Yesus Kristus',
                'Hari Buruh Internasional',
                'Hari Raya Waisak 2569 BE',
                'Kenaikan Yesus Kristus',
                'Hari Lahir Pancasila',
                'Hari Raya Idul Adha 1446 H',
                'Tahun Baru Islam 1447 H',
                'Hari Kemerdekaan RI ke-80',
                'Maulid Nabi Muhammad SAW 1447 H',
                'Hari Pahlawan',
                'Hari Raya Natal',
                'Cuti Bersama Natal',
                'Tahun Baru 2026',
                'Hari Raya Idul Fitri 1 Syawal 1447 H',
                'Cuti Bersama Idul Fitri 1447 H',
                'Hari Jadi PT Bojeri',
            ];

            $descriptions = [
                'Libur Tahun Baru Masehi 2025. Seluruh karyawan PT Bojeri mendapat hari libur nasional.',
                'Libur Isra Miraj 1446 H. Peringatan perjalanan malam Nabi Muhammad SAW dari Mekah ke Madinah.',
                'Libur Tahun Baru Imlek 2576 Kongzili. Perayaan bagi karyawan yang merayakan tahun baru Tiongkok.',
                'Libur Hari Raya Nyepi Tahun Baru Saka 1947. Hari libur nasional seluruh karyawan.',
                'Libur Wafat Yesus Kristus (Good Friday). Hari libur nasional umat Kristiani di Indonesia.',
                'Libur Hari Buruh Internasional. Menghargai perjuangan dan dedikasi seluruh pekerja Indonesia.',
                'Libur Hari Raya Waisak 2569 BE. Hari suci umat Buddha memperingati lahir, pencerahan, dan wafatnya Sang Buddha.',
                'Libur Kenaikan Yesus Kristus ke surga. Hari libur nasional umat Kristiani Indonesia.',
                'Libur Hari Lahir Pancasila 1 Juni. Peringatan kelahiran dasar negara Republik Indonesia.',
                'Libur Hari Raya Idul Adha 1446 H. Hari raya kurban umat Islam di seluruh Indonesia.',
                'Libur Tahun Baru Islam 1447 Hijriyah. Karyawan Muslim PT Bojeri mendapat hari libur nasional.',
                'Libur Hari Kemerdekaan RI ke-80 tanggal 17 Agustus. Upacara bendera dan kegiatan perayaan kemerdekaan.',
                'Libur Maulid Nabi Muhammad SAW 1447 H. Peringatan hari kelahiran Nabi Muhammad SAW.',
                'Libur Hari Pahlawan 10 November. Mengenang jasa para pahlawan kemerdekaan Indonesia.',
                'Libur Hari Raya Natal 25 Desember. Merayakan kelahiran Yesus Kristus bagi umat Kristiani.',
                'Cuti bersama menjelang dan sesudah perayaan Hari Raya Natal.',
                'Libur Tahun Baru Masehi 2026. Seluruh karyawan PT Bojeri mendapat hari libur nasional.',
                'Libur Hari Raya Idul Fitri 1 Syawal 1447 H. Lebaran dan silaturahmi keluarga bagi seluruh karyawan.',
                'Cuti bersama Idul Fitri 1447 H. Karyawan mendapat libur panjang Lebaran sesuai ketetapan pemerintah.',
                'Peringatan Hari Jadi PT Bojeri. Seluruh karyawan mendapat hari libur khusus dari perusahaan.',
            ];

            // Create boolean arrays for realistic distribution
            $isPaidArray = array_merge(array_fill(0, 22, true), array_fill(0, 8, false));
            $isGoogleSyncArray = array_merge(array_fill(0, 12, true), array_fill(0, 18, false));
            $isOutlookSyncArray = array_merge(array_fill(0, 10, true), array_fill(0, 20, false));
            
            shuffle($isPaidArray);
            shuffle($isGoogleSyncArray);
            shuffle($isOutlookSyncArray);

            $holidays = [];
            for ($i = 0; $i < 20; $i++) {
                $startDaysAgo = 175 - ($i * 5);
                $createdDaysAgo = $startDaysAgo - 1;
                
                // Some holidays are single day, others are multi-day
                $isMultiDay = ($i % 4 === 0); // Every 4th holiday is multi-day
                $endDaysAgo = $isMultiDay ? $startDaysAgo - rand(1, 3) : $startDaysAgo;

                $holidays[] = [
                    'name' => $holidayNames[$i],
                    'start_date' => Carbon::now()->subDays($startDaysAgo)->format('Y-m-d'),
                    'end_date' => Carbon::now()->subDays($endDaysAgo)->format('Y-m-d'),
                    'holiday_type_id' => $holidayTypes[$i % count($holidayTypes)],
                    'description' => $descriptions[$i],
                    'is_paid' => $isPaidArray[$i],
                    'is_sync_google_calendar' => $isGoogleSyncArray[$i],
                    'is_sync_outlook_calendar' => $isOutlookSyncArray[$i],
                    'created_at' => Carbon::now()->subDays($createdDaysAgo)->addHours(rand(8, 17))->addMinutes(rand(0, 59)),
                    'updated_at' => Carbon::now()->subDays($createdDaysAgo)->addHours(rand(8, 17))->addMinutes(rand(0, 59)),
                ];
            }

            foreach ($holidays as $holiday) {
                Holiday::updateOrCreate(
                    [
                        'name' => $holiday['name'],
                        'start_date' => $holiday['start_date'],
                        'created_by' => $userId
                    ],
                    array_merge($holiday, [
                        'creator_id' => $userId,
                        'created_by' => $userId,
                    ])
                );
            }
        }
    }
}
