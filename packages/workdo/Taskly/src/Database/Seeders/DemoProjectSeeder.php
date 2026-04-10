<?php

namespace Workdo\Taskly\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Workdo\Taskly\Models\Project;
use Workdo\Taskly\Models\ProjectFile;

class DemoProjectSeeder extends Seeder
{
    public function run($userId): void
    {
        if (!empty($userId)) {

            $projects = [
                // PT Bojeri — 4 active projects (Section 18 of bojeri_seeder.md)
                [
                    'name'        => 'Interior Kantor PT Maju Bersama',
                    'description' => 'Pengadaan dan instalasi furnitur lengkap untuk kantor PT Maju Bersama, meliputi meja kerja, kursi ergonomis, partisi ruang, dan lemari arsip untuk 3 lantai gedung (±120 workstation).',
                    'budget'      => 8500000,
                    'start_date'  => now()->subDays(45),
                    'end_date'    => now()->addDays(30),
                    'status'      => 'Ongoing',
                ],
                [
                    'name'        => 'Renovasi Lobby Hotel Santika',
                    'description' => 'Desain dan produksi furnitur lobby premium Hotel Santika Jakarta — sofa lounge custom, meja resepsionis, rak display, dan sitting area untuk area lobby dan koridor utama.',
                    'budget'      => 12000000,
                    'start_date'  => now()->subDays(20),
                    'end_date'    => now()->addDays(60),
                    'status'      => 'Onhold',
                ],
                [
                    'name'        => 'Dining Set Restoran Padang Emas',
                    'description' => 'Produksi 40 set meja makan kayu jati 4-kursi untuk Restoran Padang Emas. Pekerjaan meliputi desain, produksi, finishing, pengiriman, dan pemasangan. Selesai tepat waktu.',
                    'budget'      => 3400000,
                    'start_date'  => now()->subDays(90),
                    'end_date'    => now()->subDays(5),
                    'status'      => 'Finished',
                ],
                [
                    'name'        => 'Paket Bedroom Perumahan Asri',
                    'description' => 'Pengadaan 100 paket furnitur kamar tidur standar (ranjang 160×200, lemari 3 pintu, meja rias, nakas) untuk Developer Perumahan Asri. Proyek terbesar PT Bojeri tahun ini.',
                    'budget'      => 51000000,
                    'start_date'  => now()->subDays(10),
                    'end_date'    => now()->addDays(120),
                    'status'      => 'Ongoing',
                ],
            ];


            foreach ($projects as $projectData) {
                $project = Project::updateOrCreate(
                    ['name' => $projectData['name'], 'created_by' => $userId],
                    array_merge($projectData, [
                        'creator_id' => $userId,
                        'created_by' => $userId,
                    ])
                );

                // Add team members to project
                $teamMemberIds = User::where('created_by', $userId)->emp()->pluck('id')->toArray();
                if (!empty($teamMemberIds)) {
                    $project->teamMembers()->sync(collect($teamMemberIds)->random(min(rand(2, 3), count($teamMemberIds))));
                }

                // Add clients to project
                $clientIds = User::where('created_by', $userId)->where('type', 'client')->pluck('id')->toArray();
                if (!empty($clientIds)) {
                    $project->clients()->sync(collect($clientIds)->random(min(rand(1, 2), count($clientIds))));
                }

                // Add project files
                $files = [
                    ['file_name' => 'gambar-desain-interior.pdf',       'file_path' => 'dummy.pdf'],
                    ['file_name' => 'daftar-material-furnitur.pdf',     'file_path' => 'dummy.pdf'],
                    ['file_name' => 'jadwal-produksi.xlsx',             'file_path' => 'dummy.pdf'],
                    ['file_name' => 'spesifikasi-teknis-kayu.pdf',      'file_path' => 'dummy.pdf'],
                ];

                $selectedFiles = collect($files)->random(rand(3, 4));
                foreach ($selectedFiles as $fileData) {
                    ProjectFile::create([
                        'project_id' => $project->id,
                        'file_name' => $fileData['file_name'],
                        'file_path' => $fileData['file_path']
                    ]);
                }
            }
        }
    }
}
