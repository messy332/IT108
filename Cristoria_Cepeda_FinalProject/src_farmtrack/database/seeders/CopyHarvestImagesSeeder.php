<?php

namespace Database\Seeders;

use App\Models\Crop;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CopyHarvestImagesSeeder extends Seeder
{
    public function run(): void
    {
        $sourceImage = 'crops/harvest.jpg';
        
        if (!Storage::disk('public')->exists($sourceImage)) {
            echo "Source image not found\n";
            return;
        }

        $cropsWithoutImage = Crop::whereNull('harvest_image')->get();
        echo "Found " . $cropsWithoutImage->count() . " crops without images\n";

        foreach ($cropsWithoutImage as $crop) {
            $newFilename = 'crops/' . uniqid() . '_' . time() . '.jpg';
            $sourceContent = Storage::disk('public')->get($sourceImage);
            Storage::disk('public')->put($newFilename, $sourceContent);
            $crop->update(['harvest_image' => $newFilename]);
            echo "Copied to: " . $crop->crop_name . "\n";
            usleep(100000); // Small delay to ensure unique filenames
        }

        echo "Done!\n";
    }
}
