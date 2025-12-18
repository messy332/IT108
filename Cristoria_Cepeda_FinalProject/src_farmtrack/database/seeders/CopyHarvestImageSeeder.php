<?php

namespace Database\Seeders;

use App\Models\Crop;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CopyHarvestImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the source image from Johnson Nolan's crop
        $sourceImage = 'crops/dGk5YbwQOEIYEYvNW19PNTSdpzTyqxtXFd3yKW0i.jpg';
        
        // Check if source image exists
        if (!Storage::disk('public')->exists($sourceImage)) {
            $this->command->error('Source image not found: ' . $sourceImage);
            return;
        }

        // Get all crops without harvest_image
        $cropsWithoutImage = Crop::whereNull('harvest_image')->get();

        $this->command->info('Found ' . $cropsWithoutImage->count() . ' crops without proof images');

        foreach ($cropsWithoutImage as $crop) {
            // Generate a unique filename for each copy
            $newFilename = 'crops/' . uniqid() . '_' . time() . '.jpg';
            
            // Copy the image
            $sourceContent = Storage::disk('public')->get($sourceImage);
            Storage::disk('public')->put($newFilename, $sourceContent);
            
            // Update the crop with the new image path
            $crop->update(['harvest_image' => $newFilename]);
            
            $this->command->info('Copied image to crop: ' . $crop->crop_name . ' (ID: ' . $crop->id . ')');
        }

        $this->command->info('Successfully copied harvest images to all crops!');
    }
}
