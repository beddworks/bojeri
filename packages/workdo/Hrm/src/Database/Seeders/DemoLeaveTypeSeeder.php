<?php

namespace Workdo\Hrm\Database\Seeders;

use Workdo\Hrm\Models\LeaveType;
use Illuminate\Database\Seeder;



class DemoLeaveTypeSeeder extends Seeder
{
    public function run($userId): void
    {
        if (LeaveType::where('created_by', $userId)->exists()) {
            return; // Skip seeding if data already exists
        }
        
        // PT Bojeri — 6 official leave types
        $leaveTypes = [
            [
                'name'             => 'Annual Leave',
                'description'      => 'Cuti tahunan karyawan — 12 hari kerja per tahun.',
                'max_days_per_year' => 12,
                'is_paid'          => true,
                'color'            => '#10B981',
            ],
            [
                'name'             => 'Sick Leave',
                'description'      => 'Cuti sakit dengan surat keterangan dokter.',
                'max_days_per_year' => 14,
                'is_paid'          => true,
                'color'            => '#EF4444',
            ],
            [
                'name'             => 'Maternity Leave',
                'description'      => 'Cuti melahirkan sesuai UU Ketenagakerjaan.',
                'max_days_per_year' => 90,
                'is_paid'          => true,
                'color'            => '#F59E0B',
            ],
            [
                'name'             => 'Paternity Leave',
                'description'      => 'Cuti ayah mendampingi istri melahirkan.',
                'max_days_per_year' => 3,
                'is_paid'          => true,
                'color'            => '#3B82F6',
            ],
            [
                'name'             => 'Emergency Leave',
                'description'      => 'Cuti darurat untuk keperluan mendesak keluarga.',
                'max_days_per_year' => 3,
                'is_paid'          => true,
                'color'            => '#DC2626',
            ],
            [
                'name'             => 'Unpaid Leave',
                'description'      => 'Cuti tanpa upah atas persetujuan manajemen.',
                'max_days_per_year' => 30,
                'is_paid'          => false,
                'color'            => '#6B7280',
            ],
        ];

        foreach ($leaveTypes as $leaveType) {
            LeaveType::updateOrCreate(
                [
                    'name' => $leaveType['name'],
                    'created_by' => $userId
                ],
                [
                    'description' => $leaveType['description'],
                    'max_days_per_year' => $leaveType['max_days_per_year'],
                    'is_paid' => $leaveType['is_paid'],
                    'color' => $leaveType['color'],
                    'creator_id' => $userId,
                ]
            );
        }
    }
}