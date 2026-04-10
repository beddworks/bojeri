<?php

namespace Workdo\Lead\Database\Seeders;

use Workdo\Lead\Models\Deal;
use Workdo\Lead\Models\Pipeline;
use Workdo\Lead\Models\DealStage;
use Workdo\Lead\Models\Lead;
use Workdo\Lead\Models\Source;
use Workdo\Lead\Models\Label;
use Workdo\Lead\Models\ClientDeal;
use Workdo\Lead\Models\DealTask;
use Workdo\Lead\Models\UserDeal;
use Workdo\Lead\Models\DealDiscussion;
use Workdo\Lead\Models\DealFile;
use Workdo\Lead\Models\DealCall;
use Workdo\Lead\Models\DealEmail;
use Workdo\ProductService\Models\ProductServiceItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DemoDealSeeder extends Seeder
{
    public function run($userId): void
    {
        if (Deal::where('created_by', $userId)->exists()) {
            return;
        }
        if (!empty($userId)) {
            $pipelines = Pipeline::where('created_by', $userId)->get();
            $users = User::where('created_by', $userId)->where('type', '!=', 'client')->pluck('id')->toArray();

            if ($pipelines->isEmpty() || empty($users)) {
                return;
            }

            $dealData = [
                // PT Bojeri — 3 CRM deals (Section 17 of bojeri_seeder.md)
                ['name' => 'Sofa Lobby 50 Unit — PT Graha Properti',      'price' => 24000000, 'created_at' => Carbon::now()->subDays(42)],
                ['name' => 'Paket Bedroom 100 Unit — Dev Perumahan Asri', 'price' => 51000000, 'created_at' => Carbon::now()->subDays(25)],
                ['name' => 'Furnitur Nurse Station — RS Medistra',        'price' => 18000000, 'created_at' => Carbon::now()->subDays(28)],
            ];

            $notes = [
                "Prospek melalui website PT Bojeri — PT Graha Properti membutuhkan 50 unit sofa lobby grade-A. Sudah dikirim proposal harga Rp 240.000.000, menunggu persetujuan direksi untuk PO.",
                "Lead dari pameran Furniture Indonesia — Developer Perumahan Asri membutuhkan 100 paket bedroom untuk unit siap huni. Deal senilai Rp 510.000.000 sudah ditandatangani dan DP 30% diterima.",
                "Referral dari vendor lama — RS Medistra butuh furnitur nurse station & ruang tunggu. Presentasi desain selesai, menunggu approval pengadaan barang untuk deal Rp 180.000.000.",
            ];

            $clients = User::where('created_by', $userId)->where('type', 'client')->pluck('id')->toArray();
            // $usedUserIds = [];
            $usedClientIds = [];
            $dealIndex = 0;
            $convertedLeadIndex = 0;

            foreach ($pipelines as $pipeline) {

                
                // Get 6 leads for conversion per pipeline (reduced from 17)
                $convertibleLeads = Lead::where('created_by', $userId)
                    ->where('pipeline_id', $pipeline->id)
                    ->where('is_converted', 0)
                    ->orderBy('created_at', 'asc')
                    ->limit(6)
                    ->get();

                $stages = DealStage::where('pipeline_id', $pipeline->id)->get();
                if ($stages->isEmpty()) continue;

                $convertedLeadIndex = 0;

                // Create 10 deals per pipeline (reduced from 30)
                for ($i = 0; $i < 10; $i++) {
                    if ($dealIndex >= count($dealData)) break;

                    $deal = $dealData[$dealIndex];

                    // Realistic stage distribution based on deal funnel
                    $stageCount = $stages->count();
                    if ($pipeline->name === 'Marketing') {
                        // Marketing funnel: Campaign Launch (40%), Lead Generation (30%), Nurturing (20%), Qualification (10%)
                        if ($i < 4) $stageIndex = 0; // Campaign Launch
                        elseif ($i < 7) $stageIndex = min(1, $stageCount - 1); // Lead Generation
                        elseif ($i < 9) $stageIndex = min(2, $stageCount - 1); // Nurturing
                        else $stageIndex = min(3, $stageCount - 1); // Qualification
                    } else {
                        // Lead Qualification: Initial Contact (30%), Needs Assessment (30%), Solution Fit (20%), Proposal Sent (20%)
                        if ($i < 3) $stageIndex = 0; // Initial Contact
                        elseif ($i < 6) $stageIndex = min(1, $stageCount - 1); // Needs Assessment
                        elseif ($i < 8) $stageIndex = min(2, $stageCount - 1); // Solution Fit
                        else $stageIndex = min(3, $stageCount - 1); // Proposal Sent
                    }
                    $stage = $stages[$stageIndex];

                    // Status distribution: Active (60%), Won (30%), Loss (10%)
                    if ($i < 6) $status = 'Active';
                    elseif ($i < 9) $status = 'Won';
                    else $status = 'Loss';

                    // Check if this is a converted lead (first 6 deals per pipeline)
                    $convertedLead = null;
                    if ($i < 6 && $convertedLeadIndex < count($convertibleLeads)) {
                        $convertedLead = $convertibleLeads[$convertedLeadIndex];
                        // Ensure deal created_at is after lead created_at
                        if ($convertedLead && $deal['created_at']->lte($convertedLead->created_at)) {
                            $deal['created_at'] = $convertedLead->created_at->addDays(3);
                        }
                        $convertedLeadIndex++;
                    }

                    // Get sources, products, labels from converted lead or random selection
                    if ($convertedLead) {
                        // Use lead data for converted deals (following convertToDeal logic)
                        $sources = $convertedLead->sources;
                        $products = $convertedLead->products;
                        $noteText  = $convertedLead->notes;
                        $labels = $convertedLead->labels;
                        $dealName = $convertedLead->subject . ' - ' . 'From Lead';
                        $pipelineId = $pipeline->id; // Always use current pipeline, not converted lead's pipeline
                    } else {
                        // Random selection for non-converted deals
                        $sourceIds = Source::where('created_by', $userId)->pluck('id')->toArray();
                        $productIds = [];
                        if (Module_is_active('ProductService')) {
                        $productIds = ProductServiceItem::where('created_by', $userId)->pluck('id')->toArray();
                        }

                        $sources = !empty($sourceIds) ? $sourceIds[array_rand($sourceIds)] : null;
                        $products = !empty($productIds) ? $productIds[array_rand($productIds)] : null;
                        $noteText  = $notes[$dealIndex % count($notes)] ?? null;

                        // Select pipeline-appropriate labels
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
                        $labels = implode(',', $selectedLabelIds);
                        $dealName = $deal['name'];
                        $pipelineId = $pipeline->id;
                    }

                    $hour = rand(0, 23);
                    $minute = rand(0, 59);
                    $second = rand(0, 59);

                    $dealRecord = Deal::create([
                        'name' => $dealName,
                        'price' => $deal['price'],
                        'pipeline_id' => $pipelineId,
                        'stage_id' => $stage->id,
                        'sources' => !empty($sources) ? (array)$sources : null,
                        'products' => !empty($products) ? (array)$products : null,
                        'notes' => $noteText,
                        'labels' => $labels,
                        'phone' => '+62' . mt_rand(8100000000, 8999999999),
                        'status' => $status,
                        'order' => 0,
                        'is_active' => $status === 'Active',
                        'creator_id' => $userId,
                        'created_by' => $convertedLead ? $convertedLead->created_by : $userId,
                        'created_at' => $deal['created_at']->setTime($hour, $minute, $second)->format('Y-m-d H:i:s'),
                    ]);

                    // Mark lead as converted and transfer related data
                    if ($convertedLead) {
                        $convertedLead->update(['is_converted' => $dealRecord->id]);

                        // Load lead relationships for transfer
                        $convertedLead->load(['tasks', 'userLeads', 'discussions', 'files', 'calls', 'emails']);

                        // Assign unique client_id
                        if (!empty($clients)) {
                            $availableClients = array_diff($clients, $usedClientIds);
                            if (empty($availableClients)) {
                                $availableClients = $clients;
                                $usedClientIds = [];
                            }
                            $assignedClientId = $availableClients[array_rand($availableClients)];
                            $usedClientIds[] = $assignedClientId;

                            ClientDeal::create([
                                'deal_id' => $dealRecord->id,
                                'client_id' => $assignedClientId,
                            ]);
                        }

                        // Transfer tasks
                        if ($convertedLead->tasks) {
                            foreach ($convertedLead->tasks as $task) {
                                DealTask::create([
                                    'deal_id' => $dealRecord->id,
                                    'name' => $task->name,
                                    'date' => $task->date,
                                    'time' => $task->time,
                                    'priority' => $task->priority,
                                    'status' => $task->status,
                                    'creator_id' => $task->creator_id,
                                    'created_by' => $task->created_by,
                                ]);
                            }
                        }

                        // Transfer users
                        if ($convertedLead->userLeads) {
                            foreach ($convertedLead->userLeads as $userLead) {
                                UserDeal::firstOrCreate([
                                    'user_id' => $userLead->user_id,
                                    'deal_id' => $dealRecord->id,
                                ]);
                            }
                        }

                        // Transfer discussions
                        if ($convertedLead->discussions) {
                            foreach ($convertedLead->discussions as $discussion) {
                                DealDiscussion::create([
                                    'deal_id' => $dealRecord->id,
                                    'comment' => $discussion->comment,
                                    'creator_id' => $discussion->creator_id,
                                    'created_by' => $discussion->created_by,
                                ]);
                            }
                        }

                        // Transfer files
                        if ($convertedLead->files) {
                            foreach ($convertedLead->files as $file) {
                                DealFile::create([
                                    'deal_id' => $dealRecord->id,
                                    'file_name' => $file->file_name,
                                    'file_path' => $file->file_path,
                                ]);
                            }
                        }

                        // Transfer calls
                        if ($convertedLead->calls) {
                            foreach ($convertedLead->calls as $call) {
                                DealCall::create([
                                    'deal_id' => $dealRecord->id,
                                    'subject' => $call->subject,
                                    'call_type' => $call->call_type,
                                    'duration' => $call->duration,
                                    'user_id' => $call->user_id,
                                    'description' => $call->description,
                                    'call_result' => $call->call_result,
                                ]);
                            }
                        }

                        // Transfer emails
                        if ($convertedLead->emails) {
                            foreach ($convertedLead->emails as $email) {
                                DealEmail::create([
                                    'deal_id' => $dealRecord->id,
                                    'to' => $email->to,
                                    'subject' => $email->subject,
                                    'description' => $email->description,
                                ]);
                            }
                        }
                    }

                    $dealIndex++;
                }
            }
        }
    }
}
