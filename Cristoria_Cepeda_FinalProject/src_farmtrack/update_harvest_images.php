<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Crop;

$crops = Crop::whereNull('harvest_image')->get();
foreach ($crops as $crop) {
    $crop->update(['harvest_image' => 'crops/dGk5YbwQOEIYEYvNW19PNTSdpzTyqxtXFd3yKW0i.jpg']);
}

echo "Updated " . $crops->count() . " crops with harvest image\n";
?>
