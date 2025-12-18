<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Farmer;

class AddFarmerLocationsSeeder extends Seeder
{
    public function run(): void
    {
        // Sample location: Philippines - Central Luzon rice farming area
        $farmers = Farmer::all();
        
        foreach ($farmers as $index => $farmer) {
            // Base coordinates for Central Luzon (Nueva Ecija - Rice Granary of the Philippines)
            $baseLat = 15.5784;
            $baseLng = 120.9842;
            
            // Add slight offset for each farmer (keep within valid range)
            $offset = ($index % 100) * 0.01; // Limit offset to prevent out of range
            $lat = $baseLat + $offset;
            $lng = $baseLng + $offset;
            
            // Create sample farm boundaries (rectangular polygon)
            $farmBoundaries = [
                ['lat' => $lat, 'lng' => $lng],
                ['lat' => $lat + 0.002, 'lng' => $lng],
                ['lat' => $lat + 0.002, 'lng' => $lng + 0.003],
                ['lat' => $lat, 'lng' => $lng + 0.003],
            ];
            
            $farmer->update([
                'latitude' => $lat,
                'longitude' => $lng,
                'farm_boundaries' => $farmBoundaries,
            ]);
        }
        
        $this->command->info('Added location data to ' . $farmers->count() . ' farmers.');
    }
}
