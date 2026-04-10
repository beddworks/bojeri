<?php

namespace Database\Seeders;

use App\Models\HelpdeskTicket;
use App\Models\HelpdeskCategory;
use Illuminate\Database\Seeder;

class HelpdeskTicketSeeder extends Seeder
{
    public function run($userId): void
    {
        $categories = HelpdeskCategory::get()->pluck('id')->toArray();

        if (empty($categories)) {
            return;
        }

        HelpdeskTicket::where('created_by', $userId)->delete();

        $tickets = [
            ['ticket_id' => 12345001, 'title' => 'Tidak Bisa Login ke Dashboard Admin Sistem', 'description' => 'Akun admin saya tidak bisa login ke dashboard sistem sejak pagi tadi. Muncul pesan error "Invalid credentials" padahal password sudah benar. Sudah dicoba di browser lain dan hasilnya sama.', 'status' => 'open', 'priority' => 'high', 'category_id' => $categories[0] ?? 1],
            ['ticket_id' => 12345002, 'title' => 'Data Stok Tidak Update Setelah Input Penerimaan Barang', 'description' => 'Setelah input penerimaan material kayu jati dari CV Kayu Jati Indah (PO-20251105), stok di sistem tidak bertambah. Data stok di Gudang Produksi masih menunjukkan angka lama. Hal ini menyebabkan perbedaan dengan stok fisik.', 'status' => 'in_progress', 'priority' => 'urgent', 'category_id' => $categories[0] ?? 1],
            ['ticket_id' => 12345003, 'title' => 'Error Saat Mencetak Surat Jalan Pengiriman', 'description' => 'Ketika mencoba print surat jalan untuk pengiriman ke Hotel Santika Jakarta, sistem menampilkan pesan error "Template not found". Masalah ini terjadi sejak 3 hari terakhir dan menghambat proses pengiriman ke pelanggan.', 'status' => 'open', 'priority' => 'medium', 'category_id' => $categories[0] ?? 1],
            ['ticket_id' => 12345004, 'title' => 'Laporan Penjualan Bulanan Tidak Bisa Diexport Excel', 'description' => 'Fitur export laporan penjualan ke format Excel (XLSX) tidak berfungsi. Tombol export diklik tapi tidak ada file yang terdownload. Sepertinya masalah ini mulai terjadi setelah update sistem minggu lalu.', 'status' => 'resolved', 'priority' => 'medium', 'category_id' => $categories[0] ?? 1, 'resolved_at' => now()->subDays(2)],
            ['ticket_id' => 12345005, 'title' => 'Pembayaran Vendor Belum Tercatat di Modul Akuntansi', 'description' => 'Transfer pembayaran ke PT Kain Sofa Makmur sebesar Rp 8.500.000 sudah dilakukan melalui BCA tanggal 3 November 2025, namun status di modul Account Vendor Payments masih "Pending". Mohon dicek dan update statusnya.', 'status' => 'closed', 'priority' => 'high', 'category_id' => $categories[0] ?? 1],
            ['ticket_id' => 12345006, 'title' => 'Permintaan Akses Modul HRM untuk Manajer Baru', 'description' => 'Kami meminta penambahan akses modul HRM (submodul Payroll, Attendance, Leave Management) untuk karyawan baru kami sebagai Manajer SDM. Akun sudah dibuat tapi belum mendapat permission yang sesuai.', 'status' => 'open', 'priority' => 'low', 'category_id' => $categories[0] ?? 1],
            ['ticket_id' => 12345007, 'title' => 'Email Notifikasi ke Pelanggan Tidak Terkirim', 'description' => 'Setelah membuat Sales Invoice baru untuk PT Maju Bersama, email notifikasi ke pelanggan tidak terkirim secara otomatis. Sudah dicek di spam folder pelanggan dan memang tidak ada. Tolong periksa konfigurasi SMTP dan email template.', 'status' => 'in_progress', 'priority' => 'high', 'category_id' => $categories[0] ?? 1],
            ['ticket_id' => 12345008, 'title' => 'Data Karyawan Terduplikasi Setelah Import dari Excel', 'description' => 'Setelah melakukan import data karyawan baru dari file Excel, beberapa karyawan muncul dua kali di sistem HRM. Total ada 5 duplikasi. Mohon bantu untuk membersihkan data dan mencegah duplikasi saat import berikutnya.', 'status' => 'resolved', 'priority' => 'medium', 'category_id' => $categories[0] ?? 1, 'resolved_at' => now()->subDays(1)],
            ['ticket_id' => 12345009, 'title' => 'Bukti Pembayaran Customer Tidak Bisa Diupload', 'description' => 'Di modul Customer Payments, saat mencoba upload foto bukti transfer dari Café Kopi Nusantara, sistem menampilkan error "File size exceeded" padahal ukuran file sudah di bawah 2MB. Sudah dicoba berbagai format (JPG dan PDF) dengan hasil yang sama.', 'status' => 'open', 'priority' => 'high', 'category_id' => $categories[0] ?? 1],
            ['ticket_id' => 12345010, 'title' => 'Request Fitur: Filter Produk Berdasarkan Kategori di POS', 'description' => 'Kasir di semua showroom meminta penambahan fitur filter produk berdasarkan kategori (Sofa, Meja, Kursi, Lemari, dll.) di tampilan POS. Saat ini semua produk ditampilkan bersamaan yang membuat proses pencarian produk lambat terutama saat showroom ramai.', 'status' => 'open', 'priority' => 'low', 'category_id' => $categories[0] ?? 1],
        ];
        foreach ($tickets as $ticket) {
            HelpdeskTicket::firstOrCreate(
                ['ticket_id' => $ticket['ticket_id']],
                array_merge($ticket, [
                    'created_by' => $userId,
                    'created_at' => now()->subDays(rand(1, 30)),
                ])
            );
        }
    }
}
