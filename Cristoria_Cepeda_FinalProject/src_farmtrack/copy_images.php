<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Crop;
use Illuminate\Support\Facades\Storage;

$sourceFile = 'storage/app/public/crops/dGk5YbwQOEIYEYvNW19PNTSdpzTyqxtXFd3yKW0i.jpg';
$sourceImage = 'crops/dGk5YbwQOEIYEYvNW19PNTSdpzTyqxtXFd3yKW0i.jpg';

if (!file_exists($sourceFile)) {
    echo "Source file not found at: $sourceFile\n";
    exit(1);
}

echo "Source file found\n";

$cropsWithoutImage = Crop::whereNull('harvest_image')->get();
echo "Found " . $cropsWithoutImage->count() . " crops without images\n";

$count = 0;
foreach ($cropsWithoutImage as $crop) {
    $newFilename = 'crops/harvest_' . $crop->id . '.jpg';
    $newPath = 'storage/app/public/' . $newFilename;
    
    if (copy($sourceFile, $newPath)) {
        $crop->update(['harvest_image' => $newFilename]);
        echo "✓ Copied to: " . $crop->crop_name . " (ID: " . $crop->id . ")\n";
        $count++;
    } else {
        echo "✗ Failed to copy for: " . $crop->crop_name . "\n";
    }
}

echo "Successfully copied $count images\n";
