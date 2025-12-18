<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Crop;

// The actual existing image file
$existingImage = 'crops/dGk5YbwQOEIYEYvNW19PNTSdpzTyqxtXFd3yKW0i.jpg';

// Update all crops with status 'harvested' that don't have an image or have the non-existent harvested.jpg
$harvestedCrops = Crop::where('status', 'harvested')
    ->where(function($query) {
        $query->whereNull('harvest_image')
              ->orWhere('harvest_image', 'crops/harvested.jpg');
    })
    ->get();

echo "Found " . $harvestedCrops->count() . " harvested crops to update\n";

foreach ($harvestedCrops as $crop) {
    $crop->update(['harvest_image' => $existingImage]);
    echo "✓ Updated: " . $crop->crop_name . " (ID: " . $crop->id . ")\n";
}

echo "\nDone! All harvested crops now reference the existing image file.\n";
?>
