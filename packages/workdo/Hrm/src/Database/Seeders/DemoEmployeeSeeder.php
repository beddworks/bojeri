<?php

namespace Workdo\Hrm\Database\Seeders;

use App\Models\User;
use Workdo\Hrm\Models\Employee;
use Illuminate\Database\Seeder;
use Workdo\Hrm\Models\Branch;
use Workdo\Hrm\Models\Department;
use Workdo\Hrm\Models\Designation;
use Workdo\Hrm\Models\Shift;

class DemoEmployeeSeeder extends Seeder
{
    public function run($userId): void
    {
        if (Employee::where('created_by', $userId)->exists()) {
            return;
        }

        // ── Resolve branches ──
        $branchMap = Branch::where('created_by', $userId)->get()->keyBy('branch_name');

        // ── Helper: resolve department id ──
        $getDept = fn (string $name, int $branchId): ?int =>
            Department::where('created_by', $userId)
                ->where('branch_id', $branchId)
                ->where('department_name', $name)
                ->value('id');

        // ── Helper: resolve designation id ──
        $getDesig = fn (string $name, int $branchId, int $deptId): ?int =>
            Designation::where('created_by', $userId)
                ->where('branch_id', $branchId)
                ->where('department_id', $deptId)
                ->where('designation_name', $name)
                ->value('id');

        // ── Resolve shift ids ──
        $shiftMap = Shift::where('created_by', $userId)->get()->keyBy('shift_name');
        $shiftPagi   = $shiftMap->get('Morning Shift')?->id;
        $shiftKantor = $shiftMap->get('Office Shift')?->id;
        $shiftSiang  = $shiftMap->get('Office Shift')?->id;

        $indoBanks = ['Bank BCA', 'Bank Mandiri', 'Bank BNI', 'Bank BRI', 'Bank BTN'];

        /**
         * Employee data rows from PT Bojeri bojeri_seeder.md.
         *
         * Keys: email, id_code, branch, dept, desig, shift_name,
         *       salary, gender, city, address, joining_days_ago
         */
        $employees = [
            // ── Head Office – Jakarta (HQ) ──
            ['email' => 'sari.dewi@bojeri.com',       'id_code' => 'E-002', 'branch' => 'Jakarta HQ', 'dept' => 'Sales',        'desig' => 'Sales Manager',           'shift' => 'Shift Kantor', 'salary' => 8500000,  'gender' => 'Female', 'city' => 'Jakarta Selatan', 'address' => 'Jl. Sudirman No.45',          'join' => 1095],
            ['email' => 'reza.f@bojeri.com',           'id_code' => 'E-003', 'branch' => 'Jakarta HQ', 'dept' => 'Sales',        'desig' => 'Sales Executive',         'shift' => 'Shift Kantor', 'salary' => 5500000,  'gender' => 'Male',   'city' => 'Jakarta Barat',  'address' => 'Jl. Tanjung Duren No.22',     'join' => 900],
            ['email' => 'putri.h@bojeri.com',          'id_code' => 'E-004', 'branch' => 'Jakarta HQ', 'dept' => 'Sales',        'desig' => 'Sales Representative',    'shift' => 'Shift Kantor', 'salary' => 4200000,  'gender' => 'Female', 'city' => 'Depok',           'address' => 'Jl. Margonda Raya No.88',     'join' => 730],
            ['email' => 'dimas.a@bojeri.com',          'id_code' => 'E-005', 'branch' => 'Jakarta HQ', 'dept' => 'Sales',        'desig' => 'Sales Representative',    'shift' => 'Shift Kantor', 'salary' => 4200000,  'gender' => 'Male',   'city' => 'Tangerang',       'address' => 'Jl. Imam Bonjol No.11',       'join' => 720],
            ['email' => 'yuni.rahayu@bojeri.com',      'id_code' => 'E-006', 'branch' => 'Jakarta HQ', 'dept' => 'Sales',        'desig' => 'POS Cashier',             'shift' => 'Shift Siang',  'salary' => 3500000,  'gender' => 'Female', 'city' => 'Jakarta Utara',  'address' => 'Jl. Pluit Raya No.6',         'join' => 540],
            ['email' => 'rina.marlina@bojeri.com',     'id_code' => 'E-007', 'branch' => 'Jakarta HQ', 'dept' => 'Design',       'desig' => 'Design Manager',          'shift' => 'Shift Kantor', 'salary' => 8000000,  'gender' => 'Female', 'city' => 'Jakarta Selatan', 'address' => 'Jl. Kemang Raya No.30',       'join' => 1200],
            ['email' => 'bagas.n@bojeri.com',          'id_code' => 'E-008', 'branch' => 'Jakarta HQ', 'dept' => 'Design',       'desig' => 'Senior Designer',         'shift' => 'Shift Kantor', 'salary' => 6500000,  'gender' => 'Male',   'city' => 'Bekasi',          'address' => 'Jl. Hasibuan No.14',          'join' => 800],
            ['email' => 'dewi.susanti@bojeri.com',     'id_code' => 'E-009', 'branch' => 'Jakarta HQ', 'dept' => 'Design',       'desig' => 'Junior Designer',         'shift' => 'Shift Kantor', 'salary' => 4500000,  'gender' => 'Female', 'city' => 'Depok',           'address' => 'Jl. Nusantara No.7',          'join' => 400],
            ['email' => 'fajar.k@bojeri.com',          'id_code' => 'E-010', 'branch' => 'Jakarta HQ', 'dept' => 'Design',       'desig' => '3D Render Artist',        'shift' => 'Shift Kantor', 'salary' => 5000000,  'gender' => 'Male',   'city' => 'Jakarta Timur',  'address' => 'Jl. Jatinegara No.55',        'join' => 600],
            ['email' => 'agus.purnomo@bojeri.com',     'id_code' => 'E-011', 'branch' => 'Jakarta HQ', 'dept' => 'Production',   'desig' => 'Production Manager',      'shift' => 'Shift Pagi',   'salary' => 7500000,  'gender' => 'Male',   'city' => 'Tangerang',       'address' => 'Jl. Cikokol No.3',            'join' => 1500],
            ['email' => 'hendra.s@bojeri.com',         'id_code' => 'E-012', 'branch' => 'Jakarta HQ', 'dept' => 'Production',   'desig' => 'Production Supervisor',   'shift' => 'Shift Pagi',   'salary' => 5800000,  'gender' => 'Male',   'city' => 'Tangerang',       'address' => 'Jl. Merdeka No.21',           'join' => 1000],
            ['email' => 'bambang.t@bojeri.com',        'id_code' => 'E-013', 'branch' => 'Jakarta HQ', 'dept' => 'Production',   'desig' => 'Craftsman/Worker',               'shift' => 'Shift Pagi',   'salary' => 3800000,  'gender' => 'Male',   'city' => 'Tangerang',       'address' => 'Jl. Pahlawan No.9',           'join' => 1460],
            ['email' => 'sukiman.w@bojeri.com',        'id_code' => 'E-014', 'branch' => 'Jakarta HQ', 'dept' => 'Production',   'desig' => 'Craftsman/Worker',               'shift' => 'Shift Pagi',   'salary' => 3800000,  'gender' => 'Male',   'city' => 'Tangerang',       'address' => 'Jl. Industri No.18',          'join' => 1300],
            ['email' => 'rudi.hartono@bojeri.com',     'id_code' => 'E-015', 'branch' => 'Jakarta HQ', 'dept' => 'Warehouse',    'desig' => 'Warehouse Manager',       'shift' => 'Shift Pagi',   'salary' => 6200000,  'gender' => 'Male',   'city' => 'Tangerang',       'address' => 'Jl. Utama No.5',              'join' => 1100],
            ['email' => 'wahyu.s@bojeri.com',          'id_code' => 'E-016', 'branch' => 'Jakarta HQ', 'dept' => 'Warehouse',    'desig' => 'Inventory Controller',    'shift' => 'Shift Pagi',   'salary' => 4800000,  'gender' => 'Male',   'city' => 'Tangerang',       'address' => 'Jl. Veteran No.33',           'join' => 850],
            ['email' => 'eko.prasetyo@bojeri.com',     'id_code' => 'E-017', 'branch' => 'Jakarta HQ', 'dept' => 'Warehouse',    'desig' => 'Logistic Staff',         'shift' => 'Shift Pagi',   'salary' => 3500000,  'gender' => 'Male',   'city' => 'Tangerang',       'address' => 'Jl. Raya Serpong No.12',      'join' => 500],
            ['email' => 'andi.wijaya@bojeri.com',      'id_code' => 'E-018', 'branch' => 'Jakarta HQ', 'dept' => 'Finance & HR', 'desig' => 'Finance Manager',         'shift' => 'Shift Kantor', 'salary' => 9000000,  'gender' => 'Male',   'city' => 'Jakarta Selatan', 'address' => 'Jl. Gatot Subroto No.77',    'join' => 1400],
            ['email' => 'lestari.n@bojeri.com',        'id_code' => 'E-019', 'branch' => 'Jakarta HQ', 'dept' => 'Finance & HR', 'desig' => 'HR Manager',              'shift' => 'Shift Kantor', 'salary' => 7800000,  'gender' => 'Female', 'city' => 'Jakarta Pusat',  'address' => 'Jl. Wahid Hasyim No.44',     'join' => 1250],
            ['email' => 'mega.w@bojeri.com',           'id_code' => 'E-020', 'branch' => 'Jakarta HQ', 'dept' => 'Finance & HR', 'desig' => 'Accountant / Payroll Staff',              'shift' => 'Shift Kantor', 'salary' => 5500000,  'gender' => 'Female', 'city' => 'Jakarta Selatan', 'address' => 'Jl. Fatmawati No.66',        'join' => 700],
            ['email' => 'taufik.h@bojeri.com',         'id_code' => 'E-021', 'branch' => 'Jakarta HQ', 'dept' => 'Finance & HR', 'desig' => 'Accountant / Payroll Staff',           'shift' => 'Shift Kantor', 'salary' => 4200000,  'gender' => 'Male',   'city' => 'Depok',           'address' => 'Jl. Cinere No.28',           'join' => 480],
            ['email' => 'novita.sari@bojeri.com',      'id_code' => 'E-022', 'branch' => 'Jakarta HQ', 'dept' => 'CRM & Sales',          'desig' => 'CRM Manager',             'shift' => 'Shift Kantor', 'salary' => 7000000,  'gender' => 'Female', 'city' => 'Jakarta Selatan', 'address' => 'Jl. Kuningan No.2',          'join' => 950],
            ['email' => 'irfan.m@bojeri.com',          'id_code' => 'E-023', 'branch' => 'Jakarta HQ', 'dept' => 'CRM & Sales',          'desig' => 'Lead Specialist',         'shift' => 'Shift Kantor', 'salary' => 5000000,  'gender' => 'Male',   'city' => 'Jakarta Timur',  'address' => 'Jl. Otista No.99',           'join' => 600],
            // ── North Branch – Bandung (NBD) ──
            ['email' => 'asep.g@bojeri.com',           'id_code' => 'E-024', 'branch' => 'Bandung (North)', 'dept' => 'Sales',       'desig' => 'Branch Sales Head',       'shift' => 'Shift Kantor', 'salary' => 6000000,  'gender' => 'Male',   'city' => 'Bandung',         'address' => 'Jl. Dago No.15',             'join' => 1050],
            ['email' => 'ningsih.r@bojeri.com',        'id_code' => 'E-025', 'branch' => 'Bandung (North)', 'dept' => 'Sales',       'desig' => 'Sales Executive',         'shift' => 'Shift Kantor', 'salary' => 4500000,  'gender' => 'Female', 'city' => 'Bandung',         'address' => 'Jl. Riau No.27',             'join' => 730],
            ['email' => 'dedi.k@bojeri.com',           'id_code' => 'E-026', 'branch' => 'Bandung (North)', 'dept' => 'Sales',       'desig' => 'POS Cashier',             'shift' => 'Shift Siang',  'salary' => 3500000,  'gender' => 'Male',   'city' => 'Bandung',         'address' => 'Jl. Braga No.8',             'join' => 390],
            ['email' => 'endang.s@bojeri.com',         'id_code' => 'E-027', 'branch' => 'Bandung (North)', 'dept' => 'Production',  'desig' => 'Floor Supervisor',        'shift' => 'Shift Pagi',   'salary' => 5000000,  'gender' => 'Male',   'city' => 'Bandung',         'address' => 'Jl. Cicendo No.4',           'join' => 860],
            ['email' => 'ujang.r@bojeri.com',          'id_code' => 'E-028', 'branch' => 'Bandung (North)', 'dept' => 'Production',  'desig' => 'Craftsman/Worker',               'shift' => 'Shift Pagi',   'salary' => 3500000,  'gender' => 'Male',   'city' => 'Bandung',         'address' => 'Jl. Antapani No.37',         'join' => 700],
            ['email' => 'dadang.p@bojeri.com',         'id_code' => 'E-029', 'branch' => 'Bandung (North)', 'dept' => 'Production',  'desig' => 'Craftsman/Worker',               'shift' => 'Shift Pagi',   'salary' => 3500000,  'gender' => 'Male',   'city' => 'Cimahi',          'address' => 'Jl. Cihanjuang No.12',       'join' => 650],
            ['email' => 'yayan.s@bojeri.com',          'id_code' => 'E-030', 'branch' => 'Bandung (North)', 'dept' => 'Warehouse',   'desig' => 'Store Keeper',            'shift' => 'Shift Pagi',   'salary' => 3800000,  'gender' => 'Male',   'city' => 'Bandung',         'address' => 'Jl. Sukajadi No.53',         'join' => 580],
            ['email' => 'nana.sh@bojeri.com',          'id_code' => 'E-031', 'branch' => 'Bandung (North)', 'dept' => 'Warehouse',   'desig' => 'Logistic Staff',         'shift' => 'Shift Pagi',   'salary' => 3200000,  'gender' => 'Male',   'city' => 'Bandung',         'address' => 'Jl. Pasteur No.78',          'join' => 420],
            // ── South Branch – Surabaya (SBY) ──
            ['email' => 'slamet.r@bojeri.com',         'id_code' => 'E-032', 'branch' => 'Surabaya (South)', 'dept' => 'Sales',       'desig' => 'Branch Sales Head',       'shift' => 'Shift Kantor', 'salary' => 6000000,  'gender' => 'Male',   'city' => 'Surabaya',        'address' => 'Jl. Basuki Rahmat No.10',    'join' => 980],
            ['email' => 'ratna.k@bojeri.com',          'id_code' => 'E-033', 'branch' => 'Surabaya (South)', 'dept' => 'Sales',       'desig' => 'Sales Executive',         'shift' => 'Shift Kantor', 'salary' => 4500000,  'gender' => 'Female', 'city' => 'Surabaya',        'address' => 'Jl. Darmo No.25',            'join' => 680],
            ['email' => 'joko.santoso@bojeri.com',     'id_code' => 'E-034', 'branch' => 'Surabaya (South)', 'dept' => 'Sales',       'desig' => 'POS Cashier',             'shift' => 'Shift Siang',  'salary' => 3500000,  'gender' => 'Male',   'city' => 'Surabaya',        'address' => 'Jl. Pemuda No.31',           'join' => 350],
            ['email' => 'mulyono.h@bojeri.com',        'id_code' => 'E-035', 'branch' => 'Surabaya (South)', 'dept' => 'Production',  'desig' => 'Floor Supervisor',        'shift' => 'Shift Pagi',   'salary' => 5000000,  'gender' => 'Male',   'city' => 'Surabaya',        'address' => 'Jl. Mayjen Sungkono No.44',  'join' => 810],
            ['email' => 'paijo.w@bojeri.com',          'id_code' => 'E-036', 'branch' => 'Surabaya (South)', 'dept' => 'Production',  'desig' => 'Craftsman/Worker',               'shift' => 'Shift Pagi',   'salary' => 3500000,  'gender' => 'Male',   'city' => 'Surabaya',        'address' => 'Jl. Wonorejo No.7',          'join' => 620],
            ['email' => 'sutrisno.a@bojeri.com',       'id_code' => 'E-037', 'branch' => 'Surabaya (South)', 'dept' => 'Production',  'desig' => 'Craftsman/Worker',               'shift' => 'Shift Pagi',   'salary' => 3500000,  'gender' => 'Male',   'city' => 'Sidoarjo',        'address' => 'Jl. Raya Gedangan No.15',    'join' => 550],
            ['email' => 'hariyanto.p@bojeri.com',      'id_code' => 'E-038', 'branch' => 'Surabaya (South)', 'dept' => 'Warehouse',   'desig' => 'Store Keeper',            'shift' => 'Shift Pagi',   'salary' => 3800000,  'gender' => 'Male',   'city' => 'Surabaya',        'address' => 'Jl. Ngagel No.88',           'join' => 490],
            ['email' => 'supardi.l@bojeri.com',        'id_code' => 'E-039', 'branch' => 'Surabaya (South)', 'dept' => 'Warehouse',   'desig' => 'Logistic Staff',         'shift' => 'Shift Pagi',   'salary' => 3200000,  'gender' => 'Male',   'city' => 'Surabaya',        'address' => 'Jl. Kenjeran No.54',         'join' => 360],
        ];

        foreach ($employees as $index => $data) {
            $staffUser = User::where('email', $data['email'])
                ->where('created_by', $userId)
                ->first();

            if (!$staffUser) continue;

            $branch = $branchMap->get($data['branch']);
            if (!$branch) continue;

            $deptId  = $getDept($data['dept'], $branch->id);
            if (!$deptId) continue;

            $desigId = $getDesig($data['desig'], $branch->id, $deptId);
            if (!$desigId) continue;

            $shiftId = match ($data['shift']) {
                'Shift Pagi'   => $shiftPagi,
                'Shift Siang'  => $shiftSiang,
                default        => $shiftKantor,
            };

            $bankName = $indoBanks[array_rand($indoBanks)];
            $empNumber = str_pad($index + 2, 3, '0', STR_PAD_LEFT);

            Employee::updateOrCreate(
                ['user_id' => $staffUser->id, 'created_by' => $userId],
                [
                    'employee_id'                   => $data['id_code'],
                    'date_of_birth'                 => now()->subYears(rand(25, 45))->subDays(rand(1, 365))->format('Y-m-d'),
                    'gender'                        => $data['gender'],
                    'shift'                         => $shiftId,
                    'date_of_joining'               => now()->subDays($data['join'])->format('Y-m-d'),
                    'employment_type'               => 'Full Time',
                    'address_line_1'                => $data['address'],
                    'city'                          => $data['city'],
                    'state'                         => 'Jawa',
                    'country'                       => 'Indonesia',
                    'postal_code'                   => str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT),
                    'emergency_contact_name'        => 'Keluarga ' . explode(' ', $staffUser->name)[0],
                    'emergency_contact_relationship' => 'Spouse',
                    'emergency_contact_number'      => '+628' . rand(100000000, 999999999),
                    'bank_name'                     => $bankName,
                    'account_holder_name'           => $staffUser->name,
                    'account_number'                => str_pad(rand(1000000000, 9999999999), 10, '0', STR_PAD_LEFT),
                    'bank_identifier_code'          => strtoupper(substr(str_replace('Bank ', '', $bankName), 0, 4)) . rand(1000, 9999),
                    'bank_branch'                   => $data['city'],
                    'tax_payer_id'                  => 'NPWP' . rand(10000000, 99999999),
                    'basic_salary'                  => $data['salary'],
                    'hours_per_day'                 => 8,
                    'days_per_week'                 => 5,
                    'rate_per_hour'                 => round($data['salary'] / (21 * 8), 2),
                    'branch_id'                     => $branch->id,
                    'department_id'                 => $deptId,
                    'designation_id'                => $desigId,
                    'creator_id'                    => $userId,
                ]
            );
        }
    }
}
