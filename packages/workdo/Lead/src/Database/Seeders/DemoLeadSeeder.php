<?php

namespace Workdo\Lead\Database\Seeders;

use Workdo\Lead\Models\Lead;
use Workdo\Lead\Models\Pipeline;
use Workdo\Lead\Models\LeadStage;
use Workdo\Lead\Models\Source;
use Workdo\Lead\Models\Label;
use Workdo\ProductService\Models\ProductServiceItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DemoLeadSeeder extends Seeder
{
    public function run($userId): void
    {
        if (Lead::where('created_by', $userId)->exists()) {
            return;
        }
        if (!empty($userId)) {
            $pipelines = Pipeline::where('created_by', $userId)->get();
            $users = User::where('created_by', $userId)->where('type', '!=', 'client')->pluck('id')->toArray();

            if ($pipelines->isEmpty() || empty($users)) {
                return;
            }

            // PT Bojeri — 5 CRM leads (Section 16 of bojeri_seeder.md)
            $leadData = [
                ['name' => 'Pak Hendro',   'email' => 'hendro@grahaproperti.co.id',  'subject' => 'Sofa Lobby 50 Unit — PT Graha Properti',         'source' => 'Website',    'date' => Carbon::now()->subDays(45), 'created_at' => Carbon::now()->subDays(45)],
                ['name' => 'Bu Tania',     'email' => 'tania@rsmedistra.co.id',       'subject' => 'Furnitur Nurse Station — RS Medistra',           'source' => 'Referral',   'date' => Carbon::now()->subDays(30), 'created_at' => Carbon::now()->subDays(30)],
                ['name' => 'Pak Rizal',    'email' => 'rizal@perumahanasri.co.id',   'subject' => 'Paket Bedroom 100 Unit — Dev Perumahan Asri',    'source' => 'Exhibition', 'date' => Carbon::now()->subDays(20), 'created_at' => Carbon::now()->subDays(20)],
                ['name' => 'Pak Darmawan', 'email' => 'darmawan@grandmercure.co.id', 'subject' => 'Kursi Makan Custom 80 Bj — Hotel Grand Mercure', 'source' => 'Instagram',  'date' => Carbon::now()->subDays(14), 'created_at' => Carbon::now()->subDays(14)],
                ['name' => 'Bu Sinta',     'email' => 'sinta@kafeliterasi.co.id',    'subject' => 'Kursi Baca Kayu 30 Bj — Kafe Literasi',          'source' => 'Cold Call',  'date' => Carbon::now()->subDays(7),  'created_at' => Carbon::now()->subDays(7)],
            ];
            $notes = [
                "Prospek awal dari website PT Bojeri — sudah dikirimkan katalog produk sofa dan estimasi harga. Menunggu balasan dari tim procurement PT Graha Properti.",
                "Dirujuk oleh vendor furnitur lama — kebutuhan furnitur nurse station dan ruang tunggu RS Medistra. Diskusi awal positif, jadwal survei lokasi sedang dikonfirmasi.",
                "Bertemu di Pameran Furniture Indonesia — Developer Perumahan Asri butuh 100 paket bedroom standar. Sudah dikirim proposal awal dan sample material.",
                "Kontak melalui DM Instagram Bojeri — Hotel Grand Mercure butuh 80 kursi makan custom rotan. Desainer hotel sudah review katalog, menunggu approval GM.",
                "Cold call berhasil — Kafe Literasi tertarik dengan 30 kursi baca kayu jati. Owner kafe meminta presentasi singkat dan sample dalam 1 minggu.",
            ];

            $usedUserIds = [];
            $leadIndex = 0;

            foreach ($pipelines as $pipeline) {
                
                $stages = LeadStage::where('pipeline_id', $pipeline->id)->get();

                if ($stages->isEmpty()) continue;

                // Create 10 leads per pipeline with proper date distribution
                for ($i = 0; $i < 10; $i++) {
                    if ($leadIndex >= count($leadData)) break;

                    $lead = $leadData[$leadIndex];

                    // Assign unique user_id (1 lead per user)
                    $availableUsers = array_diff($users, $usedUserIds);
                    if (empty($availableUsers)) {
                        $availableUsers = $users; // Reset if all users used
                        $usedUserIds = [];
                    }
                    $assignedUserId = $availableUsers[array_rand($availableUsers)];
                    $usedUserIds[] = $assignedUserId;

                    // Realistic stage distribution based on sales funnel
                    if ($pipeline->name === 'Sales') {
                        // Sales funnel: more leads in early stages
                        if ($i < 4) $stageIndex = 0; // Draft (40%)
                        elseif ($i < 7) $stageIndex = 1; // Sent (30%)
                        elseif ($i < 8) $stageIndex = 2; // Open (10%)
                        elseif ($i < 9) $stageIndex = 3; // Revised (10%)
                        else $stageIndex = 4; // Accepted (10%)
                    } elseif ($pipeline->name === 'Marketing') {
                        // Marketing funnel: gradual decrease
                        if ($i < 4) $stageIndex = 0; // Prospect (40%)
                        elseif ($i < 6) $stageIndex = 1; // Contacted (20%)
                        elseif ($i < 8) $stageIndex = 2; // Engaged (20%)
                        elseif ($i < 9) $stageIndex = 3; // Qualified (10%)
                        else $stageIndex = 4; // Converted (10%)
                    } else {
                        // Lead Qualification: assessment-based distribution
                        if ($i < 3) $stageIndex = 0; // Unqualified (30%)
                        elseif ($i < 5) $stageIndex = 1; // In Review (20%)
                        elseif ($i < 7) $stageIndex = 2; // Qualified (20%)
                        elseif ($i < 9) $stageIndex = 3; // Approved (20%)
                        else $stageIndex = 4; // Rejected (10%)
                    }
                    $stage = $stages[$stageIndex];

                    // Select source by name from lead data
                    $selectedSourceId = Source::where('created_by', $userId)->where('name', $lead['source'] ?? '')->value('id');

                    $productIds = [];
                    if (Module_is_active('ProductService')) {
                        $productIds = ProductServiceItem::where('created_by', $userId)->pluck('id')->toArray();
                    }
                    $selectedProductId = !empty($productIds) ? $productIds[array_rand($productIds)] : null;

                    // Select pipeline-appropriate label IDs (1-3 labels)
                    $labelIds = Label::where('pipeline_id', $pipeline->id)->pluck('id')->toArray();
                    $selectedLabelIds = [];
                    if (!empty($labelIds)) {
                        $labelCount = rand(1, min(3, count($labelIds)));
                        $randomLabelIds = array_rand($labelIds, $labelCount);
                        if (!is_array($randomLabelIds)) $randomLabelIds = [$randomLabelIds];
                        foreach ($randomLabelIds as $labelIndex) {
                            $selectedLabelIds[] = $labelIds[$labelIndex];
                        }
                    }

                    // Generate an Indonesian phone number
                    $hour = rand(0, 23);
                    $minute = rand(0, 59);
                    $second = rand(0, 59);

                    Lead::create([
                        'name' => $lead['name'],
                        'email' => $lead['email'],
                        'subject' => $lead['subject'],
                        'user_id' => $assignedUserId,
                        'pipeline_id' => $pipeline->id,
                        'stage_id' => $stage->id,
                        'sources' => $selectedSourceId ?? null,
                        'products' => $selectedProductId ?? null,
                        'notes' => $notes[$leadIndex % count($notes)] ?? null,
                        'labels' => implode(',', $selectedLabelIds) ?? null,
                        'order' => 0,
                        'phone' => '+62' . mt_rand(8100000000, 8999999999),
                        'is_active' => true,
                        'date' => $lead['date']->format('Y-m-d'),
                        'creator_id' => $userId,
                        'created_by' => $userId,
                        'created_at' => $lead['created_at']->setTime($hour, $minute, $second)->format('Y-m-d H:i:s'),
                    ]);

                    $leadIndex++;
                }
            }
        }
    }
}
