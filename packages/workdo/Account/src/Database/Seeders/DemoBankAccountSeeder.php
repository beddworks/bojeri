<?php

namespace Workdo\Account\Database\Seeders;

use Workdo\Account\Models\BankAccount;
use Illuminate\Database\Seeder;
use Workdo\Account\Models\ChartOfAccount;


class DemoBankAccountSeeder extends Seeder
{
    public function run($userId): void
    {

        if (BankAccount::where('created_by', $userId)->exists()) {
            return;
        }

        // PT Bojeri — 3 bank accounts (Section 11 of bojeri_seeder.md)
        $bankAccounts = [
            [
                'account_number'  => '0883009910',
                'account_name'    => 'PT Bojeri',
                'bank_name'       => 'BCA',
                'branch_name'     => 'Cabang Tangerang',
                'account_type'    => '0',
                'payment_gateway' => null,
                'opening_balance' => 25000000.00,
                'current_balance' => 31250000.00,
                'iban'            => null,
                'swift_code'      => 'CENAIDJA',
                'routing_number'  => null,
                'is_active'       => true,
                'gl_code'         => '1010',
            ],
            [
                'account_number'  => '1560004421',
                'account_name'    => 'PT Bojeri',
                'bank_name'       => 'Bank Mandiri',
                'branch_name'     => 'Cabang Sudirman Jakarta',
                'account_type'    => '1',
                'payment_gateway' => null,
                'opening_balance' => 10000000.00,
                'current_balance' => 12500000.00,
                'iban'            => null,
                'swift_code'      => 'BMRIIDJA',
                'routing_number'  => null,
                'is_active'       => true,
                'gl_code'         => '1020',
            ],
            [
                'account_number'  => '0238887700',
                'account_name'    => 'PT Bojeri',
                'bank_name'       => 'BNI',
                'branch_name'     => 'Cabang Tangerang',
                'account_type'    => '0',
                'payment_gateway' => null,
                'opening_balance' => 5000000.00,
                'current_balance' => 6250000.00,
                'iban'            => null,
                'swift_code'      => 'BNINIDJA',
                'routing_number'  => null,
                'is_active'       => true,
                'gl_code'         => '1030',
            ],
        ];

        foreach ($bankAccounts as $account) {
            $glCode = $account['gl_code'];
            unset($account['gl_code']);

            $glAccountId = ChartOfAccount::where('created_by', $userId)
                ->where('account_code', $glCode)
                ->value('id');

            BankAccount::updateOrCreate(
                ['account_number' => $account['account_number'], 'created_by' => $userId],
                array_merge($account, [
                    'gl_account_id' => $glAccountId,
                    'creator_id'    => $userId,
                    'created_by'    => $userId,
                ])
            );
        }
    }
}
