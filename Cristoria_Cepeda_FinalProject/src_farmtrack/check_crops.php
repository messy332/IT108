<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Crop;
use Carbon\Carbon;

$today = Carbon::now()->startOfDay();

// Check crops with expected_harvest_date passed
$completedCrops = Crop::where('expected_harvest_date', '<=', $today)->get();

echo "=== Crops with Expected Harvest Date Passed ===\n";
echo "Total: " . $completedCrops->count() . "\n\n";

foreach ($completedCrops as $crop) {
    echo "Crop: " . $crop->crop_name . "\n";
    echo "  Farmer: " . $crop->farmer->name . "\n";
    echo "  Expected Harvest: " . $crop->expected_harvest_date->format('Y-m-d') . "\n";
    echo "  Harvest Image: " . ($crop->harvest_image ?? 'None') . "\n";
    echo "\n";
}

// Check if harvest.jpg file exists
$harvestFile = 'storage/app/public/crops/harvest.jpg';
echo "=== File Check ===\n";
echo "harvest.jpg exists: " . (file_exists($harvestFile) ? 'YES' : 'NO') . "\n";

$sourceFile = 'storage/app/public/crops/dGk5YbwQOEIYEYvNW19PNTSdpzTyqxtXFd3yKW0i.jpg';
echo "Source file exists: " . (file_exists($sourceFile) ? 'YES' : 'NO') . "\n";

if (file_exists($sourceFile) && !file_exists($harvestFile)) {
    echo "\nAttempting to create harvest.jpg...\n";
    if (copy($sourceFile, $harvestFile)) {
        echo "✓ Successfully created harvest.jpg\n";
    } else {
        echo "✗ Failed to create harvest.jpg\n";
    }
}
?>
