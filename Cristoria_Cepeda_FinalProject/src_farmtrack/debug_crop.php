<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Crop;
use Carbon\Carbon;

$crop = Crop::find(10752);

if (!$crop) {
    echo "Crop not found\n";
    exit;
}

echo "=== Crop ID 10752 ===\n";
echo "Name: " . $crop->crop_name . "\n";
echo "Farmer: " . $crop->farmer->name . "\n";
echo "Status: " . $crop->status . "\n";
echo "Planting Date: " . $crop->planting_date->format('Y-m-d') . "\n";
echo "Expected Harvest Date: " . $crop->expected_harvest_date->format('Y-m-d') . "\n";
echo "Actual Harvest Date: " . ($crop->actual_harvest_date ? $crop->actual_harvest_date->format('Y-m-d') : 'NULL') . "\n";
echo "Actual Yield: " . ($crop->actual_yield ?? 'NULL') . "\n";
echo "Harvest Image: " . ($crop->harvest_image ?? 'NULL') . "\n";

$today = Carbon::now()->startOfDay();
echo "\nToday: " . $today->format('Y-m-d') . "\n";
echo "Expected Harvest <= Today: " . ($crop->expected_harvest_date <= $today ? 'YES' : 'NO') . "\n";

// Check if file exists
$harvestedFile = 'storage/app/public/crops/harvested.jpg';
echo "\nharvested.jpg exists: " . (file_exists($harvestedFile) ? 'YES' : 'NO') . "\n";

// Check all crops with status harvested
echo "\n=== All Harvested Crops ===\n";
$harvestedCrops = Crop::where('status', 'harvested')->get();
echo "Total harvested crops: " . $harvestedCrops->count() . "\n";

foreach ($harvestedCrops as $c) {
    echo "- " . $c->crop_name . " (ID: " . $c->id . ") - Harvest Image: " . ($c->harvest_image ?? 'NULL') . "\n";
}
?>
