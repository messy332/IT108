<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Crop;
use Carbon\Carbon;

// The actual existing image file
$existingImage = 'crops/harvested.jpg';

// Find all crops with harvest_image set to 'crops/harvest.jpg' or null (which don't have the correct image)
$cropsWithoutImage = Crop::where('expected_harvest_date', '<=', Carbon::now()->startOfDay())
    ->whereNull('harvest_image')
    ->get();

echo "Found " . $cropsWithoutImage->count() . " completed crops without harvest image\n";

// Update them to use the existing image
foreach ($cropsWithoutImage as $crop) {
    $crop->update(['harvest_image' => $existingImage]);
    echo "✓ Assigned: " . $crop->crop_name . " (Farmer: " . $crop->farmer->name . ")\n";
}

echo "\nDone! All crops now reference the existing image file.\n";
?>
