<?php

namespace Workdo\Hrm\Database\Seeders;

use Workdo\Hrm\Models\HrmDocument;
use Workdo\Hrm\Models\DocumentCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Workdo\Hrm\Models\Employee;

class DemoHrmDocumentSeeder extends Seeder
{
    public function run($userId): void
    {
        if (!empty($userId)) {
            $users = User::whereIn('id', Employee::where('created_by', $userId)->pluck('user_id'))
                ->where('created_by', $userId)
                ->pluck('id')
                ->toArray();

            $uploaders = !empty($users) ? array_slice($users, 0, min(5, count($users))) : [$userId];
            $approvers = !empty($users) ? array_slice($users, 0, min(3, count($users))) : [$userId];

            HrmDocument::where('created_by', $userId)->delete();

            $documents = [
                ['title' => 'Peraturan Perusahaan PT Bojeri 2025', 'category' => 'Legal Documents', 'desc' => 'Dokumen resmi peraturan perusahaan PT Bojeri yang mengatur hak dan kewajiban karyawan, tata tertib kerja, sanksi disiplin, dan prosedur penyelesaian perselisihan hubungan industrial.', 'status' => 'approve', 'created_at' => Carbon::now()->subDays(175)->addHours(9)->addMinutes(0)],
                ['title' => 'SOP Produksi Furnitur Kayu Jati Premium', 'category' => 'Legal Documents', 'desc' => 'Standar Operasional Prosedur (SOP) proses produksi furnitur kayu jati mulai dari seleksi bahan baku, proses pengeringan kayu, pembentukan, ukiran, sampai proses finishing dan quality check akhir.', 'status' => 'approve', 'created_at' => Carbon::now()->subDays(165)->addHours(10)->addMinutes(0)],
                ['title' => 'Panduan Keselamatan & Kesehatan Kerja (K3)', 'category' => 'Legal Documents', 'desc' => 'Panduan lengkap K3 untuk seluruh karyawan produksi PT Bojeri. Mencakup prosedur penggunaan APD, penanganan mesin berbahaya, prosedur evakuasi darurat, dan pelaporan insiden kecelakaan kerja.', 'status' => 'approve', 'created_at' => Carbon::now()->subDays(155)->addHours(9)->addMinutes(0)],
                ['title' => 'Perjanjian Kerja Waktu Tertentu (PKWT)', 'category' => 'Contract Documents', 'desc' => 'Template perjanjian kerja waktu tertentu (kontrak) yang digunakan PT Bojeri untuk karyawan kontrak. Mencakup lingkup pekerjaan, jangka waktu kontrak, kompensasi, dan hak-hak karyawan selama masa kontrak.', 'status' => 'approve', 'created_at' => Carbon::now()->subDays(150)->addHours(9)->addMinutes(0)],
                ['title' => 'Formulir Pengajuan Cuti & Izin Karyawan', 'category' => 'Employment Records', 'desc' => 'Formulir standar pengajuan cuti tahunan, cuti sakit, cuti melahirkan, dan izin khusus bagi karyawan PT Bojeri. Dilengkapi dengan prosedur approvals dan batas waktu pengajuan.', 'status' => 'approve', 'created_at' => Carbon::now()->subDays(145)->addHours(9)->addMinutes(0)],
                ['title' => 'SOP Quality Control Produk Furnitur', 'category' => 'Professional Licenses', 'desc' => 'Standar prosedur quality control produk furnitur PT Bojeri sebelum pengiriman ke pelanggan. Mencakup checklist dimensi, finishing, kekuatan sambungan, dan standar kehalus-an permukaan sesuai spesifikasi produk.', 'status' => 'approve', 'created_at' => Carbon::now()->subDays(140)->addHours(9)->addMinutes(0)],
                ['title' => 'Kebijakan Pemberian THR & Bonus Karyawan', 'category' => 'Financial Documents', 'desc' => 'Kebijakan resmi PT Bojeri mengenai tata cara perhitungan dan pemberian Tunjangan Hari Raya (THR), bonus tahunan, dan insentif kinerja. Mencakup syarat penerima, cara perhitungan, dan jadwal pembayaran.', 'status' => 'approve', 'created_at' => Carbon::now()->subDays(130)->addHours(9)->addMinutes(0)],
                ['title' => 'Panduan Penggajian & Prosedur Potongan BPJS', 'category' => 'Financial Documents', 'desc' => 'Panduan lengkap prosedur penggajian bulanan PT Bojeri termasuk komponen gaji, cara perhitungan potongan BPJS Kesehatan, BPJS Ketenagakerjaan, PPh 21, dan prosedur transfer gaji karyawan.', 'status' => 'pending', 'created_at' => Carbon::now()->subDays(120)->addHours(9)->addMinutes(0)],
                ['title' => 'Sertifikat Pelatihan K3 Karyawan Produksi', 'category' => 'Training Certificates', 'desc' => 'Rekap sertifikat pelatihan K3 yang telah diikuti oleh karyawan divisi produksi PT Bojeri. Dokumen ini wajib diperbarui setiap tahun sebagai bukti kepatuhan terhadap regulasi keselamatan kerja nasional.', 'status' => 'approve', 'created_at' => Carbon::now()->subDays(110)->addHours(9)->addMinutes(0)],
                ['title' => 'SOP Penerimaan & Pemeriksaan Stok Material', 'category' => 'Professional Licenses', 'desc' => 'Prosedur standar penerimaan material bahan baku (kayu jati, rotan, kain, besi) dari vendor ke gudang PT Bojeri. Mencakup prosedur pengecekan kualitas, pencatatan stok, dan penanganan material reject.', 'status' => 'pending', 'created_at' => Carbon::now()->subDays(100)->addHours(9)->addMinutes(0)],
                ['title' => 'Perjanjian Kerahasiaan Data Pelanggan (NDA)', 'category' => 'Contract Documents', 'desc' => 'Non-Disclosure Agreement (NDA) yang wajib ditandatangani oleh seluruh karyawan PT Bojeri yang memiliki akses terhadap data pelanggan, harga khusus, dan desain produk eksklusif yang merupakan rahasia dagang perusahaan.', 'status' => 'approve', 'created_at' => Carbon::now()->subDays(90)->addHours(9)->addMinutes(0)],
                ['title' => 'Polis Asuransi BPJS Ketenagakerjaan', 'category' => 'Insurance Papers', 'desc' => 'Dokumentasi polis asuransi BPJS Ketenagakerjaan seluruh karyawan PT Bojeri yang mencakup Jaminan Kecelakaan Kerja (JKK), Jaminan Kematian (JKM), Jaminan Hari Tua (JHT), dan Jaminan Pensiun.', 'status' => 'approve', 'created_at' => Carbon::now()->subDays(80)->addHours(9)->addMinutes(0)],
                ['title' => 'SOP Logistik & Prosedur Pengiriman Furnitur', 'category' => 'Legal Documents', 'desc' => 'Standar operasional prosedur pengiriman produk furnitur kepada pelanggan korporat, ritel, dan ekspor. Mencakup prosedur pengemasan, loading, pengisian surat jalan, dan prosedur konfirmasi penerimaan barang.', 'status' => 'approve', 'created_at' => Carbon::now()->subDays(70)->addHours(9)->addMinutes(0)],
                ['title' => 'Panduan Evaluasi Kinerja Karyawan', 'category' => 'Performance Reviews', 'desc' => 'Panduan resmi pelaksanaan evaluasi kinerja semi-tahunan karyawan PT Bojeri. Mencakup form penilaian, kriteria penilaian kinerja, prosedur feedback atasan ke bawahan, dan tata cara penetapan target kinerja periode berikutnya.', 'status' => 'approve', 'created_at' => Carbon::now()->subDays(60)->addHours(9)->addMinutes(0)],
                ['title' => 'Kontrak Kerjasama Distributor & Agen Penjualan', 'category' => 'Contract Documents', 'desc' => 'Template kontrak kerjasama resmi antara PT Bojeri dengan distributor dan agen penjualan furnitur di seluruh Indonesia. Mengatur hak eksklusivitas wilayah, target penjualan, diskon distributor, dan ketentuan putus mitra.', 'status' => 'reject', 'created_at' => Carbon::now()->subDays(50)->addHours(9)->addMinutes(0)],
            ];

            foreach ($documents as $index => $document) {
                $categoryRecord = DocumentCategory::where('document_type', $document['category'])
                    ->where('created_by', $userId)
                    ->first();

                if (!$categoryRecord) {
                    $categoryRecord = DocumentCategory::where('created_by', $userId)->first();
                }

                $uploadedBy = $uploaders[$index % count($uploaders)];
                $finalApprovedBy = $document['status'] === 'approve' ? $approvers[$index % count($approvers)] : null;
                $effectiveDate = $document['status'] != 'pending' ? $document['created_at']->toDateString() : null;
                $randomDocument = 'hrm_document' . rand(1, 3) . '.png';

                HrmDocument::updateOrCreate(
                    [
                        'title' => $document['title'],
                        'created_by' => $userId
                    ],
                    [
                        'description' => $document['desc'],
                        'document_category_id' => $categoryRecord?->id,
                        'document' => $randomDocument,
                        'effective_date' => $effectiveDate,
                        'status' => $document['status'],
                        'uploaded_by' => $uploadedBy,
                        'approved_by' => $finalApprovedBy,
                        'creator_id' => $userId,
                        'created_by' => $userId,
                        'created_at' => $document['created_at'],
                        'updated_at' => $document['created_at']
                    ]
                );
            }
        }
    }
}
