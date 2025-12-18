<?php

namespace App\Console\Commands;

use App\Models\ProgressLog;
use Illuminate\Console\Command;

class FixProgressLogGrowthStages extends Command
{
    protected $signature = 'fix:progress-log-growth-stages';
    protected $description = 'Fix all progress logs with incorrect growth stages based on activity type';

    public function handle()
    {
        $activityToGrowthStage = [
            'land_preparation' => 1,
            'seed_preparation_nursery' => 2,
            'transplanting_seeding' => 3,
            'watering_irrigation' => 4,
            'fertilization' => 5,
            'weeding' => 6,
            'pest_disease_management' => 7,
            'panicle_flowering_care' => 8,
            'preharvest_drainage' => 9,
            'harvesting' => 10,
        ];

        $logs = ProgressLog::all();
        $updated = 0;

        foreach ($logs as $log) {
            $correctStage = $activityToGrowthStage[$log->activity_type] ?? null;
            
            if ($correctStage && $log->growth_stage != $correctStage) {
                $log->update(['growth_stage' => $correctStage]);
                $updated++;
                $this->info("Updated Progress Log ID {$log->id}: {$log->activity_type} -> Growth Stage {$correctStage}");
            }
        }

        $this->info("\nTotal updated: {$updated} progress logs");
    }
}
