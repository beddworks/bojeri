<?php

namespace Workdo\Hrm\Database\Seeders;

use Illuminate\Database\Seeder;
use Workdo\Hrm\Models\Shift;

class DemoShiftSeeder extends Seeder
{
    public function run($userId)
    {
        if (Shift::where('created_by', $userId)->exists()) {
            return; // Skip seeding if data already exists
        }
        
        // PT Bojeri — 3 official work shifts
        $shifts = [
            [
                'shift_name'       => 'Shift Pagi',
                'start_time'       => '07:00:00',
                'end_time'         => '16:00:00',
                'break_start_time' => '12:00:00',
                'break_end_time'   => '13:00:00',
                'is_night_shift'   => false,
                'creator_id'       => $userId,
                'created_by'       => $userId,
            ],
            [
                'shift_name'       => 'Shift Kantor',
                'start_time'       => '08:00:00',
                'end_time'         => '17:00:00',
                'break_start_time' => '12:00:00',
                'break_end_time'   => '13:00:00',
                'is_night_shift'   => false,
                'creator_id'       => $userId,
                'created_by'       => $userId,
            ],
            [
                'shift_name'       => 'Shift Siang',
                'start_time'       => '12:00:00',
                'end_time'         => '21:00:00',
                'break_start_time' => '17:00:00',
                'break_end_time'   => '18:00:00',
                'is_night_shift'   => false,
                'creator_id'       => $userId,
                'created_by'       => $userId,
            ],
        ];

        foreach ($shifts as $shift) {
            Shift::updateOrCreate(
                ['shift_name' => $shift['shift_name'], 'created_by' => $userId],
                $shift
            );
        }
    }
}