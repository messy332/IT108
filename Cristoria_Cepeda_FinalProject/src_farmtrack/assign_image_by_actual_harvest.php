<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Crop;

$existingImage = 'crops/dGk5YbwQOEIYEYvNW19PNTSdpzTyqxtXFd3yKW0i.jpg';

// Assign image to crops that have actual_harvest_date OR actual_yield filled in
$cropsWithHarvest = Crop::where(function($query) {
    $query->whereNotNull('actual_harvest_date')
          ->orWhereNotNull('actual_yield');
})
->whereNull('harvest_image')
->get();

echo "Found " . $cropsWithHarvest->count() . " crops with harvest data\n";

foreach ($cropsWithHarvest as $crop) {
    $crop->update(['harvest_image' => $existingImage]);
    echo "✓ " . $crop->crop_name . " (ID: " . $crop->id . ")\n";
}

echo "\nDone!\n";
?>
