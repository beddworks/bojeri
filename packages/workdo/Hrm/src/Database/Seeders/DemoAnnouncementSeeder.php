<?php

namespace Workdo\Hrm\Database\Seeders;

use Workdo\Hrm\Models\Announcement;
use Illuminate\Database\Seeder;
use Workdo\Hrm\Models\AnnouncementCategory;
use Workdo\Hrm\Models\Department;
use Carbon\Carbon;
use App\Models\User;

class DemoAnnouncementSeeder extends Seeder
{
    public function run($userId): void
    {
        if (!empty($userId)) {
            $categories = AnnouncementCategory::where('created_by', $userId)->pluck('id')->toArray();
            $departments = Department::where('created_by', $userId)->pluck('id')->toArray();
            $users = User::whereIn('id', \Workdo\Hrm\Models\Employee::where('created_by', $userId)->pluck('user_id'))
                ->where('created_by', $userId)
                ->pluck('id')
                ->toArray();

            if (empty($categories) || empty($departments)) {
                return;
            }

            Announcement::where('created_by', $userId)->delete();

            $announcements = [
                ['title' => 'Pengumuman THR Hari Raya Idul Fitri 1447 H', 'description' => 'PT Bojeri akan membagikan Tunjangan Hari Raya (THR) Idul Fitri 1447 H kepada seluruh karyawan tetap dan kontrak. Pembayaran dilakukan paling lambat H-7 sebelum Hari Raya. Besaran THR mengacu pada peraturan ketenagakerjaan yang berlaku.', 'priority' => 'high', 'status' => 'active', 'start_date' => Carbon::now()->subDays(175)->addHours(9)->addMinutes(0), 'end_date' => Carbon::now()->addDays(200), 'created_at' => Carbon::now()->subDays(175)->addHours(9)->addMinutes(0)],
                ['title' => 'Update Kebijakan Lembur & Jam Kerja Shift Produksi', 'description' => 'Mulai bulan depan, kebijakan lembur dan jadwal shift produksi PT Bojeri akan diperbarui. Karyawan shift pagi dan shift kantor diwajibkan mengisi form lembur sebelum H-1 melalui sistem HRM. Uang lembur dibayarkan bersamaan dengan gaji bulanan.', 'priority' => 'high', 'status' => 'active', 'start_date' => Carbon::now()->subDays(165)->addHours(10)->addMinutes(0), 'end_date' => Carbon::now()->addDays(190), 'created_at' => Carbon::now()->subDays(165)->addHours(10)->addMinutes(0)],
                ['title' => 'Pembukaan Showroom Baru PT Bojeri di Surabaya Timur', 'description' => 'PT Bojeri dengan bangga mengumumkan pembukaan showroom baru di kawasan Surabaya Timur pada kuartal berikutnya. Showroom ini akan menampilkan seluruh koleksi furnitur kayu jati premium dan furnitur minimalis modern. Karyawan cabang Surabaya diminta berpartisipasi aktif dalam persiapan.', 'priority' => 'medium', 'status' => 'active', 'start_date' => Carbon::now()->subDays(155)->addHours(11)->addMinutes(0), 'end_date' => Carbon::now()->addDays(180), 'created_at' => Carbon::now()->subDays(155)->addHours(11)->addMinutes(0)],
                ['title' => 'Penyesuaian Daftar Harga Produk Furnitur 2026', 'description' => 'Sehubungan dengan kenaikan harga bahan baku kayu dan kain, PT Bojeri akan melakukan penyesuaian harga jual produk furnitur mulai Januari 2026. Tim Sales diwajibkan menggunakan daftar harga terbaru (Price List Rev.4-2026) dalam setiap penawaran kepada pelanggan.', 'priority' => 'high', 'status' => 'draft', 'start_date' => Carbon::now()->subDays(145)->addHours(9)->addMinutes(0), 'end_date' => Carbon::now()->addDays(120), 'created_at' => Carbon::now()->subDays(145)->addHours(9)->addMinutes(0)],
                ['title' => 'Program Cuti Bersama Lebaran Idul Fitri 1447 H', 'description' => 'Direksi PT Bojeri menetapkan jadwal cuti bersama Lebaran Idul Fitri 1447 H. Seluruh karyawan mendapat libur dari tanggal 20-24 Maret 2026. Operasional pabrik dan showroom akan berhenti selama periode libur ini. Karyawan yang bertugas jaga akan mendapat kompensasi sesuai peraturan perusahaan.', 'priority' => 'high', 'status' => 'active', 'start_date' => Carbon::now()->subDays(120)->addHours(9)->addMinutes(0), 'end_date' => Carbon::now()->addDays(80), 'created_at' => Carbon::now()->subDays(120)->addHours(9)->addMinutes(0)],
                ['title' => 'Penerimaan Karyawan Baru Divisi Produksi & Pengrajin', 'description' => 'PT Bojeri membuka lowongan kerja untuk posisi Pengrajin Kayu Senior, Operator CNC, dan Staf Quality Control di Divisi Produksi. Karyawan yang memiliki rekomendasi dapat menyampaikan referral ke HRD. Bonus referral senilai Rp500.000 akan diberikan jika kandidat diterima dan melewati masa probasi.', 'priority' => 'medium', 'status' => 'active', 'start_date' => Carbon::now()->subDays(100)->addHours(10)->addMinutes(0), 'end_date' => Carbon::now()->addDays(60), 'created_at' => Carbon::now()->subDays(100)->addHours(10)->addMinutes(0)],
                ['title' => 'Pelatihan ISO 9001:2015 Wajib Seluruh Karyawan Produksi', 'description' => 'Dalam rangka persiapan sertifikasi ISO 9001:2015, seluruh karyawan divisi produksi, QC, dan logistik wajib mengikuti pelatihan yang dijadwalkan bulan ini. Kehadiran minimal 90% menjadi syarat untuk mendapatkan sertifikat pelatihan. Ketidakhadiran tanpa izin akan dicatat dalam evaluasi kinerja.', 'priority' => 'high', 'status' => 'active', 'start_date' => Carbon::now()->subDays(80)->addHours(8)->addMinutes(0), 'end_date' => Carbon::now()->addDays(30), 'created_at' => Carbon::now()->subDays(80)->addHours(8)->addMinutes(0)],
                ['title' => 'Renovasi Area Lantai Produksi & Penggantian Mesin', 'description' => 'Proses renovasi lantai produksi Gudang Jakarta akan berlangsung selama 3 minggu ke depan. Sebagian operasional produksi sementara dipindahkan ke Gudang Bandung. Karyawan yang terdampak akan menerima informasi lebih lanjut dari supervisor masing-masing. Keselamatan kerja selama renovasi menjadi prioritas utama.', 'priority' => 'medium', 'status' => 'active', 'start_date' => Carbon::now()->subDays(60)->addHours(9)->addMinutes(0), 'end_date' => Carbon::now()->addDays(10), 'created_at' => Carbon::now()->subDays(60)->addHours(9)->addMinutes(0)],
                ['title' => 'Pengumuman Kenaikan Gaji Tahunan Karyawan 2025', 'description' => 'PT Bojeri mengumumkan kenaikan gaji tahunan bagi seluruh karyawan tetap yang telah melewati evaluasi kinerja dengan nilai minimal B. Penyesuaian gaji berlaku efektif mulai bulan depan. Karyawan dapat mengkonfirmasi besaran kenaikan gaji kepada atasan langsung atau HRD.', 'priority' => 'high', 'status' => 'inactive', 'start_date' => Carbon::now()->subDays(40)->addHours(9)->addMinutes(0), 'end_date' => Carbon::now()->subDays(10), 'created_at' => Carbon::now()->subDays(40)->addHours(9)->addMinutes(0)],
                ['title' => 'Perubahan SOP Pengiriman & Prosedur Logistik', 'description' => 'Efektif bulan depan, PT Bojeri memberlakukan SOP baru untuk proses pengiriman barang ke pelanggan. Perubahan utama meliputi: wajib cetak surat jalan melalui sistem, foto bukti kirim wajib diupload di hari yang sama, dan konfirmasi penerimaan barang oleh pelanggan melalui WhatsApp resmi PT Bojeri.', 'priority' => 'medium', 'status' => 'active', 'start_date' => Carbon::now()->subDays(20)->addHours(9)->addMinutes(0), 'end_date' => Carbon::now()->addDays(365), 'created_at' => Carbon::now()->subDays(20)->addHours(9)->addMinutes(0)],
            ];

            foreach ($announcements as $index => $announcementData) {
                $approvedBy = null;
                if ($announcementData['status'] === 'active') {
                    $approvedBy = !empty($users) ? $users[$index % count($users)] : $userId;
                }

                $announcement = Announcement::updateOrCreate(
                    [
                        'title' => $announcementData['title'],
                        'created_by' => $userId
                    ],
                    [
                        'description' => $announcementData['description'],
                        'start_date' => $announcementData['start_date']->toDateString(),
                        'end_date' => $announcementData['end_date']->toDateString(),
                        'priority' => $announcementData['priority'],
                        'status' => $announcementData['status'],
                        'announcement_category_id' => $categories[$index % count($categories)],
                        'creator_id' => $userId,
                        'created_by' => $userId,
                        'approved_by' => $approvedBy,
                        'created_at' => $announcementData['created_at'],
                        'updated_at' => $announcementData['created_at']
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
                $announcement->departments()->sync($departmentData);
            }
        }
    }
}
