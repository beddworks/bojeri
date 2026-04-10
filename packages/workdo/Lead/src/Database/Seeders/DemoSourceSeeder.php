<?php

namespace Workdo\Lead\Database\Seeders;

use Workdo\Lead\Models\Source;
use Illuminate\Database\Seeder;

class DemoSourceSeeder extends Seeder
{
    public function run($userId): void
    {
        if (Source::where('created_by', $userId)->exists()) {
            return;
        }
        if (!empty($userId)) 
        {
            // PT Bojeri — CRM lead sources (Section 16)
            $sources = [
                'Website',
                'Referral',
                'Exhibition',
                'Instagram',
                'Cold Call',
            ];
            
            foreach ($sources as $name) {
                Source::create([
                    'name' => $name,
                    'creator_id' => $userId,
                    'created_by' => $userId,
                ]);
            }
        }
    }
}