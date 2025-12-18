<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Crop;

return new class extends Migration
{
    public function up(): void
    {
        // Update all crops without harvest_image to use the source image
        $sourceImage = 'crops/dGk5YbwQOEIYEYvNW19PNTSdpzTyqxtXFd3yKW0i.jpg';
        
        $crops = Crop::whereNull('harvest_image')->get();
        
        foreach ($crops as $crop) {
            $newFilename = 'crops/harvest_' . $crop->id . '.jpg';
            $crop->update(['harvest_image' => $newFilename]);
        }
    }

    public function down(): void
    {
        // Revert by setting harvest_image to null
        Crop::whereNotNull('harvest_image')->update(['harvest_image' => null]);
    }
};
