<?php

namespace Database\Seeders;

use App\Models\Crop;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ResetCropStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $crops = Crop::all();

        foreach ($crops as $crop) {
            // If both actual harvest date AND actual yield are filled, status is 'harvested'
            if (!empty($crop->actual_harvest_date) && !empty($crop->actual_yield)) {
                $crop->status = 'harvested';
            }
            // If expected harvest date has passed and no actual harvest/yield, status is 'failed'
            elseif (!empty($crop->expected_harvest_date) && 
                    Carbon::parse($crop->expected_harvest_date)->isPast() &&
                    empty($crop->actual_harvest_date) && 
                    empty($crop->actual_yield)) {
                $crop->status = 'failed';
            }
            // Default to 'planted'
            else {
                $crop->status = 'planted';
            }

            $crop->save();
        }

        $this->command->info('Crop statuses have been reset based on the corrected logic.');
    }
}
