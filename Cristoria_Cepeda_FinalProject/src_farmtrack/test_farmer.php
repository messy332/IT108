<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$farmer = \App\Models\Farmer::whereNotNull('user_id')->first();
if ($farmer) {
    echo "ID: " . $farmer->id . "\n";
    echo "Name: " . $farmer->name . "\n";
    echo "Birthdate: " . ($farmer->birthdate ? $farmer->birthdate->format('Y-m-d') : 'NULL') . "\n";
    echo "Address: " . ($farmer->address ?? 'NULL') . "\n";
    echo "Phone: " . ($farmer->phone ?? 'NULL') . "\n";
    echo "Gender: " . ($farmer->gender ?? 'NULL') . "\n";
    echo "Latitude: " . ($farmer->latitude ?? 'NULL') . "\n";
    echo "Longitude: " . ($farmer->longitude ?? 'NULL') . "\n";
} else {
    echo "No farmer with user_id found\n";
}
