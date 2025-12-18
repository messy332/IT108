<?php

namespace Database\Seeders;

use App\Models\Crop;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AssignHarvestImageToCompletedCropsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Assigns harvest.jpg to all crops where expected_harvest_date has passed
     */
    public function run(): void
    {
        // Use the existing image file
        $harvestImage = 'crops/dGk5YbwQOEIYEYvNW19PNTSdpzTyqxtXFd3yKW0i.jpg';

        // Find all crops where:
        // 1. Have actual_harvest_date OR actual_yield filled in (harvested)
        // 2. Don't already have a harvest image
        $harvestedCrops = Crop::where(function($query) {
            $query->whereNotNull('actual_harvest_date')
                  ->orWhereNotNull('actual_yield');
        })
        ->whereNull('harvest_image')
        ->get();

        $this->command->info('Found ' . $harvestedCrops->count() . ' harvested crops without proof image');

        foreach ($harvestedCrops as $crop) {
            $crop->update(['harvest_image' => $harvestImage]);
            $this->command->line('✓ Assigned proof image to: ' . $crop->crop_name . ' (Farmer: ' . $crop->farmer->name . ')');
        }

        $this->command->info('Successfully assigned proof images to all harvested crops!');
    }
}
