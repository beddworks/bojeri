<?php

namespace Workdo\Hrm\Database\Seeders;

use Workdo\Hrm\Models\Payroll;
use Workdo\Hrm\Models\Employee;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DemoPayrollSeeder extends Seeder
{
    public function run($userId): void
    {
        if (Payroll::where('created_by', $userId)->exists()) {
            return; // Skip seeding if data already exists
        }
        
        // Validate userId
        if (!$userId || !is_numeric($userId)) {
            return;
        }

        // Get employee count for realistic payroll data
        $employeeCount = Employee::where('created_by', $userId)
            ->whereHas('user')
            ->count();

        if ($employeeCount == 0) {
            return;
        }

        // PT Bojeri — payroll periods: Oktober 2025 – Maret 2026
        $payrollPeriods = [
            ['month' => 10, 'year' => 2025, 'name' => 'Oktober 2025',  'paid' => true],
            ['month' => 11, 'year' => 2025, 'name' => 'November 2025', 'paid' => true],
            ['month' => 12, 'year' => 2025, 'name' => 'Desember 2025', 'paid' => true],
            ['month' => 1,  'year' => 2026, 'name' => 'Januari 2026',  'paid' => true],
            ['month' => 2,  'year' => 2026, 'name' => 'Februari 2026', 'paid' => true],
            ['month' => 3,  'year' => 2026, 'name' => 'Maret 2026',    'paid' => false],
        ];

        foreach ($payrollPeriods as $period) {
            $startDate = Carbon::create($period['year'], $period['month'], 1);
            $endDate   = $startDate->copy()->endOfMonth();
            $payDate   = $endDate->copy()->addDays(5);

            Payroll::updateOrCreate(
                ['title' => $period['name'], 'created_by' => $userId],
                [
                    'payroll_frequency' => 'monthly',
                    'pay_period_start'  => $startDate->format('Y-m-d'),
                    'pay_period_end'    => $endDate->format('Y-m-d'),
                    'pay_date'          => $payDate->format('Y-m-d'),
                    'notes'             => 'Penggajian bulanan PT Bojeri — ' . $period['name'],
                    'status'            => 'draft',
                    'is_payroll_paid'   => $period['paid'] ? 'paid' : 'unpaid',
                    'creator_id'        => $userId,
                    'created_by'        => $userId,
                ]
            );
        }
    }
}