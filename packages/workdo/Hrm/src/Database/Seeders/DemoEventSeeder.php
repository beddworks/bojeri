<?php

namespace Workdo\Hrm\Database\Seeders;

use Workdo\Hrm\Models\Event;
use Workdo\Hrm\Models\EventType;
use Workdo\Hrm\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Workdo\Hrm\Models\Employee;

class DemoEventSeeder extends Seeder
{
    public function run($userId): void
    {
        if (!empty($userId)) {
            $eventTypes = EventType::where('created_by', $userId)->pluck('id')->toArray();
            $users = User::whereIn('id', Employee::where('created_by', $userId)->pluck('user_id'))
                ->where('created_by', $userId)
                ->pluck('id')
                ->toArray();
            $departments = Department::where('created_by', $userId)->pluck('id')->toArray();

            if (empty($eventTypes) || empty($departments)) {
                return;
            }

            Event::where('created_by', $userId)->delete();

            $events = [
                ['title' => 'Rapat Evaluasi Tahunan PT Bojeri 2025', 'description' => 'Rapat strategis tahunan seluruh divisi PT Bojeri membahas pencapaian target produksi, penjualan, dan rencana pengembangan bisnis furnitur tahun 2025.', 'location' => 'Aula Utama', 'start_date' => Carbon::now()->subDays(175)->addHours(9)->addMinutes(0), 'end_date' => Carbon::now()->subDays(175)->addHours(9)->addMinutes(0), 'start_time' => '09:00', 'end_time' => '12:00', 'status' => 'approved', 'color' => '#3b82f6', 'created_at' => Carbon::now()->subDays(175)->addHours(9)->addMinutes(0)],
                ['title' => 'Training K3 & Keselamatan Kerja Pabrik', 'description' => 'Pelatihan wajib Keselamatan dan Kesehatan Kerja (K3) bagi seluruh karyawan produksi. Meliputi prosedur penggunaan alat, pencegahan kecelakaan, dan penanganan darurat di lantai produksi.', 'location' => 'Workshop Room', 'start_date' => Carbon::now()->subDays(165)->addHours(8)->addMinutes(0), 'end_date' => Carbon::now()->subDays(165)->addHours(8)->addMinutes(0), 'start_time' => '08:00', 'end_time' => '17:00', 'status' => 'approved', 'color' => '#ef4444', 'created_at' => Carbon::now()->subDays(165)->addHours(8)->addMinutes(0)],
                ['title' => 'Pameran Furniture Indonesia (IFI) 2025', 'description' => 'Keikutsertaan PT Bojeri dalam Pameran Furnitur Indonesia 2025 di Jakarta Convention Center. Tim Sales, Desain, dan Produksi akan mempresentasikan koleksi terbaru kepada para pembeli potensial.', 'location' => 'Jakarta Convention Center', 'start_date' => Carbon::now()->subDays(150)->addHours(9)->addMinutes(0), 'end_date' => Carbon::now()->subDays(148)->addHours(9)->addMinutes(0), 'start_time' => '09:00', 'end_time' => '18:00', 'status' => 'approved', 'color' => '#10b981', 'created_at' => Carbon::now()->subDays(160)->addHours(10)->addMinutes(0)],
                ['title' => 'Evaluasi Kinerja Karyawan Semester I 2025', 'description' => 'Sesi penilaian kinerja karyawan Semester I 2025. Setiap karyawan akan bertemu langsung dengan manajer departemen untuk membahas pencapaian, kendala, dan target semester berikutnya.', 'location' => 'Conference Room A', 'start_date' => Carbon::now()->subDays(130)->addHours(9)->addMinutes(0), 'end_date' => Carbon::now()->subDays(130)->addHours(9)->addMinutes(0), 'start_time' => '09:00', 'end_time' => '17:00', 'status' => 'approved', 'color' => '#8b5cf6', 'created_at' => Carbon::now()->subDays(140)->addHours(9)->addMinutes(0)],
                ['title' => 'Buka Bersama Ramadan 1446 H PT Bojeri', 'description' => 'Acara buka puasa bersama seluruh karyawan PT Bojeri dalam menyambut bulan suci Ramadan 1446 H. Dihadiri oleh direksi, manajemen, dan seluruh karyawan dari tiga cabang.', 'location' => 'Cafeteria', 'start_date' => Carbon::now()->subDays(120)->addHours(17)->addMinutes(0), 'end_date' => Carbon::now()->subDays(120)->addHours(17)->addMinutes(0), 'start_time' => '17:00', 'end_time' => '19:30', 'status' => 'approved', 'color' => '#f59e0b', 'created_at' => Carbon::now()->subDays(135)->addHours(10)->addMinutes(0)],
                ['title' => 'Pertemuan Pelanggan Korporat Q2 2025', 'description' => 'Pertemuan khusus dengan pelanggan korporat utama PT Bojeri — Hotel Santika, PT Maju Bersama, dan PT Graha Properti — untuk mendiskusikan kebutuhan furnitur proyek Q3 2025.', 'location' => 'Board Room', 'start_date' => Carbon::now()->subDays(100)->addHours(14)->addMinutes(0), 'end_date' => Carbon::now()->subDays(100)->addHours(14)->addMinutes(0), 'start_time' => '14:00', 'end_time' => '16:00', 'status' => 'pending', 'color' => '#06b6d4', 'created_at' => Carbon::now()->subDays(110)->addHours(9)->addMinutes(0)],
                ['title' => 'Pelatihan Teknik Ukir & Finishing Furnitur Premium', 'description' => 'Workshop teknis untuk tim pengrajin dan finishing PT Bojeri — meliputi teknik ukir kayu jati, teknik finishing lacquer premium, dan standar quality control produk ekspor.', 'location' => 'Workshop Room', 'start_date' => Carbon::now()->subDays(80)->addHours(8)->addMinutes(0), 'end_date' => Carbon::now()->subDays(79)->addHours(8)->addMinutes(0), 'start_time' => '08:00', 'end_time' => '17:00', 'status' => 'approved', 'color' => '#7c3aed', 'created_at' => Carbon::now()->subDays(90)->addHours(9)->addMinutes(0)],
                ['title' => 'Family Gathering PT Bojeri 2025', 'description' => 'Acara Family Gathering tahunan PT Bojeri yang diikuti seluruh karyawan beserta keluarga. Kegiatan meliputi senam bersama, games, lomba anak-anak, makan siang bersama, dan pembagian doorprize.', 'location' => 'Gedung Serbaguna Pantai Indah', 'start_date' => Carbon::now()->subDays(60)->addHours(7)->addMinutes(0), 'end_date' => Carbon::now()->subDays(60)->addHours(7)->addMinutes(0), 'start_time' => '07:00', 'end_time' => '17:00', 'status' => 'approved', 'color' => '#f97316', 'created_at' => Carbon::now()->subDays(75)->addHours(9)->addMinutes(0)],
                ['title' => 'Customer Appreciation Event - Showroom Jakarta', 'description' => 'Acara apresiasi pelanggan setia PT Bojeri di Showroom Jakarta Selatan. Meliputi presentasi koleksi terbaru, cicip produk baru, penawaran eksklusif, dan networking makan malam bersama.', 'location' => 'Showroom Jakarta Selatan', 'start_date' => Carbon::now()->subDays(40)->addHours(16)->addMinutes(0), 'end_date' => Carbon::now()->subDays(40)->addHours(16)->addMinutes(0), 'start_time' => '16:00', 'end_time' => '20:00', 'status' => 'approved', 'color' => '#059669', 'created_at' => Carbon::now()->subDays(50)->addHours(10)->addMinutes(0)],
                ['title' => 'Rapat Kinerja Q3 & Proyeksi Q4 2025', 'description' => 'Evaluasi kinerja penjualan dan produksi Q3 2025 serta penyusunan proyeksi target Q4 sebelum akhir tahun. Dihadiri oleh kepala divisi, manajer penjualan, dan manajer produksi.', 'location' => 'Executive Lounge', 'start_date' => Carbon::now()->subDays(25)->addHours(10)->addMinutes(0), 'end_date' => Carbon::now()->subDays(25)->addHours(10)->addMinutes(0), 'start_time' => '10:00', 'end_time' => '15:00', 'status' => 'reject', 'color' => '#dc2626', 'created_at' => Carbon::now()->subDays(30)->addHours(9)->addMinutes(0)],
                ['title' => 'Pameran IndoBuildTech 2026', 'description' => 'Partisipasi PT Bojeri dalam pameran konstruksi dan furnitur terbesar Indonesia — IndoBuildTech 2026 di JIExpo Kemayoran. Persiapan booth, dekorasi, dan materi marketing harus rampung H-7.', 'location' => 'JIExpo Kemayoran', 'start_date' => Carbon::now()->addDays(15), 'end_date' => Carbon::now()->addDays(17), 'start_time' => '09:00', 'end_time' => '18:00', 'status' => 'pending', 'color' => '#14b8a6', 'created_at' => Carbon::now()->subDays(10)->addHours(10)->addMinutes(0)],
                ['title' => 'Rapat Strategi Ekspansi Showroom Baru 2026', 'description' => 'Rapat strategis manajemen PT Bojeri membahas rencana pembukaan showroom baru di Surabaya Timur dan Bali. Agenda meliputi analisis pasar, kebutuhan investasi, dan timeline implementasi.', 'location' => 'Board Room', 'start_date' => Carbon::now()->addDays(30), 'end_date' => Carbon::now()->addDays(30), 'start_time' => '09:00', 'end_time' => '17:00', 'status' => 'approved', 'color' => '#6366f1', 'created_at' => Carbon::now()->subDays(5)->addHours(10)->addMinutes(0)],
            ];

            foreach ($events as $index => $eventData) {
                $approvedBy = null;
                if ($eventData['status'] === 'approved') {
                    $approvedBy = !empty($users) ? $users[$index % count($users)] : $userId;
                }

                $event = Event::updateOrCreate(
                    [
                        'title' => $eventData['title'],
                        'start_date' => $eventData['start_date']->toDateString(),
                        'created_by' => $userId
                    ],
                    [
                        'description' => $eventData['description'],
                        'event_type_id' => $eventTypes[$index % count($eventTypes)],
                        'end_date' => $eventData['end_date']->toDateString(),
                        'start_time' => $eventData['start_time'],
                        'end_time' => $eventData['end_time'],
                        'location' => $eventData['location'],
                        'status' => $eventData['status'],
                        'color' => $eventData['color'],
                        'approved_by' => $approvedBy,
                        'creator_id' => $userId,
                        'created_by' => $userId,
                        'created_at' => $eventData['created_at'],
                        'updated_at' => $eventData['created_at']
                    ]
                );

                $selectedDepartments = array_rand(array_flip($departments), min(rand(1, 3), count($departments)));
                if (!is_array($selectedDepartments)) {
                    $selectedDepartments = [$selectedDepartments];
                }

                $departmentData = [];
                foreach ($selectedDepartments as $departmentId) {
                    $departmentData[$departmentId] = [
                        'creator_id' => $userId,
                        'created_by' => $userId,
                    ];
                }
                $event->departments()->sync($departmentData);
            }
        }
    }
}
