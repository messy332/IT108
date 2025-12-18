<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Crop;

// Update ALL crops with status 'harvested' to have the harvested.jpg image
$harvestedCrops = Crop::where('status', 'harvested')
    ->whereNull('harvest_image')
    ->get();

echo "Found " . $harvestedCrops->count() . " harvested crops without image\n";

foreach ($harvestedCrops as $crop) {
    $crop->update(['harvest_image' => 'crops/harvested.jpg']);
    echo "✓ Updated: " . $crop->crop_name . " (ID: " . $crop->id . ")\n";
}

echo "\nDone!\n";
?>
