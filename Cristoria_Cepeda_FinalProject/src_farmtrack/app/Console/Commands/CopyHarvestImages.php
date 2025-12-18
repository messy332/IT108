<?php

namespace App\Console\Commands;

use App\Models\Crop;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CopyHarvestImages extends Command
{
    protected $signature = 'crops:copy-harvest-images';
    protected $description = 'Copy harvest image from Johnson Nolan to all crops without proof images';

    public function handle()
    {
        $sourceImage = 'crops/harvest.jpg';
        
        if (!Storage::disk('public')->exists($sourceImage)) {
            $this->error('Source image not found: ' . $sourceImage);
            return 1;
        }

        $cropsWithoutImage = Crop::whereNull('harvest_image')->get();
        $this->info('Found ' . $cropsWithoutImage->count() . ' crops without proof images');

        foreach ($cropsWithoutImage as $crop) {
            $newFilename = 'crops/' . uniqid() . '_' . time() . '.jpg';
            $sourceContent = Storage::disk('public')->get($sourceImage);
            Storage::disk('public')->put($newFilename, $sourceContent);
            $crop->update(['harvest_image' => $newFilename]);
            $this->line('✓ Copied image to: ' . $crop->crop_name . ' (ID: ' . $crop->id . ')');
        }

        $this->info('Successfully copied harvest images to all crops!');
        return 0;
    }
}
